package com.mymed.controller.core.manager.reputation.primary_key.comparator;

import java.nio.ByteBuffer;

import org.apache.cassandra.db.marshal.AbstractType;
import org.apache.cassandra.db.marshal.MarshalException;

import com.mymed.controller.core.manager.reputation.primary_key.VerdictId;

public class OrderByTimeVerdict extends AbstractType<VerdictId>{

    public static final OrderByTimeVerdict instance = new OrderByTimeVerdict();
    
    OrderByTimeVerdict(){} //Singleton
    
    @Override
    public int compare(ByteBuffer o1, ByteBuffer o2) {
	// Compare two instances of 
        if (o1.remaining() == 0)
	    {
		return o2.remaining() == 0 ? 0 : -1;
	    }
        if (o2.remaining() == 0)
	    {
		return 1;
	    }
        
        try{
	    VerdictId r1 = VerdictId.parseByteBuffer(o1);
	    VerdictId r2 = VerdictId.parseByteBuffer(o2);
	    if(r1.getTime() > r2.getTime()){
		return 1;
	    }
	    else{
		if(r1.getTime() < r2.getTime()){
		    return -1;
		}
		else{
		    if(r1.getJudgeId().equals(r2.getJudgeId())){
                        if(r1.getChargedId().equals(r2.getChargedId())){
                            if(r1.getApplicationId().equals(r2.getApplicationId())){
                                return 0;
                            }
                            else{
                                return r1.getApplicationId().compareTo(r2.getApplicationId());
                            }
                        }
                        else{
                            return r1.getChargedId().compareTo(r2.getChargedId());
                        }
                    }
                    else{
                        return r1.getJudgeId().compareTo(r2.getJudgeId());
                    }
		}
	    }
	}
	catch(Exception e){
                throw new MarshalException("Could not parse ByteBuffer object", e);	
        }
    }
    
    @Override
	public VerdictId compose(ByteBuffer arg0) {
        try{
	    VerdictId verdictId = VerdictId.parseByteBuffer(arg0);
	    return verdictId;
        }catch (Exception e) {  
	    throw new MarshalException("Compose: Could not parse ByteBuffer object", e);
        }
    }
    
    @Override
	public ByteBuffer decompose(VerdictId arg0) {
	return arg0.getVerdictIdAsByteBuffer();
    }
    
    @Override
	public String getString(ByteBuffer arg0) {
	if (arg0.remaining() == 0)
	    {
		return "";
	    }
        try{
	    VerdictId verdictId = VerdictId.parseByteBuffer(arg0);
	    return verdictId.toString();
        }catch (Exception e) {  
	    throw new MarshalException("getString: Could not parse ByteBuffer object", e);
        }
    }
    
    @Override
	public void validate(ByteBuffer arg0) throws MarshalException {
	if (arg0.remaining() < 8 && arg0.remaining() != 0)
            throw new MarshalException(String.format("ReportID should be greater then 8 or 0 bytes (%d)", arg0.remaining()));
	if (arg0.remaining()>0){
	    try{
		VerdictId.parseByteBuffer(arg0);
	    }catch (Exception e) { 
                e.printStackTrace();
		throw new MarshalException("validate: Invalid format : "+e.getMessage(),e);
	    }
	}
    }
}