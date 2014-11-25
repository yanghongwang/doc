<?php
require_once ("../include/config.inc.php");

require_once ("../class/Logger.php");
Logger::configure("../class/Logger.ini");

$phone = $_GET["phone"];
$loginPassword = $_GET["loginPassword"];
$agentCode = strval($_GET["agentCode"]);

$logger = Logger::getLogger('AuthorInfo');
$logger->info("开始注册 : phone : $phone, agentCode : $agentCode");
$authorId = AuthorInfo :: Register($phone, md5($loginPassword));
if($authorId > 0)
{
	echo "1";
	if($agentCode != "")
	{
		if(AuthorInfo :: RelateAgent($authorId, $agentCode))
		{
			//echo "绑定代理商成功";
		}
		else
		{
			//echo "代理商编号不存在";
		}
	}
	//header("Location: http://a.app.qq.com/o/simple.jsp?pkgname=com.inter.trade&g_f=995087");
}
else
{
	echo "0";
}

class AuthorInfo
{
	public function Register($phone, $loginPassword)
	{
		$db = new DB_test();
		$query = "SELECT 1 FROM tb_author WHERE fd_author_username = '$phone'";
		$db->query($query);
		if($db->nf())
		{
			return 0;
		}
		else
		{
			$query = "INSERT INTO tb_author (fd_author_username, fd_author_paypassword, fd_author_mobile, fd_author_regtime, fd_author_datetime, fd_author_isstop, fd_author_state, fd_author_sdcrid, fd_author_auindustryid, fd_author_slotpayfsetid, fd_author_slotscdmsetid, fd_author_bkcardpayfsetid, fd_author_bkcardscdmsetid, fd_author_couponstate, fd_author_memid, fd_author_shopid, fd_author_authortypeid) VALUES( '$phone' ,' $loginPassword', '$phone', now(), now(), 0, 9, 3, 4, 8, 14, 25, 9, 0, 3554, 102, 5)";
			$db->query($query);
			
			$authorId = $db->insert_id();
			
			return $authorId;
		}
	}
	
	public function RelateAgent($authorId, $agentCode)
	{
$logger = Logger::getLogger('AuthorInfo');
$logger->info("开始绑定代理商 : authorId : $authorId, agentCode : $agentCode");
		$db = new DB_test();
		$query = "SELECT fd_cus_id FROM tb_customer WHERE fd_cus_no = '$agentCode'";
$logger->debug("开始绑定代理商 : query : $query");
		$agentId = $db->get_all($query);
		if(!is_array($agentId) || count($agentId) != 1)
		{
$logger->debug("开始绑定代理商 : return 0");
			return 0;
		}
		else
		{
			$query = "UPDATE tb_author SET fd_author_bdagentno = '$agentCode', fd_author_bdagentid = " . $agentId[0]["fd_cus_id"] . ", fd_author_bdagenttime = NOW() WHERE fd_author_id = $authorId";
$logger->debug("开始绑定代理商 : query : $query");
			$db->query($query);
			return 1;
		}
	}
}