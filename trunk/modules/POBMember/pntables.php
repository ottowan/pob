<?php
  function POBMember_pntables(){
    //Initialise table array
    $pntable = array();
    ////////////////////////////////////////////
    //table definition hotel
    ////////////////////////////////////////////
    $pntable['pobmember_member'] = DBUtil::getLimitedTablename('pobmember_member');
    $pntable['pobmember_member_column'] = array(
                                          'id'        => 'member_id',
                                          'uid'       => 'member_uid',
                                          'hotelcode' => 'member_hotelcode',
                                          'status'    => 'member_status'
    );
    $pntable['pobmember_member_column_def'] = array(
                                          'id'        => 'INT(11) NOTNULL AUTOINCREMENT PRIMARY',
                                          'uid'       => 'INT(11) NOTNULL ',
                                          'hotelcode' => 'VARCHAR(255)',
                                          'status'    => 'INT(11) DEFAULT 2'
    );

    //add standard data fields
    ObjectUtil::addStandardFieldsToTableDefinition ($pntable['pobmember_member_column'], 'member_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['pobmember_member_column_def']);
    $pntable['pobmember_member_column_idx'] = array(
                                                                'idx_member_uid' => 'member_uid',
                                                                'idx_member_status_id' => 'member_status_id'
                                                          );

    return $pntable;
  }