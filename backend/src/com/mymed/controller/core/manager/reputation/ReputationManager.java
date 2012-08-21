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
import com.mymed.controller.core.manager.reputation.api.mymed_ids.MymedAppUserId;
import com.mymed.controller.core.manager.storage.IStorageManager;
import com.mymed.controller.core.manager.storage.v2.StorageManager;
import com.mymed.model.data.reputation.MReputationEntity;
import com.mymed.model.data.session.MAuthenticationBean;
import com.mymed.utils.MConverter;
import com.mymed.utils.MiscUtils;

public class ReputationManager extends AbstractManager{

	public ReputationManager() throws InternalBackEndException {
		this(new StorageManager());
	}
	
	public ReputationManager(IStorageManager storageManager) throws InternalBackEndException {
		super(storageManager);
	}
	
	public MReputationEntity read(final MymedAppUserId key){
		final Map<byte[], byte[]> args = storageManager.selectAll("ReputationEntity", key.getPrimaryId());
		MReputationEntity reputationEntity = (MReputationEntity) introspection(MReputationEntity.class, args);
		return reputationEntity;
	}
	
	public Map<String, MReputationEntity> read(final List<MymedAppUserId> keys){
		
		List<String> keysStr = new ArrayList<String>();
		for (MymedAppUserId id : keys){
			keysStr.add(id.getPrimaryId());
		}
		
		final Map<ByteBuffer, List<ColumnOrSuperColumn>> map = storageManager.batch_read("ReputationEntity", keysStr);
		
		Map<String, MReputationEntity> res = new HashMap<String, MReputationEntity>();

        for (Entry<ByteBuffer, List<ColumnOrSuperColumn>> entry : map.entrySet()){
        	
        	Map<byte[], byte[]> args = MConverter.columnsToMap(entry.getValue());
        		
        	MReputationEntity reputationEntity = (MReputationEntity) introspection(MReputationEntity.class, args);
        	String key = MConverter.byteBufferToString(entry.getKey());
        	
        	String[] pieces = key.split("\\|");
        	if (pieces.length>2)
        		res.put(pieces[2], reputationEntity);
        }
        
		return res;
	}

}
