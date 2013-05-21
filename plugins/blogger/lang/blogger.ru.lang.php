<?php
/**
 * Blogger plugin for Cotonti Siena
 *   Russian Lang File
 * @package Blogger
 * @author Alex - Studio Portal30
 * @copyright Portal30 2010-2013 http://portal30.ru
 *
 */
defined('COT_CODE') or die('Wrong URL');

/**
 * Plugin Title & Subtitle
 */

$L['info_name'] = $L['blogger']['plu_title'] = 'Блогер';
$L['info_desc'] = 'Плагин блогов для Cotonti. Пользователи сайта могут создавать и вести свои блоги';


$L['an_blogger']['plu_subtitle'] = 'Интернет дневники - блоги на CMS Cotonti.';
$L['an_blogger']['plu_meta_keywords'] = "Блог, дневник, интернет, инетренет дневник";
if ($e == 'an_blogger') $L['plu_title'] = $L['an_blogger']['plu_title'];
/**
 * Plugin Body
 */
$L['blogger']['add_blog'] = 'Создать свой блог';
$L['blogger']['anonimus'] = "Анонимный";
$L['an_blogger']['new_blog'] = 'Новый блог';
$L['blogger']['new_blog_desc'] = 'Для создания своего блога заполните поля ниже';
$L['blogger']['new_blog_desc2'] = 'После создания блога, Вы сможете добавлять в него страницы, фотографии. Название, описание и скин Вы сможете сменить позже.';
$L['blogger']['title'] = 'Название';
$L['blogger']['desc'] = 'Краткое описание';
$L['blogger']['theme'] = 'Тема (обложка)';
$L['blogger']['new_theme'] = 'Выбрать тему (обложку)';
$L['blogger']['blog_created'] = 'Создан новый блог';
$L['blogger']['blog_created2'] = "создал новый блог";
$L['blogger']['blog_deleted'] = 'Блог удален';
$L['blogger']['blog_deleted2'] = "удалил блог";
$L['blogger']['blog_deleted_why'] = 'Причина удаления блога указана следующая';
$L['blogger']['blog_url'] = "Блог находится по адресу";
$L['blogger']['blog_created_success'] = 'Новый блог создан успешно!';
$L['blogger']['blog_created_success_desc'] = 'Ваш блог постоянно доступен по адресу';
$L['blogger']['blog_created_success_desc2'] = 'Зайдя на страницы своего блога Вы можете отредактировать главную страницу, добавить записи, картинки и т.п.<br /><br />Также на главной странице Вашего блога вы можете использовать настройки для изменения обложки, названия и описания Вашего блога!';
$L['blogger']['welcome'] = 'Добро пожаловать!';
$L['blogger']['add_new_page'] = 'Создать новую запись';
$L['blogger']['page_created'] = 'Добавлена новая запись в блог';
$L['blogger']['page_created2'] = "добавил в свой блог новую запись";
$L['blogger']['page_created3'] = "Страница находится по адресу";
$L['blogger']['page_created_log'] = 'Добавлена новая запись';
$L['blogger']['edit_page'] = 'Редактировать запись';
$L['blogger']['page_edited_log'] = 'Отредактирована запись';
$L['blogger']['page_edited'] = 'Отредактирована запись в блоге';
$L['blogger']['page_edited2'] = "отредактировал запись в блоге";
$L['blogger']['page_edited3'] = "Страница находится по адресу";
$L['blogger']['add_category'] = "Создать новый раздел";
$L['blogger']['new_category'] = 'Новый раздел';
$L['blogger']['new_category_desc'] = 'После создания раздела, Вы сможете добавлять в него новые записи.';
$L['blogger']['full_desc'] = "Подробное описание";
$L['blogger']['category_created'] = 'В блог добавлен новый раздел';
$L['blogger']['category_created2'] = "добавил(а) в свой блог новый раздел (категорию)";
$L['blogger']['category_created3'] = "Раздел находится по адресу";
$L['blogger']['category_created_log'] = 'Добавлен новый раздел (категория)';
$L['blogger']['del_category'] = 'Удалить раздел';
$L['blogger']['del_category_quest'] = 'Вы действительно хотите удалить раздел';
$L['blogger']['del_category_desc'] = 'Это действие также удалит все вложенные разделы и записи.';
$L['blogger']['cant_cansel'] = 'Данное действие нельзя отменить.';
$L['blogger']['category_deleted'] = 'Удален раздел блога';
$L['blogger']['category_deleted2'] = "удалил(а) раздел (категорию) из своего блога";
$L['blogger']['category_deleted3'] = "Включая все вложенные разделы и записи.";
$L['blogger']['del_my_blog'] = 'Удалить мой блог';
$L['blogger']['del_blog'] = 'Удалить блог';
$L['blogger']['del_blog_att'] = 'Внимание!!! Вы удаляете блог';
$L['blogger']['del_blog_desc'] = 'Это действие безвозвратно удалит все записи, вложенные разделы, комментарии, если они есть. Данное действие нельзя отменить!';
$L['blogger']['del_blog_why'] = 'Если Вы все же решили удалить Ваш блог, опишите, пожалуйста, причину Вашего решения. Нам очень Важно Ваше мнение.';
$L['blogger']['del_blog_quest'] = 'Вы действительно хотите удалить блог';
$L['blogger']['goto_myblog'] = 'Перейти в мой блог';
$L['blogger']['myblog'] = 'Мой блог';
$L['blogger']['user_blog'] = 'Блог пользователя';
$L['blogger']['edit_blog'] = 'Редактировать блог';
$L['blogger']['comnotify'] = 'Уведомлять о новых комментариях';
$L['blogger']['comnotify_desc'] = 'получать e-mail уведомление о новом комментарии записи в моем блоге';
$L['blogger']['edit_blog_desc'] = 'Измените настройки Вашего блога и нажмите &laquo;'.$L['Submit'].'&raquo;';
$L['blogger']['blog_edited'] = 'Изменены настройки блога';
$L['blogger']['blog_edited2'] = "изменил настройки блога";
$L['blogger']['edit_category'] = 'Редактировать раздел';
$L['blogger']['category_edited'] = 'Раздел отредактирован';
$L['blogger']['category_edited2'] = 'отредактировал раздел блога';
$L['blogger']['RSS_feed'] = 'Последние записи в блогах';
$L['blogger']['recent_pages'] = 'Последние записи в блогах';
$L['an_blogger']['read_more'] = 'Читать полностью';
$L['an_blogger']['hits'] = 'Просмотров';
$L['blogger']['comlive'] = 'Новый комментарий в Вашем блоге';
$L['blogger']['comlive2'] = 'оставил комментарий на Вашу запись в блоге';
$L['blogger']['comlive3'] = 'Запись с комментарием';
$L['an_blogger']['Ok'] = "OK";
$L['an_blogger']['Cancel'] = "OTMEHA";

/**
 * Errors and messages
 */
$L['blogger']['msg_category_create_success'] = 'Новый раздел &laquo;{CAT_TITLE}&raquo; добавлен.';
$L['blogger']['msg_page_create_success'] = 'Новая запись &laquo;{PAGE_TITLE}&raquo; добавлена.';
$L['blogger']['msg_category_deleted_success'] = 'Раздел &laquo;{CAT_TITLE}&raquo; удален.';
$L['blogger']['msg_blog_deleted_success'] = 'Блог &laquo;{TITLE}&raquo; удален.';
$L['blogger']['msg_blog_edited_success'] = 'Настройки блога &laquo;{TITLE}&raquo; сохранены.';
$L['blogger']['msg_category_edited_success'] = 'Раздел &laquo;{TITLE}&raquo; сохранен.';

$L['blogger']['err_blog_alredy_exist'] = 'У Вас уже есть блог. И Вы можете создать в нем новую запись.';
$L['blogger']['err_create_blog'] = 'Ошибка создания блога. Попробуйте повторить операцию позже. Если не удастся, обратитесь к администрации.';
$L['blogger']['err_create_blog_short'] = 'Ошибка создания нового блога.';
$L['blogger']['err_same_cat_just_created'] = 'В разделе &laquo;{PARENT_TITLE}&raquo; недавно уже добавлен подраздел &laquo;{TITLE}&raquo;.';
$L['blogger']['err_no_cat_name'] = 'Раздел должен иметь название.';
$L['blogger']['err_create_category'] = 'Ошибка создания раздела. Попробуйте повторить операцию позднее. Если ошибка будет повторяться, свяжитесь, пожалуйста с администрацией сайта. Приносим свои извинения.';
$L['blogger']['err_create_category_short'] = 'Ошибка создания раздела.';
$L['blogger']['err_delete_category_notempty'] = 'Ошибка удаления раздела. Раздел не пустой!';
$L['blogger']['err_delete_category'] = 'Ошибка удаления раздела. Попробуйте повторить операцию позднее. Если ошибка будет повторяться, свяжитесь, пожалуйста с администрацией сайта. Приносим свои извинения.';
$L['blogger']['err_delete_short'] = 'Ошибка удаления раздела.';
$L['an_blogger']['err_delete_blog'] = 'Ошибка удаления блога. Попробуйте повторить операцию позднее. Если ошибка будет повторяться, свяжитесь, пожалуйста с администрацией сайта. Приносим свои извинения.';
$L['an_blogger']['err_delete_blog_short'] = 'Ошибка удаления блога.';
//$L['an_blogger']['err_delete_blog_short'] = 'err_edit_category.';

/**
 * Admin Part
 */

/**
 * Plugin Config
 */

$L['cfg_rootCat'] = array('Корневая категория блогов', 'Все блоги будут создаваться в этой категории. Пользователи, для которых на этот плагин установлены права на запись, смогут создать в ней свой блог. Один на каждого пользователя.');
$L['cfg_subCatsOn'] = array('Разрешить подкатегории в блогах?', 'Если включено, блогеры смогут создавать подкатегории в своих блогах');
//$L['cfg_catFullTextFld'] = array('Екстраполе категории с полным описанием?', 'тип: textarea; парсинг по умолчанию');
$L['cfg_themeSelectOn'] = array('Разрешить выбор темы?', 'Если включено, блогеры смогут выбирать тему для своего блога');
$L['cfg_turnedOffThemes'] = array('Исключенные темы?', 'Список тем, которые нельза выбрать для своего блога. (Через запятую).');
$L['cfg_thumbsPerLine'] = array('Количество превью тем на строку','Количество превью, отображаемых на одной строке в форме выбора темы.');
$L['cfg_thumbsLinePerPage'] = array('Количество строк превью тем на страницу');
$L['cfg_notifyAdminNewBlog'] = array('Уведомлять администратора о создании новых блогов?', 'Уведомление отсылается на e-mail');
$L['cfg_notifyAdminNewPage'] = array('Уведомлять администратора о новых записях в блогах?', 'Уведомление отсылается на e-mail. Уведомление будет отослано когда пользователи добавляют/удаляют записи или разделы');
//$L['cfg_useCKEditor'] = array('Использовать &laquo;CKEditor&raquo;?', 'Пользователи могут использовать HTML Wysiwyg редактор. Включите только если у Вас на сайте установлен плагин <a href="http://portal30.ru/page.php?al=cotonti_ckeditor" target="_blank">&laquo;CKEditor&raquo;</a>');
$L['cfg_canDelNotEmptyCat'] = array('Разрешить удаление не пустых разделов?', 'Если запрещено, то пользователи не смогут удалить раздел блога если в нем есть вложенные разделы или страницы');
$L['cfg_recentPagesOn'] = array('Выводить последние записи?', 'Последние записи выводятся на корневой категории блога.');
$L['cfg_recentPagesNum'] = array('Количество выводимых последних записей', '');
$L['cfg_rssToHeader'] = array('Вывести ссылку на RSS канал блога', 'Ссылка на RSS канал выводится в теге <header>');
$L['cfg_delBlogWUser'] = array('Удалить блог при удалении владельца?', 'Если включено, то при удалении пользователя-владельца блога блог будет полностью удален со всеми разделами, страницами, коментариями и т.д.');
