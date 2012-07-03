package com.mymed.utils;

import java.io.InputStream;

import com.mymed.controller.core.exception.InternalBackEndException;

/** Misc base level java utils */
public class MiscUtils {

    /** Null or empty string ? */
    public static boolean empty(String val) {
        return (val == null) || (val.length() == 0);
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
    
}
