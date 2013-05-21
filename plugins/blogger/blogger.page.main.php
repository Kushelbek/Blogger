<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.main
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

if (bl_inBlog($pag['page_cat'])){

    if ($currentBlog){
        require_once cot_langfile('blogger', 'plug');
        if($usr['id'] > 0 && ($currentBlog->user_id == $usr['id'] || $usr['isadmin'])) {
            $L['Edit'] = $L['blogger']['edit_page'];
        }
    }
}
