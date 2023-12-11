(function($) {
	$.extend(
	{
		openDialog : function(url,msg,width,title,btnyes,btnno){
			$.proj.dialog_confirm_buttons[btnyes] = function() {window.location.href = url;};
			$.proj.dialog_confirm_buttons[btnno] = function() {$(this).dialog("destroy");};
			$("#dialog").html(msg);
			$("#dialog").dialog({
				autoOpen:false,
				width: width,
				modal: true,
				resizable: false,
				closeOnEscape: true,
				position:"top",
				title:title,
				buttons: $.proj.dialog_confirm_buttons
			});
			$("#dialog").dialog("open");
		},
		openCaption : function(url,width,title,btnyes,btnno){
			$.proj.dialog_caption_buttons[btnyes] = function(){
				var str = "&akt=caption";
				$("#formcaptions").find("textarea").each(function(){
					str += "&"+$(this).attr("name")+"="+encodeURIComponent($(this).val());
				});
				window.location.href = url+str;
			};
			$.proj.dialog_caption_buttons[btnno] = function() {$(this).dialog("destroy");};
			$("#captiondialog").dialog({
				autoOpen:false,
				width: width,
				modal: true,
				resizable: false,
				closeOnEscape: true,
				position:"top",
				title:title,
				buttons: $.proj.dialog_caption_buttons
			});
			$("#captiondialog").dialog("open");
		},
		openLinks : function(url,width,title,btnyes,btnno){
			$.proj.dialog_caption_buttons[btnyes] = function(){
				var str = "&akt=link";
				$("#dialog").find("input[name=link]").each(function(){
					str += "&"+$(this).attr("name")+"="+encodeURIComponent($(this).val());
				});
				window.location.href = url+str;
			};
			$.proj.dialog_caption_buttons[btnno] = function() {$(this).dialog("destroy");};
			$("#dialog").dialog({
				autoOpen:false,
				width: width,
				modal: true,
				resizable: false,
				closeOnEscape: true,
				position:"top",
				title:title,
				buttons: $.proj.dialog_caption_buttons
			});
			$("#dialog").dialog("open");
		},
		openExtVid : function(url,width,title,btnyes,btnno){
			$.proj.dialog_caption_buttons[btnyes] = function(){
                $(this).dialog("close");
			};
			$.proj.dialog_caption_buttons[btnno] = function() {$(this).dialog("destroy");};
			$("#extvideodialog").dialog({
				autoOpen:false,
				width: width,
				modal: true,
				resizable: false,
				closeOnEscape: true,
				position:"top",
				title:title,
				buttons: $.proj.dialog_caption_buttons,
                close: function(event,ui){
                    var str = "&akt=extvideo&vimeo="+encodeURIComponent($("#vimeo").val())+"&youtube="+encodeURIComponent($("#youtube").val());
				    window.location.href = url+str;
                }
			});
			$("#extvideodialog").dialog("open");
		},
		doUplForm : function(input,fileDesc,fileMime,btnTxt,tipo,isRef,refType,id){
            $("#"+input).uploadifive({
				'fileObjName'       : input,
				'uploadScript'      : 'data_upload.php',
                'dnd'               : true,
				'auto'              : false,
                'queueID'           : input+'Queue',
				'multi'             : true,
				'fileType'          : fileMime,
				'buttonText'        : btnTxt,
                'removeCompleted'   : true,
                'simUploadLimit'    : 1,
				'formData'          : {'inpname':input,'tipo':tipo,'isRef':isRef,'refType':refType,'id':id},
				onUploadComplete    : function(file, data){
					if(data!=1)
                    {
                        $.proj.uplErr=true;
                    }
				},
                onQueueComplete     : function(uploads) {
                    if(!$.proj.uplErr)
                        location.reload();
                }

			});
		},
		openUPLDialog : function(dv,input,title,msg,fileDesc,fileMime,btnTxt,tipo,isRef,refType,id,btnupl,btnclose){
			$("#"+dv).html("");
			html = "";
			if(msg)
				html = "<p style='text-align:center;' class='note'>"+fileDesc+"<br />"+msg+"<br />&nbsp;</p>";
			html += "<input type='file' name='"+input+"' id='"+input+"' /></form><div id='"+input+"Queue'></div>";
			$("#"+dv).html(html);
            $.proj.dialog_upload_buttons[btnupl] = function(){
				$("#"+input).uploadifive('upload');
			};
			$.proj.dialog_upload_buttons[btnclose] = function(){
				$.each($.proj.arrQueueID, function(key, value) 
				{
					$("#"+input+value).remove();
				});
				$.proj.uplErr = false;
				$(this).dialog("destroy");
			};
			$("#"+dv).dialog({
				autoOpen:false,
				width: 425,
				height: 500,
				modal: false,
				resizable: false,
				closeOnEscape: true,
				position:"top",
				title:title,
				buttons: $.proj.dialog_upload_buttons
			});
			$(".ui-dialog-titlebar-close").remove();
			$("#"+dv).dialog("open");
            $("#"+dv).show();
			$.doUplForm(input,fileDesc,fileMime,btnTxt,tipo,isRef,refType,id);
		},
		openFOLDUPLDialog : function(dv,title,btnupl,btnclose)
		{
			$.proj.dialog_upload_buttons[btnclose] = function(){
				$(this).dialog("destroy");
			};
			$("#"+dv).dialog({
				autoOpen:false,
				width: 425,
				height: 500,
				modal: false,
				resizable: false,
				closeOnEscape: true,
				position:"top",
				title:title,
				buttons: $.proj.dialog_upload_buttons
			});
			$(".ui-dialog-titlebar-close").remove();
			$("#"+dv).dialog("open");
		},
		openSec : function(zona)
		{
			url = $("#a_"+zona).attr("href");
			$(".mainframe").attr('src', url);
			$.actSec(zona);
			$("#icons").show();
		},
		actSec : function(zona)
		{
			$("#li_"+$.proj.isAktSec).removeClass("select");
			$("#li_"+zona).addClass("select");
			$.proj.isAktSec = zona;
		},
		doEditor : function(lan,css)
		{
			$("textarea.mceEditor").tinymce({
				script_url : "js/jquery/tiny_mce/tiny_mce.js",
				language : lan,
				theme : "advanced",
				mode : "textareas",
				content_css : css,
				media_use_script : true,
                width: "100%",
                height: "500px",
				media_external_list_url : "../../../../../getvideolist.php",
                relative_urls : false,
				plugins : "autolink,advlist,gallerycon,fullscreen,paste,safari,searchreplace,media,preview,table",
				theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,bullist,numlist,|,styleselect,formatselect,|,tablecontrols",
				theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,undo,redo,|,link,unlink,gallerycon,|,fullscreen,code,preview",
				theme_advanced_buttons3 : "",
				theme_advanced_buttons4 : "",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_blockformats	: "p,h1,h2,h3",
				theme_advanced_styles : "Links=left-float;Rechts=right-float;Gelb=yellow",
				gallerycon_settings :
				  {
				    urls :
				    {
				      	galleries : '../../../../../getgallery.php?get=galleries',
					    images : '../../../../../getgallery.php?get=images&id={gallery_id}&lan=de',
					    image : '../../../../../getgallery.php?get=image&id={image_id}&lan=de',
					    img_src: '../../../../../getgallery.php?get=imagesrc&id={image_id}&lan=de'
				    },
				    sizes :
				    [
				      {
				        id : 'event_thumb',
				        name : 'Tiny thumbnail'
				      },
				      {
				        id : 'thumbnail',
				        name : 'Thumbnail'
				      },
				      {
				        id : 'litebox',
				        name : 'Display size'
				      },
				      {
				        id : 'square',
				        name : 'Square thumbnail'
				      },
				    ],
				    default_size : 'thumbnail',
				    default_alignment : 'left'
				  }
			});
			$("textarea.mceEditor_reduced").tinymce({
				script_url : "js/jquery/tiny_mce/tiny_mce.js",
				language : lan,
				theme : "advanced",
				mode : "textareas",
				content_css : css,
				media_use_script : false,
                width: "100%",
                height: "350px",
                relative_urls : false,
				plugins : "autolink,advlist,paste,safari",
				theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,bullist,numlist,|,undo,redo,|,link,unlink",
				theme_advanced_buttons2 : "",
				theme_advanced_buttons3 : "",
				theme_advanced_buttons4 : "",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_blockformats	: "p,h1,h2,h3",
				theme_advanced_styles : "Links=left-float;Rechts=right-float;Gelb=yellow"
			});
		}
	});
})(jQuery);

$(document).ready(function()
{
	$.proj = {
		isAktSec	: "",
		helpakt 	: false,
		arrQueueID 	: new Array(),
		uplErr 		: false,
		aktINP		: null,
		dialog_confirm_buttons : {},
		dialog_caption_buttons : {},
		dialog_upload_buttons : {},
		isLinkMSG 	: null
	};
	$("li.mainmenu").add(".amenu").click(function(){
		var d = this.id.split("_");
		$.openSec(d[1]);
	});
	$("ul.reference li").find("input").click(function(){
		var d = this.id.split("-");
		arrRefText = new Array();
		$(this).parent().parent().find("li").each(function(){
			if($(this).find("input").attr("checked"))
			{
				arrRefText.push($(this).find("span").text())
			}
		})
		arrRefText.sort();
		if(arrRefText.length==0)
		{
			txt = $.proj.isLinkMSG;
		}
		else
		{
			txt = "<ul class='list-"+d[0]+"'>";
			$.each(arrRefText, function(i,o){
				txt += "<li>"+o+"</li>"
			});
			txt += "</ul>";
		}
		$("#c"+d[0]).html(txt);
	});
	$('.imghref').add(".imghref_ispag").click(function(e){
		class_name = $(this).attr("class");
		url = this;
		var d = this.id.split(",");
		//type,$img->id_IMG,$isH
		obj = $(this);
		$("#meddialog").hide();
		$("#meddialog").addClass("imgmedparainfo");
		$("#meddialog").html("Bitte warten...");
		$("#meddialog").fadeIn("fast",function (){
			$.ajax({
				url			: "updbildpara.php",
				type		: "POST",
				dataType	: "json",
				timeout 	: 1000,
				data		: {id_MED:d[0],isH:d[1],id:d[2],idName:d[3],tbl_name:d[4]},
				error: function(){
					$("#meddialog").html("Ein Fehler ist aufgetreten");
					$("#meddialog").fadeOut("slow",function(){
						$("#meddialog").removeClass("imgparainfo");
					});
				},
				success: function(data)
				{
					//alert(data)
					if(data=="KO"||data=="EMPTY")
					{
						$("#meddialog").html("Ein Fehler ist aufgetreten");
						$("#meddialog").fadeOut("slow",function(){
							$("#meddialog").removeClass("imgparainfo");
						});
					}
					else if(data=="OK")
					{
						//location.reload();
						location.href=url;
					}
				}
			});
		});
		e.preventDefault();
	});
	if($("input[name=start_rand_gal]:radio:checked").val()==0)
		$("li.gall_chs").hide();
	
	$("input[name=start_rand_gal]:radio").click(function()
	{
		if($(this).val()==1)
			$("li.gall_chs").show();
		else
			$("li.gall_chs").hide();
	});
});




