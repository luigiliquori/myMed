/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.db.facade;

import java.io.IOException;
import java.lang.reflect.Constructor;
import java.lang.reflect.Field;
import java.lang.reflect.InvocationTargetException;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;
import org.xml.sax.SAXException;

import com.mymed.controller.core.manager.reputation.db.table.UserApplicationConsumer;
import com.mymed.controller.core.manager.reputation.db.table.Verdict;
import com.mymed.controller.core.manager.reputation.globals.Constants;

/**
 *
 * @author piccolo
 */
public class CassandraDescTable {
    private Document dom;
    
    private static CassandraDescTable instance = new CassandraDescTable();
    
    public static CassandraDescTable getNewInstance(){
        return instance;
    }
    
    public static final String SEPARATOR_CHAR = "|";
     
    private CassandraDescTable(){
            //get the factory
	DocumentBuilderFactory dbf = DocumentBuilderFactory.newInstance();
		
	try {			
            //Using factory get an instance of document builder
            DocumentBuilder db = dbf.newDocumentBuilder();
			
            //parse using builder to get DOM representation of the XML file
            dom = db.parse(Constants.DATA_MODEL_FILE_PATH);
			

	}catch(ParserConfigurationException pce) {
		pce.printStackTrace();
	}catch(SAXException se) {
		se.printStackTrace();
	}catch(IOException ioe) {
		ioe.printStackTrace();
	}
    }
    
    public NodeList getListOfAttribute(String columnFamilyName){
        Element rootElement = dom.getDocumentElement();
        NodeList nl = rootElement.getElementsByTagName("table");
        Element elToRetrieve = null;
        if(nl != null && nl.getLength() > 0){
            for(int i =0; i< nl.getLength(); i++){
                Element currentEl = (Element) nl.item(i);
                if(currentEl.getAttribute("name").equals(columnFamilyName)){
                    elToRetrieve = currentEl;
                    break;
                }
            }
            if(elToRetrieve == null){
                throw new RuntimeException(columnFamilyName + ": ColumnFamily not found");
            } else{
                Element attributesElement = (Element)elToRetrieve.getElementsByTagName("attributes").item(0);
                NodeList listOfAttribute = attributesElement.getElementsByTagName("attribute");
                return listOfAttribute;
            }
        }
        else{
            throw new RuntimeException("The structure list cannot be empty");
        }
    }
    
    public NodeList getListOfConstraints(String columnFamilyName){
        Element rootElement = dom.getDocumentElement();
        NodeList nl = rootElement.getElementsByTagName("table");
        Element elToRetrieve = null;
        if(nl != null && nl.getLength() > 0){
            for(int i =0; i< nl.getLength(); i++){
                Element currentEl = (Element) nl.item(i);
                if(currentEl.getAttribute("name").equals(columnFamilyName)){
                    elToRetrieve = currentEl;
                    break;
                }
            }
            if(elToRetrieve == null){
                throw new RuntimeException(columnFamilyName + ": ColumnFamily not found");
            } else{
                Element retEl= (Element) elToRetrieve.getElementsByTagName("constraints").item(0);
                return retEl.getElementsByTagName("constraint");
            }
        }
        else{
            throw new RuntimeException("The structure list cannot be empty");
        }
    }
    
    public String getNameOfPrimaryKeyField(String columnFamilyName){
        NodeList listOfAttribute = getListOfAttribute(columnFamilyName);
        
        if(listOfAttribute != null && listOfAttribute.getLength() >0){
            Element pkToRetrieve=null;
            for(int i = 0; i< listOfAttribute.getLength();i++){
                 Element currEl = (Element) listOfAttribute.item(i);
                 if(currEl.hasAttribute("key") && currEl.getAttribute("key").equals("primary")){
                     pkToRetrieve = currEl;
                     break;
                 }
            }
            if(pkToRetrieve == null){
                throw new RuntimeException("Primary key not found");
            }
            else{
                return pkToRetrieve.getFirstChild().getNodeValue();
            }
        }
        else{
            throw new RuntimeException("The attribute list cannot be empty");
         }
    }

    public List<String> getUniqueFields(String columnFamilyName){
        NodeList constraintList = getListOfConstraints(columnFamilyName);
        ArrayList<String> result = new ArrayList<String>();
        if(constraintList != null && constraintList.getLength() > 0){
            Element elToRetrieve = null;
            for(int i =0;i< constraintList.getLength(); i++){
                Element currEl = (Element) constraintList.item(i);
                System.out.println(currEl.getFirstChild().getNodeValue());
                if(currEl.hasAttribute("type") && currEl.getAttribute("type").equals("unique")){
                    elToRetrieve = currEl;
                    break;
                }
            }
            if(elToRetrieve != null){
                NodeList listOfAttributes = elToRetrieve.getElementsByTagName("attribute");
                if(listOfAttributes != null && listOfAttributes.getLength() >0){
                    String[] resultArray = new String[listOfAttributes.getLength()];
                    for(int i=0;i<listOfAttributes.getLength();i++){
                        Element currEl = (Element) listOfAttributes.item(i);
                        resultArray[Integer.parseInt(currEl.getAttribute("order"))] = 
                                currEl.getFirstChild().getNodeValue();
                    }
                    result.addAll(Arrays.asList(resultArray));
                    return result;
                }
                else{
                    throw new RuntimeException();
                }
            }
            else{
                throw new RuntimeException();
            }
        }
        else{
            throw new RuntimeException();
        }
    }
    
    public String generateKeyForColumnFamily(Object dbTableObject) {
        try {
            String[] objParts = dbTableObject.getClass().getName().split("\\.");
            
            String columnFamilyName = objParts[objParts.length -1];
            
            Class idClass = Class.forName(Constants.PRIMARY_KEY_PACKAGE + "." + columnFamilyName + "Id");
            Constructor mainConstructor = idClass.getConstructors()[0];
            
            List<String> uniqueFields = getUniqueFields(columnFamilyName);
            Object[] initArgs = new Object[uniqueFields.size()];
            for(int i=0; i<uniqueFields.size();i++){
                Field currentField = dbTableObject.getClass().getDeclaredField(uniqueFields.get(i));
                currentField.setAccessible(true);
                initArgs[i] = currentField.get(dbTableObject);
            }
            
            Object referredId = mainConstructor.newInstance(initArgs);
            return referredId.toString();
        } catch (InstantiationException ex) {
            Logger.getLogger(CassandraDescTable.class.getName()).log(Level.SEVERE, null, ex);
        } catch (IllegalAccessException ex) {
            Logger.getLogger(CassandraDescTable.class.getName()).log(Level.SEVERE, null, ex);
        } catch (IllegalArgumentException ex) {
            Logger.getLogger(CassandraDescTable.class.getName()).log(Level.SEVERE, null, ex);
        } catch (InvocationTargetException ex) {
            Logger.getLogger(CassandraDescTable.class.getName()).log(Level.SEVERE, null, ex);
        } catch (NoSuchFieldException ex) {
            Logger.getLogger(CassandraDescTable.class.getName()).log(Level.SEVERE, null, ex);
        } catch (SecurityException ex) {
            Logger.getLogger(CassandraDescTable.class.getName()).log(Level.SEVERE, null, ex);
        } catch (ClassNotFoundException ex) {
            Logger.getLogger(CassandraDescTable.class.getName()).log(Level.SEVERE, null, ex);
        }
        return "";
    }
    
    @Deprecated
    public String generateKeyForSuperColumnItem(Object dbTableObject,String superColumnFamilyName) throws Exception{
        return generateKeyForColumnFamily(dbTableObject);
    }
    
    public List<String> getListOfForeignKey(String tableName){
        List<String> result = new ArrayList<String>();
        
        NodeList nl = getListOfAttribute(tableName);
        if(nl != null && nl.getLength()>0){
            for(int i=0;i<nl.getLength();i++){
                Element currEl = (Element)nl.item(i);
                if(currEl.hasAttribute("key") && currEl.getAttribute("key").equals("foreign")){
                    result.add(currEl.getFirstChild().getNodeValue());
                }
            }
        }
        return result;
    }
    
    public String getReferredTable(String tableName, String fieldName){
        NodeList nl = getListOfAttribute(tableName);
        if(nl != null && nl.getLength()>0){
            for(int i=0;i<nl.getLength();i++){
                Element currEl = (Element)nl.item(i);
                if(currEl.getFirstChild().getNodeValue().equals(fieldName)){
                    if(currEl.hasAttribute("references")){
                        return currEl.getAttribute("references");
                    }
                }
            }
        }
        throw new RuntimeException();
    }
    
    public static void main(String a[]) throws Exception{
        Verdict verd = new Verdict();
        verd.setVerdictId("ver1");
        verd.setJudgeId("user1");
        verd.setChargedId("char1");
        verd.setApplicationId("app1");
        verd.setTime(System.currentTimeMillis());
        verd.setIsJudgeProducer(true);
        verd.setVerdictAggregationList("listAgg");
        verd.setVote(0.6);
        
        System.out.println(instance.generateKeyForColumnFamily(verd));
        
        UserApplicationConsumer c = new UserApplicationConsumer();
        c.setUserId("xxx");
        c.setApplicationId("aaaa");
        
        System.out.println(instance.generateKeyForColumnFamily(c));
    }
}
