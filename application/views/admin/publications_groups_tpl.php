    <!-- page body -->
    <div id="page_body">
        <div class="tabs">
			<ul>
				<li><a href="#groups"><?php echo $lang->line('CONTENT_GROUPS'); ?></a></li>
                <?php if ($modules_privileges->publications->edit && (isset($formdata['edit']) || @$group->id)) { ?>
				<li><a href="#edit_group"><?php echo $lang->line('ACT_EDIT'); ?></a></li>
                <?php } ?>
				<?php if ($modules_privileges->publications->add) { ?>
				<li><a href="#add_group"><?php echo $lang->line('ACT_ADD'); ?></a></li>
                <?php } ?>
			</ul>
			<div id="groups">
            	<?php echo $pages_line; ?>
				<table class="fields_line" cellspacing="0" cellpadding="0">
					<tr>
                    	<th><?php echo $lang->line('CONTENT_POSITION_NUMBER'); ?></th>
                        <th><?php echo $lang->line('FIELD_TITLE'); ?></th>
                        <th><?php echo $lang->line('FIELD_COMMENTS'); ?></th>
                        <?php if ($modules_privileges->publications->delete) { ?>
						<th><?php echo $lang->line('ACT_DELETE'); ?></th>
                        <?php } ?>
					</tr>
                    <?php $row_class = 'row_a'; foreach ($publications_groups->result() as $row) { ?>
					<tr class="<?php echo $row_class; ?>">
						<td><?php echo ++$groups_position_number; ?></td>
						<td class="row_title"><?php echo '<sub title="'. $lang->line('FIELD_PRIORITY') . ': ' . $row->priority . '">[' . $row->priority . ']</sub> ' . (!$row->id_resource ? '<sub title="'. $lang->line('FIELD_FOR_ALL_RESOURCES') . '">[G]</sub> ' : '') . ($row->flag_is_default ? '<sub title="'. $lang->line('FIELD_FLAG_IS_DEFAULT') . '">[D]</sub> ' : ''); ?><?php if ($modules_privileges->publications->edit) { ?><a href="/admin/publications_groups/edit_group/<?php echo $row->id; ?>#edit_group" class="edit_row_link" title="<?php echo $lang->line('ACT_EDIT'); ?>"><?php } else { ?><a><?php } ?><?php echo $row->title; ?></a></td>
						<td><?php echo $row->comments; ?></td>
                        <?php if ($modules_privileges->publications->delete) { ?>
						<td><a href="/admin/publications_groups/delete_group/<?php echo $row->id; ?>" class="delete_row_link" title="<?php echo $lang->line('ACT_DELETE'); ?>"></a></td>
                        <?php } ?>
					</tr>
                    <?php $row_class = ($row_class == 'row_a') ? 'row_b' : 'row_a'; } ?>
                </table>
                <?php echo $pages_line; ?>
            </div>
            <?php if ($modules_privileges->publications->edit && (isset($formdata['edit']) || @$group->id)) { ?>
			<div id="edit_group">
	            <form method="post" action="/admin/publications_groups/edit_group/<?php echo $group->id; ?>#edit_group">
                	<table class="fields_line" cellspacing="0" cellpadding="0">
                    	<tr>
                        	<th><?php echo $lang->line('CONTENT_FORM_PARAMETER'); ?></th>
                            <th><?php echo $lang->line('CONTENT_FORM_VALUE'); ?></th>
                        </tr>
                        <tr class="row_a">
                            <td class="first"><?php echo $lang->line('FIELD_PRIORITY'); ?></td>
                            <td><input type="text" name="priority" value="<?php echo (isset($formdata['edit']['priority']) ? $formdata['edit']['priority'] : $group->priority); ?>" maxlength="3" class="small_text integer" />&nbsp; <button class="value_add ui-state-default ui-corner-all" onclick="return false;"><span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></button> <button class="value_subtract ui-state-default ui-corner-all" onclick="return false;"><span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></button></td>
                        </tr>
                        <tr class="row_b">
                        	<td class="first"><?php echo $lang->line('FIELD_TITLE'); ?> <span class="important">*</span></td>
                            <td><input type="text" name="title" value="<?php echo (isset($formdata['edit']['title']) ? $formdata['edit']['title'] : $group->title); ?>" maxlength="250" class="text" /></td>
                        </tr>
                        <tr class="row_a">
                        	<td class="first"><?php echo $lang->line('FIELD_COMMENTS'); ?></td>
                            <td><textarea name="comments" cols="70" rows="7" class="text field_comments"><?php echo (isset($formdata['edit']['comments']) ? $formdata['edit']['comments'] : $group->comments); ?></textarea></td>
                        </tr>
                        <tr class="row_b">
                        	<td class="first"><?php echo $lang->line('FIELD_FLAG_IS_DEFAULT'); ?></td>
                            <td><input type="checkbox" name="flag_is_default" value="1"<?php echo ((isset($formdata['edit']['flag_is_default']) ? $formdata['edit']['flag_is_default'] : $group->flag_is_default) ? ' checked="checked" ' : ' '); ?>/></td>
                        </tr>
                        <tr class="row_a">
                        	<td class="first"><?php echo $lang->line('FIELD_FOR_ALL_RESOURCES'); ?></td>
                            <td><input type="checkbox" name="for_all_resources" value="1"<?php echo ((isset($formdata['edit']['for_all_resources']) ? $formdata['edit']['for_all_resources'] : !$group->id_resource) ? ' checked="checked" ' : ' '); ?>/></td>
                        </tr>
                    </table>
                    <div class="actions_line">
                    	<input type="submit" value="<?php echo $lang->line('ACT_SAVE'); ?>" />
	        	        <input type="reset" value="<?php echo $lang->line('ACT_RESET'); ?>" />
                        <?php if ($formdata['edit']) { ?><input type="button" value="<?php echo $lang->line('ACT_REFRESH'); ?>" /><?php } ?>
					</div>
            	</form>
			</div>
			<?php } ?>
			<?php if ($modules_privileges->publications->add) { ?>
			<div id="add_group">
	            <form method="post" action="/admin/publications_groups/add_group">
                	<table class="fields_line" cellspacing="0" cellpadding="0">
                    	<tr>
                        	<th><?php echo $lang->line('CONTENT_FORM_PARAMETER'); ?></th>
                            <th><?php echo $lang->line('CONTENT_FORM_VALUE'); ?></th>
                        </tr>
                        <tr class="row_a">
                            <td class="first"><?php echo $lang->line('FIELD_PRIORITY'); ?></td>
                            <td><input type="text" name="priority" value="<?php echo (isset($formdata['priority']) ? $formdata['priority'] : '0'); ?>" maxlength="3" class="small_text integer" />&nbsp; <button class="value_add ui-state-default ui-corner-all" onclick="return false;"><span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></button> <button class="value_subtract ui-state-default ui-corner-all" onclick="return false;"><span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></button></td>
                        </tr>
                        <tr class="row_b">
                        	<td class="first"><?php echo $lang->line('FIELD_TITLE'); ?> <span class="important">*</span></td>
                            <td><input type="text" name="title" value="<?php echo (isset($formdata['title']) ? $formdata['title'] : ''); ?>" maxlength="250" class="text" /></td>
                        </tr>
                        <tr class="row_a">
                        	<td class="first"><?php echo $lang->line('FIELD_COMMENTS'); ?></td>
                            <td><textarea name="comments" cols="70" rows="7" class="text field_comments"><?php echo (isset($formdata['comments']) ? $formdata['comments'] : ''); ?></textarea></td>
                        </tr>
                        <tr class="row_b">
                        	<td class="first"><?php echo $lang->line('FIELD_FLAG_IS_DEFAULT'); ?></td>
                            <td><input type="checkbox" name="flag_is_default" value="1"<?php echo ((isset($formdata['flag_is_default']) && $formdata['flag_is_default']) ? ' checked="checked" ' : ' '); ?>/></td>
                        </tr>
                        <tr class="row_a">
                        	<td class="first"><?php echo $lang->line('FIELD_FOR_ALL_RESOURCES'); ?></td>
                            <td><input type="checkbox" name="for_all_resources" value="1"<?php echo ((isset($formdata['for_all_resources']) && $formdata['for_all_resources']) ? ' checked="checked" ' : ' '); ?>/></td>
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
