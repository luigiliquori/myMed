/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.db.facade;

import java.io.File;
import java.lang.reflect.Field;
import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;
import java.nio.ByteBuffer;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.TreeSet;
import java.util.logging.Level;
import java.util.logging.Logger;

import org.apache.cassandra.thrift.Column;
import org.apache.cassandra.thrift.ColumnOrSuperColumn;
import org.apache.cassandra.thrift.ColumnParent;
import org.apache.cassandra.thrift.ConsistencyLevel;
import org.apache.cassandra.thrift.Deletion;
import org.apache.cassandra.thrift.KeyRange;
import org.apache.cassandra.thrift.KeySlice;
import org.apache.cassandra.thrift.Mutation;
import org.apache.cassandra.thrift.SlicePredicate;
import org.apache.cassandra.thrift.SliceRange;
import org.apache.cassandra.thrift.SuperColumn;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.reputation.db.table.UserApplicationConsumer;
import com.mymed.controller.core.manager.reputation.db.table.Verdict;
import com.mymed.controller.core.manager.reputation.globals.Constants;
import com.mymed.controller.core.manager.reputation.primary_key.VerdictId;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;
import com.mymed.utils.ClassType;
import com.mymed.utils.MConverter;

/**
 * This class wraps different Cassandra Insert Queries
 * @author piccolo
 */
public class CassandraQueryFacade {
    Map<ByteBuffer,Map<String,List<Mutation>>> buffer;
    
    Map<String,Map<String,Object>> columnFamilyInsertions;
    Map<String,Map<String,List<String>>> superColumnFamilyInsertions;
    
    CassandraWrapper wrapper;
    CassandraDescTable descTable = CassandraDescTable.getNewInstance();
    
    
    private static CassandraQueryFacade instance;
    
    /**
     * this methods returns a reference to the manager of all the queries submitted to Cassandra
     * @param w
     * @return 
     */
    static CassandraQueryFacade getNewInstance(CassandraWrapper w){
        if(instance == null){
            instance = new CassandraQueryFacade(w);
        }
        return instance;
    }
    
    private CassandraQueryFacade(CassandraWrapper w){
        wrapper = w;
        buffer = new HashMap<ByteBuffer, Map<String, List<Mutation>>> ();
        columnFamilyInsertions = new HashMap<String, Map<String, Object>>();
        superColumnFamilyInsertions = new HashMap<String, Map<String, List<String>>>();
    }
    
    /**
     * @return true if the buffer is empty, false otherwise
     */
    boolean isEmpty(){
        return (buffer == null || buffer.isEmpty());
    }
    
    /**
     * This methods inserts an object corresponding to a row of a ColumnFamily into the buffer
     * @param objToInsert the object to insert
     */
    void insertDbTableObject(Object objToInsert){
        try{
            //retrieving the name of the ColumnFamily from the name of the (dynamic) type of the object
            String[] objParts = objToInsert.getClass().getName().split("\\.");
            String columnFamilyName = objParts[objParts.length -1];

            //retrieving the name of the primary key field
            String primaryKeyFieldName = descTable.getNameOfPrimaryKeyField(columnFamilyName);

            List<Mutation> row = new ArrayList<Mutation>();

            //retrieving the name of the corresponding "Id" class in the package "primary_key"
            //and the methods allowing us to make the translation from the string representation to
            //the bytebuffer representation
            Class idClass = Class.forName(Constants.PRIMARY_KEY_PACKAGE + "." + columnFamilyName + "Id");
            Method stringToObj = idClass.getMethod("parseString", new Class[]{String.class});
            Method objToByteBuffer = idClass.getMethod("get" + columnFamilyName + "Id" + "AsByteBuffer", new Class[0]);
            Object idInstance;
            ByteBuffer pkByteBuffer = null;

            for(Field field : objToInsert.getClass().getDeclaredFields()){

                field.setAccessible(true);
                ColumnOrSuperColumn currentCol = new ColumnOrSuperColumn();

                ClassType type = ClassType.inferTpye(field.getType());

                //if the current field is the primary key field, then
                //we use the retrieved translation methods to generate the bytebuffer
                //representation
                if(field.getName().equals(primaryKeyFieldName)){
                    idInstance = stringToObj.invoke(null, field.get(objToInsert));
                    pkByteBuffer = (ByteBuffer) objToByteBuffer.invoke(idInstance, new Object[0]);
                    currentCol.setColumn(new Column(ByteBuffer.wrap(field.getName().getBytes()),
                            pkByteBuffer,System.currentTimeMillis()));
                }
                else{ //otherwise, we use the usual methodology
                    currentCol.setColumn(new Column(ByteBuffer.wrap(field.getName().getBytes()),
                        ByteBuffer.wrap(ClassType.objectToByteArray(type,field.get(objToInsert))), 
                        System.currentTimeMillis()));
                }
                Mutation m = new Mutation();
                m.setColumn_or_supercolumn(currentCol);
                row.add(m);
            }

            //putting the value in the buffer with the key specified by the variable pkByteBuffer
            //which has been initalized by the previous cycle
            Map<String, List<Mutation>> keyRow = buffer.get(pkByteBuffer);
            if(keyRow == null){
                keyRow = new HashMap<String, List<Mutation>>();
            }
            List<Mutation> currList = keyRow.get(columnFamilyName);
            if(currList == null){
                currList = new ArrayList<Mutation>();
            }
            currList.addAll(row);
            keyRow.put(columnFamilyName, currList);
            buffer.put(pkByteBuffer, keyRow);

            //putting the value also in the buffer of columnfamilyinsertions
            Map<String, Object> insertionMapKey = columnFamilyInsertions.get(columnFamilyName);
            if(insertionMapKey == null){
                insertionMapKey = new HashMap<String, Object>();
            }
            Field pkField = objToInsert.getClass().getDeclaredField(primaryKeyFieldName);
            pkField.setAccessible(true);
            String pkValue = (String)pkField.get(objToInsert);
            insertionMapKey.put(pkValue,objToInsert);
            columnFamilyInsertions.put(columnFamilyName, insertionMapKey);
        }
        catch (NoSuchMethodException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (SecurityException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }        catch (ClassNotFoundException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }        catch (InvocationTargetException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }        catch (NoSuchFieldException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (IllegalArgumentException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (IllegalAccessException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }   
    }
    
    /**
     * This method adds a key pointing to a row of a ColumnFamily into a list stored in a SuperColumnFamily
     * @param superColumnFamilyName the name of the SuperColumFamily
     * @param keyOfList the key of the list
     * @param cfKey the key pointing to the row of the ColumnFamily, which will be a node of the list
     * 
     */
   void insertIntoList(String superColumnFamilyName, String keyOfList, String cfKey){
       try{
           NodeList listAttributes = descTable.getListOfAttribute(superColumnFamilyName);

            Map<String,List<Mutation>> keySuperRow = buffer.get(ByteBuffer.wrap(keyOfList.getBytes()));
            if(keySuperRow == null){
                keySuperRow = new HashMap<String, List<Mutation>>();
            }

            List<Mutation> superRow = keySuperRow.get(superColumnFamilyName);
            if(superRow == null){
                superRow = new ArrayList<Mutation>();
            }

            Mutation m = new Mutation();
            ColumnOrSuperColumn c = new ColumnOrSuperColumn();
            List<Column> columns = new ArrayList<Column>();

            //in the xml file there is only one attribute for the supercolumnfamily
            if(listAttributes != null && listAttributes.getLength() ==1){
                Element uniqueAttribute = ((Element)(listAttributes.item(0)));
                String columnFamilyName = uniqueAttribute.getAttribute("references");

                Class idClass = Class.forName(Constants.PRIMARY_KEY_PACKAGE + "." + columnFamilyName + "Id");
                Method stringToObj = idClass.getMethod("parseString", new Class[]{String.class});
                Method objToByteBuffer = idClass.getMethod("get" + columnFamilyName + "Id" + "AsByteBuffer", new Class[0]);

                Object idInstance = stringToObj.invoke(null, cfKey);
                ByteBuffer pkByteBuffer = (ByteBuffer) objToByteBuffer.invoke(idInstance, new Object[0]);

                columns.add(new Column(ByteBuffer.wrap(uniqueAttribute.getFirstChild().getNodeValue().getBytes()),
                            pkByteBuffer,System.currentTimeMillis()));

                SuperColumn sc = new SuperColumn(pkByteBuffer, columns);
                c.setSuper_column(sc);
                m.setColumn_or_supercolumn(c);
                superRow.add(m);
                keySuperRow.put(superColumnFamilyName, superRow);

                buffer.put(ByteBuffer.wrap(keyOfList.getBytes()), keySuperRow);

                //putting the item also in the buffer of supercolumnfamily
                Map<String, List<String>> insertionKey = superColumnFamilyInsertions.get(superColumnFamilyName);
                if(insertionKey == null){
                    insertionKey = new HashMap<String, List<String>>();
                }
                List<String> listOfKeys = insertionKey.get(keyOfList);
                if(listOfKeys == null){
                    listOfKeys = new ArrayList<String>();
                }
                listOfKeys.add(cfKey);
                insertionKey.put(keyOfList, listOfKeys);
                superColumnFamilyInsertions.put(superColumnFamilyName, insertionKey);
            }
            else{
                throw new RuntimeException("there must be attributes");
            }
       }
        catch (IllegalAccessException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (IllegalArgumentException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (InvocationTargetException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }        catch (NoSuchMethodException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (SecurityException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }        catch (ClassNotFoundException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }       
    }
   
   /**
    * This method loads into a bean a row of a ColumnFamily having the supplied key which has
    * been already persisted in Cassandra
    * @param nameOfColumnFamily the name of the ColumnFamily
    * @param key the key
    * @return the corresponding object or null if the key is not found
    * @throws InternalBackEndException
    */
   private Object loadRowFromCassandra(String nameOfColumnFamily, String key) throws InternalBackEndException{
       try{
           SlicePredicate predicate = new SlicePredicate();
           SliceRange sliceRange = new SliceRange();
           sliceRange.setStart(new byte[0]);
           sliceRange.setFinish(new byte[0]);
           predicate.setSlice_range(sliceRange);
           ColumnParent parent = new ColumnParent(nameOfColumnFamily);

           //retrieving the value of the supplied key
           List<ColumnOrSuperColumn> result = wrapper.get_slice(key, parent, predicate, ConsistencyLevel.ONE);

           if(result.size() > 0){
                //retrieving the type of the bean where loading the retrieved information 
                Class chosenTable = Class.forName(Constants.DB_TABLE_PACKAGE + "." + nameOfColumnFamily);
                Object newInstance = chosenTable.newInstance();

                //loading the value into the corresponding object
                for(ColumnOrSuperColumn c : result){
                    Field f = chosenTable.getDeclaredField(MConverter.byteBufferToString(c.column.name));
                    f.setAccessible(true);

                    ClassType type = ClassType.inferTpye(f.getType());
                    Object value = ClassType.objectFromClassType(type, c.column.getValue());

                    f.set(newInstance,value);
                }
                return newInstance;
           }
           else{
               return null;
           }
       }
        catch (NoSuchFieldException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (SecurityException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }        catch (InstantiationException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (IllegalAccessException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }        catch (ClassNotFoundException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }        catch (IllegalArgumentException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } 
       return null;
   }
   
   
   /**
    * this method load into a bean a row of a ColumnFamily having the specified key, which has not
    * yet been persisted into Cassandra
    * @param nameOfColumnFamily the name of the ColumnFamily
    * @param key the supplied key
    * @return the corresponding object or null if the key is not found
    */
   private Object loadRowFromBuffer(String nameOfColumnFamily, String key){
        Map<String, Object> insertionKey = columnFamilyInsertions.get(nameOfColumnFamily);
        if(insertionKey != null){
            return insertionKey.get(key);
        }
        else
            return null;
   }
   
   /**
    * this methods loads the value of the supplied key of a ColumnFamily into an object
    * @param nameOfColumnFamily the name of the ColumnFamily
    * @param key the name of the key
    * @return the corresponding object or null if the key is not found
    * @throws InternalBackEndException
    */
   Object loadRow(String nameOfColumnFamily, String key) throws InternalBackEndException {
       //we read first from the buffer
       Object loadedValue = loadRowFromBuffer(nameOfColumnFamily, key);
       
       // if the load fails then we load from Cassandra
       if(loadedValue == null){ 
           loadedValue = loadRowFromCassandra(nameOfColumnFamily, key);
           return loadedValue;
       }
       else{
           return loadedValue;
       }
    }
    
   /**
    * this method returns the collection of rows constituting the given ColumFamily, loading them from Cassandra
    * @param columnFamilyName the name of the ColumnFamily
    * @return the collection of objects
    * @throws InternalBackEndException
    */
   private List<Object> loadTableFromCassandra(String columnFamilyName) throws InternalBackEndException{
       try{
       SlicePredicate predicate = new SlicePredicate();
       SliceRange sliceRange = new SliceRange();
       sliceRange.setStart(new byte[0]);
       sliceRange.setFinish(new byte[0]);
       predicate.setSlice_range(sliceRange);
       ColumnParent parent = new ColumnParent(columnFamilyName);
       
       //setting the slice range allowing us to read the entire ColumnFamily
       KeyRange totRange = new KeyRange();
       totRange.setStart_key(new byte[0]);
       totRange.setEnd_key(new byte[0]);
       
       // retrieving the result
       List<KeySlice> _range_slices = wrapper.get_range_slices(parent, predicate, totRange, ConsistencyLevel.ONE);
       
       //loading the result into a list of beans
       List<Object> resultList = new ArrayList<Object>();
       for(KeySlice keySlice : _range_slices){
            List<ColumnOrSuperColumn> colList = keySlice.columns;
            Class chosenTable = Class.forName(Constants.DB_TABLE_PACKAGE + "." + columnFamilyName);
            Object newInstance = chosenTable.newInstance();
            for(ColumnOrSuperColumn c : colList){
                Field f = chosenTable.getDeclaredField(MConverter.byteBufferToString(c.column.name));
                f.setAccessible(true);
                
                ClassType type = ClassType.inferTpye(f.getType());
                Object value = ClassType.objectFromClassType(type, c.column.getValue());
                
                f.set(newInstance,value);
            }
            resultList.add(newInstance);
       }
       
       return resultList;
       }
        catch (ClassNotFoundException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }        catch (InstantiationException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (IllegalAccessException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }        catch (NoSuchFieldException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (SecurityException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }        catch (IllegalArgumentException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }
       return new ArrayList<Object>();
   }
   
      /**
    * this method returns the collection of rows constituting the given ColumFamily, loading them from the buffer
    * @param columnFamilyName the name of the ColumnFamily
    * @return the collection of objects
    * @throws InternalBackEndException
    * @throws ClassNotFoundException
    * @throws InstantiationException
    * @throws IllegalAccessException
    * @throws NoSuchFieldException 
    */
   private List<Object> loadTableFromBuffer(String columnFamilyName) {
        Map<String, Object> insertionKey = columnFamilyInsertions.get(columnFamilyName);
        if (insertionKey != null) {
        	return new ArrayList<Object>(insertionKey.values());
        }
        return new ArrayList<Object>();
   }
   
      /**
    * this method returns the collection of rows constituting the given ColumFamily
    * @param columnFamilyName the name of the ColumnFamily
    * @return the collection of objects
    * @throws InternalBackEndException
    * @throws ClassNotFoundException
    * @throws InstantiationException
    * @throws IllegalAccessException
    * @throws NoSuchFieldException 
    */
   List<Object> loadTable(String columnFamilyName) throws InternalBackEndException {
        try {
            //getting the data from Cassandra
            List<Object> loadTableFromCassandra = loadTableFromCassandra(columnFamilyName);
            
            // retrieving the ones coming from the buffer (if any)
            List<Object> loadTableFromBuffer = loadTableFromBuffer(columnFamilyName);
            
            //retrieving the name of the primary key
            String primaryKeyFieldName = descTable.getNameOfPrimaryKeyField(columnFamilyName);
            Class chosenTable = Class.forName(Constants.DB_TABLE_PACKAGE + "." + columnFamilyName);
            Field pkField = chosenTable.getDeclaredField(primaryKeyFieldName);
            pkField.setAccessible(true);
            
            //merging the two
            List<Object> result = new ArrayList<Object>();
            for(Object fromCassandra : loadTableFromCassandra){
                Object toAdd = fromCassandra;
                for(Object fromBuffer : loadTableFromBuffer){
                    if(pkField.get(toAdd).equals(pkField.get(fromBuffer))){
                        toAdd = fromBuffer;
                        loadTableFromBuffer.remove(fromBuffer);
                        break;
                    }
                }
                result.add(toAdd);
            }
            result.addAll(loadTableFromBuffer);
            return result;
        } catch (NoSuchFieldException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (SecurityException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (ClassNotFoundException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch(IllegalAccessException ex){
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);            
        } 
        return new ArrayList<Object>();
   }
   
   /**
    * this methods returns the list of objects corresponding to a given list of keys
    * @param keys the list of keys
    * @param columnFamilyName the name of the columnfamily in which the rows are stored
    * @return the list of objects corresponding to the keys
    * @throws InternalBackEndException 
    */
   List<Object> getListOfObjectFromListOfKeys(List<String> keys, String columnFamilyName) throws InternalBackEndException{
       try{
           SlicePredicate predicate = new SlicePredicate();
           SliceRange sliceRange = new SliceRange();
           sliceRange.setStart(new byte[0]);
           sliceRange.setFinish(new byte[0]);
           predicate.setSlice_range(sliceRange);
           ColumnParent parent = new ColumnParent(columnFamilyName);
           Map<ByteBuffer, List<ColumnOrSuperColumn>> multiget_slice = wrapper.multiget_slice(keys, parent, predicate, ConsistencyLevel.ONE);

           List<Object> result = new ArrayList<Object>();
            List<Mutation> mutations;

           for(String key : keys){
               Class chosenTable = Class.forName(Constants.DB_TABLE_PACKAGE + "." + columnFamilyName);
               Object newInstance =null; 
                // we first load from buffer
                Map<String, Object> insertionKey = columnFamilyInsertions.get(columnFamilyName);
               if(insertionKey != null){
                   newInstance = insertionKey.get(key);
               }

               //if nothing is found then we load from cassandra
               if(newInstance == null){ 
                   newInstance = chosenTable.newInstance();

                   List<ColumnOrSuperColumn> columns = multiget_slice.get(ByteBuffer.wrap(key.getBytes()));

                   for(ColumnOrSuperColumn c : columns){
                       String fieldName = MConverter.byteBufferToString(ByteBuffer.wrap(c.column.getName()));
                       Field f = chosenTable.getDeclaredField(fieldName);
                       f.setAccessible(true);

                       ClassType type = ClassType.inferTpye(f.getType());
                       Object value = ClassType.objectFromClassType(type, c.column.getValue());

                       f.set(newInstance,value);
                   }
               }
               result.add(newInstance);
           }
           return result;
       }
        catch (ClassNotFoundException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }        catch (InstantiationException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (IllegalAccessException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }        catch (NoSuchFieldException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (SecurityException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }        catch (IllegalArgumentException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } 
       return new ArrayList<Object>();
   }
   
   /**
    * This methods returns the list of keys stored in a SuperColumnFamily corresponding to a given
    * list, loading it from Cassandra
    * @param nameOfSuperColumnFamily the name of the SuperColumnFamily
    * @param key the key of the list
    * @return
    * @throws InternalBackEndException 
    */
   private List<String> readSuperColummFamilyFromCassandra(String nameOfSuperColumnFamily,String key) throws InternalBackEndException{
       try{ 
           SlicePredicate predicate = new SlicePredicate();
            SliceRange sliceRange = new SliceRange();
            sliceRange.setStart(new byte[0]);
            sliceRange.setFinish(new byte[0]);
            
            //i verdetti ordinati per tempo devono andare dal piu' recente al piu' antico
            if(nameOfSuperColumnFamily.equals("TimeOrderVerdictList")){
                sliceRange.setReversed(true);
            }

            predicate.setSlice_range(sliceRange);
            ColumnParent parent = new ColumnParent(nameOfSuperColumnFamily);

            List<ColumnOrSuperColumn> result = wrapper.get_slice(key, parent, predicate, ConsistencyLevel.ONE);
            List<String> keys = new ArrayList<String>();

            NodeList nl = descTable.getListOfAttribute(nameOfSuperColumnFamily);

            for(ColumnOrSuperColumn c : result){
                SuperColumn sc = c.super_column;
                List<Column> columns = sc.getColumns();
                for(Column col : columns){
                    String colName = MConverter.byteBufferToString(col.name);
                    for(int i = 0; i< nl.getLength();i++){
                        Element e = (Element) nl.item(i);
                        if(e.hasAttribute("key") && e.getAttribute("key").equals("foreign")){
                            if(e.getFirstChild().getNodeValue().equals(colName)){
                                String columnFamilyName = e.getAttribute("references");
                                Class idClass = Class.forName(Constants.PRIMARY_KEY_PACKAGE + "." + columnFamilyName + "Id");
                                Method byteBufferToObj = idClass.getMethod("parseByteBuffer", new Class[]{ByteBuffer.class});

                                keys.add(byteBufferToObj.invoke(null, col.value).toString());
                            }
                        }
                    }
                }
            }
            return keys;
       }
        catch (IllegalAccessException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (IllegalArgumentException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (InvocationTargetException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }        catch (NoSuchMethodException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (SecurityException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }       
       catch(ClassNotFoundException e){
           Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE,null,e);
       }
       return new ArrayList<String>();
    }
      
     /**
    * This methods returns the list of keys stored in a SuperColumnFamily corresponding to a given
    * list, loading it from Cassandra
    * @param nameOfSuperColumnFamily the name of the SuperColumnFamily
    * @param key the key of the list
    * @return
    */
    private List<String> readSuperColummFamilyFromBuffer(String nameOfSuperColumnFamily,String key){
        
        Map<String, List<String>> insertionKey = superColumnFamilyInsertions.get(nameOfSuperColumnFamily);
        if(insertionKey != null){
            List<String> resultList = insertionKey.get(key);
            if(resultList != null){
                return resultList;
            }
        }
        return new ArrayList<String>();
        
    }
    
    /**
     * This method deletes a row of a columnfamily
     * @param nameOfColumnFamily the name of the columnfamily
     * @param key the key of the row
     * @throws InternalBackEndException 
     */
    void deleteRow(String nameOfColumnFamily, String key) throws InternalBackEndException{ 
    	
    	ByteBuffer bkey = ByteBuffer.wrap(key.getBytes());
    	Map<String, List<Mutation>> keyRow = buffer.get(bkey);
    	Map<String, List<String>> insertionSCFKey = superColumnFamilyInsertions.get(nameOfColumnFamily);
    	Map<String, Object> insertionCFKey = columnFamilyInsertions.get(nameOfColumnFamily);
        
        if(keyRow != null) {
        	// delete all previous operations referring the key
        	buffer.put(bkey, null);
        }
        if(insertionSCFKey != null) {
        	insertionSCFKey.put(key,null);
        }
        if(insertionCFKey != null) {
        	insertionCFKey.put(key,null);
        }
        Deletion deletion = new Deletion();
        deletion.predicate = null; // NOTE: Deletion does not yet support SliceRange predicate (Cassandra 0.7.2)
        deletion.timestamp = System.currentTimeMillis();

        Mutation mutation = new Mutation();
        mutation.deletion = deletion;
        
        List<Mutation> mutationList = new ArrayList<Mutation>();
        mutationList.add(mutation);
        
        Map<String, List<Mutation>> m = new HashMap<String, List<Mutation>>();
        m.put(nameOfColumnFamily, mutationList);

        buffer.put(bkey, m);
    }
   
    List<String> readSuperColummFamily(String nameOfSuperColumnFamily,String key) throws InternalBackEndException  {
        List<String> cass = readSuperColummFamilyFromCassandra(nameOfSuperColumnFamily, key);
        List<String> buff = readSuperColummFamilyFromBuffer(nameOfSuperColumnFamily, key);
        VerdictId buffItemId;
        VerdictId cassItemId;
        if(nameOfSuperColumnFamily.equals("TimeOrderVerdictList")){
            for(String buffItem : buff){
                try {
                    buffItemId = VerdictId.parseString(buffItem);
                    for(int i = 0;i< cass.size();i++){
                        String cassItem = cass.get(i);
                        cassItemId = VerdictId.parseString(cassItem);
                        if(buffItemId.getTime() < cassItemId.getTime()){
                            cass.add(i, buffItem);
                            break;
                        }
                        else if(buffItemId.getTime() == cassItemId.getTime())
                            cass.set(i, buffItem);
                            break;
                    }
                } catch (Exception ex) {
                    Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
                }
            }
            return cass;
        }
        else{
            cass.addAll(buff);
            return Arrays.asList(new TreeSet<String>(cass).toArray(new String[0]));
        }
    }
            
    void flush() throws InternalBackEndException{
        wrapper.batchMutate(buffer, ConsistencyLevel.ONE);
        
        //empty the buffer
        buffer = new HashMap<ByteBuffer, Map<String, List<Mutation>>>();
        columnFamilyInsertions = new HashMap<String, Map<String, Object>>();
        superColumnFamilyInsertions = new HashMap<String, Map<String, List<String>>>();
    }
    
    void clear() {
    	buffer = new HashMap<ByteBuffer, Map<String, List<Mutation>>>();
	columnFamilyInsertions = new HashMap<String, Map<String, Object>>();
        superColumnFamilyInsertions = new HashMap<String, Map<String, List<String>>>();
    }
    
    public static void main(String a[]) throws IllegalArgumentException, IllegalAccessException, NoSuchFieldException, InternalBackEndException, ClassNotFoundException, InstantiationException, InvocationTargetException, Exception{
        final WrapperConfiguration conf = new WrapperConfiguration(new File(Constants.CONFIGURATION_FILE_PATH));
        
        final String listenAddress = conf.getCassandraListenAddress();
        final int thriftPort = conf.getThriftPort();

	System.out.println("Connection information:");
	System.out.println("\tListen Address: " + listenAddress);
	System.out.println("\tThrift Port   : " + thriftPort);
	System.out.println("\n");

	final CassandraWrapper wrapper = new CassandraWrapper(listenAddress, thriftPort);
        
        System.out.println("Opening Cassandra connection...");
//        wrapper.open();
        
        wrapper.set_keyspace(Constants.KEYSPACE);
        
        CassandraQueryFacade x = new CassandraQueryFacade(wrapper);
        CassandraDescTable desc = CassandraDescTable.getNewInstance();
        
        
        UserApplicationConsumer c = new UserApplicationConsumer();
        c.setApplicationId("app1");
        c.setOutcomeList("outcomel");
        c.setScore(1);
        c.setSize(4);
        c.setUserApplicationConsumerId(desc.generateKeyForColumnFamily(c));
        c.setUserId("user1");
        c.setVerdictList_userCharging("ccc");
        
        x.insertDbTableObject(c);
                                    
        Verdict verd = new Verdict();
        verd.setJudgeId("user1");
        verd.setChargedId("char1");
        verd.setApplicationId("app1");
        verd.setTime(101);
        verd.setIsJudgeProducer(true);
        verd.setVerdictAggregationList("listAgg");
        verd.setVote(0.8);

        verd.setVerdictId(desc.generateKeyForColumnFamily(verd));              
        x.insertDbTableObject(verd);
        
        x.insertIntoList("TimeOrderVerdictList","ccc" ,desc.generateKeyForColumnFamily(verd));
        
        Verdict verd2 = new Verdict();
        verd2.setJudgeId("user2");
        verd2.setChargedId("char2");
        verd2.setApplicationId("app1");
        verd2.setTime(110);
        verd2.setIsJudgeProducer(true);
        verd2.setVerdictAggregationList("listAgg");
        verd2.setVote(0.8);
        
        verd2.setVerdictId(desc.generateKeyForColumnFamily(verd2));

        x.insertIntoList("AuxOrderVerdictList", "agg" ,verd.getVerdictId() );
        x.insertIntoList("AuxOrderVerdictList", "agg" ,verd2.getVerdictId() );
        
        x.insertDbTableObject(verd2);
        x.flush();
        
        System.out.println("-----------------");
        
        System.out.println(x.loadRow("UserApplicationConsumer", "sssss"));
        System.out.println("---1---");
        System.out.println(x.readSuperColummFamily("TimeOrderVerdictList","ccc"));
        System.out.println(x.loadTable("Verdict"));
        System.out.println(x.getListOfObjectFromListOfKeys(x.readSuperColummFamily("TimeOrderVerdictList","ccc"),"Verdict"));
        
        System.out.println("-----------------");
        
        System.out.println(x.descTable.generateKeyForColumnFamily(verd));
        System.out.println(x.descTable.generateKeyForSuperColumnItem(verd, "TimeOrderVerdictList"));

	System.out.println("-----------------");
        
        System.err.println(x.descTable.getReferredTable("TimeOrderVerdictList", "verdictId"));
        System.out.println(x.descTable.getUniqueFields("Verdict"));

        System.out.println("-- aux--");
        System.out.println(x.readSuperColummFamily("AuxOrderVerdictList","agg"));
        
        System.out.println("-----------------");
        
        System.out.println(x.loadTable("UserApplicationConsumer"));
        System.out.println("---2---");
        System.out.println(x.getListOfObjectFromListOfKeys(x.readSuperColummFamily("TimeOrderVerdictList","ccc"),"Verdict"));
        
        System.out.println("-----------------");
        
        System.out.println(x.descTable.generateKeyForColumnFamily(verd));
        System.out.println("---3---");
        System.out.println(x.descTable.generateKeyForSuperColumnItem(verd, "TimeOrderVerdictList"));
        
        System.out.println("-----------------");
        
        System.err.println(x.descTable.getReferredTable("TimeOrderVerdictList", "verdictId"));
        System.out.println(x.descTable.getUniqueFields("Verdict"));
        
        System.out.println("------del agg --------");
        x.deleteRow("AuxOrderVerdictList", "agg");
        x.flush(); // if we remove this flush the following read returns correct values, FIX ?
        System.out.println("------read agg --------");
        System.out.println(x.readSuperColummFamily("AuxOrderVerdictList","agg"));
        
        System.out.println("------ deferred commit --------");

        Verdict verd3 = new Verdict();
        verd3.setJudgeId("user1");
        verd3.setChargedId("char1");
        verd3.setApplicationId("app1");
        verd3.setTime(System.currentTimeMillis());
        verd3.setIsJudgeProducer(true);
        verd3.setVerdictAggregationList("listAgg");
        verd3.setVote(0.6);
        verd3.setVerdictId(desc.generateKeyForColumnFamily(verd3));
    
        x.insertDbTableObject(verd3);

        //x.insertIntoList("AuxOrderVerdictList", "agg1" ,"ver2" ,"ver2" );
        //x.insertIntoList("AuxOrderVerdictList", "agg1" ,"ver3" ,"ver3" );
        x.deleteRow("AuxOrderVerdictList", "agg1");

        x.flush();
        
    }
    
}
