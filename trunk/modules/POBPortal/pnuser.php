<?php
/**
 * Zikula Application Framework
 *
 * @link http://www.zikula.org
 * @version $Id: pnuser.php,v 1.1 2009/06/26 05:17:04 chongasem Exp $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @author Simon Birtwistle simon@itbegins.co.uk
 * @package Zikula_Docs
 * @subpackage Tour
 */

/**
 * Main user function, simply returnt he tour index page.
 * @author Simon Birtwistle
 * @return string HTML string
 */
function POBPortal_user_main() {
    return POBPortal_user_page();
}


function POBPortal_user_page() {

    //$ctrl the class name
    $ctrl    = FormUtil::getPassedValue ('ctrl', 'map' , 'GET');
    //$method the method of request for edit or view enum[ view | form | delete | list | page]
    $func  = FormUtil::getPassedValue ('func', 'page' , 'GET');
    $render = pnRender::getInstance('POBPortal');
    
    //try to load class
    return $render->fetch('user_'.$func.'_'.strtolower($ctrl).'.htm');
}