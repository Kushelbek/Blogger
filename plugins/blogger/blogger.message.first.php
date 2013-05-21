<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=message.first
[END_COT_EXT]
==================== */
/**
 * Blogger plugin for Cotonti Siena
 * @package Blogger
 * @author Alex - Studio Portal30
 * @copyright Portal30 2010-2013 http://portal30.ru
 */
defined('COT_CODE') or die('Wrong URL');

// Добавлена запись, которая ушла в очередь на модерацию
// После добавления страницы, отправляем на страницу категории блога
// в которую и была добавлена запись
if ($msg == 300){
    if (!empty($_SESSION['blogger']['msg300']['cat'])){
        $rd = '5';
        $ru = cot_url('page', 'c='.$_SESSION['blogger']['msg300']['cat']);
        unset($_SESSION['blogger']['msg300']);
    }
}
