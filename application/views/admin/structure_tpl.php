<!-- page body -->
<div id="page_body">
    <div class="tabs">
        <ul>
            <li><a href="#structure"><?php echo $lang->line('CONTENT_TREE'); ?></a></li>
            <?php if ($components_privileges->structure->edit && (isset($formdata['edit']) || @$document->id)) { ?>
                <li><a href="#edit_document"><?php echo $lang->line('ACT_EDIT'); ?></a></li>
            <?php } ?>
            <?php if ($components_privileges->structure->add) { ?>
                <li><a href="#add_document"><?php echo $lang->line('ACT_ADD'); ?></a></li>
            <?php } ?>
        </ul>
        <div id="structure">
            <div id="tree">
                <?php echo $tree; ?>
            </div>
            <br clear="all" />
            <p class="comments"><?php echo $lang->line('CONTENT_COMMENTS_STRUCTURE'); ?></p>
        </div>
        <?php if ($components_privileges->structure->edit && (isset($formdata['edit']) || @$document->id)) { ?>
            <div id="edit_document">
                <form method="post" action="/admin/structure/edit_document/<?php echo $document->id; ?>#edit_document">
                    <table class="fields_line" cellspacing="0" cellpadding="0">
                    	<tr>
                            <th><?php echo $lang->line('CONTENT_FORM_PARAMETER'); ?></th>
                            <th><?php echo $lang->line('CONTENT_FORM_VALUE'); ?></th>
                        </tr>
                        <tr class="row_a">
                            <td class="first"><?php echo $lang->line('FIELD_ID'); ?></td>
                            <td><div class="text_line"><?php echo $document->id; ?></div></td>
                        </tr>
                        <tr class="row_b">
                            <td class="first"><?php echo $lang->line('FIELD_DOCUMENT_TYPE'); ?> <span class="important">*</span></td>
                            <td><select name="document_type" size="1">
                                <option value="0"<?php echo (((isset($formdata['edit']['document_type']) ? $formdata['edit']['document_type'] : $document->id_module) == 0) ? ' selected="selected"' : ''); ?>><?php echo $lang->line('FIELD_VALUE_SIMPLE_DOCUMENT'); ?></option>
                                <?php foreach ($modules->result() as $row) { ?>
                               	<option value="<?php echo $row->id; ?>"<?php echo (((isset($formdata['edit']['document_type']) ? $formdata['edit']['document_type'] : $document->id_module) == $row->id) ? ' selected="selected"' : ''); ?>><?php echo $row->title; ?></option>
                                <?php } ?>
                            </select></td>
                        </tr>
                        <tr class="row_a">
                            <td class="first"><?php echo $lang->line('FIELD_PARENT'); ?> <span class="important">*</span></td>
                            <td><select name="id_parent" size="1">
                            	<?php foreach ($parents_list_edit as $k => $v) { ?>
                                <option value="<?php echo $v['id']; ?>"<?php echo (((isset($formdata['edit']['id_parent']) ? $formdata['edit']['id_parent'] : $document->id_parent) == $v['id']) ? ' selected="selected"' : ''); ?>><?php echo $v['title']; ?></option>
                                <?php } ?>
                            </select></td>
                        </tr>
                        <tr class="row_b">
                            <td class="first"><?php echo $lang->line('FIELD_TEMPLATE'); ?> <span class="important">*</span></td>
                            <td><select name="id_template" size="1">
                            	<?php $id_optgroup = 'zzz'; foreach ($templates->result() as $row) { ?>
                                <?php if ($row->id_group != $id_optgroup) { ?>
                                <?php if ($id_optgroup != 'zzz') { ?>
                                </optgroup>
                                <?php } ?>
                                    <?php $id_optgroup = $row->id_group; ?>
                                    <optgroup label="<?php echo ($row->id_group ? $row->title_group : $lang->line('FIELD_VALUE_NO_GROUP')); ?>">
                                    <?php } ?>
                                <option value="<?php echo $row->id_template; ?>"<?php echo (((isset($formdata['edit']['id_template']) ? $formdata['edit']['id_template'] : $document->id_template) == $row->id_template) ? ' selected="selected"' : ''); ?>><?php echo $row->title_template; ?></option>
                                <?php } ?>
                                <?php if ($id_optgroup != 'zzz') { ?>
                                </optgroup>
                                <?php } ?>
                                <option class="hidden">&nbsp;</option>
                            </select></td>
                        </tr>
                        <tr class="row_a">
                            <td class="first"><?php echo $lang->line('FIELD_PRIORITY'); ?></td>
                            <td><input type="text" name="priority" value="<?php echo (isset($formdata['edit']['priority']) ? $formdata['edit']['priority'] : $document->priority); ?>" maxlength="3" class="small_text integer" />&nbsp; <button class="value_add ui-state-default ui-corner-all" onclick="return false;"><span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></button> <button class="value_subtract ui-state-default ui-corner-all" onclick="return false;"><span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></button></td>
                        </tr>
                        <tr class="row_b">
                            <td class="first"><?php echo $lang->line('FIELD_URL'); ?> <span class="dependent">*</span></td>
                            <td><input type="text" name="url" value="<?php echo (isset($formdata['edit']['url']) ? $formdata['edit']['url'] : $document->url); ?>" maxlength="250" class="text" /></td>
                        </tr>
                        <tr class="row_a">
                            <td class="first"><?php echo $lang->line('FIELD_META_KEYWORDS'); ?></td>
                            <td><input type="text" name="keywords" value="<?php echo (isset($formdata['edit']['keywords']) ? $formdata['edit']['keywords'] : $document->meta_keywords); ?>" maxlength="250" class="text" /></td>
                        </tr>
                        <tr class="row_b">
                            <td class="first"><?php echo $lang->line('FIELD_META_DESCRIPTION'); ?></td>
                            <td><input type="text" name="description" value="<?php echo (isset($formdata['edit']['description']) ? $formdata['edit']['description'] : $document->meta_description); ?>" maxlength="250" class="text" /></td>
                        </tr>
                        <tr class="row_a">
                            <td class="first"><?php echo $lang->line('FIELD_TITLE_BROWSER'); ?></td>
                            <td><input type="text" name="title_browser" value="<?php echo (isset($formdata['edit']['title_browser']) ? $formdata['edit']['title_browser'] : $document->title_browser); ?>" maxlength="250" class="text" /></td>
                        </tr>
                        <tr class="row_b">
                            <td class="first"><?php echo $lang->line('FIELD_TITLE_PAGE'); ?></td>
                            <td><input type="text" name="title_page" value="<?php echo (isset($formdata['edit']['title_page']) ? $formdata['edit']['title_page'] : $document->title_page); ?>" maxlength="250" class="text" /></td>
                        </tr>
                        <tr class="row_a">
                            <td class="first"><?php echo $lang->line('FIELD_TITLE_MENU'); ?></td>
                            <td><input type="text" name="title_menu" value="<?php echo (isset($formdata['edit']['title_menu']) ? $formdata['edit']['title_menu'] : $document->title_menu); ?>" maxlength="250" class="text" /></td>
                        </tr>
                        <tr class="row_b not_hover">
                            <td class="first"><?php echo $lang->line('FIELD_BODY'); ?></td>
                            <td><textarea name="body" cols="70" rows="7" <?php if ($global_flag_use_rte && (isset($formdata['edit']['flag_use_rte']) ? $formdata['edit']['flag_use_rte'] : $document->flag_use_rte)) : ?>id="ckeditor-full"<?php endif ?> class="rte"><?php echo (isset($formdata['edit']['body']) ? $formdata['edit']['body'] : $document->body); ?></textarea></td>
                        </tr>
                        <tr class="row_a">
                            <td class="first"><?php echo $lang->line('FIELD_USE_RTE'); ?></td>
                            <td><input type="checkbox" name="flag_use_rte" value="1"<?php echo ((isset($formdata['edit']['flag_use_rte']) ? $formdata['edit']['flag_use_rte'] : $document->flag_use_rte) ? ' checked="checked" ' : ' '); ?>/></td>
                        </tr>
                        <tr class="row_b">
                            <td class="first"><?php echo $lang->line('FIELD_DISPLAY_IN_MENU'); ?></td>
                            <td><input type="checkbox" name="flag_display_in_menu" value="1"<?php echo ((isset($formdata['edit']['flag_display_in_menu']) ? $formdata['edit']['flag_display_in_menu'] : $document->flag_display_in_menu) ? ' checked="checked" ' : ' '); ?>/></td>
                        </tr>
                        <tr class="row_a">
                            <td class="first"><?php echo $lang->line('FIELD_FREE_ACCESS'); ?></td>
                            <td><input type="checkbox" name="flag_free_access" value="1"<?php echo ((isset($formdata['edit']['flag_free_access']) ? $formdata['edit']['flag_free_access'] : $document->flag_free_access) ? ' checked="checked" ' : ' '); ?>/></td>
                        </tr>
                        <tr class="row_b">
                            <td class="first"><?php echo $lang->line('FIELD_CACHING'); ?></td>
                            <td><input type="checkbox" name="flag_caching" value="1"<?php echo ((isset($formdata['edit']['flag_caching']) ? $formdata['edit']['flag_caching'] : $document->flag_caching) ? ' checked="checked" ' : ' '); ?>/></td>
                        </tr>
                        <tr class="row_a">
                            <td class="first"><?php echo $lang->line('FIELD_PUBLICATION'); ?></td>
                            <td><input type="checkbox" name="flag_publication" value="1"<?php echo ((isset($formdata['edit']['flag_publication']) ? $formdata['edit']['flag_publication'] : $document->flag_publication) ? ' checked="checked" ' : ' '); ?>/></td>
                        </tr>
                        <tr class="row_b">
                            <td class="first"><?php echo $lang->line('FIELD_IS_MAINPAGE'); ?></td>
                            <td><input type="checkbox" name="flag_is_mainpage" value="1"<?php echo ((isset($formdata['edit']['flag_is_mainpage']) ? $formdata['edit']['flag_is_mainpage'] : $document->flag_is_mainpage) ? ' checked="checked" ' : ' '); ?>/></td>
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
        <?php if ($components_privileges->structure->add) { ?>
            <div id="add_document">
                <form method="post" action="/admin/structure/add_document">
                    <table class="fields_line" cellspacing="0" cellpadding="0">
                    	<tr>
                            <th><?php echo $lang->line('CONTENT_FORM_PARAMETER'); ?></th>
                            <th><?php echo $lang->line('CONTENT_FORM_VALUE'); ?></th>
                        </tr>
                        <tr class="row_a">
                            <td class="first"><?php echo $lang->line('FIELD_ID'); ?></td>
                            <td><div class="text_line"><?php echo $lang->line('FIELD_SET_AUTO'); ?></div></td>
                        </tr>
                        <tr class="row_b">
                            <td class="first"><?php echo $lang->line('FIELD_DOCUMENT_TYPE'); ?> <span class="important">*</span></td>
                            <td><select name="document_type" size="1">
                                <option value="0"<?php echo ((isset($formdata['document_type']) && $formdata['document_type'] == 0) ? ' selected="selected"' : ''); ?>><?php echo $lang->line('FIELD_VALUE_SIMPLE_DOCUMENT'); ?></option>
                                <?php foreach ($modules->result() as $row) { ?>
                               	<option value="<?php echo $row->id; ?>"<?php echo ((isset($formdata['document_type']) && $formdata['document_type'] == $row->id) ? ' selected="selected"' : ''); ?>><?php echo $row->title; ?></option>
                                <?php } ?>
                            </select></td>
                        </tr>
                        <tr class="row_a">
                            <td class="first"><?php echo $lang->line('FIELD_PARENT'); ?> <span class="important">*</span></td>
                            <td><select name="id_parent" size="1">
                            	<?php foreach ($parents_list as $k => $v) { ?>
                                <option value="<?php echo $v['id']; ?>"<?php echo ((isset($formdata['id_parent']) && $formdata['id_parent'] == $v['id']) ? ' selected="selected"' : ''); ?>><?php echo $v['title']; ?></option>
                                <?php } ?>
                            </select></td>
                        </tr>
                        <tr class="row_b">
                            <td class="first"><?php echo $lang->line('FIELD_TEMPLATE'); ?> <span class="important">*</span></td>
                            <td><select name="id_template" size="1">
                            	<?php $id_optgroup = 'zzz'; foreach ($templates->result() as $row) { ?>
                                <?php if ($row->id_group != $id_optgroup) { ?>
                                <?php if ($id_optgroup != 'zzz') { ?>
                                </optgroup>
                                <?php } ?>
                                    <?php $id_optgroup = $row->id_group; ?>
                            	<optgroup label="<?php echo ($row->id_group ? $row->title_group : $lang->line('FIELD_VALUE_NO_GROUP')); ?>">
                                    <?php } ?>
                                    <option value="<?php echo $row->id_template; ?>"<?php echo ((isset($formdata['id_template']) && $formdata['id_template'] == $row->id_template) ? ' selected="selected"' : ''); ?>><?php echo $row->title_template; ?></option>
                                <?php } ?>
                                <?php if ($id_optgroup != 'zzz') { ?>
                                </optgroup>
                                <?php } ?>
                                <option class="hidden">&nbsp;</option>
                            </select></td>
                        </tr>
                        <tr class="row_a">
                            <td class="first"><?php echo $lang->line('FIELD_PRIORITY'); ?></td>
                            <td><input type="text" name="priority" value="<?php echo (isset($formdata['priority']) ? $formdata['priority'] : '0'); ?>" maxlength="3" class="small_text integer" />&nbsp; <button class="value_add ui-state-default ui-corner-all" onclick="return false;"><span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></button> <button class="value_subtract ui-state-default ui-corner-all" onclick="return false;"><span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></button></td>
                        </tr>
                        <tr class="row_b">
                            <td class="first"><?php echo $lang->line('FIELD_URL'); ?> <span class="dependent">*</span></td>
                            <td><input type="text" name="url" value="<?php echo (isset($formdata['url']) ? $formdata['url'] : ''); ?>" maxlength="250" class="text" /></td>
                        </tr>
                        <tr class="row_a">
                            <td class="first"><?php echo $lang->line('FIELD_META_KEYWORDS'); ?></td>
                            <td><input type="text" name="keywords" value="<?php echo (isset($formdata['keywords']) ? $formdata['keywords'] : ''); ?>" maxlength="250" class="text" /></td>
                        </tr>
                        <tr class="row_b">
                            <td class="first"><?php echo $lang->line('FIELD_META_DESCRIPTION'); ?></td>
                            <td><input type="text" name="description" value="<?php echo (isset($formdata['description']) ? $formdata['description'] : ''); ?>" maxlength="250" class="text" /></td>
                        </tr>
                        <tr class="row_a">
                            <td class="first"><?php echo $lang->line('FIELD_TITLE_BROWSER'); ?></td>
                            <td><input type="text" name="title_browser" value="<?php echo (isset($formdata['title_browser']) ? $formdata['title_browser'] : ''); ?>" maxlength="250" class="text" /></td>
                        </tr>
                        <tr class="row_b">
                            <td class="first"><?php echo $lang->line('FIELD_TITLE_PAGE'); ?></td>
                            <td><input type="text" name="title_page" value="<?php echo (isset($formdata['title_page']) ? $formdata['title_page'] : ''); ?>" maxlength="250" class="text" /></td>
                        </tr>
                        <tr class="row_a">
                            <td class="first"><?php echo $lang->line('FIELD_TITLE_MENU'); ?></td>
                            <td><input type="text" name="title_menu" value="<?php echo (isset($formdata['title_menu']) ? $formdata['title_menu'] : ''); ?>" maxlength="250" class="text" /></td>
                        </tr>
                        <tr class="row_b not_hover">
                            <td class="first"><?php echo $lang->line('FIELD_BODY'); ?></td>
                            <td><textarea name="body" cols="70" rows="7" <?php if ($global_flag_use_rte && (!isset($formdata['flag_use_rte']) || $formdata['flag_use_rte'])) : ?>id="ckeditor2-full"<?php endif ?> class="rte"><?php echo (isset($formdata['body']) ? $formdata['body'] : ''); ?></textarea></td>
                        </tr>
                        <tr class="row_a">
                            <td class="first"><?php echo $lang->line('FIELD_USE_RTE'); ?></td>
                            <td><input type="checkbox" name="flag_use_rte" value="1"<?php echo ((isset($formdata['flag_use_rte']) && !$formdata['flag_use_rte']) ? ' ' : ' checked="checked" '); ?>/></td>
                        </tr>
                        <tr class="row_b">
                            <td class="first"><?php echo $lang->line('FIELD_DISPLAY_IN_MENU'); ?></td>
                            <td><input type="checkbox" name="flag_display_in_menu" value="1"<?php echo ((isset($formdata['flag_display_in_menu']) && !$formdata['flag_display_in_menu']) ? ' ' : ' checked="checked" '); ?>/></td>
                        </tr>
                        <tr class="row_a">
                            <td class="first"><?php echo $lang->line('FIELD_FREE_ACCESS'); ?></td>
                            <td><input type="checkbox" name="flag_free_access" value="1"<?php echo ((isset($formdata['flag_free_access']) && !$formdata['flag_free_access']) ? ' ' : ' checked="checked" '); ?>/></td>
                        </tr>
                        <tr class="row_b">
                            <td class="first"><?php echo $lang->line('FIELD_CACHING'); ?></td>
                            <td><input type="checkbox" name="flag_caching" value="1"<?php echo ((isset($formdata['flag_caching']) && $formdata['flag_caching']) ? ' checked="checked" ' : ' '); ?>/></td>
                        </tr>
                        <tr class="row_a">
                            <td class="first"><?php echo $lang->line('FIELD_PUBLICATION'); ?></td>
                            <td><input type="checkbox" name="flag_publication" value="1"<?php echo ((isset($formdata['flag_publication']) && $formdata['flag_publication']) ? ' checked="checked" ' : ' '); ?>/></td>
                        </tr>
                        <tr class="row_b">
                            <td class="first"><?php echo $lang->line('FIELD_IS_MAINPAGE'); ?></td>
                            <td><input type="checkbox" name="flag_is_mainpage" value="1"<?php echo ((isset($formdata['flag_is_mainpage']) && $formdata['flag_is_mainpage']) ? ' checked="checked" ' : ' '); ?>/></td>
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
