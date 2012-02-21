package com.mymed.model.data.geolocation;

import com.mymed.model.data.AbstractMBean;

/**
 * Class used to exchange located reports, without details.
 * 
 * @author iacopo
 */
public final class MSearchBean extends AbstractMBean {
    /**
     * Generated serial ID.
     */
    private static final long serialVersionUID = -928196743811600908L;
    private String id;
    private long locationId;
    private int latitude;
    private int longitude;
    private String value;
    private double distance;
    private long date;
    private long expirationDate;

    /**
     * Create a new MSearchBean
     */
    public MSearchBean() {
        super();
    }

    /**
     * Copy constructor
     * <p>
     * Provide a clone of the passed bean
     * 
     * @param toClone
     *            the MSearchBean to clone
     */
    protected MSearchBean(final MSearchBean toClone) {
        super();

        id = toClone.getId();
        locationId = toClone.getLocationId();
        latitude = toClone.getLatitude();
        longitude = toClone.getLongitude();
        value = toClone.getValue();
        distance = toClone.getDistance();
        date = toClone.getDate();
        expirationDate = toClone.getExpirationDate();
    }

    /*
     * (non-Javadoc)
     * @see java.lang.Object#clone()
     */
    @Override
    public MSearchBean clone() {
        return new MSearchBean(this);
    }

    public void setId(final String id) {
        this.id = id;
    }

    public String getId() {
        return id;
    }

    public long getLocationId() {
        return locationId;
    }

    public void setLocationId(final long locationId) {
        this.locationId = locationId;
    }

    public void setLatitude(final int latitude) {
        this.latitude = latitude;
    }

    public int getLatitude() {
        return latitude;
    }

    public void setLongitude(final int longitude) {
        this.longitude = longitude;
    }

    public int getLongitude() {
        return longitude;
    }

    public double getDistance() {
        return distance;
    }

    public void setDistance(final double distance) {
        this.distance = distance;
    }

    public void setValue(final String value) {
        this.value = value;
    }

    public String getValue() {
        return value;
    }

    public long getDate() {
        return date;
    }

    public void setDate(final long date) {
        this.date = date;
    }

    public long getExpirationDate() {
        return expirationDate;
    }

    public void setExpirationDate(final long expirationDate) {
        this.expirationDate = expirationDate;
    }

    /*
     * (non-Javadoc)
     * @see java.lang.Object#hashCode()
     */
    @Override
    public int hashCode() {
        int result = 1;
        result = (PRIME * result) + (int) (date ^ (date >>> 32));
        result = (PRIME * result) + (int) (distance * distance);
        result = (PRIME * result) + (id == null ? 0 : id.hashCode());
        result = (PRIME * result) + latitude;
        result = (PRIME * result) + (int) (locationId ^ (locationId >>> 32));
        result = (PRIME * result) + longitude;
        result = (PRIME * result) + (value == null ? 0 : value.hashCode());
        return result;
    }

    /*
     * (non-Javadoc)
     * @see java.lang.Object#equals(java.lang.Object)
     */
    @Override
    public boolean equals(final Object obj) {

        boolean equal = false;

        if (this == obj) {
            equal = true;
        } else if (obj instanceof MSearchBean) {
            final MSearchBean comparable = (MSearchBean) obj;

            equal = true;

            if (((id == null) && (comparable.getId() != null)) || ((id != null) && (comparable.getId() == null))) {
                equal &= false;
            } else if ((id != null) && (comparable.getId() != null)) {
                equal &= id.equals(comparable.getId());
            }

            if (((value == null) && (comparable.getValue() != null))
                            || ((value != null) && (comparable.getValue() == null))) {
                equal &= false;
            } else if ((value != null) && (comparable.getValue() != null)) {
                equal &= value.equals(comparable.getValue());
            }

            equal &= date == comparable.getDate();
            equal &= distance == comparable.getDistance();
            equal &= latitude == comparable.getLatitude();
            equal &= locationId == comparable.getLocationId();
            equal &= longitude == comparable.getLongitude();
        }

        return equal;
    }
}
