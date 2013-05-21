<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.list.query
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

if (bl_inBlog($c)){

    require_once cot_langfile('blogger', 'plug');
    // Получить корневые категории блогов
    $bl_rootCats = explode(',', $cfg['plugin']['blogger']['rootCat']);
    foreach ($bl_rootCats as $key => $val){
        $bl_rootCats[$key] = trim($bl_rootCats[$key]);
        if (!isset($structure['page'][$bl_rootCats[$key]])) unset($bl_rootCats[$key]) ;
    }

    // Если мы в корне блогов
    if(in_array($c, $bl_rootCats)){
//        $userBlog = null;
        if ($usr['id'] > 0 && empty($userBlog)) $userBlog = Blog::getByUserId($usr['id']);
    }else{

        if ($currentBlog){
            $R['page_submitnewpage'] = str_replace($L['Submitnew'], $L['blogger']['add_new_page'], $R['page_submitnewpage']);
            $theme_reload['R']['page_submitnewpage'] = str_replace($L['Submitnew'],
                    $L['blogger']['add_new_page'], $theme_reload['R']['page_submitnewpage']);
            $L['Submitnew'] = $L['blogger']['add_new_page'];

            if($usr['id'] > 0 && ($currentBlog->user_id == $usr['id'] && cot_auth('plug', 'blogger', 'W') ) || $usr['isadmin']) {
                $usr['auth_write'] = true;
                // Без консолидации
                cot_rc_link_file($cfg['plugins_dir'].'/blogger/js/blogger_dialog.js');
                cot_rc_link_file($cfg['plugins_dir'].'/blogger/js/blogger.js');
                cot_rc_embed("var blogLocale = {ok: 'Ok', cancel: '{$L['Cancel']}' }");
            }
        }
    }
}
