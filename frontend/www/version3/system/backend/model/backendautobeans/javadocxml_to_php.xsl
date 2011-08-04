<?xml version="1.0" encoding="UTF-8"?>
<stylesheet version="1.0" xmlns="http://www.w3.org/1999/XSL/Transform">
	<output method="text" encoding="utf-8" media-type="application/x-php" />

<template match="/"><apply-templates select="/jel/jelclass"/></template>
<template match="/jel/jelclass">
<text>&lt;?php
require_once __DIR__.'/../JSon.class.php';
</text>
<apply-templates select="comment"/>
abstract class <value-of select="@type"/> extends JSon
{<apply-templates select="fields"/>
}
?&gt;
</template>


<template match="comment">
	<param name="tab" select="''"/>
	<text>&#x0a;</text>
	<value-of select="$tab"/>
	<text>/**</text>
	<for-each select="description">
		<text>&#x0a;</text>
		<value-of select="$tab"/>
		<text> * </text><value-of select="."/>
	</for-each>
	<for-each select="attribute">
		<text>&#x0a;</text>
		<value-of select="$tab"/>
		<text> * </text><value-of select="@name"/><text> </text><value-of select="description"/>
	</for-each>
	<text>&#x0a;</text>
	<value-of select="$tab"/>
	<text> */</text>
</template>


<template match="fields">
	<for-each select="field">
		<apply-templates select="comment">
			<with-param name="tab" select="'&#x09;'"/><!-- &#x09; => tab -->
		</apply-templates>
		<text>&#x0a;	public /*</text><value-of select="@type"/><text>*/	$</text><value-of select="@name"/><text>;</text>
	</for-each>
</template>

</stylesheet>
