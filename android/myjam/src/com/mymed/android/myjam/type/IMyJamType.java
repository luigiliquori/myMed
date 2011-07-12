package com.mymed.android.myjam.type;
/**
 * myJam classes must expose a method to be validated.
 * consistency.
 * @author iacopo
 *
 */
public interface IMyJamType {
	/**
	 * Validate the instance.
	 * @throws WrongFormatException
	 */
	public abstract void validate() throws WrongFormatException;
}
