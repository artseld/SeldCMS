-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.0.45-community-nt - MySQL Community Edition (GPL)
-- Server OS:                    Win32
-- HeidiSQL version:             6.0.0.4005
-- Date/time:                    2012-10-28 15:29:07
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

-- Dumping structure for table seld2.seld2_captcha
DROP TABLE IF EXISTS `seld2_captcha`;
CREATE TABLE IF NOT EXISTS `seld2_captcha` (
  `captcha_id` bigint(13) unsigned NOT NULL auto_increment,
  `captcha_time` int(10) unsigned NOT NULL,
  `ip_address` varchar(16) NOT NULL default '0',
  `word` varchar(20) NOT NULL,
  PRIMARY KEY  (`captcha_id`),
  KEY `word` (`word`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Dumping data for table seld2.seld2_captcha: 0 rows
/*!40000 ALTER TABLE `seld2_captcha` DISABLE KEYS */;
/*!40000 ALTER TABLE `seld2_captcha` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_components
DROP TABLE IF EXISTS `seld2_components`;
CREATE TABLE IF NOT EXISTS `seld2_components` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `alias` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE` (`alias`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='System components';

-- Dumping data for table seld2.seld2_components: 16 rows
/*!40000 ALTER TABLE `seld2_components` DISABLE KEYS */;
INSERT INTO `seld2_components` (`id`, `alias`) VALUES
	(13, 'profile'),
	(12, 'mailing'),
	(10, 'users_groups'),
	(9, 'users'),
	(8, 'templates_groups'),
	(6, 'containers_groups'),
	(4, 'files'),
	(3, 'modules'),
	(2, 'structure'),
	(1, 'main'),
	(14, 'settings'),
	(15, 'resources'),
	(11, 'users_privileges'),
	(7, 'templates'),
	(5, 'containers'),
	(16, 'logs');
/*!40000 ALTER TABLE `seld2_components` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_containers
DROP TABLE IF EXISTS `seld2_containers`;
CREATE TABLE IF NOT EXISTS `seld2_containers` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `id_resource` int(11) unsigned NOT NULL,
  `id_group` int(11) unsigned NOT NULL,
  `id_document` int(11) unsigned NOT NULL default '0',
  `id_type` int(11) unsigned NOT NULL default '1',
  `priority` int(11) unsigned NOT NULL,
  `alias` varchar(50) NOT NULL,
  `title` varchar(250) NOT NULL,
  `body` longtext NOT NULL,
  `flag_free_access` tinyint(1) NOT NULL default '0',
  `comments` varchar(500) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE` (`id_resource`,`alias`),
  UNIQUE KEY `UNIQUE_T` (`id_resource`,`title`),
  KEY `INDEX` (`id_resource`,`id_group`),
  KEY `priority` (`priority`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Containers';

-- Dumping data for table seld2.seld2_containers: 5 rows
/*!40000 ALTER TABLE `seld2_containers` DISABLE KEYS */;
INSERT INTO `seld2_containers` (`id`, `id_resource`, `id_group`, `id_document`, `id_type`, `priority`, `alias`, `title`, `body`, `flag_free_access`, `comments`) VALUES
	(1, 1, 3, 0, 2, 0, 'tpl_header', 'Templates.Header', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />\r\n	<title>Seld CMS 2 :: Demo Site :: {document.title_browser}</title>\r\n	<meta http-equiv="Pragma" content="no-cache">\r\n	<meta name="keywords" content="{document.meta_keywords} {resource.meta_keywords}" />\r\n	<meta http-equiv="keywords" content="{document.meta_keywords} {resource.meta_keywords}" />\r\n	<meta name="description" content="{document.meta_description} {resource.meta_description}" />\r\n	<meta http-equiv="description" content="{document.meta_description} {resource.meta_description}" />\r\n	<meta name="author" content="ArtSeld" />\r\n	<meta name="copyright" content="(c) 2012 ArtSeld" />\r\n	<meta name="resource-type" content="document" />\r\n	<meta name="document-state" content="dynamic" />\r\n	<meta name="revisit-after" content="7 days">\r\n	<meta name="robots" content="index, follow" />\r\n	<link rel="alternate" type="application/rss+xml" title="RSS-feed" href="/news/rss" />\r\n	<link rel="icon" href="/img/favicon.ico" type="image/x-icon" />\r\n	<link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon" />\r\n	<link rel="stylesheet" href="/css/style.css" type="text/css" />\r\n	<script type="text/javascript" src="/js/jquery-1.7.1.min.js"></script>\r\n	<script type="text/javascript" src="/js/jquery.jBrowserBookmark.min.js"></script>\r\n	<script type="text/javascript" src="/js/script.js"></script>\r\n</head>\r\n<body>', 1, ''),
	(2, 1, 3, 0, 2, 999, 'tpl_footer', 'Templates.Footer', '<div id="footer">Copyright © ArtSeld</div>\r\n\r\n</div>\r\n\r\n</body>\r\n</html>', 1, ''),
	(3, 1, 3, 0, 1, 0, 'navigation', 'Navigation', 'echo $this->document_model->get_tree(\r\n	$this->get(\'resource.id\'),\r\n	0,\r\n	$this->get(\'resource.prefix\'),\r\n	FALSE\r\n);', 1, ''),
	(4, 1, 3, 0, 1, 0, 'sitemap', 'Sitemap', 'echo $this->document_model->get_tree(\r\n	$this->get(\'resource.id\'),\r\n	0,\r\n	$this->get(\'resource.prefix\'),\r\n	FALSE,\r\n	\'title_menu\',\r\n	\'class="sitemap"\'\r\n);', 1, ''),
	(5, 1, 3, 0, 1, 0, 'breadcrumbs', 'Breadcrumbs', 'if (!$this->get(\'document.flag_is_mainpage\')) {\r\n	echo $this->document_model->get_breadcrumbs(\r\n		$this->get(\'resource.id\'),\r\n		$this->get(\'document.id\'),\r\n		$this->get(\'resource.prefix\'),\r\n		$this->get(\'global.delimiter_breadcrumbs\'),\r\n		FALSE\r\n	);\r\n}', 1, ''),
	(6, 1, 3, 0, 1, 0, 'news', 'News', '// set news and page\r\nif ($this->uri->segment($this->get(\'uri.segment\') + 1) == \'page\')\r\n{\r\n	$news = \'\';\r\n	$page = intval($this->uri->segment($this->get(\'uri.segment\') + 2));\r\n}\r\nelse\r\n{\r\n	$news = $this->uri->segment($this->get(\'uri.segment\') + 1);\r\n	$page = 0;\r\n}\r\n\r\n// RSS feed\r\nif ($news && $news == \'rss\')\r\n{\r\n\r\n// Set date format\r\ndefine(\'DATE_FORMAT_RFC822\',\'r\');\r\n// Send to browser XML header\r\nheader("Content-type: text/xml; charset=utf-8");\r\n\r\n// Date of last build\r\n$lastBuildDate=date(DATE_FORMAT_RFC822);\r\n\r\necho <<<END\r\n<?xml version="1.0" encoding="utf-8"?>\r\n<rss version="2.0">\r\n<channel>\r\n    <title>Demo site. RSS-канал</title>\r\n    <link>http://www.demosite.com</link>\r\n    <description>Demo site. RSS-канал</description>\r\n    <pubDate>$lastBuildDate</pubDate>\r\n    <lastBuildDate>$lastBuildDate</lastBuildDate>\r\n    <docs>http://www.demosite.com/news/rss</docs>\r\n    <generator>Weblog Editor 2.0</generator>\r\n    <copyright>Copyright 2011 Demo site</copyright>\r\n    <managingEditor>support@demosite.com</managingEditor>\r\n    <webMaster>webmaster@demosite.com</webMaster>\r\n    <language>en</language>\r\nEND;\r\n\r\n// Modify table for your needs\r\n$query = $this->db->query(\'\r\n	select\r\n		pit.*\r\n	from\r\n		\' . $this->db->dbprefix(\'publications_items\') . \' as pit\r\n	where\r\n		pit.id_group=1 and\r\n		pit.flag_publication=1\r\n	order by\r\n		pit.time_publication desc\r\n	limit\r\n		0, 10\r\n\');\r\n\r\nforeach ($query->result() as $row) {\r\n// Delete all html tags and spaces from title\r\n$title   = strip_tags(trim($row->title_page));\r\n// Announce is set in CDATA block\r\n$anon = $row->announce;\r\n$pageUrl = base_url() . $this->get(\'resource.prefix\') . $this->get(\'uri.alias\') . \'/\' . $row->url;\r\n$pubDate = date(DATE_FORMAT_RFC822, strtotime($row->time_publication));\r\necho <<<END\r\n    <item>\r\n        <title>$title</title>\r\n        <description><![CDATA[$anon]]></description>\r\n        <link>$pageUrl</link>\r\n        <guid isPermaLink="true">$pageUrl</guid>\r\n        <pubDate>$pubDate</pubDate>\r\n    </item>\r\nEND;\r\n}\r\n\r\necho <<<END\r\n</channel>\r\n</rss>\r\nEND;\r\nexit();\r\n\r\n}\r\nelseif ($news)\r\n{\r\n\r\n// get news\r\n$news_item = $this->db->query(\'\r\n	select\r\n		pit.*,\r\n		date_format(pit.time_publication, \\\'%d.%m.%Y, %H:%i\\\') as time_publication\r\n	from\r\n		\' . $this->db->dbprefix(\'publications_items\') . \' as pit\r\n	where\r\n		pit.id_group=1 and\r\n		pit.flag_publication=1 and\r\n		pit.url=\' . $this->db->escape($news) . \'\r\n	limit 1\r\n\');\r\n\r\n// show news\r\nif ($news_item->num_rows())\r\n{\r\n\r\n	$row = $news_item->row();\r\n	echo \'<h2>\' . $row->title_page . \'</h2>\r\n	\' . $row->body . \'\r\n	<p><a href="\' . base_url() . $this->get(\'resource.prefix\') . $this->get(\'uri.alias\') . \'">&larr; Back to news list</a></p>\r\n	<div class="document_date">\' . $row->time_publication . \'</div>\r\n	<p>&nbsp;</p>\';\r\n\r\n}\r\nelse\r\n{\r\n\r\n	echo \'<strong>Error!</strong> News not found.</p>\r\n	<p>You can <a href="\' . base_url() . $this->get(\'resource.prefix\') . $this->get(\'uri.alias\') . \'">back</a> to news list.\';\r\n\r\n}\r\n\r\n}\r\nelse\r\n{\r\n\r\n// pagination\r\n$this->load->library(\'pagination\');\r\n\r\n// pagination configurations\r\n$config[\'cur_page\'] = $page;\r\n$config[\'per_page\'] = 5;\r\n$config[\'total_rows\'] = $this->db->query(\'\r\n	select\r\n		pit.id\r\n	from\r\n		\' . $this->db->dbprefix(\'publications_items\') . \' as pit\r\n	where\r\n		pit.id_group=1 and\r\n		pit.flag_publication=1\r\n\')->num_rows();\r\n$config[\'base_url\'] = base_url() . $this->get(\'resource.prefix\') . $this->get(\'uri.alias\') . \'/page/\';\r\n\r\n// pagination init\r\n$this->pagination->initialize($config);\r\n\r\n// get news\r\n$news_items = $this->db->query(\'\r\n	select\r\n		pit.*,\r\n		date_format(pit.time_publication, \\\'%d.%m.%Y, %H:%i\\\') as time_publication\r\n	from\r\n		\' . $this->db->dbprefix(\'publications_items\') . \' as pit\r\n	where\r\n		pit.id_group=1 and\r\n		pit.flag_publication=1\r\n	order by\r\n		pit.time_publication desc\r\n	limit\r\n		\' . $config[\'cur_page\'] . \', \' . $this->pagination->per_page . \'\r\n\');\r\n\r\nif ($news_items->num_rows()) {\r\n\r\nforeach ($news_items->result() as $row)\r\n{\r\n	echo \'<h2>\' . $row->title_page . \'</h2>\r\n	\' . $row->announce . \'\r\n	<p><a href="\' . base_url() . $this->get(\'resource.prefix\') . $this->get(\'uri.alias\') . \'/\' . $row->url . \'">Read more &rarr;</a></p>\r\n	<div class="document_date">\' . $row->time_publication . \'</div>\r\n	<br clear="all" />\r\n	<div class="line"></div>\';\r\n}\r\n\r\necho \'<p style="text-align: center;">\' . $this->pagination->create_links() . \'</p>\';\r\n\r\n} else {\r\n\r\necho \'<p>No any news yet.</p>\';\r\n\r\n}\r\n\r\n}', 1, '');
/*!40000 ALTER TABLE `seld2_containers` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_containers_groups
DROP TABLE IF EXISTS `seld2_containers_groups`;
CREATE TABLE IF NOT EXISTS `seld2_containers_groups` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `id_module` int(11) unsigned default NULL,
  `priority` int(11) unsigned NOT NULL,
  `title` varchar(250) NOT NULL,
  `comments` varchar(500) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE_T` (`title`),
  KEY `id_module` (`id_module`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Containers groups';

-- Dumping data for table seld2.seld2_containers_groups: 4 rows
/*!40000 ALTER TABLE `seld2_containers_groups` DISABLE KEYS */;
INSERT INTO `seld2_containers_groups` (`id`, `id_module`, `priority`, `title`, `comments`) VALUES
	(0, NULL, 0, 'no group', ''),
	(1, NULL, 0, 'Init', 'Functions and code for initialization, executing before code in Controllers and Views groups.'),
	(2, NULL, 1, 'Controllers', 'Controllers, PHP-code, executing on pages. Usually, it is working with modules and specific logic.'),
	(3, NULL, 2, 'Views', 'Views, HTML-code with CodeIgniter parser using (or simple PHP-code). Minimal programming constructions.');
/*!40000 ALTER TABLE `seld2_containers_groups` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_countries
DROP TABLE IF EXISTS `seld2_countries`;
CREATE TABLE IF NOT EXISTS `seld2_countries` (
  `group_alias` char(2) NOT NULL,
  `alias` char(2) NOT NULL,
  `title` varchar(100) NOT NULL,
  UNIQUE KEY `UNIQUE` (`group_alias`,`alias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Multiresource counties list';

-- Dumping data for table seld2.seld2_countries: 496 rows
/*!40000 ALTER TABLE `seld2_countries` DISABLE KEYS */;
INSERT INTO `seld2_countries` (`group_alias`, `alias`, `title`) VALUES
	('ru', 'au', 'Австралия'),
	('ru', 'at', 'Австрия'),
	('ru', 'az', 'Азербайджан'),
	('ru', 'al', 'Албания'),
	('ru', 'dz', 'Алжир'),
	('ru', 'as', 'Американское Самоа'),
	('ru', 'ai', 'Ангилья'),
	('ru', 'ao', 'Ангола'),
	('ru', 'ad', 'Андорра'),
	('ru', 'aq', 'Антарктида'),
	('ru', 'ag', 'Антигуа и Барбуда'),
	('ru', 'ar', 'Аргентина'),
	('ru', 'am', 'Армения'),
	('ru', 'aw', 'Аруба'),
	('ru', 'af', 'Афганистан'),
	('ru', 'bs', 'Багамы'),
	('ru', 'bd', 'Бангладеш'),
	('ru', 'bb', 'Барбадос'),
	('ru', 'bh', 'Бахрейн'),
	('ru', 'by', 'Беларусь'),
	('ru', 'bz', 'Белиз'),
	('ru', 'be', 'Бельгия'),
	('ru', 'bj', 'Бенин'),
	('ru', 'bm', 'Бермуды'),
	('ru', 'bg', 'Болгария'),
	('ru', 'bo', 'Боливия'),
	('ru', 'ba', 'Босния и Герцеговина'),
	('ru', 'bw', 'Ботсвана'),
	('ru', 'br', 'Бразилия'),
	('ru', 'io', 'Британская территория в Индийском океане'),
	('ru', 'bn', 'Бруней-Даруссалам'),
	('ru', 'bf', 'Буркина-Фасо'),
	('ru', 'bi', 'Бурунди'),
	('ru', 'bt', 'Бутан'),
	('ru', 'vu', 'Вануату'),
	('ru', 'hu', 'Венгрия'),
	('ru', 've', 'Венесуэла'),
	('ru', 'vg', 'Виргинские острова, Британские'),
	('ru', 'vi', 'Виргинские острова, США'),
	('ru', 'vn', 'Вьетнам'),
	('ru', 'ga', 'Габон'),
	('ru', 'ht', 'Гаити'),
	('ru', 'gy', 'Гайана'),
	('ru', 'gm', 'Гамбия'),
	('ru', 'gh', 'Гана'),
	('ru', 'gp', 'Гваделупа'),
	('ru', 'gt', 'Гватемала'),
	('ru', 'gn', 'Гвинея'),
	('ru', 'gw', 'Гвинея-Бисау'),
	('ru', 'de', 'Германия'),
	('ru', 'gg', 'Гернси'),
	('ru', 'gi', 'Гибралтар'),
	('ru', 'hn', 'Гондурас'),
	('ru', 'hk', 'Гонконг'),
	('ru', 'gd', 'Гренада'),
	('ru', 'gl', 'Гренландия'),
	('ru', 'gr', 'Греция'),
	('ru', 'ge', 'Грузия'),
	('ru', 'gu', 'Гуам'),
	('ru', 'dk', 'Дания'),
	('ru', 'je', 'Джерси'),
	('ru', 'dj', 'Джибути'),
	('ru', 'dm', 'Доминика'),
	('ru', 'do', 'Доминиканская Республика'),
	('ru', 'eg', 'Египет'),
	('ru', 'zm', 'Замбия'),
	('ru', 'eh', 'Западная Сахара'),
	('ru', 'zw', 'Зимбабве'),
	('ru', 'il', 'Израиль'),
	('ru', 'in', 'Индия'),
	('ru', 'id', 'Индонезия'),
	('ru', 'jo', 'Иордания'),
	('ru', 'iq', 'Ирак'),
	('ru', 'ir', 'Иран, Исламская Республика'),
	('ru', 'ie', 'Ирландия'),
	('ru', 'is', 'Исландия'),
	('ru', 'es', 'Испания'),
	('ru', 'it', 'Италия'),
	('ru', 'ye', 'Йемен'),
	('ru', 'cv', 'Кабо-Верде'),
	('ru', 'kz', 'Казахстан'),
	('ru', 'kh', 'Камбоджа'),
	('ru', 'cm', 'Камерун'),
	('ru', 'ca', 'Канада'),
	('ru', 'qa', 'Катар'),
	('ru', 'ke', 'Кения'),
	('ru', 'cy', 'Кипр'),
	('ru', 'kg', 'Киргизия'),
	('ru', 'ki', 'Кирибати'),
	('ru', 'cn', 'Китай'),
	('ru', 'cc', 'Кокосовые (Килинг) острова'),
	('ru', 'co', 'Колумбия'),
	('ru', 'km', 'Коморы'),
	('ru', 'cg', 'Конго'),
	('ru', 'cd', 'Конго, Демократическая Республика'),
	('ru', 'cs', 'Косово'),
	('ru', 'cr', 'Коста-Рика'),
	('ru', 'ci', 'Кот д\'Ивуар'),
	('ru', 'cu', 'Куба'),
	('ru', 'kw', 'Кувейт'),
	('ru', 'la', 'Лаос'),
	('ru', 'lv', 'Латвия'),
	('ru', 'ls', 'Лесото'),
	('ru', 'lb', 'Ливан'),
	('ru', 'ly', 'Ливийская Арабская Джамахирия'),
	('ru', 'lr', 'Либерия'),
	('ru', 'li', 'Лихтенштейн'),
	('ru', 'lt', 'Литва'),
	('ru', 'lu', 'Люксембург'),
	('ru', 'mu', 'Маврикий'),
	('ru', 'mr', 'Мавритания'),
	('ru', 'mg', 'Мадагаскар'),
	('ru', 'yt', 'Майотта'),
	('ru', 'mo', 'Макао'),
	('ru', 'mw', 'Малави'),
	('ru', 'my', 'Малайзия'),
	('ru', 'ml', 'Мали'),
	('ru', 'um', 'Малые Тихоокеанские отдаленные острова Соединенных Штатов'),
	('ru', 'mv', 'Мальдивы'),
	('ru', 'mt', 'Мальта'),
	('ru', 'ma', 'Марокко'),
	('ru', 'mq', 'Мартиника'),
	('ru', 'mh', 'Маршалловы острова'),
	('ru', 'mx', 'Мексика'),
	('ru', 'fm', 'Микронезия, Федеративные Штаты'),
	('ru', 'mz', 'Мозамбик'),
	('ru', 'md', 'Молдова, Республика'),
	('ru', 'mc', 'Монако'),
	('ru', 'mn', 'Монголия'),
	('ru', 'ms', 'Монтсеррат'),
	('ru', 'mm', 'Мьянма'),
	('ru', 'na', 'Намибия'),
	('ru', 'nr', 'Науру'),
	('ru', 'np', 'Непал'),
	('ru', 'ne', 'Нигер'),
	('ru', 'ng', 'Нигерия'),
	('ru', 'an', 'Нидерландские Антилы'),
	('ru', 'nl', 'Нидерланды'),
	('ru', 'ni', 'Никарагуа'),
	('ru', 'nu', 'Ниуэ'),
	('ru', 'nz', 'Новая Зеландия'),
	('ru', 'nc', 'Новая Каледония'),
	('ru', 'no', 'Норвегия'),
	('ru', 'ae', 'Объединенные Арабские Эмираты'),
	('ru', 'om', 'Оман'),
	('ru', 'bv', 'Остров Буве'),
	('ru', 'cp', 'Остров Клиппертон'),
	('ru', 'im', 'Остров Мэн'),
	('ru', 'nf', 'Остров Норфолк'),
	('ru', 'cx', 'Остров Рождества'),
	('ru', 'mf', 'Остров Святого Мартина'),
	('ru', 'hm', 'Остров Херд и острова Макдональд'),
	('ru', 'ky', 'Острова Кайман'),
	('ru', 'ck', 'Острова Кука'),
	('ru', 'tc', 'Острова Теркс и Кайкос'),
	('ru', 'pk', 'Пакистан'),
	('ru', 'pw', 'Палау'),
	('ru', 'ps', 'Палестинская территория, оккупированная'),
	('ru', 'pa', 'Панама'),
	('ru', 'va', 'Папский Престол (Государство — город Ватикан)'),
	('ru', 'pg', 'Папуа-Новая Гвинея'),
	('ru', 'py', 'Парагвай'),
	('ru', 'pe', 'Перу'),
	('ru', 'pn', 'Питкерн'),
	('ru', 'pl', 'Польша'),
	('ru', 'pt', 'Португалия'),
	('ru', 'pr', 'Пуэрто-Рико'),
	('ru', 'mk', 'Республика Македония'),
	('ru', 're', 'Реюньон'),
	('ru', 'ru', 'Россия'),
	('ru', 'rw', 'Руанда'),
	('ru', 'ro', 'Румыния'),
	('ru', 'ws', 'Самоа'),
	('ru', 'sm', 'Сан-Марино'),
	('ru', 'st', 'Сан-Томе и Принсипи'),
	('ru', 'sa', 'Саудовская Аравия'),
	('ru', 'sz', 'Свазиленд'),
	('ru', 'sh', 'Святая Елена'),
	('ru', 'kp', 'Северная Корея'),
	('ru', 'mp', 'Северные Марианские острова'),
	('ru', 'bl', 'Сен-Бартельми'),
	('ru', 'pm', 'Сен-Пьер и Микелон'),
	('ru', 'sn', 'Сенегал'),
	('ru', 'vc', 'Сент-Винсент и Гренадины'),
	('ru', 'lc', 'Сент-Люсия'),
	('ru', 'kn', 'Сент-Китс и Невис'),
	('ru', 'rs', 'Сербия'),
	('ru', 'sc', 'Сейшелы'),
	('ru', 'sg', 'Сингапур'),
	('ru', 'sy', 'Сирийская Арабская Республика'),
	('ru', 'sk', 'Словакия'),
	('ru', 'si', 'Словения'),
	('ru', 'gb', 'Соединенное Королевство'),
	('ru', 'us', 'Соединенные Штаты'),
	('ru', 'sb', 'Соломоновы острова'),
	('ru', 'so', 'Сомали'),
	('ru', 'sd', 'Судан'),
	('ru', 'sr', 'Суринам'),
	('ru', 'sl', 'Сьерра-Леоне'),
	('ru', 'tj', 'Таджикистан'),
	('ru', 'th', 'Таиланд'),
	('ru', 'tz', 'Танзания, Объединенная Республика'),
	('ru', 'tw', 'Тайвань (Китай)'),
	('ru', 'tl', 'Тимор-Лесте'),
	('ru', 'tg', 'Того'),
	('ru', 'tk', 'Токелау'),
	('ru', 'to', 'Тонга'),
	('ru', 'tt', 'Тринидад и Тобаго'),
	('ru', 'tv', 'Тувалу'),
	('ru', 'tn', 'Тунис'),
	('ru', 'tm', 'Туркмения'),
	('ru', 'tr', 'Турция'),
	('ru', 'ug', 'Уганда'),
	('ru', 'uz', 'Узбекистан'),
	('ru', 'ua', 'Украина'),
	('ru', 'wf', 'Уоллис и Футуна'),
	('ru', 'uy', 'Уругвай'),
	('ru', 'fo', 'Фарерские острова'),
	('ru', 'fj', 'Фиджи'),
	('ru', 'ph', 'Филиппины'),
	('ru', 'fi', 'Финляндия'),
	('ru', 'fk', 'Фолклендские острова (Мальвинские)'),
	('ru', 'fr', 'Франция'),
	('ru', 'gf', 'Французская Гвиана'),
	('ru', 'pf', 'Французская Полинезия'),
	('ru', 'tf', 'Французские Южные территории'),
	('ru', 'hr', 'Хорватия'),
	('ru', 'cf', 'Центрально-Африканская Республика'),
	('ru', 'td', 'Чад'),
	('ru', 'me', 'Черногория'),
	('ru', 'cz', 'Чешская Республика'),
	('ru', 'cl', 'Чили'),
	('ru', 'ch', 'Швейцария'),
	('ru', 'se', 'Швеция'),
	('ru', 'sj', 'Шпицберген и Ян Майен'),
	('ru', 'lk', 'Шри-Ланка'),
	('ru', 'ec', 'Эквадор'),
	('ru', 'gq', 'Экваториальная Гвинея'),
	('ru', 'ax', 'Эландские острова'),
	('ru', 'sv', 'Эль-Сальвадор'),
	('ru', 'er', 'Эритрея'),
	('ru', 'ee', 'Эстония'),
	('ru', 'et', 'Эфиопия'),
	('ru', 'za', 'Южная Африка'),
	('ru', 'gs', 'Южная Джорджия и Южные Сандвичевы острова'),
	('ru', 'kr', 'Южная Корея'),
	('ru', 'jm', 'Ямайка'),
	('ru', 'jp', 'Япония'),
	('en', 'au', 'Australia'),
	('en', 'at', 'Austria'),
	('en', 'az', 'Azerbaijan'),
	('en', 'al', 'Albania'),
	('en', 'dz', 'Algeria'),
	('en', 'as', 'American Samoa'),
	('en', 'ai', 'Anguilla'),
	('en', 'ao', 'Angola'),
	('en', 'ad', 'Andorra'),
	('en', 'aq', 'Antarctica'),
	('en', 'ag', 'Antigua and Barbuda'),
	('en', 'ar', 'Argentina'),
	('en', 'am', 'Armenia'),
	('en', 'aw', 'Aruba'),
	('en', 'af', 'Afghanistan'),
	('en', 'bs', 'Bahamas'),
	('en', 'bd', 'Bangladesh'),
	('en', 'bb', 'Barbados'),
	('en', 'bh', 'Bahrain'),
	('en', 'by', 'Belarus'),
	('en', 'bz', 'Belize'),
	('en', 'be', 'Belgium'),
	('en', 'bj', 'Benin'),
	('en', 'bm', 'Bermuda'),
	('en', 'bg', 'Bulgaria'),
	('en', 'bo', 'Bolivia'),
	('en', 'ba', 'Bosnia and Herzegovina'),
	('en', 'bw', 'Botswana'),
	('en', 'br', 'Brazil'),
	('en', 'io', 'British Indian Ocean Territory'),
	('en', 'bn', 'Brunei Darussalam'),
	('en', 'bf', 'Burkina Faso'),
	('en', 'bi', 'Burundi'),
	('en', 'bt', 'Bhutan'),
	('en', 'vu', 'Vanuatu'),
	('en', 'hu', 'Hungary'),
	('en', 've', 'Venezuela'),
	('en', 'vg', 'Virgin Islands, British'),
	('en', 'vi', 'Virgin Islands, U.S.'),
	('en', 'vn', 'Viet Nam'),
	('en', 'ga', 'Gabon'),
	('en', 'ht', 'Haiti'),
	('en', 'gy', 'Guyana'),
	('en', 'gm', 'Gambia'),
	('en', 'gh', 'Ghana'),
	('en', 'gp', 'Guadeloupe'),
	('en', 'gt', 'Guatemala'),
	('en', 'gn', 'Guinea'),
	('en', 'gw', 'Guinea-bissau'),
	('en', 'de', 'Germany'),
	('en', 'gg', 'Guernsey'),
	('en', 'gi', 'Gibraltar'),
	('en', 'hn', 'Honduras'),
	('en', 'hk', 'Hong Kong'),
	('en', 'gd', 'Grenada'),
	('en', 'gl', 'Greenland'),
	('en', 'gr', 'Greece'),
	('en', 'ge', 'Georgia'),
	('en', 'gu', 'Guam'),
	('en', 'dk', 'Denmark'),
	('en', 'je', 'Jersey'),
	('en', 'dj', 'Djibouti'),
	('en', 'dm', 'Dominica'),
	('en', 'do', 'Dominican Republic'),
	('en', 'eg', 'Egypt'),
	('en', 'zm', 'Zambia'),
	('en', 'eh', 'Western Sahara'),
	('en', 'zw', 'Zimbabwe'),
	('en', 'il', 'Israel'),
	('en', 'in', 'India'),
	('en', 'id', 'Indonesia'),
	('en', 'jo', 'Jordan'),
	('en', 'iq', 'Iraq'),
	('en', 'ir', 'Iran, Islamic Republic of'),
	('en', 'ie', 'Ireland'),
	('en', 'is', 'Iceland'),
	('en', 'es', 'Spain'),
	('en', 'it', 'Italy'),
	('en', 'ye', 'Yemen'),
	('en', 'cv', 'Cape Verde'),
	('en', 'kz', 'Kazakhstan'),
	('en', 'kh', 'Cambodia'),
	('en', 'cm', 'Cameroon'),
	('en', 'ca', 'Canada'),
	('en', 'qa', 'Qatar'),
	('en', 'ke', 'Kenya'),
	('en', 'cy', 'Cyprus'),
	('en', 'kg', 'Kyrgyzstan'),
	('en', 'ki', 'Kiribati'),
	('en', 'cn', 'China'),
	('en', 'cc', 'Cocos (Keeling) Islands'),
	('en', 'co', 'Colombia'),
	('en', 'km', 'Comoros'),
	('en', 'cg', 'Congo'),
	('en', 'cd', 'Congo, The Democratic Republic of The'),
	('en', 'cs', 'Kosovo'),
	('en', 'cr', 'Costa Rica'),
	('en', 'ci', 'Cote D\'ivoire'),
	('en', 'cu', 'Cuba'),
	('en', 'kw', 'Kuwait'),
	('en', 'la', 'Lao People\'s Democratic Republic'),
	('en', 'lv', 'Latvia'),
	('en', 'ls', 'Lesotho'),
	('en', 'lb', 'Lebanon'),
	('en', 'ly', 'Libyan Arab Jamahiriya'),
	('en', 'lr', 'Liberia'),
	('en', 'li', 'Liechtenstein'),
	('en', 'lt', 'Lithuania'),
	('en', 'lu', 'Luxembourg'),
	('en', 'mu', 'Mauritius'),
	('en', 'mr', 'Mauritania'),
	('en', 'mg', 'Madagascar'),
	('en', 'yt', 'Mayotte'),
	('en', 'mo', 'Macao'),
	('en', 'mw', 'Malawi'),
	('en', 'my', 'Malaysia'),
	('en', 'ml', 'Mali'),
	('en', 'um', 'United States Minor Outlying Islands'),
	('en', 'mv', 'Maldives'),
	('en', 'mt', 'Malta'),
	('en', 'ma', 'Morocco'),
	('en', 'mq', 'Martinique'),
	('en', 'mh', 'Marshall Islands'),
	('en', 'mx', 'Mexico'),
	('en', 'fm', 'Micronesia, Federated States of'),
	('en', 'mz', 'Mozambique'),
	('en', 'md', 'Moldova, Republic of'),
	('en', 'mc', 'Monaco'),
	('en', 'mn', 'Mongolia'),
	('en', 'ms', 'Montserrat'),
	('en', 'mm', 'Myanmar'),
	('en', 'na', 'Namibia'),
	('en', 'nr', 'Nauru'),
	('en', 'np', 'Nepal'),
	('en', 'ne', 'Niger'),
	('en', 'ng', 'Nigeria'),
	('en', 'an', 'Netherlands Antilles'),
	('en', 'nl', 'Netherlands'),
	('en', 'ni', 'Nicaragua'),
	('en', 'nu', 'Niue'),
	('en', 'nz', 'New Zealand'),
	('en', 'nc', 'New Caledonia'),
	('en', 'no', 'Norway'),
	('en', 'ae', 'United Arab Emirates'),
	('en', 'om', 'Oman'),
	('en', 'bv', 'Bouvet Island'),
	('en', 'cp', 'Clipperton Island'),
	('en', 'im', 'Isle of Man'),
	('en', 'nf', 'Norfolk Island'),
	('en', 'cx', 'Christmas Island'),
	('en', 'mf', 'Saint Martin'),
	('en', 'hm', 'Heard Island and Mcdonald Islands'),
	('en', 'ky', 'Cayman Islands'),
	('en', 'ck', 'Cook Islands'),
	('en', 'tc', 'Turks and Caicos Islands'),
	('en', 'pk', 'Pakistan'),
	('en', 'pw', 'Palau'),
	('en', 'ps', 'Palestinian Territory, Occupied'),
	('en', 'pa', 'Panama'),
	('en', 'va', 'Holy See (Vatican City State)'),
	('en', 'pg', 'Papua New Guinea'),
	('en', 'py', 'Paraguay'),
	('en', 'pe', 'Peru'),
	('en', 'pn', 'Pitcairn'),
	('en', 'pl', 'Poland'),
	('en', 'pt', 'Portugal'),
	('en', 'pr', 'Puerto Rico'),
	('en', 'mk', 'Macedonia, The Former Yugoslav Republic of'),
	('en', 're', 'Reunion'),
	('en', 'ru', 'Russian Federation'),
	('en', 'rw', 'Rwanda'),
	('en', 'ro', 'Romania'),
	('en', 'ws', 'Samoa'),
	('en', 'sm', 'San Marino'),
	('en', 'st', 'Sao Tome and Principe'),
	('en', 'sa', 'Saudi Arabia'),
	('en', 'sz', 'Swaziland'),
	('en', 'sh', 'Saint Helena'),
	('en', 'kp', 'Korea, Democratic People\'s Republic of'),
	('en', 'mp', 'Northern Mariana Islands'),
	('en', 'bl', 'Saint Barthélemy'),
	('en', 'pm', 'Saint Pierre and Miquelon'),
	('en', 'sn', 'Senegal'),
	('en', 'vc', 'Saint Vincent and The Grenadines'),
	('en', 'lc', 'Saint Lucia'),
	('en', 'kn', 'Saint Kitts and Nevis'),
	('en', 'rs', 'Serbia'),
	('en', 'sc', 'Seychelles'),
	('en', 'sg', 'Singapore'),
	('en', 'sy', 'Syrian Arab Republic'),
	('en', 'sk', 'Slovakia'),
	('en', 'si', 'Slovenia'),
	('en', 'gb', 'United Kingdom'),
	('en', 'us', 'United States'),
	('en', 'sb', 'Solomon Islands'),
	('en', 'so', 'Somalia'),
	('en', 'sd', 'Sudan'),
	('en', 'sr', 'Suriname'),
	('en', 'sl', 'Sierra Leone'),
	('en', 'tj', 'Tajikistan'),
	('en', 'th', 'Thailand'),
	('en', 'tz', 'Tanzania, United Republic of'),
	('en', 'tw', 'Taiwan, Province of China'),
	('en', 'tl', 'Timor-leste'),
	('en', 'tg', 'Togo'),
	('en', 'tk', 'Tokelau'),
	('en', 'to', 'Tonga'),
	('en', 'tt', 'Trinidad and Tobago'),
	('en', 'tv', 'Tuvalu'),
	('en', 'tn', 'Tunisia'),
	('en', 'tm', 'Turkmenistan'),
	('en', 'tr', 'Turkey'),
	('en', 'ug', 'Uganda'),
	('en', 'uz', 'Uzbekistan'),
	('en', 'ua', 'Ukraine'),
	('en', 'wf', 'Wallis and Futuna'),
	('en', 'uy', 'Uruguay'),
	('en', 'fo', 'Faroe Islands'),
	('en', 'fj', 'Fiji'),
	('en', 'ph', 'Philippines'),
	('en', 'fi', 'Finland'),
	('en', 'fk', 'Falkland Islands (Malvinas)'),
	('en', 'fr', 'France'),
	('en', 'gf', 'French Guiana'),
	('en', 'pf', 'French Polynesia'),
	('en', 'tf', 'French Southern Territories'),
	('en', 'hr', 'Croatia'),
	('en', 'cf', 'Central African Republic'),
	('en', 'td', 'Chad'),
	('en', 'me', 'Montenegro'),
	('en', 'cz', 'Czech Republic'),
	('en', 'cl', 'Chile'),
	('en', 'ch', 'Switzerland'),
	('en', 'se', 'Sweden'),
	('en', 'sj', 'Svalbard and Jan Mayen'),
	('en', 'lk', 'Sri Lanka'),
	('en', 'ec', 'Ecuador'),
	('en', 'gq', 'Equatorial Guinea'),
	('en', 'ax', 'Åland Islands'),
	('en', 'sv', 'El Salvador'),
	('en', 'er', 'Eritrea'),
	('en', 'ee', 'Estonia'),
	('en', 'et', 'Ethiopia'),
	('en', 'za', 'South Africa'),
	('en', 'gs', 'South Georgia and The South Sandwich Islands'),
	('en', 'kr', 'Korea, Republic of'),
	('en', 'jm', 'Jamaica'),
	('en', 'jp', 'Japan');
/*!40000 ALTER TABLE `seld2_countries` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_countries_cities
DROP TABLE IF EXISTS `seld2_countries_cities`;
CREATE TABLE IF NOT EXISTS `seld2_countries_cities` (
  `id` int(10) unsigned NOT NULL,
  `group_alias` char(2) NOT NULL,
  `country_alias` char(2) NOT NULL,
  `weight` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL,
  UNIQUE KEY `UNIQUE` (`id`,`group_alias`),
  KEY `country` (`country_alias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Multiresource counties cities list';

-- Dumping data for table seld2.seld2_countries_cities: 12 rows
/*!40000 ALTER TABLE `seld2_countries_cities` DISABLE KEYS */;
INSERT INTO `seld2_countries_cities` (`id`, `group_alias`, `country_alias`, `weight`, `title`) VALUES
	(1, 'ru', 'ru', 2, 'Москва'),
	(1, 'en', 'ru', 2, 'Moscow'),
	(2, 'ru', 'ru', 1, 'Санкт-Петербург'),
	(2, 'en', 'ru', 1, 'Saint Peterburg'),
	(3, 'ru', 'by', 1, 'Минск'),
	(3, 'en', 'by', 1, 'Minsk'),
	(4, 'ru', 'by', 0, 'Брест'),
	(4, 'en', 'by', 0, 'Brest'),
	(5, 'ru', 'by', 0, 'Витебск'),
	(5, 'en', 'by', 0, 'Витебск');
/*!40000 ALTER TABLE `seld2_countries_cities` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_ip_blacklist
DROP TABLE IF EXISTS `seld2_ip_blacklist`;
CREATE TABLE IF NOT EXISTS `seld2_ip_blacklist` (
  `ip` varbinary(16) NOT NULL default '0',
  `last_attempt` datetime default NULL,
  `failed_attempts` tinyint(3) unsigned NOT NULL default '0',
  `datetime_until` datetime default NULL,
  `flag_permanent` tinyint(1) unsigned NOT NULL default '0',
  UNIQUE KEY `UNIQ_IP` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='IP BlackList';

-- Dumping data for table seld2.seld2_ip_blacklist: 0 rows
/*!40000 ALTER TABLE `seld2_ip_blacklist` DISABLE KEYS */;
/*!40000 ALTER TABLE `seld2_ip_blacklist` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_logs
DROP TABLE IF EXISTS `seld2_logs`;
CREATE TABLE IF NOT EXISTS `seld2_logs` (
  `event_time` datetime NOT NULL,
  `event_category` enum('system','frontside','backside','component','module') NOT NULL,
  `id_event_source` int(11) unsigned NOT NULL,
  `body` varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='Project logs';

-- Dumping data for table seld2.seld2_logs: 0 rows
/*!40000 ALTER TABLE `seld2_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `seld2_logs` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_mailing
DROP TABLE IF EXISTS `seld2_mailing`;
CREATE TABLE IF NOT EXISTS `seld2_mailing` (
  `id_resource` int(10) unsigned NOT NULL,
  `accounts_num_in_stream` int(10) unsigned NOT NULL default '0',
  `subscribers_category` int(11) unsigned NOT NULL,
  `signature` varchar(500) NOT NULL,
  UNIQUE KEY `id_resource` (`id_resource`),
  KEY `subscribers_category` (`subscribers_category`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Mailing settings';

-- Dumping data for table seld2.seld2_mailing: 1 rows
/*!40000 ALTER TABLE `seld2_mailing` DISABLE KEYS */;
INSERT INTO `seld2_mailing` (`id_resource`, `accounts_num_in_stream`, `subscribers_category`, `signature`) VALUES
	(2, 25, 1, '<p>Всех благ, Администрация.</p>'),
	(1, 25, 1, '<p>Best regards, Administration</p>');
/*!40000 ALTER TABLE `seld2_mailing` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_mailing_events
DROP TABLE IF EXISTS `seld2_mailing_events`;
CREATE TABLE IF NOT EXISTS `seld2_mailing_events` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `datetime_event` datetime NOT NULL,
  `id_resource` int(11) unsigned NOT NULL,
  `subscribers_count` int(11) unsigned NOT NULL default '0',
  `subscribers_category` int(11) unsigned NOT NULL,
  `subject` varchar(250) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id_resource` (`id_resource`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Mailing events';

-- Dumping data for table seld2.seld2_mailing_events: 0 rows
/*!40000 ALTER TABLE `seld2_mailing_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `seld2_mailing_events` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_mailing_subscribers_categories
DROP TABLE IF EXISTS `seld2_mailing_subscribers_categories`;
CREATE TABLE IF NOT EXISTS `seld2_mailing_subscribers_categories` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(250) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Mailing subscribers categories';

-- Dumping data for table seld2.seld2_mailing_subscribers_categories: 1 rows
/*!40000 ALTER TABLE `seld2_mailing_subscribers_categories` DISABLE KEYS */;
INSERT INTO `seld2_mailing_subscribers_categories` (`id`, `title`) VALUES
	(1, 'All active users');
/*!40000 ALTER TABLE `seld2_mailing_subscribers_categories` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_messages
DROP TABLE IF EXISTS `seld2_messages`;
CREATE TABLE IF NOT EXISTS `seld2_messages` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `id_sender` int(11) unsigned NOT NULL,
  `id_recipient` int(11) unsigned NOT NULL,
  `subject` varchar(250) default NULL,
  `body` text,
  `datetime_creation` datetime NOT NULL,
  `flag_is_readed` tinyint(1) NOT NULL default '0',
  `flag_is_deleted` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Users private messages';

-- Dumping data for table seld2.seld2_messages: 0 rows
/*!40000 ALTER TABLE `seld2_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `seld2_messages` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_modules
DROP TABLE IF EXISTS `seld2_modules`;
CREATE TABLE IF NOT EXISTS `seld2_modules` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `alias` varchar(50) NOT NULL,
  `title` varchar(250) NOT NULL,
  `comments` varchar(500) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE` (`alias`),
  UNIQUE KEY `UNIQUE_T` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Project modules';

-- Dumping data for table seld2.seld2_modules: 1 rows
/*!40000 ALTER TABLE `seld2_modules` DISABLE KEYS */;
INSERT INTO `seld2_modules` (`id`, `alias`, `title`, `comments`) VALUES
	(1, 'publications', 'Publications', 'Management of news, articles, and other list information.');
/*!40000 ALTER TABLE `seld2_modules` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_modules_controllers
DROP TABLE IF EXISTS `seld2_modules_controllers`;
CREATE TABLE IF NOT EXISTS `seld2_modules_controllers` (
  `id_module` int(11) NOT NULL,
  `alias` varchar(50) NOT NULL,
  `title` varchar(250) NOT NULL,
  `comments` varchar(500) NOT NULL,
  UNIQUE KEY `UNIQUE` (`alias`),
  KEY `id_module` (`id_module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Modules controllers list';

-- Dumping data for table seld2.seld2_modules_controllers: 2 rows
/*!40000 ALTER TABLE `seld2_modules_controllers` DISABLE KEYS */;
INSERT INTO `seld2_modules_controllers` (`id_module`, `alias`, `title`, `comments`) VALUES
	(1, 'publications_items', 'List', 'Manage your publications.'),
	(1, 'publications_groups', 'Groups', 'Managing groups of publications.');
/*!40000 ALTER TABLE `seld2_modules_controllers` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_publications_additional
DROP TABLE IF EXISTS `seld2_publications_additional`;
CREATE TABLE IF NOT EXISTS `seld2_publications_additional` (
  `id_item` int(10) unsigned NOT NULL,
  `id_recipient` int(10) unsigned default NULL,
  `id_city` int(10) unsigned default NULL,
  `gender` enum('male','female','couple','team') default NULL,
  UNIQUE KEY `unique_item` (`id_item`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Publications module additional';

-- Dumping data for table seld2.seld2_publications_additional: 0 rows
/*!40000 ALTER TABLE `seld2_publications_additional` DISABLE KEYS */;
/*!40000 ALTER TABLE `seld2_publications_additional` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_publications_groups
DROP TABLE IF EXISTS `seld2_publications_groups`;
CREATE TABLE IF NOT EXISTS `seld2_publications_groups` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `id_resource` int(11) unsigned NOT NULL COMMENT 'may be equals 0 - for all resources',
  `priority` int(11) unsigned NOT NULL COMMENT 'may be equals 0 - for all resources',
  `title` varchar(250) NOT NULL,
  `comments` varchar(500) NOT NULL,
  `flag_is_default` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `id_resource` (`id_resource`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Publications module groups';

-- Dumping data for table seld2.seld2_publications_groups: 1 rows
/*!40000 ALTER TABLE `seld2_publications_groups` DISABLE KEYS */;
INSERT INTO `seld2_publications_groups` (`id`, `id_resource`, `priority`, `title`, `comments`, `flag_is_default`) VALUES
	(1, 0, 0, 'Main news', '', 1);
/*!40000 ALTER TABLE `seld2_publications_groups` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_publications_items
DROP TABLE IF EXISTS `seld2_publications_items`;
CREATE TABLE IF NOT EXISTS `seld2_publications_items` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `id_group` int(11) unsigned NOT NULL,
  `id_user_author` int(11) unsigned NOT NULL,
  `url` varchar(250) NOT NULL COMMENT 'optional, may be empty',
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `title_browser` varchar(250) NOT NULL,
  `title_page` varchar(250) NOT NULL,
  `title_menu` varchar(250) NOT NULL,
  `announce` text NOT NULL,
  `body` longtext NOT NULL,
  `time_modification` datetime NOT NULL,
  `time_publication` datetime default NULL,
  `time_expiration` datetime default NULL,
  `flag_publication` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE` (`url`),
  KEY `id_group` (`id_group`),
  KEY `id_user_author` (`id_user_author`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Publications module items';

-- Dumping data for table seld2.seld2_publications_items: 0 rows
/*!40000 ALTER TABLE `seld2_publications_items` DISABLE KEYS */;
INSERT INTO `seld2_publications_items` (`id`, `id_group`, `id_user_author`, `url`, `meta_keywords`, `meta_description`, `title_browser`, `title_page`, `title_menu`, `announce`, `body`, `time_modification`, `time_publication`, `time_expiration`, `flag_publication`) VALUES
	(1, 1, 1, 'we_are_opened', 'we are opened', 'we are opened', 'We are opened', 'We are opened', 'We are opened', '<p>\r\n	We are opened!</p>', '<p>\r\n	We are opened! Full text here.</p>', '2012-10-28 15:20:58', '2012-10-28 15:20:58', NULL, 1);
/*!40000 ALTER TABLE `seld2_publications_items` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_resources
DROP TABLE IF EXISTS `seld2_resources`;
CREATE TABLE IF NOT EXISTS `seld2_resources` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `url` varchar(50) NOT NULL,
  `title` varchar(250) NOT NULL,
  `comments` varchar(500) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE` (`url`),
  UNIQUE KEY `UNIQUE_T` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='Project resources';

-- Dumping data for table seld2.seld2_resources: 2 rows
/*!40000 ALTER TABLE `seld2_resources` DISABLE KEYS */;
INSERT INTO `seld2_resources` (`id`, `url`, `title`, `comments`) VALUES
	(1, 'eng', 'English', 'Resource for english locale'),
	(2, 'rus', 'Russian', 'Resource for russian locale');
/*!40000 ALTER TABLE `seld2_resources` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_resources_settings
DROP TABLE IF EXISTS `seld2_resources_settings`;
CREATE TABLE IF NOT EXISTS `seld2_resources_settings` (
  `id_resource` int(11) unsigned NOT NULL,
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `meta_additional` text NOT NULL,
  `title_browser` varchar(250) NOT NULL,
  `title_page` varchar(250) NOT NULL,
  `title_menu` varchar(250) NOT NULL,
  `url_error_404` varchar(250) NOT NULL,
  `url_ipblocked` varchar(250) NOT NULL,
  UNIQUE KEY `UNIQUE` (`id_resource`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='Project resources settings';

-- Dumping data for table seld2.seld2_resources_settings: 1 rows
/*!40000 ALTER TABLE `seld2_resources_settings` DISABLE KEYS */;
INSERT INTO `seld2_resources_settings` (`id_resource`, `meta_keywords`, `meta_description`, `meta_additional`, `title_browser`, `title_page`, `title_menu`, `url_error_404`, `url_ipblocked`) VALUES
	(1, 'keywords', 'description', '', 'Demo site', '', '', 'error_404', 'error_ipblocked'),
	(2, 'ключевые слова', 'описание', '', 'Демонстранционный сайт', '', '', 'error_404', 'error_ipblocked');
/*!40000 ALTER TABLE `seld2_resources_settings` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_settings
DROP TABLE IF EXISTS `seld2_settings`;
CREATE TABLE IF NOT EXISTS `seld2_settings` (
  `id_resource_default` int(11) unsigned NOT NULL,
  `id_users_group_default` int(11) unsigned NOT NULL,
  `flag_countries_selector` tinyint(1) NOT NULL default '0',
  `countries_selector_alias` char(2) NOT NULL,
  `delimiter_breadcrumbs` varchar(25) NOT NULL,
  `flag_use_rte` tinyint(1) NOT NULL default '0',
  `flag_caching` tinyint(1) NOT NULL default '0',
  `caching_duration` int(10) unsigned NOT NULL default '0',
  `flag_logging` tinyint(1) NOT NULL default '0',
  `max_failed_attempts` tinyint(3) unsigned NOT NULL default '0',
  `blocking_duration` int(10) unsigned NOT NULL default '0',
  `clear_interval` int(10) unsigned NOT NULL default '0',
  `root_message` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='Project global settings';

-- Dumping data for table seld2.seld2_settings: 1 rows
/*!40000 ALTER TABLE `seld2_settings` DISABLE KEYS */;
INSERT INTO `seld2_settings` (`id_resource_default`, `id_users_group_default`, `flag_countries_selector`, `countries_selector_alias`, `delimiter_breadcrumbs`, `flag_use_rte`, `flag_caching`, `caching_duration`, `flag_logging`, `max_failed_attempts`, `blocking_duration`, `clear_interval`, `root_message`) VALUES
	(1, 1, 1, 'en', '/', 1, 0, 15, 0, 3, 60, 15, 'Hello, administrators!');
/*!40000 ALTER TABLE `seld2_settings` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_shortcuts
DROP TABLE IF EXISTS `seld2_shortcuts`;
CREATE TABLE IF NOT EXISTS `seld2_shortcuts` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `filename` varchar(250) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE` (`filename`)
) ENGINE=MyISAM AUTO_INCREMENT=81 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='Backside shortcuts';

-- Dumping data for table seld2.seld2_shortcuts: 80 rows
/*!40000 ALTER TABLE `seld2_shortcuts` DISABLE KEYS */;
INSERT INTO `seld2_shortcuts` (`id`, `filename`) VALUES
	(1, 'shortcut_01.png'),
	(2, 'shortcut_02.png'),
	(3, 'shortcut_03.png'),
	(4, 'shortcut_04.png'),
	(5, 'shortcut_05.png'),
	(6, 'shortcut_06.png'),
	(7, 'shortcut_07.png'),
	(8, 'shortcut_08.png'),
	(9, 'shortcut_09.png'),
	(10, 'shortcut_10.png'),
	(11, 'shortcut_11.png'),
	(12, 'shortcut_12.png'),
	(13, 'shortcut_13.png'),
	(14, 'shortcut_14.png'),
	(15, 'shortcut_15.png'),
	(16, 'shortcut_16.png'),
	(17, 'shortcut_17.png'),
	(18, 'shortcut_18.png'),
	(19, 'shortcut_19.png'),
	(20, 'shortcut_20.png'),
	(21, 'shortcut_21.png'),
	(22, 'shortcut_22.png'),
	(23, 'shortcut_23.png'),
	(24, 'shortcut_24.png'),
	(25, 'shortcut_25.png'),
	(26, 'shortcut_26.png'),
	(27, 'shortcut_27.png'),
	(28, 'shortcut_28.png'),
	(29, 'shortcut_29.png'),
	(30, 'shortcut_30.png'),
	(31, 'shortcut_31.png'),
	(32, 'shortcut_32.png'),
	(33, 'shortcut_33.png'),
	(34, 'shortcut_34.png'),
	(35, 'shortcut_35.png'),
	(36, 'shortcut_36.png'),
	(37, 'shortcut_37.png'),
	(38, 'shortcut_38.png'),
	(39, 'shortcut_39.png'),
	(40, 'shortcut_40.png'),
	(41, 'shortcut_41.png'),
	(42, 'shortcut_42.png'),
	(43, 'shortcut_43.png'),
	(44, 'shortcut_44.png'),
	(45, 'shortcut_45.png'),
	(46, 'shortcut_46.png'),
	(47, 'shortcut_47.png'),
	(48, 'shortcut_48.png'),
	(49, 'shortcut_49.png'),
	(50, 'shortcut_50.png'),
	(51, 'shortcut_51.png'),
	(52, 'shortcut_52.png'),
	(53, 'shortcut_53.png'),
	(54, 'shortcut_54.png'),
	(55, 'shortcut_55.png'),
	(56, 'shortcut_56.png'),
	(57, 'shortcut_57.png'),
	(58, 'shortcut_58.png'),
	(59, 'shortcut_59.png'),
	(60, 'shortcut_60.png'),
	(61, 'shortcut_61.png'),
	(62, 'shortcut_62.png'),
	(63, 'shortcut_63.png'),
	(64, 'shortcut_64.png'),
	(65, 'shortcut_65.png'),
	(66, 'shortcut_66.png'),
	(67, 'shortcut_67.png'),
	(68, 'shortcut_68.png'),
	(69, 'shortcut_69.png'),
	(70, 'shortcut_70.png'),
	(71, 'shortcut_71.png'),
	(72, 'shortcut_72.png'),
	(73, 'shortcut_73.png'),
	(74, 'shortcut_74.png'),
	(75, 'shortcut_75.png'),
	(76, 'shortcut_76.png'),
	(77, 'shortcut_77.png'),
	(78, 'shortcut_78.png'),
	(79, 'shortcut_79.png'),
	(80, 'shortcut_80.png');
/*!40000 ALTER TABLE `seld2_shortcuts` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_structure
DROP TABLE IF EXISTS `seld2_structure`;
CREATE TABLE IF NOT EXISTS `seld2_structure` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `id_resource` int(11) unsigned NOT NULL,
  `id_parent` int(11) unsigned NOT NULL,
  `id_template` int(11) unsigned NOT NULL,
  `id_module` int(11) unsigned NOT NULL,
  `priority` int(11) unsigned NOT NULL,
  `url` varchar(250) NOT NULL COMMENT 'optional, may be empty',
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `title_browser` varchar(250) NOT NULL,
  `title_page` varchar(250) NOT NULL,
  `title_menu` varchar(250) NOT NULL,
  `body` longtext NOT NULL,
  `time_modification` datetime NOT NULL,
  `time_publication` datetime NOT NULL,
  `flag_use_rte` tinyint(1) NOT NULL default '0',
  `flag_display_in_menu` tinyint(1) NOT NULL default '0',
  `flag_free_access` tinyint(1) NOT NULL default '0',
  `flag_caching` tinyint(1) NOT NULL default '0',
  `flag_publication` tinyint(1) NOT NULL default '0',
  `flag_is_mainpage` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE` (`url`,`id_resource`),
  KEY `INDEX` (`id_resource`,`id_parent`,`id_template`,`id_module`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Main content structure';

-- Dumping data for table seld2.seld2_structure: 8 rows
/*!40000 ALTER TABLE `seld2_structure` DISABLE KEYS */;
INSERT INTO `seld2_structure` (`id`, `id_resource`, `id_parent`, `id_template`, `id_module`, `priority`, `url`, `meta_keywords`, `meta_description`, `title_browser`, `title_page`, `title_menu`, `body`, `time_modification`, `time_publication`, `flag_use_rte`, `flag_display_in_menu`, `flag_free_access`, `flag_caching`, `flag_publication`, `flag_is_mainpage`) VALUES
	(1, 1, 0, 1, 0, 0, 'index', 'main page', 'main page', 'Main page', 'Main page', 'Main page', '<h2>\r\n	Welcome to Seld CMS 2 Demo site!</h2>\r\n<p>\r\n	<strong>Section 1</strong></p>\r\n<p>\r\n	Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>\r\n	<strong>Section 2</strong></p>\r\n<p>\r\n	Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>\r\n<p>\r\n	<strong>Section 3</strong></p>\r\n<p>\r\n	Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', '2012-10-27 22:53:29', '2010-12-12 15:38:42', 1, 1, 1, 0, 1, 0),
	(10, 1, 0, 1, 0, 2, 'news', 'news', 'news', 'News', 'News', 'News', '{containers.news}', '2012-10-28 15:01:54', '2012-10-28 14:59:59', 0, 1, 1, 0, 1, 0),
	(2, 1, 0, 1, 0, 7, 'error_404', 'error 404', 'error 404', 'Error 404', 'Error 404', 'Error 404', '<p>Page not found.</p>\r\n<p>Please use <a href="/sitemap">sitemap</a> or left navigation to find needed information. If problem appears again please contact us.</p>', '2012-10-28 15:00:32', '2010-12-13 18:40:21', 0, 0, 1, 0, 1, 0),
	(8, 1, 3, 1, 0, 0, 'internal', 'internal', 'internal', 'Internal page', 'Internal page', 'Internal page', '<p>\r\n	Example of&nbsp;Internal page.</p>', '2012-10-28 13:02:31', '2012-10-28 13:02:31', 1, 1, 1, 0, 1, 0),
	(9, 2, 0, 1, 0, 0, 'index', 'главная страница', 'главная страница', 'Главная страница', 'Главная страница', 'Главная страница', '<p>\r\n	Главная страница.</p>', '2012-10-28 13:53:58', '2012-10-28 13:52:38', 1, 1, 1, 0, 1, 1),
	(3, 1, 0, 1, 0, 1, 'about', 'about', 'about', 'About us', 'About us', 'About us', '<p>\r\n	Many words about our simple project.</p>', '2012-10-28 10:45:53', '2010-12-13 18:41:47', 1, 1, 1, 0, 1, 0),
	(4, 1, 0, 1, 0, 4, 'contacts', 'contacts', 'contacts', 'Contacts', 'Contacts', 'Contacts', '<p>Our contacts here.</p>', '2012-10-28 15:01:00', '2010-12-19 13:45:17', 0, 1, 1, 0, 1, 0),
	(5, 1, 0, 1, 0, 8, 'error_ipblocked', 'error_ipblocked', 'error_ipblocked', 'Your IP is blocked', 'Your IP is blocked', 'Your IP is blocked', '<p>\r\n	Too many failed attempts or possible hacker attack detected. Please wait a few minutes and try again.</p>\r\n<p>\r\n	May be your IP is in denied list.</p>', '2012-10-28 15:00:24', '2011-12-20 23:45:39', 1, 0, 1, 0, 1, 0),
	(6, 1, 0, 1, 0, 6, 'private', 'private', 'private', 'Private page', 'Private page', 'Private page', '<p>\r\n	Hello, this is private page! Shown only for registered users.</p>', '2012-10-28 15:01:30', '2012-10-28 12:37:25', 1, 1, 0, 0, 1, 0),
	(7, 1, 0, 1, 0, 5, 'sitemap', 'sitemap', 'sitemap', 'Sitemap', 'Sitemap', 'Sitemap', '<p>\r\n	Choose the page:</p>\r\n<p>\r\n	{containers.sitemap}</p>', '2012-10-28 15:01:39', '2012-10-28 12:46:32', 1, 1, 1, 0, 1, 0);
/*!40000 ALTER TABLE `seld2_structure` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_templates
DROP TABLE IF EXISTS `seld2_templates`;
CREATE TABLE IF NOT EXISTS `seld2_templates` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `id_group` int(11) unsigned NOT NULL,
  `alias` varchar(50) NOT NULL,
  `title` varchar(250) NOT NULL,
  `comments` varchar(500) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE_T` (`title`),
  KEY `INDEX` (`id_group`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Project templates';

-- Dumping data for table seld2.seld2_templates: 2 rows
/*!40000 ALTER TABLE `seld2_templates` DISABLE KEYS */;
INSERT INTO `seld2_templates` (`id`, `id_group`, `alias`, `title`, `comments`) VALUES
	(1, 0, 'index', '1. Simple page', ''),
	(2, 0, 'json', '2. JSON', '');
/*!40000 ALTER TABLE `seld2_templates` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_templates_groups
DROP TABLE IF EXISTS `seld2_templates_groups`;
CREATE TABLE IF NOT EXISTS `seld2_templates_groups` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(250) NOT NULL,
  `comments` varchar(500) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE_T` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Project templates groups';

-- Dumping data for table seld2.seld2_templates_groups: 1 rows
/*!40000 ALTER TABLE `seld2_templates_groups` DISABLE KEYS */;
INSERT INTO `seld2_templates_groups` (`id`, `title`, `comments`) VALUES
	(0, 'no group', '');
/*!40000 ALTER TABLE `seld2_templates_groups` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_users
DROP TABLE IF EXISTS `seld2_users`;
CREATE TABLE IF NOT EXISTS `seld2_users` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `id_group` int(11) unsigned NOT NULL,
  `auth_login` varchar(50) NOT NULL,
  `auth_password` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `avatar_filename` varchar(50) default NULL,
  `avatar_mimetype` varchar(25) default NULL,
  `birthday` date NOT NULL,
  `gender` enum('male','female','couple','team') NOT NULL,
  `country` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `address` varchar(250) NOT NULL,
  `email` varchar(50) NOT NULL,
  `additional` varchar(250) NOT NULL,
  `private_message` text NOT NULL,
  `time_registration` datetime NOT NULL,
  `visibility_type` enum('nobody','friends','members','all') NOT NULL,
  `rating` int(11) NOT NULL default '0',
  `datetime_last_action` datetime default NULL,
  `flag_verification` tinyint(1) NOT NULL default '0',
  `flag_access` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE` (`auth_login`),
  UNIQUE KEY `email` (`email`),
  KEY `INDEX` (`id_group`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Users and administrators';

-- Dumping data for table seld2.seld2_users: 1 rows
/*!40000 ALTER TABLE `seld2_users` DISABLE KEYS */;
INSERT INTO `seld2_users` (`id`, `id_group`, `auth_login`, `auth_password`, `first_name`, `middle_name`, `last_name`, `avatar_filename`, `avatar_mimetype`, `birthday`, `gender`, `country`, `city`, `address`, `email`, `additional`, `private_message`, `time_registration`, `visibility_type`, `rating`, `datetime_last_action`, `flag_verification`, `flag_access`) VALUES
	(1, 0, 'admin', '171b786d2574fdba', 'Super', '', 'Administrator', '', '', '1970-01-01', 'male', '', '', '', 'admin@landofadmins.com', '', 'No remarks yet', '2011-03-04 12:00:00', 'nobody', 0, '2012-01-01 00:00:00', 1, 1);
/*!40000 ALTER TABLE `seld2_users` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_users_contacts
DROP TABLE IF EXISTS `seld2_users_contacts`;
CREATE TABLE IF NOT EXISTS `seld2_users_contacts` (
  `id_user` int(10) unsigned NOT NULL,
  `id_group` int(10) unsigned NOT NULL default '0',
  `value` varchar(250) NOT NULL,
  `flag_visibility` tinyint(1) unsigned NOT NULL default '0',
  UNIQUE KEY `id_user_id_group` (`id_user`,`id_group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Users contacts data';

-- Dumping data for table seld2.seld2_users_contacts: 6 rows
/*!40000 ALTER TABLE `seld2_users_contacts` DISABLE KEYS */;
INSERT INTO `seld2_users_contacts` (`id_user`, `id_group`, `value`, `flag_visibility`) VALUES
	(1, 1, '', 1),
	(1, 6, '', 1),
	(1, 5, '', 1),
	(1, 4, '', 1),
	(1, 3, '', 1),
	(1, 2, '', 1);
/*!40000 ALTER TABLE `seld2_users_contacts` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_users_contacts_groups
DROP TABLE IF EXISTS `seld2_users_contacts_groups`;
CREATE TABLE IF NOT EXISTS `seld2_users_contacts_groups` (
  `id` int(10) unsigned NOT NULL,
  `id_resource` int(10) unsigned NOT NULL default '0',
  `title` varchar(50) NOT NULL,
  `type` enum('text','number','phone','email','url') NOT NULL,
  UNIQUE KEY `id_id_resource` (`id`,`id_resource`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Users contacts groups';

-- Dumping data for table seld2.seld2_users_contacts_groups: 12 rows
/*!40000 ALTER TABLE `seld2_users_contacts_groups` DISABLE KEYS */;
INSERT INTO `seld2_users_contacts_groups` (`id`, `id_resource`, `title`, `type`) VALUES
	(6, 2, 'Фейсбук', 'text'),
	(5, 2, 'Сайт', 'url'),
	(3, 2, 'Skype', 'text'),
	(4, 2, 'ICQ', 'number'),
	(1, 1, 'Phone', 'phone'),
	(2, 1, 'E-mail', 'email'),
	(3, 1, 'Skype', 'text'),
	(4, 1, 'ICQ', 'number'),
	(1, 2, 'Телефон', 'phone'),
	(5, 1, 'Web-site', 'url'),
	(2, 2, 'E-mail', 'email'),
	(6, 1, 'Facebook', 'text');
/*!40000 ALTER TABLE `seld2_users_contacts_groups` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_users_friends
DROP TABLE IF EXISTS `seld2_users_friends`;
CREATE TABLE IF NOT EXISTS `seld2_users_friends` (
  `id_user` int(10) unsigned NOT NULL,
  `id_friend` int(10) unsigned NOT NULL,
  `flag_approved` tinyint(1) unsigned NOT NULL default '0',
  `datetime_creation` datetime NOT NULL,
  UNIQUE KEY `unique relation` (`id_user`,`id_friend`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Users friends relations';

-- Dumping data for table seld2.seld2_users_friends: 0 rows
/*!40000 ALTER TABLE `seld2_users_friends` DISABLE KEYS */;
/*!40000 ALTER TABLE `seld2_users_friends` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_users_groups
DROP TABLE IF EXISTS `seld2_users_groups`;
CREATE TABLE IF NOT EXISTS `seld2_users_groups` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(250) NOT NULL,
  `flag_frontside_access` tinyint(1) NOT NULL default '0',
  `flag_backside_access` int(11) NOT NULL default '0',
  `comments` varchar(500) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE_T` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Users and administrators groups';

-- Dumping data for table seld2.seld2_users_groups: 2 rows
/*!40000 ALTER TABLE `seld2_users_groups` DISABLE KEYS */;
INSERT INTO `seld2_users_groups` (`id`, `title`, `flag_frontside_access`, `flag_backside_access`, `comments`) VALUES
	(0, 'roots group', 1, 1, 'roots group'),
	(1, 'Users', 1, 0, 'Users with no any specific rights.');
/*!40000 ALTER TABLE `seld2_users_groups` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_users_groups_backside_privileges
DROP TABLE IF EXISTS `seld2_users_groups_backside_privileges`;
CREATE TABLE IF NOT EXISTS `seld2_users_groups_backside_privileges` (
  `id_group` int(11) unsigned NOT NULL,
  `id_element` int(11) unsigned NOT NULL,
  `type_element` enum('component','module') NOT NULL,
  `flag_view_access` tinyint(1) NOT NULL default '0',
  `flag_edit_access` tinyint(1) NOT NULL default '0',
  `flag_add_access` tinyint(1) NOT NULL default '0',
  `flag_delete_access` tinyint(1) NOT NULL default '0',
  UNIQUE KEY `UNIQUE` (`id_group`,`id_element`,`type_element`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='Users groups backside privileges';

-- Dumping data for table seld2.seld2_users_groups_backside_privileges: 34 rows
/*!40000 ALTER TABLE `seld2_users_groups_backside_privileges` DISABLE KEYS */;
INSERT INTO `seld2_users_groups_backside_privileges` (`id_group`, `id_element`, `type_element`, `flag_view_access`, `flag_edit_access`, `flag_add_access`, `flag_delete_access`) VALUES
	(0, 1, 'component', 1, 1, 1, 1),
	(0, 2, 'component', 1, 1, 1, 1),
	(0, 3, 'component', 1, 1, 1, 1),
	(0, 4, 'component', 1, 1, 1, 1),
	(0, 5, 'component', 1, 1, 1, 1),
	(0, 6, 'component', 1, 1, 1, 1),
	(0, 7, 'component', 1, 1, 1, 1),
	(0, 8, 'component', 1, 1, 1, 1),
	(0, 9, 'component', 1, 1, 1, 1),
	(0, 10, 'component', 1, 1, 1, 1),
	(0, 11, 'component', 1, 1, 1, 1),
	(0, 12, 'component', 1, 1, 1, 1),
	(0, 13, 'component', 1, 1, 1, 1),
	(0, 14, 'component', 1, 1, 1, 1),
	(0, 15, 'component', 1, 1, 1, 1),
	(1, 2, 'component', 0, 0, 0, 0),
	(1, 11, 'component', 0, 0, 0, 0),
	(1, 16, 'component', 0, 0, 0, 0),
	(1, 9, 'component', 0, 0, 0, 0),
	(1, 7, 'component', 0, 0, 0, 0),
	(1, 5, 'component', 0, 0, 0, 0),
	(1, 10, 'component', 0, 0, 0, 0),
	(1, 12, 'component', 0, 0, 0, 0),
	(1, 14, 'component', 0, 0, 0, 0),
	(1, 6, 'component', 0, 0, 0, 0),
	(1, 13, 'component', 0, 0, 0, 0),
	(1, 15, 'component', 0, 0, 0, 0),
	(1, 3, 'component', 0, 0, 0, 0),
	(1, 1, 'component', 0, 0, 0, 0),
	(1, 4, 'component', 0, 0, 0, 0),
	(0, 16, 'component', 1, 1, 1, 1),
	(1, 8, 'component', 0, 0, 0, 0),
	(0, 1, 'module', 1, 1, 1, 1),
	(1, 1, 'module', 0, 0, 0, 0);
/*!40000 ALTER TABLE `seld2_users_groups_backside_privileges` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_users_groups_frontside_privileges
DROP TABLE IF EXISTS `seld2_users_groups_frontside_privileges`;
CREATE TABLE IF NOT EXISTS `seld2_users_groups_frontside_privileges` (
  `id_group` int(11) unsigned NOT NULL,
  `id_module` int(11) unsigned NOT NULL,
  `flag_access` tinyint(1) NOT NULL default '0',
  UNIQUE KEY `UNIQUE` (`id_group`,`id_module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='Users groups frontside privileges';

-- Dumping data for table seld2.seld2_users_groups_frontside_privileges: 2 rows
/*!40000 ALTER TABLE `seld2_users_groups_frontside_privileges` DISABLE KEYS */;
INSERT INTO `seld2_users_groups_frontside_privileges` (`id_group`, `id_module`, `flag_access`) VALUES
	(0, 1, 1),
	(1, 1, 1);
/*!40000 ALTER TABLE `seld2_users_groups_frontside_privileges` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_users_rating_history
DROP TABLE IF EXISTS `seld2_users_rating_history`;
CREATE TABLE IF NOT EXISTS `seld2_users_rating_history` (
  `id_user` int(11) unsigned NOT NULL,
  `id_critic` int(11) unsigned NOT NULL,
  `id_publication` int(11) unsigned default NULL,
  `points` int(11) NOT NULL,
  `datetime_modification` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Users rating history';

-- Dumping data for table seld2.seld2_users_rating_history: 0 rows
/*!40000 ALTER TABLE `seld2_users_rating_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `seld2_users_rating_history` ENABLE KEYS */;


-- Dumping structure for table seld2.seld2_users_shortcuts
DROP TABLE IF EXISTS `seld2_users_shortcuts`;
CREATE TABLE IF NOT EXISTS `seld2_users_shortcuts` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `id_user` int(11) unsigned NOT NULL,
  `id_shortcut` int(11) unsigned NOT NULL,
  `title` varchar(250) NOT NULL,
  `url` varchar(250) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `UNIQUE` (`url`),
  UNIQUE KEY `UNIQUE_T` (`title`),
  KEY `INDEX` (`id_user`,`id_shortcut`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Users backside shortcuts';

-- Dumping data for table seld2.seld2_users_shortcuts: 0 rows
/*!40000 ALTER TABLE `seld2_users_shortcuts` DISABLE KEYS */;
/*!40000 ALTER TABLE `seld2_users_shortcuts` ENABLE KEYS */;
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
