<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo $lang->line('TITLE_BROWSER') . ' ' . $lang->line('APP_VERSION'); ?></title>
<link type="text/css" rel="stylesheet" href="/js/jquery-ui-1.8.17.custom/css/black-tie/jquery-ui-1.8.17.custom.css" />
<link type="text/css" rel="stylesheet" href="/css/admin/style.css" media="screen, projection" />
<link rel="icon" href="/img/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />
<!--[if lte IE 7]>
	<link rel="stylesheet" type="text/css" href="/css/admin/ie.css" media="screen" />
<![endif]-->
<script type="text/javascript" language="javascript" src="/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" language="javascript" src="/js/jquery.alphanumeric.js"></script>
<script type="text/javascript" language="javascript" src="/js/admin/jquery.ba-replacetext.min.js"></script>
<script type="text/javascript" language="javascript" src="/js/admin/jquery.dropdownPlain.js"></script>
<script type="text/javascript" language="javascript" src="/js/jquery-ui-1.8.17.custom/js/jquery-ui-1.8.17.custom.min.js"></script>
<script type="text/javascript" language="javascript" src="/js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" language="javascript" src="/js/admin/ckeditor/ckeditor.js"></script>
<script type="text/javascript" language="javascript" src="/js/admin/ckeditor/adapters/jquery.js"></script>
<script type="text/javascript" language="javascript" src="/js/admin/ckfinder/ckfinder.js"></script>
<script type="text/javascript" language="javascript" src="/js/admin/edit_area/edit_area_full.js"></script>
<script type="text/javascript" language="javascript" src="/js/admin/script.js"></script>
</head>
<body>
<!-- JS verify layout start -->
<div id="noscript" class="ui-widget">
    <div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;"> 
        <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;">&nbsp;</span> 
        <strong><?php echo $lang->line('MESSAGE_UNSC_WARNING'); ?>:</strong> <?php echo $lang->line('MESSAGE_UNSC_JS_OFF'); ?></p>
    </div>
</div>
<!-- JS verify layout end -->
<!-- overlay layouts -->
<div id="ajax_loading_bg"></div>
<div id="ajax_loading_img"></div>
<div id="blocked_bg"></div>
<script type="text/javascript">
    $('#noscript, #blocked_bg').hide();
</script>
<!-- end of overlay layouts -->
