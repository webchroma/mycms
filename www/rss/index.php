<?php
session_start();
echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n";
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
		<title>HZT Berlin - <?=WEBTITLE?></title> 
		<link><?=BASEURL?>rss/<?=$cmslan?></link> 
		<description>description</description> 
		<language><?=$cmslan?></language>
		<atom:link href="<?=BASEURL?>rss/<?=$cmslan?>" rel="self" type="application/rss+xml" />
	</channel>
</rss>