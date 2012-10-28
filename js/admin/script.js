// AdminPanel JavaScript Document

$(function(){

	// Variables
	var currentLocation = window.location;
	var deleteLink;	// link `href` or button `onclick` attribute value for delete dialog window
	var deleteText;	// original delete dialog window text

	// Buttons
	$('button, input:submit, input:reset, input:button', '#auth_container, #page_body').button();

	// Submit click actions
	$('input:submit', '#auth_container, #page_body .actions_line').click(function() {
		ajaxStart();
	});

	// Refresh buttons actions
	$('input:button', '#page_body .actions_line').click(function(){
		ajaxStart();
		location.reload();
	});

	// Shortcuts reset action
	$('input:reset', '#add_user_shortcut').click(function() {
		$('#form_shortcuts ul li').each(function() {
			$(this).css('background', '');
		});
		$('#form_shortcuts').next().val('');
	});

	// Resources change action
	$('#resources_selector').change(function() {
		window.location.replace('/admin/main/change_resource/' + $(this).children('option:selected').val());
	});

	// No-click navigation links
	$('.nav_group').click(function() {
		return false;
	});

	// Exit dialog window
	$('#exit_dialog').dialog({
		autoOpen: false,
		width: 400,
		buttons: {
			"Yes": function() {
				$(this).dialog("close");
				ajaxStart();
				window.location.replace('/admin/auth/quit');
			},
			"No": function() {
				$(this).dialog("close");
			}
		},
		close: function() {
			blockEnd();
		}
	});

	// Exit app Link
	$('#exit_link').click(function() {
		blockStart();
		$('#exit_dialog').dialog('open');
		return false;
	});

	// Messages/errors "click-to-close" actions
	$('#page_message').click(function() {
		$(this).hide(700);
	});

	// Tabs
	$('.tabs').tabs();

	// Tabs click messages/errors correct width
	$('.tabs a').click(function(){
		// Messages/errors correct width
		$('#page_message').css('left','8px');
		$('#page_message').width(document.width-16);
		// Re-init EditArea textareas
		initEditArea();
	});

	// Accordion
	$('.accordion').accordion({
		collapsible: true,
		header: "h3"
	});

	// Pre-set shortcuts data
	$('#form_shortcuts ul li').each(function() {
		if ($(this).children('a').attr('href') == $('#form_shortcuts').next().val()) $(this).css('background', '#ccc');
	});

	// Shortcuts click actions in add form
	$('#form_shortcuts ul li').click(function() {
		$('#form_shortcuts').next().val($(this).children('a').attr('href'));
		$('#form_shortcuts ul li').each(function() {
			$(this).css('background', '');
		});
		$(this).css('background', '#ccc');
		return false;
	});

	// Delete dialog window
	$('#delete_dialog').dialog({
		autoOpen: false,
		width: 400,
		buttons: {
			"Yes": function() {
				$(this).dialog("close");
				ajaxStart();
				window.location.replace(deleteLink);
			},
			"No": function() {
				$(this).dialog("close");
			}
		},
		close: function() {
			$('#delete_dialog p').html(deleteText);
			blockEnd();
		}
	});

	// Delete shortcuts links actions
	$('.delete_shortcut_link').click(function() {
		blockStart();
		deleteLink = $(this).attr('href');
		deleteText = $('#delete_dialog p').html();
		$('#delete_dialog p').replaceText('{item}', '"' + $(this).parent().find('span').html() + '"');
		$('#delete_dialog').dialog('open');
		return false;
	});

	// Delete rows links actions
	$('.delete_row_link').click(function() {
		blockStart();
		deleteLink = $(this).attr('href');
		deleteText = $('#delete_dialog p').html();
		$('#delete_dialog p').replaceText('{item}', '"' + $(this).parent().parent().find('.row_title a').html() + '"');
		$('#delete_dialog').dialog('open');
		return false;
	});

	// Delete tree links actions
	$('.delete_tree_link').click(function() {
		blockStart();
		deleteLink = $(this).attr('href');
		deleteText = $('#delete_dialog p').html();
		$('#delete_dialog p').replaceText('{item}', '"' + $(this).prev().html() + '"');
		$('#delete_dialog').dialog('open');
		return false;
	});

	// Edit rows links actions
	$('.edit_row_link').click(function() {
		ajaxStart();
		if ($(this).attr('href') == currentLocation.pathname + currentLocation.hash) {
			window.location.reload(false);
			return false;
		}
		window.location.replace($(this).attr('href'));
	});

	// Hide hidden
	$('.hidden').hide();

    // Fields with number content
    $('.integer').numeric();
    $('.double').numeric({allow : '.'});
    $('.rating').numeric({allow : '-'});

    // Priority focus out event
    $('input[name="priority"]').focusout(function() {
        if ($(this).val() == '') $(this).val('0');
    });
    // Karma focus out event
    $('input[name="rating"]').focusout(function() {
        if ($(this).val() == '') $(this).val('0');
    });

    // Value change buttons actions
    $('.value_add').click(function() {
        field = $(this).prev();
        field_val = field.val();
        if (field.hasClass('integer') && !field.hasClass('rating')) {
            if (field_val < 999) field.val(++field_val);
            if (field_val < 0) field.val(0);
        }
        if (field.hasClass('rating') && !field.hasClass('integer')) {
            if (field_val < 2147483647) field.val(++field_val);
            if (field_val < -2147483648) field.val(-2147483648);
        }
        return false;
    });
    $('.value_subtract').click(function() {
        field = $(this).prev().prev();
        field_val = field.val();
        if (field.hasClass('integer') && !field.hasClass('rating')) {
            if (field_val > 0) field.val(--field_val);
            if (field_val < 0) field.val(0);
        }
        if (field.hasClass('rating') && !field.hasClass('integer')) {
            if (field_val > -2147483648) field.val(--field_val);
            if (field_val < -2147483648) field.val(-2147483648);
        }
        return false;
    });

	// Date field calendar
	$('.date_field').datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd',
		firstDay: 1,
//		monthNamesShort: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'],
//		dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб']
	});
	$('.datetime_field').datetimepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd',
//		firstDay: 1,
//		monthNamesShort: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'],
//		dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
		showSecond: true,
		timeFormat: 'hh:mm:ss',
//		timeText: 'Время',
//		hourText: 'Часы',
//		minuteText: 'Минуты',
//		secondText: 'Секунды',
//		currentText: 'Теперь',
//		closeText: 'Закрыть',
		showButtonPanel: false
	});

        // CKEditor run!
        var finder = new CKFinder();
        finder.basePath = '/js/admin/ckfinder';

        var CKConfigFull = {
                toolbar : "Full",
		filebrowserBrowseUrl : finder.basePath + '/ckfinder.html',
		filebrowserImageBrowseUrl : finder.basePath + '/ckfinder.html?Type=Images',
		filebrowserFlashBrowseUrl : finder.basePath + '/ckfinder.html?Type=Flash',
		filebrowserUploadUrl : finder.basePath + '/core/connector/php/connector.php?command=QuickUpload&type=Files&currentFolder=/Public%20Folder',
		filebrowserImageUploadUrl : finder.basePath + '/core/connector/php/connector.php?command=QuickUpload&type=Images&currentFolder=/Public%20Folder',
		filebrowserFlashUploadUrl : finder.basePath + '/core/connector/php/connector.php?command=QuickUpload&type=Flash&currentFolder=/Public%20Folder'
	};
        var CKConfigBasic = {
                toolbar : "Basic",
		filebrowserBrowseUrl : finder.basePath + '/ckfinder.html',
		filebrowserImageBrowseUrl : finder.basePath + '/ckfinder.html?Type=Images',
		filebrowserFlashBrowseUrl : finder.basePath + '/ckfinder.html?Type=Flash',
		filebrowserUploadUrl : finder.basePath + '/core/connector/php/connector.php?command=QuickUpload&type=Files&currentFolder=/Public%20Folder',
		filebrowserImageUploadUrl : finder.basePath + '/core/connector/php/connector.php?command=QuickUpload&type=Images&currentFolder=/Public%20Folder',
		filebrowserFlashUploadUrl : finder.basePath + '/core/connector/php/connector.php?command=QuickUpload&type=Flash&currentFolder=/Public%20Folder'
	};

	// Initialize the editor.
	// Callback function can be passed and executed after full instance creation.
        $('#ckeditor-full').ckeditor(CKConfigFull);
        $('#ckeditor2-full').ckeditor(CKConfigFull);
        $('#ckeditor-basic').ckeditor(CKConfigBasic);
        $('#ckeditor2-basic').ckeditor(CKConfigBasic);

        
	// ---

	// Dialog
	$('#dialog').dialog({
		autoOpen: false,
		width: 600,
		buttons: {
			"Ok": function() {
				$(this).dialog("close");
			},
			"Cancel": function() {
				$(this).dialog("close");
			}
		}
	});

	// Dialog Link
	$('#dialog_link').click(function(){
		$('#dialog').dialog('open');
		return false;
	});
	
	// Datepicker
	$('#datepicker').datepicker({
		inline: true
	});

	// Slider
	$('#slider').slider({
		range: true,
		values: [17, 67]
	});

	// Progressbar
	$("#progressbar").progressbar({
		value: 20
	});

	//hover states on the static widgets
	$('#dialog_link, ul#icons li').hover(
		function() { $(this).addClass('ui-state-hover'); },
		function() { $(this).removeClass('ui-state-hover'); }
	);

});

// On-load document events
$(document).ready(function(){

	// auth events
	if ($('input[name="user_login"]').length > 0) {
		$('input[name="user_login"]').focus();
	}

	if ($('.tabs').length > 0) {
		$('.tabs').show();
	}

	// structure tree events
	if ($('#tree .inner_list').length > 0) {
		var tree = $('#tree .inner_list');
		$('li[class!="hidden"]', tree.get(0)).each(
			function()
			{
				subbranch = $('ul', this);
				if (subbranch.size() > 0) {
					if (subbranch.eq(0).css('display') == 'none') {
						$(this).prepend('<img src="/img/admin/bullet_toggle_plus.png" width="16" height="16" class="expandImage" />');
					} else {
						$(this).prepend('<img src="/img/admin/bullet_toggle_minus.png" width="16" height="16" class="expandImage" />');
					}
				} else {
					$(this).prepend('<img src="/img/admin/spacer.gif" width="16" height="16" class="expandImage" />');
				}
			}
		);
		$('img.expandImage', tree.get(0)).click(
			function()
			{
				if (this.src.indexOf('spacer') == -1) {
					subbranch = $('ul', this.parentNode).eq(0);
					if (subbranch.css('display') == 'none') {
						subbranch.show();
						this.src = '/img/admin/bullet_toggle_minus.png';
					} else {
						subbranch.hide();
						this.src = '/img/admin/bullet_toggle_plus.png';
					}
				}
			}
		);
	}

	// Messages/errors correct width
	$('#page_message').css('left','8px');
	$('#page_message').width(document.width-16);

	// Fields `comments` maxlength
	$('.field_comments').keyup(function() {
		$('.field_comments').each(function() {
			var max = 500;
			$(this).after("");
			textareaMaxLength(this, max);
			$(this).bind("keyup keydown", function () { textareaMaxLength(this, max) });
		});
	});

	// EditArea init textareas (for edit & add actions)
	initEditArea();
});

// On-resize document events
window.onresize = function() {

	// Messages/errors correct width
	$('#page_message').css('left','8px');
	$('#page_message').width(document.width-16);

};

// global blocking window show
function blockStart() {
	$('#blocked_bg').show();
}

// global blocking window hide
function blockEnd() {
	$('#blocked_bg').hide();
}

// global ajax window show
function ajaxStart() {
	$('#ajax_loading_bg, #ajax_loading_img').show();
}

// global ajax window hide
function ajaxEnd() {
	$('#ajax_loading_bg, #ajax_loading_img').hide();
}

// textarea maxlength fuction
function textareaMaxLength(element, max) {
	//var max = parseInt($(element).attr('maxlength'));
	if($(element).val().length > max){
	$(element).val($(element).val().substr(0, max));
	}
	//$(element).parent().find('.charsRemaining').html('Caracteres restantes: ' + (max - $(element).val().length));
}

/* === Native JS functions === */

// EditArea init textareas (for edit & add actions)
function initEditArea() {
	// --- dynamic content add
	if ($('#ea_dynamic_add:visible').length > 0) editAreaLoader.init({
		id: "ea_dynamic_add"	// id of the textarea to transform
		,start_highlight: true
		,allow_toggle: false
		,language: "en"
		,syntax: "php"
		,word_wrap: false
		,toolbar: "new_document, search, go_to_line, fullscreen, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight, word_wrap, |, help"
		,syntax_selection_allow: "css,html,js,php,xml,sql"
		,show_line_colors: true
	});
	// --- dynamic content edit
	if ($('#ea_dynamic_edit:visible').length > 0) editAreaLoader.init({
		id: "ea_dynamic_edit"	// id of the textarea to transform
		,start_highlight: true
		,allow_toggle: false
		,language: "en"
		,syntax: "php"
		,word_wrap: false
		,toolbar: "new_document, search, go_to_line, fullscreen, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight, word_wrap, |, help"
		,syntax_selection_allow: "css,html,js,php,xml,sql"
		,show_line_colors: true
	});
	// --- static content add
	if ($('#ea_static_add:visible').length > 0) editAreaLoader.init({
		id: "ea_static_add"	// id of the textarea to transform
		,start_highlight: true
		,allow_toggle: false
		,language: "en"
		,syntax: "html"
		,word_wrap: false
		,toolbar: "new_document, search, go_to_line, fullscreen, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight, word_wrap, |, help"
		,syntax_selection_allow: "css,html,js,php,xml,sql"
		,show_line_colors: true
	});
	// --- static content edit
	if ($('#ea_static_edit:visible').length > 0) editAreaLoader.init({
		id: "ea_static_edit"	// id of the textarea to transform
		,start_highlight: true
		,allow_toggle: false
		,language: "en"
		,syntax: "html"
		,word_wrap: false
		,toolbar: "new_document, search, go_to_line, fullscreen, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight, word_wrap, |, help"
		,syntax_selection_allow: "css,html,js,php,xml,sql"
		,show_line_colors: true
	});
}