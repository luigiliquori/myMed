package com.mymed.tests.unit.manager;

import static com.mymed.utils.GsonUtils.gson;

import static org.junit.Assert.*;

import java.util.ArrayList;

import java.util.HashMap;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;

import org.junit.Before;
import org.junit.Test;
import org.slf4j.LoggerFactory;

import ch.qos.logback.classic.Logger;

import com.google.gson.JsonParseException;
import com.google.gson.JsonSyntaxException;
import com.google.gson.reflect.TypeToken;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.publish.PublishManager;
import com.mymed.model.data.application.DataBean;
import com.mymed.model.data.application.MOntologyID;
import com.mymed.model.data.user.MUserBean;
import com.mymed.utils.MatchMakingv2;
import com.mymed.utils.CombiLine;

public class MatchMakingV2 extends TestValues {
	
	
	final static Logger LOGGER = (Logger) LoggerFactory.getLogger(MatchMakingV2.class);

	protected static PublishManager publishManager;
	private static MUserBean userBean;
	private static final String date = "1971-01-01";
	
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
	
	public static void main(String[] args) {
		LOGGER.info(" testCombi: ");

		List<DataBean> indexes = new ArrayList<DataBean>();
		
		indexes.add(new DataBean("app", "test", MOntologyID.ENUM));

		//indexes.add(new DataBean("Pos", "43.54545|7.1224", MOntologyID.GPS));
		indexes.add(new DataBean("A", "a5|a6", MOntologyID.ENUM));
		indexes.add(new DataBean("B", "b0|b1", MOntologyID.ENUM));
		indexes.add(new DataBean("C", "clothes", MOntologyID.ENUM));
		//indexes.add(new DataBean("D", "|d0", MOntologyID.ENUM));
		
		LinkedHashMap<String, List<String>> keywords = MatchMakingv2.format(indexes);

		
		LOGGER.info("indexes: {} ", indexes );
		LOGGER.info("keywords: {} ", keywords );
		
		List<String> rows = new CombiLine(keywords).expand();
		MatchMakingv2.prefix(application, rows);
		
		LOGGER.info("rows {}: {}",rows.size(), rows);


	}

	@Test
	public void testCombi() {


		
	}
	
	@Before
	public void setUp() throws InternalBackEndException {
		// storageManager = new StorageManager(new WrapperConfiguration(new
		// File(CONF_FILE)));
		publishManager = new PublishManager();

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
		userBean.setApplicationList(SUBSCRIPTION_LST_ID);

	}
    
	@Test
	public void testCreateData() {
		try {
			
			List<DataBean> indexes = new ArrayList<DataBean>();
			
			indexes.add(new DataBean("1", "43.54545|7.1224", MOntologyID.GPS));
			indexes.add(new DataBean("2", "|a6|a7|a8", MOntologyID.ENUM));
			
			dataList = new HashMap<String, String>();
			dataList.put("text1", ".........");

			LinkedHashMap<String, List<String>> keywords = MatchMakingv2.format(indexes);

			LOGGER.info("keywords: {} ", keywords );
			
			List<String> rows = new CombiLine(keywords).expand();
			MatchMakingv2.prefix("AZERTY", rows);

			LOGGER.info("rows {} ", rows );
			
			Map<String , String> metas = new HashMap<String, String>();
			metas.put("id", dataId);
	    	
	    
			publishManager.create(rows, dataId, metas);

			/* add indexes as a data, the only way to remove all the indexes later if necessary*/
			
	    	dataList.put("predicates", gson.toJson(keywords));
			/* creates data */
	    	publishManager.create("AZERTY"+ dataId, dataList);

		} catch (final Exception ex) {
			fail(ex.getMessage());
		}
	}
    
	

    @Test
	public void testFindData() {
		try {

			List<DataBean> indexes = new ArrayList<DataBean>();
			
			indexes.add(new DataBean("1", "43.54541|7.1224|43.5458|7.1225", MOntologyID.GPS));
			indexes.add(new DataBean("2", "a8|a9", MOntologyID.ENUM));

			LinkedHashMap<String, List<String>> keywords = MatchMakingv2.format(indexes);

			LOGGER.info("keywords: {} ", keywords );
			
			List<String> rows = new CombiLine(keywords).expand();
			MatchMakingv2.prefix("AZERTY", rows);

			/* resultMap for range query */
			Map<String, Map<String, String>> resMap;

			LOGGER.info("ext find rows: " + rows);
			
			
			resMap = publishManager.read(rows, "", "");
			
			List<Map<String, String>> resList = new ArrayList<Map<String,String>>(resMap.values()); 
			
			LOGGER.info("ext find {} results : {}", resList.size(), resList);
			
			if (resList.size() > 0){
				final Map<String, String> details = publishManager.read(
						"AZERTY"+ resList.get(0).get("id"));
				
				LOGGER.info("ext find first details : {}", details);
				
				//assertEquals(details.get("text1"), ".........");
			}	

		} catch (final Exception ex) {
			LOGGER.info("ext err : {}", ex);
			fail(ex.getMessage());
		}
	}
    
	
	@Test
	public void testDeleteData() {
		try {
			String index = publishManager.read("AZERTY"+ dataId, "predicates");

			LinkedHashMap<String, List<String>> keywords = new LinkedHashMap<String, List<String>>();
			try {
				keywords = gson.fromJson(index,
						new TypeToken<Map<String, List<String>>>() {}.getType());
			} catch (final JsonSyntaxException e) {
				LOGGER.debug("Error in Json format", e);
				throw new InternalBackEndException(
						"jSon format is not valid");
			} catch (final JsonParseException e) {
				LOGGER.debug("Error in parsing Json", e);
				throw new InternalBackEndException(e.getMessage());
			}

			
			LOGGER.info(" keywords :{} " , keywords);
			LOGGER.info(" deleting  " + dataId + "." + keywords.size());

			List<String> rows = new CombiLine(keywords).expand();
			MatchMakingv2.prefix("AZERTY", rows);

			for (String i : rows) {
				publishManager.delete("", i, dataId);
			}

			/* deletes data */
			//pubsubManager.delete("AZERTY"+ dataId);

		} catch (final Exception ex) {
			fail(ex.getMessage());
		}
	}

    
}
