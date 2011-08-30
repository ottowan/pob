<?php
class OTA_RoomCheckOut {
  
  private $hotelCode;
  private $startDate;
  private $endDate;
  private $quantity;

  function __construct(){
    
  }
  
  public function checkOut($hotelCode='',$startDate='',$endDate='',$quantity=0){
    if($hotelCode==''||$startDate==''||$endDate==''||$quantity==0){
      return FALSE;
    }
    
    $this->hotelCode = $hotelCode;
    $this->startDate = $startDate;
    $this->endDate = $endDate;
    $this->quantity = $quantity;
    
  }
  
  
  private function genHotelAvailNotif(){
    $xml = new DOMDocument('1.0','utf-8');
    $xml->formatOutput = true;
      //OTA_HotelDescriptiveContentNotifRQ
    $OTA_HotelAvailNotifRQ = $xml->createElement("OTA_HotelAvailNotifRQ");
    // Set the attributes.
    $OTA_HotelAvailNotifRQ->setAttribute("xmlns", "http://www.opentravel.org/OTA/2003/05");
    $OTA_HotelAvailNotifRQ->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
    $OTA_HotelAvailNotifRQ->setAttribute("xsi:schemaLocation", "http://www.opentravel.org/OTA/2003/05OTA_HotelAvailNotifRQ.xsd");
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

    $AvailStatusMessages->setAttribute("ChainCode", $this->hotelObject["code"]);
    $AvailStatusMessages->setAttribute("BrandCode", $this->hotelObject["code"]);
    $AvailStatusMessages->setAttribute("HotelCode", $this->hotelObject["code"]);
    $AvailStatusMessages->setAttribute("HotelName", $this->hotelObject["name"]);

    foreach($this->guestRoomObject AS $guestRoom){
      $AvailStatusMessage = $xml->createElement("AvailStatusMessage");
      $AvailStatusMessage->setAttribute("BookingLimit",$guestRoom["limit"]);
      $AvailStatusMessage->setAttribute("BookingLimitMessageType","SetLimit");
      $StatusApplicationControl = $xml->createElement("StatusApplicationControl");
      $StatusApplicationControl->setAttribute("Start",$guestRoom["season_date_start"]);
      $StatusApplicationControl->setAttribute("End",$guestRoom["season_date_end"]);
      $StatusApplicationControl->setAttribute("RatePlanCode",$guestRoom["type_name"]);
      $StatusApplicationControl->setAttribute("InvCode",$guestRoom["type_name"]);
      $StatusApplicationControl->setAttribute("Rate",$guestRoom["price"]);
      $StatusApplicationControl->setAttribute("MaxCapacity",$guestRoom["capacity"]);

      $MultimediaDescriptions = $xml->createElement("MultimediaDescriptions");
      $MultimediaDescription = $xml->createElement("MultimediaDescription");
      $TextItems = $xml->createElement("TextItems");
      $TextItem = $xml->createElement("TextItem");
      $TextItem->setAttribute("Title","Description");
      $Description = $xml->createElement("Description",htmlentities($guestRoom["type_description"]));
      $TextItem->appendChild($Description);
      $TextItems->appendChild($TextItem);
      $MultimediaDescription->appendChild($TextItems);
      $MultimediaDescriptions->appendChild($MultimediaDescription);

      $UniqueID  = $xml->createElement("UniqueID");
      $UniqueID->setAttribute("Type",16);
      $UniqueID->setAttribute("ID",1);

      $StatusApplicationControl->appendChild($MultimediaDescriptions);
      $AvailStatusMessage->appendChild($StatusApplicationControl);
      $AvailStatusMessage->appendChild($UniqueID);
      $AvailStatusMessages->appendChild($AvailStatusMessage);
    }

    $OTA_HotelAvailNotifRQ->appendChild($AvailStatusMessages);
    $xml->appendChild($OTA_HotelAvailNotifRQ);
    return $xml->saveXML();
  }
}
?>