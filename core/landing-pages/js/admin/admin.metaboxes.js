jQuery(document).ready(function($) {	jQuery('.upload_image_button').click(		function()		{			var media_name = jQuery(this).attr('id');			media_name = media_name.replace('uploader_','');			//alert(media_name);			jQuery.cookie('media_name', media_name);			jQuery.cookie('media_init', 1);			tb_show('', 'media-upload.php?type=image&type=image&amp;TB_iframe=true');			return false;		}	 );	window.tb_remove = function()	{		$("#TB_imageOff").unbind("click");		$("#TB_closeWindowButton").unbind("click");		$("#TB_window").fadeOut("fast",function(){$('#TB_window,#TB_overlay,#TB_HideSelect').trigger("unload").unbind().remove();});		$("#TB_load").remove();		if (typeof document.body.style.maxHeight == "undefined") {//if IE 6			$("body","html").css({height: "auto", width: "auto"});			$("html").css("overflow","");		}		document.onkeydown = "";		document.onkeyup = "";		jQuery.cookie('media_init', 0);		return false;	}	window.send_to_editor = function(h) {		if (jQuery.cookie('media_init')==1)		{			var imgurl = jQuery('img',h).attr('src');			if (!imgurl)			{				var array = h.match("src=\"(.*?)\"");				imgurl = array[1];			}			//alert(jQuery.cookie('media_name'));			jQuery('#' + jQuery.cookie('media_name')).val(imgurl);			jQuery.cookie('media_init', 0);			tb_remove();		}		else		{			var ed, mce = typeof(tinymce) != 'undefined', qt = typeof(QTags) != 'undefined';			if ( !wpActiveEditor ) {				if ( mce && tinymce.activeEditor ) {					ed = tinymce.activeEditor;					wpActiveEditor = ed.id;				} else if ( !qt ) {					return false;				}			} else if ( mce ) {				if ( tinymce.activeEditor && (tinymce.activeEditor.id == 'mce_fullscreen' || tinymce.activeEditor.id == 'wp_mce_fullscreen') )					ed = tinymce.activeEditor;				else					ed = tinymce.get(wpActiveEditor);			}			if ( ed && !ed.isHidden() ) {				// restore caret position on IE				if ( tinymce.isIE && ed.windowManager.insertimagebookmark )					ed.selection.moveToBookmark(ed.windowManager.insertimagebookmark);				if ( h.indexOf('[caption') === 0 ) {					if ( ed.wpSetImgCaption )						h = ed.wpSetImgCaption(h);				} else if ( h.indexOf('[gallery') === 0 ) {					if ( ed.plugins.wpgallery )						h = ed.plugins.wpgallery._do_gallery(h);				} else if ( h.indexOf('[embed') === 0 ) {					if ( ed.plugins.wordpress )						h = ed.plugins.wordpress._setEmbed(h);				}				ed.execCommand('mceInsertContent', false, h);			} else if ( qt ) {				QTags.insertContent(h);			} else {				document.getElementById(wpActiveEditor).value += h;			}			jQuery.cookie('media_init', 0);			try{tb_remove();}catch(e){};		}	}});