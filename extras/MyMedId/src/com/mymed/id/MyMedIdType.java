//package com.mymed.data.id;
package com.mymed.id;

import java.nio.ByteBuffer;

import org.apache.cassandra.db.marshal.AbstractType; 
import org.apache.cassandra.db.marshal.MarshalException;
/**
 * 
 * @author iacopo
 *	New cassandra column type, used as identifier for myJam application.
 */
public class MyMedIdType extends AbstractType<MyMedId>{

	public static final MyMedIdType instance = new MyMedIdType();
	
	MyMedIdType(){} //Singleton

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
        	MyMedId r1 = MyMedId.parseByteBuffer(o1);
        	MyMedId r2 = MyMedId.parseByteBuffer(o2);
        	int res = r1.getType()-r2.getType();
        	if (res!=0)
        		return res;
            res = Long.signum(r1.getTimestamp()-r2.getTimestamp());
            if (res!=0)
            	return res;
            else
            	return r1.getUserId().compareTo(r2.getUserId());
        }catch (WrongFormatException e) {  
                throw new MarshalException("Could not parse ByteBuffer object", e);
        }
	}

	@Override
	public MyMedId compose(ByteBuffer arg0) {
        try{
        	MyMedId myJamId = MyMedId.parseByteBuffer(arg0);
        	return myJamId;
        }catch (WrongFormatException e) {  
                throw new MarshalException("Compose: Could not parse ByteBuffer object", e);
        }
	}

	@Override
	public ByteBuffer decompose(MyMedId arg0) {
		return arg0.ReportIdAsByteBuffer();
	}

	@Override
	public String getString(ByteBuffer arg0) {
		if (arg0.remaining() == 0)
        {
            return "";
        }
        try{
        	MyMedId myJamId = MyMedId.parseByteBuffer(arg0);
        	return myJamId.toString();
        }catch (WrongFormatException e) {  
                throw new MarshalException("getString: Could not parse ByteBuffer object", e);
        }
	}

	@Override
	public void validate(ByteBuffer arg0) throws MarshalException {
		if (arg0.remaining() < 10 && arg0.remaining() != 0)
            throw new MarshalException(String.format("ReportID should be greater then 8 or 0 bytes (%d)", arg0.remaining()));
		if (arg0.remaining()>0){
			try{
	        	MyMedId.parseByteBuffer(arg0);
	        }catch (WrongFormatException e) { 
	            throw new MarshalException("validate: Invalid format : "+e.getMessage(),e);
	        }
		}
	}

}
