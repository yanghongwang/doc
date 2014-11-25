<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<form action="register.php" method="get">
	<div id="hdivTotal"><h2>下载前，请先输入注册信息</h2>
	<table>
		<tr>
			<td>手机号:</td>
			<td><input id="userInputPhone" type="text" name="phone" /></td>
			<td id="userInputPhoneErrorInfo"></td>
		</tr>
		<tr>
			<td>密码:</td>
			<td><input id="userInputLoginPassword" type="password" name="loginPassword" /></td>
			<td id="userInputLoginPasswordErrorInfo"></td>
		</tr>
		<tr>
			<td>短信验证码:</td>
			<td><input id="userInputVerifyCode" type="text" name="verifyCode" /></td>
			<td><input type="submit" value="获取短信验证码" onclick="GetVerifyCode(); return false;" /></td>
		</tr>
		<tr>
			<td></td>
			<td id="userInputVerifyCodeErrorInfo"></td>
			<td></td>
		</tr>
	</table>
	<input type="submit" value="注册" onclick="return VerifyCode();" />
	<input type="reset" value="重置" />
	<input id="userInputAgentCode" type="hidden" value="<?php echo $_GET["agentcode"] ?>" name="agentCode" /></div>
</form>
<script>
var xmlHttp = null;
var verifyCode = '123456';
var sendVerifyCodeStatus = 0;
var lastSendVerifyCodeTime = 0;
function GetVerifyCode()
{
	var userInputPhone = document.getElementById("userInputPhone").value;
	if(userInputPhone == "")
	{
		document.getElementById("userInputPhoneErrorInfo").innerHTML = '<small>请输入手机号码</small>';
		return false;
	}
	if(!IsPhoneNumber(userInputPhone))
	{
		document.getElementById("userInputPhoneErrorInfo").innerHTML = '<small>您填写的手机号格式有误，请输入11位大陆手机号</small>';
		return false;
	}
	
	if(sendVerifyCodeStatus != 0)
	{
		var intervalSinceLastSend = (new Date()).getTime() - lastSendVerifyCodeTime;
		intervalSinceLastSend = 60 - parseInt(intervalSinceLastSend / 1000);
		
		if(intervalSinceLastSend > 0)
		{
			alert('验证码已发送，' + intervalSinceLastSend + '秒内未收到请重新获取');
			return false;
		}
	}
	
	if(verifyCode == '')
	{
		for(var i = 0; i < 6; i++)
		{
			verifyCode += Math.floor(Math.random() * 10);
		}
	}
	
	sendVerifyCodeStatus = 1;
	lastSendVerifyCodeTime = (new Date()).getTime();
	
	xmlHttp = GetXmlHttpObject();
	if(xmlHttp == null) return false;
	var url = "sendsms.php?phone=" + userInputPhone + "&verifycode=" + verifyCode;
	xmlHttp.onreadystatechange = StateChanged;
	xmlHttp.open("GET", url, false);
	xmlHttp.send(null);
}

function StateChanged()
{
	if(xmlHttp.readyState == 4 || xmlHttp.readyState == "complete")
	{
		var result = xmlHttp.responseText;
		if(result == 0)
		{
			document.getElementById("userInputPhoneErrorInfo").innerHTML = '<small>该手机号已注册过通付宝账户</small>';
		}
		else if(result == 1)
		{
			document.getElementById("hdivTotal").innerHTML = '恭喜你，注册成功<br>点击<a href="http://a.app.qq.com/o/simple.jsp?pkgname=com.inter.trade&g_f=995087">此处</a>跳转到下载页面';
			
			/*var start = new Date().getTime();
			while(true)
			{
				if((new Date().getTime() - start) > 3000)
					break;
			}
			
			window.location.href = "http://a.app.qq.com/o/simple.jsp?pkgname=com.inter.trade&g_f=995087"; */
		}
		else
		{
			alert(result);
		}
	}
}

function GetXmlHttpObject()
{
	var xmlHttp = null;
	try
	{
		xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
	}
	catch(e)
	{
		try
		{
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(e)
		{
			xmlHttp = new XMLHttpRequest();
		}
	}
	return xmlHttp; 
}

function VerifyCode()
{
	var userInputPhone = document.getElementById("userInputPhone").value;
	if(userInputPhone == "")
	{
		document.getElementById("userInputPhoneErrorInfo").innerHTML = '<small>请输入手机号码</small>';
		return false;
	}
	if(!IsPhoneNumber(userInputPhone))
	{
		document.getElementById("userInputPhoneErrorInfo").innerHTML = '<small>您填写的手机号格式有误，请输入11位大陆手机号</small>';
		return false;
	}
	var userInputLoginPassword = document.getElementById("userInputLoginPassword").value;
	if(userInputLoginPassword == "")
	{
		document.getElementById("userInputLoginPasswordErrorInfo").innerHTML = '<small>请输入登录密码</small>';
		return false;
	}
	if(!IsLoginPassword(userInputLoginPassword))
	{
		document.getElementById("userInputLoginPasswordErrorInfo").innerHTML = '<small>您填写的登录密码格式有误，请输入6-20位数字、字母或下划线</small>';
		return false;
	}
	
	var userInputVerifyCode = document.getElementById("userInputVerifyCode").value;
	if(userInputVerifyCode == "")
	{
		document.getElementById("userInputVerifyCodeErrorInfo").innerHTML = '<small>请输入短信验证码</small>';
		return false;
	}
	if(userInputVerifyCode != verifyCode)
	{
		document.getElementById("userInputVerifyCodeErrorInfo").innerHTML = '<small>验证码输入有误</small>';
		return false;
	}
	
	var userInputAgentCode = document.getElementById("userInputAgentCode").value;
	
	xmlHttp = GetXmlHttpObject();
	if(xmlHttp == null) return false;
	var url = "register.php?phone=" + userInputPhone + "&loginPassword=" + userInputLoginPassword + "&agentCode=" + userInputAgentCode;
	xmlHttp.onreadystatechange = StateChanged;
	xmlHttp.open("GET", url, false);
	xmlHttp.send(null);
	return false;
}

function IsPhoneNumber(phone)
{
	if(phone.length != 11) return false;
	var myreg = /^((13|14|15|17|18)+\d{9})$/;
	if(!myreg.test(phone))return false;
	
	return true;
}

function IsLoginPassword(loginPassword)
{
	if(loginPassword.length < 6) return false;
	var myreg = /^[a-zA-Z0-9_]{6,20}$/;
	if(!myreg.test(loginPassword))return false;
	
	return true;
}
</script>