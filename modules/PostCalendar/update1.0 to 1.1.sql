CREATE TABLE `zk_postcalendar_daybooking` (
  `day_id` int(11) NOT NULL auto_increment,
  `day_cus_id` int(11) default NULL,
  `day_customer_refid` text,
  `booking_id` text,
  `day_status_id` int(2) default '2',
  `day_chaincode` varchar(10) default NULL,
  `day_hotelname` varchar(255) default NULL,
  `day_isocurrency` varchar(10) default NULL,
  `day_date` date default NULL,
  `day_invcode` varchar(255) default NULL,
  `day_rate` double default NULL,
  `day_identificational` varchar(50) default NULL,
  `day_nameprefix` varchar(50) default NULL,
  `day_givenname` text,
  `day_surname` text,
  `day_addressline` text,
  `day_cityname` varchar(255) default NULL,
  `day_stateprov` varchar(255) default NULL,
  `day_countryname` varchar(255) default NULL,
  `day_postalcode` varchar(50) default NULL,
  `day_mobile` varchar(255) default NULL,
  `day_phone` varchar(255) default NULL,
  `day_email` text,
  `day_addition_request` text,
  `day_cardcode` varchar(255) default NULL,
  `day_cardnumber` varchar(255) default NULL,
  `day_cardholdernamer` varchar(255) default NULL,
  `day_cardexpire` varchar(255) default NULL,
  `day_issue_date` datetime default NULL,
  `day_cardsecurecode` varchar(255) default NULL,
  `day_cardbankname` varchar(255) default NULL,
  `day_cardissuingcountry` varchar(255) default NULL,
  PRIMARY KEY  (`day_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8