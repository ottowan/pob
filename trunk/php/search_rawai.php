<?php
//http://pob-ws.heroku.com/api/hotel_descriptive_content_notif


    $url = 'http://pob-ws.heroku.com/api/hotel_search';

    $data = '<?xml version="1.0"?> 
<OTA_HotelSearchRQ xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_HotelSearchRQ.xsd" EchoToken="HL" Target="Production" Version="2.004" PrimaryLangID="EN-US" ResponseType="PropertyList"> 
  <POS> 
    <Source> 
      <RequestorID ID="8508a7e6ce43e091" ID_Context="RZ"/> 
    </Source> 
  </POS> 
  <Criteria> 
    <Criterion> 
      <Position Latitude="7.77971"/> 
      <Position Longitude="98.325577"/> 
      <Radius DistanceMeasure="MILES" Distance="10"/> 
      <StayDateRange Start="2011-08-7" End="2011-08-8"/> 
    </Criterion> 
  </Criteria> 
</OTA_HotelSearchRQ> ';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);

    curl_close($ch);
  header("Content-type: text/xml");
    echo $response;
    //var_dump($response);

?>