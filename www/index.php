<?php
/* import functions and php settings */
include_once("inc/fx/page_header.inc.php");
include_once("inc/fx/page_settings.inc.php");
include_once("inc/fx/display_fx.inc.php"); // display functions

/* start the page*/
include_once("inc/layout_struktur/head.inc.php"); // html head _ meta and styling

/*HEADER*/
include_once("inc/layout_struktur/header.inc.php"); // page layout header

/* create background image div container*/
echo "<div class=\"full_img_background\"><div class=\"full_img_background_mask\"></div></div>";
?>
<div id="container" class="w_90 clearfix">
<?php
//if(START_FIX_START&&$p==null) // show startpage as page
if($p==null&&$s==null) // show startpage as page
	include_once("inc/page_module/start_page.inc.php");
else
{
	if($s=="nws")
	{
		include_once("inc/page_module/news.inc.php"); // show blog
	}
	else if($p!=null) // show other section
	{
		if($s=="page")
		{
			if($wPage=="protekt"&&!isset($_SESSION["usr_log"]))
				include_once("inc/page_module/protekt.inc.php"); // page is protected -> first login
			else
				if($wPage=="news")
					include_once("inc/page_module/news.inc.php"); // show blog
				elseif($wPage=="portfolio"||$wPage=="gallery")
					include_once("inc/page_module/portfolio.inc.php"); // show portfolio or gallery
				else 
                    include_once("inc/page_module/pages.inc.php"); // show a page		
		}
		else
			include_once("inc/page_module/project.inc.php"); // show a page
	}	
	else
		include_once("inc/page_module/animage.inc.php"); // showing a random image or gallery image
	
}
?>
</div>
<?php
include_once("inc/layout_struktur/footer.inc.php"); // footer _ scripting
?>
