<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.edit.update.done
[END_COT_EXT]
==================== */
/**
 * Blogger plugin for Cotonti Siena
 * @package Blogger
 * @author Alex - Studio Portal30
 * @copyright Portal30 2010-2013 http://portal30.ru
 */
defined('COT_CODE') or die('Wrong URL');


if (!empty($rpage['page_cat']) && bl_inBlog($rpage['page_cat'])){
    global $usr, $L, $db_users, $cfg;

    // Если есть права на автоутверждение страниц
    // автоматически утвердим ее.
    $tmp = cot_import('rpagestate', 'P', 'INT');
    if ($tmp == 0){
        if (!$auth['isadmin'] && $cfg['page']['autovalidate'] && cot_auth('plug', 'blogger', '2')){
            $rpage['page_state'] = 0;

            $db->update($db_pages, array('page_state' => $rpage['page_state']),
                'page_id=?', array($id));

            // синхронизация счетчиков, а то муть получется
            $tmpCnt = cot_page_sync($rpage['page_cat']);
            $db->update($db_structure, array("structure_count" => (int)$tmpCnt),
                "structure_code='".$db->prep($rpage['page_cat'])."' AND structure_area='page'");
        }
    }
    $urlparams = empty($rpage['page_alias']) ?
        array('c' => $rpage['page_cat'], 'id' => $id) :
        array('c' => $rpage['page_cat'], 'al' => $rpage['page_alias']);
    $page_url = cot_url('page', $urlparams, '', true);
    if (!cot_url_check($page_url)) $page_url = COT_ABSOLUTE_URL .$page_url;

    // Лог, отредактирована запись.
    cot_log($L['blogger']['plu_title'].'. '.$L['blogger']['page_edited_log'].': «'.$rpage['page_title'].'» ( '.
        $page_url.' )    ', 'plg');

    // Если необходимо отправляем письмо админу об отредактированной страницы
    if ($cfg['plugin']['blogger']['notifyAdminNewPage'] == 1){
        // Для уведомлений получим адреса админов
        $admEmails = $db->query("SELECT user_email FROM $db_users WHERE user_maingrp=5")
            ->fetchAll(PDO::FETCH_COLUMN);
        $tmp = trim($cfg['adminemail']);
        if ($tmp != '') $admEmails[] = $tmp;
        $admEmails = array_unique($admEmails);

        $usr_url = cot_url('users', "m=details&id={$usr['id']}&u={$usr['name']}");
        if (!cot_url_check($usr_url)) $usr_url = COT_ABSOLUTE_URL . $usr_url;

        $email_title = $L['blogger']['page_edited'].' - '.$cfg['mainurl'];
        $email_body  = $L['User'] .' '.cot_rc_link($usr_url, $usr['name']).' ( '.$usr_url.' ), ' .
            $L['blogger']['page_edited2'].": <strong>&laquo;{$rpage['page_title']}&raquo;</strong><br /><br />";
        $email_body .= $L['blogger']['desc'].":";
        $email_body .= "<hr />";
        $email_body .= $rpage['page_desc'];
        $email_body .= "<hr /><br />";
        $email_body .= $L['blogger']['page_edited3'].":<br />";
        $email_body .= $page_url."<br /><br />";

        $tmp = array();
        foreach($admEmails as $email){
            if (!in_array($email, $tmp)){
                cot_mail($email, $email_title, $email_body, '', '', '', true);
                $tmp[] = $email;
            }
        }
    }

//    cot_message(str_replace('{PAGE_TITLE}', $rpage['page_title'], $L['blogger']['msg_page_create_success']));

    // добавить в сессию флаг для правильного редиректа, если страница ушла на утверждение
    if ($rpage['page_state'] == 1) $_SESSION['blogger']['msg300']['cat'] = $rpage['page_cat'];
}
