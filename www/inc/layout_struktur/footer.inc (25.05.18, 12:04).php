	<!--footer-->
	<footer class="w_90">
		<?php
		echo "&copy; ".META_COPY;
		echo " - C.F. 80120910155";
		//build footer menu
		$domenu = "footer"; // which menu to display _ leave empty if all pages are shown
		echo "<nav id=\"menu_$domenu\"><ul>";
		doNavi($p,$pp,$cmslan,$domenu,$objConn);
		echo "</ul></nav>";
		?>
	</footer>
	</div>
	<!-- end wrapper -->
	<!--background image-->
	<?php
	// set background image
	echo "<style>"; 
	if(isset($arrIMGS))
	{   
		if($arrIMGS[$arridMED[0]]["type"]=="img")
			echo ".full_img_background{background: url(\"".getFileNameFormat(IMAGES_AS_URL.$arrIMGS[$arridMED[0]]["url"],HUGE,false)."\");-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;}";
	}
	else
	{
		echo ".full_img_background{background: url(\"/imago/placebo.png\");-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;}";
		echo "#container,.header_bottom{border-top:2px solid #197607;}";
		echo "#container{margin-top:106px;}";
		echo ".header_bottom{margin-top:0px;}";
        echo "@media all and (max-width: 750px){#container{margin-top:2px;}}";
	}
	echo "</style>";
	?>
	<!--scripts
	<!--jQuery v1.11.1<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>-->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="/js/js.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/video.js/4.12.15/video.js"></script>
	<script type="text/javascript">
		videojs.options.flash.swf = "https://cdnjs.cloudflare.com/ajax/libs/video.js/4.12.15/video-js.swf";
	</script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.0/jquery.fancybox.min.js"></script>
	
</body>
</html>