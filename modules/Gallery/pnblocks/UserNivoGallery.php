<?php

/**
 * initialise block
 * 
 * @author       The PostNuke Development Team
 */
function Gallery_UserNivoGalleryblock_init()
{

}

/**
 * get information on block
 * 
 * @author       The PostNuke Development Team
 * @return       array       The block information
 */
function Gallery_UserNivoGalleryblock_info()
{
    return array('text_type'      => 'Nivo Gallery',
                 'module'         => 'Gallery',
                 'text_type_long' => 'Nivo gallery ',
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
function Gallery_UserNivoGalleryblock_display($blockinfo)
{

    $modname  = 'Gallery';
    $class = '';
    $vars = pnBlockVarsFromContent($blockinfo['content']);
    $modinfo = pnModGetInfo(pnModGetIDFromName($modname));
    $directory = $modinfo['directory'];
    pnModDBInfoLoad($modname, $directory);
    //load render
    $render = pnRender::getInstance($modname);
    $render->assign('modulepath', 'modules/' . $modname);
    $render->assign('vars',$vars);

    $object = pnModAPIFunc('POBHotel', 'user', 'getHotelCode', null);
    $render->assign('hotelcode', $object["hotelcode"]);

    //Read image file
/*
    $upload_dir = '../../../gallery/files/'.$hotelcode["hotelcode"].'/';

    $filenames = array();
    $iterator = new DirectoryIterator($upload_dir);
    foreach ($iterator as $fileinfo) {
        if ($fileinfo->isFile()) {
            $filenames[$fileinfo->getMTime()] = $fileinfo->getFilename();
        }
    }
*/

/*
    $upload_dir = '../../../gallery/files/'.$hotelcode["hotelcode"]."/";
    $dir=@dir($upload_dir);
    //List files in images directory
    while (($file = $dir->read()) !== false) {
      echo "filename: " . $file . "<br />";
    }
    $dir->close();
*/

    $upload_dir = 'gallery/files/'.$object["hotelcode"]."/";
    $imagesArray = getDirectoryList($upload_dir);

    //print_r($imagesArray); exit;
    $render->assign('imagesArray', $imagesArray);

    $blockinfo['content'] = $render->fetch('block_usernivogallery.htm');
    return themesideblock($blockinfo);

}


/**
 * modify block settings
 * 
 * @author       The PostNuke Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output      the bock form
 */
function Gallery_UserNivoGalleryblock_modify($blockinfo)
{
  $vars = pnBlockVarsFromContent($blockinfo['content']);
  //load render
  $render = pnRender::getInstance('Gallery');
  $render->assign('form', $vars);
  
  return $render->fetch('block_usernivogallery_modify.htm');
}


/**
 * update block settings
 * 
 * @author       The PostNuke Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       $blockinfo  the modified blockinfo structure
 */
function Gallery_UserNivoGalleryblock_update($blockinfo)
{
    
    // Get current content
    $vars = pnBlockVarsFromContent($blockinfo['content']);
    
    // alter the corresponding variable
    $form = FormUtil::getPassedValue ('form', false );
    $vars['albums_id'] = !empty($form['albums_id']) ? $form['albums_id'] : 1;
    $vars['width']     = !empty($form['width']) ? $form['width'] : '345';
    $vars['height']    = !empty($form['height']) ? $form['height'] : '230';


    // write back the new contents
    $blockinfo['content'] = pnBlockVarsToContent($vars);

    // clear the block cache
    $pnRender = pnRender::getInstance('Gallery');
    $pnRender->clear_cache('block_usernivogallery.htm');
    
    return $blockinfo;
}


function getDirectoryList ($directory){

  // create an array to hold directory list
  $results = array();

  // create a handler for the directory
  $handler = opendir($directory);

  // open directory and walk through the filenames
  while ($file = readdir($handler)) {

    // if file isn't this directory or its parent, add it to the results
    if ($file[0] != "." && $file != "Thumbs.db") {
      $results[] = $file;
    }
  }

  // tidy up: close the handler
  closedir($handler);

  // done!
  return $results;

}
