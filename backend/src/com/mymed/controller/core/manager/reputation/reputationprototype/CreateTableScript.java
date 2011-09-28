/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.reputationprototype;

import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.parsers.SAXParser;
import javax.xml.parsers.SAXParserFactory;

import org.apache.cassandra.thrift.CfDef;
import org.apache.cassandra.thrift.KsDef;
import org.xml.sax.Attributes;
import org.xml.sax.SAXException;
import org.xml.sax.helpers.DefaultHandler;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.reputation.globals.Constants;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;

/**
 *
 * @author piccolo
 */
public class CreateTableScript extends DefaultHandler{
    
    private CassandraWrapper wrapper;
    private String tempVal;
    private CfDef currentCfDefinition;
    
    private void parseDocument() {		
	//get a factory
        SAXParserFactory spf = SAXParserFactory.newInstance();
	try {		
		//get a new instance of parser
		SAXParser sp = spf.newSAXParser();
		
                //parse the file and also register this class for call backs
		sp.parse(Constants.DATA_MODEL_FILE_PATH, this);
		
	}catch(SAXException se) {
            se.printStackTrace();
	}catch(ParserConfigurationException pce) {
            pce.printStackTrace();
	}catch (IOException ie) {
            ie.printStackTrace();
	}
    }
    
    @Override
    public void startElement(String uri, String localName, String qName, Attributes attributes) throws SAXException {
        //resetting
        tempVal = "";
        
        if(qName.equalsIgnoreCase("table")){
            currentCfDefinition = new CfDef();
            
            //setting the current keyspace
            currentCfDefinition.setKeyspace(Constants.KEYSPACE); 
            
            //setting the name of the new ColumFamily or SuperColumnfamily
            currentCfDefinition.setName(attributes.getValue("name"));
            
            //setting wether the structure is a ColumnFamily or a SuperColumnFamily
            if(attributes.getValue("type").equalsIgnoreCase("cf")){
                
                System.out.println("Creating ColumnFamily " + attributes.getValue("name"));
                        
                currentCfDefinition.setColumn_type("Standard");
            }
            else if(attributes.getValue("type").equalsIgnoreCase("scf")){
                
                System.out.println("Creating SuperColumnFamily " + attributes.getValue("name"));
                
                currentCfDefinition.setColumn_type("Super");
                if(attributes.getValue("subcomparator").equalsIgnoreCase("yes")){
                    
                    System.out.println("Setting the comparator " + Constants.DEFAULT_COMPARATOR);
                    
                    currentCfDefinition.setComparator_type(Constants.DEFAULT_COMPARATOR);
                }
            }
        }
    }
    
    @Override
    public void characters(char[] ch, int start, int length) throws SAXException {
        tempVal = new String(ch,start,length);
    }
    
    @Override
    public void endElement(String uri, String localName, String qName) throws SAXException {
        if(qName.equalsIgnoreCase("table")){
            try {
                wrapper.system_add_column_family(currentCfDefinition);
                System.out.println("Structure creation completed");
            } catch (InternalBackEndException ex) {
                Logger.getLogger(CreateTableScript.class.getName()).log(Level.SEVERE, null, ex);
            }
        }
    }
    
    public CreateTableScript() throws InternalBackEndException{
        File confFile = new File(Constants.CONFIGURATION_FILE_PATH);
        final WrapperConfiguration conf = new WrapperConfiguration(confFile);

	final String listenAddress = conf.getCassandraListenAddress();
	final int thriftPort = conf.getThriftPort();

        System.out.println("Connection information:");
	System.out.println("\tListen Address: " + listenAddress);
	System.out.println("\tThrift Port   : " + thriftPort);
	System.out.println("\n");

	wrapper = new CassandraWrapper(listenAddress, thriftPort);
        
        System.out.println("Opening Cassandra connection...");
//        wrapper.open();
        
        System.out.println("Creating KeySpace " + Constants.KEYSPACE);
        
        //setting keyspace attributes
        KsDef keyDef = new KsDef();
        keyDef.setName(Constants.KEYSPACE);
        keyDef.setReplication_factor(Constants.REPLICATION_FACTOR);
        keyDef.setStrategy_class(Constants.CASSANDRA_STRATEGY);        
        //creo la lista delle columnfamily del keyspace per ora vuota
        List<CfDef> cfDefs = new ArrayList<CfDef>();
        keyDef.setCf_defs(cfDefs);
        wrapper.system_add_keyspace(keyDef);
        
        wrapper.set_keyspace(Constants.KEYSPACE);
        
        parseDocument();
    }
    
    public static void main(String a[]) throws InternalBackEndException{
        CreateTableScript c = new CreateTableScript();
    }
}
