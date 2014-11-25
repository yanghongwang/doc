<?php
namespace ctrip;

class Hotel
{
	private static $aid = "20230";
	private static $sid = "451200";
	private static $key = "471B29E6-0CEF-4561-88CB-57C03C7C948C";
	
	// 通过接口（酒店查询 OTA_HotelSearch）获取数据
	public static function GetHotelList($cityId)
	{
		$cityId = intval($cityId);
		if($cityId <= 0)	return;
		
$logger = \Logger :: GetLogger("ctrip");
$logger->info("开始查询城市($cityId)中的酒店列表", __FUNCTION__);
		$now = time();
		$ws = "http://openapi.ctrip.com/Hotel/OTA_HotelSearch.asmx?wsdl";
		
		$xml = '<?xml version="1.0" encoding="utf-8"?><Request><Header  AllianceID="' . self :: $aid . '" SID="' . self :: $sid . '" TimeStamp="' . $now . '" Signature="' . self :: GetSign($now, "OTA_HotelSearch") . '" RequestType="OTA_HotelSearch" /><HotelRequest><RequestBody xmlns:ns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><ns:OTA_HotelSearchRQ Version="1.0" PrimaryLangID="zh" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_HotelSearchRQ.xsd" xmlns="http://www.opentravel.org/OTA/2003/05"><ns:Criteria AvailableOnlyIndicator="true"><ns:Criterion><ns:HotelRef HotelCityCode="' . $cityId . '" /></ns:Criterion></ns:Criteria></ns:OTA_HotelSearchRQ></RequestBody></HotelRequest></Request>';
		
		$client = new \SoapClient($ws);
		$result = $client->Request(array('requestXML' => $xml));
		
		$xml = simplexml_load_string($result->RequestResult);
		$xml->asXML(__DIR__ . "/hotellistdata/city" . $cityId . ".xml");
$logger->info("完成查询城市($cityId)中的酒店列表", __FUNCTION__);
	}
	
	// 分析接口（酒店查询 OTA_HotelSearch）返回的数据
	public static function AnalyzeHotelList($cityId)
	{
$logger = \Logger :: GetLogger("ctrip");
		$xml = simplexml_load_file(__DIR__ . "/hotellistdata/city" . $cityId . ".xml");

		if(!isset($xml->HotelResponse->OTA_HotelSearchRS->Properties))	return;
		$hotelList = $xml->HotelResponse->OTA_HotelSearchRS->Properties;
		
		$sqlObject = new \Mysql();
		
		$hotelIndex = 0;
		foreach($hotelList->children() as $hotel)
		{
			$hotelIndex++;
$logger->info("开始分析第" . $hotelIndex . "个酒店", __FUNCTION__);
			$hotelId = intval($hotel["HotelCode"]);
			if($cityId != intval($hotel["HotelCityCode"]))
			{
$logger->error("查询到的酒店的HotelCityCode(" . $hotel["HotelCityCode"] . ")和($cityId)不一致", __FUNCTION__);
			}
			$hotelName = strval($hotel["HotelName"]);
			$districtId = intval($hotel["AreaID"]);
			$hotelCode = intval($hotel["HotelId"]);
			if($hotelId != $hotelCode)
			{
$logger->error("查询到的酒店的HotelCode($hotelId)和HotelId($hotelCode)不一致", __FUNCTION__);
			}
			$brandId = intval($hotel["BrandCode"]);
			
			$sql = "INSERT INTO ctrip_hotel (id, city_id, name, district_id, brand_id) VALUES ($hotelId, $cityId, '$hotelName', $districtId, $brandId);";
			$sqlObject->Query($sql);
			
			if(isset($hotel->VendorMessages))
			{
				foreach($hotel->VendorMessages->children() as $message)
				{
					$infoType = intval($message["InfoType"]);
					if($infoType == 23)
					{
						$sql = "INSERT INTO ctrip_hotel_image (hotel_id, url) VALUES ($hotelId, '" . strval($message->SubSection->Paragraph->Text) . "');";
						$sqlObject->Query($sql);
					}
					else if($infoType == 1)
					{
						$sql = "INSERT INTO ctrip_hotel_description (hotel_id, description) VALUES ($hotelId, '" . strval($message->SubSection->Paragraph->Text) . "');";
						$sqlObject->Query($sql);
					}
					else
					{
$logger->error("查询到的酒店的信息(" . $infoType . ")：" . strval($message->SubSection->Paragraph->Text), __FUNCTION__);
					}
				}
			}
			
			$address = strval($hotel->Address->AddressLine);
			$sql = "UPDATE ctrip_hotel SET address = '$address' WHERE id = $hotelId;";
			$sqlObject->Query($sql);
			
			$awardList = $hotel->Award; 
			for($i = 0; $i < count($awardList); $i++)
			{
				switch(strval($awardList[$i]["Provider"]))
				{
					case "HotelStarRate":
						$fieldName = "star_rate";
						break;
					case "CtripStarRate":
						$fieldName = "ctrip_star_rate";
						break;
					case "CtripRecommendRate":
						$fieldName = "ctrip_recommend_rate";
						break;
					case "CtripCommRate":
						$fieldName = "ctrip_client_rate";
						break;
					case "CommSurroundingRate":
						$fieldName = "ctrip_client_surrounding_rate";
						break;
					case "CommFacilityRate":
						$fieldName = "ctrip_client_facility_rate";
						break;
					case "CommCleanRate":
						$fieldName = "ctrip_client_clean_rate";
						break;
					case "CommServiceRate":
						$fieldName = "ctrip_client_service_rate";
						break;
					default:
$logger->error("查询到的酒店的星级（" . strval($awardList[$i]["Provider"]) . "）：" . doubleval($awardList[$i]["Rating"]), __FUNCTION__);
				}
				$sql = "UPDATE ctrip_hotel SET $fieldName = " . doubleval($awardList[$i]["Rating"]) . " WHERE id = $hotelId;";
				$sqlObject->Query($sql);
			}
			
			/*未解析RelativePosition结点，因为下面一个接口有更详细的信息
			if(isset($hotel->RelativePosition))
			{
				$relativePositionList = $hotel->RelativePosition;
				for($i = 0; $i < count($relativePositionList); $i++)
				{
$logger->debug("查询到酒店距离（" . strval($relativePositionList[$i]["Name"]) . "）有" . doubleval($relativePositionList[$i]["Distance"]) . "公里：", __FUNCTION__);
$sql = "INSERT INTO ctrip_hotel_surround (hotel_id, location_type_id, location_name, distance) VALUES ($hotelId, 0, '" . strval($relativePositionList[$i]["Name"]) . "', " . doubleval($relativePositionList[$i]["Distance"]) . ");";
file_put_contents($sqlFilename, $sql . "\r\n", FILE_APPEND | LOCK_EX);
				}
			}
			*/
			
			if(isset($hotel->TPA_Extensions->Zone))
			{
				$zoneId = intval($hotel->TPA_Extensions->Zone->ZoneType["ZoneID"]);
				$zoneName = strval($hotel->TPA_Extensions->Zone->ZoneType["ZoneName"]);
				$sql = "SELECT name FROM ctrip_area_business_zone WHERE id = " . $zoneId;
				$zoneDataInDB = $sqlObject->GetOne($sql);
				if(!is_array($zoneDataInDB))
				{
					$sql = "INSERT INTO ctrip_area_business_zone (id, name, short_name, city_id) VALUES ($zoneId, '" . $zoneName . "', '" . $zoneName . "', $cityId)";
					$sqlObject->Query($sql);
				}
				else
				{
					if($zoneDataInDB["name"] != $zoneName)
					{
$logger->error("查询（" . $zoneId . "）有不一致的zoneName，数据库中的数据是：" . $zoneDataInDB["name"] . "，接口返回的是：" . $zoneName, __FUNCTION__);
					}
				}
				
				$sql = "UPDATE ctrip_hotel SET zone_id = $zoneId WHERE id = $hotelId;";
				$sqlObject->Query($sql);
			}
		}
	}
	
	// 通过接口（酒店静态信息查询 OTA_HotelDescriptiveInfo）获取数据
	public static function GetHotelDetail($hotelList)
	{
		if(!is_array($hotelList) || count($hotelList) == 0)	return;
		
		$now = time();
		$ws = "http://openapi.ctrip.com/Hotel/OTA_HotelDescriptiveInfo.asmx?wsdl";
		
		$xml = '<?xml version="1.0" encoding="utf-8"?><Request><Header  AllianceID="' . self :: $aid . '" SID="' . self :: $sid . '" TimeStamp="' . $now . '" Signature="' . self :: GetSign($now, "OTA_HotelDescriptiveInfo") . '" RequestType="OTA_HotelDescriptiveInfo" /><HotelRequest><RequestBody xmlns:ns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><OTA_HotelDescriptiveInfoRQ Version="1.0" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_HotelDescriptiveInfoRQ.xsd" xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><HotelDescriptiveInfos>';
		
		for($i = 0; $i < count($hotelList); $i++)
		{
			$xml .= '<HotelDescriptiveInfo HotelCode="' . $hotelList[$i] . '" PositionTypeCode="502"><HotelInfo SendData="true"/><FacilityInfo SendGuestRooms="true"/><AreaInfo SendAttractions="true" SendRecreations="true"/><ContactInfo SendData="true"/><MultimediaObjects SendData="true"/></HotelDescriptiveInfo>';
		}
		
		$xml .= '</HotelDescriptiveInfos></OTA_HotelDescriptiveInfoRQ></RequestBody></HotelRequest></Request>';
$logger = \Logger :: GetLogger("ctrip");
$logger->debug("查询酒店详情：请求xml\n" . $xml, __FUNCTION__);
		$client = new \SoapClient($ws);
		$result = $client->Request(array('requestXML' => $xml));
		$xml = simplexml_load_string($result->RequestResult);
		$xml->asXML(__DIR__ . "/hoteldetaildata/hotel" . $hotelList[0] . ".xml");
		return;
	}

	// 分析接口（酒店静态信息查询 OTA_HotelDescriptiveInfo）返回的数据
	public static function AnalyzeHotelDetail($hotelId)
	{
		$xml = simplexml_load_file(__DIR__ . "/hoteldetaildata/hotel" . $hotelId . ".xml");
		$sqlFilename = __DIR__ . "/hoteldetaildata/sql/hotel" . $hotelId . ".sql";

		if(!isset($xml->HotelResponse->OTA_HotelDescriptiveInfoRS->HotelDescriptiveContents))	return;
$logger = \Logger :: GetLogger("ctrip");
$logger->info("开始分析文件hotel" . $hotelId . ".xml中的酒店", __FUNCTION__);
		$hotelList = $xml->HotelResponse->OTA_HotelDescriptiveInfoRS->HotelDescriptiveContents;
		
		$sqlObject = new \Mysql();
		
		$hotelIndex = 0;
		foreach($hotelList->children() as $hotel)
		{
			$hotelIndex++;

			$hotelId = intval($hotel["HotelCode"]);
			$cityId = intval($hotel["HotelCityCode"]);
$logger->info("开始分析酒店（" . $hotelId . "）", __FUNCTION__);
			$hotelInfo = $hotel->HotelInfo;
			
			$sql = "UPDATE ctrip_hotel SET built_date = '" . $hotelInfo["WhenBuilt"] . "', category = " . $hotelInfo->CategoryCodes->SegmentCategory["Code"] . " WHERE id = " . $hotelId . ";";
//$logger->info($sql, __FUNCTION__);
file_put_contents($sqlFilename, $sql . "\r\n", FILE_APPEND | LOCK_EX);
			
			if(isset($hotelInfo->Services))
			{
				$serviceList = $hotelInfo->Services;
				foreach($serviceList->children() as $service)
				{
					$serviceId = intval($service["Code"]);
					$serviceName = strval($service->DescriptiveText);
					
					$sql = "SELECT name FROM ctrip_hotel_baseinfo_service WHERE id = " . $serviceId;
					$serviceDataInDB = $sqlObject->GetOne($sql);
					if(!is_array($serviceDataInDB))
					{
						$sql = "REPLACE INTO ctrip_hotel_baseinfo_service (id, name) VALUES ($serviceId, '" . $serviceName . "');";
//$logger->info($sql, __FUNCTION__);
file_put_contents($sqlFilename, $sql . "\r\n", FILE_APPEND | LOCK_EX);
					}
					else
					{
						if($serviceDataInDB["name"] != $serviceName)
						{
$logger->error("查询（" . $serviceId . "）有不一致的serviceName，数据库中的数据是：" . $serviceDataInDB["name"] . "，接口返回的是：" . $serviceName, __FUNCTION__);
						}
					}
					
					$sql = "INSERT IGNORE INTO ctrip_hotel_service_detail (hotel_id, service_id) VALUES (" . $hotelId . ", " . $serviceId . ");";
// $logger->info($sql, __FUNCTION__);
file_put_contents($sqlFilename, $sql . "\r\n", FILE_APPEND | LOCK_EX);
				}
			}
			
			$roomList = $hotel->FacilityInfo->GuestRooms;
			foreach($roomList->children() as $room)
			{
				$roomName = strval($room["RoomTypeName"]);
				$resident = intval($room->TypeRoom["StandardOccupancy"]);				// 标准入住人数
				$bedSize = strval($room->TypeRoom["Size"]);								// 床的宽度
				$roomId = intval($room->TypeRoom["RoomTypeCode"]);						// 房型ID
				$floor = strval($room->TypeRoom["Floor"]);								// 楼层范围
				// InvBlockCode属性不知道什么意思，所以未处理
				$bedTypeId = intval($room->TypeRoom["BedTypeCode"]);					// 床型ID
				$noSmoking = strval($room->TypeRoom["NonSmoking"]) == "false" ? 0 : 1;
																						// 是否禁止吸烟
				$windowCount = intval($room->TypeRoom["HasWindow"]);					// 房间窗户数
				$quantity = intval($room->TypeRoom["Quantity"]);						// 房间数量
				$roomSize = strval($room->TypeRoom["RoomSize"]);						// 房间空间大小
				$sql = "REPLACE INTO ctrip_hotel_room (id, hotel_id, name, resident, room_size, bed_size, bed_type_id, no_smoking, window_count, floor, quantity) VALUES (" . $roomId . ", " . $hotelId . ", '" . $roomName . "', " . $resident . ", '" . $roomSize . "', '" . $bedSize . "', " . $bedTypeId . ", " . $noSmoking . ", " . $windowCount . ", '" . $floor . "', " . $quantity . ");";
//$logger->info($sql, __FUNCTION__);
file_put_contents($sqlFilename, $sql . "\r\n", FILE_APPEND | LOCK_EX);
				
				if(isset($room->Amenities))
				{
					$amenityList = $room->Amenities;
					foreach($amenityList->children() as $amenity)
					{
						$amenityType = strval($amenity["RoomAmenityCode"]);
						$amenityName = strval($amenity->DescriptiveText);

						$sql = "INSERT IGNORE INTO ctrip_hotel_room_amenity_detail (room_id, type, name) VALUES (" . $roomId . ", '" . $amenityType . "' , '" . $amenityName . "');";
// $logger->info($sql, __FUNCTION__);
file_put_contents($sqlFilename, $sql . "\r\n", FILE_APPEND | LOCK_EX);
					}
				}
			}
			
			/*未解析Policies结点
			$policyList = $hotel->Policies->Policy->PolicyInfoCodes->PolicyInfoCode;
			foreach($policyList->children() as $policy)
			{
$logger->debug("政策（" . $policy["Name"] . ", " . $policy->Text . "）", __FUNCTION__);
			}
$logger->debug("退房政策（" . $hotel->Policies->Policy->PolicyInfo["CheckInTime"] . ", " . $hotel->Policies->Policy->PolicyInfo["CheckOutTime"] . "）", __FUNCTION__);*/
			
			$areaList = $hotel->AreaInfo->RefPoints;
			foreach($areaList->children() as $area)
			{
				$sql = "INSERT IGNORE INTO ctrip_area_city_location (city_id, name, type_id, type_name) VALUES ('$cityId', '" . str_replace("'", " ", $area["Name"]) . "', '" . $area["RefPointCategoryCode"] . "', '" . $area["RefPointName"] . "');";
// $logger->debug($sql, __FUNCTION__);
file_put_contents($sqlFilename, $sql . "\r\n", FILE_APPEND | LOCK_EX);
				
				$sql = "INSERT IGNORE INTO ctrip_hotel_surround(hotel_id, location_type_id, location_name, distance, description) VALUES (" . $hotelId . ", '" . $area["RefPointCategoryCode"] . "', '" . str_replace("'", " ", $area["Name"]) . "', '" . $area["Distance"] . "', '" . str_replace("'", " ", $area->DescriptiveText) . "');";
// $logger->debug($sql, __FUNCTION__);
file_put_contents($sqlFilename, $sql . "\r\n", FILE_APPEND | LOCK_EX);
			}
			
			$mediaList = $hotel->MultimediaDescriptions;
			foreach($mediaList->children() as $mediaInfo)
			{
				if(isset($mediaInfo->ImageItems))
				{
					$imageList = $mediaInfo->ImageItems;
					foreach($imageList->children() as $image)
					{
						$sql = "INSERT IGNORE INTO ctrip_hotel_image(hotel_id, type, caption, url) VALUES (" . $hotelId . ", '" . $image["Category"] . "', '" . $image->Description["Caption"] . "', '" . $image->ImageFormat->URL . "');";
// $logger->debug($sql, __FUNCTION__);
file_put_contents($sqlFilename, $sql . "\r\n", FILE_APPEND | LOCK_EX);
					}
				}
				else if(isset($mediaInfo->TextItems))
				{
					$textList = $mediaInfo->TextItems;
					foreach($textList->children() as $text)
					{
						$sql = "INSERT IGNORE INTO ctrip_hotel_description(hotel_id, type, description) VALUES (" . $hotelId . ", '" . $text["Category"] . "', '" . $text->Description . "');";
// $logger->debug($sql, __FUNCTION__);
file_put_contents($sqlFilename, $sql . "\r\n", FILE_APPEND | LOCK_EX);
					}
				}
				else
				{
$logger->error("酒店（" . $hotelId . "）多媒体信息：" . print_r($mediaInfo, true), __FUNCTION__);
				}
			}
			
			if(isset($hotel->ContactInfos->ContactInfo->Phones))
			{
				$phoneList = $hotel->ContactInfos->ContactInfo->Phones;
				foreach($phoneList->children() as $phone)
				{
					switch(intval($phone["PhoneTechType"]))
					{
						case 1:
							$sql = "UPDATE ctrip_hotel SET phone = '" . $phone["PhoneNumber"] . "' WHERE id = $hotelId;";
// $logger->debug($sql, __FUNCTION__);
file_put_contents($sqlFilename, $sql . "\r\n", FILE_APPEND | LOCK_EX);
							break;
						case 3:
							$sql = "UPDATE ctrip_hotel SET fax = '" . $phone["PhoneNumber"] . "' WHERE id = $hotelId;";
// $logger->debug($sql, __FUNCTION__);
file_put_contents($sqlFilename, $sql . "\r\n", FILE_APPEND | LOCK_EX);
							break;
						default:
$logger->error("酒店（" . $hotelId . "）的联系方式（" . $phone["PhoneTechType"] . ", " . $phone["PhoneNumber"] . "）", __FUNCTION__);
					}
				}
			}
			
			/*未解析ThemeCategory结点
			if(isset($hotel->TPA_Extensions->ThemeCategory))
			{
				$themeList = $hotel->TPA_Extensions->ThemeCategory;
				foreach($themeList->children() as $theme)
				{
$logger->debug("酒店主题（" . $theme["Code"] . "）", __FUNCTION__);
				}
			}*/
		}
	}
	
	public static function GetHotelPlan($hotelList)
	{
		if(!is_array($hotelList) || count($hotelList) == 0)	return;
		
		$now = time();
		$ws = "http://openapi.ctrip.com/Hotel/OTA_HotelRatePlan.asmx?wsdl";
		
		$xml = '<?xml version="1.0" encoding="utf-8"?><Request><Header  AllianceID="' . self :: $aid . '" SID="' . self :: $sid . '" TimeStamp="' . $now . '" Signature="' . self :: GetSign($now, "OTA_HotelRatePlan") . '" RequestType="OTA_HotelRatePlan" /><HotelRequest><RequestBody xmlns:ns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><ns:OTA_HotelRatePlanRQ TimeStamp="2013-06-01T00:00:00.000+08:00" Version="1.0"><ns:RatePlans>';
		
		for($i = 0; $i < count($hotelList); $i++)
		{
			$xml .= '<ns:RatePlan><ns:DateRange Start="' . date('Y-m-d') . '" End="' . date('Y-m-d', time() + (7 * 24 * 60 * 60)) .  '"/><ns:RatePlanCandidates><ns:RatePlanCandidate AvailRatesOnlyInd="true" ><ns:HotelRefs><ns:HotelRef HotelCode="' . $hotelList[$i] . '"/></ns:HotelRefs></ns:RatePlanCandidate></ns:RatePlanCandidates></ns:RatePlan>';
		}
		
		$xml .= '</ns:RatePlans></ns:OTA_HotelRatePlanRQ></RequestBody></HotelRequest></Request>';
$logger = \Logger :: GetLogger("ctrip");
$logger->debug("查询酒店价格：请求xml\n" . $xml, __FUNCTION__);
		$client = new \SoapClient($ws);
		$result = $client->Request(array('requestXML' => $xml));
		$xml = simplexml_load_string($result->RequestResult);
		$xml->asXML(__DIR__ . "/hotelpricedata/hotel" . $hotelList[0] . ".xml");
		return;
	}
	
	private static function GetSign($now, $requestType)
	{
		$key = strtoupper(md5(self :: $key));
		$plaintext = $now . self :: $aid . $key . self :: $sid . $requestType;
		$sign = strtoupper(md5($plaintext));
		return $sign;
	}
}