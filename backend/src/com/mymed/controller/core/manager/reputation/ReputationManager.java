package com.mymed.controller.core.manager.reputation;

import java.nio.ByteBuffer;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;

import org.apache.cassandra.thrift.ColumnOrSuperColumn;

import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.reputation.api.mymed_ids.MymedRepId;
import com.mymed.controller.core.manager.reputation.api.mymed_ids.ReputationRole;
import com.mymed.controller.core.manager.storage.IStorageManager;
import com.mymed.controller.core.manager.storage.v2.StorageManager;
import com.mymed.model.data.reputation.MReputationEntity;
import com.mymed.utils.GsonUtils;
import com.mymed.utils.MConverter;

public class ReputationManager extends AbstractManager{
	
	private static final String CF_INTERACTION = COLUMNS
			.get("column.cf.interaction");

	public ReputationManager() throws InternalBackEndException {
		this(new StorageManager());
	}
	
	public ReputationManager(IStorageManager storageManager) throws InternalBackEndException {
		super(storageManager);
	}
	

	public Map<String, MReputationEntity> read(String app, String producer, String consumer){
		
		LOGGER.info(" >>reps>> {}, {}",app, producer);
		
		Map<ByteBuffer, List<ColumnOrSuperColumn>> map = null;
		if (app!=null && producer !=null){
			map = storageManager.batch_read("ReputationEntity", getAppUserKeys(app, producer));
		} else if (app!=null){
			map = storageManager.batch_read("ReputationEntity", getAppKeys(app));
		} else if (producer!=null){
			map = storageManager.batch_read("ReputationEntity", getUserKeys(producer));
		}

		if (map==null){
			throw new InternalBackEndException("missing some arguments!");
		}
		Map<String, MReputationEntity> res = new HashMap<String, MReputationEntity>();
		for (Entry<ByteBuffer, List<ColumnOrSuperColumn>> entry : map.entrySet()) {
			Map<byte[], byte[]> args = MConverter.columnsToMap(entry.getValue());
        	MReputationEntity reputationEntity = (MReputationEntity) introspection(MReputationEntity.class, args);
        	String key = MConverter.byteBufferToString(entry.getKey());
        	String[] pieces = key.split("\\|");
        	res.put(pieces[1], reputationEntity);
		}

		return res;
	}
	
	
	private List<String> getAppKeys(String s){
		List<String> keysStr = new ArrayList<String>();
		if (s.startsWith("[")) { // list of id's
			List<String> ids = GsonUtils.json_to_list(s);
			for (String i : ids){
				keysStr.add(MymedRepId.MakeAppId(i));
			}
		} else {
			keysStr.add(MymedRepId.MakeAppId(s));
		}
		return keysStr;
	}
	private List<String> getUserKeys(String s){
		List<String> keysStr = new ArrayList<String>();
		if (s.startsWith("[")) { // list of id's
			List<String> ids = GsonUtils.json_to_list(s);
			for (String i : ids){
				keysStr.add(MymedRepId.MakeUserId(i));
			}
		} else {
			keysStr.add(MymedRepId.MakeUserId(s));
		}
		return keysStr;
	}
	private List<String> getAppUserKeys(String app, String producer){
		List<String> keysStr = new ArrayList<String>();
		if (app.startsWith("[")) { // list of id's
			List<String> ids = GsonUtils.json_to_list(app);
			for (String i : ids){
				keysStr.add(MymedRepId.MakeId(i, producer, ReputationRole.Producer));
			}
		} else {
			keysStr.add(MymedRepId.MakeId(app, producer, ReputationRole.Producer));
		}
		return keysStr;
	}

}
