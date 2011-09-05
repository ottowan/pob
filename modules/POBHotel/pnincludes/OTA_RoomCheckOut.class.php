<?php
class OTA_RoomCheckOut {
  
  private $hotelCode;
  private $startDate;
  private $endDate;
  private $quantity;
  private $invCode;
  
  private $hotelData;
  
  function __construct(){
    
  }
  
  public function checkOut($hotelCode='',$invCode='',$startDate='',$endDate='',$quantity=0){
    if($hotelCode==''||$startDate==''||$endDate==''||$quantity==0||$invCode==''){
      return FALSE;
    }
    
    $this->hotelCode = $hotelCode;
    $this->startDate = $startDate;
    $this->endDate = $endDate;
    $this->quantity = $quantity;
    $this->invCode = $invCode;
    
    $this->hotelData = $this->getHotelAvail();
    
    header("content-type:text/xml");
    $result = $this->sendContent($this->genHotelAvailNotif(),"http://pob-ws.heroku.com/api/hotel_avail_notif");
    
    return $result;
  }
  
  public function getHotelAvail(){
    $xml = new DOMDocument('1.0','utf-8');
    $xml->formatOutput = true;
      //OTA_HotelDescriptiveContentNotifRQ
    $POB_HotelAvailRQ = $xml->createElement("POB_HotelAvailRQ");
    // Set the attributes.
    $POB_HotelAvailRQ->setAttribute("xmlns", "http://www.opentravel.org/OTA/2003/05");
    $POB_HotelAvailRQ->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
    $POB_HotelAvailRQ->setAttribute("xsi:schemaLocation", "http://www.opentravel.org/OTA/2003/05 OTA_HotelAvailRQ.xsd");
    $POB_HotelAvailRQ->setAttribute("Version", "1.003");

    $POS = $xml->createElement("POS");
    $POB_HotelAvailRQ->appendChild($POS);
    $Source = $xml->createElement("Source");
    $RequestorID = $xml->createElement("RequestorID");
    $RequestorID->setAttribute("Type", "1");
    $RequestorID->setAttribute("ID", "638fdJa7vRmkLs5");
    $Source->appendChild($RequestorID);
    $POS->appendChild($Source);
    
    $AvailRequestSegments = $xml->createElement("AvailRequestSegments");
    $AvailRequestSegment = $xml->createElement("AvailRequestSegment");
    $StayDateRange = $xml->createElement("StayDateRange");
    $StayDateRange->setAttribute("Start",$this->startDate);
    $StayDateRange->setAttribute("End",$this->endDate);
    
    $HotelSearchCriteria = $xml->createElement("HotelSearchCriteria");
    $Criterion = $xml->createElement("Criterion");
    $HotelRef = $xml->createElement("HotelRef");;
    $HotelRef->setAttribute("HotelCode",$this->hotelCode);
    
    $Criterion->appendChild($HotelRef);
    $HotelSearchCriteria->appendChild($Criterion);
    
    $AvailRequestSegment->appendChild($StayDateRange);
    $AvailRequestSegment->appendChild($HotelSearchCriteria);
    
    $AvailRequestSegments->appendChild($AvailRequestSegment);
    $POB_HotelAvailRQ->appendChild($AvailRequestSegments);
    $xml->appendChild($POB_HotelAvailRQ);
    
    $response = simplexml_load_string($this->sendContent($xml->saveXML(),'http://pob-ws.heroku.com/api/hotel_avail'));
    
    $datas["HotelData"]["HotelCode"] = (string)$response->Properties->Property->attributes()->HotelCode;
    $datas["HotelData"]["HotelName"] = (string)$response->Properties->Property->attributes()->HotelName;
    
    foreach($response->Properties->Property->Availabilities->Availability AS $Availability){
      if(((string)$Availability->attributes()->InvCode) == $this->invCode){
        $data["InvCode"] = (string)$Availability->attributes()->InvCode;
        $data["Limit"] = (string)$Availability->attributes()->Limit;
        $data["Rate"] = (string)$Availability->attributes()->Rate;
        $data["RatePlanCode"] = (string)$Availability->attributes()->RatePlanCode;
        $datas["Avail"][] = $data;
      }
    }
    return $datas;
  }
  
  public function sendContent($data='',$url=''){
    if($data==''||$url==''){
      return FALSE;
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);

    curl_close($ch);
    
    return $response;
  }
  
  private function genHotelAvailNotif(){
    $xml = new DOMDocument('1.0','utf-8');
    $xml->formatOutput = true;
      //OTA_HotelDescriptiveContentNotifRQ
    $OTA_HotelAvailNotifRQ = $xml->createElement("OTA_HotelAvailNotifRQ");
    // Set the attributes.
    $OTA_HotelAvailNotifRQ->setAttribute("xmlns", "http://www.opentravel.org/OTA/2003/05");
    $OTA_HotelAvailNotifRQ->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
    $OTA_HotelAvailNotifRQ->setAttribute("xsi:schemaLocation", "http://www.opentravel.org/OTA/2003/05 OTA_HotelAvailNotifRQ.xsd");
    $OTA_HotelAvailNotifRQ->setAttribute("Version", "1.002");
    $OTA_HotelAvailNotifRQ->setAttribute("Target", "Production");

    $POS = $xml->createElement("POS");
    $OTA_HotelAvailNotifRQ->appendChild($POS);
    $Source = $xml->createElement("Source");
    $POS->appendChild($Source);
    $RequestorID = $xml->createElement("RequestorID");
    $RequestorID->setAttribute("Type", "1");
    $RequestorID->setAttribute("ID", "638fdJa7vRmkLs5");
    $Source->appendChild($RequestorID);

    $AvailStatusMessages  = $xml->createElement("AvailStatusMessages");

    $AvailStatusMessages->setAttribute("ChainCode", $this->hotelData["HotelData"]["HotelCode"]);
    $AvailStatusMessages->setAttribute("BrandCode", $this->hotelData["HotelData"]["HotelCode"]);
    $AvailStatusMessages->setAttribute("HotelCode", $this->hotelData["HotelData"]["HotelCode"]);
    $AvailStatusMessages->setAttribute("HotelName", $this->hotelData["HotelData"]["HotelName"]);
    
    if(isset($this->hotelData["Avail"])){
      foreach($this->hotelData["Avail"] AS $guestRoom){
        $AvailStatusMessage = $xml->createElement("AvailStatusMessage");
        $AvailStatusMessage->setAttribute("BookingLimit",($guestRoom["Limit"]+$this->quantity));
        $AvailStatusMessage->setAttribute("BookingLimitMessageType","SetLimit");
        $StatusApplicationControl = $xml->createElement("StatusApplicationControl");
        $StatusApplicationControl->setAttribute("Start",$this->startDate);
        $StatusApplicationControl->setAttribute("End",$this->endDate);
        $StatusApplicationControl->setAttribute("RatePlanCode",$guestRoom["RatePlanCode"]);
        $StatusApplicationControl->setAttribute("InvCode",$guestRoom["InvCode"]);
        $StatusApplicationControl->setAttribute("Rate",$guestRoom["Rate"]);
  
        $UniqueID  = $xml->createElement("UniqueID");
        $UniqueID->setAttribute("Type",16);
        $UniqueID->setAttribute("ID",1);
        
        $AvailStatusMessage->appendChild($StatusApplicationControl);
        $AvailStatusMessage->appendChild($UniqueID);
        $AvailStatusMessages->appendChild($AvailStatusMessage);
      }
    }

    $OTA_HotelAvailNotifRQ->appendChild($AvailStatusMessages);
    $xml->appendChild($OTA_HotelAvailNotifRQ);
    return $xml->saveXML();
  }
}
?>