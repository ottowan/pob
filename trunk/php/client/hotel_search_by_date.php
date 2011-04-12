<?php
//http://pob-ws.heroku.com/api/hotel_descriptive_content_notif


    $url = 'http://pob-ws.heroku.com/api/hotel_search';

    $data = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<OTA_HotelSearchRQ xmlns="http://www.opentravel.org/OTA/2003/05"
  PrimaryLangID="en" RetransmissionIndicator="false" SequenceNmbr="1"
  TransactionIdentifier="1" Version="2.004" Target="Production"
  TimeStamp="2010-07-21T21:41:52">
  <POS>
    <Source>
      <RequestorID ID="8508a7e6ce43e091" ID_Context="RZ" />
    </Source>
  </POS>
  <Criteria>
    <Criterion>
      <Position Latitude="7.88806" />
      <Position Longitude="98.3975" />
      <Radius DistanceMeasure="MILES" Distance="10000" />
      <StayDateRange Start="2004-08-02" End="2004-08-04" />
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
    //var_dump($response);

?>