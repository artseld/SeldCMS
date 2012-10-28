    <!-- page body -->
    <div id="page_body">
        <div class="tabs">
			<ul>
				<li><a href="#templates"><?php echo $lang->line('CONTENT_TEMPLATES'); ?></a></li>
                <?php if ($components_privileges->templates->edit && (isset($formdata['edit']) || @$template->id)) { ?>
				<li><a href="#edit_template"><?php echo $lang->line('ACT_EDIT'); ?></a></li>
                <?php } ?>
				<?php if ($components_privileges->templates->add) { ?>
				<li><a href="#add_template"><?php echo $lang->line('ACT_ADD'); ?></a></li>
                <?php } ?>
			</ul>
			<div id="templates">
            	<?php echo $pages_line; ?>
                <div class="search_line"><form method="post" action="/admin/templates/set_keywords">
                	<select name="group" size="1">
                        <option value="0"<?php echo ($keywords['group'] == 0 ? ' selected="selected"' : ''); ?>><?php echo $lang->line('FIELD_VALUE_ALL'); ?></option>
                    	<?php foreach ($templates_groups->result() as $row) { ?>
                        <?php if (!$row->id) continue; ?>
                    	<option value="<?php echo $row->id; ?>"<?php echo ($row->id == $keywords['group'] ? ' selected="selected"' : ''); ?>><?php echo $row->title; ?></option>
                        <?php } ?>
                    </select>
                	<input type="text" name="text" value="<?php echo $keywords['text']; ?>" />
                    <button type="submit" title="<?php echo $lang->line('ACT_SEARCH'); ?>">&nbsp;</button>
                    <a href="/admin/templates/clear_keywords" class="clear_link" title="<?php echo $lang->line('ACT_RESET'); ?>"></a>
				</form></div>
				<table class="fields_line" cellspacing="0" cellpadding="0">
					<tr>
                    	<th><?php echo $lang->line('CONTENT_POSITION_NUMBER'); ?></th>
                        <th><?php echo $lang->line('FIELD_GROUP'); ?></th>
                        <th><?php echo $lang->line('FIELD_ALIAS'); ?></th>
                        <th><?php echo $lang->line('FIELD_TITLE'); ?></th>
                        <th><?php echo $lang->line('FIELD_COMMENTS'); ?></th>
                        <?php if ($components_privileges->templates->delete) { ?>
						<th><?php echo $lang->line('ACT_DELETE'); ?></th>
                        <?php } ?>
					</tr>
                    <?php $row_class = 'row_a'; foreach ($templates->result() as $row) { ?>
					<tr class="<?php echo $row_class; ?>">
						<td><?php echo ++$templates_position_number; ?></td>
						<td><?php echo ($row->id_group ? $row->title_group : $lang->line('FIELD_VALUE_NO_GROUP')); ?></td>
						<td><?php echo $row->alias; ?></td>
						<td class="row_title"><?php if ($components_privileges->templates->edit) { ?><a href="/admin/templates/edit_template/<?php echo $row->id; ?>#edit_template" class="edit_row_link" title="<?php echo $lang->line('ACT_EDIT'); ?>"><?php } else { ?><a><?php } ?><?php echo $row->title; ?></a></td>
						<td><?php echo $row->comments; ?></td>
                        <?php if ($components_privileges->templates->delete) { ?>
						<td><a href="/admin/templates/delete_template/<?php echo $row->id; ?>" class="delete_row_link" title="<?php echo $lang->line('ACT_DELETE'); ?>"></a></td>
                        <?php } ?>
					</tr>
                    <?php $row_class = ($row_class == 'row_a') ? 'row_b' : 'row_a'; } ?>
                </table>
                <?php echo $pages_line; ?>
            </div>
            <?php if ($components_privileges->templates->edit && (isset($formdata['edit']) || @$template->id)) { ?>
			<div id="edit_template">
	            <form method="post" action="/admin/templates/edit_template/<?php echo $template->id; ?>#edit_template">
                	<table class="fields_line" cellspacing="0" cellpadding="0">
                    	<tr>
                        	<th><?php echo $lang->line('CONTENT_FORM_PARAMETER'); ?></th>
                            <th><?php echo $lang->line('CONTENT_FORM_VALUE'); ?></th>
                        </tr>
                        <tr class="row_a">
                        	<td class="first"><?php echo $lang->line('FIELD_GROUP'); ?> <span class="important">*</span></td>
                            <td><select name="id_group" size="1">
                                <option value="0"<?php echo (((isset($formdata['edit']['id_group']) ? $formdata['edit']['id_group'] : $template->id_group) == 0) ? ' selected="selected"' : ''); ?>><?php echo $lang->line('FIELD_VALUE_NO_GROUP'); ?></option>
                            	<?php foreach ($templates_groups->result() as $row) { ?>
                                <?php if (!$row->id) continue; ?>
                                <option value="<?php echo $row->id; ?>"<?php echo (((isset($formdata['edit']['id_group']) ? $formdata['edit']['id_group'] : $template->id_group) == $row->id) ? ' selected="selected"' : ''); ?>><?php echo $row->title; ?></option>
                                <?php } ?>
							</select></td>
                        </tr>
                        <tr class="row_b">
                        	<td class="first"><?php echo $lang->line('FIELD_ALIAS'); ?> <span class="important">*</span></td>
                            <td><input type="text" name="url" value="<?php echo $template->alias; ?>" maxlength="50" class="small_text" disabled="disabled" /></td>
                        </tr>
                        <tr class="row_a">
                        	<td class="first"><?php echo $lang->line('FIELD_TITLE'); ?> <span class="important">*</span></td>
                            <td><input type="text" name="title" value="<?php echo (isset($formdata['edit']['title']) ? $formdata['edit']['title'] : $template->title); ?>" maxlength="250" class="text" /></td>
                        </tr>
                        <tr class="row_b not_hover">
                        	<td class="first"><?php echo $lang->line('FIELD_BODY'); ?> <span class="important">*</span></td>
                            <td><textarea id="ea_static_edit" name="body" cols="70" rows="25" class="rte"><?php echo (isset($formdata['edit']['body']) ? $formdata['edit']['body'] : htmlspecialchars($template->body)); ?></textarea></td>
                        </tr>
                        <tr class="row_a">
                        	<td class="first"><?php echo $lang->line('FIELD_COMMENTS'); ?></td>
                            <td><textarea name="comments" cols="70" rows="7" class="text field_comments"><?php echo (isset($formdata['edit']['comments']) ? $formdata['edit']['comments'] : $template->comments); ?></textarea></td>
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
			<?php if ($components_privileges->templates->add) { ?>
			<div id="add_template">
	            <form method="post" action="/admin/templates/add_template">
                	<table class="fields_line" cellspacing="0" cellpadding="0">
                    	<tr>
                        	<th><?php echo $lang->line('CONTENT_FORM_PARAMETER'); ?></th>
                            <th><?php echo $lang->line('CONTENT_FORM_VALUE'); ?></th>
                        </tr>
                        <tr class="row_a">
                        	<td class="first"><?php echo $lang->line('FIELD_GROUP'); ?> <span class="important">*</span></td>
                            <td><select name="id_group" size="1">
                                <option value="0"<?php echo ((isset($formdata['id_group']) && $formdata['id_group'] == 0) ? ' selected="selected"' : ''); ?>><?php echo $lang->line('FIELD_VALUE_NO_GROUP'); ?></option>
                            	<?php foreach ($templates_groups->result() as $row) { ?>
                                <?php if (!$row->id) continue; ?>
                                <option value="<?php echo $row->id; ?>"<?php echo ((isset($formdata['id_group']) && $formdata['id_group'] == $row->id) ? ' selected="selected"' : ''); ?>><?php echo $row->title; ?></option>
                                <?php } ?>
							</select></td>
                        </tr>
                        <tr class="row_b">
                        	<td class="first"><?php echo $lang->line('FIELD_ALIAS'); ?> <span class="important">*</span></td>
                            <td><input type="text" name="alias" value="<?php echo (isset($formdata['alias']) ? $formdata['alias'] : ''); ?>" maxlength="50" class="small_text" /></td>
                        </tr>
                        <tr class="row_a">
                        	<td class="first"><?php echo $lang->line('FIELD_TITLE'); ?> <span class="important">*</span></td>
                            <td><input type="text" name="title" value="<?php echo (isset($formdata['title']) ? $formdata['title'] : ''); ?>" maxlength="250" class="text" /></td>
                        </tr>
                        <tr class="row_b not_hover">
                        	<td class="first"><?php echo $lang->line('FIELD_BODY'); ?> <span class="important">*</span></td>
                            <td><textarea id="ea_static_add" name="body" cols="70" rows="25" class="rte"><?php echo (isset($formdata['body']) ? $formdata['body'] : ''); ?></textarea></td>
                        </tr>
                        <tr class="row_a">
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
