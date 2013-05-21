<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=comments.send.new
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


$bl_cat = '';
if ($comarray['com_area'] == 'page' ){
    if (!empty($url_params['c'])){
        $bl_cat = $url_params['c'];
    }elseif(!empty($cat)){
        $bl_cat = $cat;
    }else{
        // получить категорию по id страницы:
        //$comarray["com_code"]
    }
}

if (bl_inBlog($bl_cat)){
    $blog = Blog::getByCategory($bl_cat);
    if ($blog && ($blog->owner['user_maingrp'] != 5 || !$cfg['plugin']['comments']['mail'])){

        require_once cot_langfile('blogger');
        // $blog->owner['user_name']
        // $blog->owner['user_email']

        $bl_UrlParams = $url_params;
        if(isset($bl_UrlParams['e'])) unset($bl_UrlParams['e']);

        $bl_CommUrl = cot_url($url_area, $bl_UrlParams, '#c' . $id, true);
        if (!cot_url_check($bl_CommUrl)) $bl_CommUrl = COT_ABSOLUTE_URL . $bl_CommUrl;

        // Выдержка с поста
        $len_cut = 500;  // Длина выдержки с поста (символов)
        $bl_ComText = cot_parse($comarray['com_text'], $cfg['plugin']['comments']['markup']);
        $bl_ComText = cot_string_truncate($bl_ComText, $len_cut, true, false, '...');
        // /Выдержка с поста

        $bl_CommenterName = '';
        if (!empty($usr['name']) && $usr['name'] != ''){
            $bl_CommenterName = $usr['name'];
            $bl_CommenterUrl = '';
            if ($usr['id'] > 0){
                $bl_CommenterUrl = cot_url('users', 'm=details&id='.$usr['id'].'&u='.$usr['name']);
                if (!cot_url_check($bl_CommenterUrl)) $bl_CommenterUrl = COT_ABSOLUTE_URL . $bl_CommenterUrl;
                $bl_CommenterName = cot_rc_link($bl_CommenterUrl, $usr['name']);
            }
        }else{
            $bl_CommenterName = $L['blogger']['anonimus'];
        }

        $bl_Url = cot_url($url_area, $bl_UrlParams, '', true);
        if (!cot_url_check($bl_Url)) $bl_Url = COT_ABSOLUTE_URL . $bl_Url;

        $bl_MyBlogUrl = cot_url('page', "c={$blog->ub_cat}");
        if (!cot_url_check($bl_MyBlogUrl)) $bl_MyBlogUrl = COT_ABSOLUTE_URL . $bl_MyBlogUrl;

        $email_title = $L['blogger']['comlive'].' - '.$cfg['mainurl'];
        $email_body  = $L['User']." <strong>{$bl_CommenterName}</strong> {$L['blogger']['comlive2']}<br /><br />";
        $email_body .= $L['comments_comment'].":";
        $email_body .= "<hr />";
        $email_body .= $bl_ComText;
        $email_body .= "<hr />";
        $email_body .= $L['blogger']['comlive3'].":<br />";
        $email_body .= cot_rc_link($bl_CommUrl, $bl_Url).'<br /><br />';
        $email_body .= $L['blogger']['myblog'].":<br />";
        $email_body .= cot_rc_link($bl_MyBlogUrl, $bl_MyBlogUrl).'<br /><br />';

        cot_mail($blog->owner['user_email'], $email_title, $email_body, '', false, null, true);
    }
}