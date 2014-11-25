; CLW file contains information for the MFC ClassWizard

[General Info]
Version=1
LastClass=CTestDlg
LastTemplate=CEdit
NewFileInclude1=#include "stdafx.h"
NewFileInclude2=#include "test.h"

ClassCount=4
Class1=CTestApp
Class2=CTestDlg
Class3=CAboutDlg

ResourceCount=3
Resource1=IDD_ABOUTBOX
Resource2=IDR_MAINFRAME
Class4=count
Resource3=IDD_TEST_DIALOG

[CLS:CTestApp]
Type=0
HeaderFile=test.h
ImplementationFile=test.cpp
Filter=N

[CLS:CTestDlg]
Type=0
HeaderFile=testDlg.h
ImplementationFile=testDlg.cpp
Filter=D
BaseClass=CDialog
VirtualFilter=dWC
LastObject=IDC_BUTTON1

[CLS:CAboutDlg]
Type=0
HeaderFile=testDlg.h
ImplementationFile=testDlg.cpp
Filter=D

[DLG:IDD_ABOUTBOX]
Type=1
Class=CAboutDlg
ControlCount=4
Control1=IDC_STATIC,static,1342177283
Control2=IDC_STATIC,static,1342308480
Control3=IDC_STATIC,static,1342308352
Control4=IDOK,button,1342373889

[DLG:IDD_TEST_DIALOG]
Type=1
Class=CTestDlg
ControlCount=22
Control1=IDC_IPADDR,edit,1350631552
Control2=IDC_GW,edit,1350631552
Control3=IDC_spuser,edit,1350631552
Control4=IDC_sppass,edit,1350631552
Control5=IDC_mobile,edit,1350631552
Control6=IDC_COUNT,edit,1350631552
Control7=IDC_SEND_TIME,edit,1350631552
Control8=IDOK,button,1073807361
Control9=IDC_BUTTON1,button,1342242816
Control10=IDC_BUTTON2,button,1342242816
Control11=IDCANCEL,button,1342242816
Control12=IDC_STATIC,static,1342308352
Control13=IDC_STATIC,static,1342308352
Control14=IDC_STATIC,static,1342308352
Control15=IDC_STATIC,static,1342308352
Control16=IDC_STATIC,static,1342308352
Control17=IDC_STATIC,static,1342308352
Control18=IDC_CHECK1,button,1342242819
Control19=IDC_EDIT1,edit,1350631556
Control20=IDC_EDIT2,edit,1350633604
Control21=IDC_STATIC,static,1342308352
Control22=IDC_STATIC,static,1342308352

[CLS:count]
Type=0
HeaderFile=count.h
ImplementationFile=count.cpp
BaseClass=CEdit
Filter=W

