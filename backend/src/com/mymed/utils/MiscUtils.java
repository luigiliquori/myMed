package com.mymed.utils;

import static com.mymed.controller.core.manager.storage.StorageManager.ENCODING;

import java.io.InputStream;
import java.io.UnsupportedEncodingException;
import java.nio.ByteBuffer;
import java.nio.charset.Charset;
import java.util.ArrayList;
import java.util.List;

import com.mymed.controller.core.exception.InternalBackEndException;

/** Misc base level java utils */
public class MiscUtils {

    /** Null or empty string ? */
    public static boolean empty(String val) {
        return (val == null) || (val.length() == 0);
    }
    
    /** Null or empty array of strings ? */
    public static boolean empty(List<String> list) {
        return (list == null) || (list.size() == 0);
    } 
    
    /** Read input stream into a String 
     * @param resURL Name of the resource : To output a clean exception if inputStream is null */
    public static String isToStr(InputStream is) {
        try {
            return new java.util.Scanner(is).useDelimiter("\\A").next();
        } catch (java.util.NoSuchElementException e) {
            return "";
        }
    }
    
    
    /** Load a resource into a string
     * @param resURL Path to the reousource : Should be absolute path */
    public static String resToStr(String resURL) {
        InputStream is = MiscUtils.class.getClassLoader().getResourceAsStream(resURL);
        if (is==null) throw new InternalBackEndException("Resource '%s' not found", resURL);
        return isToStr(is);
    }
    
    // ---------------------------------------------------------------------------
    // Encode / Decode
    // ---------------------------------------------------------------------------
    
    /** Decode a byte array into a string, using the default encoding */
    public static String decode(byte[] value) {
        return Charset.forName(ENCODING).decode(ByteBuffer.wrap(value)).toString();
    }
    
    /** Decode a byte array into a string, using the default encoding */
    public static byte[] encode(String value) {
        if (value == null) return null;
        try {
            return value.getBytes(ENCODING);
        } catch (UnsupportedEncodingException e) {
            throw new InternalBackEndException(e, "Error while encoding");
        }
    }

    public static byte[] encode(int value) {
        return encode(String.valueOf(value));
    }
    
    
    //---

	public static ArrayList<String> singleton( String v) {
		ArrayList<String> l = new ArrayList<String>();
		l.add(v);
		return l;
    }
    
}
