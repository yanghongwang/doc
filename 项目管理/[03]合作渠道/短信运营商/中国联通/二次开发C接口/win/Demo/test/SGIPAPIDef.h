/*
 *Header File of the TSSX SGIP API
 * 
 *Created By DingLi 2001-07-01
 *
*/

#ifndef TSSX_SGIP_H
#define TSSX_SGIP_H

#define ERROR_OK			0

#define ERROR_SOCKET_CREATE  	-100
#define ERROR_CONNECT		-101
#define ERROR_SOCKET_WRITE	-102
#define ERROR_SOCKET_READ	-103
#define ERROR_ICP_ID		-104
#define ERROR_AUTH		-105
#define ERROR_MSG_LEN		-106
#define ERROR_FEE_CODE		-107
#define ERROR_SERVICE_ID	-108
#define ERROR_FLOW_CONTROL	-109
#define ERROR_SOCKET_CLOSE	-110
#define ERROR_CMD			-111


#define ERROR_INTERNAL		-200
#define ERROR_UNKNOWN		-201
#define ERROR_ARGUMENT		-202

#ifndef SGIPAPI_API
#define SGIPAPI_API
#endif

SGIPAPI_API void set_src_node(int num);   //设置源节点号
	

SGIPAPI_API int	SGIP_Connect(char *gw_ip,short port,char *username,char *passwd);
SGIPAPI_API int SGIP_Submit(int conn_id,				//the return value by SGIP_Connect		
			char *sp_dial_num,		//SP接入号
			char *ICPID,			//企业代码
			char *service_id,			//业务代码
			unsigned char fee_type,			//01=免费 02=按条 03=包月 04=封顶 05=SP收费
			char *fee_value,			//资费代码，以分为单位
			char *give_value,			//赠送费用代码，以分为单位
			unsigned char agent_flag,			//代收费标志，0：应收；1：实收
			unsigned char mt_flag,			//引起MT消息的原因
								//	0-MO点播引起的第一条MT消息；
								//	1-MO点播引起的非第一条MT消息；
								//	2-非MO点播引起的MT消息。

			unsigned char priority,		//优先级0-9从低到高
			char *valid_time,			//存活时间，格式为YYYYMMDDHHMISS
			char *at_time,			//存活时间，格式为YYYYMMDDHHMISS
			unsigned char report_flag,	//状态报告标记
								//	0-该条消息只有最后出错时要返回状态报告
								//	1-该条消息无论最后是否成功都要返回状态报告
								//	2-该条消息不需要返回状态报告
			
			unsigned char tp_pid,			//一般填0 具体值参见GSM03.40
			unsigned char tp_udhi,			//一般填0 具体值参见GSM03.40
			unsigned char msg_type,		//消息类型
			unsigned char msg_format,	//消息格式 0=ASCII 4=bin 8=UCS2 15=GB
			char *charge_mobile,		//付费号码，如果为空，则该条短消息产生的费用由UserNumber代表的用户支付
 			unsigned char user_num,		//接收手机个数 <100
			char *dest_mobiles,		//接收手机号码，以空格分隔
			char *msg,				//消息内容
			unsigned long  msg_len,		//消息长度
			unsigned long *p_seq1,		//返回生成的序列号1-3，可以传递NULL
			unsigned long *p_seq2,
			unsigned long *p_seq3
			);
SGIPAPI_API int SGIP_Disconnect(int conn_id);

 	
struct MO_msg{
        char    sourceUser[21];		//发送消息的手机
        char    SPNumber[21];		//SP接入号
        unsigned char    tp_pid;
        unsigned char    tp_udhi;
        unsigned char   msgFormat;		//消息格式
        unsigned long   msgLen;		//消息长度
        char    msg[160];			//消息内容
};     

struct MO_report{
 	unsigned long	seq1;					//相应的submit序列号1-3
	unsigned long	seq2;
	unsigned long	seq3;
	unsigned char	report_type;
	char			mobile[21];		//目的手机号
	unsigned char	state;				//状态 0：发送成功 1：等待发送 2：发送失败
	unsigned char	errCode;				//错误代码
};


#define MO_MSG 	1
#define MO_REPORT 2

SGIPAPI_API int SGIP_Get_MO(int conn_id,void **pp_MO);

#endif
