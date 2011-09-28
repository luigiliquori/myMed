/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.reputationprototype;

import java.nio.charset.Charset;

import org.apache.cassandra.db.marshal.UTF8Type;
import org.apache.commons.lang.builder.CompareToBuilder;
import org.apache.commons.lang.builder.EqualsBuilder;
import org.apache.commons.lang.builder.HashCodeBuilder;

/**
 *
 * @author piccolo
 */
public class ProvaHash implements Comparable<ProvaHash> {
    private long timestamp;
    
    private String idUser1;
    private String idUser2;
    
    @Override
    public int compareTo(ProvaHash obj){
        ProvaHash p1 = (ProvaHash)obj;
        
        return new CompareToBuilder().
                append(timestamp, p1.timestamp).
                append(idUser1, p1.idUser1).
                append(idUser2, p1.idUser2).toComparison();
    }

    @Override
    public boolean equals(Object o) {
        ProvaHash p1 = (ProvaHash)o;
        
        return new EqualsBuilder().append(idUser1, p1.idUser1).append(idUser2, p1.idUser2).append(timestamp, p1.timestamp).isEquals();
    }

    @Override
    public int hashCode() {
        return new HashCodeBuilder().append(idUser1).append(idUser2).append(timestamp).hashCode();
    }

    public ProvaHash(long timestamp, String idUser1, String idUser2) {
        this.timestamp = timestamp;
        this.idUser1 = idUser1;
        this.idUser2 = idUser2;
    }
    
    
    public static void main(String a[]){
        Long now = System.currentTimeMillis();
        
        Long now1 = now + 235452;
        
        String p1 = String.valueOf(now) + "|pippo|pluto";
        String p2 = String.valueOf(now1) + "|pippo|pluto";
        
        UTF8Type test = UTF8Type.instance;
        
        System.out.println(now.compareTo(now1));
        
        String s1 = new String(p1.getBytes(), Charset.forName("UTF8"));
        String s2 = new String(p2.getBytes(), Charset.forName("UTF8"));
        
        System.out.println(s1 + " " + s2);
        System.out.println(s1.compareTo(s2));
        
    }
    
}
