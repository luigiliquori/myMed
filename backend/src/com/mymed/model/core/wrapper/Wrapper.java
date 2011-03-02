package com.mymed.model.core.wrapper;

import java.io.UnsupportedEncodingException;
import java.util.Map;

import org.apache.cassandra.thrift.ConsistencyLevel;
import org.apache.cassandra.thrift.InvalidRequestException;
import org.apache.cassandra.thrift.NotFoundException;
import org.apache.cassandra.thrift.TimedOutException;
import org.apache.cassandra.thrift.UnavailableException;
import org.apache.thrift.TException;

import com.mymed.model.core.data.dht.DHTFactory;
import com.mymed.model.core.data.dht.IDHT;
import com.mymed.model.core.data.dht.IDHT.Type;
import com.mymed.model.core.data.dht.protocol.Cassandra;
import com.mymed.model.datastructure.User;

/**
 * @author lvanni
 * 
 * This class represent the DAO pattern:
 * Access to data varies depending on the source of the data. 
 * Access to persistent storage, such as to a database, varies greatly depending on the type of storage
 * 
 * Use a Data Access Object (DAO) to abstract and encapsulate all access to the data source. 
 * The DAO manages the connection with the data source to obtain and store data.
 */
public class Wrapper {
	
	/** Default ConsistencyLevel */
	public static ConsistencyLevel consistencyOnWrite = ConsistencyLevel.ANY;
	public static ConsistencyLevel consistencyOnRead = ConsistencyLevel.ONE;
	
	/* --------------------------------------------------------- */
	/* 					User Profile Management					 */
	/* --------------------------------------------------------- */
	/**
	 * @see com.mymed.model.core.data.dht.protocol.Cassandra#getProfile(id)
	 */
	@Deprecated
	public User getProfile(String id) {
		Cassandra node = (Cassandra) DHTFactory.createDHT(Type.CASSANDRA);

		String columnFamily = "Users";
		try {
			// USER NAME
			String name = new String(node.getSimpleColumn("Mymed",
					columnFamily, id, "name".getBytes("UTF8"),
					consistencyOnRead), "UTF8");
			// USER GENDER
			String gender = new String(node.getSimpleColumn("Mymed",
					columnFamily, id, "gender".getBytes("UTF8"),
					consistencyOnRead), "UTF8");
			// USER LOCALE
			String locale = new String(node.getSimpleColumn("Mymed",
					columnFamily, id, "locale".getBytes("UTF8"),
					consistencyOnRead), "UTF8");
			// USER UPTIME
			String updated_time = new String(node.getSimpleColumn("Mymed",
					columnFamily, id, "updated_time".getBytes("UTF8"),
					consistencyOnRead), "UTF8");
			// USER PROFILE
			String profile = new String(node.getSimpleColumn("Mymed",
					columnFamily, id, "profile".getBytes("UTF8"),
					consistencyOnRead), "UTF8");
			// USER PROFILE_PICTURE
			String profile_picture = new String(node.getSimpleColumn("Mymed",
					columnFamily, id, "profile_picture".getBytes("UTF8"),
					consistencyOnRead), "UTF8");
			// USER SOCIAL_NETWORK
			String social_network = new String(node.getSimpleColumn("Mymed",
					columnFamily, id, "social_network".getBytes("UTF8"),
					consistencyOnRead), "UTF8");
			if(social_network.equals("myMed")){
				// USER EMAIL
				String email = new String(node.getSimpleColumn("Mymed",
						columnFamily, id, "email".getBytes("UTF8"),
						consistencyOnRead), "UTF8");
				// USER PASSWORD
				String password = new String(node.getSimpleColumn("Mymed",
						columnFamily, id, "password".getBytes("UTF8"),
						consistencyOnRead), "UTF8");
				return new User(id, name, gender, locale, updated_time, profile,
						profile_picture, social_network, email, password);
			} else {
				return new User(id, name, gender, locale, updated_time, profile,
						profile_picture, social_network);
			}
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return new User();
	}

	/**
	 * @see com.mymed.model.core.data.dht.protocol.Cassandra#setProfile(User)
	 */
	@Deprecated
	public void setProfile(User user) {
		Cassandra node = (Cassandra) DHTFactory.createDHT(Type.CASSANDRA);
		try {
			String columnFamily = "Users";
			// USER ID
			node.setSimpleColumn("Mymed", columnFamily, user.getId(), "id"
					.getBytes("UTF8"), user.getId().getBytes("UTF8"),
					consistencyOnWrite);
			// USER NAME
			node.setSimpleColumn("Mymed", columnFamily, user.getId(), "name"
					.getBytes("UTF8"), user.getName().getBytes("UTF8"),
					consistencyOnWrite);
			// USER GENDER
			node.setSimpleColumn("Mymed", columnFamily, user.getId(), "gender"
					.getBytes("UTF8"), user.getGender().getBytes("UTF8"),
					consistencyOnWrite);
			// USER LOCALE
			node.setSimpleColumn("Mymed", columnFamily, user.getId(), "locale"
					.getBytes("UTF8"), user.getLocale().getBytes("UTF8"),
					consistencyOnWrite);
			// USER UPTIME
			node.setSimpleColumn("Mymed", columnFamily, user.getId(),
					"updated_time".getBytes("UTF8"), user.getUpdated_time()
					.getBytes("UTF8"), consistencyOnWrite);
			// USER PROFILE
			node.setSimpleColumn("Mymed", columnFamily, user.getId(), "profile"
					.getBytes("UTF8"), user.getProfile().getBytes("UTF8"),
					consistencyOnWrite);
			// USER PROFILE_PICTURE

			node.setSimpleColumn("Mymed", columnFamily, user.getId(),
					"profile_picture".getBytes("UTF8"), user
					.getProfile_picture().getBytes("UTF8"),
					consistencyOnWrite);
			// USER SOCIAL_NETWORK
			node.setSimpleColumn("Mymed", columnFamily, user.getId(),
					"social_network".getBytes("UTF8"), user.getSocial_network()
					.getBytes("UTF8"), consistencyOnWrite);
			if(user.getSocial_network().equals("myMed")){
				// USER EMAIL
				node.setSimpleColumn("Mymed", columnFamily, user.getId(), "email"
						.getBytes("UTF8"), user.getEmail().getBytes("UTF8"),
						consistencyOnWrite);
				// USER PASSWORD
				node.setSimpleColumn("Mymed", columnFamily, user.getId(),
						"password".getBytes("UTF8"), user.getPassword().getBytes(
						"UTF8"), consistencyOnWrite);
			}
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	/* --------------------------------------------------------- */
	/*             			 public methods        		         */
	/* --------------------------------------------------------- */
	/**
	 * Insert a new entry in the database
	 * @param tableName
	 * 		the name of the Table/ColumnFamily
	 * @param primaryKey
	 * 		the ID of the entry
	 * @param args
	 * 		All columnName and the their value
	 * @return true if the entry is correctly stored, false otherwise
	 */
	public boolean insertInto(String tableName, String primaryKey, Map<String, byte[]> args) {
		// The main database is managed by Cassandra
		Cassandra node = (Cassandra) DHTFactory.createDHT(Type.CASSANDRA);
	
		// keyspace is similare to the database name
		String keysapce = "Mymed";
		// columnFamily is similare to the table name
		String columnFamily = tableName;
		
		// Store into Cassandra the new entry
		for(String columnName : args.keySet()){
			try {
				node.setSimpleColumn(keysapce, columnFamily, primaryKey,
						columnName.getBytes("UTF8"), args.get(columnName), consistencyOnWrite);
			} catch (UnsupportedEncodingException e) {
				e.printStackTrace();
				return false;
			} catch (InvalidRequestException e) {
				e.printStackTrace();
				return false;
			} catch (UnavailableException e) {
				e.printStackTrace();
				return false;
			} catch (TimedOutException e) {
				e.printStackTrace();
				return false;
			} catch (TException e) {
				e.printStackTrace();
				return false;
			}
		}
		return true;
	}

	/**
	 * Get the value of an entry column
	 * @param tableName
	 * 		the name of the Table/ColumnFamily
	 * @param primaryKey
	 * 		the ID of the entry
	 * @param columnName
	 * 		the name of the column
	 * @return the value of the column
	 */
	public byte[] selectColumn(String tableName, String primaryKey, String columnName) 
	throws UnsupportedEncodingException, InvalidRequestException, NotFoundException, UnavailableException, TimedOutException, TException {
		// The main database is managed by Cassandra
		Cassandra node = (Cassandra) DHTFactory.createDHT(Type.CASSANDRA);
	
		// keyspace is similare to the database name
		String keysapce = "Mymed";
		// columnFamily is similare to the table name
		String columnFamily = tableName;
		
		return node.getSimpleColumn(keysapce, tableName, primaryKey, columnName.getBytes("UTF8"), consistencyOnRead);
	}
	
	
	/* --------------------------------------------------------- */
	/* 					Common DHT operations					 */
	/* --------------------------------------------------------- */
	/**
	 * Common put operation
	 * @param key
	 * @param value
	 * @param DHTType	The type of DHT used for the operation
	 */
	public void put(String key, String value, Type DHTType) {
		IDHT node = DHTFactory.createDHT(DHTType);
		node.put(key, value);
	}

	/**
	 * Common get operation
	 * @param key
	 * @param DHTType	The type of DHT used for the operation
	 */
	public String get(String key, Type DHTType) {
		IDHT node = DHTFactory.createDHT(DHTType);
		return node.get(key);
	}
	
}
