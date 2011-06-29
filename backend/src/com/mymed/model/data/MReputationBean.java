package com.mymed.model.data;

/**
 * 
 * @author lvanni
 *
 */
public class MReputationBean extends AbstractMBean {

	/* --------------------------------------------------------- */
	/* Attributes */
	/* --------------------------------------------------------- */
	private double value;
	private int nbRaters;

	/* --------------------------------------------------------- */
	/* Constructors */
	/* --------------------------------------------------------- */
	public MReputationBean() {
		// TODO Auto-generated constructor stub
	}
	
	/* --------------------------------------------------------- */
	/* Override methods */
	/* --------------------------------------------------------- */
	@Override
	public String toString() {
		return "Reputation:\n" + super.toString();
	}

	/* --------------------------------------------------------- */
	/* GETTER AND SETTER */
	/* --------------------------------------------------------- */
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
