<?php
define('ADMIN_PAGE_SIZE'          , 100);
define('USER_PAGE_SIZE'           , 20);

define('IMAGE_LARGE_PATH'             , 'resource/innogallery/large');
define('IMAGE_SMALL_PATH'             , 'resource/innogallery/small');
define('FILE_SCANER_PATH'             , 'resource/innogallery/flashgallery.php');
define('GALLERY_ROOT'                 , 'resource/innogallery');

define('UPLOAD_IMAGE_LARGE_SIZE'      , '600');
define('UPLOAD_IMAGE_SMALL_SIZE'      , '200');

define('UPLOAD_IMAGE_LIMIT_SIZE'      , 10 * 1024 * 1024); //10 MByte
define('UPLOAD_IMAGE_LIMIT_FILE_TYPE' , "^(image/*)");

define('THUMB_SIZE_WIDTH'           , "200");
define('THUMB_SIZE_HEIGHT'          , "150");

define('VIEWER_SIZE_WIDTH'           , "700");
define('VIEWER_SIZE_HEIGHT'          , "600");

//more information in pnSecurity.php
//define('ACCESS_INVALID', -1);
//define('ACCESS_NONE', 0);
//define('ACCESS_OVERVIEW', 100);
//define('ACCESS_READ', 200);
//define('ACCESS_COMMENT', 300);
//define('ACCESS_MODERATE', 400);
//define('ACCESS_EDIT', 500);
//define('ACCESS_ADD', 600);
//define('ACCESS_DELETE', 700);
//define('ACCESS_ADMIN', 800);

//the min access level to access admin panel
define('ADMIN_EDIT_LEVEL'       , 800); //ACCESS_EDIT
//the min access level to allow user add placemark
define('USER_EDIT_LEVEL'        , 300); //ACCESS_COMMENT
