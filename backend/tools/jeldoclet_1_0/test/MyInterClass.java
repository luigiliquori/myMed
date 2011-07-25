/**
 * My interface implemented
 */
public class MyInterClass implements MyInterface
{
	/**
	 * A private string
	 */
	private String _priv_string;

	/**
	 * A protected string
	 */
	protected String _prot_string;

	/**
	 * A public string
	 */
	public String _pub_string;

	/**
	 * A one dimensional array
	 */
	private int[] _one_dim_array;

	/**
	 * A two dimensional array
	 */
	private int[][] _two_dim_array;

	/**
	 * A no-argument constructor
	 */
	public MyInterClass( )
	{
	}

	/**
	 * A constructor with a string
	 * @param aString A string.
	 */
	public MyInterClass( String aString )
	{
	}

	/**
	 * Gets an integer
	 * @return The integer.
	 */
	public int getInteger( )
	{
		return 1;
	}

	/**
	 * One argument method.
	 * @param a An integer.
	 * @return The string.
	 */
	protected String oneArgMethod( int a )
	{
		return "";
	}

	/**
	 * Two argument method.
	 * @param a An integer.
	 * @param b Another integer.
	 * @return The string.
	 */
	protected String twoArgMethod( int a, int b )
	{
		return "";
	}

/**
 * thz: a nested class
 */
	private class AnestedClass
	{
		/**
		 * public constructor in nested class
		 */
		public AnestedClass()
		{
		}

		/**
		 * whether this makes sense (access rights) or not is not our business
		 */
		protected void someMethod()
		{
		}
	}
}
