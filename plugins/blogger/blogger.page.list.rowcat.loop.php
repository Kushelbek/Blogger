<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.list.rowcat.loop
Tags=page.list.tpl:{LIST_ROWCAT_DEL_URL}, {LIST_ROWCAT_DEL}, {LIST_ROWCAT_EDIT_URL}, {LIST_ROWCAT_EDIT}, {LIST_BLOGGER_ROOT}
[END_COT_EXT]
==================== */
/**
 * Blogger plugin for Cotonti Siena
 * @package Blogger
 * @author Alex - Studio Portal30
 * @copyright Portal30 2010-2013 http://portal30.ru
 *
 * @var Blog[] $blogList
 * @var Blog   $blog
 */
defined('COT_CODE') or die('Wrong URL');

if (bl_inBlog($c)){

    $bl_del_url = '';
    $bl_edit_url = '';
    $bl_del = '';
    $bl_edit = '';

    // Если мы в корне блога
    if(in_array($c, $bl_rootCats)){
        if (!empty($blogList[$x])){
            $t->assign(cot_generate_usertags($blogList[$x]->owner, 'LIST_ROWCAT_OWNER_'));
        }
        // Права на редактирование
        if( ($blogList[$x]->user_id == $usr['id'] && cot_auth('plug', 'blogger', 'W') ) || $usr['isadmin']){
            $bl_del_url  = cot_url('blogger', "a=del&cat={$x}");
            $blAttr = "title=\"{$L['blogger']['del_my_blog']}: «".strip_tags($structure['page'][$x]['title'])."»\"";
            $bl_del = cot_rc('blogger_row_delete_blog', array('url' => $bl_del_url, 'text' => $L['Delete'], 'attr' => $blAttr));

            $bl_edit_url = cot_url('blogger', 'cat='.$x.'&a=edit');
            $blAttr = "title=\"{$L['blogger']['edit_blog']}: «".strip_tags($structure['page'][$blogList[$x]->ub_cat]['title'])."»\"";
            $bl_edit = cot_rc('blogger_row_edit_blog', array('url' => $bl_edit_url, 'text' => $L['Edit'], 'attr' => $blAttr));
        }
    }else{
        // Права на редактирование
        if( ($currentBlog->user_id == $usr['id'] && cot_auth('plug', 'blogger', 'W') ) || $usr['isadmin']){

            $bl_del_url = cot_url('blogger', "m=category&cat={$x}&a=delete&".cot_xg());
            $tmp_msg = '';
            if (!bl_catEmpty($x) && $cfg['plugin']['blogger']['canDelNotEmptyCat']){
                $tmp_msg = $L['blogger']['del_category_desc'].'<br />';
            }
            $blAttrArr = array(
                'title' => "{$L['blogger']['del_category']}: «".strip_tags($structure['page'][$x]['title'])."»",
                'onclick' => "return bl_confirm('{$L['blogger']['del_category_quest']}: «".
                    strip_tags($structure['page'][$x]['title'])."»?<br /> {$tmp_msg} {$L['blogger']['cant_cansel']}', '".
                    $L['blogger']['del_category']."', '/{$bl_del_url}', false)"
            );
            $blAttr = cot_rc_attr_string($blAttrArr);
            $blAttr = str_replace('&amp;', '&', $blAttr);
            $bl_del = cot_rc('blogger_row_delete_category', array('url' => '#', 'text' => $L['Delete'], 'attr' => $blAttr));

            $bl_edit_url = cot_url('blogger', "m=category&cat={$x}&a=edit");
            $blAttr = "title=\"{$L['blogger']['edit_category']}: «".strip_tags($structure['page'][$x]['title'])."»\"";
            $bl_edit = cot_rc('blogger_row_edit_category', array('url' => $bl_edit_url, 'text' => $L['Edit'], 'attr' => $blAttr));
        }
    }


    $t->assign(array(
        'LIST_BLOGGER_ROOT' => in_array($c, $bl_rootCats),
        'LIST_ROWCAT_DEL_URL' => $bl_del_url,
        'LIST_ROWCAT_DEL' => $bl_del,
        'LIST_ROWCAT_EDIT_URL' => $bl_edit_url,
        'LIST_ROWCAT_EDIT' => $bl_edit,
    ));

}