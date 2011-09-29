/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.reputationprototype;

import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import javax.xml.parsers.ParserConfigurationException;
import javax.xml.parsers.SAXParser;
import javax.xml.parsers.SAXParserFactory;

import org.xml.sax.Attributes;
import org.xml.sax.SAXException;
import org.xml.sax.helpers.DefaultHandler;

import com.mymed.controller.core.manager.reputation.globals.Constants;

/**
 *
 * @author piccolo
 */
public class CreateTableScriptOnFile extends DefaultHandler{
    
    private String tempVal;
    FileWriter writer;
    
    private void parseDocument() {		
	//get a factory
        SAXParserFactory spf = SAXParserFactory.newInstance();
	try {		
		//get a new instance of parser
		SAXParser sp = spf.newSAXParser();
		
		writer = new FileWriter(new File(Constants.OUTPUT_SCRIPT));
		
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
        String row = "";
        
        if(qName.equalsIgnoreCase("table")){
            row += "create column family ";
            
            row += attributes.getValue("name");
            
            row += " with column_type = ";
            
            //setting wether the structure is a ColumnFamily or a SuperColumnFamily
            if(attributes.getValue("type").equalsIgnoreCase("cf")){
                
                System.out.println("Creating ColumnFamily " + attributes.getValue("name"));
                        
                row+= "'Standard'";
            }
            else if(attributes.getValue("type").equalsIgnoreCase("scf")){
                
            	row += "'Super'";
            	
                if(attributes.getValue("subcomparator").equalsIgnoreCase("yes")){
                    row += " and comparator ='" + Constants.DEFAULT_COMPARATOR +"'";
                }
            }
            row += " and rows_cached = 100.0 and keys_cached = 1000.0 and key_cache_save_period = 3600;\n";
            
            System.out.println(row);
            
            try{
            	writer.write(row);
            }
            catch(Exception e){}
        }
    }
    
    @Override
    public void characters(char[] ch, int start, int length) throws SAXException {
        tempVal = new String(ch,start,length);
    }
    
    @Override
    public void endElement(String uri, String localName, String qName) throws SAXException {
           
    }
    
    public CreateTableScriptOnFile(){
        parseDocument();
    }
    
    public static void main(String a[]) throws IOException{
        CreateTableScriptOnFile c = new CreateTableScriptOnFile();
        c.writer.close();
    }
}