/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.db.facade;

import java.lang.reflect.Field;
import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;
import java.nio.ByteBuffer;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
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
import com.mymed.controller.core.manager.reputation.db.table.ICassandraPersistable;
import com.mymed.controller.core.manager.reputation.globals.Constants;
//import com.mymed.controller.core.manager.reputation.primary_key.VerdictId;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;
import com.mymed.utils.ClassType;
import com.mymed.utils.MConverter;

/**
 * This class wraps different Cassandra Insert Queries
 * 
 * @author piccolo
 */
public class CassandraQueryFacade {

    Map<ByteBuffer, Map<String, List<Mutation>>> buffer;
    Map<String, Map<String, Object>> columnFamilyInsertions;
    Map<String, Map<String, List<String>>> superColumnFamilyInsertions;
    CassandraWrapper wrapper;
    CassandraDescTable descTable = CassandraDescTable.getNewInstance();
    private static CassandraQueryFacade instance;

    /**
     * this methods returns a reference to the manager of all the queries
     * submitted to Cassandra
     *
     * @param w
     * @return
     */
    static CassandraQueryFacade getInstance(final CassandraWrapper w) {
        if (instance == null) {
            instance = new CassandraQueryFacade(w);
        }
        return instance;
    }

    private CassandraQueryFacade(final CassandraWrapper w) {
        wrapper = w;
        buffer = new HashMap<ByteBuffer, Map<String, List<Mutation>>>();
        columnFamilyInsertions = new HashMap<String, Map<String, Object>>();
        superColumnFamilyInsertions = new HashMap<String, Map<String, List<String>>>();
    }

    /**
     * @return true if the buffer is empty, false otherwise
     */
    boolean isEmpty() {
        return buffer == null || buffer.isEmpty();
    }

    /**
     * This methods inserts an object corresponding to a row of a ColumnFamily
     * into the buffer
     *
     * @param objToInsert
     *          the object to insert
     */
    void insertDbTableObjectOld(final Object objToInsert) {
        boolean Debug = true;
        try {
            if (Debug) {
                System.out.println("insertDbTableObject: " + objToInsert);
            }
            // retrieving the name of the ColumnFamily from the name of the (dynamic)
            // type of the object
            final String[] objParts = objToInsert.getClass().getName().split("\\.");
            final String columnFamilyName = objParts[objParts.length - 1];

            // retrieving the name of the primary key field
            final String primaryKeyFieldName = descTable.getNameOfPrimaryKeyField(columnFamilyName);

            if (Debug) {
                System.out.println("ColumnFamilyName: " + columnFamilyName + " keyField: " + primaryKeyFieldName);
            }

            final List<Mutation> row = new ArrayList<Mutation>();

            // retrieving the name of the corresponding "Id" class in the package
            // "primary_key"
            // and the methods allowing us to make the translation from the string
            // representation to
            // the bytebuffer representation
            final Class<?> idClass = Class.forName(Constants.PRIMARY_KEY_PACKAGE + "." + columnFamilyName + "Id");
            final Method stringToObj = idClass.getMethod("parseString", new Class[]{String.class});
            final Method objToByteBuffer = idClass.getMethod("get" + columnFamilyName + "Id" + "AsByteBuffer", new Class[0]);
            Object idInstance;
            ByteBuffer pkByteBuffer = null;

            for (final Field field : objToInsert.getClass().getDeclaredFields()) {

                field.setAccessible(true);
                final ColumnOrSuperColumn currentCol = new ColumnOrSuperColumn();

                final ClassType type = ClassType.inferTpye(field.getType());

                if (Debug) {
                    System.out.println("  field: " + field + " type: " + type);
                }
                // if the current field is the primary key field, then
                // we use the retrieved translation methods to generate the bytebuffer
                // representation
                if (field.getName().equals(primaryKeyFieldName)) {
                    idInstance = stringToObj.invoke(null, field.get(objToInsert));
                    pkByteBuffer = (ByteBuffer) objToByteBuffer.invoke(idInstance, new Object[0]);
                    currentCol.setColumn(new Column(ByteBuffer.wrap(field.getName().getBytes()), pkByteBuffer, System.currentTimeMillis()));
                } else { // otherwise, we use the usual methodology
                    currentCol.setColumn(new Column(ByteBuffer.wrap(field.getName().getBytes()), ByteBuffer.wrap(ClassType.objectToByteArray(type, field.get(objToInsert))), System.currentTimeMillis()));
                }
                final Mutation m = new Mutation();
                m.setColumn_or_supercolumn(currentCol);
                row.add(m);
            }

            // putting the value in the buffer with the key specified by the variable
            // pkByteBuffer
            // which has been initalized by the previous cycle
            Map<String, List<Mutation>> keyRow = buffer.get(pkByteBuffer);
            if (keyRow == null) {
                keyRow = new HashMap<String, List<Mutation>>();
            }

            List<Mutation> currList = keyRow.get(columnFamilyName);
            if (currList == null) {
                currList = new ArrayList<Mutation>();
            }
            if (Debug) {
                System.out.println("keyrow: " + keyRow + " currList: " + currList);
            }
            currList.addAll(row);
            keyRow.put(columnFamilyName, currList);
            buffer.put(pkByteBuffer, keyRow);

            // putting the value also in the buffer of columnfamilyinsertions
            Map<String, Object> insertionMapKey = columnFamilyInsertions.get(columnFamilyName);
            if (insertionMapKey == null) {
                insertionMapKey = new HashMap<String, Object>();
            }
            final Field pkField = objToInsert.getClass().getDeclaredField(primaryKeyFieldName);
            pkField.setAccessible(true);
            final String pkValue = (String) pkField.get(objToInsert);
            insertionMapKey.put(pkValue, objToInsert);
            columnFamilyInsertions.put(columnFamilyName, insertionMapKey);
        } catch (final NoSuchMethodException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final SecurityException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final ClassNotFoundException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final InvocationTargetException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final NoSuchFieldException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final IllegalArgumentException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final IllegalAccessException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    /**
     * This methods inserts an object corresponding to a row of a ColumnFamily
     * into the buffer
     *
     * @param objToInsert
     *          the object to insert
     */
    void insertDbTableObject(final ICassandraPersistable obj) {
        boolean Debug = false;
        try {
            if (Debug) {
                System.out.println("insertDbTableObject: " + obj);
            }
            final String columnFamilyName = obj.getColumnFamilyName();

            final List<Mutation> row = new ArrayList<Mutation>();

            String pkKey = obj.getKey();
            ByteBuffer pkByteBuffer = ByteBuffer.wrap(pkKey.getBytes());

            for (final Field field : obj.getClass().getDeclaredFields()) {

                field.setAccessible(true);
                final ColumnOrSuperColumn currentCol = new ColumnOrSuperColumn();

                final ClassType type = ClassType.inferTpye(field.getType());

                if (Debug) {
                    System.out.println("  field: " + field + " type: " + type);
                }
                if (type == null) continue;  // don't crash if non-primitive type, just skip
                currentCol.setColumn(new Column(ByteBuffer.wrap(field.getName().getBytes()), ByteBuffer.wrap(ClassType.objectToByteArray(type, field.get(obj))), System.currentTimeMillis()));
                final Mutation m = new Mutation();
                m.setColumn_or_supercolumn(currentCol);
                row.add(m);
            }

            // putting the value in the buffer with the key specified by the variable
            // pkByteBuffer which has been initalized by the previous cycle
            Map<String, List<Mutation>> keyRow = buffer.get(pkByteBuffer);
            if (keyRow == null) {
                keyRow = new HashMap<String, List<Mutation>>();
            }

            List<Mutation> currList = keyRow.get(columnFamilyName);
            if (currList == null) {
                currList = new ArrayList<Mutation>();
            }
            if (Debug) {
                System.out.println("keyrow: " + keyRow + " currList: " + currList);
            }
            currList.addAll(row);
            keyRow.put(columnFamilyName, currList);
            buffer.put(pkByteBuffer, keyRow);

            // putting the value also in the buffer of columnfamilyinsertions
            Map<String, Object> insertionMapKey = columnFamilyInsertions.get(columnFamilyName);
            if (insertionMapKey == null) {
                insertionMapKey = new HashMap<String, Object>();
            }
            insertionMapKey.put(pkKey, obj);
            columnFamilyInsertions.put(columnFamilyName, insertionMapKey);
        } catch (final SecurityException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final IllegalArgumentException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final IllegalAccessException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    /**
     * This method adds a key pointing to a row of a ColumnFamily into a list
     * stored in a SuperColumnFamily
     *
     * @param superColumnFamilyName
     *          the name of the SuperColumFamily
     * @param keyOfList
     *          the key of the list
     * @param cfKey
     *          the key pointing to the row of the ColumnFamily, which will be a
     *          node of the list
     *
     */
    void insertIntoList(final String superColumnFamilyName, final String keyOfList, final String cfKey) {
        System.out.println("insertIntoList " + superColumnFamilyName + "," + keyOfList + "," + cfKey);
        try {
            final NodeList listAttributes = descTable.getListOfAttribute(superColumnFamilyName);

            Map<String, List<Mutation>> keySuperRow = buffer.get(ByteBuffer.wrap(keyOfList.getBytes()));
            if (keySuperRow == null) {
                keySuperRow = new HashMap<String, List<Mutation>>();
            }

            List<Mutation> superRow = keySuperRow.get(superColumnFamilyName);
            if (superRow == null) {
                superRow = new ArrayList<Mutation>();
            }

            final Mutation m = new Mutation();
            final ColumnOrSuperColumn c = new ColumnOrSuperColumn();
            final List<Column> columns = new ArrayList<Column>();

            // in the xml file there is only one attribute for the supercolumnfamily
            if (listAttributes != null && listAttributes.getLength() == 1) {
                final Element uniqueAttribute = (Element) listAttributes.item(0);
                final String columnFamilyName = uniqueAttribute.getAttribute("references");
                System.out.println("  columnFamilyName: " + columnFamilyName);

                final Class<?> idClass = Class.forName(Constants.PRIMARY_KEY_PACKAGE + "." + columnFamilyName + "Id");
                final Method stringToObj = idClass.getMethod("parseString", new Class[]{String.class});
                final Method objToByteBuffer = idClass.getMethod("get" + columnFamilyName + "Id" + "AsByteBuffer", new Class[0]);

                final Object idInstance = stringToObj.invoke(null, cfKey);
                final ByteBuffer pkByteBuffer = (ByteBuffer) objToByteBuffer.invoke(idInstance, new Object[0]);

                System.out.println(" first arg to Column const: " + uniqueAttribute.getFirstChild().getNodeValue());
                columns.add(new Column(ByteBuffer.wrap(uniqueAttribute.getFirstChild().getNodeValue().getBytes()),
                        pkByteBuffer, System.currentTimeMillis()));

                final SuperColumn sc = new SuperColumn(pkByteBuffer, columns);
                c.setSuper_column(sc);
                m.setColumn_or_supercolumn(c);
                superRow.add(m);
                keySuperRow.put(superColumnFamilyName, superRow);

                buffer.put(ByteBuffer.wrap(keyOfList.getBytes()), keySuperRow);

                // putting the item also in the buffer of supercolumnfamily
                Map<String, List<String>> insertionKey = superColumnFamilyInsertions.get(superColumnFamilyName);
                if (insertionKey == null) {
                    insertionKey = new HashMap<String, List<String>>();
                }
                List<String> listOfKeys = insertionKey.get(keyOfList);
                if (listOfKeys == null) {
                    listOfKeys = new ArrayList<String>();
                }
                listOfKeys.add(cfKey);
                insertionKey.put(keyOfList, listOfKeys);
                superColumnFamilyInsertions.put(superColumnFamilyName, insertionKey);
            } else {
                throw new RuntimeException("there must be attributes");
            }
        } catch (final IllegalAccessException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final IllegalArgumentException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final InvocationTargetException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final NoSuchMethodException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final SecurityException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final ClassNotFoundException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    void insertIntoListNew(final String superColumnFamilyName, final String keyOfList, final String cfKey) {
        try {
            final NodeList listAttributes = descTable.getListOfAttribute(superColumnFamilyName);

            Map<String, List<Mutation>> keySuperRow = buffer.get(ByteBuffer.wrap(keyOfList.getBytes()));
            if (keySuperRow == null) {
                keySuperRow = new HashMap<String, List<Mutation>>();
            }

            List<Mutation> superRow = keySuperRow.get(superColumnFamilyName);
            if (superRow == null) {
                superRow = new ArrayList<Mutation>();
            }

            final Mutation m = new Mutation();
            final ColumnOrSuperColumn c = new ColumnOrSuperColumn();

            final List<Column> columns = new ArrayList<Column>();

            ByteBuffer cfKeyAsByteBuffer = ByteBuffer.wrap(cfKey.getBytes());


            columns.add(new Column(ByteBuffer.wrap("foo".getBytes()),
                    cfKeyAsByteBuffer, System.currentTimeMillis()));

            final SuperColumn sc = new SuperColumn(cfKeyAsByteBuffer, columns);
            c.setSuper_column(sc);
            m.setColumn_or_supercolumn(c);
            superRow.add(m);
            keySuperRow.put(superColumnFamilyName, superRow);

            buffer.put(ByteBuffer.wrap(keyOfList.getBytes()), keySuperRow);

            // putting the item also in the buffer of supercolumnfamily
            Map<String, List<String>> insertionKey = superColumnFamilyInsertions.get(superColumnFamilyName);
            if (insertionKey == null) {
                insertionKey = new HashMap<String, List<String>>();
            }
            List<String> listOfKeys = insertionKey.get(keyOfList);
            if (listOfKeys == null) {
                listOfKeys = new ArrayList<String>();
            }
            listOfKeys.add(cfKey);
            insertionKey.put(keyOfList, listOfKeys);
            superColumnFamilyInsertions.put(superColumnFamilyName, insertionKey);
        } catch (final IllegalArgumentException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final SecurityException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    void insertIntoVerdictListOrdered(final String repEntity,  java.util.UUID timeUuid, double value) throws InternalBackEndException {
        ColumnParent colFamily = new ColumnParent("VerdictsTimeOrdered");
        Column col = new Column();
        col.setName(com.mymed.utils.TimeUuid.asByteArray(timeUuid));
        ByteBuffer dbl = ByteBuffer.allocate(Double.SIZE);
        dbl.asDoubleBuffer().put(value);
        col.setValue(dbl);
        col.setTimestamp(System.currentTimeMillis());
        wrapper.insert(repEntity, colFamily, col, ConsistencyLevel.ONE);
    }

    List <ColumnOrSuperColumn> getVerdictListColumns(final String repEntity) throws InternalBackEndException {
        return getVerdictListColumns(repEntity, 100);
    }

    List <ColumnOrSuperColumn> getVerdictListColumns(final String repEntity, int numResults) throws InternalBackEndException {
        ColumnParent colFamily = new ColumnParent("VerdictsTimeOrdered");
        final SlicePredicate predicate = new SlicePredicate();
        final SliceRange sliceRange = new SliceRange();
        sliceRange.setStart(new byte[0]);
        sliceRange.setFinish(new byte[0]);
        sliceRange.setReversed(true);
        sliceRange.setCount(numResults);
        predicate.setSlice_range(sliceRange);
        List<ColumnOrSuperColumn> cols = wrapper.get_slice(repEntity, colFamily,predicate, ConsistencyLevel.ONE);
        return cols;
    }

    /**
     * This method loads into a bean a row of a ColumnFamily having the supplied
     * key which has been already persisted in Cassandra
     *
     * @param nameOfColumnFamily
     *          the name of the ColumnFamily
     * @param key
     *          the key
     * @return the corresponding object or null if the key is not found
     * @throws InternalBackEndException
     */
    private Object loadRowFromCassandra(final String nameOfColumnFamily, final String key)
            throws InternalBackEndException {
        try {
            final SlicePredicate predicate = new SlicePredicate();
            final SliceRange sliceRange = new SliceRange();
            sliceRange.setStart(new byte[0]);
            sliceRange.setFinish(new byte[0]);
            predicate.setSlice_range(sliceRange);
            final ColumnParent parent = new ColumnParent(nameOfColumnFamily);
            
            // retrieving the value of the supplied key
            final List<ColumnOrSuperColumn> result = wrapper.get_slice(key, parent, predicate, ConsistencyLevel.ONE);

            if (result.size() > 0) {
                // retrieving the type of the bean where loading the retrieved
                // information
                final Class<?> chosenTable = Class.forName(Constants.DB_TABLE_PACKAGE + "." + nameOfColumnFamily);
                final Object newInstance = chosenTable.newInstance();

                // loading the value into the corresponding object
                for (final ColumnOrSuperColumn c : result) {
                    final Field f = chosenTable.getDeclaredField(MConverter.byteBufferToString(c.column.name));
                    f.setAccessible(true);

                    final ClassType type = ClassType.inferTpye(f.getType());
                    final Object value = ClassType.objectFromClassType(type, c.column.getValue());

                    f.set(newInstance, value);
                }
                return newInstance;
            } else {
                return null;
            }
        } catch (final NoSuchFieldException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final SecurityException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final InstantiationException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final IllegalAccessException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final ClassNotFoundException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final IllegalArgumentException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }
        return null;
    }

    /**
     * this method load into a bean a row of a ColumnFamily having the specified
     * key, which has not yet been persisted into Cassandra
     *
     * @param nameOfColumnFamily
     *          the name of the ColumnFamily
     * @param key
     *          the supplied key
     * @return the corresponding object or null if the key is not found
     */
    private Object loadRowFromBuffer(final String nameOfColumnFamily, final String key) {
        final Map<String, Object> insertionKey = columnFamilyInsertions.get(nameOfColumnFamily);
        if (insertionKey != null) {
            return insertionKey.get(key);
        } else {
            return null;
        }
    }

    /**
     * this methods loads the value of the supplied key of a ColumnFamily into an
     * object
     *
     * @param nameOfColumnFamily
     *          the name of the ColumnFamily
     * @param key
     *          the name of the key
     * @return the corresponding object or null if the key is not found
     * @throws InternalBackEndException
     */
    Object loadRow(final String nameOfColumnFamily, final String key) throws InternalBackEndException {
        // we read first from the buffer
        Object loadedValue = loadRowFromBuffer(nameOfColumnFamily, key);

        // if the load fails then we load from Cassandra
        if (loadedValue == null) {
            loadedValue = loadRowFromCassandra(nameOfColumnFamily, key);
            return loadedValue;
        } else {
            return loadedValue;
        }
    }

    /**
     * this method returns the collection of rows constituting the given
     * ColumFamily, loading them from Cassandra
     *
     * @param columnFamilyName
     *          the name of the ColumnFamily
     * @return the collection of objects
     * @throws InternalBackEndException
     */
    private List<Object> loadTableFromCassandra(final String columnFamilyName) throws InternalBackEndException {
        try {
            final SlicePredicate predicate = new SlicePredicate();
            final SliceRange sliceRange = new SliceRange();
            sliceRange.setStart(new byte[0]);
            sliceRange.setFinish(new byte[0]);
            predicate.setSlice_range(sliceRange);
            final ColumnParent parent = new ColumnParent(columnFamilyName);

            // setting the slice range allowing us to read the entire ColumnFamily
            final KeyRange totRange = new KeyRange();
            totRange.setStart_key(new byte[0]);
            totRange.setEnd_key(new byte[0]);

            // retrieving the result
            final List<KeySlice> _range_slices = wrapper.get_range_slices(parent, predicate, totRange, ConsistencyLevel.ONE);

            // loading the result into a list of beans
            final List<Object> resultList = new ArrayList<Object>();
            for (final KeySlice keySlice : _range_slices) {
                final List<ColumnOrSuperColumn> colList = keySlice.columns;
                final Class<?> chosenTable = Class.forName(Constants.DB_TABLE_PACKAGE + "." + columnFamilyName);
                final Object newInstance = chosenTable.newInstance();
                for (final ColumnOrSuperColumn c : colList) {
                    final Field f = chosenTable.getDeclaredField(MConverter.byteBufferToString(c.column.name));
                    f.setAccessible(true);

                    final ClassType type = ClassType.inferTpye(f.getType());
                    final Object value = ClassType.objectFromClassType(type, c.column.getValue());

                    f.set(newInstance, value);
                }
                resultList.add(newInstance);
            }

            return resultList;
        } catch (final ClassNotFoundException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final InstantiationException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final IllegalAccessException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final NoSuchFieldException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final SecurityException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final IllegalArgumentException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }
        return new ArrayList<Object>();
    }

    /**
     * this method returns the collection of rows constituting the given
     * ColumFamily, loading them from the buffer
     *
     * @param columnFamilyName
     *          the name of the ColumnFamily
     * @return the collection of objects
     * @throws InternalBackEndException
     * @throws ClassNotFoundException
     * @throws InstantiationException
     * @throws IllegalAccessException
     * @throws NoSuchFieldException
     */
    private List<Object> loadTableFromBuffer(final String columnFamilyName) {
        final Map<String, Object> insertionKey = columnFamilyInsertions.get(columnFamilyName);
        if (insertionKey != null) {
            return new ArrayList<Object>(insertionKey.values());
        }
        return new ArrayList<Object>();
    }

    /**
     * this method returns the collection of rows constituting the given
     * ColumFamily
     *
     * @param columnFamilyName
     *          the name of the ColumnFamily
     * @return the collection of objects
     * @throws InternalBackEndException
     * @throws ClassNotFoundException
     * @throws InstantiationException
     * @throws IllegalAccessException
     * @throws NoSuchFieldException
     */
    List<Object> loadTable(final String columnFamilyName) throws InternalBackEndException {
        try {
            // getting the data from Cassandra
            final List<Object> loadTableFromCassandra = loadTableFromCassandra(columnFamilyName);

            // retrieving the ones coming from the buffer (if any)
            final List<Object> loadTableFromBuffer = loadTableFromBuffer(columnFamilyName);

            // retrieving the name of the primary key
            final String primaryKeyFieldName = descTable.getNameOfPrimaryKeyField(columnFamilyName);
            final Class<?> chosenTable = Class.forName(Constants.DB_TABLE_PACKAGE + "." + columnFamilyName);
            final Field pkField = chosenTable.getDeclaredField(primaryKeyFieldName);
            pkField.setAccessible(true);

            // merging the two
            final List<Object> result = new ArrayList<Object>();
            for (final Object fromCassandra : loadTableFromCassandra) {
                Object toAdd = fromCassandra;
                for (final Object fromBuffer : loadTableFromBuffer) {
                    if (pkField.get(toAdd).equals(pkField.get(fromBuffer))) {
                        toAdd = fromBuffer;
                        loadTableFromBuffer.remove(fromBuffer);
                        break;
                    }
                }
                result.add(toAdd);
            }
            result.addAll(loadTableFromBuffer);
            return result;
        } catch (final NoSuchFieldException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final SecurityException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final ClassNotFoundException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final IllegalAccessException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }
        return new ArrayList<Object>();
    }

    /**
     * this methods returns the list of objects corresponding to a given list of
     * keys
     *
     * @param keys
     *          the list of keys
     * @param columnFamilyName
     *          the name of the columnfamily in which the rows are stored
     * @return the list of objects corresponding to the keys
     * @throws InternalBackEndException
     */
    List<Object> getListOfObjectFromListOfKeys(final List<String> keys, final String columnFamilyName)
            throws InternalBackEndException {
        try {
            final SlicePredicate predicate = new SlicePredicate();
            final SliceRange sliceRange = new SliceRange();
            sliceRange.setStart(new byte[0]);
            sliceRange.setFinish(new byte[0]);
            predicate.setSlice_range(sliceRange);
            final ColumnParent parent = new ColumnParent(columnFamilyName);
            final Map<ByteBuffer, List<ColumnOrSuperColumn>> multiget_slice = wrapper.multiget_slice(keys, parent, predicate,
                    ConsistencyLevel.ONE);

            final List<Object> result = new ArrayList<Object>();
            for (final String key : keys) {
                final Class<?> chosenTable = Class.forName(Constants.DB_TABLE_PACKAGE + "." + columnFamilyName);
                Object newInstance = null;
                // we first load from buffer
                final Map<String, Object> insertionKey = columnFamilyInsertions.get(columnFamilyName);
                if (insertionKey != null) {
                    newInstance = insertionKey.get(key);
                }

                // if nothing is found then we load from cassandra
                if (newInstance == null) {
                    newInstance = chosenTable.newInstance();

                    final List<ColumnOrSuperColumn> columns = multiget_slice.get(ByteBuffer.wrap(key.getBytes()));

                    for (final ColumnOrSuperColumn c : columns) {
                        final String fieldName = MConverter.byteBufferToString(ByteBuffer.wrap(c.column.getName()));
                        final Field f = chosenTable.getDeclaredField(fieldName);
                        f.setAccessible(true);

                        final ClassType type = ClassType.inferTpye(f.getType());
                        final Object value = ClassType.objectFromClassType(type, c.column.getValue());

                        f.set(newInstance, value);
                    }
                }
                result.add(newInstance);
            }
            return result;
        } catch (final ClassNotFoundException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final InstantiationException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final IllegalAccessException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final NoSuchFieldException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final SecurityException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final IllegalArgumentException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        }
        return new ArrayList<Object>();
    }

    /**
     * This methods returns the list of keys stored in a SuperColumnFamily
     * corresponding to a given list, loading it from Cassandra
     *
     * @param nameOfSuperColumnFamily
     *          the name of the SuperColumnFamily
     * @param key
     *          the key of the list
     * @return
     * @throws InternalBackEndException
     */
    private List<String> readSuperColummFamilyFromCassandra(final String nameOfSuperColumnFamily, final String key)
            throws InternalBackEndException {
        try {
            final SlicePredicate predicate = new SlicePredicate();
            final SliceRange sliceRange = new SliceRange();
            sliceRange.setStart(new byte[0]);
            sliceRange.setFinish(new byte[0]);

            // i verdetti ordinati per tempo devono andare dal piu' recente al piu'
            // antico
            if (nameOfSuperColumnFamily.equals("TimeOrderVerdictList")) {
                sliceRange.setReversed(true);
            }

            predicate.setSlice_range(sliceRange);
            final ColumnParent parent = new ColumnParent(nameOfSuperColumnFamily);

            final List<ColumnOrSuperColumn> result = wrapper.get_slice(key, parent, predicate, ConsistencyLevel.ONE);
            final List<String> keys = new ArrayList<String>();

            final NodeList nl = descTable.getListOfAttribute(nameOfSuperColumnFamily);

            for (final ColumnOrSuperColumn c : result) {
                final SuperColumn sc = c.super_column;
                final List<Column> columns = sc.getColumns();
                for (final Column col : columns) {
                    final String colName = MConverter.byteBufferToString(col.name);
                    for (int i = 0; i < nl.getLength(); i++) {
                        final Element e = (Element) nl.item(i);
                        if (e.hasAttribute("key") && e.getAttribute("key").equals("foreign")) {
                            if (e.getFirstChild().getNodeValue().equals(colName)) {
                                final String columnFamilyName = e.getAttribute("references");
                                final Class<?> idClass = Class.forName(Constants.PRIMARY_KEY_PACKAGE + "." + columnFamilyName + "Id");
                                final Method byteBufferToObj = idClass.getMethod("parseByteBuffer", new Class[]{ByteBuffer.class});

                                keys.add(byteBufferToObj.invoke(null, col.value).toString());
                            }
                        }
                    }
                }
            }
            return keys;
        } catch (final IllegalAccessException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final IllegalArgumentException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final InvocationTargetException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final NoSuchMethodException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final SecurityException ex) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
        } catch (final ClassNotFoundException e) {
            Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, e);
        }
        return new ArrayList<String>();
    }

    /**
     * This methods returns the list of keys stored in a SuperColumnFamily
     * corresponding to a given list, loading it from Cassandra
     *
     * @param nameOfSuperColumnFamily
     *          the name of the SuperColumnFamily
     * @param key
     *          the key of the list
     * @return
     */
    private List<String> readSuperColummFamilyFromBuffer(final String nameOfSuperColumnFamily, final String key) {
        final Map<String, List<String>> insertionKey = superColumnFamilyInsertions.get(nameOfSuperColumnFamily);

        if (insertionKey != null) {
            final List<String> resultList = insertionKey.get(key);
            if (resultList != null) {
                return resultList;
            }
        }

        return new ArrayList<String>();
    }

    /**
     * This method deletes a row of a columnfamily
     *
     * @param nameOfColumnFamily
     *          the name of the columnfamily
     * @param key
     *          the key of the row
     * @throws InternalBackEndException
     */
    public void deleteRow(final String nameOfColumnFamily, final String key) throws InternalBackEndException {

        final ByteBuffer bkey = ByteBuffer.wrap(key.getBytes());
        final Map<String, List<Mutation>> keyRow = buffer.get(bkey);
        final Map<String, List<String>> insertionSCFKey = superColumnFamilyInsertions.get(nameOfColumnFamily);
        final Map<String, Object> insertionCFKey = columnFamilyInsertions.get(nameOfColumnFamily);

        if (keyRow != null) {
            // delete all previous operations referring the key
            buffer.put(bkey, null);
        }
        if (insertionSCFKey != null) {
            insertionSCFKey.put(key, null);
        }
        if (insertionCFKey != null) {
            insertionCFKey.put(key, null);
        }
        final Deletion deletion = new Deletion();
        deletion.predicate = null; // NOTE: Deletion does not yet support SliceRange
        // predicate (Cassandra 0.7.2)
        deletion.timestamp = System.currentTimeMillis();

        final Mutation mutation = new Mutation();
        mutation.deletion = deletion;

        final List<Mutation> mutationList = new ArrayList<Mutation>();
        mutationList.add(mutation);

        final Map<String, List<Mutation>> m = new HashMap<String, List<Mutation>>();
        m.put(nameOfColumnFamily, mutationList);

        buffer.put(bkey, m);
    }

    /**
     * deletes an item from a SuperColumnFamily
     * @param nameOfSuperColumnFamily
     * @param keyOfList
     * @param key
     */
    public void deleteSuperColumnItem(String nameOfSuperColumnFamily, String keyOfList, String key) {
        //TODO: da implementare
        throw new UnsupportedOperationException("Not yet implemented");
    }

//    public List<String> readSuperColummFamily(final String nameOfSuperColumnFamily, final String key)
//            throws InternalBackEndException {
//        final List<String> cass = readSuperColummFamilyFromCassandra(nameOfSuperColumnFamily, key);
//        final List<String> buff = readSuperColummFamilyFromBuffer(nameOfSuperColumnFamily, key);
//        VerdictId buffItemId;
//        VerdictId cassItemId;
//        if (nameOfSuperColumnFamily.equals("TimeOrderVerdictList")) {
//            for (final String buffItem : buff) {
//                try {
//                    buffItemId = VerdictId.parseString(buffItem);
//                    for (int i = 0; i < cass.size(); i++) {
//                        final String cassItem = cass.get(i);
//                        cassItemId = VerdictId.parseString(cassItem);
//                        if (buffItemId.getTime() < cassItemId.getTime()) {
//                            cass.add(i, buffItem);
//                            break;
//                        } else if (buffItemId.getTime() == cassItemId.getTime()) {
//                            cass.set(i, buffItem);
//                        }
//                        break;
//                    }
//                } catch (final Exception ex) {
//                    Logger.getLogger(CassandraQueryFacade.class.getName()).log(Level.SEVERE, null, ex);
//                }
//            }
//            return cass;
//        } else {
//            cass.addAll(buff);
//            return Arrays.asList(new TreeSet<String>(cass).toArray(new String[0]));
//        }
//    }

    public void flush() throws InternalBackEndException {
        wrapper.batchMutate(buffer, ConsistencyLevel.ONE);

        // empty the buffer
        buffer = new HashMap<ByteBuffer, Map<String, List<Mutation>>>();
        columnFamilyInsertions = new HashMap<String, Map<String, Object>>();
        superColumnFamilyInsertions = new HashMap<String, Map<String, List<String>>>();
    }

    public void clear() {
        buffer = new HashMap<ByteBuffer, Map<String, List<Mutation>>>();
        columnFamilyInsertions = new HashMap<String, Map<String, Object>>();
        superColumnFamilyInsertions = new HashMap<String, Map<String, List<String>>>();
    }
}
