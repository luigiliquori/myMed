package it.polito.cassandra.Test;



import java.nio.ByteBuffer;
import java.util.Random;

import org.apache.cassandra.db.marshal.MarshalException;

//import com.mymed.data.id.MyMedId;
import com.mymed.id.MyMedId;
//import com.mymed.data.id.MyMedIdType;
import com.mymed.id.MyMedIdType;


import junit.framework.TestCase;


public class myJamIdTest extends TestCase{
	public final static char REPORT_ID = 'r';
	public final static char UPDATE_ID = 'u';
	public final static char FEEDBACK_ID = 'f';
	
	public void testCompare() {

		Random random = new Random();
		for (int i=0;i<10000;i++){
			long num1 = Math.abs(random.nextLong());
			long num2 = Math.abs(random.nextLong());
			MyMedId myTestId1 = new MyMedId(REPORT_ID,num1,"user1");
			MyMedId myTestId2 = new MyMedId(REPORT_ID,num2,"user1");
			ByteBuffer bb1 = myTestId1.ReportIdAsByteBuffer();
			ByteBuffer bb2 = myTestId2.ReportIdAsByteBuffer();
			int int1 = MyMedIdType.instance.compare(bb1, bb2);
			int int2 = Long.signum(new Long(myTestId1.getTimestamp()).compareTo(myTestId2.getTimestamp()));
			if (int1 != int2)
				fail(String.valueOf(myTestId1.toString()) + " is not less then " +
						String.valueOf(myTestId2.toString()));
		}
		MyMedId myTestId1 = new MyMedId(REPORT_ID,1000,"user1");
		MyMedId myTestId2 = new MyMedId(REPORT_ID,1000,"aser1");
		MyMedId myTestId3 = new MyMedId(REPORT_ID,1000,"aser2");
		MyMedId myTestId4 = new MyMedId(REPORT_ID,1000,"aser1");
		ByteBuffer bb1 = myTestId1.ReportIdAsByteBuffer();
		ByteBuffer bb2 = myTestId2.ReportIdAsByteBuffer();
		ByteBuffer bb3 = myTestId3.ReportIdAsByteBuffer();
		ByteBuffer bb4 = myTestId4.ReportIdAsByteBuffer();
		MyMedIdType.instance.validate(bb1);
		MyMedIdType.instance.validate(bb1);
		MyMedIdType.instance.validate(bb2);
		MyMedIdType.instance.validate(bb3);
		MyMedIdType.instance.validate(bb4);
		int int1 = MyMedIdType.instance.compare(bb1, bb2);
		if (int1 < 1)
			fail("user1 less then aser1");
		int1 = MyMedIdType.instance.compare(bb2, bb3);
		if (int1  > -1)
			fail("user1 less then aser1");
		int1 = MyMedIdType.instance.compare(bb2, bb4);
		if (int1  != 0)
			fail("user1 less then aser1");
	}
	
	public void testValidate() {
		
		try{
			MyMedId myTestId1 = new MyMedId(REPORT_ID,-1000,"user1");
			MyMedIdType.instance.validate(myTestId1.ReportIdAsByteBuffer());
			fail("Negative timestamp.");
		}catch(MarshalException e){
			e.printStackTrace();
		}catch(RuntimeException e){
			e.printStackTrace();
		}
		try{
			MyMedId myTestId1 = new MyMedId(REPORT_ID,1000,"");
			MyMedIdType.instance.validate(myTestId1.ReportIdAsByteBuffer());
			fail("Negative timestamp.");
		}catch(MarshalException e){
			e.printStackTrace();
		}catch(RuntimeException e){
			e.printStackTrace();
		}
		try{
			MyMedId myTestId1 = new MyMedId(REPORT_ID,1000,null);
			MyMedIdType.instance.validate(myTestId1.ReportIdAsByteBuffer());
			fail("Negative timestamp.");
		}catch(MarshalException e){
			e.printStackTrace();
		}catch(RuntimeException e){
			e.printStackTrace();
		}
		try{
			MyMedIdType.instance.validate(ByteBuffer.wrap(new String("Ciao").getBytes()));
			fail("Malformed byte array.");
		}catch(MarshalException e){
			e.printStackTrace();
		}
	}
}
