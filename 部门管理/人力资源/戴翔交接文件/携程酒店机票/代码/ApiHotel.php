<?php
require_once ("../class/Logger.php");
Logger::configure("../class/Logger.ini");

class ApiHotel extends TfbxmlResponse
{
	public function GetCity()
	{
		$arr_body = $this->arr_body;
		$firstLetter = trim($arr_body['firstLetter']);
		$cityName = trim($arr_body['cityName']);
		$cityName = auto_charset($cityName, 'utf-8', 'gbk');

		$sql = "SELECT id AS cityId, name AS cityName FROM ctrip_area_city ORDER BY id LIMIT 30";
		if($cityName != "")
		{
			$sql = "SELECT id AS cityId, name AS cityName FROM ctrip_area_city WHERE name LIKE '%" . $cityName . "%' ORDER BY id";
		}
		else if($firstLetter != "")
		{
			$sql = "SELECT id AS cityId, name AS cityName FROM ctrip_area_city WHERE english_name LIKE '" . $firstLetter . "%' ORDER BY id";
		}
		
		$db = new DB_test();
		$db->query($sql);
		
		if($db->nf() > 0)
		{
			$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
			$arr_message = array("result" => "success", "message" => "成功获取数据");
		}
		else
		{
			$arr_message = array("result" => "success", "message" => "没有返回数据");
		}
		$retcode = "0";
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	
	public function GetDistrict()
	{
		$arr_body = $this->arr_body;
		$cityId = intval($arr_body['cityId']);

		$sql = "SELECT id AS districtId, name AS districtName FROM ctrip_area_district WHERE city_id = " . $cityId;
		
		$db = new DB_test();
		$db->query($sql);
		
		if($db->nf() > 0)
		{
			$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
			$arr_message = array("result" => "success", "message" => "成功获取数据");
		}
		else
		{
			$arr_message = array("result" => "success", "message" => "没有返回数据");
		}
		$retcode = "0";
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	
	public function GetBusinessZone()
	{
		$arr_body = $this->arr_body;
		$cityId = intval($arr_body['cityId']);

		$sql = "SELECT id AS zoneId, name AS zoneName FROM ctrip_area_business_zone WHERE city_id = " . $cityId;
		
		$db = new DB_test();
		$db->query($sql);
		
		if($db->nf() > 0)
		{
			$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
			$arr_message = array("result" => "success", "message" => "成功获取数据");
		}
		else
		{
			$arr_message = array("result" => "success", "message" => "没有返回数据");
		}
		$retcode = "0";
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	
	public function GetHotelBrand()
	{
		$sql = "SELECT id AS brandId, REPLACE(name, '&', '&amp;') AS brandName FROM ctrip_hotel_brand";
		
		$db = new DB_test();
		$db->query($sql);
		
		if($db->nf() > 0)
		{
			$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
			$arr_message = array("result" => "success", "message" => "成功获取数据");
		}
		else
		{
			$arr_message = array("result" => "success", "message" => "没有返回数据");
		}
		$retcode = "0";
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	
	public function GetHotelTheme()
	{
		$sql = "SELECT id AS themeId, name AS themeName FROM ctrip_hotel_theme";
		
		$db = new DB_test();
		$db->query($sql);
		
		if($db->nf() > 0)
		{
			$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
			$arr_message = array("result" => "success", "message" => "成功获取数据");
		}
		else
		{
			$arr_message = array("result" => "success", "message" => "没有返回数据");
		}
		$retcode = "0";
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	
	public function GetCityLocation()
	{
		$arr_body = $this->arr_body;
		$cityId = intval($arr_body['cityId']);
		$locationType = intval($arr_body['locationType']);

		$sql = "SELECT id AS locationId, name AS locationName FROM ctrip_area_business_zone WHERE city_id = " . $cityId;
		
		$db = new DB_test();
		$db->query($sql);
		
		if($db->nf() > 0)
		{
			$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
			$arr_message = array("result" => "success", "message" => "成功获取数据");
		}
		else
		{
			$arr_message = array("result" => "success", "message" => "没有返回数据");
		}
		$retcode = "0";
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	
	public function GetHotelList()
	{
		$arr_body = $this->arr_body;
		$cityId = intval($arr_body['cityId']);

		$sql = "SELECT id AS hotelCode, name AS hotelName, image_url AS imageUrl, address, star_rate AS starRate, ctrip_star_rate AS ctripRate, FLOOR(100 + RAND() * 101) AS minPrice, '' AS description FROM ctrip_hotel LIMIT 30";
		
		$db = new DB_test();
		$db->query($sql);
		
		if($db->nf() > 0)
		{
			$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
			$arr_message = array("result" => "success", "message" => "成功获取数据");
		}
		else
		{
			$arr_message = array("result" => "success", "message" => "没有返回数据");
		}
		$retcode = "0";
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	
	public function GetHotelImage()
	{
		$sql = "SELECT url, caption FROM ctrip_hotel_image LIMIT 30";
		
		$db = new DB_test();
		$db->query($sql);
		
		if($db->nf() > 0)
		{
			$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
			$arr_message = array("result" => "success", "message" => "成功获取数据");
		}
		else
		{
			$arr_message = array("result" => "success", "message" => "没有返回数据");
		}
		$retcode = "0";
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	
	public function GetHotelService()
	{
		$sql = "SELECT name AS service FROM ctrip_hotel_baseinfo_service_type";
		
		$db = new DB_test();
		$db->query($sql);
		
		if($db->nf() > 0)
		{
			$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
			$arr_message = array("result" => "success", "message" => "成功获取数据");
		}
		else
		{
			$arr_message = array("result" => "success", "message" => "没有返回数据");
		}
		$retcode = "0";
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	
	public function GetGuestRoom()
	{
		$sql = "SELECT id, name, resident, bed_size AS bedSize, url FROM ctrip_hotel_room LEFT JOIN ctrip_hotel_image ON ctrip_hotel_room.name = ctrip_hotel_image.caption WHERE ctrip_hotel_room.hotel_id = 431626 AND ctrip_hotel_image.hotel_id = 431626 ORDER BY id, name";
		
		$db = new DB_test();
		$db->query($sql);
		
		$roomList = $db->get_all($sql);
		
		for($i = 0; $i < count($roomList); $i++)
		{
			$room = array();
			$room["code"] = $roomList[$i]["id"];
			$room["name"] = auto_charset($roomList[$i]["name"], 'gbk', 'utf-8');;
			$room["resident"] = $roomList[$i]["resident"];
			$room["bedSize"] = $roomList[$i]["bedSize"];
			$room["price"] = 124;
			$room["priceCode"] = 121242;
			$room["roomImage"][] = "http://Images4.c-ctrip.com/target/hotel/1000/217/d2e0c48eddbc4daca9137f1da436c948_550_412.jpg";
			$room["roomImage"][] = "http://Images4.c-ctrip.com/target/hotel/1000/83/5cc5832beaff4171aa8c8abb953aab01_550_412.jpg";
			$room["roomImage"][] = "http://Images4.c-ctrip.com/target/hotel/1000/86/71e73bfa996f434bb019d4484e244f00_550_412.jpg";
			$arr_msg['msgbody'][] = $room;
		}
		
		$arr_message = array("result" => "success", "message" => "成功获取数据");
		$retcode = "0";
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	
	public function GetRoomAmenity()
	{
		$sql = "SELECT name AS amenity FROM ctrip_hotel_baseinfo_room_amenity";
		
		$db = new DB_test();
		$db->query($sql);
		
		if($db->nf() > 0)
		{
			$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
			$arr_message = array("result" => "success", "message" => "成功获取数据");
		}
		else
		{
			$arr_message = array("result" => "success", "message" => "没有返回数据");
		}
		$retcode = "0";
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	
	public function CreateOrder()
	{
		$retcode = "0";
		
		$arr_msg['msgbody']['orderId'] = "tfb20141011151600";
		$arr_msg['msgbody']['money'] = 14.29;
		$arr_msg['msgbody']['result'] = "success";
		$arr_msg['msgbody']['message'] = "获取成功";
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	
	public function PayOrder()
	{
		$retcode = "0";
		
		$arr_msg['msgbody']['orderId'] = "tfb20141011151600";
		$arr_msg['msgbody']['needVerifyCode'] = 1;
		$arr_msg['msgbody']['result'] = "success";
		$arr_msg['msgbody']['message'] = "获取成功";
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	
	public function PayWithVerifyCode()
	{
		$retcode = "0";
		
		$arr_msg['msgbody']['result'] = "success";
		$arr_msg['msgbody']['message'] = "获取成功";
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
	
	public function GetOrderList()
	{
		$retcode = "0";
		
		$arr_msg['msgbody']['result'] = "success";
		$arr_msg['msgbody']['message'] = "获取成功";
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		
		return $returnval;
	}
}