package com.mymed.controller.core.requesthandler;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.nio.BufferOverflowException;
import java.nio.ByteBuffer;
import java.nio.charset.Charset;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.google.gson.JsonSyntaxException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.storage.MyJamStorageManager;
import com.mymed.myjam.type.IMyJamType;
import com.mymed.myjam.type.NewReport;
import com.mymed.myjam.type.WrongFormatException;
/**
 * 
 * @author iacopo
 *
 */
@WebServlet("/MyJamHandler")
public class MyJamRequestHandler extends AbstractRequestHandler {
	private static final long serialVersionUID = 1L;
	/**StorageManager*/
	MyJamStorageManager storageManager;
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
			storageManager=new MyJamStorageManager();
		} catch (InternalBackEndException e) {
			throw new ServletException("DHTManager is not accessible because: " + e.getMessage());
		}
    	for(MyJamRequestCode r : MyJamRequestCode.values()){
			reqCodeMap.put(r.code, r);
		}
    }

	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		try {
			
			Map<String,String> params = getParameters(request);
			MyJamRequestCode code = reqCodeMap.get(params.get("code"));
			if (code==null)
				throw new InternalBackEndException("null code");
			switch (code){
			case GET_REPORT:
				break;
			case GET_REPORTS:
				break;
			case GET_REPORT_INFO:
				break;
			default:
				throw new InternalBackEndException("Wrong code");
			}
		} catch (InternalBackEndException e) {
			// TODO Auto-generated catch block
			handleInternalError(e, response);
		}		
	}

	/**
	 * @see HttpServlet#doPost(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		try {
			Map<String,String> params = getParameters(request);
			MyJamRequestCode code = reqCodeMap.get(params.get("code"));
			switch (code){
			case INSERT_REPORT:
				String content;
				int length;
				if ((length=request.getContentLength())!=-1)
					content = convertStreamToString(request.getInputStream(),length);
				else
					content = convertStreamToString(request.getInputStream());
				NewReport report = this.getGson().fromJson(content, NewReport.class);
				validate(report);
				storageManager.insertReport(report);
				break;
			case INSERT_UPDATE:
				break;
			case INSERT_FEEDBACK:
				break;
			}
		} catch (InternalBackEndException e) {
			// TODO Auto-generated catch block
			handleInternalError(e, response);
		} catch (JsonSyntaxException e) {
			handleInternalError(new InternalBackEndException("Error parsing Json string."), response);
		}
	}
	
	protected void doDelete(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		
	}
	
	/** Request Code*/
	protected enum MyJamRequestCode { 
		GET_REPORT ("0"), 	
		GET_REPORTS ("1"),
		GET_REPORT_INFO ("2"),
		INSERT_REPORT ("3"),
		INSERT_UPDATE ("4"),
		INSERT_FEEDBACK ("5");
		//TODO Eventually add DELETE_REPORT and DELETE_UPDATE
		public final String code;

		MyJamRequestCode(String code){
			this.code = code;
		}
	}

	/**
	 * Given an InputStream of fixed length reads the bytes as UTF8 chars and return a 
	 * String.
	 * @param is Input stream.
	 * @param length Length of the stream in bytes.
	 * @return The string
	 * @throws InternalBackEndException Format is not correct or the length less then the real wrong.
	 */
	private static String convertStreamToString(InputStream is,int length) throws InternalBackEndException {
		ByteBuffer byteBuff = ByteBuffer.allocate(length);
		int currByte;
		try {
			while ((currByte=is.read()) != -1) {
				byteBuff.put((byte) currByte);
			}
			byteBuff.compact();
			return com.mymed.utils.MConverter.byteBufferToString(byteBuff);
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
	/**
	 * Given an InputStream of length reads the bytes as UTF8 chars and return a 
	 * String.
	 * @param is
	 * @param length
	 * @return
	 * @throws InternalBackEndException
	 */
	private static String convertStreamToString(InputStream is) throws InternalBackEndException {
		/*
		 * 
		 */
		BufferedReader buffRead = new BufferedReader(new InputStreamReader(is,Charset.forName("UTF-8")));
		StringBuilder sb = new StringBuilder();
		String line;
		try {
			while ((line = buffRead.readLine()) != null) {
				sb.append(line + "\n");
			}
			return sb.toString();
		} catch (IOException e) {
			throw new InternalBackEndException("Wrong content");
		} finally {
			try {
				is.close();             
			} catch (IOException e) {
				e.printStackTrace();
			}
		}
	}
	
	/**
	 * Validate a NewReport.
	 * @param arg0
	 * @throws InternalBackEndException
	 */
	private static void validate(IMyJamType arg0) throws InternalBackEndException {
		try{
			arg0.validate();
		}catch(WrongFormatException e){
			throw new InternalBackEndException("Wrong request: "+e.getMessage());
		}
	}
}
