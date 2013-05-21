<?php
defined('COT_CODE') or die('Wrong URL');

/**
 * Category Controller class for the Blogger plugin
 *
 * @package Blogger
 * @subpackage Category
 * @author Alex - Studio Portal30
 * @copyright Portal30 2010-2013 http://portal30.ru
 */
class CategoryController{

    /**
     * Main (index) Action.
     */
    public function indexAction(){
        global $t, $L, $cfg, $usr, $sys, $out, $db_users, $db;
        cot_die_message(404, TRUE);
        return "qwerty";

    }

    /**
     * Новая категория
     * @return string
     */
    public function newAction(){
        global $t, $cfg, $out, $L, $structure, $sys, $cot_extrafields, $db_structure, $usr, $db, $db_users,
               $db_auth;

        $cat = cot_import('cat', 'G', 'TXT');
        $bl_rootCats = explode(',', $cfg['plugin']['blogger']['rootCat']);
        foreach ($bl_rootCats as $key => $val){
            $bl_rootCats[$key] = trim($bl_rootCats[$key]);
            if (!isset($structure['page'][$bl_rootCats[$key]])) unset($bl_rootCats[$key]) ;
        }

        if (!$usr['isadmin']){
            $blog = Blog::getByUserId($usr['id']);
        }else{
            $blog = Blog::getByCategory($cat);
        }
        if(!$blog) cot_die_message(404, TRUE);

        if ($blog->user_id != $usr['id'] && !$usr['isadmin']){
            cot_die_message(404, TRUE);
        }
        $blogCats = cot_structure_children('page', $blog->ub_cat);
        // Подразделы можно создавать только в переделах блога ))
        if (!in_array($cat, $blogCats)) $cat = $blog->ub_cat;
        // По идее этого и не надо т.к. предыдущая проверка не пропустит сюда
        if (in_array($cat, array('all','system','unvalidated'))) $cat = $blog->ub_cat;

        // Если нельзя создавать субразделы
        if ($cfg['plugin']['blogger']['subCatsOn'] == 0) {
            cot_redirect(cot_url('message', 'msg=950&' . $sys['url_redirect'], '', true));
            die;
        }

        $act = cot_import('act', 'P', 'ALP', 24);
        // === Сохранение ===
        if ($act == 'save'){
            $catData['structure_title'] = trim(cot_import('rtitle', 'P', 'TXT'));
            $catData['structure_desc'] = cot_import('rdesc', 'P', 'TXT');
            $catData['structure_area'] = 'page';
            // Extra fields
            foreach ($cot_extrafields[$db_structure] as $exfld){
                $catData['structure_'.$exfld['field_name']] = cot_import_extrafields('r'.$exfld['field_name'], $exfld, 'P', $catData['structure_'.$exfld['field_name']]);
            }
            cot_check(empty($catData['structure_title']), $L['blogger']['err_no_cat_name'], 'rtitle');
            if ($_SESSION['blogger']['just_added_cat']['title'] == $catData['structure_title'] &&
                $_SESSION['blogger']['just_added_cat']['parent'] == $cat){

                $error = str_replace(array('{PARENT_TITLE}', '{TITLE}'),
                    array($structure['page'][$cat]['title'], $catData['structure_title']), $L['blogger']['err_same_cat_just_created']);
                cot_error($error);
            }
            if(!cot_error_found()){
                // Подбираем код новой категории
                $catData['structure_code'] = bl_genCategoryCode($catData['structure_title']);
                $catData['structure_path'] = bl_genCategoryPath($cat);
                // Шаблон по-умолчанию как у родителя
                $catData['structure_tpl'] = 'same_as_parent';

                if(cot_structure_add('page', $catData) !== TRUE){
                    cot_error($L['blogger']['err_create_category']);
                    cot_log($L['blogger']['plu_title'].': '.$L['blogger']['err_create_category_short'], 'plg');
                }else{
                    $_SESSION['blogger']['just_added_cat']['title'] = $catData['structure_title'];
                    $_SESSION['blogger']['just_added_cat']['parent'] = $cat;

                    // Установим ACL на новую категорию:
                    $db->update($db_auth, array('auth_rights' => cot_auth_getvalue('R')),
                        "auth_code='page' AND auth_option=? AND auth_groupid NOT IN (1,2,3,5)",
                        array($catData['structure_code']));

                    cot_load_structure();

                    $cat_url = cot_url('page', 'c='.$catData['structure_code']);
                    if (!cot_url_check($cat_url)) $cat_url = COT_ABSOLUTE_URL . $cat_url;
                    $usr_url = cot_url('users', "m=details&id={$usr['id']}&u={$usr['name']}");
                    if (!cot_url_check($usr_url)) $usr_url = COT_ABSOLUTE_URL . $usr_url;

                    // e-mail администратору:
                    if ($cfg['plugin']['blogger']['notifyAdminNewPage'] == 1){
                        // Для уведомлений получим адреса админов
                        $admEmails = $db->query("SELECT user_email FROM $db_users WHERE user_maingrp=5")
                            ->fetchAll(PDO::FETCH_COLUMN);
                        $tmp = trim($cfg['adminemail']);
                        if ($tmp != '') $admEmails[] = $tmp;
                        $admEmails = array_unique($admEmails);

                        $email_title = $L['blogger']['category_created'].' - '.$cfg['mainurl'];
                        $email_body  = $L['User'].' '.cot_rc_link($usr_url, $usr['name']).' ( '.$usr_url.' ), ' .
                            $L['blogger']['category_created2'].": <strong>&laquo;{$catData['structure_title']}&raquo;</strong><br /><br />";
                        $email_body .= $L['blogger']['desc'].":";
                        $email_body .= "<hr />";
                        $email_body .= $catData['structure_desc'];
                        $email_body .= "<hr /><br />";
                        $email_body .= $L['blogger']['category_created3'].":<br />";
                        $email_body .= cot_rc_link($cat_url, $cat_url)."<br /><br />";

                        $tmp = array();
                        foreach($admEmails as $email){
                            if (!in_array($email, $tmp)){
                                cot_mail($email, $email_title, $email_body, '', '', '', true);
                                $tmp[] = $email;
                            }
                        }
                    }
                    cot_log($L['blogger']['plu_title'].'. '.$L['blogger']['category_created'].': «'.
                        $catData['structure_title'].'» ( '.$cat_url.' )    ', 'plg');

                    //Редирект после добавления категории
                    cot_shield_update(30, "New category");
                    cot_message(str_replace('{CAT_TITLE}', $catData['structure_title'], $L['blogger']['msg_category_create_success']));
                    cot_redirect(cot_url('page', 'c='.$catData['structure_code'], '', true));
                    exit;
                }
            }
        }
        // === /Сохранение ===

        $crumbs = cot_structure_buildpath('page', $cat);
        $crumbs[] = $L['blogger']['new_category'];
        $breadcrumbs = cot_breadcrumbs($crumbs, $cfg['homebreadcrumb'], true);

        $out['canonical_uri'] = cot_url('blogger', "m=category&cat={$cat}&a=new");
        $out['subtitle'] = $L['blogger']['new_category'];

        $t = new XTemplate(cot_tplfile('blogger.editcategory', 'plug'));

        // Structure Extra fields
        foreach($cot_extrafields[$db_structure] as $exfld){
            $uname = strtoupper($exfld['field_name']);
            $exfld_val = cot_build_extrafields('r'.$exfld['field_name'], $exfld, $catData['structure_'.$exfld['field_name']]);
            $exfld_title = isset($L['structure_'.$exfld['field_name'].'_title']) ?  $L['structure_'.$exfld['field_name'].'_title'] : $exfld['field_description'];

            $t->assign(array(
                'FORM_'.$uname => $exfld_val,
                'FORM_'.$uname.'_TITLE' => $exfld_title,
                'FORM_EXTRAFLD' => $exfld_val,
                'FORM_EXTRAFLD_TITLE' => $exfld_title
            ));
            // Файлы пользователя
            if (cot_module_active('pfs') && $exfld['field_type'] == 'textarea'){
                $t->assign(array(
                    "FORM_{$uname}_PFS" => cot_build_pfs($usr['id'], 'blogform', 'r'.$exfld['field_name'] ,$L['Mypfs'], $sys['parser']),
                    "FORM_{$uname}_SFS" => (cot_auth('pfs', 'a', 'A')) ? cot_build_pfs(0, 'blogform', 'r'.$exfld['field_name'], $L['SFS'], $sys['parser']) : '',
                ));
            }
            $t->parse('MAIN.FORM.EXTRAFLD');
        }

        $t->assign(array(
//            'FORM_ID' => $blog->ub_id,
            'FORM_ACT' => cot_url('blogger', "m=category&cat={$cat}&a=new"),
            'FORM_TITLE' => cot_inputbox('text', 'rtitle', $catData['structure_title']),
            'FORM_DESC'  => cot_inputbox('text', 'rdesc',  $catData['structure_desc']),
            'BLOG_CAT' => $blog->ub_cat,
            'BLOGGER_ROOT_CAT' => $bl_rootCats[0]
        ));

        $t->parse('MAIN.FORM');

        $t->assign(array(
            'PAGE_TITLE' => $L['blogger']['new_category'],
            'BREADCRUMBS' => $breadcrumbs,
            'NEW_CATEGORY' => 1,
        ));

        cot_display_messages($t);
    }

    /**
     * Редактировать Категорию
     * @return string
     */
    public function editAction(){
        global $t, $cfg, $out, $L, $structure, $sys, $cot_extrafields, $db_structure, $usr, $db, $db_users;

        $cat = cot_import('cat', 'G', 'TXT');
        $cat = trim($cat);
        if (!$cat) cot_die_message(404, TRUE);
        if(empty($structure['page'][$cat])) cot_die_message(404, TRUE);

        $bl_rootCats = explode(',', $cfg['plugin']['blogger']['rootCat']);
        foreach ($bl_rootCats as $key => $val){
            $bl_rootCats[$key] = trim($bl_rootCats[$key]);
            if (!isset($structure['page'][$bl_rootCats[$key]])) unset($bl_rootCats[$key]) ;
        }
        if (!$usr['isadmin']){
            $blog = Blog::getByUserId($usr['id']);
        }else{
            $blog = Blog::getByCategory($cat);
        }
        if(!$blog) cot_die_message(404, TRUE);

        if ($blog->user_id != $usr['id'] && !$usr['isadmin']){
            cot_die_message(404, TRUE);
        }
        $blogCats = cot_structure_children('page', $blog->ub_cat);
        // Подразделы должны принадлежать блогу
        if (!in_array($cat, $blogCats)){
            cot_error($L['Noitemsfound']);
            cot_redirect(cot_url('page', 'c='.$blog->ub_cat, '', true));
        }
        // По идее этого и не надо т.к. предыдущая проверка не пропустит сюда
        if (in_array($cat, array('all','system','unvalidated'))){
            cot_error($L['Noitemsfound']);
            cot_redirect(cot_url('page', 'c='.$blog->ub_cat, '', true));
        }

        // Если нельзя создавать субразделы
        if ($cfg['plugin']['blogger']['subCatsOn'] == 0) {
            cot_redirect(cot_url('message', 'msg=950&' . $sys['url_redirect'], '', true));
            die;
        }

        $act = cot_import('act', 'P', 'ALP', 24);
        // === Сохранение ===
        if ($act == 'save'){
            $catData['structure_title'] = trim(cot_import('rtitle', 'P', 'TXT'));
            $catData['structure_desc'] = cot_import('rdesc', 'P', 'TXT');
            $catData['structure_area'] = 'page';

            $oldCatData = $db->query("SELECT * FROM $db_structure
                    WHERE structure_area='page' AND structure_code='".$db->prep($cat)."' LIMIT 1")->fetch();
            $catData['structure_code'] = $oldCatData['structure_code'];
            // Extra fields
            foreach ($cot_extrafields[$db_structure] as $exfld){
                $catData['structure_'.$exfld['field_name']] = cot_import_extrafields('r'.$exfld['field_name'], $exfld, 'P', $oldCatData['structure_'.$exfld['field_name']]);
            }
            cot_check(empty($catData['structure_title']), $L['blogger']['err_no_cat_name']);

            if(!cot_error_found()){
                // Обновить категорию
                if(cot_structure_update('page', $oldCatData['structure_id'], $oldCatData, $catData) !== TRUE){
                    cot_error('err_edit_category');
                }else{
                    $cat_url = cot_url('page', 'c='.$catData['structure_code']);
                    if (!cot_url_check($cat_url)) $cat_url = COT_ABSOLUTE_URL . $cat_url;
                    $usr_url = cot_url('users', "m=details&id={$usr['id']}&u={$usr['name']}");
                    if (!cot_url_check($usr_url)) $usr_url = COT_ABSOLUTE_URL . $usr_url;

                    // e-mail администратору:
                    if ($cfg['plugin']['blogger']['notifyAdminNewPage'] == 1){
                        // Для уведомлений получим адреса админов
                        $admEmails = $db->query("SELECT user_email FROM $db_users WHERE user_maingrp=5")
                            ->fetchAll(PDO::FETCH_COLUMN);
                        $tmp = trim($cfg['adminemail']);
                        if ($tmp != '') $admEmails[] = $tmp;
                        $admEmails = array_unique($admEmails);

                        $email_title = $L['blogger']['category_edited'].' - '.$cfg['mainurl'];
                        $email_body  = $L['User'].' '.cot_rc_link($usr_url, $usr['name']).' ( '.$usr_url.' ), ' .
                            $L['blogger']['category_edited2'].": <strong>&laquo;{$catData['structure_title']}&raquo;</strong><br /><br />";
                        $email_body .= $L['blogger']['desc'].":";
                        $email_body .= "<hr />";
                        $email_body .= $catData['structure_desc'];
                        $email_body .= "<hr /><br />";
                        $email_body .= $L['blogger']['category_created3'].":<br />";
                        $email_body .= cot_rc_link($cat_url, $cat_url)."<br /><br />";

                        $tmp = array();
                        foreach($admEmails as $email){
                            if (!in_array($email, $tmp)){
                                cot_mail($email, $email_title, $email_body, '', '', '', true);
                                $tmp[] = $email;
                            }
                        }
                    }
                    cot_log($L['blogger']['plu_title'].'. '.$L['blogger']['category_edited'].': «'.
                        $catData['structure_title'].'» ( '.$cat_url.' )    ', 'plg');

                    //Редирект после добавления категории
                    cot_shield_update(30, "Edit category");
                    cot_message(str_replace('{TITLE}', $catData['structure_title'], $L['blogger']['msg_category_edited_success']));
                    cot_redirect(cot_url('page', 'c='.$catData['structure_code'], '', true));
                    exit;
                }
            }

        }else{
            $catData = $db->query("SELECT * FROM $db_structure
                    WHERE structure_area='page' AND structure_code='".$db->prep($cat)."' LIMIT 1")->fetch();
        }

        $crumbs = cot_structure_buildpath('page', $cat);
        $crumbs[] = $L['blogger']['edit_category'];
        $breadcrumbs = cot_breadcrumbs($crumbs, $cfg['homebreadcrumb'], true);

        $out['canonical_uri'] = cot_url('blogger', "m=category&cat={$cat}&a=edit");
        $out['subtitle'] = $L['blogger']['edit_category'];

        $t = new XTemplate(cot_tplfile('blogger.editcategory', 'plug'));

        // Structure Extra fields
        foreach($cot_extrafields[$db_structure] as $exfld){
            $uname = strtoupper($exfld['field_name']);
            $exfld_val = cot_build_extrafields('r'.$exfld['field_name'], $exfld, $catData['structure_'.$exfld['field_name']]);
            $exfld_title = isset($L['structure_'.$exfld['field_name'].'_title']) ?  $L['structure_'.$exfld['field_name'].'_title'] : $exfld['field_description'];

            $t->assign(array(
                'FORM_'.$uname => $exfld_val,
                'FORM_'.$uname.'_TITLE' => $exfld_title,
                'FORM_EXTRAFLD' => $exfld_val,
                'FORM_EXTRAFLD_TITLE' => $exfld_title
            ));
            // Файлы пользователя
            if (cot_module_active('pfs') && $exfld['field_type'] == 'textarea'){
                $t->assign(array(
                    "FORM_{$uname}_PFS" => cot_build_pfs($usr['id'], 'blogform', 'r'.$exfld['field_name'] ,$L['Mypfs'], $sys['parser']),
                    "FORM_{$uname}_SFS" => (cot_auth('pfs', 'a', 'A')) ? cot_build_pfs(0, 'blogform', 'r'.$exfld['field_name'], $L['SFS'], $sys['parser']) : '',
                ));
            }
            $t->parse('MAIN.FORM.EXTRAFLD');
        }

        $t->assign(array(
//            'FORM_ID' => $blog->ub_id,
            'FORM_ACT' => cot_url('blogger', "m=category&cat={$cat}&a=edit"),
            'FORM_TITLE' => cot_inputbox('text', 'rtitle', $catData['structure_title']),
            'FORM_DESC'  => cot_inputbox('text', 'rdesc',  $catData['structure_desc']),
            'BLOG_CAT' => $cat,
            'BLOGGER_ROOT_CAT' => $bl_rootCats[0]
        ));

        $t->parse('MAIN.FORM');

        $t->assign(array(
            'PAGE_TITLE' => $L['blogger']['edit_category'].": ".htmlspecialchars($catData['structure_title']),
            'BREADCRUMBS' => $breadcrumbs,
            'NEW_CATEGORY' => 0,
        ));

        cot_display_messages($t);

    }

    /**
	 * Удаляем категорию (раздел)
	 */
    public function deleteAction(){
        global $structure, $cfg, $L, $usr, $sys, $db_users, $db, $db_pages;

        cot_check_xg();

        // Если нет прав на запись в данную категорию
        if (!$usr['auth_write']) {
            cot_redirect(cot_url('message', 'msg=930&' . $sys['url_redirect'], '', true));
            die;
        }

        $cat = cot_import('cat', 'G', 'TXT');
        $cat = trim($cat);
        if (!$cat) cot_die_message(404, TRUE);
        if(empty($structure['page'][$cat])) cot_die_message(404, TRUE);

        $bl_rootCats = explode(',', $cfg['plugin']['blogger']['rootCat']);
        foreach ($bl_rootCats as $key => $val){
            $bl_rootCats[$key] = trim($bl_rootCats[$key]);
            if (!isset($structure['page'][$bl_rootCats[$key]])) unset($bl_rootCats[$key]) ;
        }

        if (!$usr['isadmin']){
            $blog = Blog::getByUserId($usr['id']);
        }else{
            $blog = Blog::getByCategory($cat);
        }
        if(!$blog) cot_die_message(404, TRUE);

        if ($blog->user_id != $usr['id'] && !$usr['isadmin']){
            cot_die_message(404, TRUE);
        }
        $blogCats = cot_structure_children('page', $blog->ub_cat, true, false, false);

        // Подразделы должны принадлежать блогу
        if (!in_array($cat, $blogCats)){
            cot_error($L['Noitemsfound']);
            cot_redirect(cot_url('page', 'c='.$blog->ub_cat, '', true));
        }
        // По идее этого и не надо т.к. предыдущая проверка не пропустит сюда
        if (in_array($cat, array('all','system','unvalidated'))){
            cot_error($L['Noitemsfound']);
            cot_redirect(cot_url('page', 'c='.$blog->ub_cat, '', true));
        }

        // Проверить на пустую категорию
        $cat_empty = bl_catEmpty($cat);
        if (!$cat_empty && !$cfg['plugin']['blogger']['canDelNotEmptyCat']){
            cot_error($L['blogger']['err_delete_category_notempty']);
            // Редирект на данную категорию
            cot_redirect(cot_url('page', 'c='.$cat, '', true));
            die;
        }

        $title = $structure['page'][$cat]['title'];
        $parents = cot_structure_parents('page', $cat);
        if (end($parents) == $cat){
            unset($parents[count($parents) - 1]);
        }
        $parentCat = end($parents);
        $blog_url = cot_url('page', 'c='.$blog->ub_cat);
        if (!cot_url_check($blog_url)) $blog_url = COT_ABSOLUTE_URL . $blog_url;

        // Получить все категории блога
        $catChildren = cot_structure_children('page', $cat, true, true, false);
        // Получить все страницы
        $sql = $db->query("SELECT * FROM $db_pages WHERE page_cat IN (".implode(',',BlModelAbstract::quote($catChildren)).")");
        $pages = $sql->fetchAll();

        // Удалить все страницы
        if (!empty($pages)){
            foreach($pages as $rpage) {
                cot_page_delete($rpage['page_id'], $rpage);
            }
        }

        // Удалить все категории
        foreach($catChildren as $categ){
            cot_structure_delete('page', $categ);
        }

        //if(cot_structure_delete('page', $cat) !== TRUE){
        if (0){
            cot_error($L['blogger']['err_delete_category']);
            cot_log($L['blogger']['plu_title'].': '.$L['blogger']['err_delete_short'].': «'.$title.'» ( '.
                $blog_url.' )    ', 'plg');
        }else{
            // e-mail администратору:
            if ($cfg['plugin']['blogger']['notifyAdminNewPage'] == 1){
                // Для уведомлений получим адреса админов
                $admEmails = $db->query("SELECT user_email FROM $db_users WHERE user_maingrp=5")
                    ->fetchAll(PDO::FETCH_COLUMN);
                $tmp = trim($cfg['adminemail']);
                if ($tmp != '') $admEmails[] = $tmp;
                $admEmails = array_unique($admEmails);

                $usr_url = cot_url('users', "m=details&id={$usr['id']}&u={$usr['name']}");
                if (!cot_url_check($usr_url)) $usr_url = COT_ABSOLUTE_URL . $usr_url;

                $email_title = $L['blogger']['category_deleted'].' - '.$cfg['mainurl'];

                $email_body  = $L['User'].' '.cot_rc_link($usr_url, $usr['name']).' ( '.$usr_url.' ), ' .
                    $L['blogger']['category_deleted2'].": <strong>&laquo;{$title}&raquo;</strong><br />";
                if (!$cat_empty) $email_body .= $L['blogger']['category_deleted3']."<br /><br />";
                $email_body .= $L['blogger']['blog_url'].":<br />";
                $email_body .= cot_rc_link($blog_url, $blog_url)."<br /><br />";

                $tmp = array();
                foreach($admEmails as $email){
                    if (!in_array($email, $tmp)){
                        cot_mail($email, $email_title, $email_body, '', '', '', true);
                        $tmp[] = $email;
                    }
                }

            }
            cot_log($L['blogger']['plu_title'].'. '.$L['blogger']['category_deleted'].': «'.$title.'» ( '.
                $blog_url.' )    ', 'plg');

            if ($_SESSION['blogger']['just_added_cat']['title'] == $title &&
                $_SESSION['blogger']['just_added_cat']['parent'] == $parentCat){
                unset($_SESSION['blogger']['just_added_cat']);
            }
            //Редирект после добавления категории
            cot_shield_update(30, "Category deleted");

            $msg = str_replace('{CAT_TITLE}', $title, $L['blogger']['msg_category_deleted_success']);
            if (!$cat_empty) $msg .= ' '.$L['blogger']['category_deleted3'];
            cot_message($msg);

            cot_redirect(cot_url('page', 'c='.$parentCat, '', true));
            exit;
        }

    }

}
