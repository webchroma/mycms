<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?=WEBTITLE?></title>
<link rel="stylesheet" href="/css/fonts.css" type="text/css" media="screen" />
<link rel="stylesheet" href="css/adm.css" type="text/css" />
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 7]>
<link rel="stylesheet" type="text/css" media="all" href="css/ie7.css"></link>
<![endif]-->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css">
<link href="js/jquery/uploadifive/uploadifive.css" rel="stylesheet" type="text/css" />
<link href="js/jquery/fancybox/jquery.fancybox-1.3.1.css" rel="stylesheet" type="text/css" />
<link href="js/jquery/multiselect/multi-select.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.9.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/jquery/fancybox/jquery.fancybox-1.3.1.pack.js"></script>
<script type="text/javascript" src="js/jquery/uploadifive/jquery.uploadifive.min.js"></script>
<script type="text/javascript" src="js/jquery/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="js/jquery/multiselect/jquery.multi-select.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject_src.js"></script>
<script type="text/javascript" src="js/adm.js"></script>
<script type="text/javascript">
    $(document).ready(function()
    {
        /*main menu*/
        $("#icons .icons").hide();	
		$("#icons .hilfe").click(function(){
			if(!$.proj.helpakt)
			{
				$("#icons .icons").show();
				$.proj.helpakt = true;
			}
			else
			{
				$("#icons .icons").hide();
				$.proj.helpakt = false;
			}
		});
        
        /* edit pages */
        $.doEditor("<?=$cmslan?>","<?=FRONTENDCSS?>");
        $( "#accordion" ).accordion({
            active: false,
            heightStyle: "content",
            collapsible: true
        });
        
        
    });
</script>
</head>