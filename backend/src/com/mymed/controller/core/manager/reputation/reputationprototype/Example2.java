/*
  	* To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.reputationprototype;

import java.io.UnsupportedEncodingException;
import java.nio.ByteBuffer;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Vector;

import org.apache.cassandra.thrift.Cassandra;
import org.apache.cassandra.thrift.Column;
import org.apache.cassandra.thrift.ColumnOrSuperColumn;
import org.apache.cassandra.thrift.ColumnParent;
import org.apache.cassandra.thrift.ColumnPath;
import org.apache.cassandra.thrift.ConsistencyLevel;
import org.apache.cassandra.thrift.InvalidRequestException;
import org.apache.cassandra.thrift.KeyRange;
import org.apache.cassandra.thrift.KeySlice;
import org.apache.cassandra.thrift.Mutation;
import org.apache.cassandra.thrift.NotFoundException;
import org.apache.cassandra.thrift.SlicePredicate;
import org.apache.cassandra.thrift.SliceRange;
import org.apache.cassandra.thrift.SuperColumn;
import org.apache.cassandra.thrift.TimedOutException;
import org.apache.cassandra.thrift.UnavailableException;
import org.apache.thrift.TException;
import org.apache.thrift.protocol.TBinaryProtocol;
import org.apache.thrift.protocol.TProtocol;
import org.apache.thrift.transport.TFramedTransport;
import org.apache.thrift.transport.TSocket;
import org.apache.thrift.transport.TTransport;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.utils.MConverter;

public class Example2 {
    //set up some constants
    private static final String UTF8 = "UTF8";
    private static final String HOST = "localhost";
    private static final int PORT = 4201;
    private static final ConsistencyLevel CL = ConsistencyLevel.ONE;
    //not paying attention to exceptions here
    public static void main(String[] args) throws UnsupportedEncodingException,
    InvalidRequestException, UnavailableException, TimedOutException,
    TException, NotFoundException, InternalBackEndException {
        TTransport tr = new TSocket(HOST, PORT);
        //new default in 0.7 is framed transport
        TFramedTransport tf = new TFramedTransport(tr);
        TProtocol proto = new TBinaryProtocol(tf);
        Cassandra.Client client = new Cassandra.Client(proto);
        tf.open();
        client.set_keyspace("Keyspace2");
        String cfName = "Standard1";
        byte[] userIDKey = "1".getBytes(); //this is a row key
        long clock = System.currentTimeMillis();
        
        //create a representation of the Name column
        ColumnPath colPathName = new ColumnPath(cfName);
        colPathName.setColumn("name".getBytes(UTF8));
        ColumnParent cp = new ColumnParent(cfName);

        //insert the name column
        System.out.println("Inserting row for key " + new String(userIDKey));
        client.insert(ByteBuffer.wrap(userIDKey), cp,
            new Column(ByteBuffer.wrap("name".getBytes(UTF8)),
                       ByteBuffer.wrap("George Clinton".getBytes()),
                       clock), CL);

        //insert the Age column
        client.insert(ByteBuffer.wrap(userIDKey), cp,
            new Column(ByteBuffer.wrap("age".getBytes(UTF8)),
                       ByteBuffer.wrap("69".getBytes()), clock), CL);
        
        System.out.println("Row insert done.");
        
        byte[] pippoIdKey = "2".getBytes();
        System.out.println("Inserting row for key " + new String(pippoIdKey));
        client.insert(ByteBuffer.wrap(pippoIdKey), cp, 
                new Column(ByteBuffer.wrap("name".getBytes(UTF8)),
                           ByteBuffer.wrap("Barack Bush".getBytes()), clock), CL);
        
        
        client.insert(ByteBuffer.wrap(pippoIdKey), cp, 
                new Column(ByteBuffer.wrap("age".getBytes(UTF8)),
                           ByteBuffer.wrap("56".getBytes()),clock), CL);
        System.out.println("Row insert done");
        
        // read just the Name column
        System.out.println("Reading Name Column:");
        Column col = client.get(ByteBuffer.wrap(userIDKey), colPathName,
                CL).getColumn();
        System.out.println("Column name: " + MConverter.byteBufferToString(col.name));
        System.out.println("Column value: " + MConverter.byteBufferToString(col.value));
        System.out.println("Column timestamp: " + col.timestamp);
        
        //create a slice predicate representing the columns to read
        //start and finish are the range of columns--here, all
        SlicePredicate predicate = new SlicePredicate();
        SliceRange sliceRange = new SliceRange();
        sliceRange.setStart(new byte[0]);
        sliceRange.setFinish(new byte[0]);
        predicate.setSlice_range(sliceRange);

        System.out.println("Complete Row:");
        // read all columns in the row
        ColumnParent parent = new ColumnParent(cfName);
        KeyRange kRange = new KeyRange();
        kRange.setStart_key(new byte[0]);
        kRange.setEnd_key(new byte[0]);
        List<KeySlice> _range_slices = client.get_range_slices(parent, predicate,kRange, CL);
        for(KeySlice ks : _range_slices){
            System.err.println(MConverter.byteBufferToString(ks.key));
            List<ColumnOrSuperColumn> results = ks.columns;
           for (ColumnOrSuperColumn result : results) {
            Column column = result.column;
            System.out.println(MConverter.byteBufferToString(column.name) + " : "
                + MConverter.byteBufferToString(column.value));
            }
        }
        
        
        //inserisco la lista 1
        System.out.println("Inserting into the supercolumnfamily");
        Map<ByteBuffer,Map<String,List<Mutation>>> listaGen = new HashMap<ByteBuffer, Map<String,List<Mutation>>>();
        
        Map<String,List<Mutation>> lista1 = new HashMap<String, List<Mutation>>();
        
        Mutation m1 = new Mutation();
        ColumnOrSuperColumn c1 = new ColumnOrSuperColumn();
        Column col1 = new Column(ByteBuffer.wrap("id".getBytes()),
                ByteBuffer.wrap("1".getBytes()),System.currentTimeMillis());
        List<Column> lc1 = new Vector<Column>();
        lc1.add(col1);
        SuperColumn sc1 = new SuperColumn(ByteBuffer.wrap("George".getBytes()), lc1);
        c1.setSuper_column(sc1);
        m1.setColumn_or_supercolumn(c1);
        
        Mutation m2 = new Mutation();
        ColumnOrSuperColumn c2 = new ColumnOrSuperColumn();
        Column col2 = new Column(ByteBuffer.wrap("id".getBytes()),
                ByteBuffer.wrap("2".getBytes()),System.currentTimeMillis());
        List<Column> lc2 = new Vector<Column>();
        lc2.add(col2);
        SuperColumn sc2 = new SuperColumn(ByteBuffer.wrap("Barack".getBytes()), lc2);
        c2.setSuper_column(sc2);
        m2.setColumn_or_supercolumn(c2);
        
        List<Mutation> lm = new Vector<Mutation>();
        lm.add(m1);
        lm.add(m2);
        
        lista1.put("ListStandard1", lm);
        
        listaGen.put(ByteBuffer.wrap("lista1".getBytes()), lista1);
        client.batch_mutate(listaGen, CL);
        
        System.out.println("Retrieving from the supercolumnfamily");
        ColumnParent secondPar = new ColumnParent("ListStandard1");
        KeyRange kRange1 = new KeyRange();
        kRange1.setStart_key(new byte[0]);
        kRange1.setEnd_key(new byte[0]);
        List<KeySlice> _range_slices1 = client.get_range_slices(secondPar, predicate,kRange, CL);
        for(KeySlice ks : _range_slices1){
            System.out.println(new String(ks.getKey()));
            List<ColumnOrSuperColumn> results = ks.columns;
           for (ColumnOrSuperColumn result : results) {
            SuperColumn sColumn = result.super_column;
            System.out.println(MConverter.byteBufferToString(sColumn.name));
            for(Column c : sColumn.columns){
                System.out.println(MConverter.byteBufferToString(c.name) + ": " +
                        MConverter.byteBufferToString(c.value));
            }
            }
        }
        
        System.out.println("Adding a fake data into the supercolumnfamily");
        Map<ByteBuffer,Map<String,List<Mutation>>> listaGen1 = new HashMap<ByteBuffer, Map<String,List<Mutation>>>();
        
        Map<String,List<Mutation>> hlm = new HashMap<String, List<Mutation>>();
        Mutation m3 = new Mutation();
        ColumnOrSuperColumn c3 = new ColumnOrSuperColumn();
        Column col3 = new Column(ByteBuffer.wrap("id".getBytes()),
                ByteBuffer.wrap("3".getBytes()),System.currentTimeMillis());
        List<Column> lc3 = new Vector<Column>();
        lc3.add(col3);
        SuperColumn sc3 = new SuperColumn(ByteBuffer.wrap("Stupida".getBytes()), lc3);
        c3.setSuper_column(sc3);
        m3.setColumn_or_supercolumn(c3);
        List<Mutation> lm1 = new Vector<Mutation>();
        lm1.add(m3);
        hlm.put("ListStandard1", lm1);
        listaGen1.put(ByteBuffer.wrap("lista1".getBytes()), hlm);
        client.batch_mutate(listaGen1, CL);
        
        
        System.out.println("Retrieving from the supercolumnfamily1");
        secondPar = new ColumnParent("ListStandard1");
        kRange1 = new KeyRange();
        kRange1.setStart_key(new byte[0]);
        kRange1.setEnd_key(new byte[0]);
        _range_slices1 = client.get_range_slices(secondPar, predicate,kRange, CL);
        for(KeySlice ks : _range_slices1){
            List<ColumnOrSuperColumn> results = ks.columns;
           for (ColumnOrSuperColumn result : results) {
            SuperColumn sColumn = result.super_column;
            System.out.println(MConverter.byteBufferToString(sColumn.name));
            for(Column c : sColumn.columns){
                System.out.println(MConverter.byteBufferToString(c.name) + ": " +
                        MConverter.byteBufferToString(c.value));
            }
            }
        }
        
        tf.close();
        System.out.println("All done.");
    }
}