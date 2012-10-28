    <!-- page body -->
    <div id="page_body">
        <div class="tabs">
			<ul>
				<li><a href="#groups"><?php echo $lang->line('CONTENT_GROUPS'); ?></a></li>
                <?php if ($components_privileges->users_privileges->edit && (isset($formdata['edit']) || @$group->id)) { ?>
				<li><a href="#edit_privileges"><?php echo $lang->line('ACT_EDIT'); ?></a></li>
                <?php } ?>
			</ul>
			<div id="groups">
            	<?php echo $pages_line; ?>
				<table class="fields_line" cellspacing="0" cellpadding="0">
					<tr>
                    	<th><?php echo $lang->line('CONTENT_POSITION_NUMBER'); ?></th>
                        <th><?php echo $lang->line('FIELD_TITLE'); ?></th>
                        <th><?php echo $lang->line('FIELD_COMMENTS'); ?></th>
                        <?php if ($components_privileges->users_privileges->delete) { ?>
                        <?php } ?>
					</tr>
                    <?php $row_class = 'row_a'; foreach ($users_groups->result() as $row) { ?>
					<tr class="<?php echo $row_class; ?>">
						<td><?php echo ++$groups_position_number; ?></td>
						<td class="row_title"><?php if ($components_privileges->users_privileges->edit) { ?><a href="/admin/users_privileges/edit_privileges/<?php echo $row->id; ?>#edit_privileges" class="edit_row_link" title="<?php echo $lang->line('ACT_EDIT'); ?>"><?php } else { ?><a><?php } ?><?php echo $row->title; ?></a></td>
						<td><?php echo $row->comments; ?></td>
					</tr>
                    <?php $row_class = ($row_class == 'row_a') ? 'row_b' : 'row_a'; } ?>
                </table>
                <?php echo $pages_line; ?>
            </div>
            <?php if ($components_privileges->users_privileges->edit && (isset($formdata['edit']) || @$group->id)) { ?>
			<div id="edit_privileges">
	            <form method="post" action="/admin/users_privileges/edit_privileges/<?php echo $group->id; ?>#edit_privileges">
                	<table class="fields_line" cellspacing="0" cellpadding="0">
                    	<tr class="row_b overlined">
                        	<td class="first" style="width: 50%;"><?php echo $lang->line('FIELD_FRONTSIDE_ACCESS'); ?></td>
                            <td><input type="checkbox" name="flag_frontside_access" value="1" <?php echo ((isset($formdata['edit']['flag_frontside_access']) ? $formdata['edit']['flag_frontside_access'] : $group->flag_frontside_access) ? 'checked="checked" ' : ''); ?>/></td>
                        </tr>
                    	<tr>
                        	<th><?php echo $lang->line('CONTENT_COMPONENTS'); ?></th>
                            <th><?php echo $lang->line('CONTENT_MODULES'); ?></th>
                        </tr>
                        <tr class="row_a not_hover">
                        	<td>&mdash;</td>
                            <td>
                            <?php if ($fm_privileges->num_rows() == 0) echo '&mdash;'; else foreach ($fm_privileges->result() as $row) { echo '<p><strong>' . $row->title . '</strong><br />'; ?>
                            <input type="checkbox" name="fm_<?php echo $row->alias; ?>_flag_access" value="1" <?php echo ((isset($formdata['edit']['fm_' . $row->alias . '_flag_access']) ? $formdata['edit']['fm_' . $row->alias . '_flag_access'] : $row->flag_access) ? 'checked="checked" ' : ''); ?>/>
                            <?php echo '</p>'; } ?></td>
                        </tr>
                    </table>
                    <table class="fields_line" cellspacing="0" cellpadding="0">
                    	<tr class="row_b overlined">
                        	<td class="first" style="width: 50%;"><?php echo $lang->line('FIELD_BACKSIDE_ACCESS'); ?></td>
                            <td><input type="checkbox" name="flag_backside_access" value="1" <?php echo ((isset($formdata['edit']['flag_backside_access']) ? $formdata['edit']['flag_backside_access'] : $group->flag_backside_access) ? 'checked="checked" ' : ''); ?>/></td>
                        </tr>
                    	<tr>
                        	<th><?php echo $lang->line('CONTENT_COMPONENTS'); ?></th>
                            <th><?php echo $lang->line('CONTENT_MODULES'); ?></th>
                        </tr>
                        <tr class="row_a not_hover">
                        	<td>
                            <?php if ($bc_privileges->num_rows() == 0) echo '&mdash;'; else foreach ($bc_privileges->result() as $row) { echo '<p><strong>' . $lang->line('NAV_TITLE_' . strtoupper($row->alias)) . '</strong><br />'; ?>
                            <input type="checkbox" name="bc_<?php echo $row->alias; ?>_flag_view_access" value="1" <?php echo ((isset($formdata['edit']['bc_' . $row->alias . '_flag_view_access']) ? $formdata['edit']['bc_' . $row->alias . '_flag_view_access'] : $row->flag_view_access) ? 'checked="checked" ' : ''); ?>/> <?php echo $lang->line('ACT_SHOW'); ?>
                            <input type="checkbox" name="bc_<?php echo $row->alias; ?>_flag_edit_access" value="1" <?php echo ((isset($formdata['edit']['bc_' . $row->alias . '_flag_edit_access']) ? $formdata['edit']['bc_' . $row->alias . '_flag_edit_access'] : $row->flag_edit_access) ? 'checked="checked" ' : ''); ?>/> <?php echo $lang->line('ACT_EDIT'); ?>
                            <input type="checkbox" name="bc_<?php echo $row->alias; ?>_flag_add_access" value="1" <?php echo ((isset($formdata['edit']['bc_' . $row->alias . '_flag_add_access']) ? $formdata['edit']['bc_' . $row->alias . '_flag_add_access'] : $row->flag_add_access) ? 'checked="checked" ' : ''); ?>/> <?php echo $lang->line('ACT_ADD'); ?>
                            <input type="checkbox" name="bc_<?php echo $row->alias; ?>_flag_delete_access" value="1" <?php echo ((isset($formdata['edit']['bc_' . $row->alias . '_flag_delete_access']) ? $formdata['edit']['bc_' . $row->alias . '_flag_delete_access'] : $row->flag_delete_access) ? 'checked="checked" ' : ''); ?>/> <?php echo $lang->line('ACT_DELETE'); ?>
                            <?php echo '</p>'; } ?>
                            </td>
                            <td>
                            <?php if ($bm_privileges->num_rows() == 0) echo '&mdash;'; else foreach ($bm_privileges->result() as $row) { echo '<p><strong>' . $row->title . '</strong><br />'; ?>
                            <input type="checkbox" name="bm_<?php echo $row->alias; ?>_flag_view_access" value="1" <?php echo ((isset($formdata['edit']['bm_' . $row->alias . '_flag_view_access']) ? $formdata['edit']['bm_' . $row->alias . '_flag_view_access'] : $row->flag_view_access) ? 'checked="checked" ' : ''); ?>/> <?php echo $lang->line('ACT_SHOW'); ?>
                            <input type="checkbox" name="bm_<?php echo $row->alias; ?>_flag_edit_access" value="1" <?php echo ((isset($formdata['edit']['bm_' . $row->alias . '_flag_edit_access']) ? $formdata['edit']['bm_' . $row->alias . '_flag_edit_access'] : $row->flag_edit_access) ? 'checked="checked" ' : ''); ?>/> <?php echo $lang->line('ACT_EDIT'); ?>
                            <input type="checkbox" name="bm_<?php echo $row->alias; ?>_flag_add_access" value="1" <?php echo ((isset($formdata['edit']['bm_' . $row->alias . '_flag_add_access']) ? $formdata['edit']['bm_' . $row->alias . '_flag_add_access'] : $row->flag_add_access) ? 'checked="checked" ' : ''); ?>/> <?php echo $lang->line('ACT_ADD'); ?>
                            <input type="checkbox" name="bm_<?php echo $row->alias; ?>_flag_delete_access" value="1" <?php echo ((isset($formdata['edit']['bm_' . $row->alias . '_flag_delete_access']) ? $formdata['edit']['bm_' . $row->alias . '_flag_delete_access'] : $row->flag_delete_access) ? 'checked="checked" ' : ''); ?>/> <?php echo $lang->line('ACT_DELETE'); ?>
                            <?php echo '</p>'; } ?>
                            </td>
                        </tr>
                    </table>
                    <div class="actions_line">
                    	<input type="submit" value="<?php echo $lang->line('ACT_SAVE'); ?>" />
	        	        <input type="reset" value="<?php echo $lang->line('ACT_RESET'); ?>" />
                        <?php if ($formdata['edit']) { ?><input type="button" value="<?php echo $lang->line('ACT_REFRESH'); ?>" /><?php } ?>
					</div>
                <input type="hidden" name="post_enable" value="true" />
            	</form>
			</div>
			<?php } ?>
		</div>
	</div><!-- end of page body -->
