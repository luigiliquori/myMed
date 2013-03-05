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
 * ChatView.java
 */
package chat;

import java.util.logging.Level;
import java.util.logging.Logger;
import org.jdesktop.application.Action;
import org.jdesktop.application.ResourceMap;
import org.jdesktop.application.SingleFrameApplication;
import org.jdesktop.application.FrameView;
import org.jdesktop.application.TaskMonitor;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import javax.swing.DefaultListModel;
import javax.swing.Timer;
import javax.swing.Icon;
import javax.swing.JDialog;
import javax.swing.JFrame;

/**
 * The application's main frame.
 */
public class ChatView extends FrameView {

    public ChatView(SingleFrameApplication app) {
	super(app);

	initComponents();


	createUpdateTimer();
	createDisplayIncomingTimer();

	// status bar initialization - message timeout, idle icon and busy animation, etc
	ResourceMap resourceMap = getResourceMap();
	int messageTimeout = resourceMap.getInteger("StatusBar.messageTimeout");
	messageTimer = new Timer(messageTimeout, new ActionListener() {

	    public void actionPerformed(ActionEvent e) {
		statusMessageLabel.setText("");
	    }
	});
	messageTimer.setRepeats(true);
	//messageTimer.setDelay(messageTimeout);

	int busyAnimationRate = resourceMap.getInteger("StatusBar.busyAnimationRate");
	for (int i = 0; i < busyIcons.length; i++) {
	    busyIcons[i] = resourceMap.getIcon("StatusBar.busyIcons[" + i + "]");
	}
	busyIconTimer = new Timer(busyAnimationRate, new ActionListener() {

	    public void actionPerformed(ActionEvent e) {
		busyIconIndex = (busyIconIndex + 1) % busyIcons.length;
		statusAnimationLabel.setIcon(busyIcons[busyIconIndex]);
	    }
	});
	idleIcon = resourceMap.getIcon("StatusBar.idleIcon");
	statusAnimationLabel.setIcon(idleIcon);
	progressBar.setVisible(false);

	// connecting action tasks to status bar via TaskMonitor
	TaskMonitor taskMonitor = new TaskMonitor(getApplication().getContext());
	taskMonitor.addPropertyChangeListener(new java.beans.PropertyChangeListener() {

	    public void propertyChange(java.beans.PropertyChangeEvent evt) {
		String propertyName = evt.getPropertyName();
		if ("started".equals(propertyName)) {
		    if (!busyIconTimer.isRunning()) {
			statusAnimationLabel.setIcon(busyIcons[0]);
			busyIconIndex = 0;
			busyIconTimer.start();
		    }
		    progressBar.setVisible(true);
		    progressBar.setIndeterminate(true);
		} else if ("done".equals(propertyName)) {
		    busyIconTimer.stop();
		    statusAnimationLabel.setIcon(idleIcon);
		    progressBar.setVisible(false);
		    progressBar.setValue(0);
		} else if ("message".equals(propertyName)) {
		    String text = (String) (evt.getNewValue());
		    statusMessageLabel.setText((text == null) ? "" : text);
		    messageTimer.restart();
		} else if ("progress".equals(propertyName)) {
		    int value = (Integer) (evt.getNewValue());
		    progressBar.setVisible(true);
		    progressBar.setIndeterminate(false);
		    progressBar.setValue(value);
		}
	    }
	});
    }

    private void createUpdateTimer() {
	updateTimer = new Timer(1000, new ActionListener() {

	    public void actionPerformed(ActionEvent evt) {
		Object oldValue = jList1.getSelectedValue();
		jList1.setModel(users);
		users.clear();
		String[] names = iw.getUsersString().split(";");
		for (int i = 0; i < names.length; i++) {
		    users.addElement(names[i]);
		}
		if (oldValue != null) {
		    jList1.setSelectedValue(oldValue, true);
		}
		//users.addElement("Peter");
		//users.addElement("fred");
		//jList1.setSelectedValue("fred", true);
	    }
	});
	updateTimer.setInitialDelay(0);
	updateTimer.start();
    }

    private void createDisplayIncomingTimer() {
	displayIncomingTimer = new Timer(1000, new ActionListener() {

	    public void actionPerformed(ActionEvent evt) {
		String input;
		while ((input = iw.getInput()) != null) {
		    jTextArea1.append(input);
		    jTextArea1.append(System.getProperty("line.separator"));
		}
	    }
	});
	displayIncomingTimer.setInitialDelay(0);
	displayIncomingTimer.start();
    }

    @Action
    public void showAboutBox() {
	if (aboutBox == null) {
	    JFrame mainFrame = ChatApp.getApplication().getMainFrame();
	    aboutBox = new ChatAboutBox(mainFrame);
	    aboutBox.setLocationRelativeTo(mainFrame);
	}
	ChatApp.getApplication().show(aboutBox);
    }

    /** This method is called from within the constructor to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        mainPanel = new javax.swing.JPanel();
        jSplitPane1 = new javax.swing.JSplitPane();
        jScrollPane1 = new javax.swing.JScrollPane();
        jTextPane2 = new javax.swing.JTextPane();
        jScrollPane2 = new javax.swing.JScrollPane();
        jTextPane1 = new javax.swing.JTextPane();
        jScrollPane3 = new javax.swing.JScrollPane();
        jList1 = new javax.swing.JList();
        jScrollPane4 = new javax.swing.JScrollPane();
        jTextArea1 = new javax.swing.JTextArea();
        menuBar = new javax.swing.JMenuBar();
        javax.swing.JMenu fileMenu = new javax.swing.JMenu();
        javax.swing.JMenuItem exitMenuItem = new javax.swing.JMenuItem();
        javax.swing.JMenu helpMenu = new javax.swing.JMenu();
        javax.swing.JMenuItem aboutMenuItem = new javax.swing.JMenuItem();
        statusPanel = new javax.swing.JPanel();
        javax.swing.JSeparator statusPanelSeparator = new javax.swing.JSeparator();
        statusMessageLabel = new javax.swing.JLabel();
        statusAnimationLabel = new javax.swing.JLabel();
        progressBar = new javax.swing.JProgressBar();
        jTextField1 = new javax.swing.JTextField();

        mainPanel.setName("mainPanel"); // NOI18N

        jSplitPane1.setName("jSplitPane1"); // NOI18N

        jScrollPane1.setName("jScrollPane1"); // NOI18N

        jTextPane2.setName("jTextPane2"); // NOI18N
        jScrollPane1.setViewportView(jTextPane2);

        jSplitPane1.setLeftComponent(jScrollPane1);

        jScrollPane2.setName("jScrollPane2"); // NOI18N

        jTextPane1.setName("jTextPane1"); // NOI18N
        jScrollPane2.setViewportView(jTextPane1);

        jSplitPane1.setRightComponent(jScrollPane2);

        jScrollPane3.setName("jScrollPane3"); // NOI18N

        jList1.setModel(new javax.swing.AbstractListModel() {
            String[] strings = { "Item 1", "Item 2", "Item 3", "Item 4", "Item 5" };
            public int getSize() { return strings.length; }
            public Object getElementAt(int i) { return strings[i]; }
        });
        jList1.setName("jList1"); // NOI18N
        jList1.addListSelectionListener(new javax.swing.event.ListSelectionListener() {
            public void valueChanged(javax.swing.event.ListSelectionEvent evt) {
                jList1ValueChanged(evt);
            }
        });
        jScrollPane3.setViewportView(jList1);

        jSplitPane1.setLeftComponent(jScrollPane3);

        jScrollPane4.setName("jScrollPane4"); // NOI18N

        jTextArea1.setColumns(20);
        jTextArea1.setRows(5);
        jTextArea1.setName("jTextArea1"); // NOI18N
        jScrollPane4.setViewportView(jTextArea1);

        jSplitPane1.setRightComponent(jScrollPane4);

        javax.swing.GroupLayout mainPanelLayout = new javax.swing.GroupLayout(mainPanel);
        mainPanel.setLayout(mainPanelLayout);
        mainPanelLayout.setHorizontalGroup(
            mainPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jSplitPane1, javax.swing.GroupLayout.DEFAULT_SIZE, 724, Short.MAX_VALUE)
        );
        mainPanelLayout.setVerticalGroup(
            mainPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jSplitPane1, javax.swing.GroupLayout.DEFAULT_SIZE, 337, Short.MAX_VALUE)
        );

        menuBar.setName("menuBar"); // NOI18N

        org.jdesktop.application.ResourceMap resourceMap = org.jdesktop.application.Application.getInstance(chat.ChatApp.class).getContext().getResourceMap(ChatView.class);
        fileMenu.setText(resourceMap.getString("fileMenu.text")); // NOI18N
        fileMenu.setName("fileMenu"); // NOI18N

        javax.swing.ActionMap actionMap = org.jdesktop.application.Application.getInstance(chat.ChatApp.class).getContext().getActionMap(ChatView.class, this);
        exitMenuItem.setAction(actionMap.get("quit")); // NOI18N
        exitMenuItem.setName("exitMenuItem"); // NOI18N
        fileMenu.add(exitMenuItem);

        menuBar.add(fileMenu);

        helpMenu.setText(resourceMap.getString("helpMenu.text")); // NOI18N
        helpMenu.setName("helpMenu"); // NOI18N

        aboutMenuItem.setAction(actionMap.get("showAboutBox")); // NOI18N
        aboutMenuItem.setName("aboutMenuItem"); // NOI18N
        helpMenu.add(aboutMenuItem);

        menuBar.add(helpMenu);

        statusPanel.setName("statusPanel"); // NOI18N

        statusPanelSeparator.setName("statusPanelSeparator"); // NOI18N

        statusMessageLabel.setName("statusMessageLabel"); // NOI18N

        statusAnimationLabel.setHorizontalAlignment(javax.swing.SwingConstants.LEFT);
        statusAnimationLabel.setName("statusAnimationLabel"); // NOI18N

        progressBar.setName("progressBar"); // NOI18N

        jTextField1.setText(resourceMap.getString("jTextField1.text")); // NOI18N
        jTextField1.setName("jTextField1"); // NOI18N
        jTextField1.addKeyListener(new java.awt.event.KeyAdapter() {
            public void keyTyped(java.awt.event.KeyEvent evt) {
                jTextField1KeyTyped(evt);
            }
        });

        javax.swing.GroupLayout statusPanelLayout = new javax.swing.GroupLayout(statusPanel);
        statusPanel.setLayout(statusPanelLayout);
        statusPanelLayout.setHorizontalGroup(
            statusPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(statusPanelLayout.createSequentialGroup()
                .addContainerGap()
                .addComponent(statusMessageLabel)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(statusPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, statusPanelLayout.createSequentialGroup()
                        .addComponent(jTextField1, javax.swing.GroupLayout.PREFERRED_SIZE, 464, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(progressBar, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(statusAnimationLabel)
                        .addContainerGap())
                    .addComponent(statusPanelSeparator, javax.swing.GroupLayout.DEFAULT_SIZE, 700, Short.MAX_VALUE)))
        );
        statusPanelLayout.setVerticalGroup(
            statusPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(statusPanelLayout.createSequentialGroup()
                .addComponent(statusPanelSeparator, javax.swing.GroupLayout.PREFERRED_SIZE, 2, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addGroup(statusPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.TRAILING)
                    .addGroup(statusPanelLayout.createSequentialGroup()
                        .addGroup(statusPanelLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                            .addComponent(statusMessageLabel)
                            .addComponent(statusAnimationLabel)
                            .addComponent(progressBar, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                        .addGap(3, 3, 3))
                    .addComponent(jTextField1, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)))
        );

        setComponent(mainPanel);
        setMenuBar(menuBar);
        setStatusBar(statusPanel);
    }// </editor-fold>//GEN-END:initComponents

    private void jList1ValueChanged(javax.swing.event.ListSelectionEvent evt) {//GEN-FIRST:event_jList1ValueChanged
	// TODO add your handling code here:
	//jList1.getSelectedValue();
	if (!evt.getValueIsAdjusting()) {
	    //jTextArea1.append(jList1.getSelectedValue().toString());
	    //jTextArea1.append(System.getProperty("line.separator"));
	}
    }//GEN-LAST:event_jList1ValueChanged

    private void jTextField1KeyTyped(java.awt.event.KeyEvent evt) {//GEN-FIRST:event_jTextField1KeyTyped
	// TODO add your handling code here:
	if ((evt.getKeyChar() == '\n' || evt.getKeyChar() == '\r')
		&& (jTextField1.getText().length() > 0)
		&& (jList1.getSelectedValue() != null)) {
	    String name = jList1.getSelectedValue().toString();
	    name = name.split(" ")[0];
	    jTextArea1.append("->" + name + ": ");
	    String msg = jTextField1.getText();
	    jTextArea1.append(msg);
	    jTextArea1.append(System.getProperty("line.separator"));
	    jTextField1.setText("");
	    // now really send it
	    int numTries = 5;
	    while (iw.hasConnection(name, getService()) == 0 && numTries > 0) {
		iw.getConnection(name, getService());
		try {
		    Thread.sleep(1000);
		} catch (InterruptedException ex) {
		    Logger.getLogger(ChatView.class.getName()).log(Level.SEVERE, null, ex);
		}
		numTries--;
	    }
	    if (iw.hasConnection(name, getService()) == 1) {
		iw.send(name, msg);
	    }
	}
    }//GEN-LAST:event_jTextField1KeyTyped
    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JList jList1;
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JScrollPane jScrollPane2;
    private javax.swing.JScrollPane jScrollPane3;
    private javax.swing.JScrollPane jScrollPane4;
    private javax.swing.JSplitPane jSplitPane1;
    private javax.swing.JTextArea jTextArea1;
    private javax.swing.JTextField jTextField1;
    private javax.swing.JTextPane jTextPane1;
    private javax.swing.JTextPane jTextPane2;
    private javax.swing.JPanel mainPanel;
    private javax.swing.JMenuBar menuBar;
    private javax.swing.JProgressBar progressBar;
    private javax.swing.JLabel statusAnimationLabel;
    private javax.swing.JLabel statusMessageLabel;
    private javax.swing.JPanel statusPanel;
    // End of variables declaration//GEN-END:variables
    private final Timer messageTimer;
    private final Timer busyIconTimer;
    private final Icon idleIcon;
    private final Icon[] busyIcons = new Icon[15];
    private int busyIconIndex = 0;
    private JDialog aboutBox;
    private DefaultListModel users = new DefaultListModel();
    private Timer updateTimer;
    private Timer displayIncomingTimer;
    private mymed.JavaIceWrapper iw;
    private int service;
    private String userName;

    /**
     * @return the iw
     */
    public mymed.JavaIceWrapper getIw() {
	return iw;




    }

    /**
     * @param iw the iw to set
     */
    public void setIw(mymed.JavaIceWrapper iw) {
	this.iw = iw;


    }

    /**
     * @return the service
     */
    public int getService() {
	return service;
    }

    /**
     * @param service the service to set
     */
    public void setService(int service) {
	this.service = service;
    }

    /**
     * @return the userName
     */
    public String getUserName() {
	return userName;
    }

    /**
     * @param userName the userName to set
     */
    public void setUserName(String userName) {
	this.userName = userName;
    }
}
