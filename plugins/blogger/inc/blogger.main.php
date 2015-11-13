<?php
defined('COT_CODE') or die('Wrong URL');

/**
 * Main Controller class for the Blogger plugin
 *
 * @package Blogger
 * @author Alex - Studio Portal30
 * @copyright Portal30 2010-2013 http://portal30.ru
 */
class MainController{

    /**
     * Main (index) Action.
     * Объявления пользователя
     */
    public function indexAction(){
        global $t, $L, $cfg, $usr, $sys, $out, $db_users, $db;
        cot_die_message(404, TRUE);
        return "qwerty";

    }

    /**
     * Редактировать блог
     * Для идентификации используем категорию, наверное
     * @return string
     */
    public function editAction(){
        global $t, $cfg, $out, $L, $structure, $sys, $cot_extrafields, $db_structure, $usr, $db, $db_users,
               $db_auth;

        Resources::linkFileFooter("{$cfg["plugins_dir"]}/blogger/js/blogger.js");

        $cat = cot_import('cat', 'G', 'TXT');
        $bl_rootCats = explode(',', $cfg['plugin']['blogger']['rootCat']);
        foreach ($bl_rootCats as $key => $val){
            $bl_rootCats[$key] = trim($bl_rootCats[$key]);
            if (!isset($structure['page'][$bl_rootCats[$key]])) unset($bl_rootCats[$key]) ;
        }

        $crumbs = array(array(cot_url('page', "c={$bl_rootCats[0]}"), $structure['page'][$bl_rootCats[0]]['title']));

        $act = cot_import('act', 'P', 'ALP', 24);

        if(!$cat){
            $cat = '';
            $can_add_blog = bl_canUserAddBlog();
            if ($can_add_blog == -1){
                cot_error($L['blogger']['err_blog_alredy_exist']);
                cot_redirect($bl_rootCats[0]);
            }elseif($can_add_blog == 0){
                cot_redirect(cot_url('message', 'msg=930&' . $sys['url_redirect'], '', true));
            }
            $crumbs[] = $L['blogger']['add_blog'];
            $out['subtitle'] = $L['blogger']['add_blog'];
            $bl['ub_theme'] = $cfg['defaulttheme'];
            $bl['ub_scheme'] = (!empty($cfg['defaultscheme'])) ? $cfg['defaultscheme'] : '';
            $bl['ub_comnotify'] = 1;    // по-умолчанию активно
        }else{
            if ($cat == 'all' || $cat == 'system') {
                list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('admin', 'a');
                cot_block($usr['isadmin']);
            }elseif(!isset($structure['page'][$cat])){
                cot_die_message(404, TRUE);
            }else{
                cot_block(cot_auth('plug', 'blogger', 'W'));
            }
            // $bl и $catData заполняем из базы, если нам не надо сейчас же их и сохранить.
            if ($act != 'save'){
                $blog = Blog::getByCategory($cat);
                if (!$blog){
                    cot_die_message(404, TRUE);
                }
                $bl = $blog->toArray();
                $cat = $blog->ub_cat;
                if (!$usr['isadmin'] && $blog->user_id != $usr['id']){
                    cot_redirect(cot_url('message', 'msg=930&' . $sys['url_redirect'], '', true));
                    return;
                }
                $catData = $db->query("SELECT * FROM $db_structure
                    WHERE structure_area='page' AND structure_code='".$db->prep($blog->ub_cat)."' LIMIT 1")->fetch();

                $crumbs[] = array(cot_url('page', "c={$blog->ub_cat}"), $structure['page'][$blog->ub_cat]['title']);
            }
            $crumbs[] = $L['blogger']['edit_blog'];
            $out['subtitle'] = $L['blogger']['edit_blog'].": ".
                htmlspecialchars($structure['page'][$cat]['title']);

            $thumb = '';
            $themeTitle = '';
            $schemeTitle = '';
            if ($cfg['plugin']['blogger']['themeSelectOn'] == 1) {
                if (file_exists($cfg['themes_dir'].'/'.$blog->ub_theme.'/thumbnail_'.$blog->ub_scheme.'.png')){
                    $thumb = $cfg['themes_dir'].'/'.$blog->ub_theme.'/thumbnail_'.$blog->ub_scheme.'.png';
                }elseif (file_exists($cfg['themes_dir'].'/'.$blog->ub_theme.'/thumbnail.png')){
                    $thumb = $cfg['themes_dir'].'/'.$blog->ub_theme.'/thumbnail.png';
                }else{
                    $thumb = cot_rc('blogger_no_thumbnail');
                }

                $themeinfo = "{$cfg['themes_dir']}/{$blog->ub_theme}/{$blog->ub_theme}.php";
                $info = cot_infoget($themeinfo, 'COT_THEME');
                if ($info){
                    if (empty($info['Schemes'])){
                        $schemes['default'] = $info['Name'];
                    }else{
                        $sch = explode(',', $info['Schemes']);
                        sort($sch);
                        foreach ($sch as $sc){
                            $sc = explode(':', $sc);
                            $schemes[$sc[0]] = (!empty($sc[1])) ? $sc[1] : $sc[0];
                        }
                    }
//                    $info['Name'];
                }else{
                    $schemes['default'] = $blog->ub_theme;
                    $info['Name'] = $blog->ub_theme;
                }
                $themeTitle = !empty($info['Name']) ? $info['Name'] : $blog->ub_theme;
                $schemeTitle = (!empty($schemes[$blog->ub_scheme])) ? $schemes[$blog->ub_scheme] : $blog->ub_scheme;
            }

        }

        // === Сохранение ===
        if ($act == 'save'){
            $bl = array();
            $catData = array();
            $bl['ub_id'] = cot_import('rid', 'P', 'INT');
            $bl['ub_comnotify'] = cot_import('rcomnotify', 'P', 'BOL');
            $bl['ub_theme'] = cot_import('rtheme', 'P', 'TXT');
            $bl['ub_scheme'] = cot_import('rscheme', 'P', 'TXT');
            $bl['user_id'] = $usr['id'];

            $catData['structure_title'] = trim(cot_import('rtitle', 'P', 'TXT'));
            $catData['structure_title'] = (!empty($catData['structure_title'])) ? $catData['structure_title'] : $usr['name'];
            $catData['structure_desc'] = cot_import('rdesc', 'P', 'TXT');
            $catData['structure_area'] = 'page';
            // Extra fields
            foreach ($cot_extrafields[$db_structure] as $exfld){
                $catData['structure_'.$exfld['field_name']] = cot_import_extrafields('r'.$exfld['field_name'], $exfld, 'P', $catData['structure_'.$exfld['field_name']]);
            }

            if (!$bl['ub_id']){
                // Подбираем код новой категории
                $catData['structure_code'] = bl_genCategoryCode();
                $catData['structure_path'] = bl_genCategoryPath();
                // Шаблон по-умолчанию как у родителя
                $catData['structure_tpl'] = 'same_as_parent';

                if(cot_structure_add('page', $catData) !== TRUE){
                    cot_error($L['blogger']['err_create_blog']);
                }else{
                    $bl['ub_cat'] = $catData['structure_code'];
                    // Установим ACL на новую категорию:
                    $db->update($db_auth, array('auth_rights' => cot_auth_getvalue('R')),
                        "auth_code='page' AND auth_option=? AND auth_groupid NOT IN (1,2,3,5)",
                        array($catData['structure_code']));
                    cot_load_structure();
                }
            }else{
                // Получить старые данные категории
                $tmp = Blog::getById($bl['ub_id']);
                if (!$tmp){
                    cot_die_message(404, TRUE);
                }
                if (!$usr['isadmin'] && $tmp->user_id != $usr['id']){
                    cot_redirect(cot_url('message', 'msg=930&' . $sys['url_redirect'], '', true));
                    return;
                }
                $oldCatData = $db->query("SELECT * FROM $db_structure
                    WHERE structure_area='page' AND structure_code='".$db->prep($tmp->ub_cat)."' LIMIT 1")->fetch();
                $catData['structure_code'] = $oldCatData['structure_code'];
                // Обновить категорию
                if(cot_structure_update('page', $oldCatData['structure_id'], $oldCatData, $catData) !== TRUE){
                    cot_error($L['blogger']['err_create_blog']);
                }
                $bl['ub_cat'] = $tmp->ub_cat;
            }
            if(!cot_error_found()){
                $blog = new Blog($bl);
                if ($id = $blog->save()){
                    $usr_url = cot_url('users', "m=details&id={$usr['id']}&u={$usr['name']}");
                    if (!cot_url_check($usr_url)) $usr_url = COT_ABSOLUTE_URL . $usr_url;
                    $blog_url = cot_url('page', array('c'=>$bl['ub_cat']));
                    if (!cot_url_check($blog_url)) $blog_url = COT_ABSOLUTE_URL . $blog_url;

                    if (!$bl['ub_id']){
                        // Новый блог
                        if (function_exists('idn_to_utf8')){
                            $murl = str_replace('http://', '', $cfg['mainurl']);
                            $hurl = idn_to_utf8($murl);
                            $blogger_url = str_replace($murl, $hurl, $blog_url);
                        }else{
                            $blogger_url = $blog_url;
                        }
                        $msg = "<strong>".$L['blogger']['blog_created_success']."<br /><br />";
                        $msg.= $L['blogger']['blog_created_success_desc'].":<br />{$blogger_url}<br /><br />";
                        $msg.= $L['blogger']['blog_created_success_desc2']."<br /><br />";
                        $msg.= cot_rc_link( cot_url('page', array('c'=>$bl['ub_cat'])) , $L['blogger']['welcome'] );
                        cot_message($msg);

                        // Уведомление
                        if ($cfg['plugin']['blogger']['notifyAdminNewBlog'] == 1){
                            $email_title = $L['blogger']['blog_created'].' - '.$cfg['mainurl'];
                            $email_body  = $L['User'].' '.cot_rc_link($usr_url, $usr['name']).' ( '.$usr_url.' ), '.
                                $L['blogger']['blog_created2'].": <strong>&laquo;{$catData['structure_title']}&raquo;</strong><br /><br />";
                        }

                        // логирование
                        cot_log($L['blogger']['plu_title'].'. '.$L['blogger']['blog_created'].': «'.$catData['structure_title'].
                                '» ( '.$cfg['mainurl'].'/'.cot_url('page', 'c='.$catData['structure_code']).' )    ', 'plg');
                        cot_shield_update(30, "New category");

                    }else{
                        // Сохранили существующий
                        cot_message(str_replace('{TITLE}', $catData['structure_title'], $L['blogger']['msg_blog_edited_success']));
                        // начало Уведомления
                        if ($cfg['plugin']['blogger']['notifyAdminNewBlog'] == 1){
                            $email_title = $L['blogger']['blog_edited'].': '.$catData['structure_title'].' - '.$cfg['mainurl'];
                            $email_body  = $L['User'] .' ' . cot_rc_link($usr_url, $usr['name']).' ( '.$usr_url.' ), ' .
                                $L['blogger']['blog_edited2'].": <strong>&laquo;{$catData['structure_title']}&raquo;</strong><br /><br />";
                        }
                        // логирование
                        cot_log($L['blogger']['plu_title'].'. '.$L['blogger']['blog_edited'].': «'.$catData['structure_title'].
                            '» ( '.$blog_url.' )    ', 'plg');

                    }

                    // Отправка уведомления
                    if ($cfg['plugin']['blogger']['notifyAdminNewBlog'] == 1){
                        // Для уведомлений получим адреса админов
                        $admEmails = $db->query("SELECT user_email FROM $db_users WHERE user_maingrp=5")
                            ->fetchAll(PDO::FETCH_COLUMN);
                        $tmp = trim($cfg['adminemail']);
                        if ($tmp != '') $admEmails[] = $tmp;
                        $admEmails = array_unique($admEmails);

                        $email_body = $L['blogger']['desc'].":";
                        $email_body .= "<hr />";
                        $email_body .= $catData['structure_desc'];
                        $email_body .= "<hr /><br />";
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
                    // Перенаправление на страницы блога
                    cot_redirect(cot_url('page', array('c'=>$bl['ub_cat']), '', true));

                }else{
                    cot_log($L['blogger']['plu_title'].': '.$L['blogger']['err_create_blog_short'], 'plg');
                    cot_error($L['blogger']['err_create_blog']);
                }
            }else{
                cot_log($L['blogger']['plu_title'].': '.$L['blogger']['err_create_blog_short'], 'plg');
            }
        }
        // === /Сохранение ===

        if(!$cat){
            $out['canonical_uri'] = cot_url('blogger', 'a=edit');
        }else{
            $out['canonical_uri'] = cot_url('blogger', "cat={$cat}&a=edit");
        }

        $breadcrumbs = cot_breadcrumbs($crumbs, $cfg['homebreadcrumb'], true);

        $comnotyfy = 0;
        if (cot_plugin_active('comments')){
            $comnotyfy = cot_radiobox($bl['ub_comnotify'], 'rcomnotify', array(1,0), array($L['Yes'], $L['No']));
        }

        $t = new XTemplate(cot_tplfile('blogger.editblog', 'plug'));

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
            'FORM_ID' => $blog->ub_id,
            'FORM_ACT' => (!empty($blog)) ? cot_url('blogger', "cat={$blog->ub_cat}&a=edit") : cot_url('blogger', "a=edit"),
            'FORM_COMNOTIFY' => $comnotyfy,
            'FORM_TITLE' => cot_inputbox('text', 'rtitle', $catData['structure_title']),
            'FORM_DESC'  => cot_inputbox('text', 'rdesc',  $catData['structure_desc']),
            'FORM_SELECT_THEME' => ($cfg['plugin']['blogger']['themeSelectOn'] == 1) ?
                $this->themesSelectAction(array('a' => 'edit')) : '',
            'FORM_CURR_THEME'  => $bl['ub_theme'],
            'FORM_CURR_SCHEME' => $bl['ub_scheme'],
            'FORM_CURR_THEME_THUMB'  => $thumb,
            'FORM_CURR_THEME_TITLE'  =>  $themeTitle,
            'FORM_CURR_SCHEME_TITLE' => $schemeTitle,
            'BLOG_CAT' => $bl['ub_cat'],
            'BLOGGER_ROOT_CAT' => $bl_rootCats[0]
        ));

        $t->parse('MAIN.FORM');

        $t->assign(array(
            'PAGE_TITLE' => isset($blog->ub_id) ? $L['blogger']['edit_blog'].": ".
                htmlspecialchars($structure['page'][$cat]['title']) : $L['blogger']['add_blog'],
            'BREADCRUMBS' => $breadcrumbs,
        ));

        cot_display_messages($t);

//        return "qwerty";
    }

    /**
     * Renders skin select table
     */
    function themesSelectAction($list_url_path = ''){
        global $cfg, $L, $a;

        //Список запрещенных скинов
        $offSkins = explode(',', $cfg['plugin']['blogger']['turnedOffThemes']);
        foreach($offSkins as $key => $value){
            $offSkins[$key] = trim($value);
        }
        $offSkins['admin'];
        $tpl = new XTemplate(cot_tplfile('blogger', true));

        $tpl->assign(array(
            "SS_BLOCK_WIDTH" => (int)(99 / $cfg['plugin']['blogger']['thumbsPerLine']),
        ));

        $blocksPerPage = $cfg['plugin']['blogger']['thumbsPerLine'] * $cfg['plugin']['blogger']['thumbsLinePerPage'];
        list($pg, $d, $durl) = cot_import_pagenav('d', $blocksPerPage);     //page number for thumbs list


        $handle = opendir($cfg['themes_dir']);
        $themeList = array();
        $i=0;
        while (false !== ($f = readdir($handle)) ){
            if (mb_strpos($f, '.') === FALSE && is_dir($cfg['themes_dir'].'/' . $f)){
                if (in_array($f, $offSkins)) continue;
                if (!file_exists("{$cfg['themes_dir']}/{$f}/header.tpl")) continue;
                $themeinfo = "{$cfg['themes_dir']}/{$f}/{$f}.php";

                $themeList[$f]['code'] = $f;
                $info = cot_infoget($themeinfo, 'COT_THEME');

                $schemes = array();
                if ($info){
                    if (empty($info['Schemes'])){
                        $schemes['default'] = $info['Name'];
                    }else{
                        $sch = explode(',', $info['Schemes']);
                        sort($sch);
                        foreach ($sch as $sc){
                            $sc = explode(':', $sc);
                            $schemes[$sc[0]] = (!empty($sc[1])) ? $sc[1] : $sc[0];
                        }
                    }
                    $themeList[$f]['name'] = $info['Name'];
                }else{
                    $schemes['default'] = $f;
                    $themeList[$f]['name'] = $f;
                }
                $themeList[$f]['schemes'] = $schemes;
                foreach ($schemes as $key => $value){
                    $i++;
                    if ($i-1 < $d || ($i > $d + $blocksPerPage)) continue;

                    if (file_exists("{$cfg['themes_dir']}/{$f}/thumbnail_{$key}.png")){
                        $thumb = "{$cfg['themes_dir']}/{$f}/thumbnail_{$key}.png";
                    }elseif (file_exists("{$cfg['themes_dir']}/{$f}/thumbnail.png")){
                        $thumb = "{$cfg['themes_dir']}/{$f}/thumbnail.png";
                    }else{
                        $thumb = cot_rc('blogger_no_thumbnail');
                    }
                    $themeList[$f]['thumbs'][$key] = $thumb;

                    $tpl->assign(array(
                        "SS_ROW_THEME_CODE" => $f,
                        "SS_ROW_THEME_TITLE" => $themeList[$f]['name'],
                        "SS_ROW_SCHEME_CODE" => $key,
                        "SS_ROW_SCHEME_TITLE" => $value,
                        "SS_ROW_STRING_COMPL" => ( ($i/$cfg['plugin']['blogger']['thumbsPerLine']) == (int) ($i/$cfg['plugin']['blogger']['thumbsPerLine']) ) ? 1 : 0,
                        "SS_ROW_THUMB" => $thumb,
                        "SS_ROW_BLOCK_ID" => $f.'_'.$key,
                        "SS_ROW_ODDEVEN" => cot_build_oddeven($i),
                    ));
                    $tpl->parse("SELECT_SKIN.SELECT_SKIN_ROW");

                }
            }
        }
        $totallines = $i;

        if (empty($list_url_path)){
            $list_url_path = array('a' => $a);
        }

        $pagenav = cot_pagenav('blogger', $list_url_path, $d, $totallines, $blocksPerPage, 'd', '', true,
            'blogger_new_theme', 'blogger', array('a'=>'themesSelect'));

        $tpl->assign(array(
            'SS_PAGEPREV' => $pagenav['prev'],
            'SS_PAGENEXT' => $pagenav['next'],
            'SS_PAGNAV'   => $pagenav['main'],
            'SS_AJAX'     => (COT_AJAX) ? 1 : 0,
        ));

        $tpl->parse("SELECT_SKIN");

        if(COT_AJAX){
            echo $tpl->text("SELECT_SKIN");
            exit;
        }

        return $tpl->text("SELECT_SKIN");
    }

    /**
     * Удалить блог
     * Для иднтивикации используем категорию, наверное
     * @return string
     */
    public function delAction(){
        global $t, $cfg, $out, $L, $structure, $sys, $usr;

        $cat = cot_import('cat', 'G', 'TXT');

        if ($usr['id'] == 0){
            cot_redirect(cot_url('message', 'msg=930&' . $sys['url_redirect'], '', true));
            die;
        }
        $bl_rootCats = explode(',', $cfg['plugin']['blogger']['rootCat']);
        foreach ($bl_rootCats as $key => $val){
            $bl_rootCats[$key] = trim($bl_rootCats[$key]);
            if (!isset($structure['page'][$bl_rootCats[$key]])) unset($bl_rootCats[$key]) ;
        }

        if (empty($cat) || $cat == '' || $cat == 'all' || $cat == 'system') {
            // Error page
            cot_die_message(404);
            exit;
        }
        if (empty($structure['page'][$cat])){
            cot_redirect(cot_url('message', 'msg=950&' . $sys['url_redirect'], '', true));
            die;
        }
        // Проверяем, не является ли эта категория корневой блога
        if (in_array($cat, $bl_rootCats)){
            cot_redirect(cot_url('message', 'msg=950&' . $sys['url_redirect'], '', true));
            die;
        }
        $blog = Blog::getByCategory($cat);
        if (!$blog){
            cot_die_message(404);
            exit;
        }
        if ($blog->user_id != $usr['id'] &&
            !($usr['isadmin'] || cot_auth('page', $blog->ub_cat, 'A' ) || cot_auth('page', $bl_rootCats[0], 'A' ))){
                cot_redirect(cot_url('message', 'msg=930&' . $sys['url_redirect'], '', true));
                die;
        }

        $out['canonical_uri'] = cot_url('blogger', 'a=del&cat='.$cat);
        $crumbs = array(array(cot_url('page', "c={$bl_rootCats[0]}"), $structure['page'][$bl_rootCats[0]]['title']));
        $crumbs[] = array(cot_url('page', "c={$cat}"), $structure['page'][$cat]['title']);

        $title = ($blog->user_id == $usr['id']) ? $L['blogger']['del_my_blog'] : $L['blogger']['del_blog'];
        $crumbs[] = $title;
        $title .= ": {$structure['page'][$cat]['title']}";
        $out['subtitle'] = $title;

        $act = cot_import('act', 'P', 'ALP', 24);
        // Удаляем блог
        if($act == 'deleteblog'){
            $this->deleteBlog($cat);
            return;
        }

        $breadcrumbs = cot_breadcrumbs($crumbs, $cfg['homebreadcrumb'], true);

        $t = new XTemplate(cot_tplfile('blogger.deleteblog', 'plug'));

        $t->parse('MAIN.FORM');

        $t->assign(array(
            'PAGE_TITLE' => $title,
            'BLOG_URL' => cot_url('page', 'c='.$cat),
            'BLOG_TITLE' => $structure['page'][$cat]['title'],
            'BREADCRUMBS' => $breadcrumbs,
            'FORM_ACTION' => cot_url('blogger', 'a=del&cat='.$cat)
        ));

        cot_display_messages($t);
    }

    /**
     * Удаляем блог
     *   Удалятся все вложенные категории, комментарии и т.п.
     *   После удаления уведомим админа.
     * @param string $cat код корневой категории блога
     */
    protected function deleteBlog($cat){
        global $cfg, $L, $structure, $usr, $db, $db_pages, $db_users;

        $msg = cot_import('del_blog_message', 'P', 'TXT');

        $blog = Blog::getByCategory($cat);
        if (!$blog){
            cot_die_message(404);
            exit;
        }

        $bl_rootCats = explode(',', $cfg['plugin']['blogger']['rootCat']);
        foreach ($bl_rootCats as $key => $val){
            $bl_rootCats[$key] = trim($bl_rootCats[$key]);
            if (!isset($structure['page'][$bl_rootCats[$key]])) unset($bl_rootCats[$key]) ;
        }

        $title = $structure['page'][$cat]['title'];
        $usr_url = cot_url('users', "m=details&id={$usr['id']}&u={$usr['name']}");
        if (!cot_url_check($usr_url)) $usr_url = COT_ABSOLUTE_URL . $usr_url;
        $blog_url = cot_url('page', 'c='.$blog->ub_cat);
        if (!cot_url_check($blog_url)) $blog_url = COT_ABSOLUTE_URL . $blog_url;

        $deleted = false;

        // Получить все категории блога
        $catChildren = cot_structure_children('page', $blog->ub_cat, true, true, false);

        // Получить все страницы
        $sql = $db->query("SELECT * FROM $db_pages WHERE page_cat IN (".implode(',',BlModelAbstract::quote($catChildren)).")");
        $pades = $sql->fetchAll();

        // Удалить все страницы
        if (!empty($pades)){
            foreach($pades as $rpage) {
                cot_page_delete($rpage['page_id'], $rpage);
            }
        }

        // Удалить все категории
        foreach($catChildren as $categ){
            cot_structure_delete('page', $categ);
        }

        // Удалить блог
        $blog->delete();

        /* === Hook === */
        foreach (cot_getextplugins('blog.delete.done') as $pl) {
            include $pl;
        }
        /* ===== */

        // Уведомляем администратора
        if ($cfg['plugin']['blogger']['notifyAdminNewBlog'] == 1 || $msg != ''){
            $admEmails = $db->query("SELECT user_email FROM $db_users WHERE user_maingrp=5")->fetchAll(PDO::FETCH_COLUMN);
            $tmp = trim($cfg['adminemail']);
            if ($tmp != '') $admEmails[] = $tmp;
            $admEmails = array_unique($admEmails);

            $email_title = $L['blogger']['blog_deleted'].': '.$title.' - '.$cfg['mainurl'];
            $email_body  = $L['User'] .' ' . cot_rc_link($usr_url, $usr['name']) . ' ( '.$usr_url.' ), ' .
                $L['blogger']['blog_deleted2'].": <strong>&laquo;".$title."&raquo;</strong><br /><br />";
            if ($msg != ''){
                $email_body .= $L['blogger']['blog_deleted_why'].":<br />";
                $email_body .= "<hr />";
                $email_body .= $msg;
                $email_body .= "<hr /><br />";
            }
            $tmp = array();
            foreach($admEmails as $email){
                if (!in_array($email, $tmp)){
                    cot_mail($email, $email_title, $email_body, '', '', '', true);
                    $tmp[] = $email;
                }

            }

        }

        cot_log($L['blogger']['plu_title'].'. '.$L['blogger']['blog_deleted'].': «'.$title.'»', 'plg');
        //Редирект после удаления блога
        cot_shield_update(30, "Blog deteted");
        cot_message(str_replace('{TITLE}', $title, $L['blogger']['msg_blog_deleted_success']));
        cot_redirect(cot_url('page', "c={$bl_rootCats[0]}", '', true));
        exit;
//        return true;
    }

}
