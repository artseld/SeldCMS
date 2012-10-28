    <!-- page body -->
    <div id="page_body">
        <div class="tabs">
			<ul>
				<li><a href="#containers"><?php echo $lang->line('CONTENT_CONTAINERS'); ?></a></li>
                <?php if ($components_privileges->containers->edit && (isset($formdata['edit']) || @$container->id)) { ?>
				<li><a href="#edit_container"><?php echo $lang->line('ACT_EDIT'); ?></a></li>
                <?php } ?>
				<?php if ($components_privileges->containers->add) { ?>
				<li><a href="#add_container"><?php echo $lang->line('ACT_ADD'); ?></a></li>
                <?php } ?>
			</ul>
			<div id="containers">
            	<?php echo $pages_line; ?>
                <div class="search_line"><form method="post" action="/admin/containers/set_keywords">
                	<select name="group" size="1">
                        <option value="0"<?php echo ($keywords['group'] == 0 ? ' selected="selected"' : ''); ?>><?php echo $lang->line('FIELD_VALUE_ALL'); ?></option>
                    	<?php foreach ($containers_groups->result() as $row) { ?>
                        <?php if (!$row->id) continue; ?>
                    	<option value="<?php echo $row->id; ?>"<?php echo ($row->id == $keywords['group'] ? ' selected="selected"' : ''); ?>><?php echo $row->title; ?></option>
                        <?php } ?>
                    </select>
                	<input type="text" name="text" value="<?php echo $keywords['text']; ?>" />
                    <button type="submit" title="<?php echo $lang->line('ACT_SEARCH'); ?>">&nbsp;</button>
                    <a href="/admin/containers/clear_keywords" class="clear_link" title="<?php echo $lang->line('ACT_RESET'); ?>"></a>
				</form></div>
				<table class="fields_line" cellspacing="0" cellpadding="0">
					<tr>
                    	<th><?php echo $lang->line('CONTENT_POSITION_NUMBER'); ?></th>
                        <th><?php echo $lang->line('FIELD_GROUP'); ?></th>
                        <th><?php echo $lang->line('FIELD_ALIAS'); ?></th>
                        <th><?php echo $lang->line('FIELD_TITLE'); ?></th>
                        <th><?php echo $lang->line('FIELD_COMMENTS'); ?></th>
                        <?php if ($components_privileges->containers->delete) { ?>
						<th><?php echo $lang->line('ACT_DELETE'); ?></th>
                        <?php } ?>
					</tr>
                    <?php $row_class = 'row_a'; foreach ($containers->result() as $row) { ?>
					<tr class="<?php echo $row_class; ?>">
						<td><?php echo ++$containers_position_number; ?></td>
						<td nowrap="nowrap"><?php echo '<sub title="'. $lang->line('FIELD_PRIORITY') . ': ' . $row->priority_group . '">[' . $row->priority_group . ']</sub> ' . ($row->id_group ? $row->title_group : $lang->line('FIELD_VALUE_NO_GROUP')); ?></td>
						<td><?php echo $row->alias; ?></td>
						<td class="row_title" nowrap="nowrap">
                                                    <img src="/img/admin/ico_<?php echo $row->id_type == Document_model::TYPE_DYNAMIC ? 'dynamic' : 'static' ?>.png" alt="<?php echo $row->id_type == Document_model::TYPE_DYNAMIC ? $lang->line('FIELD_TYPE_DYNAMIC') : $lang->line('FIELD_TYPE_STATIC') ?>" title="<?php echo $row->id_type == Document_model::TYPE_DYNAMIC ? $lang->line('FIELD_TYPE_DYNAMIC') : $lang->line('FIELD_TYPE_STATIC') ?>" width="13" height="13" style="border: 0; margin-right: 8px;" />
                                                    <?php if ($components_privileges->containers->edit) { ?><sub title="<?php echo $lang->line('FIELD_PRIORITY') . ': ' . $row->priority; ?>">[<?php echo $row->priority; ?>]</sub> <a href="/admin/containers/edit_container/<?php echo $row->id; ?>#edit_container" class="edit_row_link" title="<?php echo $lang->line('ACT_EDIT'); ?>"><?php } else { ?><a><?php } ?><?php echo $row->title; ?></a>
                                                </td>
						<td><?php echo $row->comments; ?></td>
                        <?php if ($components_privileges->containers->delete) { ?>
						<td><a href="/admin/containers/delete_container/<?php echo $row->id; ?>" class="delete_row_link" title="<?php echo $lang->line('ACT_DELETE'); ?>"></a></td>
                        <?php } ?>
					</tr>
                    <?php $row_class = ($row_class == 'row_a') ? 'row_b' : 'row_a'; } ?>
                </table>
                <?php echo $pages_line; ?>
                <br clear="all" />
            </div>
            <?php if ($components_privileges->containers->edit && (isset($formdata['edit']) || @$container->id)) { ?>
			<div id="edit_container">
	            <form method="post" action="/admin/containers/edit_container/<?php echo $container->id; ?>#edit_container">
                	<table class="fields_line" cellspacing="0" cellpadding="0">
                    	<tr>
                        	<th><?php echo $lang->line('CONTENT_FORM_PARAMETER'); ?></th>
                            <th><?php echo $lang->line('CONTENT_FORM_VALUE'); ?></th>
                        </tr>
                        <tr class="row_a">
                        	<td class="first"><?php echo $lang->line('FIELD_GROUP'); ?> <span class="important">*</span></td>
                            <td><select name="id_group" size="1">
                                <option value="0"<?php echo (((isset($formdata['edit']['id_group']) ? $formdata['edit']['id_group'] : $container->id_group) == 0) ? ' selected="selected"' : ''); ?>><?php echo $lang->line('FIELD_VALUE_NO_GROUP'); ?></option>
                            	<?php foreach ($containers_groups->result() as $row) { ?>
                                <?php if (!$row->id) continue; ?>
                                <option value="<?php echo $row->id; ?>"<?php echo (((isset($formdata['edit']['id_group']) ? $formdata['edit']['id_group'] : $container->id_group) == $row->id) ? ' selected="selected"' : ''); ?>><?php echo $row->title; ?></option>
                                <?php } ?>
							</select></td>
                        </tr>
                        <tr class="row_b">
                        	<td class="first"><?php echo $lang->line('FIELD_DOCUMENT'); ?> <span class="important">*</span></td>
                            <td><select name="id_document" size="1">
                            	<?php foreach ($documents_list as $k => $v) { ?>
                                <option value="<?php echo $v['id']; ?>"<?php echo (((isset($formdata['edit']['id_document']) ? $formdata['edit']['id_document'] : $container->id_document) == $v['id']) ? ' selected="selected"' : ''); ?>><?php echo $v['title']; ?></option>
                                <?php } ?>
							</select></td>
                        </tr>
                        <tr class="row_a">
                            <td class="first"><?php echo $lang->line('FIELD_TYPE'); ?> <span class="important">*</span></td>
                            <td><select name="id_type" size="1">
                                <option value="1"<?php echo (((isset($formdata['edit']['id_type']) ? $formdata['edit']['id_type'] : $container->id_type) == Document_model::TYPE_DYNAMIC) ? ' selected="selected"' : ''); ?>><?php echo $lang->line('FIELD_TYPE_DYNAMIC'); ?></option>
                                <option value="2"<?php echo (((isset($formdata['edit']['id_type']) ? $formdata['edit']['id_type'] : $container->id_type) == Document_model::TYPE_STATIC) ? ' selected="selected"' : ''); ?>><?php echo $lang->line('FIELD_TYPE_STATIC'); ?></option>
				</select></td>
                        </tr>
                        <tr class="row_b">
                        	<td class="first"><?php echo $lang->line('FIELD_PRIORITY'); ?></td>
                            <td><input type="text" name="priority" value="<?php echo (isset($formdata['edit']['priority']) ? $formdata['edit']['priority'] : $container->priority); ?>" maxlength="3" class="small_text integer" />&nbsp; <button class="value_add ui-state-default ui-corner-all" onclick="return false;"><span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></button> <button class="value_subtract ui-state-default ui-corner-all" onclick="return false;"><span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></button></td>
                        </tr>
                        <tr class="row_a">
                        	<td class="first"><?php echo $lang->line('FIELD_ALIAS'); ?> <span class="important">*</span></td>
                            <td><input type="text" name="alias" value="<?php echo (isset($formdata['edit']['alias']) ? $formdata['edit']['alias'] : $container->alias); ?>" maxlength="50" class="small_text" /></td>
                        </tr>
                        <tr class="row_b">
                        	<td class="first"><?php echo $lang->line('FIELD_TITLE'); ?> <span class="important">*</span></td>
                            <td><input type="text" name="title" value="<?php echo (isset($formdata['edit']['title']) ? $formdata['edit']['title'] : $container->title); ?>" maxlength="250" class="text" /></td>
                        </tr>
                        <tr class="row_a not_hover">
                        	<td class="first"><?php echo $lang->line('FIELD_BODY'); ?> <span class="important">*</span></td>
                                <td><textarea id="ea_<?php echo ((isset($formdata['edit']['id_type']) ? $formdata['edit']['id_type'] : $container->id_type) == Document_model::TYPE_STATIC) ? 'static' : 'dynamic'; ?>_edit" name="body" cols="70" rows="25" class="rte"><?php echo isset($formdata['edit']['body']) ? $formdata['edit']['body'] : htmlspecialchars($container->body); ?></textarea></td>
                        </tr>
                        <tr class="row_b">
                        	<td class="first"><?php echo $lang->line('FIELD_FREE_ACCESS'); ?></td>
                            <td><input type="checkbox" name="flag_free_access" value="1"<?php echo ((isset($formdata['edit']['flag_free_access']) ? $formdata['edit']['flag_free_access'] : $container->flag_free_access) ? ' checked="checked" ' : ' '); ?>/></td>
                        </tr>
                        <tr class="row_a">
                        	<td class="first"><?php echo $lang->line('FIELD_COMMENTS'); ?></td>
                            <td><textarea name="comments" cols="70" rows="7" class="text field_comments"><?php echo (isset($formdata['edit']['comments']) ? $formdata['edit']['comments'] : $container->comments); ?></textarea></td>
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
			<?php if ($components_privileges->containers->add) { ?>
			<div id="add_container">
	            <form method="post" action="/admin/containers/add_container">
                	<table class="fields_line" cellspacing="0" cellpadding="0">
                    	<tr>
                        	<th><?php echo $lang->line('CONTENT_FORM_PARAMETER'); ?></th>
                            <th><?php echo $lang->line('CONTENT_FORM_VALUE'); ?></th>
                        </tr>
                        <tr class="row_a">
                        	<td class="first"><?php echo $lang->line('FIELD_GROUP'); ?> <span class="important">*</span></td>
                            <td><select name="id_group" size="1">
                                <option value="0"<?php echo ((isset($formdata['id_group']) && $formdata['id_group'] == 0) ? ' selected="selected"' : ''); ?>><?php echo $lang->line('FIELD_VALUE_NO_GROUP'); ?></option>
                            	<?php foreach ($containers_groups->result() as $row) { ?>
                                <?php if (!$row->id) continue; ?>
                                <option value="<?php echo $row->id; ?>"<?php echo ((isset($formdata['id_group']) && $formdata['id_group'] == $row->id) ? ' selected="selected"' : ''); ?>><?php echo $row->title; ?></option>
                                <?php } ?>
							</select></td>
                        </tr>
                        <tr class="row_b">
                        	<td class="first"><?php echo $lang->line('FIELD_DOCUMENT'); ?> <span class="important">*</span></td>
                            <td><select name="id_document" size="1">
                            	<?php foreach ($documents_list as $k => $v) { ?>
                                <option value="<?php echo $v['id']; ?>"<?php echo ((isset($formdata['id_document']) && $formdata['id_document'] == $v['id']) ? ' selected="selected"' : ''); ?>><?php echo $v['title']; ?></option>
                                <?php } ?>
							</select></td>
                        </tr>
                        <tr class="row_a">
                            <td class="first"><?php echo $lang->line('FIELD_TYPE'); ?> <span class="important">*</span></td>
                            <td><select name="id_type" size="1">
                                <option value="1"<?php echo ((isset($formdata['id_type']) && $formdata['id_type'] == Document_model::TYPE_DYNAMIC) ? ' selected="selected"' : ''); ?>><?php echo $lang->line('FIELD_TYPE_DYNAMIC'); ?></option>
                                <option value="2"<?php echo ((isset($formdata['id_type']) && $formdata['id_type'] == Document_model::TYPE_STATIC) ? ' selected="selected"' : ''); ?>><?php echo $lang->line('FIELD_TYPE_STATIC'); ?></option>
				</select></td>
                        </tr>
                        <tr class="row_b">
                        	<td class="first"><?php echo $lang->line('FIELD_PRIORITY'); ?></td>
                            <td><input type="text" name="priority" value="<?php echo (isset($formdata['priority']) ? $formdata['priority'] : '0'); ?>" maxlength="3" class="small_text integer" />&nbsp; <button class="value_add ui-state-default ui-corner-all" onclick="return false;"><span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></button> <button class="value_subtract ui-state-default ui-corner-all" onclick="return false;"><span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></button></td>
                        </tr>
                        <tr class="row_a">
                        	<td class="first"><?php echo $lang->line('FIELD_ALIAS'); ?> <span class="important">*</span></td>
                            <td><input type="text" name="alias" value="<?php echo (isset($formdata['alias']) ? $formdata['alias'] : ''); ?>" maxlength="50" class="small_text" /></td>
                        </tr>
                        <tr class="row_b">
                        	<td class="first"><?php echo $lang->line('FIELD_TITLE'); ?> <span class="important">*</span></td>
                            <td><input type="text" name="title" value="<?php echo (isset($formdata['title']) ? $formdata['title'] : ''); ?>" maxlength="250" class="text" /></td>
                        </tr>
                        <tr class="row_a not_hover">
                            <td class="first"><?php echo $lang->line('FIELD_BODY'); ?> <span class="important">*</span></td>
                            <td><textarea id="ea_<?php echo (isset($formdata['id_type']) && $formdata['id_type'] == Document_model::TYPE_STATIC) ? 'static' : 'dynamic'; ?>_add" name="body" cols="70" rows="25" class="rte"><?php echo isset($formdata['body']) ? $formdata['body'] : ''; ?></textarea></td>
                        </tr>
                        <tr class="row_b">
                        	<td class="first"><?php echo $lang->line('FIELD_FREE_ACCESS'); ?></td>
                            <td><input type="checkbox" name="flag_free_access" value="1"<?php echo ((isset($formdata['flag_free_access']) && !$formdata['flag_free_access']) ? ' ' : ' checked="checked" '); ?>/></td>
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
