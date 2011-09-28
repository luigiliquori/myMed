package com.mymed.controller.core.manager.reputation.primary_key;

import java.nio.ByteBuffer;
import java.nio.CharBuffer;

public class VerdictId{
    private String judgeId;
    private String chargedId;
    private String applicationId;
    private long time;


    public VerdictId(String judgeId, String chargedId, String applicationId, long time) {
        this.judgeId = judgeId;
        this.chargedId = chargedId;
        this.applicationId = applicationId;
        this.time = time;
    }

    /**
     * Returns a String representation of the Id
     */
    @Override
        public String toString(){
	return judgeId + PKConstants.SEPARATOR_CHAR + 
               chargedId + PKConstants.SEPARATOR_CHAR +
               applicationId + PKConstants.SEPARATOR_CHAR +
               String.valueOf(time);		
    }

    public ByteBuffer getVerdictIdAsByteBuffer(){
        String str = toString();
        byte[] verdictIdBB = str.getBytes(PKConstants.CHARSET);
        ByteBuffer buffer = ByteBuffer.allocate(verdictIdBB.length);
        buffer.clear();
        buffer.put(verdictIdBB);
        buffer.compact();
        return buffer;
    }
    
    public static VerdictId parseByteBuffer(ByteBuffer arg0) throws Exception{
        ByteBuffer tmp = arg0.slice();
        CharBuffer buf = PKConstants.CHARSET.newDecoder().decode(tmp);
        String str = buf.toString();
        return parseString(str);
    }

    public static VerdictId parseString(String arg0) throws Exception{
        String judgeId, chargedId, applicationId, times;
        judgeId = arg0.substring(0, arg0.indexOf(PKConstants.SEPARATOR_CHAR));
         chargedId = arg0.substring(arg0.indexOf(PKConstants.SEPARATOR_CHAR) + PKConstants.SEPARATOR_CHAR.length(), 
                arg0.indexOf(PKConstants.SEPARATOR_CHAR, 
                arg0.indexOf(PKConstants.SEPARATOR_CHAR)+ PKConstants.SEPARATOR_CHAR.length()));
         applicationId = arg0.substring(arg0.indexOf(PKConstants.SEPARATOR_CHAR, 
                arg0.indexOf(PKConstants.SEPARATOR_CHAR)+ PKConstants.SEPARATOR_CHAR.length()) + PKConstants.SEPARATOR_CHAR.length(),
                arg0.indexOf(PKConstants.SEPARATOR_CHAR,arg0.indexOf(PKConstants.SEPARATOR_CHAR, 
                arg0.indexOf(PKConstants.SEPARATOR_CHAR)+PKConstants.SEPARATOR_CHAR.length()) +PKConstants.SEPARATOR_CHAR.length()));
        times = arg0.substring(arg0.indexOf(PKConstants.SEPARATOR_CHAR,arg0.indexOf(PKConstants.SEPARATOR_CHAR, 
                arg0.indexOf(PKConstants.SEPARATOR_CHAR)+PKConstants.SEPARATOR_CHAR.length()) +PKConstants.SEPARATOR_CHAR.length()) + PKConstants.SEPARATOR_CHAR.length());
        return new VerdictId(judgeId,chargedId,applicationId,Long.parseLong(times));
    }

    public String getApplicationId() {
        return applicationId;
    }

    public String getChargedId() {
        return chargedId;
    }

    public String getJudgeId() {
        return judgeId;
    }

    public long getTime() {
        return time;
    }
    
    public static void main(String[] a) throws Exception{
        VerdictId v = VerdictId.parseString("user1|PK|char1|PK|app1|PK|1316001494069");
        System.out.println(VerdictId.parseByteBuffer(v.getVerdictIdAsByteBuffer()));
    }
}