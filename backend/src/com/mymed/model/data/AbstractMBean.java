package com.mymed.model.data;

import java.io.UnsupportedEncodingException;
import java.lang.reflect.Field;
import java.util.HashMap;
import java.util.Map;

import com.mymed.controller.core.exception.InternalBackEndException;

/**
 * myMed java Beans:
 * 
 * The required conventions are as follows: 
 * 
 * -The class must have a public
 * default constructor (no-argument). This allows easy instantiation within
 * editing and activation frameworks.
 * 
 * - The class properties must be accessible using get, set, is (used for
 * boolean properties instead of get) and other methods (so-called accessor
 * methods and mutator methods), following a standard naming-convention. This
 * allows easy automated inspection and updating of bean state within
 * frameworks, many of which include custom editors for various types of
 * properties.
 * 
 * -The class should be serializable. It allows applications and frameworks to
 * reliably save, store, and restore the bean's state in a fashion independent
 * of the VM and of the platform.
 * 
 * - The class must have a getAttributeToMap method, that convert all the fields
 * in a hashMap format for the myMed wrapper
 * 
 * - The class must override toString to have an human readable format
 * 
 * @author lvanni
 */
public abstract class AbstractMBean {

	/**
	 * @return 
	 * 		all the fields in a hashMap format for the myMed wrapper
	 * @throws IllegalArgumentException
	 * @throws IllegalAccessException
	 * @throws UnsupportedEncodingException
	 */
	public Map<String, byte[]> getAttributeToMap() throws InternalBackEndException {
		Map<String, byte[]> args = new HashMap<String, byte[]>();
		for (Field f : this.getClass().getDeclaredFields()) {
			try {
				if (f.get(this) instanceof String){
					args.put(f.getName(), ((String) f.get(this)).getBytes("UTF8"));
				}
			} catch (UnsupportedEncodingException e) {
				throw new InternalBackEndException("getAttribueToMap failed!: Introspection error");
			} catch (IllegalArgumentException e) {
				throw new InternalBackEndException("getAttribueToMap failed!: Introspection error");
			} catch (IllegalAccessException e) {
				throw new InternalBackEndException("getAttribueToMap failed!: Introspection error");
			}
		}
		return args;
	}
	
	/**
	 * override toString to have an human readable format
	 */
	@Override
	public String toString() {
		String value = "";
		for (Field f : this.getClass().getDeclaredFields()) {
			try {
				if (f.get(this) instanceof String){
					value += "\t" + f.getName() + " : " + (String) f.get(this) + "\n";
				} else {
					value += "\t" + f.getName() + " : " + f.get(this) + "\n";
				}
			} catch (IllegalArgumentException e) {
				e.printStackTrace();
			} catch (IllegalAccessException e) {
				e.printStackTrace();
			}
		}
		return value;
	}
}
