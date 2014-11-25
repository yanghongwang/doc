<?php
require_once ("../class/Logger.php");
Logger::configure("../class/Logger.ini");

// 订机票接口
class ApiAirticket extends TfbxmlResponse
{
	public $aid = "20230";
	public $sid = "451200";
	public $key = "471B29E6-0CEF-4561-88CB-57C03C7C948C";
	
	public function GetSign($now, $requestType)
	{
		$key = strtoupper(md5($this->key));
		$plaintext = $now . $this->aid . $key . $this->sid . $requestType;
		$sign = strtoupper(md5($plaintext));
		return $sign;
	}
	
	public function getCity()
	{
		$arr_body = $this->arr_body;
		$firstLetter = trim($arr_body['firstLetter']);
		$cityName = trim($arr_body['cityName']);
		$cityName = auto_charset($cityName, 'utf-8', 'gbk');

		$sql = "SELECT id AS cityId, code AS cityCode, name AS cityNameCh FROM ctrip_area_city WHERE hot_city = 1 AND code != '' ORDER BY order_id LIMIT 30";
		if($cityName != "")
		{
			$sql = "SELECT id AS cityId, code AS cityCode, name AS cityNameCh FROM ctrip_area_city WHERE name LIKE '%" . $cityName . "%' AND code != '' ORDER BY id";
		}
		else if($firstLetter != "")
		{
			$sql = "SELECT id AS cityId, code AS cityCode, name AS cityNameCh FROM ctrip_area_city WHERE english_name LIKE '" . $firstLetter . "%' AND code != '' ORDER BY id";
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
	
	public function getAirline()
	{
		$arr_body = $this->arr_body;
		$departCityCode = trim($arr_body['departCityCode']);
		$arriveCityCode = trim($arr_body['arriveCityCode']);
		
		$departDate = trim($arr_body['departDate']);
		$returnDate = trim($arr_body['returnDate']);
		
		$ws = "http://openapi.ctrip.com/Flight/DomesticFlight/OTA_FlightSearch.asmx?wsdl";
		$now = time();
		$xml = '<?xml version="1.0" encoding="utf-8"?><Request><Header AllianceID="' . $this->aid . '" SID="' . $this->sid . '" TimeStamp="' . $now . '" RequestType="OTA_FlightSearch" Signature="' . $this->GetSign($now, "OTA_FlightSearch") . '" /><FlightSearchRequest><SearchType>' . ($returnDate != "" ? "D" : "S") . '</SearchType><Routes><FlightRoute><DepartCity>' . $departCityCode . '</DepartCity><ArriveCity>' . $arriveCityCode . '</ArriveCity><DepartDate>' . $departDate . '</DepartDate></FlightRoute>' . ($returnDate != "" ? ('<FlightRoute><DepartCity>' . $arriveCityCode . '</DepartCity><ArriveCity>' . $departCityCode . '</ArriveCity><DepartDate>' . $returnDate . '</DepartDate></FlightRoute>') : "") . '</Routes><IsLowestPrice>true</IsLowestPrice></FlightSearchRequest></Request>';
		$client = new SoapClient($ws);
		$result = $client->Request(array('requestXML' => $xml));
		$xml = simplexml_load_string($result->RequestResult);
		if(!isset($xml->FlightSearchResponse->FlightRoutes))
		{
ErrorReponse :: reponError(array('retcode' => '200', 'retmsg' => '很抱歉，没有找到符合筛选条件的航班。'));
		}
		$arr_msg['msgbody'] = array();
		$doubleFlightList = $xml->FlightSearchResponse->FlightRoutes;
		foreach($doubleFlightList->children() as $singleFlightList)
		{
			foreach($singleFlightList->FlightsList->children() as $flight)
			{
				$arr_msg['msgbody'][] = array(
				"takeOffTime" => strval($flight->TakeOffTime), 
				"arriveTime" => strval($flight->ArriveTime), 
				"flight" => strval($flight->Flight), 
				"craftType" => strval($flight->CraftType), 
				"airLineCode" => strval($flight->AirlineCode), 
				"airLineName" => GetAirlineName(strval($flight->AirlineCode)), 
				"price" => strval($flight->Price), 
				"quantity" => intval($flight->Quantity), 
				"dPortCode" => strval($flight->DPortCode),
				"dPortName" => GetAirPortName(strval($flight->DPortCode)),
				"aPortCode" => strval($flight->APortCode),
				"aPortName" => GetAirPortName(strval($flight->APortCode)),
				"dCityCode" => strval($flight->DepartCityCode));
			}
		}
		$retcode = "0";
		if(count($arr_msg['msgbody']) == 0)
		{
ErrorReponse :: reponError(array('retcode' => '200', 'retmsg' => '很抱歉，没有找到符合筛选条件的航班。'));	
		}
		
		$arr_message = array("result" => "success", "message" => "查询航班成功");
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}
	
	public function getAirlineDetail()
	{
		$retcode = "200";
		$arr_message = array("result" => "fail", "message" => "操作出现异常，请稍后再试！");

		$arr_body = $this->arr_body;
		$departCityCode = trim($arr_body['departCityCode']);
		$arriveCityCode = trim($arr_body['arriveCityCode']);
		
		$departTime = trim($arr_body['departTime']);
		$returnTime = trim($arr_body['returnTime']);

		$departDate = substr($departTime, 0, 10);
		$returnDate = substr($returnTime, 0, 10);
		
		$flight = trim($arr_body['flight']);
		$returnFlight = trim($arr_body['returnFlight']);
		
		$ws = "http://openapi.ctrip.com/Flight/DomesticFlight/OTA_FlightSearch.asmx?wsdl";
		$now = time();
		$xml = '<?xml version="1.0" encoding="utf-8"?><Request><Header AllianceID="' . $this->aid . '" SID="' . $this->sid . '" TimeStamp="' . $now . '" RequestType="OTA_FlightSearch" Signature="' . $this->GetSign($now, "OTA_FlightSearch") . '" /><FlightSearchRequest><SearchType>S</SearchType><Routes><FlightRoute><DepartCity>' . $departCityCode . '</DepartCity><ArriveCity>' . $arriveCityCode . '</ArriveCity><DepartDate>' . $departDate . '</DepartDate><EarliestDepartTime>' . $departTime . '</EarliestDepartTime><LatestDepartTime>' . $departTime . '</LatestDepartTime></FlightRoute>' . (($returnDate != "" && $returnTime != "" && $returnFlight != "") ? '<FlightRoute><DepartCity>' . $arriveCityCode . '</DepartCity><ArriveCity>' . $departCityCode . '</ArriveCity><DepartDate>' . $returnDate . '</DepartDate><EarliestDepartTime>' . $returnTime . '</EarliestDepartTime><LatestDepartTime>' . $returnTime . '</LatestDepartTime></FlightRoute>' : '') . '</Routes></FlightSearchRequest></Request>';
file_put_contents("/dx/mobilepay/log/debug.log", $xml . "\r\n", FILE_APPEND | LOCK_EX);
		$client = new SoapClient($ws);
		$result = $client->Request(array('requestXML' => $xml));
		
		$flightList = array();

		if(property_exists($result, "RequestResult"))
		{
			$xml = $result->RequestResult;
		}
		$xml = simplexml_load_string($xml);
		$flightCount = 0;
		if(isset($xml->FlightSearchResponse->FlightRoutes))
		{
			$flightRoutesNode = $xml->FlightSearchResponse->FlightRoutes;

			$db = new DB_test();
			foreach($flightRoutesNode->children() as $domesticFlightRouteNode)
			{
				$flightsListNode = $domesticFlightRouteNode->FlightsList;
				foreach ($flightsListNode->children() as $flightNode)
				{
					$sql = "INSERT INTO ctrip_airticket_flight_list (depart_city_code, depart_port_code, 
					arrive_city_code, arrive_port_code, 
					depart_time, arrive_time, 
					flight, craft_code, 
					airline_iata_code, seat_class, 
					sub_seat_class, display_sub_seat_class, 
					rate, price, 
					standard_price, tax, 
					oil_fee, standard_price_for_child, 
					tax_for_child, oil_fee_for_child, 
					standard_price_for_baby, tax_for_baby, 
					oil_fee_for_baby, meal_type, 
					stop_count, rer_code, 
					end_code, ref_code, 
					rer_note, end_note, 
					ref_note, remark, 
					quantity, punctuality_rate, 
					ticket_type, price_type, 
					product_type, product_source, 
					inventory_type, before_fly_date, 
					route_index, need_apply, 
					recommend, refund_fee_formula_id, 
					can_up_grade, 
					can_separate_sale, 
					can_no_defer, 
					can_post, 
					is_fly_man, 
					only_own_city, 
					is_lowest_cz_special_price, 
					allow_cp_type, 
					policy_id, 
					is_rebate, 
					rebate_amount) 
					VALUES 
					('" . $flightNode->DepartCityCode . "', '" . strval($flightNode->DPortCode) . "', '" . 
					strval($flightNode->ArriveCityCode) . "', '" . strval($flightNode->APortCode) . "', '" . 
					strval($flightNode->TakeOffTime) . "', '" . strval($flightNode->ArriveTime) . "', '" . 
					strval($flightNode->Flight) . "', '" . strval($flightNode->CraftType) . "', '" . 
					strval($flightNode->AirlineCode) . "', '" . strval($flightNode->Class) . "', '" . 
					strval($flightNode->SubClass) . "', '" . strval($flightNode->DisplaySubclass) . "', " . 
					doubleval($flightNode->Rate) . ", " . doubleval($flightNode->Price) . ", " . 
					doubleval($flightNode->StandardPrice) . ", " . doubleval($flightNode->AdultTax) . ", " . 
					doubleval($flightNode->AdultOilFee) . ", " . doubleval($flightNode->ChildStandardPrice) . ", " . doubleval($flightNode->ChildTax) . ", " . doubleval($flightNode->ChildOilFee) . ", " . 
					doubleval($flightNode->BabyStandardPrice) . ", " . doubleval($flightNode->BabyTax) . ", " . 
					doubleval($flightNode->BabyOilFee) . ", '" . strval($flightNode->MealType) . "', " . 
					intval($flightNode->StopTimes) . ", '" . strval($flightNode->Nonrer) . "', '" . 
					strval($flightNode->Nonend) . "', '" . strval($flightNode->Nonref) . "', '" . 
					strval($flightNode->Rernote) . "', '" . strval($flightNode->Endnote) . "', '" . 
					strval($flightNode->Nonref) . "', '" . strval($flightNode->Remarks) . "', " . 
					intval($flightNode->Quantity) . ", " . doubleval($flightNode->PunctualityRate) . ", " . 
					intval($flightNode->TicketType) . ", '" . strval($flightNode->PriceType) . "', '" . 
					strval($flightNode->ProductType) . "', " . intval($flightNode->ProductSource) . ", '" . 
					strval($flightNode->InventoryType) . "', " . intval($flightNode->BeforeFlyDate) . ", " . 
					intval($flightNode->RouteIndex) . ", '" . strval($flightNode->NeedApplyString) . "', " . 
					intval($flightNode->Recommend) . ", " . intval($flightNode->RefundFeeFormulaID) . ", " . 
					((strval($flightNode->CanUpGrade) == "true") ? 1 : 0) . ", " . 
					((strval($flightNode->CanSeparateSale) == "true") ? 1 : 0) . ", " . 
					((strval($flightNode->CanNoDefer) == "true") ? 1 : 0) . ", " . 
					((strval($flightNode->CanPost) == "true") ? 1 : 0) . ", " . 
					((strval($flightNode->IsFlyMan) == "true") ? 1 : 0) . ", " . 
					((strval($flightNode->OnlyOwnCity) == "true") ? 1 : 0) . ", " . 
					((strval($flightNode->IsLowestCZSpecialPrice) == "true") ? 1 : 0) . ", " . 
					intval($flightNode->AllowCPType) . ", " . intval($flightNode->PolicyID) . ", " . 
					((strval($flightNode->IsRebate) == "true") ? 1 : 0) . ", " . 
					intval($flightNode->PolicyID) . ");";
					$sql = auto_charset($sql, 'utf-8', 'gbk');
					$db->query($sql);
					$flightCount++;
				}
			}
			$lastInsertId = $db->get_insert_id();
			
			$sql = "SELECT id, flight, price, standard_price AS standardPrice, oil_fee AS oilFee, tax, standard_price_for_child AS standardPriceForChild, oil_fee_for_child AS oilFeeForChild, tax_for_child AS taxForChild, standard_price_for_baby AS standardPriceForBaby, oil_fee_for_baby AS oilFeeForBaby, tax_for_baby AS taxForBaby, quantity, display_sub_seat_class AS class, rer_note AS rerNote, end_note AS endNote, ref_note AS refNote FROM ctrip_airticket_flight_list WHERE depart_city_code = '" . $departCityCode . "' AND arrive_city_code = '" . $arriveCityCode . "' AND flight = '" . $flight . "' AND id > " . ($lastInsertId - $flightCount);
			
			if($returnDate != "" && $returnTime != "" && $returnFlight != "")
			{
				$sql .= " UNION SELECT id, flight, price, standard_price AS standardPrice, oil_fee AS oilFee, tax, standard_price_for_child AS standardPriceForChild, oil_fee_for_child AS oilFeeForChild, tax_for_child AS taxForChild, standard_price_for_baby AS standardPriceForBaby, oil_fee_for_baby AS oilFeeForBaby, tax_for_baby AS taxForBaby, quantity, display_sub_seat_class AS class, rer_note AS rerNote, end_note AS endNote, ref_note AS refNote FROM ctrip_airticket_flight_list WHERE depart_city_code = '" . $arriveCityCode . "' AND arrive_city_code = '" . $departCityCode . "' AND flight = '" . $returnFlight . "' AND id > " . ($lastInsertId - $flightCount);
			}
			
			$db->query($sql);
			$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
			$arr_message = array("result" => "success", "message" => "查询航班成功");
		}
		else
		{
			$arr_message = array("result" => "success", "message" => "航班信息有误");
		}
		
		$retcode = "0";
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}
	
	function savePassenger()
	{
$logger = Logger::getLogger("airticket");
$logger->info("保存用户信息");
		$authorid = trim($this->arr_channelinfo['authorid']);									// 必填
		if($authorid == "")	ErrorReponse :: reponError(array("retcode" => "200", "retmsg" => "用户输入信息不完整"));
		
		$arr_body = $this->arr_body;
		$name = trim($arr_body["name"]);														// 必填
		$cardType = trim($arr_body["cardType"]);												// 添加乘机人必填
		$cardId = trim($arr_body["cardId"]);													// 添加乘机人必填
		$phoneNumber = trim($arr_body["phoneNumber"]);											// 添加联系人必填
		$passengerType = intval($arr_body["passengerType"]);									// 添加乘机人必填
		
		if($cardId != "")
		{
			// 添加乘机人
			$sql = "INSERT ignore INTO ctrip_airticket_passenger (user_id, name, card_type, card_id, phone, gender, type) VALUES ('$authorid', '$name', '$cardType', '$cardId', '$phoneNumber', 'M', '$passengerType')";
		}
		else if($phoneNumber != "")
		{
			// 添加联系人
			$sql = "INSERT ignore INTO ctrip_airticket_contacter (user_id, name, phone) VALUES ('$authorid', '$name', '$phoneNumber')";
		}
		else
			ErrorReponse :: reponError(array("retcode" => "200", "retmsg" => "用户输入信息不完整"));
			
		if(isset($sql))
		{
			$sql = auto_charset($sql, 'utf-8', 'gbk');
			$db = new DB_test();
			$result = $db->query($sql);
			if($db->affected_rows() > 0)
			{
				$retcode = "0";
			$arr_message = array("result" => "success", "message" => "信息保存成功");
			}
			else
			{
				$retcode = "200";
			$arr_message = array("result" => "success", "message" => "请勿重复添加信息");
			}
		}
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}
	
	function getPassenger()
	{
		
		$authorid = trim($this->arr_channelinfo['authorid']);									// 必填
		$type = trim($this->arr_body['type']);													// 必填
		if($authorid != "")
		{
			if($type == "1")																	// 读取乘机人
			{
				$sql = "SELECT id, name, card_type AS cardType, card_id AS cardId, phone AS phoneNumber, type AS passengerType, gender FROM ctrip_airticket_passenger WHERE is_active = 1 AND user_id = " . $authorid;
			}
			else if($type == "2")																// 读取联系人
			{
				$sql = "SELECT id, name, phone AS phoneNumber FROM ctrip_airticket_contacter WHERE is_active = 1 AND user_id = " . $authorid;
			}
		}
		if(isset($sql))
		{
			$db = new DB_test();
			$db->query($sql);
			$arr_msg = auto_charset($db->getData('', 'msgbody'), 'gbk', 'utf-8');
			$retcode = "0";
			$arr_message = array("result" => "success", "message" => "");
			if($db->num_rows() == 0)
			{
				$retcode = "200";
			$arr_message = array("result" => "fail", "message" => "没有保存过相关信息");
			}
		}
		else
		{
			$arr_message = array("result" => "fail", "message" => "信息不完整");
		}
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}
	
	function deletePassenger()
	{
		$authorid = trim($this->arr_channelinfo['authorid']);									// 必填
		if($authorid == "")	ErrorReponse :: reponError(array("retcode" => "200", "retmsg" => "用户输入信息不完整"));
		
		$arr_body = $this->arr_body;
		$id = trim($arr_body["id"]);
		$type = trim($arr_body["type"]);
		
		if($type == "1")
		{
			// 删除乘机人
			$sql = "UPDATE ctrip_airticket_passenger SET is_active = 0 WHERE id = " . $id;
		}
		else if($type == "2")
		{
			// 删除联系人
			$sql = "UPDATE ctrip_airticket_contacter SET is_active = 0 WHERE id = " . $id;
		}
		else
			ErrorReponse :: reponError(array("retcode" => "200", "retmsg" => "用户输入信息不完整"));
			
		if(isset($sql))
		{
			$db = new DB_test();
			$result = $db->query($sql);
			$retcode = "0";
			$arr_message = array("result" => "success", "message" => "操作成功");
		}
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array ("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
		return $returnval;
	}
	
	function createOrder()
	{
		$result = mt_rand();
		
		if($result % 10 == 0)
		{
			$retcode = "200";
			$arr_message = array("result" => "fail", "message" => "订单处理失败！");
		}
		else
		{
			$retcode = "0";
			$arr_message = array("result" => "success", "message" => "订单处理成功！");
			$arr_msg['msgbody']['orderId'] = $result;
			$arr_msg['msgbody']['verifyCode'] = "103146";
		}
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
		
		// 往订单表、订单详情表写数据
		// 调用携程接口，生成临时订单号
		// 保存传递过来的支付卡信息
	}
	
	function payWithCreditCard()
	{
		$result = mt_rand();
		
		if($result % 10 == 0)
		{
			$retcode = "200";
			$arr_message = array("result" => "fail", "message" => "订单处理失败！");
		}
		else
		{
			$retcode = "0";
			$arr_message = array("result" => "success", "message" => "订单处理成功！");
		}
		
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
		
		// 调用携程接口，支付
		
		/*
if(!is_array($passengerList) || count($passengerList) == 0)	return false;
		if(!is_array($contacterList) || count($contacterList) != 1)	return false;
		
		// 乘机人信息
		$passengerXml = "<PassengerList>";
		foreach($passengerList as $value)
		{
			$passengerXml .= "<PassengerRequest><PassengerName>" . $value["name"] . "</PassengerName><BirthDay>" . $value["birthDay"] . "</BirthDay><PassportTypeID>" . $value["cardType"] . "</PassportTypeID><PassportNo>" . $value["cardId"] . "</PassportNo><ContactTelephone /><Gender>" . $value["gender"] . "</Gender><NationalityCode></NationalityCode></PassengerRequest>";
		}
		$passengerXml .= "</PassengerList>";
		
		$passengerCount = count($passengerList);											// 乘客个数
		
		// 联系人信息
		$contacterXml = "<ContactInfo><ContactName>" . $contacterList[0]["name"] . "</ContactName><ConfirmOption>TEL</ConfirmOption><MobilePhone>" . $contacterList[0]["phone"] . "</MobilePhone><ContactTel /><ForeignMobile /><MobileCountryFix /><ContactEMail /><ContactFax /></ContactInfo>";
		
		// 送票信息
		$deliverXml = "<DeliverInfo><DeliveryType>PJN</DeliveryType><SendTicketCityID>0</SendTicketCityID><OrderRemark></OrderRemark><PJS><Receiver /><Province /><City /><Canton /><Address /><PostCode /></PJS></DeliverInfo>";
		
		$price = 850 + 50 + 120;
		$amount = $price * $passengerCount;
		
		$now = time();
		$ws = "http://openapi.ctrip.com/Flight/DomesticFlight/OTA_FltSaveOrder.asmx?wsdl";
		$xml = '<?xml version="1.0" encoding="utf-8"?><Request><Header AllianceID="' . self :: $aid . '" SID="' . self :: $sid . '" TimeStamp="' . $now . '" RequestType="OTA_FltSaveOrder" Signature="' . self :: GetSign($now, "OTA_FltSaveOrder") . '" /><FltSaveOrderRequest><UID>86826d23-5fee-4f00-b66e-89f4b76119f2</UID><OrderType>ADU</OrderType><Amount>' . $amount . '</Amount>
  <ProcessDesc></ProcessDesc>
  <FlightInfoList>
    <FlightInfoRequest>
      <DepartCityID>1</DepartCityID>
      <ArriveCityID>2</ArriveCityID>
      <DPortCode>SHA</DPortCode>
      <APortCode>PEK</APortCode>
      <AirlineCode>HU</AirlineCode>
      <Flight>HU7604</Flight>
      <Class>Y</Class>
      <SubClass>L</SubClass>
      <TakeOffTime>2014-09-20T08:25:00</TakeOffTime>
      <ArrivalTime>2014-09-20T10:50:00</ArrivalTime>
      <Rate>0.75</Rate>
      <Price>850</Price>
      <Tax>50</Tax>
      <OilFee>120</OilFee>
      <NonRer>H</NonRer>
      <NonRef>T</NonRef>
      <NonEnd>H</NonEnd>
      <RerNote>起飞前（含）相同舱位更改每次收取票面价10％的更改费，起飞后更改每次收取票面价20％的更改费。升舱费与改期费同时发生时，需同时收取。</RerNote>
      <RefNote>起飞前（含）办理需收取票面价20％的退票费，起飞后需收取票面价30％的退票费。</RefNote>
      <EndNote>不得签转。</EndNote>
      <Remark>yeye特价产品^</Remark>
      <NeedAppl>F</NeedAppl>
      <Recommend>2</Recommend>
      <Canpost>T</Canpost>
      <CraftType>738</CraftType>
      <Quantity>10</Quantity>
      <RefundFeeFormulaID>16</RefundFeeFormulaID>
      <UpGrade>T</UpGrade>
      <TicketType>1111</TicketType>
      <AllowCPType>1111</AllowCPType>
      <ProductType>NORMAL</ProductType>
      <ProductSource>3</ProductSource>
      <InventoryType>Fav</InventoryType>
      <PriceType>NormalPrice</PriceType>
      <Onlyowncity>false</Onlyowncity>
      <CanSeparateSale />
      <RouteIndex>0</RouteIndex>
    </FlightInfoRequest>
  </FlightInfoList>' . $passengerXml . $contacterXml . $deliverXml . '
</FltSaveOrderRequest>
</Request>';

$logger = \Logger :: GetLogger("ctrip");
$logger->debug("生成订单：请求xml\n" . $xml, __FUNCTION__);



		$client = new \SoapClient($ws);
		$result = $client->Request(array('requestXML' => $xml));
		$xml = simplexml_load_string($result->RequestResult);
$logger->debug("生成订单：应答xml\n" . print_r($xml, true), __FUNCTION__);
		*/
		
		// 代理商分润
	}
	
	function getOrderHistory()
	{
		$data = array();
		$data[] = array("departCity" => "北京", "arriveCity" => "上海", "createOrderTime" => "2013-05-20T07:55:00", "takeOffTime" => "2014-05-20T07:55:00", "flight" => "CA012", "craftType" => "77A", "totalPrice" => "1246.25", "status" => "订单完成");
		$data[] = array("departCity" => "北京", "arriveCity" => "广州", "createOrderTime" => "2013-05-20T07:55:00", "takeOffTime" => "2014-05-20T07:55:00", "flight" => "CA12", "craftType" => "320", "totalPrice" => "1246.25", "status" => "订单完成");
		$arr_msg['msgbody'] = $data;
		$retcode = "0";
		$arr_message = array("result" => "success", "message" => "");
		$arr_msg['msgbody']['result'] = $arr_message['result'];
		$arr_msg['msgbody']['message'] = $arr_message['message'];
		$returnvalue = array("msgbody" => $arr_msg['msgbody']);
		$returnval = TfbxmlResponse :: ResponsetoApp($retcode, $returnvalue);
        return $returnval;
		
		// 从订单表和订单详情表中获取数据
	}
}

function GetAirPortName($code)
{
	
	$dbfile = new DB_test ( );
	$query = "select name from tb_airticket_airport where code='$code'";
	
	$dbfile->query ( $query );
	
	if ($dbfile->nf ()) {
		$dbfile->next_record ();
		$name = $dbfile->f ( name );
	}
	else
	{
		$name = $code;
	}
	$name = auto_charset($name, 'gbk', 'utf-8');
	return $name;
}

function GetAirlineName($code)
{
	
	$dbfile = new DB_test ( );
	$query = "select name from tb_airticket_airline where code='$code'";
	
	$dbfile->query ( $query );
	
	if ($dbfile->nf ()) {
		$dbfile->next_record ();
		$name = $dbfile->f ( name );
	}
	else
	{
		$name = $code;
	}
	$name = auto_charset($name, 'gbk', 'utf-8');
	return $name;
}