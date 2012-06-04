package com.mymed.controller.core.requesthandler;

import java.io.IOException;
import java.util.List;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.mymed.controller.core.exception.AbstractMymedException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.myjam.MyJamManager;
import com.mymed.controller.core.manager.storage.GeoLocStorageManager;
import com.mymed.controller.core.requesthandler.message.JsonMessage;
import com.mymed.model.data.id.MyMedId;
import com.mymed.model.data.myjam.MReportBean;

import com.mymed.model.data.myjam.MyJamTypeValidator;
import com.mymed.utils.IMyJamCallAttributes;
import com.mymed.utils.MConverter;

/**
 * Manages the requests related to updates.
 * @author iacopo
 *
 */
@WebServlet("/MyJamUpdateRequestHandler")
public class MyJamUpdateRequestHandler  extends AbstractRequestHandler implements IMyJamCallAttributes {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private static final long serialVersionUID = 1L;
	
	private MyJamManager myJamManager;
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * @throws ServletException
	 * @see HttpServlet#HttpServlet()
	 */
	public MyJamUpdateRequestHandler() throws ServletException {
		super();
		myJamManager = new MyJamManager(new GeoLocStorageManager());
	}

	/* --------------------------------------------------------- */
	/* extends HttpServlet */
	/* --------------------------------------------------------- */
	@Override
	protected void doGet(final HttpServletRequest request, final HttpServletResponse response) throws ServletException,
	IOException {

		JsonMessage message = new JsonMessage(200, this.getClass().getName());

		try {
			final Map<String, String> parameters = getParameters(request);
			final RequestCode code = REQUEST_CODE_MAP.get(parameters.get("code"));
			String id, start_time;
			
			// accessToken
			if (parameters.get("accessToken") == null) {
				throw new InternalBackEndException("accessToken argument is missing!");
			} else {
				checkToken(parameters); // Security Validation
			}

			switch (code) {
			case READ : // GET
				message.setMethod("READ");
				if ((id = parameters.get(ID)) != null && (start_time = parameters.get(START_TIME)) != null){
					long startTime = Long.parseLong(start_time);
					MyMedId reportId = MyMedId.parseString(id);
					List<MReportBean> updates = myJamManager.getUpdates(reportId.toString(), startTime);
					message.addData("updates", this.getGson().toJson(updates));
				}else
					throw new InternalBackEndException("missing parameter, bad request!");
				break;
			default :
				throw new InternalBackEndException(this.getClass().getName()+"(" + code + ") not exist!");
			}
		} catch (final AbstractMymedException e) {
			e.printStackTrace();
			LOGGER.info("Error in doGet");
			LOGGER.debug("Error in doGet", e);
			message.setStatus(e.getStatus());
			message.setDescription(e.getMessage());
		} 
		
		printJSonResponse(message, response);
	}

	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse
	 *      response)
	 */
	@Override
	protected void doPost(final HttpServletRequest request, final HttpServletResponse response)
			throws ServletException, IOException {
		
		JsonMessage message = new JsonMessage(200, this.getClass().getName());

		try {
			final Map<String, String> parameters = getParameters(request);
			final RequestCode code = REQUEST_CODE_MAP.get(parameters.get("code"));
			String content, id;
			
			// accessToken
			if (parameters.get("accessToken") == null) {
				throw new InternalBackEndException("accessToken argument is missing!");
			} else {
				checkToken(parameters); // Security Validation
			}

			switch (code) {
			case CREATE :
				message.setMethod("CREATE");
				if ((id = parameters.get(ID)) != null){
					MyMedId updateId = MyMedId.parseString(id);
					content = MConverter.convertStreamToString(request.getInputStream(),request.getContentLength());
					MReportBean update = this.getGson().fromJson(content, MReportBean.class);
					MyJamTypeValidator.validate(update);
					MReportBean res = myJamManager.insertUpdate(updateId.toString(), update);
					message.addData("update", this.getGson().toJson(res));
				}else
					throw new InternalBackEndException("missing parameter, bad request!");
				break;
			default :
				throw new InternalBackEndException(this.getClass().getName()+"(" + code + ") not exist!");
			}

		} catch (final AbstractMymedException e) {
			e.printStackTrace();
			LOGGER.info("Error in doPost");
			LOGGER.debug("Error in doPost", e);
			message.setStatus(e.getStatus());
			message.setDescription(e.getMessage());
		} 

		printJSonResponse(message, response);
	}
	
	/**
	 * @see HttpServlet#doDelete(HttpServletRequest request, HttpServletResponse
	 *      response)
	 */
	@Override
	protected void doDelete(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		
		JsonMessage message = new JsonMessage(200, this.getClass().getName());
		
		try{
			final Map<String, String> parameters = getParameters(request);
			final RequestCode code = REQUEST_CODE_MAP.get(parameters.get("code"));
			
			// accessToken
			if (parameters.get("accessToken") == null) {
				throw new InternalBackEndException("accessToken argument is missing!");
			} else {
				checkToken(parameters); // Security Validation
			}
			
			switch (code){
			case DELETE:
				message.setMethod("DELETE");
			}
			super.doDelete(request, response);
		} catch (final AbstractMymedException e) { 
			e.printStackTrace();
			LOGGER.info("Error in doDelete");
			LOGGER.debug("Error in doDelete", e);
			message.setStatus(e.getStatus());
			message.setDescription(e.getMessage());
		}
	}	
}