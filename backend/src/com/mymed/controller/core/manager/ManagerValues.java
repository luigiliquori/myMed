package com.mymed.controller.core.manager;


/**
 * Simple class to hold generic strings that can be used with all the managers.
 * We hold the names of the columns families and of the super columns.
 * <p>
 * It contains also the log facilities for all the managers.
 * 
 * @author Milo Casagrande
 * 
 */
public class ManagerValues {
	// Column families
	/**
	 * The 'Application' column family
	 */
	protected static final String CF_APPLICATION = "Application";

	/**
	 * The 'ApplicationView' column family
	 */
	protected static final String CF_APPLICATION_VIEW = "ApplicationView";

	/**
	 * The 'Authentication' column family
	 */
	protected static final String CF_AUTHENTICATION = "Authentication";

	/**
	 * The 'Interaction' column family
	 */
	protected static final String CF_INTERACTION = "Interaction";

	/**
	 * The 'Ontology' column family
	 */
	protected static final String CF_ONTOLOGY = "Ontology";

	/**
	 * The 'Reputation' column family
	 */
	protected static final String CF_REPUTATION = "Reputation";

	/**
	 * The 'Session' column family
	 */
	protected static final String CF_SESSION = "Session";

	/**
	 * The 'User' column family
	 */
	protected static final String CF_USER = "User";

	// Super columns
	/**
	 * The 'ApplicationController' super column
	 */
	protected static final String SC_APPLICATION_CONTROLLER = "ApplicationController";

	/**
	 * The 'ApplicationList' super column
	 */
	protected static final String SC_APPLICATION_LIST = "ApplicationList";

	/**
	 * The 'ApplicationModel' super column
	 */
	protected static final String SC_APPLICATION_MODEL = "ApplicationModel";
	
	/**
	 * The 'OntologyList' super column
	 */
	protected static final String SC_ONTOLOGY_LIST = "OntologyList";

	/**
	 * The 'DataList' super column
	 */
	protected static final String SC_DATA_LIST = "DataList";
	
	/**
	 * The 'UserList' super column
	 */
	protected static final String SC_USER_LIST = "UserList";

	/**
	 * The 'InteractionList' super column
	 */
	protected static final String SC_INTERACTION_LIST = "InteractionList";

	/**
	 * The 'RaterList' super column
	 */
	protected static final String SC_RATER_LIST = "RaterList";
}
