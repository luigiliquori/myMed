<?php
class iGoogleManager
{
	/**
	 * Get the Tabs's list with tab's name as key and boolean if active as value
	 */
	public /*Array<string, bool>*/ function getTabs()
	{
		return Array("Outils"=>true, "Santé"=>false, "Pratique"=>false);
	}
	/**
	 * Get the Tab's apps'slist for the $tabName tab with apps's name as key and int as value representing the column number
	 */
	public /*array<array[string, int]>*/ function getApps(/*string*/ $tabName)
	{
		switch($tabName)
		{
			case 'Outils':
				return Array(	Array("myProfile"	, 0),
								Array("myJam"		, 1),
								Array("myTransport"	, 2),
								Array("myFriends"	, 0),
								Array("myTranslator", 1),
								Array("myJob"		, 2));
			case 'Santé':
				return Array(	Array("myAngel"	, 0),
								Array("myBigApp"	, 1));
			case 'Pratique':
				return Array(	Array("myBigApp"	, 0),
								Array("myBigApp"	, 1),
								Array("mySmallApp"	, 0));
		}
	}
}