<?php
  class PNHotel extends PNObject {
    function PNHotel($init=null, $where='') {
      $this->PNObject();
    
      $this->_objType       = 'pobhotel_hotel';
      $this->_objField      = 'id';
      $this->_objPath       = 'form';
    
      $this->_init($init, $where);
    }

    function selectExtendResult(){
      $id = FormUtil::getPassedValue ('id', false);
      $result = array();
      if ($id){

        $fieldArray = array('amenity_id');

        $result['hotelAmenity'] = DBUtil::selectObjectArray( 'pobhotel_hotel_amenity',
                                                                  "WHERE hotel_amenity_hotel_id = '$id'",
                                                                  '',
                                                                  -1,
                                                                  -1,
                                                                  '',
                                                                  null,
                                                                  null,
                                                                  $fieldArray
        );

        $fieldLocationArray = array('location_category_id');

        $result['locationCategory'] = DBUtil::selectObjectArray( 'pobhotel_hotel_location',
                                                                  "WHERE hotel_location_hotel_id = '$id'",
                                                                  '',
                                                                  -1,
                                                                  -1,
                                                                  '',
                                                                  null,
                                                                  null,
                                                                  $fieldLocationArray
        );

        /*$fieldLocationArray = array('hotel_image_id');

        $result['locationCategory'] = DBUtil::selectObjectArray( 'pobhotel_hotel_image',
                                                                  "WHERE hotel_hotel_image_id = '$id'",
                                                                  '',
                                                                  -1,
                                                                  -1,
                                                                  '',
                                                                  null,
                                                                  null,
                                                                  $fieldLocationArray
        );*/

        }

      return $result;
    }


    function insertPostProcess(){
      $itemAmenity = FormUtil::getPassedValue ('itemAmenity', false);
      $id = $this->_objData['id'];

      if($itemAmenity && $id){
        foreach($itemAmenity as $key=>$val ){
          //$valArray = explode("@", $key);
          $obj[] = array(
                          'amenity_id' => $val,
                          'hotel_id' => $id
                   );
        }
        //Do the insert
        DBUtil::insertObjectArray($obj, 'pobhotel_hotel_amenity');
        unset($obj);
      }

      $itemLocationCategory = FormUtil::getPassedValue ('itemLocationCategory', false);
      if($itemLocationCategory && $id){
        foreach($itemLocationCategory as $key=>$val ){
          //$valArray = explode("@", $key);
          $obj[] = array(
                          'location_category_id' => $val,
                          'hotel_id' => $id
                   );
        }
        //Do the insert
        DBUtil::insertObjectArray($obj, 'pobhotel_hotel_location');
        unset($obj);
      }


          $this->uploadFiles($id);

        //$this->checkHasImage($id);


    }

    function updatePostProcess(){

      $id = $this->_objData['id'];
      $itemAmenity = FormUtil::getPassedValue ('itemAmenity', false);
      //Delete old data
      DBUtil::deleteObjectByID( 'pobhotel_hotel_amenity', $id, 'hotel_id');

      $itemAmenity = FormUtil::getPassedValue ('itemAmenity', false);
      $id = $this->_objData['id'];
      if($itemAmenity && $id){
        foreach($itemAmenity as $key=>$val ){
          //$valArray = explode("@", $key);
          $obj[] = array(
                          'amenity_id' => $val,
                          'hotel_id' => $id
                   );
        }
        //Do the insert
        DBUtil::insertObjectArray($obj, 'pobhotel_hotel_amenity');
      }

      $itemLocationCategory = FormUtil::getPassedValue ('itemLocationCategory', false);
      DBUtil::deleteObjectByID( 'pobhotel_hotel_location', $id, 'hotel_id');

      $itemLocationCategory = FormUtil::getPassedValue ('itemLocationCategory', false);
      if($itemLocationCategory && $id){
        foreach($itemLocationCategory as $key=>$val ){
          //$valArray = explode("@", $key);
          $obj[] = array(
                          'location_category_id' => $val,
                          'hotel_id' => $id
                   );
        }
        //Do the insert
        DBUtil::insertObjectArray($obj, 'pobhotel_hotel_location');
        unset($obj);
      }

    }
  function hotelDescriptiveContentNotifRQ{
    <OTA_HotelDescriptiveContentNotifRQ xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_HotelDescriptiveContentNotifRQ.xsd" Version="2.001">
	<HotelDescriptiveContents>
		<HotelDescriptiveContent BrandCode="MHRS" BrandName="Marriott Hotels &amp; Resorts" CurrencyCode="USD" HotelCode="BOSCO" HotelName="Boston Marriott Copley Place" LanguageCode="EN">
			<HotelInfo HotelStatus="Open" LastUpdated="2005-01-14T09:57:59" Start="1984-05-19" WhenBuilt="1984">
				<CategoryCodes>
					<LocationCategory Code="3" CodeDetail="Location Type: City"/>
					<!--Code 3 = City from OTA Code Table LOC-->
				</CategoryCodes>
				<Descriptions>
					<Renovation ImmediatePlans="false" PercentOfRenovationCompleted="100"/>
					<Renovation>
						<MultimediaDescriptions>
							<MultimediaDescription>
								<TextItems>
									<TextItem Title="Renovation Area Completion Date 1">
										<Description>2000-02-13</Description>
									</TextItem>
								</TextItems>
							</MultimediaDescription>
							<MultimediaDescription>
								<TextItems>
									<TextItem Title="Renovation Area Description 2">
										<Description>Guest Rooms</Description>
									</TextItem>
								</TextItems>
							</MultimediaDescription>
							<MultimediaDescription>
								<TextItems>
									<TextItem Title="Renovation Area Completion Date 2">
										<Description>2002-10-01</Description>
									</TextItem>
								</TextItems>
							</MultimediaDescription>
						</MultimediaDescriptions>
					</Renovation>
					<MultimediaDescriptions>
						<MultimediaDescription>
							<TextItems>
								<TextItem Title="Description">
									<Description>The Boston Marriott Copley Place Hotel is the perfect destination for business or pleasure.  We&apos;re located in Boston&apos;s Back Bay, off the Mass. Trnpk. at Exit 22, 4 mi from Logan Airport, &amp; in close proximity to subway.</Description>
								</TextItem>
								<TextItem Title="PropertyLongDescription">
									<Description>Boston Marriott Copley Place hotel is ideally located in Boston&apos;s Back Bay. This Copley Square hotel is easily accessible for business convenience. Centrally located in Copley Place for leisure activities. And technology-driven for dynamic meetings. For your trip to Boston, Massachusetts, the Copley Marriott is the perfect choice.</Description>
								</TextItem>
								<TextItem Title="Top Selling Feature 1">
									<Description>Boston&apos;s historic Back Bay, just off the Massachusetts Turnpike;  four miles from Logan Airport</Description>
								</TextItem>
								<TextItem Title="Top Selling Feature 2">
									<Description>,147 rooms and suites, the largest hotel ballroom in the area, and 65,000 square feet of event room</Description>
								</TextItem>
								<TextItem Title="Property Service Level">
									<Description>Service</Description>
								</TextItem>
								<TextItem Title="Guest Room Highlights">
									<Description ListItem="1">internet access</Description>
									<Description ListItem="2">&quot; color TV with cable movies, in-room pay movies, Web TV and Gameboy</Description>
									<Description ListItem="3">and refrigerators</Description>
								</TextItem>
								<TextItem Title="Business/Group Highlights">
									<Description ListItem="1">&amp; Service Team consistently awarded the highest Guest Satisfaction Scores</Description>
									<Description ListItem="2">the Gold Key Award and the Corporate &amp; lncentive Travel Award of Excellence</Description>
									<Description ListItem="3">Exhibit Hall 22,500 square feet</Description>
								</TextItem>
							</TextItems>
						</MultimediaDescription>
					</MultimediaDescriptions>
				</Descriptions>
				<Position Latitude="42.347996" Longitude="-71.07869"/>
				<Services>
					<Service Code="103">
						<!--Code 103 = Multilingual staff from OTA Code Table HAC-->
						<MultimediaDescriptions>
							<MultimediaDescription>
								<TextItems>
									<TextItem Title="Language Spoken By Staff">
										<Description>Spanish, French, Vietnamese, English, Russian</Description>
									</TextItem>
								</TextItems>
							</MultimediaDescription>
						</MultimediaDescriptions>
					</Service>
					<Service Code="164">
						<!--Code 164 = Food and beverage outlets from OTA Code Table HAC-->
						<MultimediaDescriptions>
							<MultimediaDescription>
								<TextItems>
									<TextItem Title="Number of Food and Beverage Outlets">
										<Description>2</Description>
									</TextItem>
								</TextItems>
							</MultimediaDescription>
						</MultimediaDescriptions>
					</Service>
					<Service Code="165">
						<!--Code 165 = Lounges/bars from OTA Code Table HAC-->
						<MultimediaDescriptions>
							<MultimediaDescription>
								<TextItems>
									<TextItem Title="Number of Lounges/Bars">
										<Description>1</Description>
									</TextItem>
								</TextItems>
							</MultimediaDescription>
						</MultimediaDescriptions>
					</Service>
					<Service Code="63" CodeDetail="Fee">
						<!--Code 63 = Off-site parking from OTA Code Table HAC-->
						<RelativePosition Distance=".1" Name="Copley Place Garage"/>
					</Service>
					<Service Code="64" CodeDetail="Fee">
						<!--Code 64 = On-site parking from OTA Code Table HAC-->
						<OperationSchedules>
							<OperationSchedule>
								<Charge Amount="29" ChargeUnit="1"/>
								<!--Charge Unit 1 = Daily from OTA Code Table CHG-->
							</OperationSchedule>
						</OperationSchedules>
					</Service>
					<Service Code="97" CodeDetail="Fee">
						<!--Code 97 = Valet parking from OTA Code Table HAC-->
						<OperationSchedules>
							<OperationSchedule>
								<Charge Amount="37" ChargeUnit="1"/>
								<!--Charge Unit 1 = Daily from OTA Code Table CHG-->
							</OperationSchedule>
						</OperationSchedules>
					</Service>
					<Service Code="80">
						<!--Code 80 = Security from OTA Code Table HAC-->
						<Features>
							<Feature SecurityCode="2">
								<!--Security Code 2 = Address of nearest police station from OTA Code Table SEC-->
								<MultimediaDescriptions>
									<MultimediaDescription>
										<TextItems>
											<TextItem Title="Address of Nearest Police Station">
												<Description>Area D, District 4, 7 Warren Ave.</Description>
											</TextItem>
										</TextItems>
									</MultimediaDescription>
								</MultimediaDescriptions>
							</Feature>
							<Feature CodeDetail="Yes" SecurityCode="7"/>
							<!--Security Code 7 = Building meets all current local, state and country building codes from OTA Code Table SEC-->
							<Feature SecurityCode="8"/>
							<!--Security Code 8 = Complies with Hotel/Motel Safety Act from OTA Code Table SEC-->
							<Feature SecurityCode="75"/>
							<!--Security Code 75 = Basic medical equipment on site from OTA Code Table SEC-->
							<Feature SecurityCode="9"/>
							<!--Security Code 9 = Complies with Local/State/Federal fire laws from OTA Code Table SEC-->
							<Feature SecurityCode="11">
								<!--Security Code 11 = Distance to nearest police station from OTA Code Table SEC-->
								<MultimediaDescriptions>
									<MultimediaDescription>
										<TextItems>
											<TextItem Title="Dist to Nearest Police Station">
												<Description>2</Description>
											</TextItem>
										</TextItems>
									</MultimediaDescription>
								</MultimediaDescriptions>
							</Feature>
							<Feature SecurityCode="12"/>
							<!--Security Code 12 = Doctor on call from OTA Code Table SEC-->
							<Feature CodeDetail="Yes" SecurityCode="15"/>
							<!--Security Code 15 = Emergency back-up generators from OTA Code Table SEC-->
							<Feature SecurityCode="35">
								<!--Security Code 35 = Phone number of nearest police station from OTA Code Table SEC-->
								<MultimediaDescriptions>
									<MultimediaDescription>
										<TextItems>
											<TextItem Title="Phone # of Nearest Police Station">
												<Description>343-4250</Description>
											</TextItem>
										</TextItems>
									</MultimediaDescription>
								</MultimediaDescriptions>
							</Feature>
						</Features>
					</Service>
					<Service Code="47">
						<!--Code 47 = Accessible facilities from OTA Code Table HAC-->
						<Features>
							<Feature AccessibleCode="3">
								<!--Accessible Code 3 = Bathroom vanity in guest rooms for disabled person height from OTA Code Table PHY-->
								<MultimediaDescriptions>
									<MultimediaDescription>
										<TextItems>
											<TextItem Title="Bathroom Vanity in Guest rooms for wheel chaired person height">
												<Description>32</Description>
											</TextItem>
										</TextItems>
									</MultimediaDescription>
								</MultimediaDescriptions>
							</Feature>
							<Feature AccessibleCode="18" CodeDetail="39">
								<!--Accessible Code 18 = Lowered deadbolt in guest room for disabled persons height (in inches) from OTA Code Table PHY-->
							</Feature>
							<Feature AccessibleCode="25">
								<!--Accessible Code 25 = Other services for persons with disabilities from OTA Code Table PHY-->
								<MultimediaDescriptions>
									<MultimediaDescription>
										<TextItems>
											<TextItem Title="Other Services Available for Disabled">
												<Description>The hotel does provide shower chairs for handicapped guests.   The beds in the hotel are not elevated any higher than a normal mattress height.</Description>
											</TextItem>
										</TextItems>
									</MultimediaDescription>
								</MultimediaDescriptions>
							</Feature>
							<Feature AccessibleCode="27" CodeDetail="41">
								<!--Accessible Code 27 = Peephole in guest room for disabled person height (in inches) from OTA Code Table PHY-->
							</Feature>
							<Feature AccessibleCode="31" CodeDetail="36">
								<!--Accessible Code 31 = Thermostat in guest room for disabled persons height (in inches) from OTA Code Table PHY-->
							</Feature>
							<Feature AccessibleCode="37">
								<!--Accessible Code 37 = Which floors have handicapped rooms from OTA Code Table PHY-->
								<MultimediaDescriptions>
									<MultimediaDescription>
										<TextItems>
											<TextItem Title="Floors with Handicapped Rooms">
												<Description>Yes</Description>
											</TextItem>
										</TextItems>
									</MultimediaDescription>
								</MultimediaDescriptions>
							</Feature>
							<Feature AccessibleCode="41" CodeDetail="39">
								<!--Accessible Code 41 = Light switches in guest rooms for disabled persons height (inches) from OTA Code Table PHY-->
							</Feature>
						</Features>
					</Service>
				</Services>
			</HotelInfo>
			<Policies>
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
			</Policies>
			<AreaInfo>
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
			</AreaInfo>
			<AffiliationInfo LastUpdated="2004-12-18T15:50:43">
				<Awards>
					<Award Provider="AAA Rating (Diamonds)" Rating="3"/>
					<Award Provider="Mobil Rating (Stars)" Rating="3"/>
				</Awards>
			</AffiliationInfo>
			<ContactInfos>
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
			</ContactInfos>
		</HotelDescriptiveContent>
	</HotelDescriptiveContents>
</OTA_HotelDescriptiveContentNotifRQ>
<FacilityInfo LastUpdated="2004-12-04T16:00:12">
	<MeetingRooms LargestRoomSpace="23431" LargestSeatingCapacity="3640" MeetingRoomCount="1" TotalRoomSpace="65000" UnitOfMeasure="Square Feet">
		<MeetingRoom Irregular="false" RoomName="Grand Ballroom">
			<Dimension Area="23431" Height="18.1" Length="108" Width="221"/>
			<AvailableCapacities>
				<MeetingRoomCapacity MeetingRoomFormatCode="1">
					<!--Meeting Room Format Code 1 = Banquet from OTA Code Table MRF-->
					<Occupancy MaxOccupancy="1990"/>
				</MeetingRoomCapacity>
				<MeetingRoomCapacity MeetingRoomFormatCode="2">
					<!--Meeting Room Format Code 2 = Classroom from OTA Code Table MRF-->
					<Occupancy MaxOccupancy="1866"/>
				</MeetingRoomCapacity>
				<MeetingRoomCapacity MeetingRoomFormatCode="5">
					<!--Meeting Room Format Code 5 = Theatre from OTA Code Table MRF-->
					<Occupancy MaxOccupancy="3138"/>
				</MeetingRoomCapacity>
				<MeetingRoomCapacity MeetingRoomFormatCode="7">
					<!--Meeting Room Format Code 7 = Reception from OTA Code Table MRF-->
					<Occupancy MaxOccupancy="3640"/>
				</MeetingRoomCapacity>
			</AvailableCapacities>
		</MeetingRoom>
		<Codes>
			<Code Code="92">
				<!--Code 92 = Other equipment and facilities from OTA Code Table MRC-->
				<MultimediaDescriptions>
					<MultimediaDescription>
						<TextItems>
							<TextItem Title="Other Equipment &amp; Facilities Available in Meeting Room">
								<Description>Red Coat Program, Event Services Hotline</Description>
							</TextItem>
						</TextItems>
					</MultimediaDescription>
				</MultimediaDescriptions>
			</Code>
		</Codes>
	</MeetingRooms>
	<GuestRooms>
		<GuestRoom>
			<Amenities>
				<Amenity RoomAmenityCode="11">
					<!--Room Amenity Code 11 = Bathroom amenities from OTA Code Table RMA-->
					<MultimediaDescriptions>
						<MultimediaDescription>
							<TextItems>
								<TextItem Title="Bathroom Amenities">
									<Description>Soap, shampoo, rinse, shower cap, lotions</Description>
								</TextItem>
							</TextItems>
						</MultimediaDescription>
					</MultimediaDescriptions>
				</Amenity>
			</Amenities>
		</GuestRoom>
		<GuestRoom CodeContext="MARSHA Room Type" MaxOccupancy="5" NonsmokingQuantity="923" Quantity="1033" RoomTypeName="General Rooms">
			<MultimediaDescriptions>
				<MultimediaDescription>
					<TextItems>
						<TextItem Title="Sort Order">
							<Description>1</Description>
						</TextItem>
						<TextItem Title="Features of Room Type">
							<Description>GENR Standard 1 King Bed/or 2 Dbl beds, Coffee Maker, Hair Dryer, Desk w/ chair</Description>
						</TextItem>
					</TextItems>
				</MultimediaDescription>
			</MultimediaDescriptions>
		</GuestRoom>
		<GuestRoom CodeContext="MARSHA Room Type" MaxOccupancy="5" NonsmokingQuantity="58" Quantity="67" RoomTypeName="Concierge">
			<MultimediaDescriptions>
				<MultimediaDescription>
					<TextItems>
						<TextItem Title="Sort Order">
							<Description>2</Description>
						</TextItem>
						<TextItem Title="Features of Room Type">
							<Description>CONC Concierge Upgraded Amenities &amp; Private Lounge, 1 King bed or 2 Double beds</Description>
						</TextItem>
					</TextItems>
				</MultimediaDescription>
			</MultimediaDescriptions>
		</GuestRoom>
	</GuestRooms>
	<Restaurants>
		<Restaurant OfferBreakfast="true" OfferDinner="true" OfferLunch="true" RestaurantName="Gourmeli&quot;s (Casual)">
			<MultimediaDescriptions Attire="Casual">
				<MultimediaDescription>
					<TextItems>
						<TextItem Title="Sort Order">
							<Description>1</Description>
						</TextItem>
						<TextItem Title="Restaurant Short Description">
							<Description>Upscale family restaurant with daily theme buffets and both a la carte and children&quot;s menus</Description>
						</TextItem>
					</TextItems>
				</MultimediaDescription>
			</MultimediaDescriptions>
			<RelativePosition Distance="0">
				<Transportations>
					<Transportation>
						<MultimediaDescriptions>
							<MultimediaDescription>
								<TextItems>
									<TextItem Title="Restaurant Location">
										<Description>Onsite</Description>
									</TextItem>
								</TextItems>
							</MultimediaDescription>
						</MultimediaDescriptions>
					</Transportation>
				</Transportations>
			</RelativePosition>
			<OperationSchedules>
				<OperationSchedule>
					<OperationTimes>
						<OperationTime End="14:00:00" Fri="true" Mon="true" Sat="true" Start="06:30:00" Sun="true" Thur="true" Tue="true" Weds="true"/>
						<OperationTime End="22:00:00" Fri="true" Mon="true" Sat="true" Start="17:00:00" Sun="true" Thur="true" Tue="true" Weds="true"/>
					</OperationTimes>
				</OperationSchedule>
			</OperationSchedules>
			<CuisineCodes>
				<CuisineCode Code="1" CodeDetail="American"/>
				<!--Code 1 = American from OTA Code Table CUI-->
			</CuisineCodes>
		</Restaurant>
		<Restaurant OfferBreakfast="false" OfferDinner="true" OfferLunch="false" RestaurantName="The Terrace Bar">
			<MultimediaDescriptions Attire="Casual">
				<MultimediaDescription>
					<TextItems>
						<TextItem Title="Sort Order">
							<Description>2</Description>
						</TextItem>
						<TextItem Title="Restaurant Short Description">
							<Description>A place to relax and enjoy drinks and light fare. Live entertainment on Saturday Nights.</Description>
						</TextItem>
					</TextItems>
				</MultimediaDescription>
			</MultimediaDescriptions>
			<RelativePosition>
				<Transportations>
					<Transportation>
						<MultimediaDescriptions>
							<MultimediaDescription>
								<TextItems>
									<TextItem Title="Restaurant Location">
										<Description>Onsite</Description>
									</TextItem>
								</TextItems>
							</MultimediaDescription>
						</MultimediaDescriptions>
					</Transportation>
				</Transportations>
			</RelativePosition>
			<OperationSchedules>
				<OperationSchedule>
					<OperationTimes>
						<OperationTime End="12:00:00" Start="14:00:00" Sun="true"/>
						<OperationTime End="12:00:00" Mon="true" Start="15:00:00" Thur="true" Tue="true" Weds="true"/>
						<OperationTime End="01:00:00" Fri="true" Sat="true" Start="14:00:00"/>
					</OperationTimes>
				</OperationSchedule>
			</OperationSchedules>
			<CuisineCodes>
				<CuisineCode Code="52" CodeDetail="Other"/>
				<!--Code 52 = Other from OTA Code Table CUI-->
			</CuisineCodes>
		</Restaurant>
	</Restaurants>
</FacilityInfo>

  }

  function uploadFiles($id){
    if ($id && in_array(0,$_FILES['images']['error'])){
      $images = $_FILES['images'];

      //Set directory to store image path
      if($id % 10000 == 0){
        $newNumberOfDirectory = ($id/10000);
        if($newNumberOfDirectory == 0){
          $rootImagePath = "pnTemp/pobhotel_upload/image/large";
          $rootThumbPath = "pnTemp/pobhotel_upload/image/thumb";
        }else{
          $rootImagePath = "pnTemp/pobhotel_upload/image/large".$newNumberOfDirectory;
          $rootThumbPath = "pnTemp/pobhotel_upload/image/thumb".$newNumberOfDirectory;
        }

      }else{
        $rootImagePath = "pnTemp/pobhotel_upload/image/large".floor($id/10000);
        $rootThumbPath = "pnTemp/pobhotel_upload/image/thumb".floor($id/10000);
      }

      //Make root topic image directory 
      if (!is_dir($rootImagePath)) {
        mkdir($rootImagePath, 0755);
      }
      //Make root topic thumb directory
      if (!is_dir($rootThumbPath)) {
        mkdir($rootThumbPath, 0755);
      }

      $imagespathbyid = $rootImagePath."/".$id."/";
      $thumbspathbyid = $rootThumbPath."/".$id."/";


      //Make topic image directory by id
      if (!is_dir($imagespathbyid)) {
        mkdir($imagespathbyid, 0755);
      }
      //Make topic thumb directory by id
      if (!is_dir($thumbspathbyid)) {
        mkdir($thumbspathbyid, 0755);
      }

      //Set directory to store image path by id
      $filepath = $imagespathbyid."hotel/";
      $thumbspath = $thumbspathbyid."hotel/";
      //Make topic image directory by id
      if (!is_dir($imagespath)) {
        mkdir($imagespath, 0755);
      }
      //Make topic thumb directory by id
      if (!is_dir($thumbspath)) {
        mkdir($thumbspath, 0755);
      }

      foreach ($images["error"] as $key => $error){
        if ($error == 0){
            //Gennerate next id
            $image_id = DBUtil::selectFieldMax( 'pobhotel_hotel_image', 'id', 'MAX', '');
            if($image_id == null){
              $next_id = 1;
            }else{
              $next_id = $image_id+1;
            }

            $tmp_name = $images["tmp_name"][$key];
            $fliename = $images["name"][$key];
            $filetype = $images["type"][$key];
            $filesize = $images["size"][$key];

////////////////////////// get the extension of the file in a lower case format/////////////////////////////////////////////
            $filename = stripslashes($fliename);
            $i = strrpos($filename,".");
            if (!$i) { return ""; }
            $l = strlen($filename) - $i;
            $ext = substr($filename,$i+1,$l);
            $extension = strtolower($ext);

////////////////////////////generate new file&path/////////////////////////////////////////////////////////////////////
            $rootname       = $next_id.time().".".$extension;
            $filename_temp  = "images_".$id.$rootname;
            $thumbname_temp = "thumbs_".$id.$rootname;
            $imgpath        = $imagespath.$filename_temp;
            $tmbpath        = $thumbspath.$filename_temp;

            if($key==0){
              $firstimage_temp = $filename_temp;
            }

//////////////////////////start upload/////////////////////////////////////////////

        $source = $tmp_name;
        $target = $imgpath;
        move_uploaded_file($source, $target);

        //images
        $imagepath = $fliename;
        $save = $imgpath;; //This is the new file you saving
        $file = $imgpath; //This is the original file
        list($width, $height) = getimagesize($file) ; 

//////////////////resize image orginal////////////////////
        $modwidth = 600; 
        $diff = $width / $modwidth;
        if (($width > 600) || ($height > 200)) 
        {
          $modheight = $height / $diff; 
          $tn = imagecreatetruecolor($modwidth, $modheight); 
          if($filetype == "image/pjpeg" || $filetype == "image/jpeg"){
            $image = imagecreatefromjpeg($file) ; 
          }elseif($filetype == "image/x-png" || $filetype == "image/png"){
            $image = imagecreatefrompng($file) ;
          }elseif($filetype == "image/gif"){
            $image = imagecreatefromgif($file) ;
          }
            imagecopyresampled($tn, $image, 0, 0, 0, 0, $modwidth, $modheight, $width, $height) ; 
        }

        if($filetype == "image/pjpeg" || $filetype == "image/jpeg"){
          imagejpeg($tn, $save, 100) ; 
        }elseif($filetype == "image/x-png" || $filetype == "image/png"){
          imagepng($tn, $save) ; 
        }elseif($filetype == "image/gif"){
          copy($file, $save) ; 
        }

//////////////////resize image thumb////////////////////

        $save = $tmbpath; //This is the new file you saving
        $file = $imgpath; //This is the original file

        list($width, $height) = getimagesize($file) ; 

        $modwidth = 100; 
        $diff = $width / $modwidth;
        $modheight = $height / $diff; 
        $tn = imagecreatetruecolor($modwidth, $modheight); 
        if($filetype == "image/pjpeg" || $filetype == "image/jpeg"){
          $image = imagecreatefromjpeg($file) ; 
        }elseif($filetype == "image/x-png" || $filetype == "image/png"){
          $image = imagecreatefrompng($file) ;
        }elseif($filetype == "image/gif"){
          $image = imagecreatefromgif($file) ;
        }
        imagecopyresampled($tn, $image, 0, 0, 0, 0, $modwidth, $modheight, $width, $height) ; 

        if($filetype == "image/pjpeg" || $filetype == "image/jpeg"){
          imagejpeg($tn, $save, 100) ; 
        }elseif($filetype == "image/x-png" || $filetype == "image/png"){
          imagepng($tn, $save) ; 
        }elseif($filetype == "image/gif"){
          copy($file, $save) ; 
        }


            $objects = array(
                            'id'         => $next_id,
                            'hotel_id' => $id,
                            'filename'   => $filename_temp,
                            'filesize'   => $filesize,
                            'filetype'   => $filetype,
                            'filepath'   => $filepath,
                            'thumbname'  => $thumbname_temp,
                            'thumbsize'  => $thumbsize,
                            'thumbtype'  => $filetype,
                            'thumbpath'   => $thumbspath
                           );

          DBUtil::insertObject($objects,'pobhotel_hotel_image');
        }
      }
    }
  }

  }
?>