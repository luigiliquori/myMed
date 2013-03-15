package mymed;

/**
 * This class loads the library which provided peer-to-peer connections for MyMed,
 * and furnishes a java intervace via JNI to this functionality.
 * @author Peter Neuss
 */
public class JavaIceWrapper {

    static {
	//System.out.println("loading library");
	try {
	    System.loadLibrary("mymed");
	} catch (Exception e) {
	    System.out.println(e.toString());
	}
    }

    public JavaIceWrapper() {
	//System.out.println("const");
	//initCppSide();
	//System.out.println("after const");
    }

    /// Initialize with Rendezvous server Ip and port, username, and mymed application number
    public native void init(String rsIp, String rsPort, String name, int service);

    /**
     * Establish connection with user for an application
     * @param name
     *   user to connect to
     * @param service
     *   MyMed application number to use
     */
    public native void getConnection(String name, int service);

    /**
     * Send a message to a user
     * @param name
     *   destination user
     * @param msg
     *   message to send
     */
    public native void send(String name, String msg);

    /**
     * Return the userstring received from Rendezvous server
     * @return
     */
    public native String getUsersString();

    /**
     * obsolete, do not use.
     * @return
     */
    public native String getInput();

    /**
     * Scan all MessageDispatchers, if any have input return the input.
     * @param from
     *   'out' parameter will be set to userName of user who sent the input.
     * @return
     *   the input
     */
    public native String getInput(StringBuffer from);  // from is for return value

    /**
     * See if there has already been a connection established to user
     * @param name
     *   other user
     * @param service
     *   MyMed application number
     * @return
     *   1 if there is a connection, 0 otherwise
     */
    public native int hasConnection(String name, int service);

//    public native void setIncomingMessageCallback(JavaIceWrapper javaIceWrapper);
//
//    public void setImCallback() {
//	setIncomingMessageCallback(this);
//    }

//    public void incomingMessageCallback(String from, String msg) {
//	System.out.println(from + ": " + msg);
//    }
}
