/*
 * Copyright 2012 INRIA
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

package com.mymed.utils.mail;

import java.io.PrintWriter;
import java.io.StringWriter;
import java.util.ArrayList;

import ch.qos.logback.classic.Logger;

import com.mymed.utils.MLogger;

/** A mail queue with a separate thread that enables to send mails asynchronously */
public class MailQueue extends Thread {

    // ---------------------------------------------------------------------------
    // Constants
    // ---------------------------------------------------------------------------   
    static private final Logger LOGGER = MLogger.getLogger();
    
    // ---------------------------------------------------------------------------
    // Singleton
    // ---------------------------------------------------------------------------
    static private MailQueue instance = new MailQueue();   
    static public MailQueue getInstance() {
        return instance;
    }
    
    // ---------------------------------------------------------------------------
    // Attributes
    // ---------------------------------------------------------------------------
    private ArrayList<Mail> queue = new ArrayList<Mail>();
    
    // ---------------------------------------------------------------------------
    // Constructor
    // ---------------------------------------------------------------------------
    
    /** Run thread on startup */
    public MailQueue() {
        this.start();
    }
    
    // ---------------------------------------------------------------------------
    // Main thread routine
    // ---------------------------------------------------------------------------
    
    @Override public void run() {
        
        // Infinite loop  
        while(true) {
            
            try {
                
                LOGGER.info("Waiting for mail");
                
                Mail mail = null;
      
                // Pop the last mail
                synchronized (this.queue) {
                    while (this.queue.size() == 0) {
                        this.queue.wait();
                    } 
                    LOGGER.info("New mail !");
                    
                    mail = this.queue.remove(0);
                }

                // Send it the mail
                mail.sendSync();
                
            } catch(Throwable e) { // Print stack and continue   
                StringWriter sw = new StringWriter();
                PrintWriter pw = new PrintWriter(sw);
                e.printStackTrace(pw);
                LOGGER.error("Error in mail sender. Continuing. " + pw);
            }
        }
    } 
    
    /** Add mail to the queue */
    public void enqeueMail(Mail mail) {
        synchronized(this.queue) {
            queue.add(mail);
            queue.notify();
        }
    }
}
