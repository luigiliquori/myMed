package com.mymed.model.core.wrapper;

import java.io.UnsupportedEncodingException;

import org.apache.cassandra.thrift.ConsistencyLevel;

import com.mymed.model.core.data.dht.DHTFactory;
import com.mymed.model.core.data.dht.IDHT.Type;
import com.mymed.model.core.data.dht.protocol.Cassandra;
import com.mymed.model.datastructure.User;

/**
 * This class represent the DAO pattern
 * 
 * @author lvanni
 * 
 */
public class Wrapper {
	
	/** Default ConsistencyLevel */
	public static ConsistencyLevel consistencyOnWrite = ConsistencyLevel.ANY;
	public static ConsistencyLevel consistencyOnRead = ConsistencyLevel.ONE;

	/* --------------------------------------------------------- */
	/* User Profile Management */
	/* --------------------------------------------------------- */
	/**
	 * @see com.mymed.model.core.data.dht.protocol.Cassandra#getProfile(id)
	 */
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
		} catch (UnsupportedEncodingException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return new User();
	}

	/**
	 * @see com.mymed.model.core.data.dht.protocol.Cassandra#setProfile(User)
	 */
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
		} catch (UnsupportedEncodingException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}

}
