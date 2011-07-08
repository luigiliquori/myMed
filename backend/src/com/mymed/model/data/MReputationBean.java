package com.mymed.model.data;

/**
 * 
 * @author lvanni
 * 
 */
public final class MReputationBean extends AbstractMBean {

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

		boolean returnValue = true;

		if (object instanceof MReputationBean) {
			final MReputationBean comparable = (MReputationBean) object;

			returnValue &= getValue() == comparable.getValue();
			returnValue &= getNbRaters() == comparable.getNbRaters();
		} else {
			returnValue = false;
		}

		return returnValue;
	}

	/*
	 * (non-Javadoc)
	 * 
	 * @see java.lang.Object#hashCode()
	 */
	@Override
	public int hashCode() {
		final int prime = 31;
		int result = 1;

		result = prime * result + nbRaters;
		long temp;
		temp = Double.doubleToLongBits(value);
		result = prime * result + (int) (temp ^ temp >>> 32);

		return result;
	}
}
