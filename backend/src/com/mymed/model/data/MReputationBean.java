package com.mymed.model.data;

public class MReputationBean extends AbstractMBean {

	private double value;
	private int nbRaters;

	public double getValue() {
		return value;
	}

	public void setValue(final double value) {
		this.value = value;
	}

	public int getNbRaters() {
		return nbRaters;
	}

	public void setNbRaters(final int nbRaters) {
		this.nbRaters = nbRaters;
	}

}
