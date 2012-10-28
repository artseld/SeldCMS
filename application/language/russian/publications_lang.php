<?php
/**
 * Publications Module
 * Language File
 *
 * Messages and names.
 *
 * @version     1.0.20120115
 * @cms_version 2.0+
 * @category    Module Language file
 * @author      ArtSeld
 * @link        http://www.artseld.com/
 */

// Module data
$lang['MODULE_VERSION']             = '1.0';
$lang['MODULE_VERSION_DETAILS']     = '1.0.20120115';
$lang['MODULE_COPYRIGHT']           = '&copy; 2011-2012 ArtSeld';
$lang['MODULE_NAME']                = 'Публикации';
$lang['MODULE_DESC']                = 'Модуль управления публикациями (новостями, статьями и другой списочной, тематически связанной и регулярно добавляемой информацией).';
$lang['MODULE_DESC_FULL']           = 'Модуль предназначен для управления публикациями, а именно: добавление, редактирование и удаление конкретных публикаций, а также их групп (лент, каналов и т.п.). Интерфейс административной части обеспечивает необходимый внутренний функционал, а внешние пользовательские интерфейсы организуются с помощью контейнеров системы. Для перехода по подразделам модуля воспользуйтесь выпадающим боковым меню.';

// Controllers
$lang['CONTROLLER_TITLE_GROUPS']    = 'Группы';
$lang['CONTROLLER_DESC_GROUPS']     = 'Управление группами публикаций.';
$lang['CONTROLLER_TITLE_ITEMS']     = 'Список';
$lang['CONTROLLER_DESC_ITEMS']      = 'Управление экземплярами публикаций.';

// Actions

// Fields
$lang['FIELD_FLAG_IS_DEFAULT']      = 'По умолчанию';
$lang['FIELD_USER_AUTHOR']          = 'Автор';

// Content and remarks
$lang['CONTENT_GROUPS']             = 'Список групп';
$lang['CONTENT_ITEMS']              = 'Список публикаций';

// Messages
// --- success events
$lang['MESSAGE_SC_ADD_ITEM']        = 'Публикация добавлена';
$lang['MESSAGE_SC_EDIT_ITEM']       = 'Публикация отредактирована';
$lang['MESSAGE_SC_DELETE_ITEM']     = 'Публикация удалена';
$lang['MESSAGE_SC_DELETE_GROUP']    = 'Группа была успешно удалена, связанные объекты уничтожены';
// --- unsuccess events
$lang['MESSAGE_UNSC_ADD_ITEM']      = 'Не получилось добавить публикацию, проверьте вводимые данные';
$lang['MESSAGE_UNSC_EDIT_ITEM']     = 'Не получилось отредактировать публикацию, проверьте вводимые данные';
$lang['MESSAGE_UNSC_DELETE_ITEM']   = 'Не получилось удалить публикацию, возможно, неверный идентификатор';

/* End of file publications_lang.php */
/* Location: ./application/language/english/publications_lang.php */