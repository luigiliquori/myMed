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
import com.mymed.controller.core.manager.storage.IStorageManager;
import com.mymed.controller.core.manager.storage.v2.StorageManager;
import com.mymed.model.data.reputation.MReputationEntity;
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
	
	public MReputationEntity read(String producer, String consumer){
		final Map<byte[], byte[]> args = storageManager.selectAll("ReputationEntity", MymedRepId.MakeUserId(producer));
		LOGGER.info(" >>rep of user  >> {}", producer);
		MReputationEntity reputationEntity = (MReputationEntity) introspection(MReputationEntity.class, args);
		return reputationEntity;
	}
	
	public MReputationEntity read(String id, String producer, String consumer){
		final Map<byte[], byte[]> args = storageManager.selectAll("ReputationEntity", MymedRepId.MakeAppId(id));
		LOGGER.info(" >>rep of >> {}", id);
		MReputationEntity reputationEntity = (MReputationEntity) introspection(MReputationEntity.class, args);
    	reputationEntity.setRated(false);
		final Map<byte[], byte[]> existingInteractionMap = storageManager.selectAll(CF_INTERACTION, id+producer+consumer);
		if (existingInteractionMap.size() > 0)
			reputationEntity.setRated(true);
		return reputationEntity;
	}
	
	public Map<String, MReputationEntity> read(final List<String> ids, String producer, String consumer){
		
		List<String> keysStr = new ArrayList<String>();
		for (String id : ids){
			keysStr.add(MymedRepId.MakeAppId(id));
		}
		LOGGER.info(" >>reps>> {}", ids);
		Map<ByteBuffer, List<ColumnOrSuperColumn>> map = storageManager.batch_read("ReputationEntity", keysStr);
		Map<String, MReputationEntity> res = new HashMap<String, MReputationEntity>();

		// prepare a list of interaction Id's to checks of we have voted on them
        final Map<String, String> interactionToId = new HashMap<String, String>();
        
        for (Entry<ByteBuffer, List<ColumnOrSuperColumn>> entry : map.entrySet()){
        	
        	Map<byte[], byte[]> args = MConverter.columnsToMap(entry.getValue());
        		
        	MReputationEntity reputationEntity = (MReputationEntity) introspection(MReputationEntity.class, args);
        	String key = MConverter.byteBufferToString(entry.getKey());
        	
        	reputationEntity.setRated(false);
        	
        	String[] pieces = key.split("\\|");
        	res.put(pieces[1], reputationEntity);
        	interactionToId.put(pieces[1]+producer+consumer, pieces[1]);
        		
        }
        List<String> itrkeys = new ArrayList<String>(interactionToId.keySet());
        LOGGER.info(" >>>> {}", interactionToId);
		map = storageManager.batch_read(CF_INTERACTION, itrkeys);
		for (Entry<ByteBuffer, List<ColumnOrSuperColumn>> entry : map.entrySet()) {
			
			String key = MConverter.byteBufferToString(entry.getKey());
			Map<byte[], byte[]> args = MConverter.columnsToMap(entry.getValue());
			LOGGER.info(" >> {} with {}", key, args.size());
			if (args.size() > 0)
				res.get(interactionToId.get(key)).setRated(true);
		}
		
        
		return res;
	}

}
