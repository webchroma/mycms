<?php
/* import functions and php settings */
include_once("inc/fx/page_header.inc.php");
$strXML = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
$strXML .= "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
// get the languages
$strSQL = "SELECT lan FROM ".PREFIX."_languages WHERE aktiv";
$rs = $objConn->rs_query($strSQL);
if ($rs->count() > 0)
{
	while($row = $rs->fetchObject())
    {
		$strXML .= "\t<sitemap>\n";
		$strXML .= "\t\t<loc>".BASEURL."/$row->lan/sitemap.xml</loc>\n";
		$strXML .= "\t\t<lastmod>".gmdate("Y-m-d",strtotime(LASTUPD))."</lastmod>\n";
		$strXML .= "\t</sitemap>\n";
	}
}
/* output */
$strXML .= "</sitemapindex>";
echo $strXML;
?>