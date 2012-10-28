    <!-- page body -->
    <div id="page_body">
        <div class="tabs">
			<ul>
				<li><a href="#groups"><?php echo $lang->line('CONTENT_GROUPS'); ?></a></li>
                <?php if ($components_privileges->users_groups->edit && (isset($formdata['edit']) || @$group->id)) { ?>
				<li><a href="#edit_group"><?php echo $lang->line('ACT_EDIT'); ?></a></li>
                <?php } ?>
				<?php if ($components_privileges->users_groups->add) { ?>
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
                        <?php if ($components_privileges->users_groups->delete) { ?>
						<th><?php echo $lang->line('ACT_DELETE'); ?></th>
                        <?php } ?>
					</tr>
                    <?php $row_class = 'row_a'; foreach ($users_groups->result() as $row) { ?>
					<tr class="<?php echo $row_class; ?>">
						<td><?php echo ++$groups_position_number; ?></td>
						<td class="row_title"><?php if ($components_privileges->users_groups->edit) { ?><a href="/admin/users_groups/edit_group/<?php echo $row->id; ?>#edit_group" class="edit_row_link" title="<?php echo $lang->line('ACT_EDIT'); ?>"><?php } else { ?><a><?php } ?><?php echo $row->title; ?></a></td>
						<td><?php echo $row->comments; ?></td>
                        <?php if ($components_privileges->users_groups->delete) { ?>
						<td><a href="/admin/users_groups/delete_group/<?php echo $row->id; ?>" class="delete_row_link" title="<?php echo $lang->line('ACT_DELETE'); ?>"></a></td>
                        <?php } ?>
					</tr>
                    <?php $row_class = ($row_class == 'row_a') ? 'row_b' : 'row_a'; } ?>
                </table>
                <?php echo $pages_line; ?>
            </div>
            <?php if ($components_privileges->users_groups->edit && (isset($formdata['edit']) || @$group->id)) { ?>
			<div id="edit_group">
	            <form method="post" action="/admin/users_groups/edit_group/<?php echo $group->id; ?>#edit_group">
                	<table class="fields_line" cellspacing="0" cellpadding="0">
                    	<tr>
                        	<th><?php echo $lang->line('CONTENT_FORM_PARAMETER'); ?></th>
                            <th><?php echo $lang->line('CONTENT_FORM_VALUE'); ?></th>
                        </tr>
                        <tr class="row_a">
                        	<td class="first"><?php echo $lang->line('FIELD_TITLE'); ?> <span class="important">*</span></td>
                            <td><input type="text" name="title" value="<?php echo (isset($formdata['edit']['title']) ? $formdata['edit']['title'] : $group->title); ?>" maxlength="250" class="text" /></td>
                        </tr>
                        <tr class="row_b">
                        	<td class="first"><?php echo $lang->line('FIELD_COMMENTS'); ?></td>
                            <td><textarea name="comments" cols="70" rows="7" class="text field_comments"><?php echo (isset($formdata['edit']['comments']) ? $formdata['edit']['comments'] : $group->comments); ?></textarea></td>
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
			<?php if ($components_privileges->users_groups->add) { ?>
			<div id="add_group">
	            <form method="post" action="/admin/users_groups/add_group">
                	<table class="fields_line" cellspacing="0" cellpadding="0">
                    	<tr>
                        	<th><?php echo $lang->line('CONTENT_FORM_PARAMETER'); ?></th>
                            <th><?php echo $lang->line('CONTENT_FORM_VALUE'); ?></th>
                        </tr>
                        <tr class="row_a">
                        	<td class="first"><?php echo $lang->line('FIELD_TITLE'); ?> <span class="important">*</span></td>
                            <td><input type="text" name="title" value="<?php echo (isset($formdata['title']) ? $formdata['title'] : ''); ?>" maxlength="250" class="text" /></td>
                        </tr>
                        <tr class="row_b">
                        	<td class="first"><?php echo $lang->line('FIELD_COMMENTS'); ?></td>
                            <td><textarea name="comments" cols="70" rows="7" class="text field_comments"><?php echo (isset($formdata['comments']) ? $formdata['comments'] : ''); ?></textarea></td>
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
