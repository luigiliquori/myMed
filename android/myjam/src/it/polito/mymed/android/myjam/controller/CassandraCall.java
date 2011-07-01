package it.polito.mymed.android.myjam.controller;
import java.nio.ByteBuffer;

import org.apache.cassandra.thrift.Cassandra.Client;
import org.apache.cassandra.thrift.Column;
import org.apache.cassandra.thrift.ColumnParent;
import org.apache.cassandra.thrift.ColumnPath;
import org.apache.cassandra.thrift.ConsistencyLevel;
import org.apache.thrift.protocol.TBinaryProtocol;
import org.apache.thrift.protocol.TProtocol;
import org.apache.thrift.transport.TFramedTransport;
import org.apache.thrift.transport.TSocket;
import org.apache.thrift.transport.TTransport;

public class CassandraCall {

	private TTransport tr;
	private TProtocol proto;
	private Client client;
	private String keyspace = "myJamKeyspace";
	private String columnFamily = "Locations";
	private String address;
	private int port;

	/**
	 * Default Constructor
	 */
	public CassandraCall() {
		// Default value
		this.address = "10.0.2.2";
		this.port = 9160;
		this.keyspace = "myJamKeyspace";
		this.columnFamily = "Locations";

		this.tr = new TSocket(address, port);
    	TSocket socket = new TSocket("192.168.2.2", 9160);
    	tr = new TFramedTransport(socket);
    	proto = new TBinaryProtocol(tr);
		this.proto = new TBinaryProtocol(tr);
		this.client = new Client(proto);
	}

	/**
	 * Simple put method using Cassandra/Thrift API
	 * If sColumnName operates on a ColumnFamily, if not on a SuperColumnFamily 
	 * @param key
	 * @param value
	 */
	public void put(String key,Long sColumnName,String name, String value){
		try{
			tr.open();
			long timestamp = System.currentTimeMillis();
        	ColumnParent parent = new ColumnParent(columnFamily);
        	if (sColumnName!=null)
        		parent.setSuper_column(ByteBuffer.wrap(longToByte(sColumnName)));
			ByteBuffer keyBB = ByteBuffer.wrap(key.getBytes("UTF8"));
			Column col = new Column();
			col.setName(name.getBytes());
			col.setValue(value.getBytes());
			col.setTimestamp(timestamp);
			col.setTtl(600000);
//			SuperColumn sCol = new SuperColumn();
//			sCol.setName(superColumnName.getBytes());
//			sCol.addToColumns(col);
			client.set_keyspace(this.keyspace);
			client.insert(keyBB, parent, col, ConsistencyLevel.ONE);
		} catch(Exception e) {
			android.util.Log.e("CassandraCall",e.getClass().getCanonicalName());
			if (e.getLocalizedMessage() != null)
				android.util.Log.e("CassandraCall",e.getLocalizedMessage());
		} finally {
			tr.close();
		}
	}

	/**
	 * Simple get method using Cassandra/Thrift API
	 * If sColumnName operates on a ColumnFamily, if not on a SuperColumnFamily
	 * @param key
	 * @return
	 */
	public String get(String key,Long sColumnName,String name){
		try{
			tr.open();
        	ColumnPath path = new ColumnPath(columnFamily);
        	if (sColumnName!=null)
            	path.setSuper_column(longToByte(sColumnName));
        	path.setColumn(name.getBytes());
        	ByteBuffer keyBB = ByteBuffer.wrap(key.getBytes("UTF8"));
			Column col = client.get(keyBB, path, ConsistencyLevel.ONE).getColumn();
			return col.value.toString();
		} catch(Exception e) {
			android.util.Log.e("CassandraCall",e.getClass().getCanonicalName());
			if (e.getLocalizedMessage() != null)
				android.util.Log.e("CassandraCall",e.getLocalizedMessage());
			return "error";
		} finally {
			tr.close();
		}
	}
	
	/**
	 * Convert a long in a Byte array of size 8. 
	 * @param l
	 * @return
	 */
    private byte[] longToByte(long l){
    	byte[] val = new byte[8];
    	for (int i=0;i<8;i++){
    		val[7-i] = (byte)(l >>> (8*i));
    	}
    	return val;
    }
}

