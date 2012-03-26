/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.globals;

import java.nio.charset.Charset;

/**
 * A few constants used for creating Cassandra keys
 * @author piccolo, neuss
 */
public class PKConstants {
    public final static Charset CHARSET = Charset.forName("UTF8");
    public final static short LONG_BYTESIZE = 8;
    public final static String SEPARATOR_CHAR = "|";
    //public final static String SEPARATOR = "|";
}
