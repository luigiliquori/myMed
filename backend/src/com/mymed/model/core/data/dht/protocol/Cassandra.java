package com.mymed.model.core.data.dht.protocol;

import java.io.UnsupportedEncodingException;

import org.apache.cassandra.thrift.Column;
import org.apache.cassandra.thrift.ColumnPath;
import org.apache.cassandra.thrift.ConsistencyLevel;
import org.apache.cassandra.thrift.InvalidRequestException;
import org.apache.cassandra.thrift.NotFoundException;
import org.apache.cassandra.thrift.TimedOutException;
import org.apache.cassandra.thrift.UnavailableException;
import org.apache.cassandra.thrift.Cassandra.Client;
import org.apache.thrift.TException;
import org.apache.thrift.protocol.TBinaryProtocol;
import org.apache.thrift.protocol.TProtocol;
import org.apache.thrift.transport.TSocket;
import org.apache.thrift.transport.TTransport;
import org.apache.thrift.transport.TTransportException;

import com.mymed.model.core.data.dht.AbstractDHT;
import com.mymed.model.datastructure.User;

/**
 * 
 * @author lvanni
 *
 */
public class Cassandra extends AbstractDHT {

	/** The Cassandra instance */
	private static Cassandra singleton;

	/** Cassandra node attributes */
	private TTransport tr;
	private TProtocol proto;
	private Client client;
	private String keyspace;

	/**
	 * Private Constructor to create a singleton
	 */
	private Cassandra() {
		super("138.96.242.2", 4201);

		this.keyspace = "Mymed";
		this.tr = new TSocket(address, port);
		this.proto = new TBinaryProtocol(tr);
		this.client = new Client(proto);
	}

	/**
	 * Cassandra getter
	 * @return
	 * 		The only one instance of Cassandra
	 */
	public static Cassandra getInstance() {
		if (null == singleton) {
			singleton = new Cassandra();
		}
		return singleton;
	}

	/**
	 * Return an user profile from the backbone
	 * @param user
	 */
	public User getProfile(String id){
		User user = new User();
		try {
			tr.open();
			String columnFamily = "Users";
			ColumnPath colPathName = new ColumnPath(columnFamily);
			// USER ID
			colPathName.setColumn("id".getBytes("UTF8"));
			Column col = client.get(keyspace, id, colPathName,
					ConsistencyLevel.QUORUM).getColumn();
			user.setId(new String(col.value, "UTF8"));
			// USER NAME
			colPathName.setColumn("name".getBytes("UTF8"));
			col = client.get(keyspace, id, colPathName,
					ConsistencyLevel.QUORUM).getColumn();
			user.setName(new String(col.value, "UTF8"));
			// USER GENDER
			colPathName.setColumn("gender".getBytes("UTF8"));
			col = client.get(keyspace, id, colPathName,
					ConsistencyLevel.QUORUM).getColumn();
			user.setGender(new String(col.value, "UTF8"));
			// USER LOCALE
			colPathName.setColumn("locale".getBytes("UTF8"));
			col = client.get(keyspace, id, colPathName,
					ConsistencyLevel.QUORUM).getColumn();
			user.setLocale(new String(col.value, "UTF8"));
			// USER UPTIME
			colPathName.setColumn("updated_time".getBytes("UTF8"));
			col = client.get(keyspace, id, colPathName,
					ConsistencyLevel.QUORUM).getColumn();
			user.setUpdated_time(new String(col.value, "UTF8"));
			// USER PROFILE
			colPathName.setColumn("profile".getBytes("UTF8"));
			col = client.get(keyspace, id, colPathName,
					ConsistencyLevel.QUORUM).getColumn();
			user.setProfile(new String(col.value, "UTF8"));
			// USER PROFILE_PICTURE
			colPathName.setColumn("profile_picture".getBytes("UTF8"));
			col = client.get(keyspace, id, colPathName,
					ConsistencyLevel.QUORUM).getColumn();
			user.setProfile_picture(new String(col.value, "UTF8"));
			// USER SOCIAL_NETWORK
			colPathName.setColumn("social_network".getBytes("UTF8"));
			col = client.get(keyspace, id, colPathName,
					ConsistencyLevel.QUORUM).getColumn();
			user.setSocial_network(new String(col.value, "UTF8"));
		} catch (TTransportException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (UnsupportedEncodingException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (InvalidRequestException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (UnavailableException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (TimedOutException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (TException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (NotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} finally {
			tr.close();
		}
		return user;
	}

	/**
	 * Register a new user in the backbone
	 * @param user
	 */
	public void setProfile(User user){
		try {
			tr.open();
			String columnFamily = "Users";
			ColumnPath colPathName = new ColumnPath(columnFamily);
			long timestamp = System.currentTimeMillis();
			// USER ID
			colPathName.setColumn("id".getBytes("UTF8"));
			client.insert(keyspace, user.getId(), colPathName, user.getId().getBytes("UTF8"), timestamp,
					ConsistencyLevel.QUORUM);
			// USER NAME
			colPathName.setColumn("name".getBytes("UTF8"));
			client.insert(keyspace, user.getId(), colPathName, user.getName().getBytes("UTF8"), timestamp,
					ConsistencyLevel.QUORUM);
			// USER GENDER
			colPathName.setColumn("gender".getBytes("UTF8"));
			client.insert(keyspace, user.getId(), colPathName, user.getGender().getBytes("UTF8"), timestamp,
					ConsistencyLevel.QUORUM);
			// USER LOCALE
			colPathName.setColumn("locale".getBytes("UTF8"));
			client.insert(keyspace, user.getId(), colPathName, user.getLocale().getBytes("UTF8"), timestamp,
					ConsistencyLevel.QUORUM);
			// USER UPTIME
			colPathName.setColumn("updated_time".getBytes("UTF8"));
			client.insert(keyspace, user.getId(), colPathName, user.getUpdated_time().getBytes("UTF8"), timestamp,
					ConsistencyLevel.QUORUM);
			// USER PROFILE
			colPathName.setColumn("profile".getBytes("UTF8"));
			client.insert(keyspace, user.getId(), colPathName, user.getProfile().getBytes("UTF8"), timestamp,
					ConsistencyLevel.QUORUM);
			// USER PROFILE_PICTURE
			colPathName.setColumn("profile_picture".getBytes("UTF8"));
			client.insert(keyspace, user.getId(), colPathName, user.getProfile_picture().getBytes("UTF8"), timestamp,
					ConsistencyLevel.QUORUM);
			// USER SOCIAL_NETWORK
			colPathName.setColumn("social_network".getBytes("UTF8"));
			client.insert(keyspace, user.getId(), colPathName, user.getSocial_network().getBytes("UTF8"), timestamp,
					ConsistencyLevel.QUORUM);

		} catch (TTransportException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (UnsupportedEncodingException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (InvalidRequestException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (UnavailableException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (TimedOutException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (TException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} finally {
			tr.close();
		}
	}

	/* --------------------------------------------------------- */
	/*                    DHT OPERATIONS                         */
	/* --------------------------------------------------------- */
	@Override
	public void put(String key, String value) {
		// TODO Auto-generated method stub
	}

	@Override
	public String get(String key) {
		// TODO Auto-generated method stub
		return null;
	}
}
