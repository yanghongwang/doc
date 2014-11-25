<?php
$phone = $_GET["phone"];
$verifyCode = $_GET["verifycode"];

SendMessage($phone, "您的验证码是：" . $verifyCode . "，欢迎注册通付宝用户。如需帮助请联系客服。");
echo "验证码已发送，60秒内未收到请重新获取";

function SendMessage($phone, $message)
{
	$uid = "nicegan";
	$pwd = "chengan";
	$url = "http://www.106jiekou.com/webservice/sms.asmx/Submit";
	$param = "account=" . $uid . "&password=" . $pwd . "&mobile=" . $phone . "&content=" . rawurlencode($message);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	$returnValue = curl_exec($ch);
	curl_close($ch);
}