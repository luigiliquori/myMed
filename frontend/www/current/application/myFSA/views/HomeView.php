<? include("header.php"); ?>
</head>

<body>

	<div data-role="page" id="Home" data-theme="b">
		
		<div data-role="header" data-theme="b" data-position="fixed">
			<h1 style="color: white;"><?= _("myFSA") ?></h1>
			<a href="?action=main" data-inline="true" rel="external" data-role="button" data-icon="back"><?= _("Back")?></a>
			<? include_once "notifications.php"; ?>
		</div>
			
		<div data-role="content" class="content_text">
			<?php
			$xml=("http://www.sophia-antipolis.org/index.php?option=com_content&view=category&layout=blog&id=119&Itemid=3&lang=fr&format=feed&type=rss");


			$xmlDoc = new DOMDocument();
			$xmlDoc->load($xml);

			//get elements from "<channel>"
			$channel=$xmlDoc->getElementsByTagName('channel')->item(0);
			$channel_title = $channel->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
			$channel_link = $channel->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;

			//output elements from "<channel>"
			echo("<p><a href='" . $channel_link. "'>" . $channel_title . "</a>");
			echo("<br />");
			echo("</p>");

			//get and output "<item>" elements
			$x=$xmlDoc->getElementsByTagName('item');
			for ($i=0; $i<=2; $i++)
  			{
  				$item_title=$x->item($i)->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
  				$item_link=$x->item($i)->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
  				$item_desc=$x->item($i)->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;

  				echo ("<p><a href='" . $item_link. "'>" . $item_title . "</a>");
  				echo ("<br />");
  				echo ($item_desc . "</p>");
  			}
			?>
		</div>
	</div>
</body>