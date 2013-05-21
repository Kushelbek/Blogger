<?php
/**
 * Blogger plugin for Cotonti Siena
 *   English Lang File
 * @package Blogger
 * @author Alex - Studio Portal30
 * @copyright Portal30 2010-2013 http://portal30.ru
 *
 */
defined('COT_CODE') or die('Wrong URL');

/**
 * Plugin Title & Subtitle
 */
$L['info_name'] = $L['blogger']['plu_title'] = 'Blogs';
$L['info_desc'] = 'WebLogs plugin for Cotonti CMF. Users can create their blogs.';

$L['an_blogger']['plu_subtitle'] = 'Weblogs - Cotonti CMS blogs.';
$L['an_blogger']['plu_meta_keywords'] = "Blog, journal, web, weblog";
if ($e == 'an_blogger') $L['plu_title'] = $L['an_blogger']['plu_title'];
/**
 * Plugin Body
 */
$L['blogger']['add_blog'] = 'Create your own blog';
$L['blogger']['anonimus'] = "Guest";
$L['an_blogger']['new_blog'] = 'New blog';
$L['blogger']['new_blog_desc'] = 'To create your own blog please fill the fields below';
$L['blogger']['new_blog_desc2'] = 'After creation of a blog, you will be able to add pages and photos. You can change the title, description and skin later.';
$L['blogger']['title'] = 'Title';
$L['blogger']['desc'] = 'Description';
$L['blogger']['theme'] = 'Theme (layout)';
$L['blogger']['new_theme'] = 'Select theme (layout)';
$L['blogger']['blog_created'] = 'New blog was created';
$L['blogger']['blog_created2'] = "created a new blog";
$L['blogger']['blog_deleted'] = 'The blog was deleted';
$L['blogger']['blog_deleted2'] = "deleted the blog";
$L['blogger']['blog_deleted_why'] = 'The reason of deleting the blog is the following';
$L['blogger']['blog_url'] = "The blog URL is";
$L['blogger']['blog_created_success'] = 'A new blog was created successfully!';
$L['blogger']['blog_created_success_desc'] = 'Your blog is always awailable via URL';
$L['blogger']['blog_created_success_desc2'] = 'By accesing your blog pages you can edit the main page, add entries, images etc.<br /><br />Also on the main page you can use settings to change the theme, title and description of your blog!';
$L['blogger']['welcome'] = 'Welcome!';
$L['blogger']['add_new_page'] = 'Create a new entry';
$L['blogger']['page_created'] = 'A new entry was added to the blog';
$L['blogger']['page_created2'] = "added a new entry to his/her blog";
$L['blogger']['page_created3'] = "The page URL is";
$L['blogger']['page_created_log'] = 'New entry was added';
$L['blogger']['edit_page'] = 'Edit the entry';
$L['blogger']['page_edited_log'] = 'The entry was edited';
$L['blogger']['page_edited'] = 'The blog entry was edited';
$L['blogger']['page_edited2'] = "edited the blog entry";
$L['blogger']['page_edited3'] = "The page URL is";
$L['blogger']['add_category'] = "Create a new category";
$L['blogger']['new_category'] = 'New category';
$L['blogger']['new_category_desc'] = 'After creating a category, you will be able to add new entries to it.';
$L['blogger']['full_desc'] = "Full description";
$L['blogger']['category_created'] = 'A new category was added to the blog';
$L['blogger']['category_created2'] = "added a new category to his/her blog";
$L['blogger']['category_created3'] = "The category url is";
$L['blogger']['category_created_log'] = 'A new category was added';
$L['blogger']['del_category'] = 'Delete the category';
$L['blogger']['del_category_quest'] = 'Do you really want to delete the category';
$L['blogger']['del_category_desc'] = 'This action also deletes all nested categories and entries.';
$L['blogger']['cant_cansel'] = 'This action can not be cancelled.';
$L['blogger']['category_deleted'] = 'The blog category was deleted';
$L['blogger']['category_deleted2'] = "deleted the category from his/her blog";
$L['blogger']['category_deleted3'] = "Including all nested categories and entries.";
$L['blogger']['del_my_blog'] = 'Delete my blog';
$L['blogger']['del_blog'] = 'Delete the blog';
$L['blogger']['del_blog_att'] = 'Attention!!! You are deleting the blog';
$L['blogger']['del_blog_desc'] = 'This action will irretrievably delete all entries, nested categories, comments, if any. This action can\'t be cancelled!';
$L['blogger']['del_blog_why'] = 'If you after all decided to delete your blog, please describe the reason why. We really appreciate your opinion.';
$L['blogger']['del_blog_quest'] = 'Do you really want to delete the blog';
$L['blogger']['goto_myblog'] = 'Go to my blog';
$L['blogger']['myblog'] = 'My blog';
$L['blogger']['user_blog'] = 'User blog';
$L['blogger']['edit_blog'] = 'Edit the blog';
$L['blogger']['comnotify'] = 'Notify me of new comments';
$L['blogger']['comnotify_desc'] = 'receive e-mail notifications of new comments to my blog entry';
$L['blogger']['edit_blog_desc'] = 'Change your blog settings and click &laquo;'.$L['Submit'].'&raquo;';
$L['blogger']['blog_edited'] = 'The blog settings were changed';
$L['blogger']['blog_edited2'] = "changed the blog settings";
$L['blogger']['edit_category'] = 'Edit the category';
$L['blogger']['category_edited'] = 'The category was edited';
$L['blogger']['category_edited2'] = 'edited the blog category';
$L['blogger']['RSS_feed'] = 'Recent blog entries';
$L['blogger']['recent_pages'] = 'Recent blog pages';
$L['an_blogger']['read_more'] = 'Read more';
$L['an_blogger']['hits'] = 'Number of hits';
$L['blogger']['comlive'] = 'New comment to your blog';
$L['blogger']['comlive2'] = 'left a comment to your blog entry';
$L['blogger']['comlive3'] = 'Entry with a comment';
$L['an_blogger']['Ok'] = "OK";
$L['an_blogger']['Cancel'] = "CANCEL";

/**
 * Errors and messages
 */
$L['blogger']['msg_category_create_success'] = 'New category &laquo;{CAT_TITLE}&raquo; was added.';
$L['blogger']['msg_page_create_success'] = 'A new entry &laquo;{PAGE_TITLE}&raquo; was added.';
$L['blogger']['msg_category_deleted_success'] = 'The category &laquo;{CAT_TITLE}&raquo; was deleted.';
$L['blogger']['msg_blog_deleted_success'] = 'The blog &laquo;{TITLE}&raquo; was deleted.';
$L['blogger']['msg_blog_edited_success'] = 'The blog settings &laquo;{TITLE}&raquo; were saved.';
$L['blogger']['msg_category_edited_success'] = 'The category &laquo;{TITLE}&raquo; was saved.';

$L['blogger']['err_blog_alredy_exist'] = 'You already have the blog. You can create a new entry in it.';
$L['blogger']['err_create_blog'] = 'Could not create a blog. Please try again later. If you do not succeed, please contact the site administrator.';
$L['blogger']['err_create_blog_short'] = 'Could not create a new blog.';
$L['blogger']['err_same_cat_just_created'] = 'In the category &laquo;{PARENT_TITLE}&raquo; the subcategory &laquo;{TITLE}&raquo; was already added.';
$L['blogger']['err_no_cat_name'] = 'The category should have a name.';
$L['blogger']['err_create_category'] = 'Could not create a category. Try again later. If the problem persists, please
    contact the site administrator. We make our apologies.';
$L['blogger']['err_create_category_short'] = 'Could not create a category.';
$L['blogger']['err_delete_category_notempty'] = 'Could not delete the category. The category is not empty!';
$L['blogger']['err_delete_category'] = 'Could not delete the category. Try again later. If the problem persists, please contact the site administrator. We make our apologies.';
$L['blogger']['err_delete_short'] = 'Could not delete the category.';
$L['an_blogger']['err_delete_blog'] = 'Could not delete the blog. Try again later. If the problem persists, please contact the site administrator. We make our apologies.';
$L['an_blogger']['err_delete_blog_short'] = 'Could not delete the blog.';
//$L['an_blogger']['err_delete_blog_short'] = 'err_edit_category.';

/**
 * Admin Part
 */

/**
 * Plugin Config
 */

$L['cfg_rootCat'] = array('Root blog category', 'All blogs will be created in this category. The users who have the right to write in this plugin can create their blogs in it, one for each user.');
$L['cfg_subCatsOn'] = array('Allow subcategories in blogs?', 'If enabled, the bloggers will be able to create subcategories in their blogs');
//$L['cfg_catFullTextFld'] = array('Category full (html) description extrafield?', 'type: textarea; parse: default');
$L['cfg_themeSelectOn'] = array('Allow theme selection?', 'If enabled, the users can select theme for their blogs');
$L['cfg_turnedOffThemes'] = array('Excluded themes', 'List of themes, that are not allowed to be selected for a blog. (Separated by comma).');
$L['cfg_thumbsPerLine'] = array('The number of theme previews per line','Number of theme previews displayed per line in the selection theme form.');
$L['cfg_thumbsLinePerPage'] = array('The number of theme preview lines per page');
$L['cfg_notifyAdminNewBlog'] = array('Notify the administrator of new blogs creation?', 'The notification is sent to e-mail');
$L['cfg_notifyAdminNewPage'] = array('Notify the administrator of new blog entries?', 'The notification is sent to e-mail. The notification is sent when users add/delete entries or categories');
//$L['cfg_useCKEditor'] = array('Use &laquo;CKEditor&raquo;?', 'Users can use HTML Wysiwyg editor. Enable if only you have the plugin <a href="http://portal30.ru/page.php?al=cotonti_ckeditor" target="_blank">&laquo;CKEditor&raquo;</a> installed on your site');
$L['cfg_canDelNotEmptyCat'] = array('Allow deleting categories which are not empty?', 'If disabled, users will not be able to delete a blog category if it includes nested categories or pages');
$L['cfg_recentPagesOn'] = array('Display recent entries?', 'Recent entries are displayed in the blog root category.');
$L['cfg_recentPagesNum'] = array('The number of recent entries displayed', '');
$L['cfg_rssToHeader'] = array('Display the link to the RSS blog feed', 'Link to the RSS feed is displayed in <header> tag');
$L['cfg_delBlogWUser'] = array('Delete the blog when deleting its owner?', 'If enabled, the blog will be deleted completely with all its categories, pages, comments etc when deleting its owner.');
