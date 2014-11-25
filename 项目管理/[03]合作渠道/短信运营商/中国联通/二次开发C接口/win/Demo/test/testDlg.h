// testDlg.h : header file
//

#if !defined(AFX_TESTDLG_H__D6DA6ABB_7231_11D5_B089_0050BA7056BA__INCLUDED_)
#define AFX_TESTDLG_H__D6DA6ABB_7231_11D5_B089_0050BA7056BA__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

/////////////////////////////////////////////////////////////////////////////
// CTestDlg dialog

class CTestDlg : public CDialog
{
// Construction
public:
	CTestDlg(CWnd* pParent = NULL);	// standard constructor

// Dialog Data
	//{{AFX_DATA(CTestDlg)
	enum { IDD = IDD_TEST_DIALOG };
	int		m_count;
	int		m_second;
	CString	m_ipaddr;
	CString	m_mobile;
	CString	m_sppass;
	CString	m_spuser;
	UINT	m_nPort;
	BOOL	m_bShowMessageBox;
	CString	m_sendmsg;
	CString	m_recvmsg;
	//}}AFX_DATA

	// ClassWizard generated virtual function overrides
	//{{AFX_VIRTUAL(CTestDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV support
	//}}AFX_VIRTUAL

// Implementation
protected:
	HICON m_hIcon;

	// Generated message map functions
	//{{AFX_MSG(CTestDlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnSysCommand(UINT nID, LPARAM lParam);
	afx_msg void OnPaint();
	afx_msg HCURSOR OnQueryDragIcon();
	afx_msg void OnButton1();
	afx_msg void OnButton2();
	afx_msg void OnChangeCount();
	afx_msg void OnChangeSendTime();
	virtual void OnCancel();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ will insert additional declarations immediately before the previous line.

#endif // !defined(AFX_TESTDLG_H__D6DA6ABB_7231_11D5_B089_0050BA7056BA__INCLUDED_)
