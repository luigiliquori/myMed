package com.mymed.model.data.reputation;

public class MReputationBean {
	
	private double reputation;
    private int noOfRatings;
    
    public MReputationBean(double reputation,int noOfRatings) {
        this.reputation = reputation;
        this.noOfRatings = noOfRatings;
    }

    public double getReputation() {
        return reputation;
    }
    
    public int getNoOfRatings(){
        return noOfRatings;
    }
}
