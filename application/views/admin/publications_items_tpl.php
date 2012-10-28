<!-- page body -->
<div id="page_body">
    <div class="tabs">
        <ul>
            <li><a href="#items"><?php echo $lang->line('CONTENT_ITEMS'); ?></a></li>
            <?php if ($modules_privileges->publications->edit && (isset($formdata['edit']) || @$item->id)) { ?>
                <li><a href="#edit_item"><?php echo $lang->line('ACT_EDIT'); ?></a></li>
            <?php } ?>
            <?php if ($modules_privileges->publications->add) { ?>
                <li><a href="#add_item"><?php echo $lang->line('ACT_ADD'); ?></a></li>
            <?php } ?>
        </ul>
        <div id="items">
            <?php echo $pages_line; ?>
            <div class="search_line"><form method="post" action="/admin/publications_items/set_keywords">
                <select name="group" size="1">
                    <?php foreach ($publications_groups->result() as $row) { ?>
                    <option value="<?php echo $row->id; ?>"<?php echo ($row->id == $keywords['group'] ? ' selected="selected"' : ''); ?>><?php echo ($row->id ? $row->title : $lang->line('FIELD_VALUE_ALL')); ?></option>
                    <?php } ?>
                </select>
                <input type="text" name="text" value="<?php echo $keywords['text']; ?>" />
                <button type="submit" title="<?php echo $lang->line('ACT_SEARCH'); ?>">&nbsp;</button>
                <a href="/admin/publications_items/clear_keywords" class="clear_link" title="<?php echo $lang->line('ACT_RESET'); ?>"></a>
            </form></div>
            <table class="fields_line" cellspacing="0" cellpadding="0">
                <tr>
                    <th><?php echo $lang->line('CONTENT_POSITION_NUMBER'); ?></th>
                    <th><?php echo $lang->line('FIELD_DATETIME_PUBLICATION'); ?></th>
                    <th><?php echo $lang->line('FIELD_GROUP'); ?></th>
                    <th><?php echo $lang->line('FIELD_TITLE'); ?></th>
                    <th><?php echo $lang->line('FIELD_USER_AUTHOR'); ?></th>
                    <?php if ($modules_privileges->publications->delete) { ?>
                    <th><?php echo $lang->line('ACT_DELETE'); ?></th>
                    <?php } ?>
                </tr>
                <?php $row_class = 'row_a'; foreach ($publications_items->result() as $row) { ?>
                    <tr class="<?php echo $row_class; ?>">
                        <td><?php echo /*++*/$items_position_number--; ?></td>
                        <td><?php echo $row->time_publication; ?></td>
                        <td nowrap="nowrap"><?php echo $row->group_title; ?></td>
                        <td class="row_title" nowrap="nowrap"><?php if ($modules_privileges->publications->edit) { ?><a href="/admin/publications_items/edit_item/<?php echo $row->id; ?>#edit_item" class="edit_row_link" title="<?php echo $lang->line('ACT_EDIT'); ?>"><?php } else { ?><a><?php } ?><?php echo $row->title_page; ?></a></td>
                        <td><?php echo ($row->auth_login !== NULL ? $row->auth_login . ' [' . $row->first_name . ' ' . $row->last_name . ']' : '-'); ?></td>
                        <?php if ($modules_privileges->publications->delete) { ?>
                        <td><a href="/admin/publications_items/delete_item/<?php echo $row->id; ?>" class="delete_row_link" title="<?php echo $lang->line('ACT_DELETE'); ?>"></a></td>
                        <?php } ?>
                    </tr>
                <?php $row_class = ($row_class == 'row_a') ? 'row_b' : 'row_a'; } ?>
            </table>
            <?php echo $pages_line; ?>
            <br clear="all" />
        </div>
        <?php if ($modules_privileges->publications->edit && (isset($formdata['edit']) || @$item->id)) { ?>
        <div id="edit_item">
            <form method="post" action="/admin/publications_items/edit_item/<?php echo $item->id; ?>#edit_item">
                <table class="fields_line" cellspacing="0" cellpadding="0">
                    <tr>
                        <th><?php echo $lang->line('CONTENT_FORM_PARAMETER'); ?></th>
                        <th><?php echo $lang->line('CONTENT_FORM_VALUE'); ?></th>
                    </tr>
                    <tr class="row_a">
                        <td class="first"><?php echo $lang->line('FIELD_GROUP'); ?> <span class="important">*</span></td>
                        <td><select name="id_group" size="1">
                            <?php foreach ($publications_groups->result() as $row) { ?>
                            <option value="<?php echo $row->id; ?>"<?php echo (((isset($formdata['edit']['id_group']) ? $formdata['edit']['id_group'] : $item->id_group) == $row->id) ? ' selected="selected"' : ''); ?>><?php echo $row->title; ?></option>
                            <?php } ?>
                            </select></td>
                    </tr>
                    <tr class="row_b">
                        <td class="first"><?php echo $lang->line('FIELD_URL'); ?> <span class="dependent">*</span></td>
                        <td><input type="text" name="url" value="<?php echo (isset($formdata['edit']['url']) ? $formdata['edit']['url'] : $item->url); ?>" maxlength="250" class="text" /></td>
                    </tr>
                    <tr class="row_a">
                        <td class="first"><?php echo $lang->line('FIELD_META_KEYWORDS'); ?></td>
                        <td><input type="text" name="keywords" value="<?php echo (isset($formdata['edit']['keywords']) ? $formdata['edit']['keywords'] : $item->meta_keywords); ?>" maxlength="250" class="text" /></td>
                    </tr>
                    <tr class="row_b">
                        <td class="first"><?php echo $lang->line('FIELD_META_DESCRIPTION'); ?></td>
                        <td><input type="text" name="description" value="<?php echo (isset($formdata['edit']['description']) ? $formdata['edit']['description'] : $item->meta_description); ?>" maxlength="250" class="text" /></td>
                    </tr>
                    <tr class="row_a">
                        <td class="first"><?php echo $lang->line('FIELD_TITLE_BROWSER'); ?></td>
                        <td><input type="text" name="title_browser" value="<?php echo (isset($formdata['edit']['title_browser']) ? $formdata['edit']['title_browser'] : $item->title_browser); ?>" maxlength="250" class="text" /></td>
                    </tr>
                    <tr class="row_b">
                        <td class="first"><?php echo $lang->line('FIELD_TITLE_PAGE'); ?></td>
                        <td><input type="text" name="title_page" value="<?php echo (isset($formdata['edit']['title_page']) ? $formdata['edit']['title_page'] : $item->title_page); ?>" maxlength="250" class="text" /></td>
                    </tr>
                    <tr class="row_a">
                        <td class="first"><?php echo $lang->line('FIELD_TITLE_MENU'); ?></td>
                        <td><input type="text" name="title_menu" value="<?php echo (isset($formdata['edit']['title_menu']) ? $formdata['edit']['title_menu'] : $item->title_menu); ?>" maxlength="250" class="text" /></td>
                    </tr>
                    <tr class="row_b not_hover">
                        <td class="first"><?php echo $lang->line('FIELD_ANNOUNCE'); ?></td>
                        <td><textarea name="announce" cols="70" rows="4" <?php if ($global_flag_use_rte) : ?>id="ckeditor-basic"<?php endif ?> class="rte"><?php echo (isset($formdata['edit']['announce']) ? $formdata['edit']['announce'] : $item->announce); ?></textarea></td>
                    </tr>
                    <tr class="row_a not_hover">
                        <td class="first"><?php echo $lang->line('FIELD_BODY'); ?></td>
                        <td><textarea name="body" cols="70" rows="7" <?php if ($global_flag_use_rte) : ?>id="ckeditor-full"<?php endif ?> class="rte"><?php echo (isset($formdata['edit']['body']) ? $formdata['edit']['body'] : $item->body); ?></textarea></td>
                    </tr>
                    <tr class="row_b">
                        <td class="first"><?php echo $lang->line('FIELD_DATETIME_PUBLICATION'); ?> <span class="important">*</span></td>
                        <td><input type="text" name="time_publication" value="<?php echo (isset($formdata['edit']['time_publication']) ? $formdata['edit']['time_publication'] : $item->time_publication); ?>" maxlength="19" class="small_text datetime_field" style="width: 150px;" /></td>
                    </tr>
                    <tr class="row_a">
                        <td class="first"><?php echo $lang->line('FIELD_DATETIME_EXPIRATION'); ?></td>
                        <td><input type="text" name="time_expiration" value="<?php echo (isset($formdata['edit']['time_expiration']) ? $formdata['edit']['time_expiration'] : $item->time_expiration); ?>" maxlength="19" class="small_text datetime_field" style="width: 150px;" /></td>
                    </tr>
                    <tr class="row_b">
                        <td class="first"><?php echo $lang->line('FIELD_PUBLICATION'); ?></td>
                        <td><input type="checkbox" name="flag_publication" value="1"<?php echo ((isset($formdata['edit']['flag_publication']) ? $formdata['edit']['flag_publication'] : $item->flag_publication) ? ' checked="checked" ' : ' '); ?>/></td>
                    </tr>
                    <tr class="row_a">
                        <td class="first"><?php echo $lang->line('FIELD_DATETIME_MODIFICATION'); ?></td>
                        <td><?php echo $item->time_modification; ?></td>
                    </tr>
                    <tr class="row_b">
                        <td class="first"><?php echo $lang->line('FIELD_USER_AUTHOR'); ?></td>
                        <td><?php if ($item->auth_login !== NULL) { ?><a href="/admin/users/edit_user/<?php echo $item->id_user_author; ?>#edit_user" title="<?php echo $lang->line('ACT_SHOW'); ?>" class="show_row_link" target="_blank"><?php echo $item->auth_login . ' [' . $item->first_name . ' ' . $item->last_name . ']'; ?></a><?php } else echo '-'; ?></td>
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
        <div id="add_item">
            <form method="post" action="/admin/publications_items/add_item">
                <table class="fields_line" cellspacing="0" cellpadding="0">
                    <tr>
                        <th><?php echo $lang->line('CONTENT_FORM_PARAMETER'); ?></th>
                        <th><?php echo $lang->line('CONTENT_FORM_VALUE'); ?></th>
                    </tr>
                    <tr class="row_a">
                        <td class="first"><?php echo $lang->line('FIELD_GROUP'); ?> <span class="important">*</span></td>
                        <td><select name="id_group" size="1">
                            <?php foreach ($publications_groups->result() as $row) { ?>
                            <option value="<?php echo $row->id; ?>"<?php echo (((isset($formdata['document_type']) && $formdata['document_type'] == $row->id) || $row->flag_is_default) ? ' selected="selected"' : ''); ?>><?php echo $row->title; ?></option>
                            <?php } ?>
                        </select></td>
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
                        <td class="first"><?php echo $lang->line('FIELD_ANNOUNCE'); ?></td>
                        <td><textarea name="announce" cols="70" rows="4" <?php if ($global_flag_use_rte) : ?>id="ckeditor2-basic"<?php endif ?> class="rte"><?php echo (isset($formdata['announce']) ? $formdata['announce'] : ''); ?></textarea></td>
                    </tr>
                    <tr class="row_a not_hover">
                        <td class="first"><?php echo $lang->line('FIELD_BODY'); ?></td>
                        <td><textarea name="body" cols="70" rows="7" <?php if ($global_flag_use_rte) : ?>id="ckeditor2-full"<?php endif ?> class="rte"><?php echo (isset($formdata['body']) ? $formdata['body'] : ''); ?></textarea></td>
                    </tr>
                    <tr class="row_b">
                        <td class="first"><?php echo $lang->line('FIELD_DATETIME_PUBLICATION'); ?></td>
                        <td><input type="text" name="time_publication" value="<?php echo (isset($formdata['time_publication']) ? $formdata['time_publication'] : ''); ?>" maxlength="19" class="small_text datetime_field" style="width: 150px;" /></td>
                    </tr>
                    <tr class="row_a">
                        <td class="first"><?php echo $lang->line('FIELD_DATETIME_EXPIRATION'); ?></td>
                        <td><input type="text" name="time_expiration" value="<?php echo (isset($formdata['time_expiration']) ? $formdata['time_expiration'] : ''); ?>" maxlength="19" class="small_text datetime_field" style="width: 150px;" /></td>
                    </tr>
                    <tr class="row_b">
                        <td class="first"><?php echo $lang->line('FIELD_PUBLICATION'); ?></td>
                        <td><input type="checkbox" name="flag_publication" value="1"<?php echo ((isset($formdata['flag_publication']) && $formdata['flag_publication']) ? ' checked="checked" ' : ' '); ?>/></td>
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
