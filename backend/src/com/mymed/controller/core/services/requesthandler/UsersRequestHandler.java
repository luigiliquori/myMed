package com.mymed.controller.core.services.requesthandler;

import java.io.IOException;
import java.util.HashMap;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import com.mymed.model.datastructure.User;

/**
 * Servlet implementation class UsersRequestHandler
 */
public class UsersRequestHandler extends AbstractRequestHandler {
	/* --------------------------------------------------------- */
	/*                      Attributes                           */
	/* --------------------------------------------------------- */
	private static final long serialVersionUID = 1L;

	/** Request code Map*/ 
	private Map<String, RequestCode> requestCodeMap = new HashMap<String, RequestCode>();
	
	/** Request codes*/ 
	private enum RequestCode {
		// C.R.U.D Users
		CREATE ("0"), 	// SET AN USER PROFILE INTO THE BACKBONE
		READ ("1"), 	// GET AN USER PROFILE FROM THE BACKBONE
		UPDATE ("2"),
		DELETE ("3"),
		
		// MYMED AUTHENTICATION
		LOGIN ("4"),
		LOGOUT ("5");

		public final String code;
		
		RequestCode(String code){
			this.code = code;
		}
	}
    
	/* --------------------------------------------------------- */
	/*                      Constructors                         */
	/* --------------------------------------------------------- */
    /**
     * @see HttpServlet#HttpServlet()
     */
    public UsersRequestHandler() {
        super();
        // initialize the CodeMapping
		for(RequestCode r : RequestCode.values()){
			requestCodeMap.put(r.code, r);
		}
    }

	/* --------------------------------------------------------- */
	/*                      extends HttpServlet                  */
	/* --------------------------------------------------------- */
	/**
	 * @see HttpServlet#doGet(HttpServletRequest request, HttpServletResponse response)
	 */
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {

		/** Get the parameters */
		Map<String, String> parameters = getParameters(request);
		
		/** handle the request */
		if (parameters.containsKey("act")){
			RequestCode code = requestCodeMap.get(parameters.get("act"));
			
			switch(code){
			case CREATE : 
				User user = getGson().fromJson(parameters.get("user"), User.class);
				System.out.println("\nreceived:\n" + user);
				getServiceManager().setProfile(user);
				setResponse("profile enregistred");
				break;
			case READ : 
				String id = parameters.get("id");
				user = getServiceManager().getProfile(id);
				setResponse(getGson().toJson(user));
				break;
			case LOGIN : 
				String email = parameters.get("email"); // email == id for mymed users
				String password = parameters.get("password");
				user = getServiceManager().getProfile(email);
				String resp = user.getPassword().equals(password) ? getGson().toJson(user) : "false";
				setResponse(resp);
				break;
			default : break;
			}
		}
		
		super.doGet(request, response);
	}
}
