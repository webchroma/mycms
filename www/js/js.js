(function($) {
	$.extend(
	{
		encode : function(str)
		{
			nStr = "";
			for (var i = 0; i < str.length; i++)
			{
				nStr += "&#" + Number(str.charCodeAt(i)).toString() + ";";
			}
			return nStr;
		},
		doMail : function(mail)
		{
			prex = $.encode("mailto:");
			mail = $.encode(mail);
			$("span.mail").html("<a href=\""+prex+mail+"\">"+mail+"</a>");
		}
	});
})(jQuery);

$(document).ready(function()
{	
	$("[data-fancybox]").fancybox({
		protect: true,
        buttons : [
            'slideShow',
            'thumbs',
            'share',
            'close'
        ],
        animationEffect : "fade"
	});
	$(".start-carousel").owlCarousel({
		items:1,
		margin:10,
		dots:true,
		autoPlay:true,
		stopOnHover:true,
        itemsDesktop : [1000,1],
        itemsDesktopSmall : [900,1],
        itemsTablet: [600,1]
	});
	$(".project-carousel").owlCarousel({
		items : 8,
		itemsScaleUp:true
	});
    
    $("#HH_menu_header_icon").click(function(e){
        $("#dv_menu_header_dx").toggle();
        $("#menu_main").toggle();
        e.preventDefault();
    });
});




