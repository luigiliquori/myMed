/*
 * Copyright 2012 POLITO 
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
*/

package mymed;

public class JavaIceWrapper {
    static {
	//System.loadLibrary("boost_serialization");
	System.out.println("loading library");
	System.loadLibrary("mymed");
    }
    public JavaIceWrapper() {
	//System.out.println("const");
    	//initCppSide();
	//System.out.println("after const");
        }
    //public native void initCppSide();
    //public native void setName(String name);
    public native void init(String rsIp, String rsPort, String name, int service);
    public native void getConnection(String name, int service);
    public native void send(String name, String msg);
    public native String getUsersString();
    public native String getInput();
    public native int hasConnection(String name, int service);
    
    //private long iwPointer;
    /*
    public static void main(String[] args) {
	JavaIceWrapper iw = new JavaIceWrapper();

	if (args.length < 2) {
	    System.out.println("Usage: java JavaIceWrapper name service");
	    return;
	}
	String userName = args[0];
	int service = Integer.parseInt(args[1]);
	String rsIp = "127.0.0.1";
	String rsPort = "44456";
	System.out.println("\nService is " + service);
	iw.init(rsIp, rsPort, userName, service);

	boolean done = false;
	while (!done) {
	    String cmdLine = System.console().readLine();
	    String[]tokens = cmdLine.split(" ");
	    if (tokens.length > 0) {
		if (tokens[0].equalsIgnoreCase("q") || tokens[0].equalsIgnoreCase("quit")) {
		    done = true;
		} else {
		    processCommand(cmdLine, tokens, service, iw);
		}
	    }
	}
    }

    static void processCommand(String cmdLine, String[] tokens, int service, JavaIceWrapper iw) {
	if (tokens[0].equalsIgnoreCase("c") || tokens[0].equalsIgnoreCase("connect")) {
	    iw.getConnection(tokens[1],service);
	} else if (tokens[0].equalsIgnoreCase("s") || tokens[0].equalsIgnoreCase("send")) {
	    String restOfLine = cmdLine.substring(tokens[0].length() + 1);
	    iw.send(tokens[1], restOfLine);
	} else if (tokens[0].equalsIgnoreCase("u") || tokens[0].equalsIgnoreCase("users")) {

	}
    }
    */
}
