package com.mymed.model.data.reputation;

public class MReputationBean {
  // Used for the hashCode
  private static final int PRIME = 31;

  private double reputation;
  private int noOfRatings;

  public MReputationBean(final double reputation, final int noOfRatings) {
    this.reputation = reputation;
    this.noOfRatings = noOfRatings;
  }

  /*
   * (non-Javadoc)
   * 
   * @see java.lang.Object#hashCode()
   */
  @Override
  public int hashCode() {
    int result = 1;
    result = PRIME * result + noOfRatings;
    long temp;
    temp = Double.doubleToLongBits(reputation);
    result = PRIME * result + (int) (temp ^ temp >>> 32);
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

    if (this == obj) {
      equal = true;
    } else if (obj instanceof MReputationBean) {
      final MReputationBean comparable = (MReputationBean) obj;

      equal = true;

      equal &= noOfRatings == comparable.getNoOfRatings();
      equal &= reputation == comparable.getReputation();
    }

    return equal;
  }

  public void setReputation(final double reputation) {
    this.reputation = reputation;
  }

  public double getReputation() {
    return reputation;
  }

  public void setNoOfRatings(final int noOfRatings) {
    this.noOfRatings = noOfRatings;
  }

  public int getNoOfRatings() {
    return noOfRatings;
  }
}
