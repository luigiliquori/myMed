/*
 * Copyright 2012 INRIA Licensed under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0 Unless required by applicable law
 * or agreed to in writing, software distributed under the License is
 * distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the specific language
 * governing permissions and limitations under the License.
 */
package com.mymed.model.data;

import java.io.Serializable;
import java.lang.reflect.Field;
import java.lang.reflect.Modifier;
import java.security.AccessController;
import java.security.PrivilegedAction;
import java.security.PrivilegedActionException;
import java.security.PrivilegedExceptionAction;
import java.util.HashMap;
import java.util.Map;

import android.util.Log;

import com.mymed.android.myjam.exception.InternalBackEndException;
import com.mymed.android.myjam.exception.InternalClientException;
import com.mymed.utils.ClassType;

/**
 * myMed java Beans.
 * <p>
 * The required conventions are as follows:<br>
 * <ul>
 * <li>The class must have a public default constructor (no-argument). This
 * allows easy instantiation within editing and activation frameworks.</li>
 * <li>The class properties must be accessible using get, set, is (used for
 * boolean properties instead of get) and other methods (so-called accessor
 * methods and mutator methods), following a standard naming-convention. This
 * allows easy automated inspection and updating of bean state within
 * frameworks, many of which include custom editors for various types of
 * properties.</li>
 * <li>The class should be serializable. It allows applications and frameworks
 * to reliably save, store, and restore the bean's state in a fashion
 * independent of the VM and of the platform.</li>
 * <li>The class must have a getAttributeToMap method, that convert all the
 * fields in a hashMap format for the myMed wrapper</li>
 * <li>The class must override toString to have an human readable format</li>
 * </ul>
 * 
 * @author lvanni
 * @author Milo Casagrande
 */
public abstract class AbstractMBean implements Cloneable, Serializable {
    /**
     * Generated serial ID.
     */
    private static final long serialVersionUID = -336877499051592717L;

    // Default logger for all the beans that extend this abstract
    protected static final String TAG = "AbstractMBean";
    // Used for the hashCode() function
    protected static final int PRIME = 31;

    // Value of the private modifier for reflection
    private static final int PRIV = Modifier.PRIVATE;

    /*
     * (non-Javadoc)
     * @see java.lang.Object#hashCode()
     */
    @Override
    public abstract int hashCode();

    /*
     * (non-Javadoc)
     * @see java.lang.Object#equals(java.lang.Object)
     */
    @Override
    public abstract boolean equals(Object obj);

    /**
     * @return all the fields in a hashMap format for the myMed wrapper
     * @throws InternalBackEndException
     * @throws PrivilegedActionException
     */
    public Map<String, byte[]> getAttributeToMap() throws InternalClientException {
        final AbstractMBean.InnerPrivilegedExceptionAction action = new AbstractMBean.InnerPrivilegedExceptionAction(
                        this);
        try {
            return AccessController.doPrivileged(action);
        } catch (final PrivilegedActionException ex) {
            throw new InternalClientException(ex.getMessage()); // NOPMD
        }
    }

    /*
     * (non-Javadoc)
     * @see java.lang.Object#toString()
     */
    @Override
    public String toString() {
        final AbstractMBean.InnerPrivilegedAction action = new AbstractMBean.InnerPrivilegedAction(this);
        final StringBuffer bean = AccessController.doPrivileged(action);

        return bean.toString();
    }

    /**
     * Static inner class to provide reflection mechanism in a protected way,
     * without strong references
     * 
     * @author Milo Casagrande
     */
    private static final class InnerPrivilegedExceptionAction implements PrivilegedExceptionAction<Map<String, byte[]>> {

        // The object to work on
        private transient final Object object;

        public InnerPrivilegedExceptionAction(final Object object) {
            this.object = object;
        }

        @Override
        public Map<String, byte[]> run() throws Exception { // NOPMD
            final Class<?> clazz = object.getClass();
            final Map<String, byte[]> args = new HashMap<String, byte[]>();

            for (final Field field : clazz.getDeclaredFields()) {
                field.setAccessible(true);

                // We check the value of the modifiers of the field: if the
                // field
                // is not just private, we skip it
                final int modifiers = field.getModifiers();
                if (modifiers != PRIV) {
                    continue;
                }

                try {
                    final ClassType type = ClassType.inferTpye(field.getType());
                    args.put(field.getName(), ClassType.objectToByteArray(type, field.get(object)));
                } catch (final IllegalArgumentException ex) {
                    Log.d(TAG,"Introspection failed", ex);
                    throw new InternalClientException("getAttribueToMap failed!: Introspection error"); // NOPMD
                } catch (final IllegalAccessException ex) {
                    Log.d(TAG,"Introspection failed", ex);
                    throw new InternalClientException("getAttribueToMap failed!: Introspection error"); // NOPMD
                }
            }

            return args;
        }
    }

    /**
     * Static inner class to provide reflection mechanism in a protected way,
     * without strong references
     * 
     * @author Milo Casagrande
     */
    private static final class InnerPrivilegedAction implements PrivilegedAction<StringBuffer> {

        // The object to work on
        private transient final Object object;

        public InnerPrivilegedAction(final Object object) {
            this.object = object;
        }

        @Override
        public StringBuffer run() {
            final Class<?> clazz = object.getClass();
            final StringBuffer value = new StringBuffer(200);

            for (final Field field : clazz.getDeclaredFields()) {
                field.setAccessible(true);

                // We check the value of the modifiers of the field: if the
                // field is
                // not just private, we skip it
                final int modifiers = field.getModifiers();
                if (modifiers != PRIV) {
                    continue;
                }

                try {
                    if (field.get(object) instanceof String) {
                        value.append('\t');
                        value.append(field.getName());
                        value.append(" : ");
                        value.append((String) field.get(object));
                        value.append('\n');
                    } else {
                        value.append('\t');
                        value.append(field.getName());
                        value.append(" : ");
                        value.append(field.get(object));
                        value.append('\n');
                    }
                } catch (final IllegalArgumentException e) {
                    // We should never get here!
                    Log.d(TAG,"Arguments are not valid", e);
                } catch (final IllegalAccessException e) {
                    Log.d(TAG,"Impossible to access field " + field.getName(), e);
                }
            }

            value.trimToSize();
            return value;
        }
    }
}