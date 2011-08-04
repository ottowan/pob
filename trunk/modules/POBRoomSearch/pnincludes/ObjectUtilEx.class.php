<?php
/**
 * Zikula Application Framework
 *
 * @copyright Robert Gasch
 * @link http://www.zikula.org
 * @version $Id: ObjectUtilEx.class.php,v 1.1 2010/08/26 10:46:08 parinya Exp $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @author Robert Gasch rgasch@gmail.com
 * @package Zikula_Core
 * @subpackage ObjectUtil
 */

/**
 * ObjectUtil
 *
 * @package Zikula_Core
 * @subpackage ObjectUtil
 */
class ObjectUtilEx extends ObjectUtil
{


    /**
     * Set the standard PN architecture fields for object creation/insert
     *
     * @param object         The object we need to set the standard fields on
     * @param preserveValues whether or not to preserve value fields which have a valid value set (optional) (default=false)
     * @param idcolumn       The column name of the primary key column (optional) (default='id')
     *
     * @return Nothing, object is altered in place
     */
    function setStandardFieldsOnObjectCreate (&$obj, $preserveValues=false, $idcolumn='id')
    {
        if (!is_array ($obj))
        {
            print "ObjectUtil::setStandardFieldsOnObjectUpdate called on non-object:<br />";
            return;
        }

        Loader::loadClass ('DateUtil');

        $obj[$idcolumn] = ( isset($obj[$idcolumn]) && $obj[$idcolumn] && $preserveValues ? $obj[$idcolumn]  : null);
        $obj['cr_date'] = ( isset($obj['cr_date']) && $obj['cr_date'] && $preserveValues ? $obj['cr_date'] : DateUtilEx::getDatetime());
        $obj['cr_uid']  = ( isset($obj['cr_uid'])  && $obj['cr_uid']  && $preserveValues ? $obj['cr_uid']  : pnUserGetVar('uid'));
        $obj['lu_date'] = ( isset($obj['lu_date']) && $obj['lu_date'] && $preserveValues ? $obj['lu_date'] : DateUtilEx::getDatetime());
        $obj['lu_uid']  = ( isset($obj['lu_uid'])  && $obj['lu_uid']  && $preserveValues ? $obj['lu_uid']  : pnUserGetVar('uid'));

        if (is_null($obj['cr_uid'])) {
            $obj['cr_uid'] = 0;
        }
        if (is_null($obj['lu_uid'])) {
            $obj['lu_uid'] = 0;
        }
        return;
    }


    /**
     * Set the standard PN architecture fields to sane values for an object update
     *
     * @param object         The object we need to set the standard fields on
     * @param preserveValues whether or not to preserve value fields which have a valid value set (optional) (default=false)
     *
     * @return Nothing, object is altered in place
     */
    function setStandardFieldsOnObjectUpdate (&$obj, $preserveValues=false)
    {
        if (!is_array ($obj))
        {
            print "ObjectUtil::setStandardFieldsOnObjectUpdate called on non-object:<br />";
            return;
        }

        Loader::loadClass ('DateUtil');

        $obj['lu_date'] = ( isset($obj['lu_date']) && $obj['lu_date'] && $preserveValues ? $obj['lu_date'] : DateUtilEx::getDatetime());
        $obj['lu_uid']  = ( isset($obj['lu_uid'])  && $obj['lu_uid']  && $preserveValues ? $obj['lu_uid']  : pnUserGetVar('uid'));

        if (is_null($obj['lu_uid'])) {
            $obj['lu_uid'] = 0;
        }

        return;
    }


    /**
     * Remove the standard fields from the given object
     *
     * @param object    The object to operate on
     *
     * @return Nothing, object is altered in place
     */
    function removeStandardFieldsFromObject (&$obj)
    {
        unset ($obj['obj_status']);
        unset ($obj['cr_date']);
        unset ($obj['cr_uid']);
        unset ($obj['lu_date']);
        unset ($obj['lu_uid']);

        return;
    }


    /**
     * If the specified field is set return it, otherwise return default
     *
     * @param object     The object to get the field from
     * @param field      The field to get
     * @param default     The default value to return if the field is not set on the object (default=null) (optional)
     *
     * @return The object field value or the default
     */
    function getField ($obj, $field, $default=null)
    {
         if (isset($obj[$field]))
             return $obj[$field];

         return $default;
    }


    /**
     * Create an object of the reuqested type and set the cr_date and cr_uid fields.
     *
     * @param $type     The type of the object to create
     *
     * @return The newly created object
     */
    function createObject ($type)
    {
        $pntable = pnDBGetTables();
        if (!$pntable[$type])
            return pn_exit ("ObjectUtil::createObject: unable to reference object type [$type]");

        Loader::loadClass ('DateUtil');
        $obj= array ();
        $obj['__TYPE__'] = $type;
        $obj['cr_date']  = DateUtilEx::getDateTime ();
        $obj['cr_uid']   = pnUserGetVar('uid');

        return $obj;
    }

}
