package com.mymed.controller.core.requesthandler;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.nio.BufferOverflowException;
import java.nio.ByteBuffer;
import java.nio.charset.Charset;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.google.gson.JsonSyntaxException;
import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.myjam.MyJamManager;
import com.mymed.controller.core.manager.storage.MyJamStorageManager;
import com.mymed.myjam.type.MyJamTypeValidator;
import com.mymed.myjam.type.MReportBean;
import com.mymed.myjam.type.MSearchReportBean;
import com.mymed.myjam.type.MFeedBackBean;
import com.mymed.myjam.type.MyJamId;
import com.mymed.myjam.type.WrongFormatException;
/**
 * 
 * @author iacopo
 *
 */
@WebServlet("/MyJamHandler")
public class MyJamRequestHandler extends AbstractRequestHandler implements IMyJamCallAttributes {
	private static final long serialVersionUID = 1L;
	/**StorageManager*/
	MyJamManager myJamManager;
	/** Request code Map*/ 
	protected Map<String, MyJamRequestCode> reqCodeMap = new HashMap<String, MyJamRequestCode>();

	public void destroy() {} // do nothing

	/**
	 * @throws ServletException 
	 * @see HttpServlet#HttpServlet()
	 */
	public MyJamRequestHandler() throws ServletException {
		super();
		try {
			myJamManager = new MyJamManager(new MyJamStorageManager());
		} catch (InternalBackEndException e) {
			throw new ServletException("DHTManager is not accessible because: " + e.getMessage());
		}
		for(MyJamRequestCode r : MyJamRequestCode.values()){
			reqCodeMap.put(r.code, r);
		}
	}

	/**
	 * @throws IOException 
	 * @throws IOBackEndException 
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		MyJamId reportId;
		String resToJson;
		try {			
			Map<String,String> params = getParameters(request);
			MyJamRequestCode code = reqCodeMap.get(params.get("code"));
			if (code==null)
				throw new InternalBackEndException("null code");
			switch (code){
			case SEARCH_REPORTS:
				// Latitude and longitude in micro-degrees
				int latitude = Integer.parseInt(params.get(LATITUDE));
				int longitude = Integer.parseInt(params.get(LONGITUDE));
				// Diameter in m.
				int radius = Integer.parseInt(params.get(RADIUS));
				List<MSearchReportBean> resultList = myJamManager.searchReports((double) (latitude/1E6), (double) (longitude/1E6), radius);
				resToJson = this.getGson().toJson(resultList);
				setResponseText(resToJson);
				break;
			case GET_REPORT:
				reportId = MyJamId.parseString(params.get(ID));	//This conversion is done only to check the syntax of the id.
				MReportBean reportInfo = myJamManager.getReport(reportId.toString());
				resToJson = this.getGson().toJson(reportInfo);
				setResponseText(resToJson);
				break;
			case GET_NUMBER_UPDATES:
				reportId = MyJamId.parseString(params.get(ID));
				int updateIdList = myJamManager.getNumUpdates(reportId.toString());
				resToJson = this.getGson().toJson(updateIdList);
				setResponseText(resToJson);
				break;
			case GET_UPDATES:
				reportId = MyJamId.parseString(params.get(ID));
				int numUpdates = Integer.parseInt(params.get(NUM));
				List<MReportBean> updates = myJamManager.getUpdates(reportId.toString(),numUpdates);
				resToJson = this.getGson().toJson(updates);
				setResponseText(resToJson);
				break;
			case GET_FEEDBACKS:
				reportId = MyJamId.parseString(params.get(ID));
				List<MFeedBackBean> feedbacksList = myJamManager.getFeedbacks(reportId.toString());
				resToJson = this.getGson().toJson(feedbacksList);
				setResponseText(resToJson);
				break;
			case GET_ACTIVE_REPORTS:
				String userId = params.get(USER_ID);
				List<String> activeRepId = myJamManager.getActiveReport(userId);
				resToJson = this.getGson().toJson(activeRepId);
				setResponseText(resToJson);
				break;
			default:
				throw new InternalBackEndException("Wrong code");
			}
			super.doGet(request, response);
		} catch (InternalBackEndException e) {
			// TODO Check
			handleError(e, response);
		} catch (NullPointerException e) {
			handleError(new InternalBackEndException("Missing parameter: "+e.getMessage()==null?"":e.getMessage()), response);
		}catch (NumberFormatException e){
			handleError(new InternalBackEndException("Wrong parameter: "+e.getMessage()==null?"":e.getMessage()), response);
		} catch (WrongFormatException e){
			handleError(new InternalBackEndException("Wrong search type: "+e.getMessage()==null?"":e.getMessage()), response);
		} catch (IOBackEndException e) {
			handleError(e, response);
		}
	}

	/**
	 * @see HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		MyJamId reportId;
		String content;
		String resToJson;
		MReportBean res;
		try {
			Map<String,String> params = getParameters(request);
			MyJamRequestCode code = reqCodeMap.get(params.get("code"));
			switch (code){
			case INSERT_REPORT:
				int latitude = Integer.parseInt(params.get(LATITUDE));
				int longitude = Integer.parseInt(params.get(LONGITUDE));
				content = convertStreamToString(request.getInputStream(),request.getContentLength());
				MReportBean report = this.getGson().fromJson(content, MReportBean.class);
				MyJamTypeValidator.validate(report);
				res = myJamManager.insertReport(report,latitude,longitude);
				resToJson = this.getGson().toJson(res);
				setResponseText(resToJson);
				break;
			case INSERT_UPDATE:
				reportId = MyJamId.parseString(params.get(ID));
				content = convertStreamToString(request.getInputStream(),request.getContentLength());
				MReportBean update = this.getGson().fromJson(content, MReportBean.class);
				MyJamTypeValidator.validate(update);
				res = myJamManager.insertUpdate(reportId.toString(), update);
				resToJson = this.getGson().toJson(res);
				setResponseText(resToJson);
				break;
			case INSERT_FEEDBACK:
				reportId = MyJamId.parseString(params.get(ID));
				String updateId = params.get(UPDATE_ID);
				if (updateId!=null)
					MyJamId.parseString(updateId);
				content = convertStreamToString(request.getInputStream(),request.getContentLength());
				MFeedBackBean feedback =  this.getGson().fromJson(content, MFeedBackBean.class);
				MyJamTypeValidator.validate(feedback);
				myJamManager.insertFeedback(reportId.toString(),updateId, feedback);
				break;
			}
			super.doPost(request, response);
		} catch (InternalBackEndException e) {
			// TODO Check
			handleError(e, response);
		} catch (IOBackEndException e) {
			handleError(e, response);
		}catch (NullPointerException e) {
			handleError(new InternalBackEndException("Missing parameter: "+e.getMessage()==null?"":e.getMessage()), response);
		} catch (NumberFormatException e){
			handleError(new InternalBackEndException("Wrong parameter: "+e.getMessage()==null?"":e.getMessage()), response);
		} catch (WrongFormatException e){
			handleError(new InternalBackEndException("Wrong format: "+e.getMessage()==null?"":e.getMessage()), response);
		} catch (JsonSyntaxException e){
			handleError(new InternalBackEndException("Error parsing content: "+e.getMessage()==null?"":e.getMessage()), response);
		}
	}

	protected void doDelete(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		MyJamId reportId;
		
		try{
			Map<String,String> params = getParameters(request);
			MyJamRequestCode code = reqCodeMap.get(params.get("code"));
			switch (code){
			case DELETE_REPORT:
				reportId = MyJamId.parseString(params.get("id"));
				myJamManager.deleteReport(reportId);
				break;
			}
			super.doDelete(request, response);
		} catch (InternalBackEndException e) {
			// TODO Check
			handleError(e, response);
		}catch (NullPointerException e) {
			handleError(new InternalBackEndException("Missing parameter: "+e.getMessage()==null?"":e.getMessage()), response);
		} catch (NumberFormatException e){
			handleError(new InternalBackEndException("Wrong parameter: "+e.getMessage()==null?"":e.getMessage()), response);
		} catch (JsonSyntaxException e){
			handleError(new InternalBackEndException("Error parsing content: "+e.getMessage()==null?"":e.getMessage()), response);
		} catch (WrongFormatException e) {
			handleError(new InternalBackEndException("Wrong report id: "+e.getMessage()==null?"":e.getMessage()), response);
		}
	}

/** Request Code*/
protected enum MyJamRequestCode { 
	SEARCH_REPORTS ("0"),
	GET_REPORT ("1"), 	
	GET_NUMBER_UPDATES ("2"),
	GET_UPDATES ("3"),
	GET_FEEDBACKS("4"),
	GET_ACTIVE_REPORTS("5"),	
	GET_USER_REPORT_UPDATE("6"),	//TODO To implement
	INSERT_REPORT ("7"),
	INSERT_UPDATE ("8"),
	INSERT_FEEDBACK ("9"),
	DELETE_REPORT("10");
	//TODO Eventually add DELETE_REPORT
	public final String code;

	MyJamRequestCode(String code){
		this.code = code;
	}
}

/**
 * Given an InputStream reads the bytes as UTF8 chars and return a 
 * String.
 * @param is Input stream.
 * @param length Length of the stream in bytes.
 * @return The string
 * @throws InternalBackEndException Format is not correct or the length less then the real wrong.
 */
private static String convertStreamToString(InputStream is,int length) throws InternalBackEndException {
	try {
		if (length>0){
			ByteBuffer byteBuff = ByteBuffer.allocate(length);
			int currByte;
			while ((currByte=is.read()) != -1) {
				byteBuff.put((byte) currByte);
			}
			byteBuff.compact();
			return com.mymed.utils.MConverter.byteBufferToString(byteBuff);
		}else{
			BufferedReader buffRead = new BufferedReader(new InputStreamReader(is,Charset.forName("UTF-8")));
			StringBuilder sb = new StringBuilder();
			String line;
			while ((line = buffRead.readLine()) != null) {
				sb.append(line + "\n");
			}
			return sb.toString();
		}
	} catch (IOException e) {
		throw new InternalBackEndException("Wrong content");
	} catch (BufferOverflowException e){
		throw new InternalBackEndException("Wrong length");
	}finally {
		try {
			is.close();             
		} catch (IOException e) {
			e.printStackTrace();
		}
	}
}

}
