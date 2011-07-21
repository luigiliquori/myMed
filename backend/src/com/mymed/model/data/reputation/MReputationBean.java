package com.mymed.model.data.reputation;

import com.mymed.model.data.AbstractMBean;

/**
 * 
 * @author lvanni
 * 
 */
public final class MReputationBean extends AbstractMBean {

	/**
	 * Used for the hash code
	 */
	private static final int PRIME = 31;

	/**
	 * The value of the reputation
	 */
	private double value;

	/**
	 * The number of raters
	 */
	private int nbRaters;

	@Override
	public String toString() {
		return "Reputation:\n" + super.toString();
	}

	/**
	 * @return the value
	 */
	public double getValue() {
		return value;
	}

	/**
	 * @param value
	 *            the value to set
	 */
	public void setValue(final double value) {
		this.value = value;
	}

	/**
	 * @return the nbRaters
	 */
	public int getNbRaters() {
		return nbRaters;
	}

	/**
	 * @param nbRaters
	 *            the nbRater to set
	 */
	public void setNbRaters(final int nbRaters) {
		this.nbRaters = nbRaters;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see java.lang.Object#equals()
	 */
	@Override
	public boolean equals(final Object object) {

		boolean equal = false;

		if (this == object) {
			equal = true;
		} else if (object instanceof MReputationBean) {
			final MReputationBean comparable = (MReputationBean) object;

			equal = true;
			equal &= getValue() == comparable.getValue();
			equal &= getNbRaters() == comparable.getNbRaters();
		}

		return equal;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see java.lang.Object#hashCode()
	 */
	@Override
	public int hashCode() {
		int result = 1;

		result = PRIME * result + nbRaters;
		long temp;
		temp = Double.doubleToLongBits(value);
		result = PRIME * result + (int) (temp ^ temp >>> 32);

		return result;
	}
}
