<?php
  /**
  * 
  * 
  * 
  */
Class HotelDescContentGenerator {
  private $hotelObject = NULL;
  private $locationObject = NULL;
  private $hotelAmenity = NULL;
  private $facilityInfoObject = NULL;
  
  function __construct($hotelId=''){
  
    $pntables = pnDBGetTables();


    if (!($class = Loader::loadClassFromModule ('POBHotel', 'HotelArray', false)))
      return LogUtil::registerError ('Unable to load class [HotelArray] ...');
      
    $objectArray = new $class;
    $objectArray->get(' WHERE hotel_id = '.$hotelId);
    $this->hotelObject = $objectArray->_objData[0];
    
    if (!($class = Loader::loadClassFromModule ('POBHotel', 'HotelLocationArray', false)))
      return LogUtil::registerError ('Unable to load class [HotelLocationArray] ...');
    
    $objectArray = new $class;
    $objectArray->get();
    $this->locationObject = $objectArray->_objData;
    
    if (!($class = Loader::loadClassFromModule ('POBHotel', 'HotelAmenityArray', false)))
      return LogUtil::registerError ('Unable to load class [HotelAmenityArray] ...');
    
    $objectArray = new $class;
    $objectArray->get();
    $this->hotelAmenity = $objectArray->_objData;
    
    if (!($class = Loader::loadClassFromModule ('POBHotel', 'HotelImageArray', false)))
      return LogUtil::registerError ('Unable to load class [HotelImageArray] ...');
    
    $objectArray = new $class;
    $objectArray->get();
    $this->imageObject  = $objectArray->_objData;

  }
  public function getContent(){
    return $this->genHotelDescriptive();
  }
  public function sendContent(){
    $url = 'http://pob-ws.heroku.com/api/hotel_descriptive_content_notif';
    $data = $this->genHotelDescriptive();
    $data = $data->saveXML();
    header("Content-type: text/xml");
    print $data;
    exit;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);

    curl_close($ch);

    return $response;
  }
  private function genHotelDescriptive(){
    $xml = new DOMDocument('1.0','utf-8');
    $xml->formatOutput = true;
      //OTA_HotelDescriptiveContentNotifRQ
    $OTA_HotelDescriptiveContentNotifRQ = $xml->createElement("OTA_HotelDescriptiveContentNotifRQ");
    // Set the attributes.
    $OTA_HotelDescriptiveContentNotifRQ->setAttribute("xmlns", "http://www.opentravel.org/OTA/2003/05");
    $OTA_HotelDescriptiveContentNotifRQ->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
    $OTA_HotelDescriptiveContentNotifRQ->setAttribute("xsi:schemaLocation", "http://www.opentravel.org/OTA/2003/05 OTA_HotelDescriptiveContentNotifRQ.xsd");
    $OTA_HotelDescriptiveContentNotifRQ->setAttribute("Version", "2.001");

    $HotelDescriptiveContents = $xml->createElement("HotelDescriptiveContents");
    $HotelDescriptiveContent = $xml->createElement("HotelDescriptiveContent");
    
    $HotelDescriptiveContent->setAttribute("BrandCode","MHRS");
    $HotelDescriptiveContent->setAttribute("BrandName",htmlentities($this->hotelObject["name"]));
    $HotelDescriptiveContent->setAttribute("CurrencyCode","THB");
    $HotelDescriptiveContent->setAttribute("HotelCode","BOSCO");
    $HotelDescriptiveContent->setAttribute("HotelName",htmlentities($this->hotelObject["name"]));
    $HotelDescriptiveContent->setAttribute("LanguageCode","TH");
    
    $LoadedXML = DOMDocument::loadXML($this->genHotelInfo());
    $HotelInfoNode = $xml->importNode($LoadedXML->getElementsByTagName("HotelInfo")->item(0), true);
    $HotelDescriptiveContent->appendChild($HotelInfoNode);
    
    if(!is_null($this->facilityInfoObject)){
      $LoadedXML = DOMDocument::loadXML($this->genFacilityInfo());
      $FacilityInfoNode = $xml->importNode($LoadedXML->getElementsByTagName("FacilityInfo")->item(0), true);
      $HotelDescriptiveContent->appendChild($FacilityInfoNode);
    }
    
    $LoadedXML = DOMDocument::loadXML($this->genPolicies());
    $PoliciesNode = $xml->importNode($LoadedXML->getElementsByTagName("Policies")->item(0), true);
    $HotelDescriptiveContent->appendChild($PoliciesNode);
    
    $LoadedXML = DOMDocument::loadXML($this->genAreaInfo());
    $AreaInfoNode = $xml->importNode($LoadedXML->getElementsByTagName("AreaInfo")->item(0), true);
    $HotelDescriptiveContent->appendChild($AreaInfoNode);
    
    $LoadedXML = DOMDocument::loadXML($this->genContractInfo());
    $ContractInfoNode = $xml->importNode($LoadedXML->getElementsByTagName("ContactInfo")->item(0), true);
    $HotelDescriptiveContent->appendChild($ContractInfoNode);
    
    $HotelDescriptiveContents->appendChild($HotelDescriptiveContent);
    $OTA_HotelDescriptiveContentNotifRQ->appendChild($HotelDescriptiveContents);
    $xml->appendChild($OTA_HotelDescriptiveContentNotifRQ);
    
    return $xml;
  }
  private function genFacilityInfo(){
  
    if(!is_null($this->facilityInfoObject)){
      $xml = new DOMDocument();
      $xml->formatOutput = true;
      $facilityInfo = $xml->createElement("FacilityInfo");
      if(isset($this->facilityInfoObject[0]['lu_date'])){
        $facilityInfo->setAttribute("LastUpdated",str_replace(" ","T",$this->facilityInfoObject[0]['lu_date']));
      }
      
      $guestRooms = $xml->createElement("GuestRooms");
      foreach($this->facilityInfoObject AS $key=>$value){
        $guestRoom = $xml->createElement("GuestRoom");
        
        $guestRoom->setAttribute("CodeContext","MARSHA Room Type");
        $guestRoom->setAttribute("Quantity","1033");
        $guestRoom->setAttribute("NonsmokingQuantity","923");

        if($value["capacity"]>=1){
          $guestRoom->setAttribute("MaxOccupancy",htmlentities($value["capacity"]));
        }
        if(isset($value["name"])){
          $guestRoom->setAttribute("RoomTypeName",htmlentities($value["name"]));
        }
        $MultimediaDescriptions = $xml->createElement("MultimediaDescriptions");
        $MultimediaDescription = $xml->createElement("MultimediaDescription");
        $TextItems = $xml->createElement("TextItems");
        $TextItem = $xml->createElement("TextItem");
        $TextItem->setAttribute("Title","Room Description");
        $Description = $xml->createElement("Description",htmlentities($value["description"]));
        
        $TextItem->appendChild($Description);
        $TextItems->appendChild($TextItem);
        $MultimediaDescription->appendChild($TextItems);
        $MultimediaDescriptions->appendChild($MultimediaDescription);
        $guestRoom->appendChild($MultimediaDescriptions);
        $guestRooms->appendChild($guestRoom);
      }
      $facilityInfo->appendChild($guestRooms);

      
      $xml->appendChild($facilityInfo);
      return $xml->saveXML();
      
    }else{
      return FALSE;
    }
    
  }
  private function genHotelInfo(){
  
    
    $xml = new DOMDocument();
    //$xml->formatOutput = true;
    
    
    $HotelInfo = $xml->createElement("HotelInfo");
    $HotelInfo->setAttribute("HotelStatus",htmlentities($this->hotelObject["status_name"]));
    $HotelInfo->setAttribute("LastUpdated",str_replace(" ","T",$this->hotelObject["lu_date"]));
    $HotelInfo->setAttribute("Start",htmlentities($this->hotelObject["start"]));
    $HotelInfo->setAttribute("WhenBuilt",substr($this->hotelObject["when_built"],0,4));
    
    $CategoryCodes = $xml->createElement("CategoryCodes");

    foreach($this->locationObject AS $key=>$value){
      if(!is_null($value["location_id"])){
        $LocationCategory = $xml->createElement("LocationCategory");
        $LocationCategory->setAttribute("Code",$value["location_id"]);
        $LocationCategory->setAttribute("CodeDetail","Location Type: ".htmlentities($value["location_name"]));
        
        $CategoryCodes->appendChild($LocationCategory);
      }

    }
    
    $Position = $xml->createElement("Position");
    $Position->setAttribute("Latitude",$this->hotelObject["position_latitude"]);
    $Position->setAttribute("Longitude",$this->hotelObject["position_longitude"]);
    
    $Services = $xml->createElement("Services");
    
    foreach($this->hotelAmenity AS $key=>$value){
      $Service = $xml->createElement("Service");
      $Service->setAttribute("Code",$value['amenity_id']);
      $MultimediaDescriptions = $xml->createElement("MultimediaDescriptions");
      $MultimediaDescription = $xml->createElement("MultimediaDescription");
      $TextItems = $xml->createElement("TextItems");
      $TextItem = $xml->createElement("TextItem");
      $TextItem->setAttribute("Title",htmlentities($value["amenity_name"]));
      $Description = $xml->createElement("Description",htmlentities($value["description"]));
      $TextItem->appendChild($Description);
      $TextItems->appendChild($TextItem);
      $MultimediaDescription->appendChild($TextItems);
      $MultimediaDescriptions->appendChild($MultimediaDescription);
      $Service->appendChild($MultimediaDescriptions);
      $Services->appendChild($Service);
    }
    
    $Descriptions = $xml->createElement("Descriptions");
    $MultimediaDescriptions = $xml->createElement("MultimediaDescriptions");
    $MultimediaDescription = $xml->createElement("MultimediaDescription");
    $TextItems = $xml->createElement("TextItems");
    $TextItem = $xml->createElement("TextItem");
    $TextItem->setAttribute("Title","Description");
    $Description = $xml->createElement("Description",htmlentities($this->hotelObject["descriptions"]));
    $TextItem->appendChild($Description);
    $TextItems->appendChild($TextItem);
    $TextItem = $xml->createElement("TextItem");
    $TextItem->setAttribute("Title","image");
    $i=0;
    foreach($this->imageObject AS $key=>$value){
      $Description = $xml->createElement("Description",htmlentities($_SERVER["HOST_NAME"].$value["filepath"].$value["filename"]));
      $Description->setAttribute("ListItem",$i);
      $TextItem->appendChild($Description);
      $i++;
    }
    $TextItems->appendChild($TextItem);
    $TextItem = $xml->createElement("TextItem");
    $TextItem->setAttribute("Title","thumb");
    $i=0;
    foreach($this->imageObject AS $key=>$value){
      $Description = $xml->createElement("Description",htmlentities($_SERVER["HOST_NAME"].$value["thumbpath"].$value["filename"]));
      $Description->setAttribute("ListItem",$i);
      $TextItem->appendChild($Description);
      $i++;
    }
    
    $TextItems->appendChild($TextItem);
    $MultimediaDescription->appendChild($TextItems);
    $MultimediaDescriptions->appendChild($MultimediaDescription);
    $Descriptions->appendChild($MultimediaDescriptions);
      
      
      
    $HotelInfo->appendChild($CategoryCodes);
    $HotelInfo->appendChild($Descriptions);
    $HotelInfo->appendChild($Position);
    $HotelInfo->appendChild($Services);
    $xml->appendChild($HotelInfo);
    return $xml->saveXML();
  }
  private function genPolicies(){
    //$xml = new DOMDocument();
    //$xml->formatOutput = true;
    //$Policies = $xml->createElement("Policies");
    //$xml->appendChild($Policies);
    //return $xml->saveXML();
    $data = '<?xml version="1.0" encoding="UTF-8"?><Policies>
				<Policy>
					<CancelPolicy>
						<CancelPenalty>
							<PenaltyDescription Name="Cancellation Policy">
								<Text>6:00 PM</Text>
							</PenaltyDescription>
						</CancelPenalty>
					</CancelPolicy>
					<GuaranteePaymentPolicy>
						<GuaranteePayment PaymentCode="2"/>
						<!--Payment Code 2 = Direct bill from OTA Code Table PMT-->
					</GuaranteePaymentPolicy>
					<PolicyInfoCodes>
						<PolicyInfoCode>
							<Description Name="Oversold - Phone Call Home/Business">
								<Text>Y</Text>
							</Description>
						</PolicyInfoCode>
						<PolicyInfoCode Name="OversoldArrangeAccommodations"/>
						<PolicyInfoCode Name="OversoldPayOneNightRoom"/>
						<PolicyInfoCode Name="OversoldArrangeTransportation"/>
					</PolicyInfoCodes>
					<CheckoutCharges>
						<CheckoutCharge CodeDetail="Late Check-Out Available" Type="Late">
							<Description Name="Late Check-Out Fees">
								<Text>00</Text>
							</Description>
						</CheckoutCharge>
					</CheckoutCharges>
					<PolicyInfo CheckInTime="16:00:00" CheckOutTime="12:00:00" KidsStayFree="true" TotalGuestCount="5"/>
					<TaxPolicies>
						<TaxPolicy Amount="5" Code="7"/>
						<!--Code 7 = Food & beverage tax from OTA Code Table FTT-->
						<TaxPolicy Code="10" NightsForTaxExemptionQuantity="90" Percent="5.7"/>
						<!--Code 10 = Occupancy tax from OTA Code Table FTT-->
						<TaxPolicy Code="17" Percent="12.45"/>
						<!--Code 17 = Total tax from OTA Code Table FTT-->
						<TaxPolicy Amount="2.75" Code="27"/>
						<!--Code 27 = Miscellaneous from OTA Code Table FTT-->
					</TaxPolicies>
				</Policy>
			</Policies>';
      
      return $data;
  }
  private function genAreaInfo(){
    //$xml = new DOMDocument();
    //$xml->formatOutput = true;
    //$AreaInfo = $xml->createElement("AreaInfo");
    //$xml->appendChild($AreaInfo);
    //return $xml->saveXML();
    
    $data = '<?xml version="1.0" encoding="UTF-8"?><AreaInfo>
				<RefPoints>
					<RefPoint Distance=".3" IndexPointCode="3" Name="Expway, Route 93, Mass. Turnpike">
						<!--Index Point Code 3 = Highway from OTA Code Table IPC-->
						<Transportations>
							<Transportation>
								<MultimediaDescriptions>
									<MultimediaDescription>
										<TextItems>
											<TextItem Title="Directions to Highway from Property">
												<Description>Please call the hotel for directions</Description>
											</TextItem>
										</TextItems>
									</MultimediaDescription>
								</MultimediaDescriptions>
							</Transportation>
						</Transportations>
					</RefPoint>
				</RefPoints>
				<Attractions LastUpdated="2004-11-10T15:50:30">
					<Attraction AttractionCategoryCode="1" AttractionName="Boston" Code="BOS" CourtesyPhone="true">
						<!--Attraction Category Code 1 = Airport from OTA Code Table ACC-->
						<Contact>
							<Addresses>
								<Address>
									<CityName>BOSTON</CityName>
									<StateProv>MA</StateProv>
									<CountryName>US</CountryName>
								</Address>
							</Addresses>
							<Phones>
								<Phone PhoneLocationType="2" PhoneNumber="8002353426" PhoneTechType="1" PhoneUseType="5"/>
								<!--Phone Location Type 2 = Central reservations office from OTA Code Table PLT, Phone Tech Type 1 = Voice from OTA Code Table PTT, Phone Use Type 5 = Contact from OTA Code Table PUT-->
							</Phones>
						</Contact>
						<MultimediaDescriptions>
							<MultimediaDescription>
								<TextItems>
									<TextItem Title="Sort Order">
										<Description>1</Description>
									</TextItem>
									<TextItem Title="AirportName/Code">
										<Description>Boston (BOS)</Description>
									</TextItem>
									<TextItem Title="Driving Instructions from Airport">
										<Description>East on Mass Turnpike (Route 90).  Take the Copley Square exit which exits onto Stuart Street.  At the first light turn left onto Dartmouth Street.  At the next light turn left onto Huntington Avenue and stay in the left lane.  At the light under the skybridge make a u-turn to the left.  The hotel is in the right.</Description>
									</TextItem>
								</TextItems>
							</MultimediaDescription>
						</MultimediaDescriptions>
						<RefPoints>
							<RefPoint Direction="W" Distance="4">
								<Transportations>
									<Transportation Description="Drive Time to Airport" TransportationCode="5" TypicalTravelTime="20"/>
									<!--Transportation Code 5 = Car from OTA Code Table TRP-->
									<Transportation Amount="10" CodeDetail="Fee" Description="Shuttle Service from Airport" NotificationRequired="On Request" TransportationCode="17"/>
									<!--Transportation Code 17 = Shuttle from OTA Code Table TRP-->
									<Transportation Amount="1" Description="Subway from Airport to Hotel Fee (one-way)" TransportationCode="18"/>
									<!--Transportation Code 18 = Subway from OTA Code Table TRP-->
									<Transportation Amount="30" Description="Estimated Taxi Fee (one-way)" TransportationCode="20"/>
									<!--Transportation Code 20 = Taxi from OTA Code Table TRP-->
									<Transportation Amount="10" CodeDetail="On Request" Description="Alternate Transportation Fee" TransportationCode="26">
										<!--Transportation Code 26 = Other or alternate from OTA Code Table TRP-->
										<MultimediaDescriptions>
											<MultimediaDescription>
												<TextItems>
													<TextItem Title="Alternate Transportation Name">
														<Description>Boston Hotels Shuttle</Description>
													</TextItem>
												</TextItems>
											</MultimediaDescription>
										</MultimediaDescriptions>
									</Transportation>
									<Transportation Description="Drive Time to Airport in Rush Hour" TransportationCode="27" TypicalTravelTime="30"/>
									<!--Transportation Code 27 = Car/rush hour from OTA Code Table TRP-->
									<Transportation Amount="35" Description="Estimated Taxi Fee (one-way) Rush Hour" TransportationCode="28"/>
									<!--Transportation Code 28 = Taxi/rush hour from OTA Code Table TRP-->
								</Transportations>
							</RefPoint>
						</RefPoints>
					</Attraction>
					<Attraction AttractionCategoryCode="62" AttractionName="Quincy Market/Faneuil Hall">
						<!--Attraction Category Code 62 = Other from OTA Code Table ACC-->
						<Contact>
							<Addresses>
								<Address>
									<CityName>Boston</CityName>
									<StateProv>MA</StateProv>
									<CountryName>USA</CountryName>
								</Address>
							</Addresses>
						</Contact>
						<RefPoints>
							<RefPoint Distance="1">
								<Transportations>
									<Transportation CodeDetail="N/A" TransportationCode="17"/>
									<!--Transportation Code 17 = Shuttle from OTA Code Table TRP-->
								</Transportations>
							</RefPoint>
						</RefPoints>
					</Attraction>
				</Attractions>
				<Recreations>
					<Recreation>
						<MultimediaDescriptions>
							<MultimediaDescription>
								<TextItems>
									<TextItem Title="Recreational Summary">
										<Description>Full health club with indoor pool and workout facilities.  Nearby are many jogging areas and other health facilities with more complete information available through our concierge.  Golf and tennis nearby.</Description>
									</TextItem>
								</TextItems>
							</MultimediaDescription>
						</MultimediaDescriptions>
					</Recreation>
					<Recreation Code="36" CodeDetail="Onsite" Name="The Health Club">
						<!--Code 36 = Health club from OTA Code Table RST-->
						<OperationSchedules>
							<OperationSchedule>
								<OperationTimes>
									<OperationTime End="23:00:00" Fri="true" Mon="true" Start="05:30:00" Thur="true" Tue="true" Weds="true"/>
									<OperationTime End="23:00:00" Sat="true" Start="06:00:00" Sun="true"/>
								</OperationTimes>
							</OperationSchedule>
							<OperationSchedule>
								<Charge Amount="0"/>
							</OperationSchedule>
						</OperationSchedules>
						<RefPoints>
							<RefPoint Distance="0"/>
						</RefPoints>
						<MultimediaDescriptions>
							<MultimediaDescription>
								<TextItems>
									<TextItem Title="Sort Order">
										<Description>1</Description>
									</TextItem>
									<TextItem Title="Year fitness center equipment was most recently replaced">
										<Description>2000</Description>
									</TextItem>
									<TextItem Title="Fitness Center Type">
										<Description>EXTENSIVE</Description>
									</TextItem>
								</TextItems>
							</MultimediaDescription>
						</MultimediaDescriptions>
						<RecreationDetails>
							<RecreationDetail Code="35">
								<!--Code 35 = Classes available from OTA Code Table REC-->
								<Description Name="Fitness Classes">
									<Text>N/A</Text>
								</Description>
							</RecreationDetail>
							<RecreationDetail Code="41">
								<!--Code 41 = Services available from OTA Code Table REC-->
								<Description Name="Fitness Service">
									<Text>Massages</Text>
								</Description>
							</RecreationDetail>
						</RecreationDetails>
					</Recreation>
					<Recreation Code="122" Name="Indoor Pool">
						<!--Code 122 = Indoor pool from OTA Code Table RST-->
						<OperationSchedules>
							<OperationSchedule>
								<OperationTimes>
									<OperationTime End="23:00:00" Fri="true" Mon="true" Start="05:30:00" Thur="true" Tue="true" Weds="true"/>
									<OperationTime End="23:00:00" Sat="true" Start="06:00:00" Sun="true"/>
								</OperationTimes>
							</OperationSchedule>
						</OperationSchedules>
						<MultimediaDescriptions>
							<MultimediaDescription>
								<TextItems>
									<TextItem Title="Sort Order">
										<Description>1</Description>
									</TextItem>
								</TextItems>
							</MultimediaDescription>
						</MultimediaDescriptions>
						<RecreationDetails>
							<RecreationDetail Code="30" CodeDetail="Yes"/>
							<!--Code 30 = Heated pool from OTA Code Table REC-->
							<RecreationDetail Code="32">
								<!--Code 32 = Pool depth from OTA Code Table REC-->
								<Description Name="Swimming Pool Depth">
									<Text>5&quot; 6&quot;</Text>
								</Description>
							</RecreationDetail>
							<RecreationDetail Code="40">
								<!--Code 40 = Restricted age for children without adult supervision from OTA Code Table REC-->
								<Description Name="Restricted age for Children w/o Adult Supervision">
									<Text>16</Text>
								</Description>
							</RecreationDetail>
							<RecreationDetail Code="43">
								<!--Code 43 = Towels available from OTA Code Table REC-->
								<Description Name="Towels Provided for Guest at Indoor Swimming Pool">
									<Text>Yes</Text>
								</Description>
							</RecreationDetail>
							<RecreationDetail>
								<Description Name="Swimming Pool Location Indoor">
									<Text>5th Floor</Text>
								</Description>
							</RecreationDetail>
						</RecreationDetails>
					</Recreation>
				</Recreations>
			</AreaInfo>';
      
      return $data;
  }
  private function genContractInfo(){
    //$xml = new DOMDocument();
    //$xml->formatOutput = true;
    //$ContractInfo = $xml->createElement("ContractInfo");
    //$xml->appendChild($ContractInfo);
    //return $xml->saveXML();
    
    
    $data = '<?xml version="1.0" encoding="UTF-8"?><ContactInfos>
				<ContactInfo ContactProfileType="Property Info">
					<Addresses>
						<Address UseType="7">
							<!--Use Type 7 = Physical from OTA Code Table AUT-->
							<AddressLine>110 Huntington Avenue</AddressLine>
							<CityName>Boston</CityName>
							<PostalCode>02116</PostalCode>
							<StateProv StateCode="MA"/>
							<CountryName>USA</CountryName>
						</Address>
					</Addresses>
					<Phones>
						<Phone PhoneLocationType="1" PhoneNumber="1-800-228-9290" PhoneTechType="1" PhoneUseType="6"/>
						<!--Phone Location Type 1 = Brand reservations office from OTA Code Table PLT, Phone Tech Type 1 = Voice from OTA Code Table PTT, Phone Use Type = 1 Emergency contact from OTA Code Table PUT-->
						<Phone AreaCityCode="617" CountryAccessCode="1" PhoneLocationType="4" PhoneNumber="236-5800" PhoneTechType="1" PhoneUseType="5"/>
						<!--Phone Location Type 4 = Property direct from OTA Code Table PLT, Phone Tech Type 1 = Voice from OTA Code Table PTT, Phone Use Type 5 = Contact from OTA Code Table PUT-->
					</Phones>
				</ContactInfo>
				<ContactInfo ContactProfileType="Property Personnel">
					<Names>
						<Name>
							<GivenName>Richard</GivenName>
							<Surname>Grand</Surname>
							<JobTitle Type="Job Title">
						
							</JobTitle>
						</Name>
					</Names>
				</ContactInfo>
			</ContactInfos>';
      
      return $data;
  }

}
?>