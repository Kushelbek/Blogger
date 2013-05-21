<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.add.first, page.edit.first
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

$bl_currCat = bl_getCurrCategory();
if (!empty($bl_currCat) && bl_inBlog($bl_currCat)){
    require_once cot_langfile('blogger', 'plug');
    // Получить корневые категории блогов
    $bl_rootCats = explode(',', $cfg['plugin']['blogger']['rootCat']);
    foreach ($bl_rootCats as $key => $val){
        $bl_rootCats[$key] = trim($bl_rootCats[$key]);
        if (!isset($structure['page'][$bl_rootCats[$key]])) unset($bl_rootCats[$key]) ;
    }
    if(!$usr['isadmin']){
        if(in_array($bl_currCat, $bl_rootCats)){
            // В корне блогов нужны специальные права
            cot_block(cot_auth('page', $bl_currCat, 'W'));
        }
    }
    // Если мы внутри каго-нибудь блога
    if(!in_array($bl_currCat, $bl_rootCats)){
        $blog = Blog::getByCategory($bl_currCat);
        if($blog && !$usr['isadmin']){
            // Если есть права на создание записи
            if ($blog->user_id == $usr['id'] && cot_auth('plug', 'blogger', 'W')){
                $usr['auth_write'] = true;
                // На все категории блога дать права на запись
                $bl_Cats = cot_structure_children('page', $bl_currCat, true, true, true);
                foreach($bl_Cats as $bl_cat){
                    $catRigths = cot_auth_getmask($usr["auth"]["page"][$bl_cat]);
                    if(mb_strpos($catRigths, 'W') === false){
                        $catRigths .= 'W';
                        $usr['auth']['page'][$bl_cat] = cot_auth_getvalue($catRigths);
                    }
                }
                if ($cfg['page']['autovalidate'] && cot_auth('plug', 'blogger', '2')) $usr_can_publish = TRUE;
                $L['page_addtitle'] = $L['blogger']['add_new_page'];
            }else{
                $usr['auth_write'] = false;
            }
        }
    }
}
