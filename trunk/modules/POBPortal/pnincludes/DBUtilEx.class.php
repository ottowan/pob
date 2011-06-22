<?php
/**
* class DBUtilEx the Database utility extendsion 
* @authur Chayakon PONGSIRI
* @package InnoCoastalDB
*/
class DBUtilEx extends DBUtil{
     /**
     * [spw v1.0] modify insertObject to do not force insert 0 (leave NULL) when data type is number
     *            [interger , real , float , double]
     *
     * Generate and execute an insert SQL statement for the given object
     *
     * @param object        The object we wish to insert
     * @param tablename     The tablename key for the PNTables structure
     * @param idcolumn      The column which stores the primary key (optional) (default='id')
     * @param preserve      whether or not to preserve existing/set standard fields (optional) (default=false)
     * @param force         whether or not to insert empty values as NULL (optional) (default=false)
     *
     * @return The result set from the update operation. The object is updated with the newly generated ID.
     */
    function insertObject (&$object, $tablename, $idcolumn='id', $preserve=false, $force=false)
    {
        if (!is_array ($object)) {
            return pn_exit ('DBUtil::insertObject: object is not an array ... ');
        }

        $dbconn   = DBConnectionStack::getConnection();
        $pntables = pnDBGetTables();
        $table    = $pntables[$tablename];
        $sql      = "INSERT INTO $table ";

        // set standard architecture fields
        //[spw v2.0] force insert id
        ObjectUtilEx::setStandardFieldsOnObjectCreate ($object, $preserve, $idcolumn);

        // build the column list
        //$obj_id = $dbconn->GenID($pntables[$tablename]);
        $column = $pntables["{$tablename}_column"];
        if (!is_array ($column)) {
            return pn_exit ("DBUtil::insertObject: [$tablename]_column is not an array ... ");
        }

        // When explicityly inserting an autoincrement value, MSSQL server needs to be told to allow this.
        // This property can only be set once per table and can only be set on 1 table at a time.
        $dbType = DBConnectionStack::getConnectionDBType();
        static $identityInsertTbl = null;
        if ($dbType=='mssql' && isset($object[$idcolumn]) && $object[$idcolumn]) {
            if (!$identityInsertTbl || $identityInsertTbl != $table) {
                // TODO/FIXME: for some reason the data dictionary on mssql seems to be slightly broken and
                // returns field information without the AUTO specification while also generating an
                // include file error to the mssql datadict include file (which is in the correct place).

                //$colType = DBUtil::metaColumnType($tablename, $idcolumn, true);
                //if (strpos($colType, ' AUTO') !== false) { // ensure that this is set only for autoincrement fields
                    DBUtil::executeSQL ("SET IDENTITY_INSERT $table ON", -1, -1, false, false);
                    $identityInsertTbl = $table;
                //}
            }
        }

        // grab each key and value and append to the sql string
        $clobArray = array();
        $cArray = array();
        $vArray = array();
        foreach ($column as $key => $val) {
            if (isset($object[$key])) {

                $skip    = false;
                $save    = false;
                $colType = DBUtil::metaColumnType($tablename, $key);

                // oracle specific stuff
                if ($dbType=='oci8' || $dbType=='oracle') {
                    $save = null;
                    // oracle treats an empty string as NULL -> hack to avoid NULL for empty strings
                    if ($object[$key]==='' && ($colType=='C' || $colType=='X')) {
                        $save = $object[$key];
                        $object[$key] = '""';
                    }
                    // oracle needs special treatment of CLOB columns
                    elseif ($colType=='XL') {
                        $skip = true;
                        $clobArray[$column[$key]] = DataUtil::formatForStore((string)$object[$key]);
                    }
                }
                else
                // mssql doesn't understand DATEFORMAT_FIXED, we have to convert
                if ($dbType=='mssql' && $colType=='T') {
                    $save = $object[$key];
                    $object[$key] = DateUtil::formatDatetime ($object[$key], '%Y%m%d %H:%M:%S');
                }
                //[spw v1.0]
                // generate the actual insert values
                if (!$skip) {
                    if (empty($object[$key]) &&
                        ($colType=='F'||$colType=='I'||$colType=='I1'||
                         $colType=='I2'||$colType=='I4'||$colType=='I8'||$colType=='R')){
                      //nothing
                    }else{
                      $cArray[] = $column[$key];
                      $vArray[] = DBUtil::_formatForStore($object[$key]);
                    }
                }

                // for oracle empty strings restore original value
                if (($dbType=='oci8' || $dbType=='oracle') && $save==='' && ($colType=='C' || $colType=='X')) {
                    $object[$key] = $save;
                }
                else
                // for mssql dates restore original value
                if ($dbType=='mssql' && $colType=='T') {
                    $object[$key] = $save;
                }

                // ensure that international float numbers are stored with '.' rather than ',' for decimal separator
                if ($colType=='F' || $colType=='N') {
                    if (is_float($object[$key]) || is_double($object[$key])) {
                        $object[$key] = number_format($object[$key], 8, '.', '');
                    }
                }
            }
            else {
                if ($key == $idcolumn) {
                    if ($dbType == 'postgres') {
                        $cArray[] = $column[$key];
                        $vArray[] = 'DEFAULT';
                    }
                }
                else
                if ($force) {
                    $cArray[] = $column[$key];
                    $vArray[] = 'NULL';
                }
            }
        }

        if ($cArray && $vArray) {
            $sql .= '(';
            $sql .= implode(',', $cArray);
            $sql .= ')';
            $sql .= 'VALUES (';
            $sql .= implode(',', $vArray);
            $sql .= ')';

            $res = DBUtil::executeSQL ($sql);
            if ($res === false) {
                return $res;
            }
        } else {
            return pn_exit ('DBUtil::insertObject: unable to find anything to insert in supplied object ... ');
        }

        if ((!$preserve || !isset($object[$idcolumn])) && isset($column[$idcolumn])) {
            $obj_id = DBUtil::getInsertID ($tablename, $idcolumn);
            $object[$idcolumn] = $obj_id;
        }

        if ($clobArray) {
            $res = DBUtil::_handleClobFields ($tablename, $object, $clobArray, $idcolumn);
        }

        if (!DBConnectionStack::isDefaultConnection()) {
            return $object;
        }

        if ($cArray && $vArray) {
             $object = DBUtil::_savePostProcess ($object, $tablename, $idcolumn);
        }

        return $object;
    }


    /**
     * [spw v1.0] modify insertObject to do not force insert 0 (leave NULL) when data type is number
     *            [interger , real , float , double]
     *
     * Generate and execute an update SQL statement for the given object
     *
     * @param object        The object we wish to update
     * @param tablename     The tablename key for the PNTables structure
     * @param where         The where clause (optional) (default='')
     * @param idcolumn      The column which stores the primary key (optional) (default='id')
     * @param force         whether or not to insert empty values as NULL (optional) (default=false)
     * @param updateid      Allow primary key to be updated (default=false)
     *
     * @return The result set from the update operation
     */
    function updateObject (&$object, $tablename, $where='', $idcolumn='id', $force=false, $updateid=false)
    {
        if (!is_array ($object)) {
            return pn_exit ('DBUtil::updateObject: object is not an array ... ');
        }

        if (!isset($object[$idcolumn]) && !$where) {
            return pn_exit ('DBUtil::updateObject: no object ID and no where ... ');
        }

        $pntables = pnDBGetTables();
        $sql      = "UPDATE $pntables[$tablename] SET ";

        // set standard architecture fields
        ObjectUtilEx::setStandardFieldsOnObjectUpdate ($object, $force);

        // grab each key and value and append to the sql string
        $clobArray = array();
        $tArray = array();
        $column = $pntables["{$tablename}_column"];
        $dbType = DBConnectionStack::getConnectionDBType();
        foreach ($column as $key => $val) {
            if ($key != $idcolumn || ($key == $idcolumn && $updateid == true)) {
                if ($force || array_key_exists($key,$object)) {

                    $skip    = false;
                    $colType = DBUtil::metaColumnType($tablename, $key);

                    // oracle specific stuff
                    if ($dbType=='oci8' || $dbType=='oracle') {
                        $save = null;
                        // oracle treats an empty string as NULL -> hack to avoid NULL for empty strings
                        if ($object[$key]==='' && ($colType=='C' || $colType=='X')) {
                            $save = $object[$key];
                            $object[$key] = '""';
                        }
                        // oracle needs special treatment of CLOB columns
                        elseif ($colType=='XL') {
                            $skip = true;
                            $clobArray[$column[$key]] = DataUtil::formatForStore((string)$object[$key]);
                        }
                    }
                    else
                    // mssql doesn't understand DATEFORMAT_FIXED, we have to convert
                    if ($dbType=='mssql' && $colType=='T') {
                        $save = $object[$key];
                        $object[$key] = DateUtil::formatDatetime ($object[$key], '%Y%m%d %H:%M:%S');
                    }
                    //[spw v1.0]
                    // generate the actual update values
                    if (!$skip) {
                      if (empty($object[$key]) &&
                        ($colType=='F'||$colType=='I'||$colType=='I1'||
                         $colType=='I2'||$colType=='I4'||$colType=='I8'||$colType=='R')){
                        //nothing
                      }else{
                        $tArray[] = "$val=" . (isset($object[$key]) ? DBUtil::_formatForStore($object[$key]) : 'NULL');
                      }                       
                    }

                    // for oracle empty strings restore original value
                    if (($dbType=='oci8' || $dbType=='oracle') && $save==='' && ($colType=='C' || $colType=='X')) {
                        $object[$key] = $save;
                    }
                    else
                    // for mssql dates restore original value
                    if ($dbType=='mssql' && $colType=='T') {
                        $object[$key] = $save;
                    }

                    // ensure that international float numbers are stored with '.' rather than ',' for decimal separator
                    if ($colType=='F' || $colType=='N') {
                        if (is_float($object[$key]) || is_double($object[$key])) {
                            $object[$key] = number_format($object[$key], 8, '.', '');
                        }
                    }
                }
            }
        }

        if ($tArray) {
            if (!$where) {
                $_where = " WHERE $column[$idcolumn]='" . DataUtil::formatForStore($object[$idcolumn]) . "'";
            } else {
                $_where = DBUtil::_checkWhereClause ($where);
            }

            $sql .= implode(',', $tArray) . " $_where";

            $res = DBUtil::executeSQL ($sql);
            if ($res === false) {
                return $res;
            }
        }

        if ($clobArray) {
            // This section commented out - see patch [#4364] DBUtil fix for explanation
            // since a where clause may not correspond to the acutal ID, we have to fetch the ID here
            //if ($where) {
            //    $id  = DBUtil::selectField ($tablename, $idcolumn, $where);
            //    $object[$idcolumn] = $id;
            //}
            $res = DBUtil::_handleClobFields ($tablename, $object, $clobArray, $idcolumn);
        }

        if (!DBConnectionStack::isDefaultConnection()) {
            return $object;
        }

        $object = DBUtil::_savePostProcess ($object, $tablename, $idcolumn, true);

        return $object;
    }

    /**
     * return the column array for the given table
     *
     * @param tablename      The tablename key for the PNTables structure
     * @param columnArray    The columns to marshall into the resulting object (optional) (default=null)
     * @param columnExArray  The columns extendsion to marshall into the resulting object (optional) (default=null)
     *
     * @return The column array for the given table
     */
    function getColumnsArray ($tablename, $columnArray=null , $columnExArray=null)
    {
        $pntables = pnDBGetTables();
        $tkey     = $tablename . '_column';
        $ca       = array ();

        if (!isset($pntables[$tkey])) {
            return $ca;
        }

        $cols = $pntables[$tkey];
        foreach ($cols as $key => $val)
        {
            // since the key is plain name, we take it rather
            // than the value to construct object fields from
            //$ca[] = $column[$key];
            if (!$columnArray || in_array($key, $columnArray)) {
                $ca[] = $key;
            }
        }
        //merge column extendsion
        if (is_array($columnExArray)){
          foreach($columnExArray as $key => $val){
            //if not number, then add key
            if (!is_int($key)){
                    $ca[] = $key;
            }
          }
        }

        if (!$ca && $columnArray) {
            return pn_exit ("Empty column array generated for [$tablename] filtered by columnArray: ");
        }

        return $ca;
    }
    /**
     * Build a basic select clause for the specified table with the specified where and orderBy clause
     *
     * @param tablename      The tablename key for the PNTables structure
     * @param where          The original where clause (optional) (default='')
     * @param orderBy        The original order-by clause (optional) (default='')
     * @param columnArray    The columns to marshall into the resulting object (optional) (default=null)
     *
     * @return Nothing, the order-by-clause is altered in place
     */
    function _getSelectAllColumnsFrom ($tablename, $where='', $orderBy='', $groupBy ='' , $columnArray=null,$columnExArray=null)
    {
        $pntables = pnDBGetTables();
        $table    = $pntables[$tablename];

        $query = 'SELECT ' . DBUtilEx::_getAllColumns($tablename, $columnArray,$columnExArray) . " FROM $table ";

        if (trim($where)) {
            $query .= DBUtilEx::_checkWhereClause ($where) . ' ';
        }
        if (trim($groupBy)) {
            $query .= DBUtilEx::_checkGroupByClause ($groupBy, $tablename) . ' ';
        }

        if (trim($orderBy)) {
            $query .= DBUtilEx::_checkOrderByClause ($orderBy, $tablename) . ' ';
        }

        return $query;
    }

    /**
     * Same as PN Api function but without AS aliasing
     *
     * @param tablename      The tablename key for the PNTables structure
     * @param columnArray    The columns to marshall into the resulting object (optional) (default=null)
     *
     * @return The generated sql string
     */
    function _getAllColumns ($tablename, $columnArray=null,$columnExArray=null)
    {
        $pntables = pnDBGetTables();
        $columns  = $pntables["{$tablename}_column"];
        $queries  = array();

        if (!$columns) {
            return pn_exit ("Invalid table-key [$tablename] passed to _getAllColumns");
        }

        $query = '';
        foreach ($columns as $key => $val) {
            if (!$columnArray || in_array($key, $columnArray)) {
                $queries[] = "$val AS \"$key\"";
            }
        }
        //merge column extendsion
        if (is_array($columnExArray)){
          foreach($columnExArray as $key => $val){
            //if not number, then add key
            if (!is_int($key)){
                    $queries[] = "$val AS \"$key\"";
            }
          }
        }
        if (!$queries && $columnArray) {
            return pn_exit ("Empty query generated for [$tablename] filtered by columnArray: ");
        }

        return implode (',', $queries);
    }
    /**
     * Select & return an object array based on a PN table definition
     *
     * @param tablename      The tablename key for the PNTables structure
     * @param where          The where clause (optional) (default='')
     * @param orderby        The order by clause (optional) (default='')
     * @param limitOffset    The lower limit bound (optional) (default=-1)
     * @param limitNumRows   The upper limit bound (optional) (default=-1)
     * @param assocKey       The key field to use to build the associative index (optional) (default='')
     * @param permissionFilter The permission filter to use for permission checking (optional) (default=null)
     * @param categoryFilter   The category list to use for filtering (optional) (default=null)
     * @param columnArray    The columns to marshall into the resulting object (optional) (default=null)
     *
     * @return The resulting object array
     */
    function selectObjectArray ($tablename, $where='', $orderby='', $limitOffset=-1, $limitNumRows=-1,
                                $assocKey='', $permissionFilter=null, $categoryFilter=null, $columnArray=null,
                                $columnExArray=null,$groupby=null)
    {
        DBUtilEx::_setFetchedObjectCount (0);
        DBUtilEx::_setMarshalledObjectCount (0);

        $where   = DBUtilEx::generateCategoryFilterWhere ($tablename, $where, $categoryFilter);
        $where   = DBUtilEx::_checkWhereClause ($where);
        $orderby = DBUtilEx::_checkOrderByClause ($orderby, $tablename);
        $groupby = DBUtilEx::_checkGroupByClause  ($groupby, $tablename);

        $objects = array();
        //[spw] add query field for column extendsion
        $ca      = DBUtilEx::getColumnsArray ($tablename, $columnArray,$columnExArray);
        $sql     = DBUtilEx::_getSelectAllColumnsFrom($tablename, $where, $orderby, $groupby, $columnArray,$columnExArray);

        do
        {
            $fc   = DBUtilEx::_getFetchedObjectCount();
            $stmt = $sql ;
            $limitOffset += $fc;

            $res = DBUtilEx::executeSQL ($stmt, $limitOffset, $limitNumRows);
            if ($res === false) {
                return $res;
            }

            $objArr = DBUtilEx::marshallObjects ($res, $ca, true, $assocKey, true, $permissionFilter, $tablename);
            if ($objArr) {
                $objects = $objects + (array)$objArr; // append new array
            }
        }
        while ($permissionFilter && ($limitNumRows!=-1 && $limitNumRows>0) &&
               $fc>0 && count($objects)<$limitNumRows);

        if ($limitNumRows != -1 && count($objects) > $limitNumRows) {
            $objects = array_slice ($objects, 0, $limitNumRows);
        }

        if (!DBConnectionStack::isDefaultConnection()) {
            return $objects;
        }

        $pntables = pnDBGetTables();
        $idcolumn = isset($pntables["{$tablename}_primary_key_column"]) ? $pntables["{$tablename}_primary_key_column"] : 'id';
        $objects  = DBUtilEx::_selectPostProcess ($objects, $tablename, $idcolumn);

        return $objects;
    }

    /**
     * Select & return an array of objects with it's left join fields filled in
     *
     * @param tablename     The tablename key for the PNTables structure
     * @param joinInfo      The array containing the extended join information
     * @param where         The where clause (optional) (default='')
     * @param orderby       The order by clause (optional) (default='')
     * @param limitOffset   The lower limit bound (optional) (default=-1)
     * @param limitNumRows  The upper limit bound (optional) (default=-1)
     * @param assocKey      The key field to use to build the associative index (optional) (default='')
     * @param permissionFilter  The permission filter to use for permission checking (optional) (default=null)
     * @param columnArray   The columns to marshall into the resulting object (optional) (default=null)
     *
     * @return The resulting object
     */
    function selectExpandedObjectArray ($tablename, $joinInfo, $where='', $orderby='', $limitOffset=-1, $limitNumRows=-1,
                                        $assocKey='', $permissionFilter=null, $categoryFilter=null, $columnArray=null,
                                        $columnExArray=null,$groupby=null)
    {
        DBUtilEx::_setFetchedObjectCount (0);
        DBUtilEx::_setMarshalledObjectCount (0);

        $pntables = pnDBGetTables();
        $columns  = $pntables["{$tablename}_column"];
        $table    = $pntables[$tablename];
        //[spw]
        $sqlStart = "SELECT " . DBUtilEx::_getAllColumnsQualified ($tablename, 'tbl', $columnArray,$columnExArray);
        $sqlFrom  = "FROM $table AS tbl ";
        //[spw]
        $sqlJoinArray = DBUtilEx::_processJoinArray($tablename, $joinInfo, $columnArray,$columnExArray);
        $sqlJoin = $sqlJoinArray[0];
        $sqlJoinFieldList = $sqlJoinArray[1];
        $ca = $sqlJoinArray[2];

        $where = DBUtilEx::generateCategoryFilterWhere ($tablename, $where, $categoryFilter);

        $where   = DBUtilEx::_checkWhereClause ($where);
        $orderby = DBUtilEx::_checkOrderByClause ($orderby, $tablename);
        $groupby = DBUtilEx::_checkGroupByClause  ($groupby, $tablename);
        
        $objects = array();
        $sql = "$sqlStart $sqlJoinFieldList $sqlFrom $sqlJoin $where $groupby $orderby";

        do
        {
            $fc   = DBUtilEx::_getFetchedObjectCount();
            $stmt = $sql;
            $limitOffset += $fc;

            $res = DBUtilEx::executeSQL ($stmt, $limitOffset, $limitNumRows);
            if ($res === false) {
                return $res;
            }

            $objArr  = DBUtilEx::marshallObjects ($res, $ca, true, $assocKey, true, $permissionFilter, $tablename);
            if ($objArr) {
                $objects = $objects + (array)$objArr; // append new array
            }
        }
        while ($permissionFilter && ($limitNumRows!=-1 && $limitNumRows>0) &&
               $fc>0 && count($objects)<$limitNumRows);

        if (count($objects) > $limitNumRows  &&  $limitNumRows>0) {
            $objects = array_slice ($objects, 0, $limitNumRows);
        }

        if (!DBConnectionStack::isDefaultConnection()) {
            return $objects;
        }

        $idcolumn = isset($pntables["{$tablename}_primary_key_column"]) ? $pntables["{$tablename}_primary_key_column"] : 'id';
        $objects  = DBUtilEx::_selectPostProcess ($objects, $tablename, $idcolumn);
        return $objects;
    }
    /**
     * Same as PN Api function but returns fully qualified fieldnames
     *
     * @param tablename      The tablename key for the PNTables structure
     * @param tablealias     The SQL table alias to use in the SQL statement
     * @param columnArray    The columns to marshall into the resulting object (optional) (default=null)
     *
     * @return The generated sql string
     */
    function _getAllColumnsQualified ($tablename, $tablealias, $columnArray=null,$columnExArray=null)
    {
        $pntables = pnDBGetTables();
        $columns  = $pntables["{$tablename}_column"];
        $queries  = array();

        if (!$columns) {
            return pn_exit ("Invalid table-key [$tablename] passed to _getAllColumns");
        }

        foreach ($columns as $key => $val) {
            if (!$columnArray || in_array($key, $columnArray)) {
                $queries[] = "$tablealias.$val AS \"$key\"";
            }
        }
        //[spw] 
        //merge column extendsion
        if (is_array($columnExArray)){
          foreach($columnExArray as $key => $val){
            //if not number, then add key
            if (!is_int($key)){
                    $queries[] = "$val AS \"$key\"";
            }
          }
        }

        if (!$queries && $columnArray) {
            return pn_exit ("Empty query generated for [$tablename] filtered by columnArray: ");
        }

        return implode (',', $queries);
    }
    /**
     * This method creates the necessary sql information for retrieving
     * fields from joined tables defined by a joinInfo array described
     * at the top of this class.
     *
     * @author rgasch
     * @param  tablename    the tablename key for the PNTables structure
     * @param  joinInfo     the array containing the extended join information
     * @param  columnArray  the columns to marshall into the resulting object (optional) (default=null)
     * @return array ($sqlJoin, $sqlJoinFieldList, $ca)
     */
    function _processJoinArray($tablename, $joinInfo, $columnArray=null,$columnExArray=null)
    {
        $pntables = pnDBGetTables();
        $columns  = $pntables["{$tablename}_column"];

        $ca       = DBUtilEx::getColumnsArray ($tablename, $columnArray,$columnExArray);
        $alias    = 'a';
        $sqlJoin  = '';
        $sqlJoinFieldList = '';
        $ak = array_keys($joinInfo);
        foreach ($ak as $k) {
            $jt   = $joinInfo[$k]['join_table'];
            $jf   = $joinInfo[$k]['join_field'];
            $ofn  = $joinInfo[$k]['object_field_name'];
            $cft  = $joinInfo[$k]['compare_field_table'];
            $cfj  = $joinInfo[$k]['compare_field_join'];

            $jtab = $pntables[$jt];
            $jcol = $pntables["{$jt}_column"];

            if (!is_array($jf)) {
                $jf = array ($jf);
            }

            if (!is_array($ofn)) {
                $ofn = array ($ofn);
            }

            // loop over all fields to select from the joined table
            foreach ($jf as $k => $v) {
                $currentColumn = $jcol[$v];
                // attempt to remove encoded table name in column list used by some PN tables
                $t = strstr ($currentColumn, '.');
                if ($t !== false) {
                    $currentColumn = substr ($t, 1);
                }

                $line  = ", $alias.$currentColumn AS $ofn[$k] ";
                $sqlJoinFieldList .= $line;

                $ca[] = $ofn[$k];
            }

            $compareColumn = $jcol[$cfj];
            // attempt to remove encoded table name in column list used by some PN tables
            $t = strstr ($compareColumn, '.');
            if ($t !== false) {
                $compareColumn = substr ($t, 1);
            }

            $t = isset($columns[$cft]) ? "tbl.$columns[$cft]" : $cft; // if not a column reference assume litereal column name
            $line  = " LEFT JOIN $jtab $alias ON $alias.$compareColumn = $t ";

            $sqlJoin .= $line;
            ++$alias;
        }
        return array($sqlJoin, $sqlJoinFieldList, $ca);
    }

    /**
     * Convenience function to ensure that the group-by starts with "GROUP BY"
     *
     * @param groupby    The original groupby
     * @param tablename  The tablename key for the PNTables structure, only used for oracle quote determination (optional) (default=null)
     *
     * @return The (potenitally) altered order-by-clause
     */
    function _checkGroupByClause ($groupby, $tablename=null)
    {
        if (!strlen(trim($groupby))) {
            return $groupby;
        }

        $dbType = DBConnectionStack::getConnectionDBType();

        // given that we use quotes in our generated SQL, oracle requires the same quotes in the order-by
        if ($dbType=='oci8' || $dbType=='oracle') {
            $t = str_replace ('GROUP BY ', '', $groupby);        // remove "ORDER BY" for easier parsing
            $t = str_replace ('group by ', '', $t);              // remove "order by" for easier parsing

            $pntables = pnDBGetTables();
            $columns  = $pntables["{$tablename}_column"];

            // anything which doesn't look like a basic ORDER BY clause (with possibly an ASC/DESC modifier)
            // we don't touch. To use such stuff with Oracle, you'll have to apply the quotes yourself.

            $tokens = explode (',', $t);                         // split on comma
            foreach ($tokens as $k=>$v) {
                $v = trim ($v);
                if (strpos ($v, ' ') === false) {                // 1 word
                    if (strpos ($v, '(') === false) {            // not a function call
                        if (strpos ($v, '"') === false) {        // not surrounded by quotes already
                            if (isset($columns[$v])) {           // ensure that token is an alias
                                $tokens[$k] = '"' . $v . '"';    // surround it by quotes
                            }
                        }
                    }
                }
                else {                                           // multiple words, perform a few basic hecks
                    $ttok = explode (' ', $v);                   // split on space
                    if (count($ttok) == 2) {                     // see if we have 2 tokens
                        $t1 = strtolower(trim($ttok[0]));
                        $t2 = strtolower(trim($ttok[1]));
                        $haveQuotes = strpos ($t1, '"') === false;
                        $isAscDesc  = (strpos($t2, 'asc')===0 || strpos($t2, 'desc')===0);
                        $isColumn   = isset($columns[$ttok[0]]);
                        if ($haveQuotes && $isAscDesc && $isColumn) {
                            $ttok[0] = '"'.$ttok[0].'"';         // surround it by quotes
                        }
                    }
                    $tokens[$k] = implode (' ', $ttok);
                }
            }

            $groupby = implode (', ', $tokens);
        }

        if (stristr($groupby, 'GROUP BY') === false) {
            $groupby = 'GROUP BY ' . $groupby;
        }

        return $groupby;
    }
}