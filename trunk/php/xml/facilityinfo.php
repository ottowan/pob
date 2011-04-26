<?php

        $MeetingRooms = $xml->createElement("MeetingRooms");
        $MeetingRooms->setAttribute("LargestRoomSpace", "23431");
        $MeetingRooms->setAttribute("LargestSeatingCapacity", "3640");
        $MeetingRooms->setAttribute("MeetingRoomCount", "1");
        $MeetingRooms->setAttribute("TotalRoomSpace", "65000");
        $MeetingRooms->setAttribute("UnitOfMeasure", "Square Feet");
        $FacilityInfo->appendChild($MeetingRooms);

?>





<?php

/*
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

?>