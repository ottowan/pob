<?php
Loader::loadFile('config.php', "modules/InnoGallery");

Loader::loadClass('SecurityUtilEx', "modules/InnoGallery/pnincludes");
Loader::loadClass('DateUtilEx', "modules/InnoGallery/pnincludes");
Loader::loadClass('ObjectUtilEx', "modules/InnoGallery/pnincludes");
Loader::loadClass('PNObjectEx', "modules/InnoGallery/pnincludes");
Loader::loadClass('PNObjectExArray', "modules/InnoGallery/pnincludes");
Loader::loadClass('DBUtilEx', "modules/InnoGallery/pnincludes");
Loader::loadClass('InnoUtil', "modules/InnoGallery/pnincludes");

/**
 * initialise block
 * 
 * @author       The PostNuke Development Team
 */
function InnoGallery_UserGalleryListblock_init()
{

}

/**
 * get information on block
 * 
 * @author       The PostNuke Development Team
 * @return       array       The block information
 */
function InnoGallery_UserGalleryListblock_info()
{
    return array('text_type'      => 'User Gallery List',
                 'module'         => 'InnoGallery',
                 'text_type_long' => 'Show list of gallery',
                 'allow_multiple' => true,
                 'form_content'   => false,
                 'form_refresh'   => false,
                 'show_preview'   => true);
}

/**
 * display block
 * 
 * @author       The PostNuke Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output      the rendered bock
 */
function InnoGallery_UserGalleryListblock_display($blockinfo)
{

    $modname  = 'InnoGallery';
    $class    = 'UserAlbums';

    $vars = pnBlockVarsFromContent($blockinfo['content']);
    $modinfo = pnModGetInfo(pnModGetIDFromName($modname));
    $directory = $modinfo['directory'];
    pnModDBInfoLoad($modname, $directory);
    //load render

    if(!empty($vars['limit'])){
      $pagesize = $vars['limit'];
    }
    //load render
    $render = pnRender::getInstance($modname);
    //load class
    if (!($class = Loader::loadClassFromModule ($modname,$class, true)))
      return LogUtil::registerError ("Unable to load class [$class] ...");

    $objectArray = new $class ();
    if (!SecurityUtilEx::checkPermissionFromObject($object)){
      return LogUtil::registerError ("You not have permission to access this section");
    }
    $where   = null;
    $sort = null;
    $where_glue = '';
    if (method_exists($objectArray,'genFilter')){
      $where = $objectArray->genFilter();
      
    }
    if (method_exists($objectArray,'genSort')){
      $sort = $objectArray->genSort();
    }
    if (method_exists($objectArray,'selectExtendResult')){
      $resultex = $objectArray->selectExtendResult();
      $render->assign('extendResult', $resultex);
    }
    if (!empty($where)){
      $where_glue = ' AND ';
    }
    if (is_array($objectArray->_objWhere)){
      $where .= implode($where_glue,$objectArray->_objWhere);
    }else if (!empty($objectArray->_objWhere)){
      $where .= $where_glue . $objectArray->_objWhere;
    }
    if (empty($sort)){
      $sort = $objectArray->_objSort;
    }

    $objectArray->get ($where, $sort , $offset, $pagesize);
    //assign to view
    $render->assign('objectArray', $objectArray->_objData);
    $render->assign('modulepath', 'modules/' . $directory);
    $render->assign('vars',$vars);


    // Populate block info and pass to theme
    $blockinfo['content'] = $render->fetch('block_usergallerylist.htm');
    return themesideblock($blockinfo);

}


/**
 * modify block settings
 * 
 * @author       The PostNuke Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output      the bock form
 */
function InnoGallery_UserGalleryListblock_modify($blockinfo)
{
  $vars = pnBlockVarsFromContent($blockinfo['content']);
  //load render
  $render = pnRender::getInstance('InnoGallery');
  $render->assign('form', $vars);
  
  return $render->fetch('block_usergallerylist_modify.htm');
}


/**
 * update block settings
 * 
 * @author       The PostNuke Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       $blockinfo  the modified blockinfo structure
 */
function InnoGallery_UserGalleryListblock_update($blockinfo)
{
    
    // Get current content
    $vars = pnBlockVarsFromContent($blockinfo['content']);
    
    // alter the corresponding variable
    $form = FormUtil::getPassedValue ('form', false );

    $vars['limit']    = !empty($form['limit']) ? $form['limit'] : '6';

    // write back the new contents
    $blockinfo['content'] = pnBlockVarsToContent($vars);

    // clear the block cache
    $pnRender = pnRender::getInstance('InnoGallery');
    $pnRender->clear_cache('block_usergallerylist.htm');
    
    return $blockinfo;
}
