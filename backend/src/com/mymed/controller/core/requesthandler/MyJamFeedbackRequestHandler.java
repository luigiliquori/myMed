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
import com.mymed.model.data.myjam.MFeedBackBean;
import com.mymed.model.data.myjam.MyJamTypeValidator;
import com.mymed.utils.IMyJamCallAttributes;
import com.mymed.utils.MConverter;

/**
 * Manages the requests related to updates.
 * 
 * @author iacopo
 * 
 */
@WebServlet("/MyJamFeedbackRequestHandler")
public class MyJamFeedbackRequestHandler  extends AbstractRequestHandler  implements IMyJamCallAttributes {
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
	public MyJamFeedbackRequestHandler() throws ServletException {
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
			String id;

			// accessToken
			if (parameters.get("accessToken") == null) {
				throw new InternalBackEndException("accessToken argument is missing!");
			} else {
				checkToken(parameters); // Security Validation
			}
			
			switch (code) {
			case READ : // GET
				message.setMethod("READ");
				if ((id = parameters.get(ID)) != null){
					MyMedId reportOfUpdateId = MyMedId.parseString(id);
					List<MFeedBackBean> feedbacksList = myJamManager.getFeedbacks(reportOfUpdateId.toString());
					message.addData("feedbacks", this.getGson().toJson(feedbacksList));
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
			String id, content;
			
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
					MyMedId.parseString(id);
					String updateId = parameters.get(UPDATE_ID); //Optional parameter, present only if the feedback refers to the an update.
					if (updateId!=null)
						MyMedId.parseString(updateId);
					content = MConverter.convertStreamToString(request.getInputStream(),request.getContentLength());
					MFeedBackBean feedback =  this.getGson().fromJson(content, MFeedBackBean.class);
					MyJamTypeValidator.validate(feedback);
					MFeedBackBean res = myJamManager.insertFeedback(id,updateId, feedback);
					message.addData("feedback", this.getGson().toJson(res));
				}else
					throw new InternalBackEndException("missing parameter, bad request!");
				break;
			default :
				throw new InternalBackEndException(this.getClass().getName()+"(" + code + ") not exist!");
			}

		} catch (final AbstractMymedException e) {
			e.printStackTrace();
			LOGGER.info("Error in doPost operation");
			LOGGER.debug("Error in doPost operation", e);
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
			LOGGER.info("Error in doDelete operation");
			LOGGER.debug("Error in doDelete operation", e);
			message.setStatus(e.getStatus());
			message.setDescription(e.getMessage());		
		}
	}
}
