<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=users.details.tags
Tags=users.details.tpl:{USERS_DETAILS_BLOG_URL}, {USERS_DETAILS_BLOG_LINK}
[END_COT_EXT]
==================== */
/**
 * Blogger plugin for Cotonti Siena
 * @package Blogger
 * @author Alex - Studio Portal30
 * @copyright Portal30 2010-2013 http://portal30.ru
 */
defined('COT_CODE') or die('Wrong URL');

// Ссылка на блог пользователя в его прифиле
$blog = Blog::getByUserId($urr['user_id']);
if($blog){
    require_once cot_langfile('blogger');
    $urr['blog_url'] = cot_url('page', 'c='.$blog->ub_cat);
    $urr['blog_link'] = cot_rc_link($urr['blog_url'], $L['blogger']['myblog']);
}

$t->assign(array(
//	"USERS_DETAILS_BLOG" => $urr['blog'],
	"USERS_DETAILS_BLOG_URL" => $urr['blog_url'],
	"USERS_DETAILS_BLOG_LINK" => $urr['blog_link'],
));
