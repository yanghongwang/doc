// testDlg.cpp : implementation file
//

#include "stdafx.h"
#include "test.h"
#include "testDlg.h"
//#include "SGIPAPIDef.h"
#include "Interface.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CAboutDlg dialog used for App About

class CAboutDlg : public CDialog
{
public:
	CAboutDlg();

// Dialog Data
	//{{AFX_DATA(CAboutDlg)
	enum { IDD = IDD_ABOUTBOX };
	//}}AFX_DATA

	// ClassWizard generated virtual function overrides
	//{{AFX_VIRTUAL(CAboutDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV support
	//}}AFX_VIRTUAL

// Implementation
protected:
	//{{AFX_MSG(CAboutDlg)
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};

CAboutDlg::CAboutDlg() : CDialog(CAboutDlg::IDD)
{
	//{{AFX_DATA_INIT(CAboutDlg)
	//}}AFX_DATA_INIT
}

void CAboutDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CAboutDlg)
	//}}AFX_DATA_MAP
}

BEGIN_MESSAGE_MAP(CAboutDlg, CDialog)
	//{{AFX_MSG_MAP(CAboutDlg)
		// No message handlers
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CTestDlg dialog

CTestDlg::CTestDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CTestDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CTestDlg)
	m_count = 1;
	m_second = 1;
	m_ipaddr = _T("10.130.54.10");
	m_mobile = _T("13788888800");
	m_sendmsg = _T("中兴通讯 SGIP_API 测试!");
	m_sppass = _T("sgip");
	m_spuser = _T("sgip");
	m_nPort  = 9001;
	m_bShowMessageBox = FALSE;
	m_sendmsg = _T("");
	m_recvmsg = _T("");
	//}}AFX_DATA_INIT
	// Note that LoadIcon does not require a subsequent DestroyIcon in Win32

	
	m_hIcon = AfxGetApp()->LoadIcon(IDR_MAINFRAME);
}

void CTestDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CTestDlg)
	DDX_Text(pDX, IDC_COUNT, m_count);
	DDX_Text(pDX, IDC_SEND_TIME, m_second);
	DDX_Text(pDX, IDC_IPADDR, m_ipaddr);
	DDX_Text(pDX, IDC_mobile, m_mobile);
	DDX_Text(pDX, IDC_sppass, m_sppass);
	DDX_Text(pDX, IDC_spuser, m_spuser);
	DDX_Text(pDX, IDC_GW, m_nPort);
	DDV_MinMaxUInt(pDX, m_nPort, 0, 60256);
	DDX_Check(pDX, IDC_CHECK1, m_bShowMessageBox);
	DDX_Text(pDX, IDC_EDIT1, m_sendmsg);
	DDV_MaxChars(pDX, m_sendmsg, 256);
	DDX_Text(pDX, IDC_EDIT2, m_recvmsg);
	//}}AFX_DATA_MAP
}

BEGIN_MESSAGE_MAP(CTestDlg, CDialog)
	//{{AFX_MSG_MAP(CTestDlg)
	ON_WM_SYSCOMMAND()
	ON_WM_PAINT()
	ON_WM_QUERYDRAGICON()
	ON_BN_CLICKED(IDC_BUTTON1, OnButton1)
	ON_BN_CLICKED(IDC_BUTTON2, OnButton2)
	ON_EN_CHANGE(IDC_COUNT, OnChangeCount)
	ON_EN_CHANGE(IDC_SEND_TIME, OnChangeSendTime)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CTestDlg message handlers

BOOL CTestDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	// Add "About..." menu item to system menu.

	// IDM_ABOUTBOX must be in the system command range.
	ASSERT((IDM_ABOUTBOX & 0xFFF0) == IDM_ABOUTBOX);
	ASSERT(IDM_ABOUTBOX < 0xF000);

	CMenu* pSysMenu = GetSystemMenu(FALSE);
	if (pSysMenu != NULL)
	{
		CString strAboutMenu;
		strAboutMenu.LoadString(IDS_ABOUTBOX);
		if (!strAboutMenu.IsEmpty())
		{
			pSysMenu->AppendMenu(MF_SEPARATOR);
			pSysMenu->AppendMenu(MF_STRING, IDM_ABOUTBOX, strAboutMenu);
		}
	}

	// Set the icon for this dialog.  The framework does this automatically
	//  when the application's main window is not a dialog
	SetIcon(m_hIcon, TRUE);			// Set big icon
	SetIcon(m_hIcon, FALSE);		// Set small icon
	
	// TODO: Add extra initialization here
	m_bShowMessageBox = FALSE;
	SetWindowText("SGIP_API_TEST");
	return TRUE;  // return TRUE  unless you set the focus to a control
}

void CTestDlg::OnSysCommand(UINT nID, LPARAM lParam)
{
	if ((nID & 0xFFF0) == IDM_ABOUTBOX)
	{
		CAboutDlg dlgAbout;
		dlgAbout.DoModal();
	}
	else
	{
		CDialog::OnSysCommand(nID, lParam);
	}
}

// If you add a minimize button to your dialog, you will need the code below
//  to draw the icon.  For MFC applications using the document/view model,
//  this is automatically done for you by the framework.

void CTestDlg::OnPaint() 
{
	if (IsIconic())
	{
		CPaintDC dc(this); // device context for painting

		SendMessage(WM_ICONERASEBKGND, (WPARAM) dc.GetSafeHdc(), 0);

		// Center icon in client rectangle
		int cxIcon = GetSystemMetrics(SM_CXICON);
		int cyIcon = GetSystemMetrics(SM_CYICON);
		CRect rect;
		GetClientRect(&rect);
		int x = (rect.Width() - cxIcon + 1) / 2;
		int y = (rect.Height() - cyIcon + 1) / 2;

		// Draw the icon
		dc.DrawIcon(x, y, m_hIcon);
	}
	else
	{
		CDialog::OnPaint();
	}
}

// The system calls this to obtain the cursor to display while the user drags
//  the minimized window.
HCURSOR CTestDlg::OnQueryDragIcon()
{
	return (HCURSOR) m_hIcon;
}

void CTestDlg::OnButton1() 
{
	// TODO: Add your control notification handler code here
	int res,conn_id;
	unsigned long seq1,seq2,seq3;
	char buf[1024];
	int i=0,j=0;
	set_src_node(1234);
	char C_result[1000];
	char C_failres[50000];
	char C_succres[950000];
	int fail_time=0,succ_time=0;
	FILE *logfile=NULL;
	char C_ip[20];
	char C_spname[20];
	char C_sppass[20];
	char C_mobile[21];

//	int conn[200];
	int timeout=100;
//	Set_Timeout(timeout);
	memset(&C_ip,0,strlen(C_ip));
	memset(&C_spname,0,strlen(C_spname));
	memset(&C_sppass,0,strlen(C_sppass));
	memset(&C_mobile,0,strlen(C_mobile));
	UpdateData(TRUE);
	strncpy(C_ip,m_ipaddr,m_ipaddr.GetLength());
	strncpy(C_spname,m_spuser,m_spuser.GetLength());
	strncpy(C_sppass,m_sppass,m_sppass.GetLength());
	strncpy(C_mobile,m_mobile,m_mobile.GetLength());

//	logfile=fopen("log","a");
//	if (logfile!=NULL)
		//fputs("\n\n",logfile);
	CTime gt;
	UpdateData(TRUE);


	conn_id=SGIP_Connect(C_ip,m_nPort,C_spname,C_sppass);
//	conn_id=SGIP_Connect(C_ip,9907,C_spname,C_sppass);
	if(conn_id<0)
	{
		sprintf(buf,"connect fail error= %d\n",conn_id);
		if (logfile!=NULL)
		{
			//fputs(buf,logfile);
			fclose(logfile);
			logfile=NULL;
		}
		if(m_bShowMessageBox)
			MessageBox(buf,"error",MB_OK);
		return;
	}else
	{
		sprintf(buf,"connect succ = %d\n",conn_id);
		if (logfile!=NULL)
		{
			//fputs(buf,logfile);
			fclose(logfile);
			logfile=NULL;
		}
		if(m_bShowMessageBox)
			MessageBox("connect OK","OK",MB_OK);
	}	
	UpdateData(TRUE);
	memset(&C_failres,0,strlen(C_failres));
	memset(&C_succres,0,strlen(C_succres));

	for (i=1;i<=m_second;i++)
	{

		for (j=1;j<=m_count;j++)
		{
//			res=SGIP_Submit(conn_id,"191103","20264","TEST",'1',"000000","000000",0,0,3,"0","0",1,0,0,0,15,C_mobile,1,C_mobile,"测试1",5,&seq1,&seq2,&seq3);
			res=SGIP_Submit(conn_id,"191103","202","1234",1,"000000","000000",0,0,3,"0","0",1,0,0,0,15,C_mobile,1,C_mobile,m_sendmsg.GetBuffer(0),m_sendmsg.GetLength(),&seq1,&seq2,&seq3);
			memset(&C_result,0,strlen(C_result));
			sprintf(C_result,"%d",res);
			//MessageBox(C_result,"submit res",MB_OK);
//			logfile=fopen("log","a");
			if (logfile!=NULL)
				//fputs("\n",logfile);
			memset(&C_result,0,strlen(C_result));
			if (res<0)
			{
					if (fail_time%3==0&&fail_time!=0)
						sprintf(C_result,"submit res=%d seq=%lu %lu %lu\n",res,seq1,seq2,seq3);
					else
						sprintf(C_result,"submit res=%d seq=%lu %lu %lu",res,seq1,seq2,seq3);
					fail_time++;
					////fputs(asctime(localtime(&now)),logfile);
					//fputs(C_result,logfile);
					strcat(C_failres,C_result);
					SGIP_Disconnect(conn_id);
					if (logfile!=NULL)
						//fputs("disconnect\n",logfile);
					closesocket(conn_id);
					conn_id=SGIP_Connect(C_ip,8801,C_spname,C_sppass);
					if (logfile!=NULL)
						//fputs("reconnect gw:",logfile);
					if(conn_id<0)
					{
						sprintf(buf,"reconnect fail error= %d",conn_id);
						if (logfile!=NULL)
							//fputs(buf,logfile);
						if(m_bShowMessageBox)
							MessageBox(buf,"error",MB_OK);
						sprintf(C_failres,"\ntotal fail times:%d",fail_time);
						sprintf(C_succres,"\ntotal succ times:%d",succ_time);
						if (logfile!=NULL)
						{
							//fputs(C_failres,logfile);
							//fputs("\n",logfile);
							//fputs(C_succres,logfile);
							fclose(logfile);
							logfile=NULL;
						}
						if(m_bShowMessageBox)
						{
							MessageBox(C_failres,"error",MB_OK);
							MessageBox(C_succres,"ok",MB_OK);
						}
						return;
					}else
					{
						sprintf(buf,"reconnect succ = %d",conn_id);
						if (logfile!=NULL)
							//fputs(buf,logfile);
						if(m_bShowMessageBox)
							MessageBox("reconnect OK","OK",MB_OK);
					}
			}
			else 
			{
					if (succ_time%3==0&&succ_time!=0)
						sprintf(C_result,"submit res=%d seq=%lu %lu %lu\n",res,seq1,seq2,seq3);
					else
						sprintf(C_result,"submit res=%d seq=%lu %lu %lu",res,seq1,seq2,seq3);
					////fputs(asctime(localtime(&now)),logfile);
					if (logfile!=NULL)
						//fputs(C_result,logfile);
					strcat(C_succres,C_result);
					succ_time++;

					//for short connection
					/*
					res=SGIP_Disconnect(conn_id);
					logfile=fopen("log","a");
					if(res<0){
						sprintf(buf,"\nshort connection:disconnect fail error= %d",conn_id);
						if (logfile!=NULL)
						{
							//fputs(buf,logfile);
							fclose(logfile);
							logfile=NULL;
						}
						//MessageBox(buf,"error",MB_OK);
						return;
					}else
					{
						if (logfile!=NULL)
							//fputs("\nshort connection:disconnect OK,exit test",logfile);
						//fclose(logfile);
						//MessageBox("disconnect OK","OK",MB_OK);
					}		
					conn_id=SGIP_Connect(C_ip,8801,C_spname,C_sppass);
					if(conn_id<0)
					{
						sprintf(buf,"connect fail error= %d",conn_id);
						if (logfile!=NULL)
						{
							//fputs(buf,logfile);
							fclose(logfile);
						}
						//MessageBox(buf,"error",MB_OK);
						return;
					}else
					{
						sprintf(buf,"connect succ = %d",conn_id);
						if (logfile!=NULL)
							//fputs(buf,logfile);
						//fclose(logfile);
						//MessageBox("connect OK","OK",MB_OK);
					}
					*/
			}
			if(logfile != NULL)fclose(logfile);
			logfile=NULL;
		}
		Sleep(1000); 
	}
	sprintf(C_result,"\ntotal fail times:%d",fail_time);
	strcat(C_failres,C_result);
	sprintf(C_result,"\ntotal succ times:%d",succ_time);
	strcat(C_succres,C_result);
	if(m_bShowMessageBox)
	{
		MessageBox(C_failres,"error",MB_OK);
		MessageBox(C_succres,"ok",MB_OK);
	}
	

	res=SGIP_Disconnect(conn_id);
	logfile=fopen("log","a");
	if(res<0){
		sprintf(buf,"\ndisconnect fail error= %d",conn_id);
		if (logfile!=NULL)
		{
			//fputs(buf,logfile);
			fclose(logfile);
			logfile=NULL;
		}
		if(m_bShowMessageBox)
			MessageBox(buf,"error",MB_OK);	
		return;
	}else
	{
		if (logfile!=NULL)
		{
			//fputs("\ndisconnect OK,exit test",logfile);
			fclose(logfile);
			logfile=NULL;
		}
		if(m_bShowMessageBox)
			MessageBox("disconnect OK","OK",MB_OK);
	}		

}


void CTestDlg::OnButton2() 
{
	// TODO: Add your control notification handler code here
	UpdateData(TRUE);

	int res ;
//	void *p_MO=NULL;
	char p_MO[256];
	int listenfd,connfd;
    struct sockaddr_in servaddr;
	char buf[1024];
        
	struct MO_msg *p_msg=NULL;
	struct MO_report *p_report=NULL;
 
	memset(p_MO,0,256);
 	listenfd=socket(AF_INET,SOCK_STREAM,0);
     if(listenfd<0){
                sprintf(buf,"socket create error,%s",strerror(errno));
                MessageBox(buf,"error",MB_OK);
				return;
        }
        
       
        memset(&servaddr,0,sizeof(struct sockaddr_in));
        servaddr.sin_family=AF_INET;
        servaddr.sin_addr.s_addr=htonl(INADDR_ANY);
        servaddr.sin_port=htons(8801);

        if(bind(listenfd,(struct sockaddr *)&servaddr,sizeof(struct sockaddr_in))<0)
        {
                sprintf(buf,"socket bind error,%s",strerror(errno));
                if(m_bShowMessageBox) 
					MessageBox(buf,"error",MB_OK);
				return;
        }
        if(listen(listenfd,5)<0)
        {
                sprintf(buf,"socket listen error,%s",strerror(errno));
                if(m_bShowMessageBox)
					MessageBox(buf,"error",MB_OK); 
                return;
        }

        connfd=accept(listenfd,NULL,NULL);

		if(connfd<0)
		{
			sprintf(buf,"socket accept error,%s",strerror(errno));
			if(m_bShowMessageBox)
				MessageBox(buf,"error",MB_OK); 
            return;
		}
			
			
	 	while(1)
	 	{
		 	res=SGIP_Get_MO(connfd,(void**)&p_MO);
			if(res==ERROR_SOCKET_CLOSE)
			{
				sprintf(buf,"connection clsed by gateway\r\n");
				if(m_bShowMessageBox)
					MessageBox(buf,"error",MB_OK); 
				m_recvmsg = buf;
				UpdateData(FALSE);
                break;
			}
			if(res<0){
				sprintf(buf,"get mo error = %d\n",res);
				if(m_bShowMessageBox)
					MessageBox(buf,"error",MB_OK); 
				m_recvmsg = buf;
				UpdateData(FALSE);
                break;
			}
			else if(res==MO_MSG)
			{
				//printf("get mo msg:");
				p_msg=(struct MO_msg *)(p_MO);
				p_msg->msg[p_msg->msgLen]=0;
				sprintf(buf,"spnum:%s sourceUser:%s,msgFormat:%d msgLen:%lu\n",
						p_msg->SPNumber,p_msg->sourceUser,p_msg->msgFormat,p_msg->msgLen);
				if(m_bShowMessageBox)
					MessageBox(buf,"MO MSG",MB_OK); 
                 
				sprintf(buf,"msg=%s\n",p_msg->msg);
				if(m_bShowMessageBox)
					MessageBox(buf,"MO MSG",MB_OK); 
				m_recvmsg = buf;
				UpdateData(FALSE);

			}
			else if(res==MO_REPORT)
			{
				//printf("get mo report: ");
				p_report=(struct MO_report *)(p_MO);
		 
				if(m_bShowMessageBox)
				{
					sprintf(buf,"mobile:%s \n",p_report->mobile);
					MessageBox(buf,"MO REPORT",MB_OK); 
					sprintf(buf,"seq:%lu %lu %lu state:%d errcode:%d\n",
						p_report->seq1,p_report->seq2,p_report->seq3,p_report->state,p_report->errCode);
					MessageBox(buf,"MO REPORT",MB_OK); 
				}		
		 	}
		 //break;
		}
	 	closesocket(connfd);	
		 
		//printf("close connection\n");
		closesocket(listenfd);
}

void CTestDlg::OnChangeCount() 
{
	// TODO: If this is a RICHEDIT control, the control will not
	// send this notification unless you override the CDialog::OnInitDialog()
	// function and call CRichEditCtrl().SetEventMask()
	// with the ENM_CHANGE flag ORed into the mask.
	
	// TODO: Add your control notification handler code here
	
}

void CTestDlg::OnChangeSendTime() 
{
	// TODO: If this is a RICHEDIT control, the control will not
	// send this notification unless you override the CDialog::OnInitDialog()
	// function and call CRichEditCtrl().SetEventMask()
	// with the ENM_CHANGE flag ORed into the mask.
	
	// TODO: Add your control notification handler code here
	
}

void CTestDlg::OnCancel() 
{
	// TODO: Add extra cleanup here
	
	CDialog::OnCancel();
}
