<!-- HEADER -->
<div id="header">
    <div id="header_user">
        <select id="resources_selector" size="1">
        <?php $resource_prefix = ''; foreach ($resources_list->result() as $row) { ?>
            <option value="<?php echo $row->id; ?>"<?php echo (($row->id == $id_resource_current) ? ' selected="selected"' : ''); ?>><?php echo $row->title . (($row->flag_is_default) ? ' [' . $lang->line('FIELD_DEFAULT') . ']' : ''); ?></option>
        <?php $resource_prefix = ($row->id == $id_resource_current && !$row->flag_is_default) ? $row->url : $resource_prefix; } ?>
        </select>
        <a href="<?php echo $this->config->item('base_url') . $resource_prefix; ?>" target="_blank" id="resource_link"><?php echo $lang->line('CONTENT_RESOURCE_LINK'); ?></a>
        <div id="userdata">
            <?php echo $userdata->first_name . ' ' . $userdata->last_name; ?> <span>&raquo;</span> <?php echo $lang->line('MESSAGE_SC_LOGGED_AS'); ?> <span id="privileges"><?php echo $userdata->user_group; ?></span>
        </div>
    </div>
    <div id="header_nav">
        <ul class="dropdown">
            <?php if ($components_privileges->main->view) { ?><li><a href="/admin/main"><?php echo $lang->line('NAV_TITLE_MAIN'); ?></a></li><?php } ?>
            <?php if ($components_privileges->structure->view) { ?><li><a href="/admin/structure"><?php echo $lang->line('NAV_TITLE_STRUCTURE'); ?></a></li><?php } ?>
            <?php if ($components_privileges->modules->view) { ?><li><a href="/admin/modules" class="nav_group"><?php echo $lang->line('NAV_TITLE_MODULES'); ?></a>
                <ul>
                <?php $id_module = 'zzz'; foreach ($mdata->result() as $row) if ($modules_privileges->{$row->alias}->view) { ?>
                <?php if ($row->id != $id_module) { ?>
                <?php if ($id_module != 'zzz') { ?>
                </ul>
            </li><?php } ?>
            <?php $id_module = $row->id; ?><li><a href="/admin/<?php echo $row->alias; ?>"><?php echo $row->title; ?><span><?php echo $row->comments; ?></span></a>
                <ul>
                    <?php } ?>
                        <?php if (!is_null($row->calias)) { ?><li><a href="/admin/<?php echo $row->calias; ?>"><?php echo $row->ctitle; ?><span><?php echo $row->ccomments; ?></span></a></li><?php } else { ?><li class="hidden">&nbsp;</li><?php } ?>
                            <?php } ?>
                            <?php if ($id_module != 'zzz') { ?>
                            </ul>
                        </li>
                    <?php } ?>
                    <li><a href="/admin/modules"><?php echo $lang->line('NAV_TITLE_MODULES_MANAGER'); ?><span><?php echo $lang->line('NAV_DESC_MODULES_MANAGER'); ?></span></a></li>
                </ul>
            </li><?php } ?>
            <?php if ($components_privileges->files->view) { ?><li><a href="/admin/files"><?php echo $lang->line('NAV_TITLE_FILES'); ?></a></li><?php } ?>
            <?php if ($components_privileges->containers->view || $components_privileges->containers_groups->view || $components_privileges->templates->view || $components_privileges->templates_groups->view) { ?><li><a href="/admin/containers" class="nav_group"><?php echo $lang->line('NAV_TITLE_ELEMENTS'); ?></a>
                <ul>
                    <?php if ($components_privileges->containers->view) { ?><li><a href="/admin/containers"><?php echo $lang->line('NAV_TITLE_CONTAINERS'); ?><span><?php echo $lang->line('NAV_DESC_CONTAINERS'); ?></span></a></li><?php } ?>
                    <?php if ($components_privileges->containers_groups->view) { ?><li><a href="/admin/containers_groups"><?php echo $lang->line('NAV_TITLE_CONTAINERS_GROUPS'); ?><span><?php echo $lang->line('NAV_DESC_CONTAINERS_GROUPS'); ?></span></a></li><?php } ?>
                    <?php if ($components_privileges->templates->view) { ?><li><a href="/admin/templates"><?php echo $lang->line('NAV_TITLE_TEMPLATES'); ?><span><?php echo $lang->line('NAV_DESC_TEMPLATES'); ?></span></a></li><?php } ?>
                    <?php if ($components_privileges->templates_groups->view) { ?><li><a href="/admin/templates_groups"><?php echo $lang->line('NAV_TITLE_TEMPLATES_GROUPS'); ?><span><?php echo $lang->line('NAV_DESC_TEMPLATES_GROUPS'); ?></span></a></li><?php } ?>
                </ul>
            </li><?php } ?>
            <?php if ($components_privileges->users->view || $components_privileges->users_groups->view || $components_privileges->users_privileges->view || $components_privileges->mailing->view || $components_privileges->profile->view) { ?><li><a href="/admin/users" class="nav_group"><?php echo $lang->line('NAV_TITLE_USERS'); ?></a>
                <ul>
                    <?php if ($components_privileges->users->view) { ?><li><a href="/admin/users"><?php echo $lang->line('NAV_TITLE_USERS_MANAGER'); ?><span><?php echo $lang->line('NAV_DESC_USERS_MANAGER'); ?></span></a></li><?php } ?>
                    <?php if ($components_privileges->users_groups->view) { ?><li><a href="/admin/users_groups"><?php echo $lang->line('NAV_TITLE_USERS_GROUPS'); ?><span><?php echo $lang->line('NAV_DESC_USERS_GROUPS'); ?></span></a></li><?php } ?>
                    <?php if ($components_privileges->users_privileges->view) { ?><li><a href="/admin/users_privileges"><?php echo $lang->line('NAV_TITLE_USERS_PRIVILEGES'); ?><span><?php echo $lang->line('NAV_DESC_USERS_PRIVILEGES'); ?></span></a></li><?php } ?>
                    <?php if ($components_privileges->mailing->view) { ?><li><a href="/admin/mailing"><?php echo $lang->line('NAV_TITLE_MAILING'); ?><span><?php echo $lang->line('NAV_DESC_MAILING'); ?></span></a></li><?php } ?>
                    <?php if ($components_privileges->profile->view) { ?><li><a href="/admin/profile"><?php echo $lang->line('NAV_TITLE_PROFILE'); ?><span><?php echo $lang->line('NAV_DESC_PROFILE'); ?></span></a></li><?php } ?>
                </ul>
            </li><?php } ?>
            <?php if ($components_privileges->settings->view || $components_privileges->resources->view || $components_privileges->logs->view) { ?><li><a href="/admin/settings" class="nav_group"><?php echo $lang->line('NAV_TITLE_SYSTEM'); ?></a>
                <ul>
                    <?php if ($components_privileges->settings->view) { ?><li><a href="/admin/settings"><?php echo $lang->line('NAV_TITLE_SETTINGS'); ?><span><?php echo $lang->line('NAV_DESC_SETTINGS'); ?></span></a></li><?php } ?>
                    <?php if ($components_privileges->resources->view) { ?><li><a href="/admin/resources"><?php echo $lang->line('NAV_TITLE_RESOURCES'); ?><span><?php echo $lang->line('NAV_DESC_RESOURCES'); ?></span></a></li><?php } ?>
                    <?php if ($components_privileges->logs->view) { ?><li><a href="/admin/logs"><?php echo $lang->line('NAV_TITLE_LOGS'); ?><span><?php echo $lang->line('NAV_DESC_LOGS'); ?></span></a></li><?php } ?>
                </ul>
            </li><?php } ?>
            <li><a href="/admin/auth/quit" id="exit_link"><?php echo $lang->line('ACT_EXIT'); ?></a></li>
        </ul>
    </div>
    <div id="header_logo">
        <a href="http://www.artseld.com/" title="<?php echo $lang->line('APP_COPYRIGHT'); ?>" target="_blank"><img src="/img/admin/logo.png" width="40" height="40" alt="<?php echo $lang->line('APP_COPYRIGHT'); ?>" /></a>
        <p><?php echo $lang->line('TITLE_APP') . ' ' . $lang->line('APP_VERSION_DETAILS'); ?></p>
        <span><?php echo $lang->line('TITLE_DESC'); ?></span>
    </div>
</div><!-- END OF HEADER -->

<!-- HIDDEN AREA -->
<div id="exit_dialog" class="dialog_window" title="<?php echo $lang->line('CONTENT_EXIT_TITLE'); ?>">
    <p><?php echo $lang->line('CONTENT_EXIT_BODY'); ?></p>
</div>
<div id="delete_dialog" class="dialog_window" title="<?php echo $lang->line('CONTENT_DELETE_TITLE'); ?>">
    <p><?php echo $lang->line('CONTENT_DELETE_BODY'); ?></p>
</div><!-- END OF HIDDEN AREA -->
