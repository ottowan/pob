<?php

class DBUtil{
    public static function getLimitedTablename($table){
      global $PNConfig;
      $_PNPREFIX = $PNConfig['System']['prefix'] ;
      return $_PNPREFIX . '_' . $table;
    }

     /**
     * Convenience function to ensure that the where-clause starts with "WHERE"
     *
     * @param where        The original where clause
     *
     * @return The value held by the global counter
     */
    function _checkWhereClause ($where)
    {
        if (!strlen(trim($where))) {
            return $where;
        }

        $where = trim ($where);
        $upwhere = strtoupper($where);
        if (strstr($upwhere, 'WHERE') === false || strpos($upwhere, 'WHERE') > 1) {
            $where = 'WHERE ' . $where;
        }

        return $where;
    }

/**
     * Convenience function to ensure that the order-by-clause starts with "ORDER BY"
     *
     * @param orderby    The original order-by clause
     * @param tablename  The tablename key for the PNTables structure, only used for oracle quote determination (optional) (default=null)
     *
     * @return The (potenitally) altered order-by-clause
     */
    function _checkOrderByClause ($orderby, $tablename=null)
    {
        if (!strlen(trim($orderby))) {
            return $orderby;
        }
        global $PNConfig;
        $dbType = $PNConfig['DBInfo']['default']['dbtype'];

        // given that we use quotes in our generated SQL, oracle requires the same quotes in the order-by
        if ($dbType=='oci8' || $dbType=='oracle') {
            $t = str_replace ('ORDER BY ', '', $orderby);        // remove "ORDER BY" for easier parsing
            $t = str_replace ('order by ', '', $t);              // remove "order by" for easier parsing

            $pntables = ypcore_getTable();
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

            $orderby = implode (', ', $tokens);
        }

        if (stristr($orderby, 'ORDER BY') === false) {
            $orderby = 'ORDER BY ' . $orderby;
        }

        return $orderby;
    }
    /**
     * Same as PN Api function but without AS aliasing
     *
     * @param tablename      The tablename key for the PNTables structure
     * @param columnArray    The columns to marshall into the resulting object (optional) (default=null)
     *
     * @return The generated sql string
     */
    function _getAllColumns ($tablename, $columnArray=null)
    {
        $pntables = ypcore_getTable();
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

        if (!$queries && $columnArray) {
            return pn_exit ("Empty query generated for [$tablename] filtered by columnArray: ");
        }

        return implode (',', $queries);
    }
    function _getSelectAllColumnsFrom ($tablename, $where='', $orderBy='', $columnArray=null)
    {
        $pntables = ypcore_getTable();
        $table    = $pntables[$tablename];

        $query = 'SELECT ' . DBUtil::_getAllColumns($tablename, $columnArray) . " FROM $table ";

        if (trim($where)) {
            $query .= DBUtil::_checkWhereClause ($where) . ' ';
        }

        if (trim($orderBy)) {
            $query .= DBUtil::_checkOrderByClause ($orderBy, $tablename) . ' ';
        }

        return $query;
    }
}