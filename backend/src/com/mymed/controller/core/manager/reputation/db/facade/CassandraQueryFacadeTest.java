/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.db.facade;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.reputation.db.table.ICassandraPersistable;
import com.mymed.controller.core.manager.reputation.db.table.Verdict;
import com.mymed.controller.core.manager.reputation.globals.Constants;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.core.wrappers.cassandra.api07.CassandraWrapper;
import com.mymed.utils.ClassType;
import com.mymed.utils.MConverter;
import com.mymed.utils.TimeUuid;
import java.io.File;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;
import org.junit.After;
import org.junit.AfterClass;
import org.junit.Before;
import org.junit.BeforeClass;
import org.junit.Test;
import org.junit.Ignore;
import static org.junit.Assert.*;

import org.apache.cassandra.thrift.ColumnOrSuperColumn;
import org.apache.cassandra.thrift.ColumnParent;
import org.apache.cassandra.thrift.ConsistencyLevel;
import org.apache.cassandra.thrift.KeyRange;
import org.apache.cassandra.thrift.KeySlice;
import org.apache.cassandra.thrift.Mutation;
import org.apache.cassandra.thrift.SlicePredicate;
import org.apache.cassandra.thrift.SliceRange;
import java.lang.reflect.Field;
import java.lang.reflect.Method;
import java.nio.ByteBuffer;
import java.util.Map;

/**
 *
 * @author peter
 */
public class CassandraQueryFacadeTest {

    private CassandraWrapper wrapper = null;
    final String listenAddress;
    final int thriftPort;

    public CassandraQueryFacadeTest() {
        final WrapperConfiguration conf = new WrapperConfiguration(new File(Constants.CONFIGURATION_FILE_PATH));

        listenAddress = conf.getCassandraListenAddress();
        thriftPort = conf.getThriftPort();
        try {
            wrapper = new CassandraWrapper(listenAddress, thriftPort);
            //wrapper.open();
            wrapper.set_keyspace(Constants.KEYSPACE);
        } catch (InternalBackEndException ex) {
            Logger.getLogger(CassandraQueryFacadeTest.class.getName()).log(Level.SEVERE, null, ex);
        }

    }

    @BeforeClass
    public static void setUpClass() throws Exception {
    }

    @AfterClass
    public static void tearDownClass() throws Exception {
    }

    @Before
    public void setUp() {
    }

    @After
    public void tearDown() {
    }

    /**
     * Helper function: create a Verdict object which can be persisted.
     * @return Verdict object with all necessary fields set
     */
    private Verdict makePersistableVerdict() {
        Verdict v = new Verdict("judged", "charged", 1.2);
        v.setChargedId("u1");
        v.setJudgeId("u2");
        return v;
    }

    /**
     * Test of getNewInstance method, of class CassandraQueryFacade.
     * 
     * test the fact that it is a singleton.
     *
     */
    @Test
    public void testGetNewInstance() {
        System.out.println("getNewInstance");
        // get an instance using object's wrapper
        CassandraQueryFacade instance = CassandraQueryFacade.getInstance(wrapper);
        try {
            // make a new wrapper
            CassandraWrapper wrapper2 = new CassandraWrapper(listenAddress, thriftPort);
            //wrapper.open();
            wrapper2.set_keyspace(Constants.KEYSPACE);
            // get 'another' instance using new wrapper
            CassandraQueryFacade inst2 = CassandraQueryFacade.getInstance(wrapper2);
            // check that they're the same object
            assertTrue(instance == inst2);
        } catch (InternalBackEndException ex) {
            Logger.getLogger(CassandraQueryFacadeTest.class.getName()).log(Level.SEVERE, null, ex);
        }
        // same test, but see if wrapper can be null
        CassandraQueryFacade inst2 = CassandraQueryFacade.getInstance(null);
        assertTrue(instance == inst2);
    }

    /**
     * Test of isEmpty method, of class CassandraQueryFacade.
     *
     * test isEmpty = true, aggiungere qualche Mutation,
     * test isEmpty = false:
     *    1. clear  => true
     *    2. flush => true
     */
    @Test
    public void testIsEmpty() throws InternalBackEndException {
        System.out.println("isEmpty");
        CassandraQueryFacade instance = CassandraQueryFacade.getInstance(wrapper);
        assertTrue(instance.isEmpty());

        Verdict v = makePersistableVerdict();

        instance.insertDbTableObject(v);
        assertFalse(instance.isEmpty());
        instance.clear();
        assertTrue(instance.isEmpty());

        instance.insertDbTableObject(v);
        assertFalse(instance.isEmpty());
        instance.flush();
        assertTrue(instance.isEmpty());
    }

    /**
     * Test that if we delete an object, we don't then read it
     */
    @Ignore
    @Test
    public void testDeleteFromBuffer() throws InternalBackEndException {
        System.out.println("deleteFromBuffer");
        // clear table Verdict
        wrapper.truncate("Verdict");
        // add a verdict
        Verdict v = new Verdict();
        v.setChargedId("u1");
        v.setJudgeId("u2");
        // write to Cassandra
        CassandraQueryFacade instance = CassandraQueryFacade.getInstance(wrapper);
        instance.insertDbTableObject(v);
        instance.flush();
        // now, let's do a delete
        instance.deleteRow("Verdict", v.getKey());
        // now, let's do a read and see what we get
        Object loadRow = instance.loadRow("Verdict", v.getKey());
        // check that it is null
        assertNull(loadRow);
    }

    /**
     * Test of insertDbTableObject method, of class CassandraQueryFacade.
     * TODO: replace static list of columnFamilyNames with dynamic list from DescTable
     *
     * this should probably become a series of tests, because we have to check the
     * consistency that the values returned mirror the state of the system
     */
    @Test
    public void testInsertDbTableObject() {
        System.out.println("insertDbTableObject");
        String[] columnFamilyNames = {"UserApplicationConsumer", "UserApplicationProducer", "Verdict", "VerdictAggregation"};
        try {
            CassandraQueryFacade instance = CassandraQueryFacade.getInstance(wrapper);
            for (int i = 0; i < columnFamilyNames.length; i++) {
                Class dbTable;
                dbTable = Class.forName(Constants.DB_TABLE_PACKAGE + "." + columnFamilyNames[i]);

                // create object and set to random values
                ICassandraPersistable objToInsert = (ICassandraPersistable) dbTable.newInstance();
                for (Field f : dbTable.getDeclaredFields()) {
                    Object value = new Object();
                    System.out.println("f: " + f.getName() + " type: " + f.getType());
                    System.out.println("  classtype: " + ClassType.inferTpye(f.getType()));
                    System.out.println("  canname: " + ClassType.inferType(f.getType().getCanonicalName()));
                    ClassType ct = ClassType.inferTpye(f.getType());
                    if (ct == null) continue;  // don't crash if there is a non-primitive field
                    switch (ct) {
                        case BOOL:
                            if (Math.random() < 0.5) {
                                value = true;
                            } else {
                                value = false;
                            }
                            break;
                        case BYTE:
                            value = (byte) System.currentTimeMillis();
                            break;
                        case DOUBLE:
                            value = Math.random();
                            break;
                        case INT:
                            value = (int) (Math.random() * Integer.MAX_VALUE);
                            break;
                        case LONG:
                            value = System.currentTimeMillis();
                            break;
                        case STRING:
                            value = "pippo";
                    }
                    f.setAccessible(true);
                    f.set(objToInsert, value);
                }
//                CassandraDescTable desc = CassandraDescTable.getNewInstance();
//                Field pkField = dbTable.getDeclaredField(desc.getNameOfPrimaryKeyField(columnFamilyNames[i]));
//                pkField.setAccessible(true);
//                String keyAsString = desc.generateKeyForColumnFamily(objToInsert);
//                pkField.set(objToInsert, keyAsString);

                String keyAsString = objToInsert.getKey();
                // insert into table
                instance.insertDbTableObject(objToInsert);

                // read it back out (points to same object)
                Object sameOb = instance.loadRow(columnFamilyNames[i], keyAsString);
                assertTrue(sameOb == objToInsert);

                // so try flushing first, should be equal() but not ==
                instance.flush();
                Object newOb = instance.loadRow(columnFamilyNames[i], keyAsString);
                assertFalse(newOb == objToInsert);
                assertTrue(newOb.equals(objToInsert));
                System.out.println("OK for object " + objToInsert);

                /*
                Class idClass = Class.forName(Constants.PRIMARY_KEY_PACKAGE + "." + columnFamilyNames[i] + "Id");
                Method stringToObj = idClass.getMethod("parseString", new Class[]{String.class});
                Method objToByteBuffer = idClass.getMethod("get" + columnFamilyNames[i] + "Id" + "AsByteBuffer", new Class[0]);

                Object idInstance = stringToObj.invoke(null, desc.generateKeyForColumnFamily(objToInsert));
                ByteBuffer pkByteBuffer = (ByteBuffer) objToByteBuffer.invoke(idInstance, new Object[0]);

                assertTrue(instance.buffer.containsKey(pkByteBuffer));
                Map<String, List<Mutation>> keyObj = instance.buffer.get(pkByteBuffer);

                assertTrue(keyObj.containsKey(columnFamilyNames[i]));
                List<Mutation> mutations = keyObj.get(columnFamilyNames[i]);

                for (Mutation m : mutations) {
                ColumnOrSuperColumn c = m.column_or_supercolumn;

                Field currField = dbTable.getDeclaredField(MConverter.byteBufferToString(c.column.name));
                currField.setAccessible(true);

                ClassType t = ClassType.inferTpye(currField.getType());
                Object value = ClassType.objectFromClassType(t, c.column.getValue());

                assertEquals(value, currField.get(objToInsert));
                } */
            }
            instance.clear();
        } catch (Exception ex) {
            Logger.getLogger(CassandraQueryFacadeTest.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    /**
     * Test of insertIntoList method, of class CassandraQueryFacade.
     */
    @Ignore
    @Test
    public void testInsertIntoList() throws InternalBackEndException {
        System.out.println("insertIntoList");
        String[] superColumnFamilyNames = {"TimeOrderVerdictList", "AuxOrderVerdictList", "VerdictAggregationList"};
        String[] primaryKeys = {"user1|PK|user2|PK|app1|PK|100", "user1|PK|user2|PK|app1|PK|100", "ppp"};
        CassandraQueryFacade instance = CassandraQueryFacade.getInstance(wrapper);
        for (int i = 0; i < superColumnFamilyNames.length; i++) {
            instance.insertIntoList(superColumnFamilyNames[i], "list" + String.valueOf(i), primaryKeys[i]);

            assertTrue(instance.buffer.containsKey(ByteBuffer.wrap(("list" + String.valueOf(i)).getBytes())));
            Map<String, List<Mutation>> keyVal = instance.buffer.get(ByteBuffer.wrap(("list" + String.valueOf(i)).getBytes()));

            assertTrue(keyVal.containsKey(superColumnFamilyNames[i]));
            List<Mutation> get = keyVal.get(superColumnFamilyNames[i]);
            boolean trovato = false;
            for (Mutation m : get) {
                if (MConverter.byteBufferToString(m.column_or_supercolumn.super_column.name).equals(primaryKeys[i])) {
                    trovato = true;
                    break;
                }
            }
            assertTrue(trovato);
        }
        instance.clear();
    }

  
    /**
     * Test the imported com.eaio.uuid.UUID to see if it
     * really sorts in Cassandra, in conjunction with a
     * TimeUUIDType column
     */
    @Test
    public void timeUuidToOrder() throws InternalBackEndException {
        System.out.println("timeUuidToOrder");
        CassandraQueryFacade cqf = CassandraQueryFacade.getInstance(wrapper);
        String tableName = "VerdictsTimeOrdered";
        // create n timeuuid's
        int N = 100;
        double value = 0.1;
        java.util.UUID[] ids = new java.util.UUID[N];
        for (int i = 0; i < N; i++) {
            ids[i] = TimeUuid.getTimeUUID();
        }
        try {
            // clear table
            wrapper.truncate(tableName);
            for (int judged = 0; judged < 10; judged++) {
                String user = "user_" + judged;
                for (int verdict = 9; verdict >= 0; verdict--) {
                    // put in user's row
                    cqf.insertIntoVerdictListOrdered(user, ids[judged * 10 + verdict], value);
                    // one row to rule them all...
                    cqf.insertIntoVerdictListOrdered("All", ids[judged * 10 + verdict], value);
                    value += 0.1;
                }
            }
        } catch (InternalBackEndException ex) {
            Logger.getLogger(CassandraQueryFacadeTest.class.getName()).log(Level.SEVERE, null, ex);
        }
        // do queries and check results
        for (int judged = 0; judged < 10; judged++) {
            String user = "user_" + judged;
            List<ColumnOrSuperColumn> cols = cqf.getVerdictListColumns(user);
            assertEquals(10, cols.size());
            System.out.println(user);
            for (int verdict = 0; verdict < 10; verdict++) {
                double lastVote = -0.1;
                ColumnOrSuperColumn col = cols.get(verdict);
                java.util.UUID uuid = TimeUuid.toUUID(col.getColumn().getName());
                double vote = MConverter.byteBufferToDouble(ByteBuffer.wrap(col.getColumn().getValue()));
                System.out.println("   " + uuid + ": " + vote);
                assertTrue(vote > lastVote);
                assertEquals((double)((judged*10+verdict)*0.1)+0.1, vote, 0.01);
                lastVote = vote;
            }
        }
        List<ColumnOrSuperColumn> cols = cqf.getVerdictListColumns("All");
        assertEquals(100, cols.size());
        cols = cqf.getVerdictListColumns("All", 17);
        assertEquals(17, cols.size());
    }

    /**
     * Test of loadRow method, of class CassandraQueryFacade.
     */
    @Ignore
    @Test
    public void testLoadRow() throws Exception {
        System.out.println("loadRow");
        String nameOfColumnFamily = "";
        String key = "";
        CassandraQueryFacade instance = null;
        Object expResult = null;
        Object result = instance.loadRow(nameOfColumnFamily, key);
        assertEquals(expResult, result);
        // TODO review the generated test code and remove the default call to fail.
        fail("The test case is a prototype.");
    }

    /**
     * Test of loadTable method, of class CassandraQueryFacade.
     */
    @Ignore
    @Test
    public void testLoadTable() throws Exception {
        System.out.println("loadTable");
        String columnFamilyName = "";
        CassandraQueryFacade instance = null;
        List expResult = null;
        List result = instance.loadTable(columnFamilyName);
        assertEquals(expResult, result);
        // TODO review the generated test code and remove the default call to fail.
        fail("The test case is a prototype.");
    }

    /**
     * Test of getListOfObjectFromListOfKeys method, of class CassandraQueryFacade.
     */
    @Ignore
    @Test
    public void testGetListOfObjectFromListOfKeys() throws Exception {
        System.out.println("getListOfObjectFromListOfKeys");
        List<String> keys = null;
        String columnFamilyName = "";
        CassandraQueryFacade instance = null;
        List expResult = null;
        List result = instance.getListOfObjectFromListOfKeys(keys, columnFamilyName);
        assertEquals(expResult, result);
        // TODO review the generated test code and remove the default call to fail.
        fail("The test case is a prototype.");
    }

    /**
     * Test of deleteRow method, of class CassandraQueryFacade.
     */
    @Ignore
    @Test
    public void testDeleteRow() throws Exception {
        System.out.println("deleteRow");
        String nameOfColumnFamily = "";
        String key = "";
        CassandraQueryFacade instance = null;
        instance.deleteRow(nameOfColumnFamily, key);
        // TODO review the generated test code and remove the default call to fail.
        fail("The test case is a prototype.");
    }

    /**
     * Test of flush method, of class CassandraQueryFacade.
     */
    @Ignore
    @Test
    public void testFlush() throws Exception {
        System.out.println("flush");
        CassandraQueryFacade instance = null;
        instance.flush();
        // TODO review the generated test code and remove the default call to fail.
        fail("The test case is a prototype.");
    }

    /**
     * Test of clear method, of class CassandraQueryFacade.
     */
    @Ignore
    @Test
    public void testClear() {
        System.out.println("clear");
        CassandraQueryFacade instance = null;
        instance.clear();
        // TODO review the generated test code and remove the default call to fail.
        fail("The test case is a prototype.");
    }
}
