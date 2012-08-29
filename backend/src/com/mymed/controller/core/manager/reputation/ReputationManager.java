package com.mymed.controller.core.manager.reputation;

import java.nio.ByteBuffer;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;

import org.apache.cassandra.thrift.ColumnOrSuperColumn;

import com.mymed.controller.core.exception.IOBackEndException;
import com.mymed.controller.core.exception.InternalBackEndException;
import com.mymed.controller.core.manager.AbstractManager;
import com.mymed.controller.core.manager.reputation.api.mymed_ids.MymedAppUserId;
import com.mymed.controller.core.manager.storage.IStorageManager;
import com.mymed.controller.core.manager.storage.v2.StorageManager;
import com.mymed.model.data.interaction.MInteractionBean;
import com.mymed.model.data.reputation.MReputationEntity;
import com.mymed.model.data.session.MAuthenticationBean;
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
	
	public MReputationEntity read(final MymedAppUserId key, String consumer){
		final Map<byte[], byte[]> args = storageManager.selectAll("ReputationEntity", key.getPrimaryId());
		MReputationEntity reputationEntity = (MReputationEntity) introspection(MReputationEntity.class, args);
		
		String[] pieces = key.getPrimaryId().split("\\|");
    	if (pieces.length<=2)
    		throw new IOBackEndException("can't parse the fucking elts of uid", 501);
    	
    	reputationEntity.setRated(false);
		
		final Map<byte[], byte[]> existingInteractionMap = storageManager.selectAll(CF_INTERACTION, pieces[1]+pieces[2]+consumer);
		if (existingInteractionMap.size() > 0)
			reputationEntity.setRated(true);
		
		return reputationEntity;
	}
	
	public Map<String, MReputationEntity> read(final List<MymedAppUserId> keys, String consumer){
		
		List<String> keysStr = new ArrayList<String>();
		for (MymedAppUserId id : keys){
			keysStr.add(id.getPrimaryId());
		}
		
		Map<ByteBuffer, List<ColumnOrSuperColumn>> map = storageManager.batch_read("ReputationEntity", keysStr);
		
		Map<String, MReputationEntity> res = new HashMap<String, MReputationEntity>();

		// prepare a list of interaction Id's to checks of we have voted on them
        keysStr.clear();
        final Map<String, String> interactionToProducer = new HashMap<String, String>();
        
        for (Entry<ByteBuffer, List<ColumnOrSuperColumn>> entry : map.entrySet()){
        	
        	Map<byte[], byte[]> args = MConverter.columnsToMap(entry.getValue());
        		
        	MReputationEntity reputationEntity = (MReputationEntity) introspection(MReputationEntity.class, args);
        	String key = MConverter.byteBufferToString(entry.getKey());
        	
        	reputationEntity.setRated(false);
        	
        	String[] pieces = key.split("\\|");
        	if (pieces.length>2){
        		res.put(pieces[2], reputationEntity);
        		interactionToProducer.put(pieces[1]+pieces[2]+consumer, pieces[2]);
        	}
        		
        }

		map = storageManager.batch_read(CF_INTERACTION, keysStr);
		for (Entry<ByteBuffer, List<ColumnOrSuperColumn>> entry : map.entrySet()) {

			String key = MConverter.byteBufferToString(entry.getKey());
			
			if (interactionToProducer.containsKey(key))
				res.get(interactionToProducer.get(key)).setRated(true);
		}
		
        
		return res;
	}

}
