<?php
class MyStore extends ContentObject
{
	/**
	 * Method to define the title of the page
	 * @return string	Content Title
	 */
	public /*string*/ function getTitle()
	{
		return 'MyStore';
	}
	public /*void*/ function contentGet()
	{
?>
		<div style="background-image:url('services/mystore/img/myStoreCS.png');height:100%;">
			<form action="?" style="text-align:center;position:absolute;bottom:4em;width:100%;"><div><button type="submit">Close</button></div></form>
		</div>
<?php
	}
}
?>