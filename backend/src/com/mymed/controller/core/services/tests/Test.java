package com.mymed.controller.core.services.tests;

import java.io.UnsupportedEncodingException;
import java.util.HashMap;
import java.util.Map;

import org.apache.cassandra.thrift.InvalidRequestException;
import org.apache.cassandra.thrift.NotFoundException;
import org.apache.cassandra.thrift.TimedOutException;
import org.apache.cassandra.thrift.UnavailableException;
import org.apache.thrift.TException;

import com.mymed.model.core.wrapper.Wrapper;

public class Test {

	public static void main(String args[]){
		Map<String, byte[]> values = new HashMap<String, byte[]>();
		try {
			
			values.put("id", "1234".getBytes("UTF8"));
			values.put("name", "toto".getBytes("UTF8"));
			values.put("job", "student".getBytes("UTF8"));
		} catch (UnsupportedEncodingException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		new Wrapper().insertInto("Users", "1234", values);
		
		try {
			System.out.println("name = " + new String(new Wrapper().selectColumn("Users", "1234", "name")));
		} catch (UnsupportedEncodingException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (InvalidRequestException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (NotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (UnavailableException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (TimedOutException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (TException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
}
