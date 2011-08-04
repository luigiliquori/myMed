<?xml version="1.0" encoding="UTF-8"?>
<stylesheet version="1.0" xmlns="http://www.w3.org/1999/XSL/Transform">
	<output method="text" encoding="utf-8" media-type="application/json" />

	<template match="/tabpanel">
		<text>{</text>
		<apply-templates select="desktop"/>
		<text>&#x0a;}</text>
	</template>
	
	<template match="desktop">
		<text>&#x0a;	"</text><value-of select="@name"/><text>":</text>
		<text>&#x0a;	[</text>
		<apply-templates select="column"/>
		<text>&#x0a;	]</text><if test="boolean(following-sibling::*)">,</if>
	</template>
	
	<template match="column">
		<text>&#x0a;		{</text>
		<text>&#x0a;			"width":"</text><value-of select="@width"/><text>",</text>
		<text>&#x0a;			"appList":</text>
		<text>&#x0a;			[</text>
		<apply-templates select="application"/>
		<text>&#x0a;			]</text>
		<text>&#x0a;		}</text><if test="boolean(following-sibling::*)">,</if>
	</template>
	
	<template match="application">
		<text>&#x0a;				"</text><value-of select="@name"/>"<if test="boolean(following-sibling::*)">,</if>
	</template>
</stylesheet>
