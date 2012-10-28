<!-- CONTENT -->
<div id="content">

    <!-- page header [title && sub-navigation] -->
    <div id="page_header">
    	<h1><?php echo $title; ?></h1>
    </div><!-- end of page header -->

    <?php if ($message['status']) { ?>
    <!-- page message line -->
        <?php if ($message['status'] == 'done') { ?><div id="page_message" title="<?php echo $lang->line('CONTENT_CLICK_TO_CLOSE'); ?>" class="ui-widget">
            <div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;">
                <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		<strong><?php echo $lang->line('MESSAGE_SC_DONE');  ?>:</strong> <?php echo $message['body']; ?></p>
            </div>
	</div><?php } ?>
    <?php if ($message['status'] == 'error') { ?><div id="page_message" title="<?php echo $lang->line('CONTENT_CLICK_TO_CLOSE'); ?>" class="ui-widget">
        <div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"> 
            <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
            <strong><?php echo $lang->line('MESSAGE_UNSC_ERROR');  ?>:</strong> <?php echo $message['body']; ?></p>
        </div>
    </div><?php } ?><!-- end of page error line -->
    <?php } ?>
