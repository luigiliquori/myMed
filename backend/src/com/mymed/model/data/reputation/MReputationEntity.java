package com.mymed.model.data.reputation;

import com.mymed.model.data.AbstractMBean;

public class MReputationEntity extends AbstractMBean {
	// Used for the hashCode
	private static final int PRIME = 31;

	private String uid; /* reputation entity id */
	private double reputation; /* most recently calculated reputation */
	private int numberOfVerdicts; /* number of verdicts used for calculation */
	/*
	 * true if verdicts have been added since last calculation
	 */
	private boolean dirty;
	
	/** true is you consumer have already voted on that Id @see ReputationManager */
	private boolean rated; 
	

	public MReputationEntity() {
		super();
	}

	public MReputationEntity(String uid, double reputation,
			int numberOfVerdicts, boolean dirty) {
		super();
		this.uid = uid;
		this.reputation = reputation;
		this.numberOfVerdicts = numberOfVerdicts;
		this.dirty = dirty;
	}

	public String getUid() {
		return uid;
	}

	public void setUid(String uid) {
		this.uid = uid;
	}

	public double getReputation() {
		return reputation;
	}

	public void setReputation(double reputation) {
		this.reputation = reputation;
	}

	public int getNumberOfVerdicts() {
		return numberOfVerdicts;
	}

	public void setNumberOfVerdicts(int numberOfVerdicts) {
		this.numberOfVerdicts = numberOfVerdicts;
	}

	public boolean isDirty() {
		return dirty;
	}

	public void setDirty(boolean dirty) {
		this.dirty = dirty;
	}

	public boolean isRated() {
		return rated;
	}

	public void setRated(boolean rated) {
		this.rated = rated;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see java.lang.Object#hashCode()
	 */
	@Override
	public int hashCode() {
		int result = 1;
		return result;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see java.lang.Object#equals(java.lang.Object)
	 */
	@Override
	public boolean equals(final Object obj) {
		boolean equal = false;
		return equal;
	}

}
