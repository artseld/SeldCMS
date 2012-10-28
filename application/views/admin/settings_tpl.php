<!-- page body -->
<div id="page_body">
    <div class="tabs">
        <ul>
            <li><a href="#resource_settings"><?php echo $lang->line('CONTENT_SETTINGS_RESOURCE'); ?></a></li>
            <li><a href="#global_settings"><?php echo $lang->line('CONTENT_SETTINGS_GLOBAL'); ?></a></li>
        </ul>
        <div id="resource_settings">
            <form method="post" action="/admin/settings/edit_resource">
                <table class="fields_line" cellspacing="0" cellpadding="0">
                    <tr>
                        <th><?php echo $lang->line('CONTENT_FORM_PARAMETER'); ?></th>
                        <th><?php echo $lang->line('CONTENT_FORM_VALUE'); ?></th>
                    </tr>
                    <tr class="row_a">
                        <td class="first"><?php echo $lang->line('FIELD_META_KEYWORDS'); ?></td>
                        <td><input type="text" name="meta_keywords" value="<?php echo (isset($formdata['meta_keywords']) ? $formdata['meta_keywords'] : $resource_settings->meta_keywords); ?>" class="text" /></td>
                    </tr>
                    <tr class="row_b">
                        <td class="first"><?php echo $lang->line('FIELD_META_DESCRIPTION'); ?></td>
                        <td><input type="text" name="meta_description" value="<?php echo (isset($formdata['meta_description']) ? $formdata['meta_description'] : $resource_settings->meta_description); ?>" class="text" /></td>
                    </tr>
                    <tr class="row_a">
                        <td class="first"><?php echo $lang->line('FIELD_META_ADDITIONAL'); ?></td>
                        <td><textarea name="meta_additional" cols="70" rows="7" class="text"><?php echo (isset($formdata['meta_additional']) ? $formdata['meta_additional'] : $resource_settings->meta_additional); ?></textarea></td>
                    </tr>
                    <tr class="row_b">
                        <td class="first"><?php echo $lang->line('FIELD_TITLE_BROWSER'); ?></td>
                        <td><input type="text" name="title_browser" value="<?php echo (isset($formdata['title_browser']) ? $formdata['title_browser'] : $resource_settings->title_browser); ?>" maxlength="250" class="text" /></td>
                    </tr>
                    <tr class="row_a">
                        <td class="first"><?php echo $lang->line('FIELD_TITLE_PAGE'); ?></td>
                        <td><input type="text" name="title_page" value="<?php echo (isset($formdata['title_page']) ? $formdata['title_page'] : $resource_settings->title_page); ?>" maxlength="250" class="text" /></td>
                    </tr>
                    <tr class="row_b">
                        <td class="first"><?php echo $lang->line('FIELD_TITLE_MENU'); ?></td>
                        <td><input type="text" name="title_menu" value="<?php echo (isset($formdata['title_menu']) ? $formdata['title_menu'] : $resource_settings->title_menu); ?>" maxlength="250" class="text" /></td>
                    </tr>
                    <tr class="row_a">
                        <td class="first"><?php echo $lang->line('FIELD_URL_ERROR_404'); ?> <span class="important">*</span></td>
                        <td><input type="text" name="url_error_404" value="<?php echo (isset($formdata['url_error_404']) ? $formdata['url_error_404'] : $resource_settings->url_error_404); ?>" maxlength="250" class="text" /></td>
                    </tr>
                    <tr class="row_b">
                        <td class="first"><?php echo $lang->line('FIELD_URL_IPBLOCKED'); ?> <span class="important">*</span></td>
                        <td><input type="text" name="url_ipblocked" value="<?php echo (isset($formdata['url_ipblocked']) ? $formdata['url_ipblocked'] : $resource_settings->url_ipblocked); ?>" maxlength="250" class="text" /></td>
                    </tr>
                </table>
                <?php if ($components_privileges->settings->edit) { ?>
                <div class="actions_line">
                    <input type="submit" value="<?php echo $lang->line('ACT_SAVE'); ?>" />
                    <input type="reset" value="<?php echo $lang->line('ACT_RESET'); ?>" />
                    <?php if ($formdata) { ?><input type="button" value="<?php echo $lang->line('ACT_REFRESH'); ?>" /><?php } ?>
                </div>
                <?php } ?>
            </form>
        </div>
        <div id="global_settings">
            <form method="post" action="/admin/settings/edit_global">
                <table class="fields_line" cellspacing="0" cellpadding="0">
                    <tr>
                        <th><?php echo $lang->line('CONTENT_FORM_PARAMETER'); ?></th>
                        <th><?php echo $lang->line('CONTENT_FORM_VALUE'); ?></th>
                    </tr>
                    <tr class="row_a">
                        <td class="first"><?php echo $lang->line('FIELD_RESOURCE') . ' [' . $lang->line('FIELD_DEFAULT') . ']'; ?> <span class="important">*</span></td>
                        <td><select name="id_resource_default" size="1">
                            <?php foreach ($resources->result() as $row) { ?>
                            <option value="<?php echo $row->id; ?>"<?php echo (($row->id == (isset($formdata['id_resource_default']) ? $formdata['id_resource_default'] : $global_settings->id_resource_default)) ? ' selected="selected"' : ''); ?>><?php echo $row->title; ?></option>
                            <?php } ?>
                        </select></td>
                    </tr>
                    <tr class="row_b">
                        <td class="first"><?php echo $lang->line('FIELD_USERS_GROUP') . ' [' . $lang->line('FIELD_DEFAULT') . ']'; ?> <span class="important">*</span></td>
                        <td><select name="id_users_group_default" size="1">
                            <?php foreach ($users_groups->result() as $row) { ?>
                            <option value="<?php echo $row->id; ?>"<?php echo (($row->id == (isset($formdata['id_users_group_default']) ? $formdata['id_users_group_default'] : $global_settings->id_users_group_default)) ? ' selected="selected"' : ''); ?>><?php echo (($row->id == 0) ? $lang->line('FIELD_ROOT_STATUS') : $row->title); ?></option>
                            <?php } ?>
                        </select></td>
                    </tr>
                    <tr class="row_a">
                        <td class="first"><?php echo $lang->line('FIELD_GLOBAL_COUNTRY_SELECTOR'); ?> <span class="important">*</span></td>
                        <td><select name="flag_countries_selector" size="1">
                            <option value="0"<?php echo ((isset($formdata['flag_countries_selector']) ? !$formdata['flag_countries_selector'] : !$global_settings->flag_countries_selector) ? ' selected="selected"' : ''); ?>><?php echo $lang->line('ACT_NO'); ?></option>
                            <option value="1"<?php echo ((isset($formdata['flag_countries_selector']) ? $formdata['flag_countries_selector'] : $global_settings->flag_countries_selector) ? ' selected="selected"' : ''); ?>><?php echo $lang->line('ACT_YES'); ?></option>
                        </select></td>
                    </tr>
                    <tr class="row_b">
                        <td class="first"><?php echo $lang->line('FIELD_GLOBAL_COUNTRY_LANGUAGE'); ?> <span class="important">*</span></td>
                        <td><select name="countries_selector_alias" size="1">
                            <?php foreach ($countries_groups->result() as $row) { ?>
                            <option value="<?php echo $row->alias; ?>"<?php echo (($row->alias == (isset($formdata['countries_selector_alias']) ? $formdata['countries_selector_alias'] : $global_settings->countries_selector_alias)) ? ' selected="selected"' : ''); ?>><?php echo $lang->line('FIELD_LANGUAGE_' . strtoupper($row->alias)); ?></option>
                            <?php } ?>
                        </select></td>
                    </tr>
                    <tr class="row_a">
                        <td class="first"><?php echo $lang->line('FIELD_USE_RTE'); ?> <span class="important">*</span></td>
                        <td><select name="flag_use_rte" size="1">
                            <option value="0"<?php echo ((isset($formdata['flag_use_rte']) ? !$formdata['flag_use_rte'] : !$global_settings->flag_use_rte) ? ' selected="selected"' : ''); ?>><?php echo $lang->line('ACT_NO'); ?></option>
                            <option value="1"<?php echo ((isset($formdata['flag_use_rte']) ? $formdata['flag_use_rte'] : $global_settings->flag_use_rte) ? ' selected="selected"' : ''); ?>><?php echo $lang->line('ACT_YES'); ?></option>
                        </select></td>
                    </tr>
                    <tr class="row_b">
                        <td class="first"><?php echo $lang->line('FIELD_DELIMITER_BREADCRUMBS'); ?></td>
                        <td><input type="text" name="delimiter_breadcrumbs" value="<?php echo (isset($formdata['delimiter_breadcrumbs']) ? $formdata['delimiter_breadcrumbs'] : $global_settings->delimiter_breadcrumbs); ?>" maxlength="25" class="small_text" /></td>
                    </tr>
                    <tr class="row_a">
                        <td class="first"><?php echo $lang->line('FIELD_GLOBAL_CACHING'); ?> <span class="important">*</span></td>
                        <td><select name="flag_caching" size="1">
                            <option value="0"<?php echo ((isset($formdata['flag_caching']) ? !$formdata['flag_caching'] : !$global_settings->flag_caching) ? ' selected="selected"' : ''); ?>><?php echo $lang->line('ACT_NO'); ?></option>
                            <option value="1"<?php echo ((isset($formdata['flag_caching']) ? $formdata['flag_caching'] : $global_settings->flag_caching) ? ' selected="selected"' : ''); ?>><?php echo $lang->line('ACT_YES'); ?></option>
                        </select></td>
                    </tr>
                    <tr class="row_b">
                        <td class="first"><?php echo $lang->line('FIELD_CACHING_DURATION'); ?></td>
                        <td><input type="text" name="caching_duration" value="<?php echo (isset($formdata['caching_duration']) ? $formdata['caching_duration'] : $global_settings->caching_duration); ?>" maxlength="10" class="small_text integer" /></td>
                    </tr>
                    <tr class="row_a">
                        <td class="first"><?php echo $lang->line('FIELD_GLOBAL_LOGGING'); ?> <span class="important">*</span></td>
                        <td><select name="flag_logging" size="1">
                            <option value="0"<?php echo ((isset($formdata['flag_logging']) ? !$formdata['flag_logging'] : !$global_settings->flag_logging) ? ' selected="selected"' : ''); ?>><?php echo $lang->line('ACT_NO'); ?></option>
                            <option value="1"<?php echo ((isset($formdata['flag_logging']) ? $formdata['flag_logging'] : $global_settings->flag_logging) ? ' selected="selected"' : ''); ?>><?php echo $lang->line('ACT_YES'); ?></option>
                        </select></td>
                    </tr>
                    <tr class="row_b">
                        <td class="first"><?php echo $lang->line('FIELD_MAX_FAILED_ATTEMPTS'); ?></td>
                        <td><input type="text" name="max_failed_attempts" value="<?php echo (isset($formdata['max_failed_attempts']) ? $formdata['max_failed_attempts'] : $global_settings->max_failed_attempts); ?>" maxlength="3" class="small_text integer" /></td>
                    </tr>
                    <tr class="row_a">
                        <td class="first"><?php echo $lang->line('FIELD_BLOCKING_DURATION'); ?></td>
                        <td><input type="text" name="blocking_duration" value="<?php echo (isset($formdata['blocking_duration']) ? $formdata['blocking_duration'] : $global_settings->blocking_duration); ?>" maxlength="10" class="small_text integer" /></td>
                    </tr>
                    <tr class="row_b">
                        <td class="first"><?php echo $lang->line('FIELD_CLEAR_INTERVAL'); ?></td>
                        <td><input type="text" name="clear_interval" value="<?php echo (isset($formdata['clear_interval']) ? $formdata['clear_interval'] : $global_settings->clear_interval); ?>" maxlength="10" class="small_text integer" /></td>
                    </tr>
                </table>
                <?php if ($components_privileges->settings->edit) { ?>
                <div class="actions_line">
                    <input type="submit" value="<?php echo $lang->line('ACT_SAVE'); ?>" />
                    <input type="reset" value="<?php echo $lang->line('ACT_RESET'); ?>" />
                    <?php if ($formdata) { ?><input type="button" value="<?php echo $lang->line('ACT_REFRESH'); ?>" /><?php } ?>
                </div>
                <?php } ?>
            </form>
        </div>
    </div>
</div><!-- end of page body -->
