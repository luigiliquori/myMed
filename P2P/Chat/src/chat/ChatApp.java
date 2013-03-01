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
/*
 * ChatApp.java
 */
package chat;

import org.jdesktop.application.Application;
import org.jdesktop.application.SingleFrameApplication;
import mymed.*;

/**
 * The main class of the application.
 */
public class ChatApp extends SingleFrameApplication {

    String userName;
    int service;

    /**
     * At startup create and show the main frame of the application.
     */
    @Override
    protected void startup() {
	System.out.println("ChatApp startup");
	view = new ChatView(this);
	view.setIw(iw);
	view.setUserName(userName);
	view.setService(service);
	show(view);

	//show(new ChatView(this));
    }

    @Override
    protected void initialize(String[] args) {
	//JavaIceWrapper iw = new JavaIceWrapper();
	System.out.println("ChatApp initializing");
	if (args.length < 2) {
	    System.out.println("Usage: java JavaIceWrapper name service");
	    System.exit(-1);
	}
	userName = args[0];
	service = Integer.parseInt(args[1]);
	String rsIp = "130.192.9.113"; //"127.0.0.1";
	String rsPort = "44456";
	System.out.println("\nService is " + service);
	iw.init(rsIp, rsPort, userName, service);
//	JOptionPane.showConfirmDialog(null, "initting, args are" + args[0] + args[1]);
    }

    /**
     * This method is to initialize the specified window by injecting resources.
     * Windows shown in our application come fully initialized from the GUI
     * builder, so this additional configuration is not needed.
     */
    @Override
    protected void configureWindow(java.awt.Window root) {
    }

    /**
     * A convenient static getter for the application instance.
     * @return the instance of ChatApp
     */
    public static ChatApp getApplication() {
	return Application.getInstance(ChatApp.class);
    }
    ChatView view;
    JavaIceWrapper iw = new JavaIceWrapper();

    /**
     * Main method launching the application.
     */
    public static void main(String[] args) {
//	JavaIceWrapper iw = new JavaIceWrapper();
//	if (args.length < 2) {
//	    System.out.println("Usage: java JavaIceWrapper name service");
//	    return;
//	}
//	String userName = args[0];
//	int service = Integer.parseInt(args[1]);
//	String rsIp = "127.0.0.1";
//	String rsPort = "44456";
//	System.out.println("\nService is " + service);
	//iw.init(rsIp, rsPort, userName, service);
	launch(ChatApp.class, args);
    }
}
