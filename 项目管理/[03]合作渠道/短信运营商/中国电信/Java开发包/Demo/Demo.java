package com.shouyi.SMGP_API.Demo;

import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.util.ArrayList;

import javax.swing.JButton;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPasswordField;
import javax.swing.JScrollPane;
import javax.swing.JTextArea;
import javax.swing.JTextField;

import javax.swing.WindowConstants;
import javax.swing.border.LineBorder;
import javax.swing.SwingUtilities;

import com.shouyi.SMGP_API.Report;
import com.shouyi.SMGP_API.Sms;
import com.shouyi.SMGP_API.SmsClient;

/**
 * This code was edited or generated using CloudGarden's Jigloo SWT/Swing GUI
 * Builder, which is free for non-commercial use. If Jigloo is being used
 * commercially (ie, by a corporation, company or business for any purpose
 * whatever) then you should purchase a license for each developer using Jigloo.
 * Please visit www.cloudgarden.com for details. Use of Jigloo implies
 * acceptance of these licensing terms. A COMMERCIAL LICENSE HAS NOT BEEN
 * PURCHASED FOR THIS MACHINE, SO JIGLOO OR THIS CODE CANNOT BE USED LEGALLY FOR
 * ANY CORPORATE OR COMMERCIAL PURPOSE.
 */
public class Demo extends javax.swing.JFrame {
	/**
	 * 
	 */
	private static final long serialVersionUID = 1L;
	private JLabel jLabel1;
	private JLabel jLabel2;
	private JLabel jLabel3;
	private JLabel jLabel4;
	private JLabel jLabel5;
	private JScrollPane jScrollPane2;
	private JScrollPane jScrollPane1;
	private JPasswordField jPasswordField1;
	private JTextArea txtMsg;
	private JButton btnGetReport;
	private JButton btnGetSms;
	private JButton btnExit;
	private JButton btnSendLong;
	private JButton btnSend;
	private JLabel jLabel7;
	private JTextField txtDestNo;
	private JLabel jLabel6;
	private JTextField txtSrcNo;
	private JTextArea txtContent;
	private JTextField txtUsername;
	private JTextField txtPort;
	private JTextField txtIP;
	private JButton btnLogin;
	private static SmsClient client;

	/**
	 * Auto-generated main method to display this JFrame
	 */
	public static void main(String[] args) {
		SwingUtilities.invokeLater(new Runnable() {
			public void run() {
				Demo inst = new Demo();
				client = new SmsClient();
				inst.setLocationRelativeTo(null);
				inst.setSize(470, 345);
				inst.setVisible(true);
				inst.addWindowListener(new WindowAdapter() {
					public void windowClosing(WindowEvent e) {
						if (client.getIsLogined()) {
							client.Disconnect();
						}
						System.exit(1);
					}
				});
			}
		});
	}

	public Demo() {
		super();
		initGUI();
	}

	private void initGUI() {
		try {
			setDefaultCloseOperation(WindowConstants.DISPOSE_ON_CLOSE);
			getContentPane().setLayout(null);
			this.setTitle("SMGP_JAVA_DEMO");
			{
				jLabel1 = new JLabel();
				getContentPane().add(jLabel1);
				jLabel1.setText("IP:");
				jLabel1.setBounds(12, 22, 58, 15);
			}
			{
				jLabel2 = new JLabel();
				getContentPane().add(jLabel2);
				jLabel2.setText("Port:");
				jLabel2.setBounds(12, 49, 58, 15);
			}
			{
				jLabel3 = new JLabel();
				getContentPane().add(jLabel3);
				jLabel3.setText("UserName:");
				jLabel3.setBounds(12, 76, 70, 15);
			}
			{
				jLabel4 = new JLabel();
				getContentPane().add(jLabel4);
				jLabel4.setText("Password:");
				jLabel4.setBounds(12, 103, 70, 15);
			}
			{
				btnLogin = new JButton();
				getContentPane().add(btnLogin);
				btnLogin.setText("Login");
				btnLogin.setBounds(66, 134, 85, 22);
				btnLogin.addActionListener(new btnLoginHandler());
			}
			{
				txtIP = new JTextField();
				getContentPane().add(txtIP);
				txtIP.setBounds(82, 19, 108, 21);
				txtIP.setText("125.88.123.137");
				txtIP.setBorder(new LineBorder(new java.awt.Color(0, 0, 0), 1,
						false));
			}
			{
				txtPort = new JTextField();
				getContentPane().add(txtPort);
				txtPort.setBounds(82, 46, 108, 22);
				txtPort.setText("3058");
				txtPort.setBorder(new LineBorder(new java.awt.Color(0, 0, 0),
						1, false));
			}
			{
				txtUsername = new JTextField();
				getContentPane().add(txtUsername);
				txtUsername.setBounds(82, 73, 108, 22);
				txtUsername.setBorder(new LineBorder(
						new java.awt.Color(0, 0, 0), 1, false));
			}
			{
				jLabel5 = new JLabel();
				getContentPane().add(jLabel5);
				jLabel5.setText("Src No:");
				jLabel5.setBounds(12, 173, 42, 15);
			}
			{
				txtSrcNo = new JTextField();
				getContentPane().add(txtSrcNo);
				txtSrcNo.setBounds(66, 170, 129, 22);
				txtSrcNo.setText("10659");
				txtSrcNo.setBorder(new LineBorder(new java.awt.Color(0, 0, 0),
						1, false));
			}
			{
				jLabel6 = new JLabel();
				getContentPane().add(jLabel6);
				jLabel6.setText("Dest No:");
				jLabel6.setBounds(243, 173, 61, 15);
			}
			{
				txtDestNo = new JTextField();
				getContentPane().add(txtDestNo);
				txtDestNo.setBounds(302, 170, 129, 22);
				txtDestNo.setBorder(new LineBorder(new java.awt.Color(0, 0, 0),
						1, false));
			}
			{
				jLabel7 = new JLabel();
				getContentPane().add(jLabel7);
				jLabel7.setText("Content:");
				jLabel7.setBounds(12, 221, 54, 15);
			}
			{
				btnSend = new JButton();
				getContentPane().add(btnSend);
				btnSend.setText("Send");
				btnSend.setBounds(6, 275, 80, 22);
				btnSend.addActionListener(new btnSendHandler());
			}
			{
				btnSendLong = new JButton();
				getContentPane().add(btnSendLong);
				btnSendLong.setText("SendLong");
				btnSendLong.setBounds(91, 275, 92, 22);
				btnSendLong.addActionListener(new btnSendLongHandler());
			}
			{
				btnExit = new JButton();
				getContentPane().add(btnExit);
				btnExit.setText("Exit");
				btnExit.setBounds(370, 275, 80, 22);
				btnExit.addActionListener(new btnExitHandler());
			}
			{
				btnGetSms = new JButton();
				getContentPane().add(btnGetSms);
				btnGetSms.setText("GetSms");
				btnGetSms.setBounds(188, 275, 80, 22);
				btnGetSms.addActionListener(new btnGetSmsHandler());
			}
			{
				btnGetReport = new JButton();
				getContentPane().add(btnGetReport);
				btnGetReport.setText("GetReport");
				btnGetReport.setBounds(273, 275, 92, 22);
				btnGetReport.addActionListener(new btnGetReportHandler());
			}
			{
				jPasswordField1 = new JPasswordField();
				getContentPane().add(jPasswordField1);
				jPasswordField1.setBounds(82, 101, 107, 22);
				jPasswordField1.setName("txtPwd");
				jPasswordField1.setBorder(new LineBorder(new java.awt.Color(0,
						0, 0), 1, false));
			}
			{
				jScrollPane1 = new JScrollPane();
				getContentPane().add(jScrollPane1);
				jScrollPane1.setBounds(202, 19, 248, 137);
				{
					txtMsg = new JTextArea();
					jScrollPane1.setViewportView(txtMsg);
					txtMsg.setBounds(66, 331, 250, 137);
					txtMsg.setEditable(false);
				}
			}
			{
				jScrollPane2 = new JScrollPane();
				getContentPane().add(jScrollPane2);
				jScrollPane2.setBounds(66, 198, 384, 65);
				{
					txtContent = new JTextArea();
					txtContent.setText("JAVA API发出的测试短信！");
					jScrollPane2.setViewportView(txtContent);
					txtContent.setBounds(45, 323, 386, 63);
				}
			}
			pack();
		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	private class btnLoginHandler implements ActionListener {
		@SuppressWarnings("deprecation")
		public void actionPerformed(ActionEvent e) {
			if (btnLogin.getText() == "Login") {
				String msg = "";
				if (txtIP.getText() == null||txtIP.getText().equals("")) {
					msg = "IP can not be left blank!";
				}
				if (txtPort.getText() == null||txtPort.getText().equals("")) {
					msg = "Port can not be left blank!";
				}
				if (txtUsername.getText() == null||txtUsername.getText().equals("")) {
					msg = "Username can not be left blank!";
				}
				if (!msg.equals("")) {
					JOptionPane.showMessageDialog(null, msg);
					return;
				}
				int loginResult = client.Login(txtIP.getText(), Integer
						.parseInt(txtPort.getText()), txtUsername.getText(),
						jPasswordField1.getText(), 30);

				if (loginResult == 0) {
					msg = "Login Succeed!";
					btnLogin.setText("Loginout");
				} else {
					msg = "Login Fail!";
				}
				JOptionPane.showMessageDialog(null, msg);
			} else if (btnLogin.getText() == "Loginout") {
				client.Disconnect();
				btnLogin.setText("Login");
			}
		}
	}

	private class btnSendHandler implements ActionListener {
		public void actionPerformed(ActionEvent e) {
			String msg = "";
			if (txtSrcNo.getText() == null||txtSrcNo.getText().equals("")) {
				JOptionPane.showMessageDialog(null,
						"Src No can not be left blank!");
				return;
			}
			if (txtDestNo.getText() ==null||txtDestNo.getText().equals("")) {
				JOptionPane.showMessageDialog(null,
						"Dest No can not be left blank!");
				return;
			}
			if (txtDestNo.getText() ==null|| txtDestNo.getText().equals("")) {
				JOptionPane.showMessageDialog(null,
						"Content can not be left blank!");
				return;
			}

			StringBuilder msgID = new StringBuilder();
			int[] sendResult = client.SendSms(txtSrcNo.getText(), txtDestNo
					.getText(), txtContent.getText(), true, msgID);

			if (!msgID.toString().equals("")) {
				txtMsg.append("msgID:" + msgID.toString() + "\r\n");
			}

			for (int i = 0; i < sendResult.length; i++) {
				if (sendResult[i] == 0) {
					msg = "SMS" + i + ":Succeed!";
				} else {
					msg = "SMS" + i + ":Fail!Return Error Code:"
							+ sendResult[i];
				}
				JOptionPane.showMessageDialog(null, msg);
			}
		}
	}

	private class btnSendLongHandler implements ActionListener {
		public void actionPerformed(ActionEvent e) {
			if ( txtSrcNo.getText()==null||txtSrcNo.getText().equals("")) {
				JOptionPane.showMessageDialog(null,
						"Src No can not be left blank!");
				return;
			}
			if (txtDestNo.getText()==null||txtDestNo.getText().equals("")) {
				JOptionPane.showMessageDialog(null,
						"Dest No can not be left blank!");
				return;
			}
			if (txtDestNo.getText()==null||txtDestNo.getText().equals("")) {
				JOptionPane.showMessageDialog(null,
						"Content can not be left blank!");
				return;
			}

			StringBuilder msgID = new StringBuilder();
			int[] sendResult = client.SendLongSms(txtSrcNo.getText(), txtDestNo
					.getText(), txtContent.getText(), true, msgID);

			if (!msgID.toString().equals("")) {
				txtMsg.append("msgID:" + msgID.toString() + "\r\n");
			}
			int errorCount = 0;

			for (int i = 0; i < sendResult.length; i++) {
				if (sendResult[i] != 0) {
					errorCount++;
					JOptionPane.showMessageDialog(null, "SMS" + i
							+ ":Fail!Return Error Code:" + sendResult[i]);
				}
			}
			if (errorCount == 0) {
				JOptionPane.showMessageDialog(null, "Send Succeed!");
			}
		}
	}

	private class btnGetSmsHandler implements ActionListener {
		public void actionPerformed(ActionEvent e) {
			ArrayList<Sms> smsDeliverList = new ArrayList<Sms>();
			client.RecvSms(smsDeliverList);

			if (!smsDeliverList.isEmpty()) {
				for (Sms de : smsDeliverList) {
					txtMsg.append("=======Got SMS Deliver========"
							+ "\r\nFrom:" + de.getSrcTermID().trim()
							+ "\r\nTo:" + de.getDestTermID().trim()
							+ "\r\nContent:" + de.getMsgContent().trim()
							+ "\r\nrecvTime:" + de.getRecvTime().trim()
							+ "\r\n===========End==========\r\n");
				}
			} else {
				JOptionPane.showMessageDialog(null, "Deliver List is empty!");
			}
		}
	}

	private class btnGetReportHandler implements ActionListener {
		public void actionPerformed(ActionEvent e) {
			ArrayList<Report> smsReportList = new ArrayList<Report>();
			client.RecvSmsReport(smsReportList);
			if (!smsReportList.isEmpty()) {
				for (Report re : smsReportList) {
					txtMsg.append("=======Got Reports========" + "\r\nFrom:"
							+ re.getSrcTermID().trim() + "\r\nTo:"
							+ re.getSrcTermID().trim() + "\r\nmsgID:"
							+ re.getMsgID().trim() + "\r\nsubmit date:"
							+ re.getSubmit_date().trim() + "\r\ndone date:"
							+ re.getDone_date().trim() + "\r\ntxt:"
							+ re.getTxt().trim()+ "\r\nStat:"
							+ re.getStat()
							+ "\r\n==========End==========\r\n");
				}
			} else {
				JOptionPane.showMessageDialog(null, "Report List is empty!");
			}
		}
	}

	private class btnExitHandler implements ActionListener {
		public void actionPerformed(ActionEvent e) {
			if (client.getIsLogined()) {
				client.Disconnect();
			}

			System.exit(1);
		}
	}

}
