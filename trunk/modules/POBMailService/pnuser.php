<?php


/**
 * POBMailService main function use for sendmail
 * @author Parinya Bumrungchoo
 * @return XML response
 */
function POBMailService_user_main() {
    return POBMailService_user_sendmail();
  pnShutdown();
}




/**
 * POBMailService customer function use for customer
 * @author Parinya Bumrungchoo
 * @return XML response
 */
function POBMailService_user_sendmail(){

  $request = testMailServiceRQ();
  //$request = testMultiRoomStayMailServiceRQ();

  if($request){
    //Convert xml response to array
    Loader::loadClass('POBReader',"modules/POBMailService/pnincludes");
    $reader = new POBReader();

    $requestArray = $reader->xmlToArray($request);
    //print_r($requestArray); exit;
    $extractRequestArray = extractMailServiceArray($requestArray);

    $isSendCustomerMail = sendmail_customer($extractRequestArray);
    $isSendHotelMail = sendmail_hotel($extractRequestArray);

    if($isSendCustomerMail && $isSendHotelMail){
      $error = false;
    }else{
      $error = true;
    }
  }else{
    $error = true;
  }
  header("Content-type: text/xml");
  if($error){
    //This case can't sending mail.
    echo '<POB_MailServiceRS xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.opentravel.org/OTA/2003/05" SequenceNmbr="1" Target="Test" TimeStamp="2011-08-17T20:28:37" Version="1.001" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 HotelSearchRS.xsd">
<Errors>
<Error Type="1">Unknown</Error>
</Errors>
</POB_MailServiceRS>';
  }else{
    //This case is sending mail.
    echo '<POB_MailServiceRS xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.opentravel.org/OTA/2003/05" SequenceNmbr="1" Target="Test" TimeStamp="2011-08-17T20:28:37" Version="1.001" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 HotelSearchRS.xsd">
<Success/>
</POB_MailServiceRS>';
  }
  pnShutdown();
}


function sendmail_customer($extractRequestArray){

    //print_r($requestArray); exit;

    $toEmail = $form[email].",".$adminEmail;

    //Config email body
    $body = "Dear ".$form[nameprefix] .' ' .$form[givenname].",\n";
    $body.= "\tThank you for your enquiry. Your request as\n";
    $body.= "detailed below has been sent to one of our client\n";
    $body.= "service who will respond to you within 1-2 business day.\n";
    $body.= "Should you require any assistancen\n";
    $body.= "please contact us at info@phuketcity.com\n";
    $body.= "[BOOKING DETAILS]\n";
    $body.= "Request type: Make a booking\n";
    foreach($booking as $item) {
      $body.= " Room Type :".$item[invcode]."\n";
      $body.= " Room(s) :".$item[numberofunits]."\n";
      $body.= " Night(s) :".$item[night]."\n";
      $body.= " Adult :".$item[adult]."\n";
      if($item[child]){
      $body.= " Children :".$item[child]."\n";
      }
      $body.= " Price / One room / One night:".$item[rate]."\n";
      $body.= "Checkin Date :".$item[startdate]."\n";
      $body.= "Checkout Date :".$item[enddate]."\n";
    }
    $body.= "Addition requests:".$form[addition_request]."\n";
    $body.= "[CUSTOMER INFORMATION]\n";
    $body.= "name : ".$form[nameprefix]."".$form[givenname]."\t".$form[surname]."\t\n";
    $body.= "email : ".$form[email]."\n";
    $body.= "[OTHERS]\n";
    $body.= "--------BOOKING PRICE:".$form[total_price]." THB--------\n\n\n\n";
    $body.= "phuketcity.com Client Service\n";
    $body.= "info@phuketcity.com";
    $body.= " ";

    mail($toEmail,$subject,$body,$header);

    return true;
}


function sendmail_hotel($extractRequestArray){
    //Config email
    $adminEmail = "admin@phuketcity.com";
    $hotelEmail = "";
    $toEmail = $form[email].",".$adminEmail;

    //Config email body
    $body = "Dear ".$form[nameprefix] .' ' .$form[givenname].",\n";
    $body.= "\tThank you for your enquiry. Your request as\n";
    $body.= "detailed below has been sent to one of our client\n";
    $body.= "service who will respond to you within 1-2 business day.\n";
    $body.= "Should you require any assistancen\n";
    $body.= "please contact us at info@phuketcity.com\n";
    $body.= "[BOOKING DETAILS]\n";
    $body.= "Request type: Make a booking\n";
    foreach($booking as $item) {
      $body.= " Room Type :".$item[invcode]."\n";
      $body.= " Room(s) :".$item[numberofunits]."\n";
      $body.= " Night(s) :".$item[night]."\n";
      $body.= " Adult :".$item[adult]."\n";
      if($item[child]){
      $body.= " Children :".$item[child]."\n";
      }
      $body.= " Price / One room / One night:".$item[rate]."\n";
      $body.= "Checkin Date :".$item[startdate]."\n";
      $body.= "Checkout Date :".$item[enddate]."\n";
    }
    $body.= "Addition requests:".$form[addition_request]."\n";
    $body.= "[CUSTOMER INFORMATION]\n";
    $body.= "name : ".$form[nameprefix]."".$form[givenname]."\t".$form[surname]."\t\n";
    $body.= "email : ".$form[email]."\n";
    $body.= "[OTHERS]\n";
    $body.= "--------BOOKING PRICE:".$form[total_price]." THB--------\n\n\n\n";
    $body.= "phuketcity.com Client Service\n";
    $body.= "info@phuketcity.com";
    $body.= " ";

    mail($toEmail,$subject,$body,$header);

    return true;
}

  function extractMailServiceArray($requestArray){

    /////////////////////////////////////////
    // Get hotel information
    /////////////////////////////////////////
    //print_r($requestArray[HotelReservation][HotelInfo]); exit;
    $hotelCode = $requestArray[HotelReservation][HotelInfo][HotelInfo][HotelCode];
    $hotelName = $requestArray[HotelReservation][HotelInfo][HotelInfo][HotelName];
    $hotelEmail = $requestArray[HotelReservation][HotelInfo][HotelInfo][Email];


    /////////////////////////////////////////
    // Get one booking information
    /////////////////////////////////////////
    //echo count($requestArray[HotelReservation][RoomStays][RoomStay]); exit;
    //print_r($requestArray[HotelReservation][RoomStays][RoomStay][0]); exit;
    //print_r($requestArray[HotelReservation][RoomStays][RoomStay][0]); exit;
    $roomStays = $requestArray[HotelReservation][RoomStays][RoomStay][0];
    

    if(!$roomStays){
      $requestArray[HotelReservation][RoomStays][RoomStay][0] = $requestArray[HotelReservation][RoomStays][RoomStay];
      unset($requestArray[HotelReservation][RoomStays][RoomStay]);
    }
    echo count($requestArray[HotelReservation][RoomStays][RoomStay][0]); exit;
    print_r($requestArray[HotelReservation][RoomStays][RoomStay][0]); exit;
    
    /////////////////////////////////////////
    // Get one booking information
    /////////////////////////////////////////
    print_r($requestArray[HotelReservation][RoomStays][RoomStay][RoomRates][RoomRates]); exit;

    /////////////////////////////////////////
    // Get one booking information
    /////////////////////////////////////////
    print_r($requestArray[HotelReservation][RoomStays][RoomStay][RoomRates][RoomRate][RoomRate]['Date']); exit;
    print_r($requestArray[HotelReservation][RoomStays][RoomStay][RoomRates][RoomRate][RoomRate][Rate]); exit;



    /////////////////////////////////////////
    // Get customer information
    /////////////////////////////////////////
    print_r($requestArray[HotelReservation][Customer][PersonName]); exit;

    print_r($requestArray[HotelReservation][Customer][Telephone]); exit;

    print_r($requestArray[HotelReservation][Customer][Email]); exit;

    print_r($requestArray[HotelReservation][Customer][Address]); exit;

    print_r($requestArray[HotelReservation][Customer][PaymentCard]); exit;

  }

  function testMailServiceRQ(){
    $data = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<POBMailServiceRQ xmlns="http://www.opentravel.org/OTA/2003/05" Version="1.000" Target="Production" TimeStamp="2010-07-21T21:41:52">
  <HotelReservation>
    <HotelInfo HotelCode="SONGRIT"  HotelName="Songrit Hotel" Email="ottowan@gmail.com"/>
    <RoomStays><!-- RoomStay may have more than one tag(rooms) -->
      <RoomStay>
        <RoomTypes>
          <RoomType NumberOfUnits="1"/>
        </RoomTypes>
        <Inv InvCode="STD"/>
        <GuestCounts>
          <GuestCount Adult="2" Children="1"/>
        </GuestCounts>
        <RoomRates>
          <RoomRate Date="2011-08-02" Rate="2200" ></RoomRate>
          <RoomRate Date="2011-08-03" Rate="2200" ></RoomRate>
        </RoomRates>
        <Comments>
          <Comment>
            <Text>non-smoking room requested,king bed</Text>
          </Comment>
        </Comments>
      </RoomStay>
    </RoomStays>
    <Customer>
      <PersonName>
        <NamePrefix>Ms.</NamePrefix>
        <GivenName>Charlotte</GivenName>
        <Surname>Costello</Surname>
      </PersonName>
      <Telephone PhoneNumber="8145556123"/>
      <Email>ao_ottowan@hotmail.com</Email>
      <Address>
        <AddressLine>123 Locust St.</AddressLine>
        <CityName>Hyndman</CityName>
        <PostalCode>15545</PostalCode>
        <StateProv>PA</StateProv>
        <CountryName>USA</CountryName>
      </Address>
      <PaymentCard CardCode="VS" CardNumber="4111111111111202" ExpireDate="0506">
        <CardHolderName>Costello</CardHolderName>
      </PaymentCard>
    </Customer>
  </HotelReservation>
</POBMailServiceRQ>';
    return $data;
  }



  function testMultiRoomStayMailServiceRQ(){
    $data = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<POBMailServiceRQ xmlns="http://www.opentravel.org/OTA/2003/05" Version="1.000" Target="Production" TimeStamp="2010-07-21T21:41:52">
  <HotelReservation>
    <HotelInfo HotelCode="SONGRIT"  HotelName="Songrit Hotel" Email="ottowan@gmail.com"/>
    <RoomStays><!-- RoomStay may have more than one tag(rooms) -->
      <RoomStay>
        <RoomTypes>
          <RoomType NumberOfUnits="1"/>
        </RoomTypes>
        <Inv InvCode="STD"/>
        <GuestCounts>
          <GuestCount Adult="2" Children="1"/>
        </GuestCounts>
        <RoomRates>
          <RoomRate Date="2011-08-02" Rate="2200" ></RoomRate>
          <RoomRate Date="2011-08-03" Rate="2200" ></RoomRate>
        </RoomRates>
        <Comments>
          <Comment>
            <Text>non-smoking room requested,king bed</Text>
          </Comment>
        </Comments>
      </RoomStay>
      <RoomStay>
        <RoomTypes>
          <RoomType NumberOfUnits="1"/>
        </RoomTypes>
        <Inv InvCode="ATD"/>
        <GuestCounts>
          <GuestCount Adult="2" Children="1"/>
        </GuestCounts>
        <RoomRates>
          <RoomRate Date="2011-08-02" Rate="2200" ></RoomRate>
          <RoomRate Date="2011-08-03" Rate="2200" ></RoomRate>
        </RoomRates>
        <Comments>
          <Comment>
            <Text>non-smoking room requested,king bed</Text>
          </Comment>
        </Comments>
      </RoomStay>
    </RoomStays>
    <Customer>
      <PersonName>
        <NamePrefix>Ms.</NamePrefix>
        <GivenName>Charlotte</GivenName>
        <Surname>Costello</Surname>
      </PersonName>
      <Telephone PhoneNumber="8145556123"/>
      <Email>ao_ottowan@hotmail.com</Email>
      <Address>
        <AddressLine>123 Locust St.</AddressLine>
        <CityName>Hyndman</CityName>
        <PostalCode>15545</PostalCode>
        <StateProv>PA</StateProv>
        <CountryName>USA</CountryName>
      </Address>
      <PaymentCard CardCode="VS" CardNumber="4111111111111202" ExpireDate="0506">
        <CardHolderName>Costello</CardHolderName>
      </PaymentCard>
    </Customer>
  </HotelReservation>
</POBMailServiceRQ>';
    return $data;
  }