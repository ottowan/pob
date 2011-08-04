<?php
class SecurityUtilEx {
  public static function checkPermissionFromObject(&$object){
    $havePerm = true;
    if ($object && $object->_objPermission){
          if (!is_array($object->_objPermission)){
            return pn_exit('Invalid permission value');
          }
          if (!$object->_objPermission['component']){
            return pn_exit('Invalid permission component value');
          }
          if (!$object->_objPermission['instance']){
            return pn_exit('Invalid permission instance value');
          }
          if (!$object->_objPermission['level']){
            return pn_exit('Invalid permission level value');
          }
          if (!SecurityUtil::checkPermission($object->_objPermission['component'], $object->_objPermission['instance'], $object->_objPermission['level'])) {
              $havePerm = false;
          }
    }//end if
    return $havePerm;
  }//end function
}//end class