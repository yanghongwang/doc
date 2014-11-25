#include <stdio.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <errno.h>
#include <string.h>
#include <unistd.h>
#pragma pack(1)
#include "../include/tssx_sgip.h"

int main()
{
	int res ;
	//void *p_MO=NULL;
	char p_MO[256];
	int listenfd,connfd;
      struct sockaddr_in servaddr;
        
	struct MO_msg *p_msg=NULL;
	struct MO_report *p_report=NULL;
	
	char log[256];
	memset(log,0,256);
	memset(p_MO,0,256);
 
 	listenfd=socket(AF_INET,SOCK_STREAM,0);
      if(listenfd<0){
                printf("socket create error,%s",strerror(errno));
                exit(-1);
        }
        
       
        bzero(&servaddr,sizeof(struct sockaddr_in));
        servaddr.sin_family=AF_INET;
        servaddr.sin_addr.s_addr=htonl(INADDR_ANY);
        servaddr.sin_port=htons(8801);

        if(bind(listenfd,(struct sockaddr *)&servaddr,sizeof(struct sockaddr_in))<0)
        {
                printf("socket bind error,%s",strerror(errno));
                 
                exit(-1);
        }
        if(listen(listenfd,5)<0)
        {
                printf("socket listen error,%s",strerror(errno));
                 
                exit(-1);
        }

 
	while(1){
		 
       
        	connfd=accept(listenfd,NULL,NULL);
 
		printf("accept connection sockfd=%d \n",connfd);
		
		if(connfd<0)
			exit(-1);
			
			
	 	while(1)
	 	{
		 	res=SGIP_Get_MO(connfd,(void**)&p_MO);
			if(res==ERROR_SOCKET_CLOSE)
			{
				printf("connection clsed by gateway\n");
				break;
			}
			if(res<0){
				printf("get mo error = %d\n",res);
				break;
			}
			else if(res==MO_MSG)
			{
				printf("get mo msg:");
				p_msg=(struct MO_msg *)(p_MO);

				sprintf(log,"\r\nspnum:%s sourceUser:%s,msgFormat:%d msgLen:%d\n",
						p_msg->SPNumber,p_msg->sourceUser,p_msg->msgFormat,p_msg->msgLen);
				printf(log);
				sprintf(log,"msg=%s\n",p_msg->msg);
				printf(log);
			}
			else if(res==MO_REPORT)
			{
				printf("get mo report: ");
				p_report=(struct MO_report *)(p_MO);
		 
				printf("mobile:%s \n",
					p_report->mobile);
				printf("seq:%lu %lu %lu state:%d errcode:%d\n",
					p_report->seq1,p_report->seq2,p_report->seq3,p_report->state,p_report->errCode);
						
		 	}
		 
		}
	 	close(connfd);	
		 
		printf("close connection\n");

	}//end of while
		
	close(listenfd);
	return(0);
}
