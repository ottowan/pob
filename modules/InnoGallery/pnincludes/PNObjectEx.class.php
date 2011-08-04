<?php
/**
 * PNObjectExUtil
 *
 * @package CoastalDB
 */
class PNObjectEx extends PNObject
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
    * $_objPermission = array('component' => 'InnoForum::'
                              'instance'  => '::',
                              'level'     => ACCESS_ADMIM);
    */
    var $_objPermission = null;
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
          foreach($languageColumn as $col){
            if( isset($this->_objData[$col])){
              if ($mergeold && $this->_objData['id'] && $this->_objType){
               $olddata = DBUtil::selectFieldByID ($this->_objType, $col, $this->_objData['id']);
              }

              //try merge with old data
              if ($mergeold){
                $oldfield = unserialize($olddata);
                if (!$oldfield){
                  $oldfield = array();
                }
                $oldfield[$lang] = $this->_objData[$col];
                $this->_objData[$col] = serialize($oldfield);
              }else{
                $newdata = array($lang=>$this->_objData[$col]);
                $this->_objData[$col] = serialize($newdata);
              }
            }
          }
        }
    }
    /*
    * provide multi language display
    */
    function prepareDataForDisplay(){
        $pntables = pnDBGetTables();
        $languageColumn    = $pntables[$this->_objType . '_language' ];
        //get current lang
        $lang = pnUserGetLang();
        if ($lang){
          //unserialize all column
          foreach($languageColumn as $col){
            if (isset($this->_objData[$col])){
              $data = unserialize($this->_objData[$col]);
              //if unserialize success,else leave unchange
              if ($data && is_array($data)){
                $this->_objData[$col] = $data[$lang];
              }
            }
          }//end for
        }//end lang
    }
    /**
     * Generic insert handler for an object (ID is inserted into the object data)
     * and leave default on empty value
     *
     * @return The Object Data
     */
    function insert ()
    {
        $this->insertPreProcess ();
        $this->prepareDataForStore(false);
        $res = DBUtilEx::insertObject ($this->_objData, $this->_objType, $this->_objField);
        if ($res) {
            $this->insertPostProcess ();
            return $this->_objData;
        }

        return false;
    }


    /**
     * Generic upate handler for an object
     * and leave default on empty value
     *
     * @return The Object Data
     */
    function update ()
    {
        $this->updatePreProcess ();
        $this->prepareDataForStore(true);
        $res = DBUtilEx::updateObject ($this->_objData, $this->_objType, '', $this->_objField);
        if ($res) {
            $this->updatePostProcess ();
            return $this->_objData;
        }

        return false;
    }

    /**
     * Select the object from the database using the specified key (and field)
     *
     * @param key        The record's key value (if init is a string directive)
     * @param field      The key-field we wish to select by (optional) (default='id')
     * @param where      The key-field we wish to select by (optional) (default='id')
     *
     * @return The object's data value
     */
    function &select ($key, $field='id', $where='')
    {
        parent::select ($key, $field, $where);
        $this->prepareDataForDisplay();
        return $this->_objData;
    }
    
    function setError($msg ,$varname = 'ERROR' , $prefix = 'YL'){
      if (is_array($msg)){
        SessionUtil::setVar($prefix . $varname , $msg);
      }else{
        SessionUtil::setVar($prefix . $varname , array($msg));
      }
    }
    
    function clearError($varname = 'ERROR' ,$prefix = 'YL'){
      SessionUtil::delVar($prefix . $varname);
    }
}
