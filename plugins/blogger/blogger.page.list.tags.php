<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.list.tags
Tags=page.list.tpl: {LIST_BLOGGER_ROOT}, {LIST_BLOGGER_ADD_URL}, {LIST_BLOGGER_ADD}, {LIST_BLOGGER_DEL_URL}, {LIST_BLOGGER_DEL}, {LIST_ADD_RECORD_URL}, {LIST_ADD_RECORD}, {LIST_GOTO_MYBLOG}, {LIST_GOTO_MYBLOG_URL}, {LIST_BLOGGER_EDIT}, {LIST_BLOGGER_EDIT_URL}, {LIST_BLOGGER_MAIN_PAGE}
Order=8
[END_COT_EXT]
==================== */
/**
 * Blogger plugin for Cotonti Siena
 * @package Blogger
 * @author Alex - Studio Portal30
 * @copyright Portal30 2010-2013 http://portal30.ru
 *
 * Order=8 чтобы вывод сообщений на главной раздела имел приоретет, например над комментариями
 *
 * @var Blog[] $blogList
 * @var Blog   $blog
 * @todo последние записи выводить callback функцией (или хз, надо кешировать)
 */
defined('COT_CODE') or die('Wrong URL');

if (bl_inBlog($c)){
    if(in_array($c, $bl_rootCats)){
        // === Корневая категория блогов ===
        $blogger_add_url = '';
        $blogger_add = '';
        $blogger_add_record_url = '';
        $blogger_add_record = '';
        $goto_myblog = '';
        $goto_myblog_url = '';
        $blogger_list_del_url = '';
        $blogger_list_del = '';
        $can_add_blog = bl_canUserAddBlog();

        //Если пользователь может создать блог,
        // то вывести ссылку на его создание.
        if ($can_add_blog == 1){
            $blogger_add_url = cot_url('blogger', 'a=edit');
            $blogger_add =  cot_rc('blogger_add_blog', array('url' => $blogger_add_url, 'text' => $L['blogger']['add_blog'], 'attr' => ''));
        }elseif($can_add_blog == -1){

            // Если у пользователя есть блог, то:
            // - на главной блогового раздела вывести ссылку "Удалить мой блог"
            $blogger_del_url = cot_url('blogger', "a=del&cat={$userBlog->ub_cat}");
            $blAttr = "title=\"{$L['blogger']['del_my_blog']}: «".strip_tags($structure['page'][$userBlog->ub_cat]['title'])."»\"";
            $blogger_del = cot_rc('blogger_delete_blog', array('url' => $blogger_del_url, 'text' => $L['blogger']['del_my_blog'], 'attr' => $blAttr));

            // - на главной блогового раздела вывести ссылку "Добавить запись"
            $blogger_add_record_url = cot_url('page', 'm=add&c='.$userBlog->ub_cat);
            $blogger_add_record = cot_rc('blogger_add_record', array('url' => $blogger_add_record_url, 'text' => $L['blogger']['add_new_page'], 'attr' => ''));

            // - на главной блогового раздела вывести ссылку "Перейти в мой блог"
            $goto_myblog_url = cot_url('page', 'c='.$userBlog->ub_cat);
            $goto_myblog = cot_rc('blogger_goto_myblog', array('url' => $goto_myblog_url, 'text' => $L['blogger']['goto_myblog'], 'attr' => ''));
            // /Если у пользователя есть блог, то

        }


        $t->assign(array(
            'LIST_BLOGGER_ROOT' => 1,  // Корневой раздел блогов
            'LIST_BLOGGER_ADD_URL' => $blogger_add_url,
            'LIST_BLOGGER_ADD' =>  $blogger_add,
            'LIST_BLOGGER_DEL_URL' => $blogger_del_url,
            'LIST_BLOGGER_DEL' =>  $blogger_del,
            'LIST_ADD_RECORD_URL' => $blogger_add_record_url,
            'LIST_ADD_RECORD' =>  $blogger_add_record,
            'LIST_GOTO_MYBLOG' =>  $goto_myblog,
            'LIST_GOTO_MYBLOG_URL' =>  $goto_myblog_url,
            'LIST_BLOGGER_MAIN_PAGE' => 0,
        ));

    }else{
        // === Внутри какого-нибудь блога ===
        $blogger_add_url = '';
        $blogger_add = '';
        $blogger_list_del_url = '';
        $blogger_list_del = '';
        $blogger_edit_url = '';
        $blogger_edit = '';
        if( ($currentBlog->user_id == $usr['id'] && cot_auth('plug', 'blogger', 'W')) || $usr['isadmin']){
            // Если можно добавлять категории, то вывести ссылку на ее создание.
            if ($cfg['plugin']['blogger']['subCatsOn']){
                $blogger_add_url = cot_url('blogger', "m=category&cat={$c}&a=new");
                $blogger_add = cot_rc('blogger_add_category', array('url' => $blogger_add_url, 'text' => $L['blogger']['add_category'], 'attr' => ''));
            }
            // Если мы в корне пользовательского блока
            //    то выведем ссылки на редактирование и удаление блога
            if ($currentBlog->ub_cat == $c){
                $blogger_del_url = cot_url('blogger', "a=del&cat={$currentBlog->ub_cat}");
                $blAttr = "title=\"{$L['blogger']['del_my_blog']}: «".strip_tags($structure['page'][$currentBlog->ub_cat]['title'])."»\"";
                $blogger_del = cot_rc('blogger_delete_blog', array('url' => $blogger_del_url, 'text' => $L['blogger']['del_my_blog'], 'attr' => $blAttr));

                $blogger_edit_url = cot_url('blogger', 'cat='.$currentBlog->ub_cat.'&a=edit');
                $blAttr = "title=\"{$L['blogger']['edit_blog']}: «".strip_tags($structure['page'][$currentBlog->ub_cat]['title'])."»\"";
                $blogger_edit = cot_rc('blogger_edit_blog', array('url' => $blogger_edit_url, 'text' => $L['blogger']['edit_blog'], 'attr' => $blAttr));

            }else{
                // Если мы не в корне пользовательскго блога, а во вложенных разделах
                //     то вывести ссылки на редактирование и удаление текущего раздела
                $blogger_del_url = cot_url('blogger', "m=category&cat={$c}&a=delete&".cot_xg());

                $tmp_msg = '';
                if (!bl_catEmpty($c) && $cfg['plugin']['blogger']['canDelNotEmptyCat']){
                    $tmp_msg = $L['blogger']['del_category_desc'].'<br />';
                }
                $blAttrArr = array(
                    'title' => "{$L['blogger']['del_category']}: «".strip_tags($structure['page'][$c]['title'])."»",
                    'onclick' => "return bl_confirm('{$L['blogger']['del_category_quest']}: «".
                        strip_tags($structure['page'][$c]['title'])."»?<br /> {$tmp_msg} {$L['blogger']['cant_cansel']}', '".
                        $L['blogger']['del_category']."', '/{$blogger_del_url}', false)"
                );
                $blAttr = cot_rc_attr_string($blAttrArr);
                $blAttr = str_replace('&amp;', '&', $blAttr);

                $blogger_del = cot_rc('blogger_delete_category', array('url' => '#', 'text' => $L['blogger']['del_category'], 'attr' => $blAttr));

                $blogger_edit_url = cot_url('blogger', "m=category&cat={$c}&a=edit");
                $blAttr = "title=\"{$L['blogger']['edit_category']}: «".strip_tags($structure['page'][$c]['title'])."»\"";
                $blogger_edit = cot_rc('blogger_edit_category', array('url' => $blogger_edit_url,
                                                         'text' => $L['blogger']['edit_category'], 'attr' => $blAttr));
            }
        }
        $t->assign(array(
            'LIST_BLOGGER_ADD' =>  $blogger_add,
            'LIST_BLOGGER_ADD_URL' => $blogger_add_url,
            'LIST_BLOGGER_DEL_URL' =>$blogger_del_url,
            'LIST_BLOGGER_DEL' =>  $blogger_del,
            'LIST_BLOGGER_EDIT_URL' => $blogger_edit,
            'LIST_BLOGGER_EDIT' => $blogger_edit,
            'LIST_BLOGGER_MAIN_PAGE' => ($currentBlog->ub_cat == $c) ? 1 : 0,   // Главная страница текущего блога
        ));
    }

    cot_display_messages($t);

}
