package com.mymed.tests.unit.manager;

import static com.mymed.model.data.application.MOntologyID.*;


import static com.mymed.utils.PubSub.*;
import static com.mymed.utils.PubSub.Index.joinCols;
import static com.mymed.utils.PubSub.Index.joinRows;
import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertTrue;
import static org.junit.Assert.fail;

import java.io.File;
import java.io.UnsupportedEncodingException;
import java.text.DecimalFormat;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;

import org.junit.Before;
import org.junit.Test;
import org.slf4j.LoggerFactory;

import ch.qos.logback.classic.Logger;

import com.google.gson.GsonBuilder;
import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.authentication.AuthenticationManager;
import com.mymed.controller.core.manager.geolocation.GeoLocationManager;
import com.mymed.controller.core.manager.interaction.InteractionManager;
import com.mymed.controller.core.manager.profile.ProfileManager;
import com.mymed.controller.core.manager.pubsub.v2.PubSubManager;
import com.mymed.controller.core.manager.reputation.api.recommendation_manager.ReputationManager;
import com.mymed.controller.core.manager.session.SessionManager;
import com.mymed.controller.core.manager.storage.StorageManager;
import com.mymed.model.core.configuration.WrapperConfiguration;
import com.mymed.model.data.application.DataBean;
import com.mymed.model.data.application.MDataBean;
import com.mymed.model.data.application.MOntologyID;
import com.mymed.model.data.session.MSessionBean;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.GsonUtils;
import com.mymed.utils.PubSub;
import com.mymed.utils.PubSub.Index;

public class PubSubTests extends TestValues {
	
	
	final static Logger LOGGER = (Logger) LoggerFactory.getLogger(PubSubTests.class);

	protected PubSubManager pubsubManager;
	private MUserBean userBean;
	private final String date = "1971-01-01";
	private List<DataBean> dataList;
	private String dataId = "mydata";

	private String application = "myTest";

	private String namespace = "fake";

	private ArrayList<String> range(final String... args) {
		ArrayList<String> result = new ArrayList<String>();
		for (String s : args) {
			result.add(s);
		}
		return result;
	}
    
	@Before
	public void setUp() throws InternalBackEndException {
		// storageManager = new StorageManager(new WrapperConfiguration(new
		// File(CONF_FILE)));
		pubsubManager = new PubSubManager();

		userBean = new MUserBean();
		userBean.setId(USER_ID);
		userBean.setBirthday(date);
		userBean.setSocialNetworkID(NAME);
		userBean.setBuddyList(BUDDY_LST_ID);
		userBean.setEmail(EMAIL);
		userBean.setFirstName(FIRST_NAME);
		userBean.setGender(GENDER);
		userBean.setHometown(HOMETOWN);
		userBean.setLastName(LAST_NAME);
		userBean.setLink(LINK);
		userBean.setName(LOGIN);
		userBean.setSession(SESSION_ID);
		userBean.setInteractionList(INTERACTION_LST_ID);
		userBean.setLastConnection(CAL_INSTANCE.getTimeInMillis());
		userBean.setReputation(REPUTATION_ID);
		userBean.setSubscribtionList(SUBSCRIPTION_LST_ID);

	}
    
	@Test
	public void testCreateData() {
		try {
			DataBean d1 = new DataBean(KEYWORD, "test1", range(""));
			DataBean d2 = new DataBean(ENUM, "test2", range("kk2", "kk3", "kk4"));
			DataBean d3 = new DataBean(FLOAT, "test3", range("22.2222"));
			DataBean d4 = new DataBean(DATE, "somedate", range("999999999"));
			DataBean d5 = new DataBean(TEXT, "text1", range("........."));
			dataList = new ArrayList<DataBean>();
			dataList.add(d1);
			dataList.add(d2);
			dataList.add(d3);
			dataList.add(d4);
			dataList.add(d5);
			
			List<Index> indexesWithoutEnums = getIndexes(dataList);
			List<List<Index>> indexesEnums = getIndexesEnums(dataList);
			List<List<Index>> lli = generateIndexes(indexesWithoutEnums, indexesEnums);

			for (List<Index> li : lli) {
				String s1 = joinRows(li);
				String s2 = joinCols(li);

				pubsubManager.create(makePrefix(application, namespace), s1,
						s2, dataId, userBean,
						PubSub.subList(dataList, MOntologyID.DATA));
				pubsubManager.sendEmailsToSubscribers(
						makePrefix(application, namespace), s1, userBean,
						dataList);
			}

			/* creates data */
			pubsubManager.create(makePrefix(application, namespace), dataId, dataList);

		} catch (final Exception ex) {
			fail(ex.getMessage());
		}
	}
    
	

    @Test
    public void testFindData() {
      try {
    	  
    	  	dataList = new ArrayList<DataBean>();
			dataList.add(new DataBean(KEYWORD, "test1", range("")));
			dataList.add(new DataBean(ENUM, "test2", range("kk3")));
					
			Map<String, Map<String, String>> resMap = null;
			List<List<String>> rowslists = getRows(dataList);
			rowslists.add(0, range(makePrefix(application, namespace)));

			List<String> rows = new ArrayList<String>();

			generateRows(rowslists, new int[rowslists.size()], 0, rows);

			LOGGER.info("ext find rows: " + rows.size() + " initial: "
					+ rows.get(0));

			List<List<String>> ranges = getRanges(dataList);
			
			if (ranges.size() != 0){
				LOGGER.info("ext find DB ranges: "+ranges.get(0).get(0)+"->"+ranges.get(0).get(1));
				List<String> range = ranges.remove(0);
				resMap = pubsubManager.read(application, rows, range.get(0), range.get(1));

			} else {
				resMap = pubsubManager.read(application, rows, "", ""); //there is just one elt in rows, equivalent to v1
			}
			
			List<Map<String, String>> resList = new ArrayList<Map<String, String>>();
			for ( Map<String, String> m : resMap.values()){
				resList.add(m);
		    }
			
			LOGGER.info("ext find results : {}", resList);
			
			if (resList.size() > 0){
				final List<Map<String, String>> details = pubsubManager.read(
						makePrefix(application, namespace), resList.get(0).get("id"), resList.get(0).get("publisherID"));
				
				
				for (Map<String, String> spcols : details){
					LOGGER.info("ext find first details : {}", spcols);
		    	}
			}	

		} catch (final Exception ex) {
			LOGGER.info("ext err : {}", ex);
			fail(ex.getMessage());
		}
	}
    
    
	@Test
	public void testDeleteData() {
		try {

			dataList = new ArrayList<DataBean>();
			DataBean d1 = new DataBean(KEYWORD, "test1", range(""));
			DataBean d2 = new DataBean(ENUM, "test2", range("kk2", "kk3", "kk4"));
			DataBean d3 = new DataBean(FLOAT, "test3", range("22.2222"));
			DataBean d4 = new DataBean(DATE, "somedate", range("999999999"));
			DataBean d5 = new DataBean(TEXT, "text1", range("useless because not used in delete"));
			dataList.add(d1);
			dataList.add(d2);
			dataList.add(d3);
			dataList.add(d4);
			dataList.add(d5);

			List<Index> indexesWithoutEnums = getIndexes(dataList);
			List<List<Index>> indexesEnums = getIndexesEnums(dataList);
			List<List<Index>> lli = generateIndexes(indexesWithoutEnums,
					indexesEnums);

			for (List<Index> li : lli) {
				String s1 = joinRows(li);
				String s2 = joinCols(li);
				pubsubManager.delete(makePrefix(application, namespace), s1, s2
						+ dataId, userBean.getId());
			}

			/* deletes data */
			pubsubManager.delete(makePrefix(application, namespace), dataId
					+ userBean.getId());

		} catch (final Exception ex) {
			fail(ex.getMessage());
		}
	}

    
//	@Test
//	public void testPUB() {
//		LOGGER.info("PUB:");
//		
//		String postJson = 
//				"{\"test\":12,\"data\":[{\"key\":\"cat1\",\"type\":2,\"value\":[1341783296,1341973211,\"toto\"]},{\"key\":\"autreCat\",\"type\":2,\"value\":[\"titi\",\"tonton\",\"tata\"]},{\"key\":\"date\",\"type\":3,\"value\":[999999999]},{\"key\":\"rate\",\"type\":-1,\"value\":[18.4]},{\"key\":\"europe\",\"type\":0,\"value\":[\"paris\"]},{\"key\":\"type\",\"type\":0,\"value\":[\"métier1\"]}]}";
//				
//			  //"{\"test\":12,\"ENUM\":{\"cat1\":[1341783296,1341973211, \"toto\"], \"autreCat\":[\"titi\",\"tonton\", \"tata\"]},\"DATE\":{\"date\":999999999},\"FLOAT\":{\"rate\":18.4},\"KEYWORD\":{\"europe\":\"paris\",\"type\":\"métier1\"}}";
//
//    	Message p =  GsonUtils.gson.fromJson( postJson , Message.class);
//
//    	LOGGER.info(p.toString());
//    	
//    	List<List<Index>> lli = generateIndexes(getIndexes(p.data), getIndexesEnums(p.data));
//    	LOGGER.info("--------- List of {} Indexes generated: ", lli.size());
//    	/*for (List<Index> li : lli){
//    		LOGGER.info(li.toString());
//    	}*/
//    	LOGGER.info(lli.get(0).toString());
//    	LOGGER.info(lli.get(1).toString());
//    	LOGGER.info(lli.get(2).toString());
//    	LOGGER.info(lli.get(3).toString());
//    	LOGGER.info("...");
//		assertEquals("nb of rows is ok", lli.size(), (int) Math.pow(2, 4) * 4 * 4 - 1);
//		/* 2^ (DATE+KEYWORD+GPS+FLOAT)sizes * (ENUM1 size+1) * (ENUM2 size +1) - 1  */
//
//	}
//
//	@Test
//	public void testFIND() {
//
//		LOGGER.info("FIND: ");
//		String reqJson = 
//			"{\"test\":12,\"data\":[{\"key\":\"enum\",\"type\":2,\"value\":[1341783296,\"toto\"]},{\"key\":\"autreEnum\",\"type\":2,\"value\":[\"titi\",\"tonton\",\"tata\"]},{\"key\":\"rate\",\"type\":-1,\"value\":[4,18.4]},{\"key\":\"europe\",\"type\":0,\"value\":[\"\"]},{\"key\":\"type\",\"type\":0,\"value\":[\"métier1\"]}]}";
//				
//		  //"{\"test\":12,\"ENUM\":{\"enum\":[1341783296,\"toto\"], \"autreEnum\":[\"titi\",\"tonton\", \"tata\"]},\"FLOAT\":{\"rate\":[4,18.4]},\"KEYWORD\":{\"europe\":\"\",\"type\":\"métier1\"}}";
//		Message r =  GsonUtils.gson.fromJson( reqJson , Message.class);
//
//    	List<List<String>> rowslists = getRows(r.data);
//    	LOGGER.info(r.toString());
//    	/*for (List<String> li : queryRows){
//    		LOGGER.info(li.toString());
//    	}*/
//    	
//    	List<String> rows = new ArrayList<String>();
//    	generateRows(rowslists, new int[rowslists.size()], 0, rows);
//    	LOGGER.info(" List of {} keys to search: {} ", rows.size(), rows.toString());
//    	LOGGER.info(" List of {} ranges to search: {}", getRanges(r.data).size(), getRanges(r.data).toString());
//    	
//		assertEquals(" nb of rows to find is ok", rows.size(), 90);
//	    /* (18-4+1=15) FLOAT rate slice range * (3) ENUM enum range * (2) ENUM enum range * (1) KEYWORD ranges  */
//		
//		assertEquals(" nb of cols to find between range limits is ok", getRanges(r.data).size(), 1);
//		/* only DATE, FLOAT, GPS are partitionned  */
//
//	}
	
	
}

class Message{
	int code;
	String application;
	List<DataBean> data;
	
	
	/*
	@SuppressWarnings("serial")
	public List<List<String>> getRows() {
		List<List<String>> res = new ArrayList<List<String>>();

		for (final DataBean d : data) {
			switch (d.getType()){
			case DATE:
				res.add(getDateRange(d.getKey(), Long.parseLong(d.getValue().get(0)), Long.parseLong(d.getValue().get(1))));
				break;
			case FLOAT:
				res.add(getFloatRange(d.getKey(), Float.parseFloat(d.getValue().get(0)), Float.parseFloat(d.getValue().get(1))));
				break;
			case ENUM:
				for (int k = 0; k < d.getValue().size(); k++) {
					d.getValue().set(k, d.getKey() + d.getValue().get(k));
				}
				res.add(d.getValue());
				break;
			case KEYWORD:
				res.add(new ArrayList<String>() {{ add(d.getKey() + d.getValue().get(0));}});
				break;
			}
		}
		return res;
	}
	
	public List<List<String>> getRanges() {
		List<List<String>> res = new ArrayList<List<String>>();

		for (final DataBean d : data) {
			switch (d.getType()) {
			case DATE:
			case FLOAT:
				List<String> l = new ArrayList<String>();
				l.add(d.getValue().get(0).toString());
				l.add(d.getValue().get(1).toString());
				res.add(l);
				break;
			}
		}
		return res;
	}
	
	public List<Index> getIndexes() {
		List<Index> res = new ArrayList<Index>();

		for (final DataBean d : data) {
			switch (d.getType()) {
			case DATE:
				long t = Long.parseLong(d.getValue().get(0));
				res.add(new Index(d.getKey() + (t - t % interval), padDate(t)));
				break;
			case FLOAT:
				float f = Float.parseFloat(d.getValue().get(0));
				res.add(new Index(d.getKey() + (int) f, padFloat(f)));
				break;
			case KEYWORD:
				res.add(new Index(d.getKey() + d.getValue().get(0), ""));
				break;
			}
		}
		return res;
	}
	
	public List<List<Index>> getIndexesEnums() {
		List<List<Index>> res = new ArrayList<List<Index>>();
		List<Index> en;
		for (final DataBean d : data) {
			switch (d.getType()) {
			case ENUM:
				en = new ArrayList<Index>();
				for (String s : d.getValue()) {
					en.add(new Index(d.getKey() + s, ""));
				}
				res.add(en);
				break;
			}
		}
		return res;
	}
	*/
	
	public String toString(){
		return  "\n" + new GsonBuilder().setPrettyPrinting().create().toJson(this);
	}
	
}
