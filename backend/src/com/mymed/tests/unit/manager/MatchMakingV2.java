package com.mymed.tests.unit.manager;

import static com.mymed.model.data.application.MOntologyID.*;
import static com.mymed.utils.GsonUtils.gson;
import static com.mymed.utils.MatchMaking.makePrefix;
import static org.junit.Assert.assertEquals;
import static org.junit.Assert.fail;

import java.util.ArrayList;
import java.util.Collections;
import java.util.HashMap;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;

import org.junit.Before;
import org.junit.Test;
import org.slf4j.LoggerFactory;

import ch.qos.logback.classic.Logger;

import com.google.gson.GsonBuilder;
import com.google.gson.JsonParseException;
import com.google.gson.JsonSyntaxException;
import com.google.gson.reflect.TypeToken;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.pubsub.v2.PubSubManager;
import com.mymed.model.data.application.IndexBean;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.MatchMaking;
import com.mymed.utils.MatchMaking.Index;
import com.mymed.utils.MatchMaking.IndexRow;

public class MatchMakingV2 extends TestValues {
	
	
	final static Logger LOGGER = (Logger) LoggerFactory.getLogger(MatchMakingV2.class);

	protected static PubSubManager pubsubManager;
	private static MUserBean userBean;
	private static final String date = "1971-01-01";
	private static List<IndexBean> indexList;
	
	private static Map<String, String> dataList;
	
	private static String dataId = "mydata";

	private static String application = "myTest";

	private static String namespace = "fake";

	private static ArrayList<String> range(final String... args) {
		ArrayList<String> result = new ArrayList<String>();
		for (String s : args) {
			result.add(s);
		}
		return result;
	}

//	@Test
//	public void testCombi() {
//
//		LOGGER.info(" testCombi: ");
//		IndexBean d1 = new IndexBean(KEYWORD, "A", range("a"));
//		IndexBean d2 = new IndexBean(ENUM, "B", range("b1", "b2", "b3"));
//		//IndexBean d3 = new IndexBean(FLOAT, "C", range("22.2222"));
//		IndexBean d4 = new IndexBean(ENUM, "C", range("c6", "c7", "c8", "c9"));
//		//DataBean d4 = new DataBean(DATE, "somedate", range("999999999"));
//		//DataBean d5 = new DataBean(TEXT, "text1", range("........."));
//		indexList = new ArrayList<IndexBean>();
//		indexList.add(d1);
//		indexList.add(d2);
//		//indexList.add(d3);
//		indexList.add(d4);
//		//dataList.add(d5);
//		
//		LinkedHashMap<String, List<String>> indexes = MatchMaking.formatIndexes(indexList);
//		LOGGER.info("indexes: {} ", indexes );
//		List<IndexRow> combi = MatchMaking.getPredicate(indexes, 0, indexes.size());
//		
//		LOGGER.info("ext find rows: " + combi.size() );
//
//		LOGGER.info("combi : {} ", combi );
//		
//		
//		String prefix = makePrefix(application, namespace);
//		
//	}

    
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
			IndexBean d1 = new IndexBean(KEYWORD, "A", range("a"));
			IndexBean d2 = new IndexBean(ENUM, "B", range("b1", "b2", "b3"));
			IndexBean d4 = new IndexBean(ENUM, "C", range("c6", "c7", "c8", "c9"));
			indexList = new ArrayList<IndexBean>();
			indexList.add(d1);
			indexList.add(d2);
			indexList.add(d4);
			
			dataList = new HashMap<String, String>();
			dataList.put("text1", ".........");
			
			LinkedHashMap<String, List<String>> indexes = MatchMaking.formatIndexes(indexList);
			
			List<IndexRow> combi = MatchMaking.getPredicate(indexes, 0, indexList.size());
			
			Map<String , String> metas = new HashMap<String, String>();
			metas.put("id", dataId);
	    	
	    	for (IndexRow i : combi) {
				pubsubManager
						.create(makePrefix(application, namespace), i.toString(),
								dataId, metas);
				/*pubsubManager.sendEmailsToSubscribers(
						makePrefix(application, namespace), i.row,
						dataList);*/
			}
			
			/* add indexes as a data, the only way to remove all the indexes later if necessary*/
	    	dataList.put("_index", gson.toJson(indexList));
			/* creates data */
			pubsubManager.create(makePrefix(application, namespace), dataId, dataList);

		} catch (final Exception ex) {
			fail(ex.getMessage());
		}
	}
    
	

    @Test
	public void testFindData() {
		try {

			indexList = new ArrayList<IndexBean>();
			indexList.add(new IndexBean(ENUM, "B", range("b2", "b3")));
			indexList.add(new IndexBean(ENUM, "C", range("c9", "c10")));

			LinkedHashMap<String, List<String>> indexes = MatchMaking
					.formatIndexes(indexList);

			List<IndexRow> combi = MatchMaking.getPredicate(indexes, indexList.size(),
					indexList.size());

			List<Map<String, String>> resList = new ArrayList<Map<String, String>>();

			/* resultMap for range query */
			Map<String, Map<String, String>> resMap;

			String prefix = makePrefix(application, namespace);
			for (IndexRow r : combi) {
				r.add(0, new Index(prefix, ""));
			}

			LOGGER.info("ext find rows: " + combi.size() + " initial: "
					+ combi.get(0));

			List<List<String>> ranges = MatchMaking.getRanges(indexList);

			List<String> rows = IndexRow.getRows(combi);

			LOGGER.info("ext find ranges: " + ranges.size());
			
			if (ranges.size() != 0) {
				LOGGER.info("ext find DB ranges: " + ranges.get(0).get(0)
						+ "->" + ranges.get(0).get(1));
				List<String> range = ranges.remove(0);
				resMap = pubsubManager.read(application, rows,
						range.get(0), range.get(1));
			} else {
				resMap = pubsubManager.read(application, rows, "", "");
			}
			
			resList = new ArrayList<Map<String, String>>();
			for ( Map<String, String> m : resMap.values()){
				resList.add(m);
		    }
			
			LOGGER.info("ext find {} results : {}", resList.size(), resList);
			
			if (resList.size() > 0){
				final Map<String, String> details = pubsubManager.read(
						makePrefix(application, namespace), resList.get(0).get("id"));
				
				LOGGER.info("ext find first details : {}", details);
				
				assertEquals(details.get("text1"), ".........");
			}	

		} catch (final Exception ex) {
			LOGGER.info("ext err : {}", ex);
			fail(ex.getMessage());
		}
	}
    
	public static void main(String[] args) {
		
	}
	
	@Test
	public void testDeleteData() {
		try {
			String index = pubsubManager.read(makePrefix(application, namespace), dataId, "_index");

			List<IndexBean> indexList = new ArrayList<IndexBean>();
			try {
				indexList = gson.fromJson(index,
						new TypeToken<List<IndexBean>>() {}.getType());
			} catch (final JsonSyntaxException e) {
				LOGGER.debug("Error in Json format", e);
				throw new InternalBackEndException(
						"jSon format is not valid");
			} catch (final JsonParseException e) {
				LOGGER.debug("Error in parsing Json", e);
				throw new InternalBackEndException(e.getMessage());
			}
			Collections.sort(indexList);

			LOGGER.info(" deleting  " + dataId + "." + indexList.size());

			
			LinkedHashMap<String, List<String>> indexes = MatchMaking
					.formatIndexes(indexList);

			List<IndexRow> combi = MatchMaking.getPredicate(indexes, 0,
					indexList.size());
			for (IndexRow i : combi) {
				pubsubManager.delete(makePrefix(application, namespace),
						i.toString(), dataId, null);
			}

			/* deletes data */
			pubsubManager
					.delete(makePrefix(application, namespace), dataId);

		} catch (final Exception ex) {
			fail(ex.getMessage());
		}
	}

	
}




class Message{ // for sending full json messages
	int code;
	String application;
	//List<DataBean> data;
	
	
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
