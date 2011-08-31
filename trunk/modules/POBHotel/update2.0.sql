DROP TABLE `zk_pobhotel_room`;
CREATE TABLE `zk_pobhotel_room` (
  `room_id` INT(11) NOT NULL AUTO_INCREMENT,
  `room_guest_room_type_id` INT(11) DEFAULT NULL,
  `room_name` VARCHAR(255),
  `room_description` VARCHAR(255),
  `room_obj_status` VARCHAR(1) NOT NULL DEFAULT 'A',
  `room_cr_date` DATETIME NOT NULL DEFAULT '1970-01-01 00:00:00',
  `room_cr_uid` INT(11) NOT NULL DEFAULT '0',
  `room_lu_date` DATETIME NOT NULL DEFAULT '1970-01-01 00:00:00',
  `room_lu_uid` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`room_id`),
  KEY idx_room_guest_room_type_id (`room_guest_room_type_id`)
) ENGINE=MYISAM CHARSET=utf8
