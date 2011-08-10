<?php


Loader::loadClass('DateUtilEx', "modules/POBRoomSearch/pnincludes");
Loader::loadClass('ObjectUtilEx', "modules/POBRoomSearch/pnincludes");
Loader::loadClass('PNObjectEx', "modules/POBRoomSearch/pnincludes");
Loader::loadClass('PNObjectExArray', "modules/POBRoomSearch/pnincludes");
Loader::loadClass('DBUtilEx', "modules/POBRoomSearch/pnincludes");
Loader::loadClass('SecurityUtilEx', "modules/POBRoomSearch/pnincludes");
Loader::loadClass('InnoUtil', "modules/POBRoomSearch/pnincludes");

/**
 * initialise block
 * 
 * @author       The PostNuke Development Team
 */
function POBRoomSearch_SearchRoomLeftblock_init()
{

}

/**
 * get information on block
 * 
 * @author       The PostNuke Development Team
 * @return       array       The block information
 */
function POBRoomSearch_SearchRoomLeftblock_info()
{
    return array('text_type'      => 'Search Room left',
                 'module'         => 'POBRoomSearch',
                 'text_type_long' => 'Search Room left',
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
function POBRoomSearch_SearchRoomLeftblock_display($blockinfo)
{
    $modname  = 'POBRoomSearch';
    $class    = 'SearchRoomLeftblock';


    $vars = pnBlockVarsFromContent($blockinfo['content']);
    //get setting
    $pagesize = $vars['pagesize'];

    $modinfo = pnModGetInfo(pnModGetIDFromName($modname));
    $directory = $modinfo['directory'];
    pnModDBInfoLoad($modname, $directory);
    //load render
    $render = pnRender::getInstance($modname);

    //Load language
    $lang = pnUserGetLang();
    if (file_exists('modules/POBRoomSearch/pnlang/' . $lang . '/user.php')){
      Loader::loadFile('user.php', 'modules/POBRoomSearch/pnlang/' . $lang );
    }else if (file_exists('modules/POBRoomSearch/pnlang/eng/user.php')){
      Loader::loadFile('user.php', 'modules/POBRoomSearch/pnlang/eng' );
    }

	//Loader::loadClass('POBReader',"modules/POBRoomSearch/pnincludes");
    //$getter = new POBReader("http://localhost/");
    $search['list'] = "1,2,3,4";
	$search['desc'] = "id";
    //$result = $getter->getRoomList($search);
	//var_dump($result);
    if(count($result['data'])==19){
      $data['data'] = $result['data'];
    }else{
      $data = $result['data'];
    }
    $render->assign("totalItems",$result['totalItems']);
    $render->assign("totalPages",$result['totalPages']);
    $render->assign("nowPage",$result['nowPage']);
    $render->assign("next",$result['next']);
    $render->assign("previous",$result['previous']);
	
    $render->assign("objectArray",$data);

    // Populate block info and pass to theme
    $blockinfo['content'] = $render->fetch('block_searchroomleft.htm');
    return themesideblock($blockinfo);
}
/**
 * modify block settings
 * 
 * @author       The PostNuke Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output      the bock form
 */
function POBRoomSearch_SearchRoomLeftblock_modify($blockinfo)
{
  $vars = pnBlockVarsFromContent($blockinfo['content']);
  //load render
  $render = pnRender::getInstance('POBRoomSearch');
  $render->assign('form', $vars);
  
	return $render->fetch('block_searchroomleft_modify.htm');
}


/**
 * update block settings
 * 
 * @author       The PostNuke Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       $blockinfo  the modified blockinfo structure
 */
function POBRoomSearch_SearchRoomLeftblock_update($blockinfo)
{
    
    // Get current content
    $vars = pnBlockVarsFromContent($blockinfo['content']);
    
    // alter the corresponding variable
    $form = FormUtil::getPassedValue ('form', false );
    $vars['pagesize'] = !empty($form['pagesize']) ? $form['pagesize'] : 10;

    // write back the new contents
    $blockinfo['content'] = pnBlockVarsToContent($vars);

    // clear the block cache
    $pnRender = pnRender::getInstance('POBRoomSearch');
    $pnRender->clear_cache('block_searchroomleft.htm');
    
    return $blockinfo;
}
