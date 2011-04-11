package com.mymed.controller.core.services.pubsub;

import java.io.UnsupportedEncodingException;
import java.lang.reflect.Field;
import java.util.HashMap;
import java.util.Map;

import org.apache.cassandra.thrift.InvalidRequestException;
import org.apache.cassandra.thrift.NotFoundException;
import org.apache.cassandra.thrift.TimedOutException;
import org.apache.cassandra.thrift.UnavailableException;
import org.apache.thrift.TException;

import com.mymed.model.core.data.dht.factory.IDHTClient.ClientType;
import com.mymed.model.core.wrapper.Wrapper;
import com.mymed.model.core.wrapper.exception.WrapperException;
import com.mymed.model.datastructure.User;

/**
 * Manage an user profile
 * @author lvanni
 *
 */
public class ProfileManager implements IProfileManager {
	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	/** DAO pattern */
	private Wrapper wrapper;
	
	
	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public ProfileManager(ClientType type) {
		this.wrapper = new Wrapper(type);
	}
	
	public ProfileManager() {
		this(ClientType.CASSANDRA);
	}
	

	/* --------------------------------------------------------- */
	/* implements IProfileManagement */
	/* --------------------------------------------------------- */
	/**
	 * Setup a new user profile into the database
	 * @param user
	 * 			the user to insert into the database
	 */
	public void create(User user) {
		Map<String, byte[]> args = new HashMap<String, byte[]>();
		try {
			args.put("id", user.getId().getBytes("UTF8"));
			args.put("name", user.getName().getBytes("UTF8"));
			args.put("gender", user.getGender().getBytes("UTF8"));
			args.put("locale", user.getLocale().getBytes("UTF8"));
			args.put("updated_time", user.getUpdated_time().getBytes("UTF8"));
			args.put("profile", user.getProfile().getBytes("UTF8"));
			args.put("profile_picture", user.getProfile_picture().getBytes("UTF8"));
			args.put("social_network", user.getSocial_network().getBytes("UTF8"));
			if(user.getSocial_network().equals("myMed")){
				args.put("email", user.getEmail().getBytes("UTF8"));
				args.put("password", user.getPassword().getBytes("UTF8"));
			}
			if(wrapper.insertInto("Users", "id", args)) { // The error need to be handled
				System.out.println("user successfully insered!");
			} else {
				System.out.println("insert failed...");
			}
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		} catch (WrapperException e) {
			e.printStackTrace();
		}
	}
	
	/**
	 * @param id
	 *            the id of the user
	 * @return the User corresponding to the id
	 */
	public User read(String id) {
		Map<String, String> args = new HashMap<String, String>();
		byte[] value;
		Field[] fields = User.class.getDeclaredFields();
		try {
			for (Field field : fields) {
				value = wrapper.selectColumn("Users", id, field.getName());
				if(value != null){
					args.put(field.getName(), new String(value, "UTF8"));
				}
			}
			if (args.get("social_network") != null && args.get("social_network").equals("myMed")) {
				return new User(args.get("id"), args.get("name"), args
						.get("gender"), args.get("locale"), args
						.get("updated_time"), args.get("profile"), args
						.get("profile_picture"), args.get("social_network"),
						args.get("email"), args.get("password"));
			} else {
				return new User(args.get("id"), args.get("name"), args
						.get("gender"), args.get("locale"), args
						.get("updated_time"), args.get("profile"), args
						.get("profile_picture"), args.get("social_network"));
			}
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		} catch (InvalidRequestException e) {
			e.printStackTrace();
		} catch (NotFoundException e) {
			e.printStackTrace();
		} catch (UnavailableException e) {
			e.printStackTrace();
		} catch (TimedOutException e) {
			e.printStackTrace();
		} catch (TException e) {
			e.printStackTrace();
		} catch (WrapperException e) {
			e.printStackTrace();
		}
		return null;
	}

	/**
	 * @see IProfileManager#update(User)
	 */
	public void update(User user) { }
	
	/**
	 * @see IProfileManager#delete(User)
	 */
	public void delete(User user) { }
	
	/**
	 * @see IProfileManager#login(User)
	 */
	public void login() { }

}
