<?
$data = "<?xml version='1.0' encoding='UTF-8'?> 
<OTA_HotelResRQ xmlns='http://www.opentravel.org/OTA/2003/05' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://www.opentravel.org/OTA/2003/05 OTA_HotelResRQ.xsd' Version='1.000'> 
  <POS> 
    <Source ISOCurrency='THB'/> 
  </POS> 
  <HotelReservations> 
    <HotelReservation> 
      <RoomStays> 
        <RoomStay> 
          <RoomTypes> 
            <RoomType NumberOfUnits='3'/> 
          </RoomTypes> 
          <Inv InvCode='Swimming Suite'/> 
          <GuestCounts> 
            <GuestCount AgeQualifyingCode='10' Count='2'/> 
          </GuestCounts> 
          <TimeSpan End='2011-08-12' Start='2011-08-11'/> 
          <Guarantee> 
            <GuaranteesAccepted> 
              <GuaranteeAccepted> 
                <PaymentCard CardCode='VC' CardNumber='1234567890121235' ExpireDate='0112'> 
                  <CardHolderName>TRaarr</CardHolderName> 
                </PaymentCard> 
              </GuaranteeAccepted> 
            </GuaranteesAccepted> 
          </Guarantee> 
          <BasicPropertyInfo ChainCode='HT' HotelCode='SONGRIT'/> 
          <Comments> 
            <Comment> 
              <Text></Text> 
            </Comment> 
          </Comments> 
        </RoomStay> 
      </RoomStays> 
      <ResGuests> 
        <ResGuest> 
          <Profiles> 
            <ProfileInfo> 
              <Profile ProfileType='1'> 
                <Customer> 
                  <PersonName> 
                    <NamePrefix>Mr.</NamePrefix> 
                    <GivenName>Kuuu</GivenName> 
                    <Surname>Kuuu</Surname> 
                  </PersonName> 
                  <Telephone PhoneNumber='0802325648' PhoneTechType='5'/> 
                  <Email>admin@mail.com</Email> 
                  <Address> 
                    <AddressLine>432</AddressLine> 
                    <CityName>Meang</CityName> 
                    <PostalCode>83000</PostalCode> 
                    <StateProv>Phuket</StateProv> 
                    <CountryName>Thailand</CountryName> 
                  </Address> 
                </Customer> 
              </Profile> 
            </ProfileInfo> 
          </Profiles> 
        </ResGuest> 
      </ResGuests> 
    </HotelReservation> 
  </HotelReservations> 
</OTA_HotelResRQ> 
";

////send xml
    $url = 'http://pob-ws.heroku.com/api/hotel_res';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    //header ("Content-Type:text/xml");
    $response = curl_exec($ch);
    //header ("Content-Type:text/xml");
    var_dump($response);

    $mystring = $response;
    $findme   = 'Success';
    $pos = strpos($mystring, $findme);
    if($pos > 0){
      echo "Booking Success.";
    }else{
      echo "Booking NOT Success.";
    }

    //$items = xmlToArray($response);
    //print_r($items);
    curl_close($ch);