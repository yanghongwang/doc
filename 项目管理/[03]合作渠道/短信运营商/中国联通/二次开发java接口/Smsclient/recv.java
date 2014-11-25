import spApi.*;
import java.net.*;
import java.io.*;
public class recv {

  public static void main(String[] args) {
         recv atestprocesse=new recv();
  }

  public recv() {
         ServerSocket serversocket=null;
         Socket so=null;
         OutputStream output=null;
         InputStream input=null;
         SGIP_Command command=null;


         try {
             serversocket=new ServerSocket(8809);
             so=serversocket.accept();
             input=so.getInputStream();
             output=so.getOutputStream();
             command=new SGIP_Command();

             SGIP_Command tmpCMD=null;
             Deliver deliver=null;
             DeliverResp deliverresp = null;

             Report report=null;
             ReportResp reportresp=null;

             Userrpt userrpt = null;
             UserrptResp userrptresp = null;

             Bind active=null;
             Unbind term=null;
	           BindResp resp=null;
             UnbindResp Unresp=null;
             boolean loop=true;
         while(loop)
         {
              tmpCMD=command.read(input);//接收sgip消息
             switch (command.getCommandID())
             {
                    case SGIP_Command.ID_SGIP_BIND :
                    {
                         active=(Bind)tmpCMD;//强制转换

                         int result=active.readbody();//解包
                         System.out.println(tmpCMD.getTotalLength());
                         System.out.println(tmpCMD.getCommandID());
                         System.out.println(tmpCMD.getSeqno_1());
                         System.out.println(tmpCMD.getSeqno_2());
                         System.out.println(tmpCMD.getSeqno_3());
                         System.out.println(active.GetLoginType());
                         System.out.println(active.GetLoginName());
                         System.out.println(active.GetLoginPassword());

                         resp=new BindResp(tmpCMD.getMsgHead());
                         resp.SetResult(0);
                         resp.write(output);
                        break;
                    }
                    case SGIP_Command.ID_SGIP_UNBIND :
                    {
                         term=(Unbind)tmpCMD;//强制转换

                         //int result=term.readbody();
                         System.out.println(tmpCMD.getTotalLength());
                         System.out.println(tmpCMD.getCommandID());
                         System.out.println(tmpCMD.getSeqno_1());
                         System.out.println(tmpCMD.getSeqno_2());
                         System.out.println(tmpCMD.getSeqno_3());
                         System.out.println(term.GetFlag());
                         Unresp=new UnbindResp(tmpCMD.getMsgHead());//node id 3+AAAA+CP_id, AAAA是长途区号
                         Unresp.write(output);
                         loop=false;
                        break;
                    }
                    case SGIP_Command.ID_SGIP_DELIVER:
                    {
                         deliver=(Deliver)tmpCMD;//强制转换
                         deliver.readbody();//解包
                         System.out.println(deliver.getTotalLength());
                         System.out.println(deliver.getCommandID());
                         System.out.println(deliver.getSeqno_1());
                         System.out.println(deliver.getSeqno_2());
                         System.out.println(deliver.getSeqno_3());
                         System.out.println(deliver.getUserNumber());
                         System.out.println(deliver.getSPNumber());
                         System.out.println(deliver.getTP_pid());
                         System.out.println(deliver.getTP_udhi());
                         System.out.println(deliver.getMessageCoding());
                         System.out.println(deliver.getMessageLength());
                         System.out.println(deliver.getMessageContent());
                         deliverresp = new DeliverResp(tmpCMD.getMsgHead());//result
                         deliverresp.SetResult(0);
                         deliverresp.write(output);
                         break;
                    }
                    case SGIP_Command.ID_SGIP_REPORT:
                    {
                         report=(Report)tmpCMD;//强制转换
                         report.readbody();//解包

                         System.out.println(tmpCMD.getTotalLength());
                         System.out.println(tmpCMD.getCommandID());
                         System.out.println(tmpCMD.getSeqno_1());
                         System.out.println(tmpCMD.getSeqno_2());
                         System.out.println(tmpCMD.getSeqno_3());
                         System.out.println(report.getSeq_1());
                         System.out.println(report.getSeq_2());
                         System.out.println(report.getSeq_3());
                         System.out.println(report.getReportType());
                         System.out.println(report.getUserNumber());
                         System.out.println(report.getState());
                         System.out.println(report.getErrorCode());
                         reportresp = new ReportResp(tmpCMD.getMsgHead());//result
                         reportresp.SetResult(0);
                         reportresp.write(output);
                         break;
                    }
                    case SGIP_Command.ID_SGIP_USERRPT:
                    {
                         userrpt=(Userrpt)tmpCMD;//强制转换
                         userrpt.readbody(); //解包
                         System.out.println(tmpCMD.getTotalLength());
                         System.out.println(tmpCMD.getCommandID());
                         System.out.println(tmpCMD.getSeqno_1());
                         System.out.println(tmpCMD.getSeqno_2());
                         System.out.println(tmpCMD.getSeqno_3());
                         System.out.println(userrpt.getSPNumber());
                         System.out.println(userrpt.getUserNumber());
                         System.out.println(userrpt.getUserCondition());
                         userrptresp= new UserrptResp(tmpCMD.getMsgHead());
                         userrptresp.SetResult(12);
                         userrptresp.write(output);
                         break;
                    }
             }

             }
	     so.close();

         } catch (Exception e) {
                 System.out.println(e.toString());
         } finally {
                   try {
                       System.in.read();
                       //it just for debug
                   } catch (Exception s) {
                           System.out.println(s.toString());
                   }
         }

  }

}
