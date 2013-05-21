<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=input
[END_COT_EXT]
==================== */
/**
 * Blogger plugin for Cotonti Siena
 * @package Blogger
 * @author Alex - Studio Portal30
 * @copyright Portal30 2010-2013 http://portal30.ru
 */
defined('COT_CODE') or die('Wrong URL');

if (!defined('COT_ADMIN')){

    require_once cot_incfile('blogger', 'plug');

    // Если мы в страницах
    if ($_GET['e'] == 'page'){
        $tmpC = cot_import('c', 'G', 'TXT'); // cat code
        $m = cot_import('m', 'G', 'ALP', 24);
        // Если мы в блогах
        if ($m != 'add' && $m != 'edit' && bl_inBlog($tmpC)){

            require_once cot_langfile('blogger', 'plug');
            // Получить корневые категории блогов
            $bl_rootCats = explode(',', $cfg['plugin']['blogger']['rootCat']);
            foreach ($bl_rootCats as $key => $val){
                $bl_rootCats[$key] = trim($bl_rootCats[$key]);
                if (!isset($structure['page'][$bl_rootCats[$key]])) unset($bl_rootCats[$key]) ;
            }
            // Если мы внутри какого-нибудь блога
            if(!in_array($tmpC, $bl_rootCats)){
                // Оформление блога
                $currentBlog = Blog::getByCategory($tmpC);

                if($currentBlog){
                    if ($cfg['plugin']['blogger']['themeSelectOn'] && !empty($currentBlog->ub_theme)){
                        $usr['theme']  = $currentBlog->ub_theme;
                        $usr['scheme'] = $currentBlog->ub_scheme;
                    }
                }
            }
        }
    }
}
