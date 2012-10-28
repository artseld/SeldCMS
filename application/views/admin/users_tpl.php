<!-- page body -->
<div id="page_body">
<div class="tabs">
<ul>
    <li><a href="#users"><?php echo $lang->line('CONTENT_USERS') ?></a></li>
    <?php if ($components_privileges->users->edit && (isset($formdata['edit']) || @$user->id)) : ?>
    <li><a href="#edit_user"><?php echo $lang->line('ACT_EDIT') ?></a></li>
    <?php endif ?>
    <?php if ($components_privileges->users->add) : ?>
    <li><a href="#add_user"><?php echo $lang->line('ACT_ADD') ?></a></li>
    <?php endif ?>
</ul>
<div id="users">
    <?php echo $pages_line ?>
    <div class="search_line"><form method="post" action="/admin/users/set_keywords">
        <select name="group" size="1">
            <option value="all"<?php echo ('all' == $keywords['group'] ? ' selected="selected"' : ''); ?>><?php echo $lang->line('FIELD_VALUE_ALL'); ?></option>
            <?php foreach ($users_groups->result() as $row) { ?>
            <option value="<?php echo $row->id; ?>"<?php echo ($row->id == $keywords['group'] ? ' selected="selected"' : ''); ?>><?php echo ($row->id ? $row->title : $lang->line('FIELD_ROOT_STATUS')); ?></option>
            <?php } ?>
        </select>
        <input type="text" name="text" value="<?php echo $keywords['text']; ?>" />
        <button type="submit" title="<?php echo $lang->line('ACT_SEARCH'); ?>">&nbsp;</button>
        <a href="/admin/users/clear_keywords" class="clear_link" title="<?php echo $lang->line('ACT_RESET'); ?>"></a>
    </form></div>
    <table class="fields_line" cellspacing="0" cellpadding="0">
        <tr>
            <th><?php echo $lang->line('CONTENT_POSITION_NUMBER'); ?></th>
            <th><?php echo $lang->line('FIELD_GROUP'); ?></th>
            <th><?php echo $lang->line('FIELD_USER_NAME'); ?></th>
            <th><?php echo $lang->line('FIELD_USER_LOGIN'); ?></th>
            <?php if ($components_privileges->users->delete) : ?>
            <th><?php echo $lang->line('ACT_DELETE'); ?></th>
            <?php endif ?>
        </tr>
        <?php $row_class = 'row_a'; foreach ($users->result() as $row) { ?>
        <tr class="<?php echo $row_class; ?>">
            <td><?php echo ++$users_position_number; ?></td>
            <td><?php echo ($row->id_group ? $row->title_group : $lang->line('FIELD_ROOT_STATUS')); ?></td>
            <td class="row_title"><?php echo '<img src="/img/admin/' . ($row->flag_access ? 'check.png" alt="+" title="' . $lang->line('FIELD_STATUS_ACTIVE') . '"' : 'lock.png" alt="-" title="' . $lang->line('FIELD_STATUS_LOCKED') . '"') . ' width="13" height="13" style="border: 0; margin-right: 8px;" />'; if ($components_privileges->users->edit) { ?><a href="/admin/users/edit_user/<?php echo $row->id; ?>#edit_user" class="edit_row_link" title="<?php echo $lang->line('ACT_EDIT'); ?>"><?php } else { ?><a><?php } ?><?php echo $row->first_name . ' ' . $row->last_name; ?></a></td>
            <td><?php echo $row->auth_login; ?></td>
            <?php if ($components_privileges->users->delete) : ?>
            <td><a href="/admin/users/delete_user/<?php echo $row->id; ?>" class="delete_row_link" title="<?php echo $lang->line('ACT_DELETE'); ?>"></a></td>
            <?php endif ?>
        </tr>
        <?php $row_class = ($row_class == 'row_a') ? 'row_b' : 'row_a'; } ?>
    </table>
    <?php echo $pages_line; ?>
</div>
<?php if ($components_privileges->users->edit && (isset($formdata['edit']) || @$user->id)) { ?>
<div id="edit_user">
    <form method="post" action="/admin/users/edit_user/<?php echo $user->id; ?>#edit_user" enctype="multipart/form-data">
        <table class="fields_line" cellspacing="0" cellpadding="0">
            <tr>
                <th><?php echo $lang->line('CONTENT_FORM_PARAMETER'); ?></th>
                <th><?php echo $lang->line('CONTENT_FORM_VALUE'); ?></th>
            </tr>
            <tr class="row_a">
                <td class="first"><?php echo $lang->line('FIELD_GROUP'); ?> <span class="important">*</span></td>
                <td><select name="id_group" size="1">
                    <?php foreach ($users_groups->result() as $row) { ?>
                    <option value="<?php echo $row->id; ?>"<?php echo (((isset($formdata['edit']['id_group']) ? $formdata['edit']['id_group'] : $user->id_group) == $row->id) ? ' selected="selected"' : ''); ?>><?php echo ($row->id ? $row->title : $lang->line('FIELD_ROOT_STATUS')); ?></option>
                    <?php } ?>
                </select></td>
            </tr>
            <tr class="row_b">
                <td class="first"><?php echo $lang->line('FIELD_USER_LOGIN'); ?> <span class="important">*</span></td>
                <td><input type="text" name="auth_login" value="<?php echo (isset($formdata['edit']['auth_login']) ? $formdata['edit']['auth_login'] : $user->auth_login); ?>" maxlength="25" class="small_text" /></td>
            </tr>
            <tr class="row_a">
                <td class="first"><?php echo $lang->line('FIELD_USER_PASSWORD'); ?> [>6]</td>
                <td><input type="password" name="auth_password" value="" maxlength="25" class="small_text" /></td>
            </tr>
            <tr class="row_b">
                <td class="first"><?php echo $lang->line('FIELD_USER_PASSWORD_VERIFY'); ?> [>6]</td>
                <td><input type="password" name="auth_passwordv" value="" maxlength="25" class="small_text" /></td>
            </tr>
            <tr class="row_a">
                <td class="first"><?php echo $lang->line('FIELD_USER_FIRST_NAME'); ?> <span class="important">*</span></td>
                <td><input type="text" name="first_name" value="<?php echo (isset($formdata['edit']['first_name']) ? $formdata['edit']['first_name'] : $user->first_name); ?>" maxlength="50" class="text" /></td>
            </tr>
            <tr class="row_b">
                <td class="first"><?php echo $lang->line('FIELD_USER_MIDDLE_NAME'); ?></td>
                <td><input type="text" name="middle_name" value="<?php echo (isset($formdata['edit']['middle_name']) ? $formdata['edit']['middle_name'] : $user->middle_name); ?>" maxlength="50" class="text" /></td>
            </tr>
            <tr class="row_a">
                <td class="first"><?php echo $lang->line('FIELD_USER_LAST_NAME'); ?></td>
                <td><input type="text" name="last_name" value="<?php echo (isset($formdata['edit']['last_name']) ? $formdata['edit']['last_name'] : $user->last_name); ?>" maxlength="50" class="text" /></td>
            </tr>
            <tr class="row_b">
                <td class="first"><?php echo $lang->line('FIELD_USER_AVATAR'); ?></td>
                <td><?php if ($user->avatar_filename) : ?>
                    <img src="/uploads/users/<?php echo $user->avatar_filename ?>" alt="<?php echo $lang->line('FIELD_USER_AVATAR'); ?>" style="margin-bottom: 7px;" /><br />
                    <?php endif ?>
                    <input type="file" name="avatar" class="text" /><br />
                    <span class="description">.jpg, .png, .gif; 100 kb; 110x110 px</span>
                </td>
            </tr>
            <tr class="row_b">
                <td class="first"><?php echo $lang->line('FIELD_USER_BIRTHDAY'); ?></td>
                <td><input type="text" name="birthday" value="<?php echo (isset($formdata['edit']['birthday']) ? $formdata['edit']['birthday'] : $user->birthday); ?>" maxlength="10" class="small_text date_field" /></td>
            </tr>
            <tr class="row_a">
                <td class="first"><?php echo $lang->line('FIELD_USER_GENDER'); ?> <span class="important">*</span></td>
                <td><input type="radio" name="gender" value="male" <?php echo ((isset($formdata['edit']['gender']) && $formdata['edit']['gender'] == 'male') || $user->gender == 'male' ? 'checked="checked" ' : ''); ?>/> <?php echo $lang->line('FIELD_GENDER_MALE'); ?>
                    &nbsp; <input type="radio" name="gender" value="female" <?php echo ((isset($formdata['edit']['gender']) && $formdata['edit']['gender'] == 'female') || $user->gender == 'female' ? 'checked="checked" ' : ''); ?>/> <?php echo $lang->line('FIELD_GENDER_FEMALE'); ?>
                    &nbsp; <input type="radio" name="gender" value="couple" <?php echo ((isset($formdata['edit']['gender']) && $formdata['edit']['gender'] == 'couple') || $user->gender == 'couple' ? 'checked="checked" ' : ''); ?>/> <?php echo $lang->line('FIELD_GENDER_COUPLE'); ?>
                    &nbsp; <input type="radio" name="gender" value="female" <?php echo ((isset($formdata['edit']['gender']) && $formdata['edit']['gender'] == 'team') || $user->gender == 'team' ? 'checked="checked" ' : ''); ?>/> <?php echo $lang->line('FIELD_GENDER_TEAM'); ?></td>
            </tr>
            <tr class="row_b">
                <td class="first"><?php echo $lang->line('FIELD_USER_COUNTRY'); ?></td>
                <td>
                    <?php if ($countries->num_rows() == 0) { ?>
                    <input type="text" name="country" value="<?php echo (isset($formdata['edit']['country']) ? $formdata['edit']['country'] : $user->country); ?>" maxlength="100" class="text" />
                    <?php } else { ?>
                    <select name="country" size="1">
                        <option value=""<?php echo (((isset($formdata['edit']['country']) ? $formdata['edit']['country'] : $user->country) == '') ? ' selected="selected"' : ''); ?>>&nbsp;</option>
                        <?php foreach ($countries->result() as $row) { ?>
                        <option value="<?php echo $row->alias; ?>"<?php echo (((isset($formdata['edit']['country']) ? $formdata['edit']['country'] : $user->country) == $row->alias) ? ' selected="selected"' : ''); ?>><?php echo $row->title; ?></option>
                        <?php } ?>
                    </select>
                    <?php } ?>
                </td>
            </tr>
            <tr class="row_a">
                <td class="first"><?php echo $lang->line('FIELD_USER_CITY'); ?></td>
                <td><input type="text" name="city" value="<?php echo (isset($formdata['edit']['city']) ? $formdata['edit']['city'] : $user->city); ?>" maxlength="50" class="text" /></td>
            </tr>
            <tr class="row_b">
                <td class="first"><?php echo $lang->line('FIELD_USER_ADDRESS'); ?></td>
                <td><input type="text" name="address" value="<?php echo (isset($formdata['edit']['address']) ? $formdata['edit']['address'] : $user->address); ?>" maxlength="250" class="text" /></td>
            </tr>
            <tr class="row_a">
                <td class="first"><?php echo $lang->line('FIELD_USER_EMAIL'); ?> <span class="important">*</span></td>
                <td><input type="text" name="email" value="<?php echo (isset($formdata['edit']['email']) ? $formdata['edit']['email'] : $user->email); ?>" maxlength="50" class="text" /></td>
            </tr>
            <?php $row_class = 'row_b'; foreach ($user_contacts_edit->result() as $row) { ?>
            <tr class="<?php echo $row_class; ?>">
                <td class="first"><?php echo $row->title; ?></td>
                <td><input type="text" name="user_contacts_value_id_<?php echo $row->id; ?>" value="<?php echo (isset($formdata['user_contacts_value_id_' . $row->id]) ? $formdata['user_contacts_value_id_' . $row->id] : $row->value); ?>" maxlength="250" class="text" />
                    <input type="checkbox" name="user_contacts_visibility_id_<?php echo $row->id; ?>" title="<?php echo $lang->line('FIELD_VISIBILITY_CHECKBOX'); ?>" value="1"<?php echo ((isset($formdata['user_contacts_visibility_id_' . $row->id]) ? $formdata['user_contacts_visibility_id_' . $row->id] : $row->visibility) ? ' checked="checked" ' : ' '); ?>/></td>
            </tr>
            <?php $row_class = ($row_class == 'row_a') ? 'row_b' : 'row_a'; } $row_class_next = ($row_class == 'row_a') ? 'row_b' : 'row_a'; ?>
            <tr class="<?php echo $row_class; ?>">
                <td class="first"><?php echo $lang->line('FIELD_USER_ADDITIONAL'); ?></td>
                <td><textarea name="additional" cols="70" rows="7" class="text"><?php echo (isset($formdata['edit']['additional']) ? $formdata['edit']['additional'] : $user->additional); ?></textarea></td>
            </tr>
            <tr class="<?php echo $row_class_next; ?>">
                <td class="first"><?php echo $lang->line('FIELD_DATETIME_REGISTRATION'); ?> <span class="important">*</span></td>
                <td><input type="text" name="datetime_registration" value="<?php echo (isset($formdata['edit']['time_registration']) ? $formdata['edit']['time_registration'] : $user->time_registration); ?>" maxlength="19" class="small_text datetime_field" style="width: 150px;" /></td>
            </tr>
            <tr class="<?php echo $row_class; ?>">
                <td class="first"><?php echo $lang->line('FIELD_USER_VISIBILITY'); ?> <span class="important">*</span></td>
                <td><input type="radio" name="visibility_type" value="nobody" <?php echo ((isset($formdata['edit']['visibility_type']) && $formdata['edit']['visibility_type'] == 'nobody') || $user->visibility_type == 'nobody' ? 'checked="checked" ' : ''); ?>/> <?php echo $lang->line('FIELD_VISIBILITY_NOBODY'); ?>
                    &nbsp; <input type="radio" name="visibility_type" value="friends" <?php echo ((isset($formdata['edit']['visibility_type']) && $formdata['edit']['visibility_type'] == 'friends') || $user->visibility_type == 'friends' ? 'checked="checked" ' : ''); ?>/> <?php echo $lang->line('FIELD_VISIBILITY_FRIENDS'); ?>
                    &nbsp; <input type="radio" name="visibility_type" value="members" <?php echo ((isset($formdata['edit']['visibility_type']) && $formdata['edit']['visibility_type'] == 'members') || $user->visibility_type == 'members' ? 'checked="checked" ' : ''); ?>/> <?php echo $lang->line('FIELD_VISIBILITY_MEMBERS'); ?>
                    &nbsp; <input type="radio" name="visibility_type" value="all" <?php echo ((isset($formdata['edit']['visibility_type']) && $formdata['edit']['visibility_type'] == 'all') || $user->visibility_type == 'all' ? 'checked="checked" ' : ''); ?>/> <?php echo $lang->line('FIELD_VISIBILITY_ALL'); ?></td>
            </tr>
            <tr class="<?php echo $row_class_next; ?>">
                <td class="first"><?php echo $lang->line('FIELD_USER_RATING'); ?></td>
                <td><input type="text" name="rating" value="<?php echo (isset($formdata['edit']['rating']) ? $formdata['edit']['rating'] : $user->rating); ?>" maxlength="12" class="small_text rating" />&nbsp; <button class="value_add ui-state-default ui-corner-all" onclick="return false;"><span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></button> <button class="value_subtract ui-state-default ui-corner-all" onclick="return false;"><span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></button></td>
            </tr>
            <tr class="<?php echo $row_class; ?>">
                <td class="first"><?php echo $lang->line('FIELD_USER_VERIFICATION'); ?></td>
                <td><input type="checkbox" name="flag_verification" value="1"<?php echo ((isset($formdata['edit']['flag_verification']) ? $formdata['edit']['flag_verification'] : $user->flag_verification) ? ' checked="checked" ' : ' '); ?>/></td>
            </tr>
            <tr class="<?php echo $row_class_next; ?>">
                <td class="first"><?php echo $lang->line('FIELD_USER_ACCESS'); ?></td>
                <td><input type="checkbox" name="flag_access" value="1"<?php echo ((isset($formdata['edit']['flag_access']) ? $formdata['edit']['flag_access'] : $user->flag_access) ? ' checked="checked" ' : ' '); ?>/></td>
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
<?php if ($components_privileges->users->add) { ?>
<div id="add_user">
    <form method="post" action="/admin/users/add_user" enctype="multipart/form-data">
        <table class="fields_line" cellspacing="0" cellpadding="0">
            <tr>
                <th><?php echo $lang->line('CONTENT_FORM_PARAMETER'); ?></th>
                <th><?php echo $lang->line('CONTENT_FORM_VALUE'); ?></th>
            </tr>
            <tr class="row_a">
                <td class="first"><?php echo $lang->line('FIELD_GROUP'); ?> <span class="important">*</span></td>
                <td><select name="id_group" size="1">
                    <?php foreach ($users_groups->result() as $row) { ?>
                    <option value="<?php echo $row->id; ?>"<?php echo (((isset($formdata['id_group']) ? $formdata['id_group'] : '') == $row->id) ? ' selected="selected"' : ''); ?>><?php echo ($row->id ? $row->title : $lang->line('FIELD_ROOT_STATUS')); ?></option>
                    <?php } ?>
                </select></td>
            </tr>
            <tr class="row_b">
                <td class="first"><?php echo $lang->line('FIELD_USER_LOGIN'); ?> <span class="important">*</span></td>
                <td><input type="text" name="auth_login" value="<?php echo (isset($formdata['auth_login']) ? $formdata['auth_login'] : ''); ?>" maxlength="50" class="small_text" /></td>
            </tr>
            <tr class="row_a">
                <td class="first"><?php echo $lang->line('FIELD_USER_PASSWORD'); ?> [>6] <span class="important">*</span></td>
                <td><input type="password" name="auth_password" value="" maxlength="50" class="small_text" /></td>
            </tr>
            <tr class="row_b">
                <td class="first"><?php echo $lang->line('FIELD_USER_PASSWORD_VERIFY'); ?> [>6] <span class="important">*</span></td>
                <td><input type="password" name="auth_passwordv" value="" maxlength="50" class="small_text" /></td>
            </tr>
            <tr class="row_a">
                <td class="first"><?php echo $lang->line('FIELD_USER_FIRST_NAME'); ?> <span class="important">*</span></td>
                <td><input type="text" name="first_name" value="<?php echo (isset($formdata['first_name']) ? $formdata['first_name'] : ''); ?>" maxlength="50" class="text" /></td>
            </tr>
            <tr class="row_b">
                <td class="first"><?php echo $lang->line('FIELD_USER_MIDDLE_NAME'); ?></td>
                <td><input type="text" name="middle_name" value="<?php echo (isset($formdata['middle_name']) ? $formdata['middle_name'] : ''); ?>" maxlength="50" class="text" /></td>
            </tr>
            <tr class="row_a">
                <td class="first"><?php echo $lang->line('FIELD_USER_LAST_NAME'); ?></td>
                <td><input type="text" name="last_name" value="<?php echo (isset($formdata['last_name']) ? $formdata['last_name'] : ''); ?>" maxlength="50" class="text" /></td>
            </tr>
            <tr class="row_b">
                <td class="first"><?php echo $lang->line('FIELD_USER_AVATAR'); ?></td>
                <td>
                    <input type="file" name="avatar" class="text" /><br />
                    <span class="description">.jpg, .png, .gif; 100 kb; 110x110 px</span>
                </td>
            </tr>
            <tr class="row_b">
                <td class="first"><?php echo $lang->line('FIELD_USER_BIRTHDAY'); ?></td>
                <td><input type="text" name="birthday" value="<?php echo (isset($formdata['birthday']) ? $formdata['birthday'] : ''); ?>" maxlength="10" class="small_text date_field" /></td>
            </tr>
            <tr class="row_a">
                <td class="first"><?php echo $lang->line('FIELD_USER_GENDER'); ?> <span class="important">*</span></td>
                <td><input type="radio" name="gender" value="male" <?php echo ((isset($formdata['gender']) && $formdata['gender'] == 'male') ? 'checked="checked" ' : ''); ?>/> <?php echo $lang->line('FIELD_GENDER_MALE'); ?>
                    &nbsp; <input type="radio" name="gender" value="female" <?php echo ((isset($formdata['gender']) && $formdata['gender'] == 'female') ? 'checked="checked" ' : ''); ?>/> <?php echo $lang->line('FIELD_GENDER_FEMALE'); ?>
                    &nbsp; <input type="radio" name="gender" value="couple" <?php echo ((isset($formdata['gender']) && $formdata['gender'] == 'couple') ? 'checked="checked" ' : ''); ?>/> <?php echo $lang->line('FIELD_GENDER_COUPLE'); ?>
                    &nbsp; <input type="radio" name="gender" value="team" <?php echo ((isset($formdata['gender']) && $formdata['gender'] == 'team') ? 'checked="checked" ' : ''); ?>/> <?php echo $lang->line('FIELD_GENDER_TEAM'); ?></td>
            </tr>
            <tr class="row_b">
                <td class="first"><?php echo $lang->line('FIELD_USER_COUNTRY'); ?></td>
                <td>
                    <?php if ($countries->num_rows() == 0) { ?>
                    <input type="text" name="country" value="<?php echo (isset($formdata['country']) ? $formdata['country'] : ''); ?>" maxlength="100" class="text" />
                    <?php } else { ?>
                    <select name="country" size="1">
                        <option value=""<?php echo ((isset($formdata['country']) ? $formdata['country'] : '') ? ' selected="selected"' : ''); ?>>&nbsp;</option>
                        <?php foreach ($countries->result() as $row) { ?>
                        <option value="<?php echo $row->alias; ?>"<?php echo (((isset($formdata['country']) ? $formdata['country'] : '') == $row->alias) ? ' selected="selected"' : ''); ?>><?php echo $row->title; ?></option>
                        <?php } ?>
                    </select>
                    <?php } ?>
                </td>
            </tr>
            <tr class="row_a">
                <td class="first"><?php echo $lang->line('FIELD_USER_CITY'); ?></td>
                <td><input type="text" name="city" value="<?php echo (isset($formdata['city']) ? $formdata['city'] : ''); ?>" maxlength="50" class="text" /></td>
            </tr>
            <tr class="row_b">
                <td class="first"><?php echo $lang->line('FIELD_USER_ADDRESS'); ?></td>
                <td><input type="text" name="address" value="<?php echo (isset($formdata['address']) ? $formdata['address'] : ''); ?>" maxlength="250" class="text" /></td>
            </tr>
            <tr class="row_a">
                <td class="first"><?php echo $lang->line('FIELD_USER_EMAIL'); ?> <span class="important">*</span></td>
                <td><input type="text" name="email" value="<?php echo (isset($formdata['email']) ? $formdata['email'] : ''); ?>" maxlength="50" class="text" /></td>
            </tr>
            <?php $row_class = 'row_b'; foreach ($user_contacts->result() as $row) { ?>
            <tr class="<?php echo $row_class; ?>">
                <td class="first"><?php echo $row->title; ?></td>
                <td><input type="text" name="user_contacts_value_id_<?php echo $row->id; ?>" value="<?php echo (isset($formdata['user_contacts_value_id_' . $row->id]) ? $formdata['user_contacts_value_id_' . $row->id] : ''); ?>" maxlength="250" class="text" />
                    <input type="checkbox" name="user_contacts_visibility_id_<?php echo $row->id; ?>" title="<?php echo $lang->line('FIELD_VISIBILITY_CHECKBOX'); ?>" value="1"<?php echo ((isset($formdata['user_contacts_visibility_id_' . $row->id]) ? $formdata['user_contacts_visibility_id_' . $row->id] : FALSE) ? ' checked="checked" ' : ' '); ?>/></td>
            </tr>
            <?php $row_class = ($row_class == 'row_a') ? 'row_b' : 'row_a'; } $row_class_next = ($row_class == 'row_a') ? 'row_b' : 'row_a'; ?>
            <tr class="<?php echo $row_class; ?>">
                <td class="first"><?php echo $lang->line('FIELD_USER_ADDITIONAL'); ?></td>
                <td><textarea name="additional" cols="70" rows="7" class="text"><?php echo (isset($formdata['additional']) ? $formdata['additional'] : ''); ?></textarea></td>
            </tr>
            <tr class="<?php echo $row_class_next; ?>">
                <td class="first"><?php echo $lang->line('FIELD_DATETIME_REGISTRATION'); ?></td>
                <td><input type="text" name="datetime_registration" value="<?php echo (isset($formdata['time_registration']) ? $formdata['time_registration'] : ''); ?>" maxlength="19" class="small_text datetime_field" style="width: 150px;" /></td>
            </tr>
            <tr class="<?php echo $row_class; ?>">
                <td class="first"><?php echo $lang->line('FIELD_USER_VISIBILITY'); ?> <span class="important">*</span></td>
                <td><input type="radio" name="visibility_type" value="nobody" <?php echo ((isset($formdata['visibility_type']) && $formdata['visibility_type'] == 'nobody') ? 'checked="checked" ' : ''); ?>/> <?php echo $lang->line('FIELD_VISIBILITY_NOBODY'); ?>
                    &nbsp; <input type="radio" name="visibility_type" value="friends" <?php echo ((isset($formdata['visibility_type']) && $formdata['visibility_type'] == 'friends') ? 'checked="checked" ' : ''); ?>/> <?php echo $lang->line('FIELD_VISIBILITY_FRIENDS'); ?>
                    &nbsp; <input type="radio" name="visibility_type" value="members" <?php echo ((isset($formdata['visibility_type']) && $formdata['visibility_type'] == 'members') ? 'checked="checked" ' : ''); ?>/> <?php echo $lang->line('FIELD_VISIBILITY_MEMBERS'); ?>
                    &nbsp; <input type="radio" name="visibility_type" value="all" <?php echo ((isset($formdata['visibility_type']) && $formdata['visibility_type'] == 'all') ? 'checked="checked" ' : ''); ?>/> <?php echo $lang->line('FIELD_VISIBILITY_ALL'); ?></td>
            </tr>
            <tr class="<?php echo $row_class_next; ?>">
                <td class="first"><?php echo $lang->line('FIELD_USER_RATING'); ?></td>
                <td><input type="text" name="rating" value="<?php echo (isset($formdata['rating']) ? $formdata['rating'] : '0'); ?>" maxlength="12" class="small_text rating" />&nbsp; <button class="value_add ui-state-default ui-corner-all" onclick="return false;"><span class="ui-icon ui-icon-triangle-1-n">&nbsp;</span></button> <button class="value_subtract ui-state-default ui-corner-all" onclick="return false;"><span class="ui-icon ui-icon-triangle-1-s">&nbsp;</span></button></td>
            </tr>
            <tr class="<?php echo $row_class; ?>">
                <td class="first"><?php echo $lang->line('FIELD_USER_VERIFICATION'); ?></td>
                <td><input type="checkbox" name="flag_verification" value="1"<?php echo ((isset($formdata['flag_verification']) ? $formdata['flag_verification'] : FALSE) ? ' checked="checked" ' : ' '); ?>/></td>
            </tr>
            <tr class="<?php echo $row_class_next; ?>">
                <td class="first"><?php echo $lang->line('FIELD_USER_ACCESS'); ?></td>
                <td><input type="checkbox" name="flag_access" value="1"<?php echo ((isset($formdata['flag_access']) ? $formdata['flag_access'] : FALSE) ? ' checked="checked" ' : ' '); ?>/></td>
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
