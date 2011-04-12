<?php
//http://pob-ws.heroku.com/api/hotel_descriptive_content_notif


    $url = 'http://pob-ws.heroku.com/api/hotel_descriptive_content_notif';



    $data = '<?xml version="1.0" encoding="UTF-8"?>
<OTA_HotelAvailNotifRQ xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.opentravel.org/OTA/2003/05" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_HotelAvailNotifRQ.xsd" TimeStamp="2004-05-01T06:39:09" Target="Production" Version="1.002">
	<AvailStatusMessages ChainCode="SW" BrandCode="SI" HotelCode="BOSCO" HotelName="The Sheraton">
		<AvailStatusMessage BookingLimit="25" BookingLimitMessageType="SetLimit">
			<StatusApplicationControl Start="2004-08-02" End="2004-08-05" RatePlanCode="WHLS1" InvCode="STD">
	</StatusApplicationControl>
			<UniqueID Type="16" ID="1">
			</UniqueID>
		</AvailStatusMessage>
		<AvailStatusMessage BookingLimit="35" BookingLimitMessageType="SetLimit">
			<StatusApplicationControl Start="2004-08-06" End="2004-08-08" RatePlanCode="WHLS1" InvCode="STD">
			</StatusApplicationControl>
			<UniqueID Type="16" ID="2">
			</UniqueID>
		</AvailStatusMessage>
		<AvailStatusMessage BookingLimit="5" BookingLimitMessageType="SetLimit">
			<StatusApplicationControl Start="2004-08-02" End="2004-08-05" RatePlanCode="WHLS1" InvCode="DLX">
			</StatusApplicationControl>
			<UniqueID Type="16" ID="3">
			</UniqueID>
		</AvailStatusMessage>
		<AvailStatusMessage BookingLimit="8" BookingLimitMessageType="SetLimit">
			<StatusApplicationControl Start="2004-08-06" End="2004-08-08" RatePlanCode="WHLS1" InvCode="DLX">
			</StatusApplicationControl>
<UniqueID Type="16" ID="4">
			</UniqueID>
		</AvailStatusMessage>
	</AvailStatusMessages>
</OTA_HotelAvailNotifRQ>';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);

    curl_close($ch);

    var_dump($response);

?>