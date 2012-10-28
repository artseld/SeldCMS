    <!-- page body -->
    <div id="page_body">
        <div class="tabs">
			<ul>
				<li><a href="#modules"><?php echo $lang->line('CONTENT_MODULES'); ?></a></li>
                <?php if ($components_privileges->modules->add) { ?>
				<li><a href="#add_module"><?php echo $lang->line('ACT_ADD'); ?></a></li>
                <?php } ?>
			</ul>
			<div id="modules">
            	<?php echo $pages_line; ?>
				<table class="fields_line" cellspacing="0" cellpadding="0">
					<tr>
                    	<th><?php echo $lang->line('CONTENT_POSITION_NUMBER'); ?></th>
                        <th><?php echo $lang->line('FIELD_ALIAS'); ?></th>
                        <th><?php echo $lang->line('FIELD_TITLE'); ?></th>
                        <th><?php echo $lang->line('FIELD_COMMENTS'); ?></th>
                        <?php if ($components_privileges->modules->delete) { ?>
						<th><?php echo $lang->line('ACT_DELETE'); ?></th>
                        <?php } ?>
					</tr>
                    <?php $row_class = 'row_a'; foreach ($modules->result() as $row) { ?>
					<tr class="<?php echo $row_class; ?>">
						<td><?php echo ++$modules_position_number; ?></td>
                        <td><?php echo $row->alias; ?></td>
						<td class="row_title"><a><?php echo $row->title; ?></a></td>
						<td><?php echo $row->comments; ?></td>
                        <?php if ($components_privileges->modules->delete) { ?>
						<td><a href="/admin/modules/delete_module/<?php echo $row->id; ?>" class="delete_row_link" title="<?php echo $lang->line('ACT_DELETE'); ?>"></a></td>
                        <?php } ?>
					</tr>
                    <?php $row_class = ($row_class == 'row_a') ? 'row_b' : 'row_a'; } ?>
                </table>
                <?php echo $pages_line; ?>
            </div>
            <?php if ($components_privileges->modules->add) { ?>
			<div id="add_module">
	            <form method="post" action="/admin/modules/add_module">
                	<table class="fields_line" cellspacing="0" cellpadding="0">
                    	<tr>
                        	<th><?php echo $lang->line('CONTENT_FORM_PARAMETER'); ?></th>
                            <th><?php echo $lang->line('CONTENT_FORM_VALUE'); ?></th>
                        </tr>
                        <tr class="row_a">
                        	<td class="first"><?php echo $lang->line('FIELD_ALIAS'); ?> <span class="important">*</span></td>
                            <td><input type="text" name="alias" value="<?php echo (isset($formdata['alias']) ? $formdata['alias'] : ''); ?>" maxlength="50" class="small_text" /></td>
                        </tr>
                    </table>
                    <div class="actions_line">
                    	<input type="submit" value="<?php echo $lang->line('ACT_ADD'); ?>" />
	        	        <input type="reset" value="<?php echo $lang->line('ACT_RESET'); ?>" />
					</div>
            	</form>
			</div>
			<?php } ?>
		</div>
	</div><!-- end of page body -->
