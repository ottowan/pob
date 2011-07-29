<?php
  /**
  * 
  * 
  * 
  */
Class HotelSearchEndpoint {
  private $location  = NULL;
  private $distance  = 0;
  private $latitude  = NULL;
  private $longitude = NULL;
  private $startDate = NULL;
  private $endDate   = NULL;
  
  
  function __construct(){
  }

  public function setHotelSearchXML( $location  = NULL,  $distance  = 0, $latitude  = NULL, $longitude = NULL, $startDate = NULL, $endDate   = NULL){
    $this->location  = $location;
    $this->distance  = $distance;
    $this->latitude  = $latitude;
    $this->longitude = $longitude;
    $this->startDate = $startDate;
    $this->endDate   = $endDate;
  }

  public function genHotelSearchXML(){

    $xml = new DOMDocument();
    $xml->preserveWhiteSpace = true;
    $xml->formatOutput = true;

      //OTA_HotelSearchRQ
    $OTA_HotelSearchRQ = $xml->createElement("OTA_HotelSearchRQ");

    // Set the attributes.
    $OTA_HotelSearchRQ->setAttribute("xmlns", "http://www.opentravel.org/OTA/2003/05");
    $OTA_HotelSearchRQ->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
    $OTA_HotelSearchRQ->setAttribute("xsi:schemaLocation", "http://www.opentravel.org/OTA/2003/05 OTA_HotelSearchRQ.xsd");
    $OTA_HotelSearchRQ->setAttribute("EchoToken", "HL");
    $OTA_HotelSearchRQ->setAttribute("Target", "Production");
    $OTA_HotelSearchRQ->setAttribute("Version", "2.004");
    $OTA_HotelSearchRQ->setAttribute("PrimaryLangID", "EN-US");
    $OTA_HotelSearchRQ->setAttribute("ResponseType", "PropertyList");

    $POS = $xml->createElement("POS");
    $OTA_HotelSearchRQ->appendChild($POS);

    $Source = $xml->createElement("Source");
    $POS->appendChild($Source);

    $RequestorID = $xml->createElement("RequestorID");
    $RequestorID->setAttribute("ID", "8508a7e6ce43e091");
    $RequestorID->setAttribute("ID_Context", "RZ");

    $Criteria = $xml->createElement("Criteria");
    $Criterion = $xml->createElement("Criterion");
    $Position = $xml->createElement("Position");
    $Position->setAttribute("Latitude", $this->latitude);
    $Criterion->appendChild($Position);

    $Position = $xml->createElement("Position");
    $Position->setAttribute("Longitude", $this->longitude);
    $Criterion->appendChild($Position);

    $Radius = $xml->createElement("Radius");
    $Radius->setAttribute("DistanceMeasure", "MILES");
    $Radius->setAttribute("Distance", $this->distance);
    $Criterion->appendChild($Radius);

    if($this->startDate && $this->endDate){
      $StayDateRange = $xml->createElement("StayDateRange");
      $StayDateRange->setAttribute("Start", $this->startDate);
      $StayDateRange->setAttribute("End", $this->endDate);
      $Criterion->appendChild($StayDateRange);
    }

    $Source->appendChild($RequestorID);



    $Criteria->appendChild($Criterion);
    $OTA_HotelSearchRQ->appendChild($Criteria);
    $xml->appendChild($OTA_HotelSearchRQ);
    
    

    return $xml->saveXML();;
    //echo $out; exit;
  }

  public function hotelSearchTemp(){
      $temp = '<?xml version="1.0" encoding="UTF-8"?>
      <OTA_HotelSearchRQ  xmlns="http://www.opentravel.org/OTA/2003/05" 
                          xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
                          xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_HotelSearchRQ.xsd" 
                          EchoToken="HL" 
                          Target="Production" 
                          Version="1.003" 
                          PrimaryLangID="EN-US" 
                          ResponseType="PropertyList"
       >
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
            <Position Latitude="7.88806" />
            <Position Longitude="98.3975" />
            <Radius DistanceMeasure="MILES" Distance="10000" />
            <StayDateRange Start="2004-08-02" End="2004-08-04" />
            <RefPoint>EIFFEL TOWER</RefPoint>
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
  }

  public function sendHotelSearchXML(){
    $url = 'http://pob-ws.heroku.com/api/hotel_search';
    $data = $this->genHotelSearchXML();
    //$data = $data->saveXML();

    //$data = $data->saveXML();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);

    curl_close($ch);

    //echo($response); exit;
    return $response;
  }



  public function getHotelSearchXML(){

    //Load reader class
    //Loader::loadClass('POBReader',"modules/POBPortal/pnincludes");
    //$reader = new POBReader();
    
    //Get data from search
    $response = $this->sendHotelSearchXML();
    //print $response; exit;
    //Convert xml to array
    //$arrayResponse = $reader->xmlToArray($response);
    //var_dump($arrayResponse); exit;
    return $response;
  }

}


?>