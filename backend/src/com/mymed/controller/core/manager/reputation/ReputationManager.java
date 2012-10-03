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
import com.mymed.utils.MiscUtils;

public class ReputationManager extends AbstractManager{
	
	private static final String CF_INTERACTION = COLUMNS
			.get("column.cf.interaction");

	public ReputationManager() throws InternalBackEndException {
		this(new StorageManager());
	}
	
	public ReputationManager(IStorageManager storageManager) throws InternalBackEndException {
		super(storageManager);
	}
	
	/*
	 * @param consumer 
	 *  not necessary now, was used before to return also if you had voted or not
	 *  but since you can't update vote with this API, it's not useful
	 */
	public Map<String, MReputationEntity> read(String app, String producer, String consumer){ 
		
		LOGGER.info(" >>reps>> {}, {}",app, producer);
		
		Map<ByteBuffer, List<ColumnOrSuperColumn>> map = 
				storageManager.batch_read("ReputationEntity", getKeys(app, producer));
		
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

	private List<String> getKeys(String app, String producer){
		List<String> keysStr = new ArrayList<String>();
		List<String> apps = MiscUtils.json_to_list(app);
		List<String> prods = MiscUtils.json_to_list(producer);
		
		if (apps.size() !=0 && prods.size() !=0){
			for (String i : apps){keysStr.add(MymedRepId.MakeId(i, producer, ReputationRole.Producer));}
		} else if (prods.size() == 0){
			for (String i : apps){keysStr.add(MymedRepId.MakeAppId(i));}
		} else if (apps.size() == 0){
			for (String i : prods){keysStr.add(MymedRepId.MakeUserId(i));}
		} 

		return keysStr;
	}

}
