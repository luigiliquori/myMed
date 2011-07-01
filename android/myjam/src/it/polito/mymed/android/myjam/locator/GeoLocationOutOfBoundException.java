package it.polito.mymed.android.myjam.locator;

public class GeoLocationOutOfBoundException extends Exception{

	private static final long serialVersionUID = 151036190872073038L;
	
	/**
	 * Thrown when the location of the user is out from the area defined in GeoLocation class.
	 * @param message
	 */
	public GeoLocationOutOfBoundException(String message){
		super(message);
	}

}
