package com.mymed.controller.core.services;

import java.io.UnsupportedEncodingException;
import java.lang.reflect.Field;
import java.util.HashMap;
import java.util.Map;

import org.apache.cassandra.thrift.InvalidRequestException;
import org.apache.cassandra.thrift.NotFoundException;
import org.apache.cassandra.thrift.TimedOutException;
import org.apache.cassandra.thrift.UnavailableException;
import org.apache.thrift.TException;

import com.mymed.controller.core.services.engine.reputation.IReputation;
import com.mymed.controller.core.services.engine.reputation.IReputationSystem;
import com.mymed.controller.core.services.engine.reputation.ReputationSystem;
import com.mymed.model.core.wrapper.Wrapper;
import com.mymed.model.datastructure.Transaction;
import com.mymed.model.datastructure.User;

/**
 * Manage all the request from the RequestHandler
 * @author lvanni
 * 
 */
public class ServiceManager {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	/** DAO pattern */
	private Wrapper wrapper;

	/** WPF3 - UNITO - Reputation Systems and Security */
	private IReputationSystem reputation;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	/**
	 * default constructor
	 */
	public ServiceManager() {
		this.wrapper = new Wrapper();
		this.reputation = new ReputationSystem();
	}

	/* --------------------------------------------------------- */
	/* User Profile Management */
	/* --------------------------------------------------------- */
	/**
	 * Setup a new user profile into the database
	 * @param user
	 * 			the user to insert into the database
	 */
	public void setProfile(User user) {
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
			if(wrapper.insertInto("Users", user.getId(), args)) { // The error need to be handled
				System.out.println("user successfully insered!");
			} else {
				System.out.println("insert failed...");
			}
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
		}
	}
	
	/**
	 * @param id
	 *            the id of the user
	 * @return the User corresponding to the id
	 */
	public User getProfile(String id) {
		Map<String, String> args = new HashMap<String, String>();
		String value;
		Field[] fields = User.class.getDeclaredFields();
		try {
			for (Field field : fields) {
				value = new String(wrapper.selectColumn("Users", id, field
						.getName()), "UTF8");
				args.put(field.getName(), value);
			}
			if (args.get("social_network").equals("myMed")) {
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
		}
		return null;
	}

	/* --------------------------------------------------------- */
	/* Reputation System Management */
	/* --------------------------------------------------------- */
	// TBD with UNITO
	/**
	 * @see com.mymed.controller.core.services.engine.reputation.ReputationSystem#getReputation(User,
	 *      String)
	 * @param user
	 * @param serviceID
	 */
	public IReputation getReputation(User user, String serviceID) {
		return reputation.getReputation(user, serviceID);
	}

	/**
	 * @see com.mymed.controller.core.services.engine.reputation.ReputationSystem#notifyTransaction(Transaction)
	 * @param transaction
	 */
	public void notifyTransaction(Transaction transaction) {
		reputation.notifyTransaction(transaction);
	}
}
