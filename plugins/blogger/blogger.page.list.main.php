<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.list.main
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

    // Если мы в корне блогов
    if(in_array($c, $bl_rootCats)){
        $allsub1 = cot_structure_children('page', $c, false, false, true, false);
        $subcat1 = array_slice($allsub1, $dc, $cfg['page']['maxlistsperpage']);

        /** @var Blog[] $blogList  */
        $blogList = array();
        if (is_array($subcat1) && count($subcat1) > 0){
            $tmp = Blog::find(array(array('ub_cat', $subcat1)));
            if ($tmp){
                foreach($tmp as $blg){
                    $blogList[$blg->ub_cat] = $blg;
                }
            }
            unset($tmp); // освободим память
        }

    }else{
        if (!isset($currentBlog)) $currentBlog = Blog::getByCategory($c);
    }
}
