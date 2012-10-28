<!-- page body -->
<div id="page_body">
    <div class="tabs">
        <ul>
            <?php if (!empty($messages->root_message) || !$userdata->id_group) { ?>
                <li id="root_message_link"><a href="#root_message"><?php echo $lang->line('CONTENT_MAIN_ROOT_MESSAGE'); ?></a></li>
            <?php } ?>
            <li><a href="#user_message"><?php echo $lang->line('CONTENT_MAIN_USER_MESSAGE'); ?></a></li>
        </ul>
        <?php if (!empty($messages->root_message) || !$userdata->id_group) { ?>
        <div id="root_message">
            <?php if (!$userdata->id_group) { ?>
            <form method="post" action="/admin/main/edit_root_message">
                <div class="fields_line">
                    <textarea name="root_message" cols="70" rows="7" class="text"><?php echo (isset($formdata['root_message']) ? $formdata['root_message'] : $messages->root_message); ?></textarea>
                </div>
                <?php if ($components_privileges->main->edit) { ?>
                    <div class="actions_line">
                        <input type="submit" value="<?php echo $lang->line('ACT_SAVE'); ?>" />
    	                <input type="reset" value="<?php echo $lang->line('ACT_RESET'); ?>" />
                        <?php if ($formdata) { ?><input type="button" value="<?php echo $lang->line('ACT_REFRESH'); ?>" /><?php } ?>
                    </div>
                <?php } ?>
            </form>
            <?php } else { ?>
            	<div class="inner"><?php echo $messages->root_message; ?></div>
            <?php } ?>
        </div>
        <?php } ?>
        <div id="user_message">
            <form method="post" action="/admin/main/edit_user_message">
                <div class="fields_line">
                    <textarea name="user_message" cols="70" rows="7" class="text"><?php echo (isset($formdata['user_message']) ? $formdata['user_message'] : $messages->private_message); ?></textarea>
                </div>
                <?php if ($components_privileges->main->edit) { ?>
                    <div class="actions_line">
                        <input type="submit" value="<?php echo $lang->line('ACT_SAVE'); ?>" />
    	                <input type="reset" value="<?php echo $lang->line('ACT_RESET'); ?>" />
                        <?php if ($formdata) { ?><input type="button" value="<?php echo $lang->line('ACT_REFRESH'); ?>" /><?php } ?>
                    </div>
                <?php } ?>
            </form>
        </div>
    </div>
    <br clear="all" />
    <div class="tabs">
        <ul>
            <li><a href="#user_shortcuts"><?php echo $lang->line('CONTENT_MAIN_USER_SHORTCUTS'); ?></a></li>
            <?php if ($components_privileges->main->add) { ?>
                <li><a href="#add_user_shortcut"><?php echo $lang->line('ACT_ADD'); ?></a></li>
            <?php } ?>
        </ul>
        <div id="user_shortcuts">
            <ul>
                <?php foreach ($user_shortcuts->result() as $row) { ?>
                    <li><a href="/admin/main/delete_user_shortcut/<?php echo $row->id; ?>" title="<?php echo $lang->line('ACT_DELETE'); ?>" class="delete_shortcut_link"></a><a href="<?php echo $row->url; ?>" title="<?php echo $lang->line('CONTENT_GO_TO') . ' ' . $row->url; ?>"><img src="/img/admin/shortcuts/<?php echo $row->filename; ?>" width="48" height="48" alt="<?php echo $row->filename; ?>" /><span><?php echo $row->title; ?></span></a></li>
                <?php } ?>
                <li class="hidden">&nbsp;</li>
            </ul>
            <br clear="all" />
            <p class="comments"><?php echo $lang->line('CONTENT_COMMENTS_SHORTCUTS'); ?></p>
        </div>
        <?php if ($components_privileges->main->add) { ?>
            <div id="add_user_shortcut">
            	<form method="post" action="/admin/main/add_user_shortcut">
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
                            <td class="first"><?php echo $lang->line('FIELD_URL'); ?> <span class="important">*</span></td>
                            <td><input type="text" name="url" value="<?php echo (isset($formdata['url']) ? $formdata['url'] : ''); ?>" maxlength="250" class="text" /></td>
                        </tr>
                        <tr class="row_a not_hover">
                            <td class="first"><?php echo $lang->line('FIELD_ICON'); ?> <span class="important">*</span></td>
                            <td>
                            	<div id="form_shortcuts">
                                    <ul>
                                    	<?php foreach ($shortcuts->result() as $row) { ?>
                                            <li><a href="<?php echo $row->id; ?>">
                                                <img src="/img/admin/shortcuts/<?php echo $row->filename; ?>" width="48" height="48" alt="<?php echo $lang->line('SHORTCUT_' . ($row->id < 10 ? '0' : '') . $row->id) ?>" />
                                                <span><?php echo $lang->line('SHORTCUT_' . ($row->id < 10 ? '0' : '') . $row->id) ?></span>
                                            </a></li>
                                        <?php } ?>
                                        <li class="hidden">&nbsp;</li>
                                    </ul>
                                </div>
                                <input type="hidden" name="id_shortcut" value="<?php echo (isset($formdata['id_shortcut']) ? $formdata['id_shortcut'] : ''); ?>" />
                            </td>
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
