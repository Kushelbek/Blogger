<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=page.add.add.query
[END_COT_EXT]
==================== */
/**
 * Blogger plugin for Cotonti Siena
 * @package Blogger
 * @author Alex - Studio Portal30
 * @copyright Portal30 2010-2013 http://portal30.ru
 */
defined('COT_CODE') or die('Wrong URL');

if (bl_inBlog($rpage['page_cat'])){

    $tmp = cot_import('rpagestate', 'P', 'INT');
    if ($tmp == 0){
        // Если есть права на автоутверждение страниц
        // автоматически утвердим ее.
        if (!$auth['isadmin'] && $cfg['page']['autovalidate'] && cot_auth('plug', 'blogger', '2')){
            $rpage['page_state'] = 0;
            $db->query("UPDATE $db_structure SET structure_count=structure_count+1
                WHERE structure_area='page' AND structure_code = ?", $rpage['page_cat']);
            $cache && $cache->db->remove('structure', 'system');
        }
    }
}

