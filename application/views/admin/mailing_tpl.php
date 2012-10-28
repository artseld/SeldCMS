    <!-- page body -->
    <div id="page_body">
        <div class="tabs">
			<ul>
                <li><a href="#log"><?php echo $lang->line('CONTENT_MAILING_LOG'); ?></a></li>
				<?php if ($components_privileges->mailing->edit) { ?>
                <li><a href="#settings"><?php echo $lang->line('CONTENT_MAILING_SETTINGS'); ?></a></li>
                <?php } if ($components_privileges->mailing->add) { ?>
				<li><a href="#message"><?php echo $lang->line('CONTENT_MAILING_MESSAGE'); ?></a></li>
                <?php } ?>
			</ul>
            <div id="log">
				<?php echo $pages_line; ?>
                <div class="search_line"><form method="post" action="/admin/mailing/set_keywords">
                	<input type="text" name="text" value="<?php echo $keywords['text']; ?>" />
                    <button type="submit" title="<?php echo $lang->line('ACT_SEARCH'); ?>">&nbsp;</button>
                    <a href="/admin/mailing/clear_keywords" class="clear_link" title="<?php echo $lang->line('ACT_RESET'); ?>"></a>
				</form></div>
				<table class="fields_line" cellspacing="0" cellpadding="0">
					<tr>
                    	<th><?php echo $lang->line('CONTENT_POSITION_NUMBER'); ?></th>
                    	<th><?php echo $lang->line('FIELD_DATETIME_EVENT'); ?></th>
                        <th><?php echo $lang->line('FIELD_MAILING_TYPE'); ?></th>
                        <th><?php echo $lang->line('CONTENT_MAILING_ACCOUNTSNUM'); ?></th>
                        <th><?php echo $lang->line('FIELD_TITLE'); ?></th>
                        <?php if ($components_privileges->mailing->delete) { ?>
						<th><?php echo $lang->line('ACT_DELETE'); ?></th>
                        <?php } ?>
					</tr>
                    <?php $row_class = 'row_a'; foreach ($mailing_log->result() as $row) { ?>
					<tr class="<?php echo $row_class; ?>">
						<td><?php echo ++$mailing_log_position_number; ?></td>
						<td><?php echo $row->datetime_event; ?></td>
						<td><?php echo preg_replace('/&nbsp;\s/', '', $row->category); ?></td>
						<td><?php echo $row->subscribers_count; ?></td>
						<td class="row_title" nowrap="nowrap"><a href="<?php echo $row->id; ?>" class="show_row_link" title="<?php echo $lang->line('FIELD_BODY'); ?>"><?php echo $row->subject; ?></a></td>
                        <?php if ($components_privileges->mailing->delete) { ?>
						<td><a href="/admin/mailing/delete_log/<?php echo $row->id; ?>" class="delete_row_link" title="<?php echo $lang->line('ACT_DELETE'); ?>"></a></td>
                        <?php } ?>
					</tr>
                    <?php $row_class = ($row_class == 'row_a') ? 'row_b' : 'row_a'; } ?>
                </table>
                <?php echo $pages_line; ?>
                <br clear="all" />
            </div>
            <?php if ($components_privileges->mailing->edit) { ?>
			<div id="settings">
            	<form method="post" action="/admin/mailing/edit_settings">
                	<table class="fields_line" cellspacing="0" cellpadding="0">
                    	<tr>
                        	<th><?php echo $lang->line('CONTENT_FORM_PARAMETER'); ?></th>
                            <th><?php echo $lang->line('CONTENT_FORM_VALUE'); ?></th>
                        </tr>
                        <tr class="row_a">
                        	<td class="first"><?php echo $lang->line('FIELD_ACCOUNTS_NUM_IN_STREAM'); ?> <span class="important">*</span></td>
                            <td><input type="text" name="accounts_num_in_stream" value="<?php echo (isset($formdata['accounts_num_in_stream']) ? $formdata['accounts_num_in_stream'] : $mailing->accounts_num_in_stream); ?>" maxlength="3" class="small_text integer" /></td>
                        </tr>
                        <tr class="row_b">
                        	<td class="first"><?php echo $lang->line('FIELD_MAILING_TYPE'); ?> <span class="important">*</span></td>
                            <td><select name="subscribers_category" size="1">
                            	<?php foreach ($subscribers_categories->result() as $row) { ?>
                                <option value="<?php echo $row->id; ?>"<?php echo (((isset($formdata['edit']['subscribers_category']) ? $formdata['edit']['subscribers_category'] : $mailing->subscribers_category) == $row->id) ? ' selected="selected"' : ''); ?>><?php echo $row->title; ?></option>
                                <?php } ?>
							</select></td>
                        </tr>
                        <tr class="row_a not_hover">
                        	<td class="first"><?php echo $lang->line('FIELD_SIGNATURE'); ?></td>
                            <td><textarea name="signature" cols="70" rows="7" <?php if ($global_flag_use_rte) : ?>id="ckeditor-basic"<?php endif ?> class="text field_comments"><?php echo (isset($formdata['signature']) ? $formdata['signature'] : $mailing->signature); ?></textarea></td>
                        </tr>
                    </table>
                    <?php if ($components_privileges->mailing->edit) { ?>
                    <div class="actions_line">
                    	<input type="submit" value="<?php echo $lang->line('ACT_SAVE'); ?>" />
	        	        <input type="reset" value="<?php echo $lang->line('ACT_RESET'); ?>" />
                        <?php if ($formdata) { ?><input type="button" value="<?php echo $lang->line('ACT_REFRESH'); ?>" /><?php } ?>
					</div>
                    <?php } ?>
            	</form>
            </div>
            <?php } ?>
            <?php if ($components_privileges->mailing->add) { ?>
		    <script language="javascript" type="text/javascript" src="/js/admin/script.mailing.js"></script>
		    <!-- HIDDEN AREA -->
			<div id="operation_dialog" class="dialog_window" title="<?php echo $lang->line('CONTENT_EMPTYDATA_TITLE'); ?>">
				<p><?php echo $lang->line('CONTENT_EMPTYDATA_BODY'); ?></p>
			</div>
            <div id="log_refresh_dialog" class="dialog_window" title="<?php echo $lang->line('CONTENT_MAILING_LOG_REFRESH_TITLE'); ?>">
				<p><?php echo $lang->line('CONTENT_MAILING_LOG_REFRESH_BODY'); ?></p>
			</div>
            <div id="details_dialog" class="dialog_window" title="<?php echo $lang->line('CONTENT_MAILING_DETAILS_TITLE'); ?>">
				<p><?php echo $lang->line('CONTENT_MAILING_DETAILS_BODY'); ?></p>
			</div><!-- END OF HIDDEN AREA -->
			<div id="message">
	            <form method="post" action="/admin/mailing/#message">
                	<table class="fields_line" cellspacing="0" cellpadding="0">
                    	<tr>
                        	<th><?php echo $lang->line('CONTENT_FORM_PARAMETER'); ?></th>
                            <th><?php echo $lang->line('CONTENT_FORM_VALUE'); ?></th>
                        </tr>
						<tr class="row_a">
                        	<td class="first"><?php echo $lang->line('FIELD_TIMELEFT'); ?></td>
							<td><div id="secondsmeter">-</div></td>
						</tr>
                        <tr class="row_b">
                        	<td class="first"><?php echo $lang->line('FIELD_PROCESSBAR'); ?></td>
                            <td><div id="processbar">-</div></td>
                        </tr>
                        <tr class="row_a">
                        	<td class="first"><?php echo $lang->line('FIELD_TITLE'); ?> <span class="important">*</span></td>
                            <td><input type="text" name="subject" value="" maxlength="250" class="text" /></td>
                        </tr>
                        <tr class="row_b">
                        	<td class="first"><?php echo $lang->line('FIELD_TITLE_IN_TEXT'); ?></td>
                            <td><input type="text" name="title" value="" maxlength="250" class="text" /></td>
                        </tr>
                        <tr class="row_a not_hover">
                        	<td class="first"><?php echo $lang->line('FIELD_BODY'); ?> <span class="important">*</span></td>
                            <td><textarea name="message" cols="70" rows="7" <?php if ($global_flag_use_rte) : ?>id="ckeditor-full"<?php endif ?> class="text"></textarea></td>
                        </tr>
                    </table>
                    <?php if ($components_privileges->mailing->add) { ?>
                    <div class="questions_line">
                    	<input type="button" value="<?php echo $lang->line('ACT_SEND'); ?>" />
	        	        <input type="reset" value="<?php echo $lang->line('ACT_RESET'); ?>" />
					</div>
                    <?php } ?>
            	</form>
			</div>
            <script type="text/javascript">
			  var sendButton = $('input:button', '#page_body .questions_line');
			  var detailsLink = $('#log').find('a.show_row_link');
			  function sendIt() {
				  blockEnd();
				  mailingRun(
					  "<?php echo $access_key; ?>", sendButton, <?php echo $current_resource; ?>,
					  $('#message').find('input[name="subject"]').val(), $('#message').find('input[name="title"]').val(), $('#message').find('textarea[name="message"]').val(),
					  <?php echo $accounts_num; ?>, <?php echo $mailing->accounts_num_in_stream; ?>,
					  "<?php echo $lang->line('MESSAGE_SC_START_PROCESSBAR'); ?>",
					  "<?php echo $lang->line('MESSAGE_SC_DONE_PROCESSBAR'); ?>",
					  "<?php echo $lang->line('MESSAGE_UNSC_ERROR_PROCESSBAR'); ?>"
				  );
			  };
			  function trim(str) {
				  var	str = str.replace(/^\s\s*/, ''),
				  ws = /\s/,
				  i = str.length;
				  while (ws.test(str.charAt(--i)));
				  return str.slice(0, i + 1);
			  };
			  $(document).ready(function(){
				  // Submit click actions
				  sendButton.click(function(){
					  //blockStart();
					  if (
					  	trim($('#message').find('input[name="subject"]').val()) == "" ||
						trim($('#message').find('textarea[name="message"]').val()) == ""
					  ) {
						  $('#operation_dialog').dialog('open');
						  return false;
					  }
					  else
					  {
						  sendIt();
					  }
				  });
				  // Operation dialog window
				  $('#operation_dialog').dialog({
					  autoOpen: false,
					  width: 400,
					  open: function() {
						  blockStart();
					  },
					  buttons: {
						  "Да": function() {
							  $(this).dialog("close");
							  sendIt();
						  },
						  "Нет": function() {
							  $(this).dialog("close");
						  }
					  },
					  close: function() {
						  blockEnd();
					  }
				  });
				  // Log Refresh dialog window
				  $('#log_refresh_dialog').dialog({
					  autoOpen: false,
					  width: 400,
					  open: function() {
						  blockStart();
					  },
					  buttons: {
						  "Да": function() {
							  $(this).dialog("close");
							  window.location.replace('/admin/mailing');
						  },
						  "Нет": function() {
							  $(this).dialog("close");
						  }
					  },
					  close: function() {
						  blockEnd();
					  }
				  });
				  // Details click actions
				  detailsLink.click(function(){
				  	  $.post(
						  '../../application/admin/ajax/process.mailing.details.php',
						  {
							  access_key: "<?php echo $access_key; ?>",
							  mailing_resource: <?php echo $current_resource; ?>,
							  event_id: $(this).attr('href')
						  },
						  function(data) {
							  $('#details_dialog').html(data);
						  }
					  );
					  $('#details_dialog').dialog('open');
					  return false;
				  });
				  // Details dialog window
				  $('#details_dialog').dialog({
					  autoOpen: false,
					  width: 640,
					  height: 480,
					  open: function() {
						  blockStart();
					  },
					  buttons: false,
					  close: function() {
						  blockEnd();
						  $('#details_dialog').html('<p><?php echo $lang->line('CONTENT_MAILING_DETAILS_BODY'); ?></p>');
					  }
				  });
			  });
		  	</script>
            <?php } ?>
		</div>
	</div><!-- end of page body -->
