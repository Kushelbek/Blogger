<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.tags
Order=8
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
        if($usr['id'] > 0 && $currentBlog->user_id == $usr['id'] && cot_auth('plug', 'blogger', 'W')){
            // Блоггерам (неадминам) тоже сделаем кнопку быстрого удаления
            // todo то же самое и в листы
            $delete_url = cot_url('page', "m=edit&a=update&delete=1&id={$page_data['page_id']}&x={$sys['xk']}");
            $delete_confirm_url = cot_confirm_url($delete_url, 'page', 'page_confirm_delete');
            $t->assign(array(
                'PAGE_ADMIN_DELETE' => cot_rc_link($delete_confirm_url, $L['Delete'], 'class="confirmLink"'),
                'PAGE_ADMIN_DELETE_URL' => $delete_confirm_url,
            ));
        }
    }
}

cot_display_messages($t);
