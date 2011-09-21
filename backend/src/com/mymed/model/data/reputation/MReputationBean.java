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
	 * The raterList ID (userList)
	 */
	private String raters;

	@Override
	public String toString() {
		return "Reputation:\n" + super.toString();
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
			equal &= getRaters() == comparable.getRaters();
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

		result = PRIME * result;
		long temp;
		temp = Double.doubleToLongBits(value);
		result = PRIME * result + (int) (temp ^ temp >>> 32);

		return result;
	}
	
	@Override
	public void update(AbstractMBean mBean) {
		// TODO Auto-generated method stub
		
	}

	/* --------------------------------------------------------- */
	/* GETTER AND SETTER */
	/* --------------------------------------------------------- */
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
	 * 
	 * @return
	 */
	public String getRaters() {
		return raters;
	}

	/**
	 * 
	 * @param raters
	 */
	public void setRaters(String raters) {
		this.raters = raters;
	}
}
