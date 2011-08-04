<?php
/**
 * PNObjectArrayUtil
 *
 * @package CoastalDB
 */
class PNObjectExArray extends PNObjectArray
{
    /**
    * @variable _objColumnExArray for manual query from DB that column not exist on pntable
    * @example
    * <b>NOTE</b> MUST associate as key and value
    *             key is marshall name
    *             value is sql command or field name
    * <code>
    *   $this->_objColumnExArray = array('time' => 'NOW()');
    * </code>
    */
    var $_objColumnExArray = null; 
    /*
    * @variable _objGroup  for group by field name
    */
    var $_objGroup = null; 
    /**
    * @variable _objPermission to check permission before Get() process
    * $_objPermission = array('component' => 'InnoForum::',
                              'instance'  => '::',
                              'level'     => ACCESS_ADMIM);
    */
    var $_objPermission = null;
    /**
    *
    */
    function selectPreProcess(){

    }
    /**
    *
    */
    function selectPostProcess(){

    }
    /*
    * provide multi language for store into database 
    * @param boolean $mergeold  for load old data and merge to new data.
    *                this option use for update process ,optional default false
    */
    function prepareDataForStore($mergeold = false){
        $pntables = pnDBGetTables();
        $languageColumn    = $pntables[$this->_objType . '_language' ];
        //get current lang
        $lang = pnUserGetLang();
        if ($lang){
          //serialize all column
          for($i = 0 ; $i < count($this->_objData) ; $i++){
            foreach($languageColumn as $col){
              if( isset($this->_objData[$i][$col])){
                if ($mergeold && $this->_objData[$i]['id'] && $this->_objType){
                 $olddata = DBUtil::selectFieldByID ($this->_objType, $col, $this->_objData[$i]['id']);
                }

                //try merge with old data
                if ($mergeold){
                  $oldfield = unserialize($olddata);
                  if (!$oldfield){
                    $oldfield = array();
                  }
                  $oldfield[$lang] = $this->_objData[$i][$col];
                  $this->_objData[$i][$col] = serialize($oldfield);
                }else{
                  $newdata = array($lang=>$this->_objData[$col]);
                  $this->_objData[$i][$col] = serialize($newdata);
                }
              }
            }//end for
          }//end for
        }//end if
    }
    /*
    * provide multi language display
    * @access private
    */
    function prepareDataForDisplay(){
        $pntables = pnDBGetTables();
        $languageColumn    = $pntables[$this->_objType . '_language' ];

        //get current lang
        $lang = pnUserGetLang();
        
        if ($lang){
            //loop in join table
            $joinResult = array();
            foreach($this->_objJoin as $join){
              if ($join['join_table']){
                $joinLanguageColumn    = $pntables[$join['join_table'] . '_language' ];
                $field = null;
                if (!is_array($join['join_field'])){
                  $field = array_combine(array($join['object_field_name']),array($join['join_field']));
                }else{
                  $field = array_combine($join['object_field_name'],$join['join_field']);
                }
                $joinResult[] = array_intersect($field, $joinLanguageColumn);
              }
            }//end loop join
            for($i = 0 ; $i < count($this->_objData); $i++){
                //unserialize common column
                foreach($languageColumn as $col){
                    if (isset($this->_objData[$i][$col])){
                        $data = unserialize($this->_objData[$i][$col]);
                        //if unserialize success,else leave unchange
                        if ($data && is_array($data)){
                          $this->_objData[$i][$col] = $data[$lang];
                        }
                    }
                    
                }//end for
                //unreserial join column
                if ($joinResult){
                    foreach($joinResult as $result){
                        foreach($result as $key => $value){
                            if (isset($this->_objData[$i][$key])){
                                $data = unserialize($this->_objData[$i][$key]);
                                if ($data && is_array($data)){
                                  $this->_objData[$i][$key] = $data[$lang];
                                }
                            }
                        }
                    }
                }  
            }//end lang
        }
        
    }
    /**
     * Generic insert handler for an object (ID is inserted into the object data)
     *
     * @return The Object Data
     */
    function insert ()
    {
        $res = true;
        $this->insertPreProcess ();
        foreach ($this->_objData as $k=>$v) {
            $res = $res && DBUtilEx::insertObject ($this->_objData[$k], $this->_objType, $this->_objField);
        }

        if ($res) {
            $this->insertPostProcess ();
            $this->prepareDataForStore();
            return $this->_objData;
        }

        return false;
    }


    /**
     * Generic upate handler for an object
     *
     * @return The Object Data
     */
    function update ()
    {
        $res = true;
        $this->updatePreProcess ();
        foreach ($this->_objData as $k=>$v) {
            $res = $res && DBUtilEx::updateObject ($this->_objData[$k], $this->_objType, '', $this->_objField);
        }

        if ($res) {
            $this->updatePostProcess ();
            return $this->_objData;
        }

        return false;
    }

    /**
     * Generic select handler for an object. Select (and set) the specified object array
     *
     * @param where         The where-clause to use
     * @param orderBy       The order-by clause to use
     * @param limitOffset   The limiting offset
     * @param limitNumRows  The limiting number of rows
     * @param assocKey      Key field to use for building an associative array (optional) (default=null)
     * @param distinct      whether or not to use the distinct clause
     *
     * @return The selected Object-Array
     */
    function select ($where='', $orderBy='', $limitOffset=-1, $limitNumRows=-1, $assocKey=false, $distinct=false)
    {
        $this->selectPreProcess ();
        if ($this->_objJoin) {
            $objArr = DBUtilEx::selectExpandedObjectArray ($this->_objType, $this->_objJoin, $where, $orderBy, $limitOffset, $limitNumRows, $assocKey, $this->_objPermissionFilter, $this->_objCategoryFilter, $this->_objColumnArray,$this->_objColumnExArray,$this->_objGroup);
        } else {
            $objArr = DBUtilEx::selectObjectArray ($this->_objType, $where, $orderBy, $limitOffset, $limitNumRows, $assocKey, $this->_objPermissionFilter, $this->_objCategoryFilter, $this->_objColumnArray,$this->_objColumnExArray,$this->_objGroup);
        }

        $this->_objData         = $objArr;
        $this->_objWhere        = $where;
        $this->_objSort         = $orderBy;
        $this->_objLimitOffset  = $limitOffset;
        $this->_objLimitNumRows = $limitNumRows;
        $this->_objAssocKey     = $assocKey;
        $this->_objDistinct     = $distinct;
        
        $this->prepareDataForDisplay();
        $this->selectPostProcess ();
        return $this->_objData;
    }

    /**
     * Get a selector for the object array
     *
     * @param name          The name of the selector to generate
     * @param selected      The currently selected value (optional) (default=-1234)
     * @param defaultValue  The default value (optional) (default=0)
     * @param defaultText   The default text (optional) (default='')
     * @param allValue      The all-selected value (optional) (default=0)
     * @param allText       The all-selected text (optional) (default='')
     * @param idField       The id field to use (optional) (default='id')
     * @param nameField     The name field to use (optional) (default='title')
     * @param submit        whether or not to submit the form upon selection (optional) (default=false)
     * @param ignoreList    The list of entries to ignore (default=null)
     *
     * @return The generated selector html text
     */
    function getSelector ($name, $selected=-1234, $defaultValue=0, $defaultText='',
                          $allValue=0, $allText='', $idField='id', $nameField='name',
                          $submit=false, $ignoreList=null)
    {
        $html = '<select name="'.$name.'"'
               .' id="'.DataUtil::formatForDisplay($name).'"'
               .($submit ? ' onChange="this.form.submit();"' : '')
               .'>';

        if ($defaultText) {
            $html .= "<option value=\"$defaultValue\">$defaultText</option>";
        }

        if ($allText) {
            $html .= "<option value=\"$allValue\">$allText</option>";
        }

        foreach ($this->_objData as $k => $v) {
            $disp  = $v[$nameField];
            $val   = $v[$idField];

            if (strpos($ignoreList, '$val') === false) {
                $sel   = ($val==$selected ? "selected" : "");
                $html .= "<option value=\"$val\" $sel>$disp</option>";
            }
        }
        $html .= '</select>';

        return $html;
    }
}
