<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=header.main
[END_COT_EXT]
==================== */
/**
 * Blogger plugin for Cotonti Siena
 * @package Blogger
 * @author Alex - Studio Portal30
 * @copyright Portal30 2010-2013 http://portal30.ru
 */
defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('blogger', 'plug');

if ($cfg['plugin']['blogger']['rssToHeader']){
    require_once cot_langfile('blogger');
    $bl_rootCats = explode(',', $cfg['plugin']['blogger']['rootCat']);
    foreach ($bl_rootCats as $key => $val){
        $bl_rootCats[$key] = trim($bl_rootCats[$key]);
        if (!isset($structure['page'][$bl_rootCats[$key]])) unset($bl_rootCats[$key]) ;
    }
//    $bl_rss = cot_url('rss', 'id='.$bl_rootCats[0]);
//    if (!cot_url_check($bl_rss)) $bl_rss = COT_ABSOLUTE_URL . $bl_rss;
    $bl_rss = COT_ABSOLUTE_URL . "index.php?e=rss&c=pages&id={$bl_rootCats[0]}";
    $out['head_head'] .= "\n".'<link rel="alternate" type="application/rss+xml" title="'.$L['blogger']['RSS_feed'].
        '" href="'.$bl_rss.'" />';
}

if ($usr['id'] > 0){
    if (empty($userBlog)) $userBlog = Blog::getByUserId($usr['id']);
    if (!empty($userBlog)){
        require_once cot_langfile('blogger');

        $usr['blog_url'] = cot_url('page', 'c='.$userBlog->ub_cat);
        $usr['blog_link'] = cot_rc_link($usr['blog_url'], $L['blogger']['myblog']);
    }
}
