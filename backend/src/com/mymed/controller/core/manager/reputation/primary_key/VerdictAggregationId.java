/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mymed.controller.core.manager.reputation.primary_key;

import java.nio.ByteBuffer;

import com.mymed.utils.MConverter;

/**
 *
 * @author piccolo
 */
public class VerdictAggregationId {
    private String verdictListId;

    public VerdictAggregationId(String verdictListId) {
        this.verdictListId = verdictListId;
    }

    public String getVerdictListId() {
        return verdictListId;
    }
    
    @Override
    public String toString(){
        return verdictListId;
    }
    
    public ByteBuffer getVerdictAggregationIdAsByteBuffer(){
        return ByteBuffer.wrap(verdictListId.getBytes());
    }
    
    public static VerdictAggregationId parseByteBuffer(ByteBuffer arg0) throws Exception{
        return new VerdictAggregationId(MConverter.byteBufferToString(arg0));
    }
    
    public static VerdictAggregationId parseString(String arg0) throws Exception{
        return new VerdictAggregationId(arg0);
    }
    
    public static void main(String[] a) throws Exception{
        System.out.println(parseByteBuffer(new VerdictAggregationId("pippo").getVerdictAggregationIdAsByteBuffer()));
    }
    
    
}
