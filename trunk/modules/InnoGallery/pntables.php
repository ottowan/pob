<?php
Loader::loadFile('config.php', "modules/InnoGallery");

Loader::loadClass('SecurityUtilEx', "modules/InnoGallery/pnincludes");
Loader::loadClass('DateUtilEx', "modules/InnoGallery/pnincludes");
Loader::loadClass('ObjectUtilEx', "modules/InnoGallery/pnincludes");
Loader::loadClass('PNObjectEx', "modules/InnoGallery/pnincludes");
Loader::loadClass('PNObjectExArray', "modules/InnoGallery/pnincludes");
Loader::loadClass('DBUtilEx', "modules/InnoGallery/pnincludes");
Loader::loadClass('InnoUtil', "modules/InnoGallery/pnincludes");


function InnoGallery_pntables()
{
    // Initialise table array
    $pntable = array();
    ////////////////////////////////////////////
    //table definition albums
    ////////////////////////////////////////////
    $pntable['innogallery_albums'] = DBUtil::getLimitedTablename('innogallery_albums');
    $pntable['innogallery_albums_column'] = array(
                                        'id'                => 'abm_id',
                                        'title'             => 'abm_title',
                                        'detail'            => 'abm_detail',
                                        'count_view'        => 'abm_count_view',
                                        'count_pictures'    => 'abm_count_pictures',
                                        'author'            => 'abm_author',
                                        'is_show'           => 'abm_is_show'
    );
    $pntable['innogallery_albums_column_def'] = array(
                                        'id'                => 'INT(11) NOTNULL AUTOINCREMENT PRIMARY',
                                        'title'             => 'VARCHAR(255)',
                                        'detail'            => 'TEXT',
                                        'count_view'        => 'INT(7) DEFAULT 0',
                                        'count_pictures'    => 'INT(5) DEFAULT 0',
                                        'author'            => 'VARCHAR(255) DEFAULT NULL',
                                        'is_show'           => 'INT(1) DEFAULT 0'
    );
    $pntable['innogallery_albums_primary_key_column'] = 'id';
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['innogallery_albums_column'], 'abm_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['innogallery_albums_column_def']);

    /*
    ////////////////////////////////////////////
    //table definition pictures
    ////////////////////////////////////////////
    $pntable['innogallery_pictures'] = DBUtil::getLimitedTablename('innogallery_pictures');
    $pntable['innogallery_pictures_column'] = array(
                                        'id'                => 'pic_id',
                                        'albums_id'         => 'pic_albums_id',
                                        'caption'           => 'pic_title',
                                        'detail'            => 'pic_detail'
    );
    $pntable['innogallery_pictures_column_def'] = array(
                                        'id'                => 'INT(11) NOTNULL AUTOINCREMENT PRIMARY',
                                        'albums_id'         => 'INT(11) NOTNULL',
                                        'caption'           => 'TEXT',
                                        'detail'            => 'TEXT'
    );
    $pntable['innogallery_pictures_primary_key_column'] = 'id';
    // add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['innogallery_pictures_column'], 'pic_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['innogallery_pictures_column_def']);
    */
    ////////////////////////////////////////////
    //table definition comment
    ////////////////////////////////////////////
    $pntable['innogallery_comments'] = DBUtil::getLimitedTablename('innogallery_comments');
    $pntable['innogallery_comments_column'] = array(
                                        'id'                => 'cmt_id',
                                        'albums_id'         => 'cmt_albums_id',
                                        'name'              => 'cmt_name',
                                        'email'             => 'cmt_email',
                                        'url'               => 'cmt_url',
                                        'ip'                => 'cmt_ip',
                                        'comment'           => 'cmt_comment'
    );
    $pntable['innogallery_comments_column_def'] = array(
                                        'id'                => 'INT(11) NOTNULL AUTOINCREMENT PRIMARY',
                                        'albums_id'         => 'INT(11) DEFAULT 0',
                                        'name'              => 'VARCHAR(255)',
                                        'email'             => 'VARCHAR(255)',
                                        'url'               => 'TEXT',
                                        'ip'                => 'VARCHAR(20)',
                                        'comment'           => 'TEXT'
    );
    $pntable['innogallery_comments_primary_key_column'] = 'id';
    // add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['innogallery_comments_column'], 'cmt_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['innogallery_comments_column_def']);

    return $pntable;
}