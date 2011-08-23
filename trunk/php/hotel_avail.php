<?php
//http://pob-ws.heroku.com/api/hotel_descriptive_content_notif


    $url = 'http://pob-ws.heroku.com/api/hotel_avail';

    $data = '<?xml version="1.0"?> 
<POB_HotelAvailRQ xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_HotelAvailRQ.xsd" Version="1.003"> 
  <POS> 
    <Source> 
      <RequestorID Type="1" ID="638fdJa7vRmkLs5"/> 
    </Source> 
  </POS> 
  <AvailRequestSegments> 
    <AvailRequestSegment> 
      <StayDateRange Start="2011-08-23" End="2011-08-24"/> 
      <HotelSearchCriteria> 
        <Criterion> 
          <HotelRef HotelCode="POBHT000033"/> 
        </Criterion> 
      </HotelSearchCriteria> 
    </AvailRequestSegment> 
  </AvailRequestSegments> 
</POB_HotelAvailRQ>';


      //<StayDateRange Start="2004-08-02" End="2004-08-03" />

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