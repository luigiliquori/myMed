/*
 * Copyright 2012 POLITO
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
package com.mymed.model.data.storage;

import java.util.Arrays;

/**
 * Bean that stores an expiring column.
 * 
 * @author iacopo
 */
public class ExpColumnBean {
    private byte[] value = new byte[0];
    private long timestamp;
    private int timeToLive;

    public void setValue(final byte[] value) {
        this.value = Arrays.copyOf(value, value.length);
    }

    public byte[] getValue() {
        return Arrays.copyOf(value, value.length);
    }

    public void setTimestamp(final long timestamp) {
        this.timestamp = timestamp;
    }

    public long getTimestamp() {
        return timestamp;
    }

    public void setTimeToLive(final int timeToLive) {
        this.timeToLive = timeToLive;
    }

    public int getTimeToLive() {
        return timeToLive;
    }

    /*
     * (non-Javadoc)
     * @see java.lang.Object#hashCode()
     */
    @Override
    public int hashCode() {
        final int prime = 31;
        int result = 1;
        result = (prime * result) + timeToLive;
        result = (prime * result) + (int) (timestamp ^ (timestamp >>> 32));
        result = (prime * result) + Arrays.hashCode(value);
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
        } else if (obj instanceof ExpColumnBean) {
            final ExpColumnBean comparable = (ExpColumnBean) obj;

            equal = true;

            equal &= timeToLive == comparable.getTimeToLive();
            equal &= timestamp == comparable.getTimestamp();
            equal &= Arrays.equals(value, comparable.getValue());
        }

        return equal;
    }
}
