var GalleryConDialog = {
	g_settings: null,
	preInit : function() {
		GalleryConDialog.g_settings = tinyMCEPopup.getParam("gallerycon_settings");
		tinyMCEPopup.requireLangPack();
		/*
		var url = tinyMCEPopup.getParam("jquery_url");
		if (url) {
			document.write('<script language="javascript" type="text/javascript" src="' + url + '"></script>');
		}
		*/
	},

	init : function(ed) {
		var s = GalleryConDialog.g_settings;
		tinyMCEPopup.resizeToInnerSize();
		// Init align types
		$('.il-align-type').click(function() {
			$('.il-align-type').removeClass('il-at-selected');
			$(this).addClass('il-at-selected');
		});
		// Init sizes
		$.each(s.sizes, function(y, size) {
			var label = $('<label />').attr('for', 'il-size-'+size.id).text(size.name);
			$('<input name="size" type="radio" />').attr('id', 'il-size-'+size.id).attr('value', size.id).prependTo(label);
			label.appendTo('#il-size');
		});
		
		// If an image is selected we set the defaults according to that
		var gid = null, iid = null, sid = 0;
		var n = ed.selection.getNode();
		if (n.nodeName == 'IMG') {
			var img = $(n);
			if (img.attr('id').match(/^img__(\-?\w+?)__(\w+?)__(\w+?)$/) != null) {
				gid = RegExp.$1; // Gallery id
				iid = RegExp.$2; // Image id
				sid = RegExp.$3; // Size id
				aid = s.default_alignment;
				$.each(['left', 'right', 'baseline', 'middle', 'top'], function(i, class_name) {
					aid = img.hasClass(class_name) ? class_name : aid;
				});
				mcTabs.displayTab('general_tab','look_panel');
				this.init_image_look_panel(iid, sid, aid);
			}
		}

		// Fetch the galleries
		$.getJSON(s.urls.galleries, function(data) {
			// Insert the "Unsorted" gallery
			data.unshift({"id": 0, "title": tinyMCEPopup.getLang('gallerycon_dlg.nogallery'), "desc": tinyMCEPopup.getLang('gallerycon_dlg.nogallery_desc')});
			$.each(data, function(i, gall) {
				$('<div />').text(gall.title)
				.addClass("gc-gallery")
				.attr('id', 'ib-gid-'+gall.id)
				.data('gallery-id', gall.id)
				.click( function() {
					// TODO: Move this click function into a separate function
					$("#image-browser #ib-galleries DIV.gc-gallery").removeClass('ib-selected');
					$(this).addClass('ib-selected');
					$.getJSON(s.urls.images.replace(/\{gallery_id\}/, gall.id), function(data) {
						$("#image-browser #ib-images").empty();
						$.each(data, function(j, img) {
							var div = $('<div />').addClass('ib-thumb-box');
							var imgdiv = $('<div />').addClass('ib-img-box').appendTo(div);
							$('<img />').attr('src', img.thumb).attr('alt', img.desco).attr('title', img.desco).appendTo(imgdiv);
							$('<div />').addClass('ib-title-box').text(img.desco).appendTo(div);
							div.appendTo("#image-browser #ib-images").click( function() {
								mcTabs.displayTab('general_tab','look_panel');
								GalleryConDialog.init_image_look_panel(img.id);
							});
						});
					});
				})
				.appendTo("#image-browser #ib-galleries");
			});
			if (gid != null) {
				$('#image-browser #ib-galleries #ib-gid-'+gid).click();
			}
		});
	},

	init_image_look_panel: function(img_id, size_id, alignment) {
		var s = this.g_settings;
		size_id = size_id == null ? s.default_size : size_id;
		alignment = alignment == null ? s.default_alignment : alignment;
		$.getJSON(s.urls.image.replace(/\{image_id\}/, img_id), function(data) {
			$('#il-thumb').empty();
			$('<img />')
			.attr('src', data.thumb)
			.data('image-id', data.id)
			.addClass('ib-img-box')
			.appendTo('#il-thumb');
			$('.il-align-type IMG').attr('src', data.thumb);
			$('#il-title').text(data.title);
			$('#il-desc').text(data.desco);
		});
		$('#il-alignment .il-align-type').removeClass('il-at-selected');
		$('#il-alignment-'+alignment).addClass('il-at-selected');
		$('#il-size-'+size_id).attr('checked', 'checked');
		$('#insert').css('display', 'inline');
	},

	insert : function() {
		// TODO: Make submit button disabled
		var ed = tinyMCEPopup.editor, t = this, f = document.forms[0], nl = f.elements, v, args = {}, el;
		var s = this.g_settings;


		var gall_id = $('#image-browser #ib-galleries DIV.ib-selected').data('gallery-id');
		var img_id = $('#il-thumb IMG').data('image-id');
		var size_id = $('#il-size input:checked').val();
		var img_src_url = s.urls.img_src.replace(/\{image_id\}/, img_id).replace(/\{size_id\}/, size_id);
		var img_src = $('#il-thumb IMG').attr('src');
		//img_src = $.ajax({url: img_src_url, async: false}).responseText;
		
		$.getJSON(img_src_url, function(data){
			img_src = data.src;
			tinymce.extend(args, {
				'src': img_src,//nl.src.value,
				'alt': $('#il-desc-box #il-desc').text(), //nl.alt.value, // Setja title
				'title': $('#il-desc-box #il-desc').text(), // Setja title: desc ??
				//'class': $('#il-alignment .il-at-selected .il-class-name').attr('value'),
				//'class': $('#il-alignment .il-at-selected .il-class-name').attr('value'),
				'class':'page_img',
				'id': img_id
			});
			/*
			if (s.link) {
				var link_attrs = {};
				$.extend(link_attrs, s.link, {'title': args.title});
				if (link_attrs.rel) {
					link_attrs.rel = link_attrs.rel.replace(/\{image_id\}/, img_id).replace(/\{size_id\}/, size_id).replace(/\{gallery_id\}/, gall_id)
				}
				
				if (link_attrs.classe) {
					link_attrs.classe = link_attrs.classe.replace(/\{image_id\}/, img_id).replace(/\{size_id\}/, size_id).replace(/\{gallery_id\}/, gall_id)
				}
				
				if (link_attrs.href) {
					link_attrs.href = link_attrs.href.replace(/\{image_id\}/, img_id).replace(/\{size_id\}/, size_id).replace(/\{gallery_id\}/, gall_id)
				}
				if (link_attrs.size) {
					var img_src_url = s.urls.img_src.replace(/\{image_id\}/, img_id).replace(/\{size_id\}/, link_attrs.size);
					$.getJSON(img_src_url, function(data) {
						link_attrs.href = data.src;//$.ajax({url: img_src_url, async: false}).responseText;
						link_attrs.size = null;
						t.insertAndClose(args, link_attrs);
					});
					return;
				}
			}
			t.insertAndClose(args, link_attrs);
			*/
			t.insertAndClose(args, null);
		});
	},

	insertAndClose : function(args, link_attrs) {
		var ed = tinyMCEPopup.editor, f = document.forms[0], nl = f.elements, v, el;

		tinyMCEPopup.restoreSelection();

		// Fixes crash in Safari
		if (tinymce.isWebKit)
			ed.getWin().focus();
		
		el = ed.selection.getNode();

		if (el && el.nodeName == 'IMG') {
			// Should I rather use $(el).attr(args)?
			ed.dom.setHTML(ed.dom.select('div.img_txt'), args["title"]);
			ed.dom.setAttribs(el, args);
		} else {
			ed.execCommand('mceInsertContent', false, '<img id="__mce_tmp" />', {skip_undo : 1});
			ed.dom.setAttribs('__mce_tmp', args);
			var nID=args["id"]+"-"+Math.floor(Math.random()*100)
			ed.dom.setAttrib(args["id"], 'id', nID);
			ed.undoManager.add();
			// Make sure the image is selected in case we insert a link
			ed.execCommand('mceSelectNode', false, $('#'+nID, ed.selection.getNode())[0], {skip_undo : 1});
			/* */
		}

		if (link_attrs) {
			tinyMCE.activeEditor.execCommand('mceInsertLink', false, link_attrs);
		}
		
		tinyMCEPopup.close();
	}
};

GalleryConDialog.preInit();
tinyMCEPopup.onInit.add(GalleryConDialog.init, GalleryConDialog);
