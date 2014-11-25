#include <stdio.h>
#include "../include/tssx_sgip.h"


int main()
{
	int res,conn_id;
	unsigned long seq1,seq2,seq3;
	 
	set_src_node(1234);
	 
	conn_id=SGIP_Connect("10.130.54.10",9001,"sgip","sgip");
//	conn_id=SGIP_Connect("10.130.59.18",9001,"sgip","sgip");
	
	printf("connect res=%d\n",conn_id);
	
	if(conn_id<0)
		return(-1);
		
		
//	res=SGIP_Submit(conn_id,"8888","12345","TEST",1,"000000","000000",0,0,3,"","",0,0,0,0,15,"13501234567",1,"13800000000","测试1",5,&seq1,&seq2,&seq3);
	
	res=SGIP_Submit(conn_id,"191103","202","1234",1,"000000","000000",0,0,3,"0","0",1,0,0,0,15,"13001234567",1,"13000000000","我是流氓我怕谁;",strlen("我是流氓我怕谁;"),&seq1,&seq2,&seq3);	
	printf("submit res=%d seq=%lu %lu %lu\n",res,seq1,seq2,seq3);
    if(res<0)
		return(-1);
	
	res=SGIP_Disconnect(conn_id);
	printf("disconnect res=%d\n",res);
	
	return(0);
}
