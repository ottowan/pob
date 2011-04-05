<?php
//http://pob-ws.heroku.com/api/hotel_descriptive_content_notif


    $url = 'http://pob-ws.heroku.com/api/hotel_search';

    $data = '<?xml version="1.0" encoding="UTF-8"?>
<OTA_HotelSearchRQ xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_HotelSearchRQ.xsd" EchoToken="HL" Target="Production" Version="1.003" PrimaryLangID="EN-US" ResponseType="PropertyList">
	<POS>
		<Source AirlineVendorID="FG" PseudoCityCode="MIA" ISOCountry="US" ISOCurrency="USD" AgentSine="A4444BM" AgentDutyCode="FR">
		</Source>
		<Source>
			<RequestorID Type="5" ID="12345675" ID_Context="IATA"/>
				<!--5 means travel agency-->
		</Source>
	</POS>
	<Criteria>
		<Criterion>
			<RefPoint>PATONG BEACH</RefPoint>
			<CodeRef LocationCode="23" CodeContext="OTA-REF code list"/>
				<!--23 means monument location code-->
			<HotelRef HotelCityCode="PAR"/>
			<Radius Distance="2" DistanceMeasure="MILES"/>
			<RoomAmenity RoomAmenity="74"/>
				<!-- 74 means non smoking-->
			<RoomAmenity RoomAmenity="158"/>
				<!--158 means CNN is in the room -->
			<RoomAmenity RoomAmenity="123"/>
				<!--123 means wireless internet access-->
			</Criterion>
	</Criteria>
</OTA_HotelSearchRQ>';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);

    curl_close($ch);

    echo $response;
    var_dump($response);

?>