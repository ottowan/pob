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
    return POBPortal_user_display();
}

/**
 * Display a POBPortal page
 * @author Simon Birtwistle
 * @return string HTML string
 */
function POBPortal_user_display() {
    $page = FormUtil::getPassedValue('page', 'home', 'GET');
    
    if ($page == 'extensions') {
        $content = pnModFunc('POBPortal', 'user', 'extensions');
    } else {
        $render = pnRender::getInstance('POBPortal');
        $lang = pnUserGetLang();
        if ($render->template_exists($lang.'/POBPortal_user_display_'.$page.'.htm')) {
            $content = $render->fetch($lang.'/POBPortal_user_display_'.$page.'.htm');
        } else {
            $content = $render->fetch('POBPortal_user_display_'.$page.'.htm');
        }
    }
    
    return $content;
}

/**
 * Cycle through all installed modules looking for available module POBPortals
 * @author Simon Birtwistle
 * @return string HTML string
 */
function POBPortal_user_extensions() {
    $modules = pnModGetAllMods();
    $modpages = array();
    foreach ($modules as $mod) {
        if (file_exists('modules/'.$mod['directory'].'/pndocs/POBPortal_page1.htm')) {
            $modpages[] = $mod['name'];
        }
    }
    $themes = pnThemeGetAllThemes();
    $themepages = array();
    foreach ($themes as $theme) {
        if (file_exists('themes/'.$theme['directory'].'/pndocs/POBPortal_page1.htm')) {
            $themepages[] = $theme['name'];
        }
    }
    
    $render = pnRender::getInstance('POBPortal');
    $render->assign('modpages', $modpages);
    $render->assign('themepages', $themepages);
    $lang = pnUserGetLang();
    if ($render->template_exists($lang.'/POBPortal_user_extensions.htm')) {
        $content = $render->fetch($lang.'/POBPortal_user_extensions.htm');
    } else {
        $content = $render->fetch('POBPortal_user_extensions.htm');
    }

    return $content;
}

/**
 * Display a POBPortal page from an installed extension, or the distribution's POBPortal page
 * @author Simon Birtwistle
 * @return string HTML string
 */
function POBPortal_user_extPOBPortal() {
    $page = FormUtil::getPassedValue('page', '1', 'GET');
    $ext = FormUtil::getPassedValue('ext', '', 'GET');
    $exttype = FormUtil::getPassedValue('exttype', 'module', 'GET');

    switch ($exttype) {
        case 'distro':
            $directory = 'docs/distribution';
            break;
        case 'module':
            $id = pnModGetIDFromName($ext);
            if (!$id) {
                LogUtil::registerError('Unknown module '.$ext.' in POBPortal_user_extPOBPortal.');
                pnRedirect(pnModURL('POBPortal', 'user', 'main'));
            }
            $info = pnModGetInfo($id);
            $directory = 'modules/'.$info['directory'].'/pndocs';
            break;
        case 'theme':
            $id = pnThemeGetIDFromName($ext);
            if (!$id) {
                LogUtil::registerError('Unknown theme '.$ext.' in POBPortal_user_extPOBPortal.');
                pnRedirect(pnModURL('POBPortal', 'user', 'main'));
            }
            $info = pnThemeGetInfo($id);
            $directory = $info['directory'].'/pndocs';
            break;
    }
    
    $lang = pnUserGetLang();
    $files = array($directory.'/'.$lang.'/POBPortal_page'.$page.'.htm', $directory.'/POBPortal_page'.$page.'.htm');
    
    $exists = false;
    foreach ($files as $file) {
        $file = DataUtil::formatForOS($file);
        $file = getcwd().'/'.$file;
        if (file_exists($file)) {
            $exists = true;
            break;
        }
    }
    
    if (!$exists) {
        LogUtil::registerError('POBPortal file does not exist!');
        return pnRedirect(pnModURL('POBPortal', 'user', 'extensions'));
    }
    
    $render = pnRender::getInstance('POBPortal');
    return $render->fetch('POBPortal_user_menu.htm').$render->fetch('file://'.$file);
}
