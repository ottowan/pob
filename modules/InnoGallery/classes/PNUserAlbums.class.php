<?php
class PNUserAlbums extends PNObjectEx {
  function PNUserAlbums($init=null, $where='')
  {
    $this->PNObject();

    $this->_objType       = 'innogallery_albums';
    $this->_objField      = 'id';
    $this->_objPath       = 'form';

    $this->addCountView();

    $this->_init($init, $where);
  }

  function addCountView(){
    $id = FormUtil::getPassedValue ('id', false);
    if ($id){
      $max = DBUtil::selectFieldMax('innogallery_albums','count_view' ,'MAX', "WHERE abm_id = '$id'");
      $object = array('id'=>$id , 'count_view'=>intval($max) + 1);
      DBUtil::updateObject($object,'innogallery_albums');
    }
  }

  function addCountPicture($id){
    $result = pnModAPIFunc('InnoGallery', 'user', 'getImage',
                       array( 'id'   => $id,
                               'path' => IMAGE_LARGE_PATH));
    $object = array('id'=>$id,'count_pictures'=>count($result));
    DBUtil::updateObject ($object, 'innogallery_albums');
  }

  function validate(){
    $form = FormUtil::getPassedValue ('form', false);
    if (empty($form['title'])){
      $message[] = 'field title must not be empty.';
      $this->setError($message);
      return false;
    }
    return true;
  }

  ///////////////////////// POST PROCESS ///////////////////////////
  function insertPostProcess(){
    $id = DBUtil::getInsertID ($this->_objType, $this->_objField);  
    //upload browse image to server
    if($id){
      //upload
      $result = pnModAPIFunc('InnoGallery', 'user', 'uploadImage',
                       array( 'id'    => $id,
                              'files' => $_FILES['images'],
                              'photo_path' => IMAGE_LARGE_PATH,
                              'thumb_path' => IMAGE_SMALL_PATH,
                              'is_rename' => true,
                              'is_createthumb'=>true,
                              'is_createfirstimage'=>true ));
    }
    $this->addCountPicture($id);
  }

  function updatePostProcess(){
    $id = $this->_objData['id'];
    $is_createthumb = false;
    //upload browse image to server
    if (!is_file(IMAGE_SMALL_PATH . "/$id/thumb.jpg")){
      $is_createthumb = true;
    }
    //upload browse image to server
    if($id){
      //upload
      $result = pnModAPIFunc('InnoGallery', 'user', 'uploadImage',
                       array( 'id'    => $id,
                              'files' => $_FILES['images'],
                              'photo_path' => IMAGE_LARGE_PATH,
                              'thumb_path' => IMAGE_SMALL_PATH,
                              'is_rename' => true,
                              'is_createthumb'=>true,
                              'is_createfirstimage'=>$is_createthumb));
    }
    //set thumb image
    if ($this->_objData['image_thumb']){
      $result = pnModAPIFunc('InnoGallery', 'user', 'setThumbImage',
                        array( 'id'    => $id,
                              'size'  => UPLOAD_IMAGE_SMALL_SIZE,
                              'name' => $this->_objData['image_thumb'],
                              'path'  => IMAGE_SMALL_PATH));
    }
    //delete image
    foreach($this->_objData['image_delete'] as $key => $value){
      $result = pnModAPIFunc('InnoGallery', 'user', 'deleteImage',
                        array( 'id'    => $id,
                              'name' => $value,
                              'photo_path'  => IMAGE_LARGE_PATH,
                              'thumb_path'  => IMAGE_SMALL_PATH));
    }
    $this->addCountPicture($id);
  }

  function deletePostProcess(){
    $id = FormUtil::getPassedValue ('id', false);
    //delete 
    $result = pnModAPIFunc('InnoGallery', 'user', 'deleteImage',
                              array( 'id'    => $id,
                                     'photo_path' => IMAGE_LARGE_PATH,
                                     'thumb_path' => IMAGE_SMALL_PATH));
  }

  ///////////////////////////////// DISPLAY VIEW SELECT ///////////////////////
  function selectPostProcess ($obj=null){
    //fetch image array from directory by id
    $this->_objData['images'] = pnModAPIFunc('InnoGallery', 'user', 'getImage',
                    array( 'id'   => $this->_objData['id'],
                           'path'  => IMAGE_LARGE_PATH));
  }

  function selectExtendResult(){
    $id = FormUtil::getPassedValue ('id', false);
    $result = array();
    //select comment
    $result['comments'] = DBUtil::selectObjectArray('innogallery_comments',"WHERE cmt_albums_id = '$id'");
    return $result;
  }
}