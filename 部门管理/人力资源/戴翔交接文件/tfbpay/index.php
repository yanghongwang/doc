<?php
$client = $_SERVER["HTTP_USER_AGENT"];
if(strrpos($client, "Windows") > 0 || strrpos($client, "Macintosh") > 0)
{
	header("Location: http://www.tfbpay.cn/tfbpay/pc/");
	exit;
}
else if(strrpos($client, "Android") > 0 || strrpos($client, "iPhone") > 0)
{
	header("Location: http://www.ms56.net/wap/msmobilepay.apk");
	exit;
}
header("Location: http://www.tfbpay.cn/tfbpay/pc/");
exit;