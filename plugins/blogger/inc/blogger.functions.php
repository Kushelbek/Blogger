<?php
/**
 * Blogger plugin for Cotonti Siena
 * @package Blogger
 * @author Alex - Studio Portal30
 * @copyright Portal30 2010-2013 http://portal30.ru
 */
defined('COT_CODE') or die('Wrong URL');

$db_user_blogs = (!empty($db_user_blogs)) ? $db_user_blogs : "cot_user_blogs";

require_once $cfg['plugins_dir']."/blogger/model/Blog.php";
//require_once cot_langfile('blogger', 'plug');
require_once cot_incfile('blogger', 'plug', 'resources');

require_once cot_incfile('page', 'module');
if(cot_module_active('pfs')) require_once cot_incfile('pfs', 'module');
require_once cot_incfile('extensions');
require_once cot_incfile('structure');

function bl_getCurrCategory(){
    global $env, $pag, $c, $a, $row_page, $m, $id, $db, $db_pages;

    static $currCat = false;
    if(!empty($currCat)) return $currCat;

    $ret = '';
    /** Сохранение страницы */
    if ($env['location'] == 'pages' && ($a == 'update' || $a == 'add')){
        $ret = cot_import('rpagecat', 'P', 'TXT');
        if (!$ret && !empty($row_page['page_cat'])) $ret = $row_page['page_cat'];

        if (!empty($ret)){
            $currCat = $ret;
            return $ret;
        }
    }
    /** /Сохранение страницы */

    /** Редактирование страницы */
    // На хуке page.edit.first это единственный способ выяснить категорию
    if ($env['location'] == 'pages' && $m == 'edit' && $id > 0){
        $ret = $db->query("SELECT page_cat FROM $db_pages WHERE page_id=?", $id)->fetchColumn();
        $currCat = $ret;
        return $ret;
    }
    /** /Редактирование страницы */

    if (!$ret && ($env['location'] == 'list' || $env['location'] == 'pages')){
        $ret = (isset($pag['page_cat'])) ? $pag['page_cat'] : $c;
    }


    // Ето строго для отладки !!!
//    if (!$ret) throw new Exception('Не могу определить категорию');

    $currCat = $ret;

    return $ret;
}

/**
 * Все категории блогов
 * @return array коды категорий блога
 */
function bl_readBlogCats(){
    global $cfg, $structure;

    if(is_array($cfg['plugin']['blogger']['cats'])){
        reset($cfg['plugin']['blogger']['cats']);
        return $cfg['plugin']['blogger']['cats'];
    }

    // Получить вложенные категории
    $tmpCats = explode(',', $cfg['plugin']['blogger']['rootCat']);
    $cats = array();
    foreach ($tmpCats as $key => $val){
        $tmpCats[$key] = trim($tmpCats[$key]);
        if (!isset($structure['page'][$tmpCats[$key]])) continue;
        $cats = array_merge($cats, cot_structure_children('page', $tmpCats[$key], true, true, true, false));

    }
    $cats = array_unique($cats);
    reset($cats);
    $cfg['plugin']['blogger']['cats'] = $cats;

    return $cfg['plugin']['blogger']['cats'];
}

/**
 * Находится ли категория в блоге
 * @param bool|string $c - код категории
 * @return bool
 */
function bl_inBlog($c = false){
    global $cfg, $env, $e;

    if($env['location'] == 'plugins' && $e == 'blogger') return true;

    if (empty($c)) return false;

    if (!isset($cfg['plugin']['blogger']['cats']) || !$cfg['plugin']['blogger']['cats']) bl_readBlogCats();
    if (empty($cfg['plugin']['blogger']['cats'])) return false;

    return in_array($c, $cfg['plugin']['blogger']['cats']);
}

/**
 * Может ли пользователь создать свой блог
 * Если есть права на запись для плагина an_blog
 * и он еще не создал блог, то он может его создать
 * Вернет 1 - в случае, если можно создать блог
 *        0 - нет прав на создание
 *       -1 - блог уже создан
 * @return int 1|0|-1
 */
function bl_canUserAddBlog(){
    global $usr;

    // Проверка на то, что блог уже есть
    if ($usr["id"] > 0){
        $count = Blog::count("user_id=".$usr["id"]);
        if ($count > 0) return -1;
    }
    if ($usr["isadmin"]) return 1;
    if (!cot_auth('plug', 'blogger', 'W')) return 0;

    return 1;
}

/**
 * Подбираем уникальный код категории
 */
function bl_genCategoryCode($title = ''){
    global $usr, $lang, $cot_translit, $db, $db_structure;

    if ($title == '') $title = $usr['name'];

    $code = mb_strtolower($title);
    if ($code == 'all') $code = 'all1';
    $code = str_replace(' ', '-', $code);
    if($lang != 'en' && file_exists(cot_langfile('translit', 'core')) ){
        include_once cot_langfile('translit', 'core');
        $code = strtr($code, $cot_translit);
    }
    $code = preg_replace('#[^a-zA-Z0-9\-_\ \+]#', '', $code);
    $i = 0;
    while ($i == 0){
        $sql = $db->query("SELECT structure_code FROM $db_structure WHERE structure_code='".$db->prep($code)."' LIMIT 1");
        if ($sql->rowCount() > 0){
            $code_1 = preg_replace('#[0-9]+\b#e', '"$0"+1', $code);
            $code = ($code_1 == $code) ? $code .= '1' : $code_1;
        }else{
            return $code;
        }
    }
}

/**
 * Генерируем путь к категории
 * @param string $parent - родительская категория. Если не указано, то используем корневую блога
 * @return string
 */
function bl_genCategoryPath($parent = ''){
    global $structure, $cfg;

    $bl_rootCats = explode(',', $cfg['plugin']['blogger']['rootCat']);
    foreach ($bl_rootCats as $key => $val){
        $bl_rootCats[$key] = trim($bl_rootCats[$key]);
        if (!isset($structure['page'][$bl_rootCats[$key]])) unset($bl_rootCats[$key]) ;
    }

    $parent = ($parent != '') ? $parent : $bl_rootCats[0];
    $ret_num = 1;
    foreach($structure['page'] as $cat){
        if ( (mb_strpos($cat['rpath'], $structure['page'][$parent]['rpath']) === 0) &&
                                        $cat['rpath'] != $structure['page'][$parent]['rpath']){

            $tmp = str_replace($structure['page'][$parent]['rpath'].'.', '', $cat['rpath']);
            if ($tmp == '') continue;
            $tmp = explode('.', $tmp);
            if (is_numeric($tmp[0]) && $tmp[0] >= $ret_num) $ret_num = $tmp[0]+1;
        }
    }
    return $structure['page'][$parent]['rpath'].'.'.$ret_num;
}

/**
 * Пустая ли категория
 * @param string $c - код категории
 * @return bool вернет true, если категория пуста
 */
function bl_catEmpty($c){
    global $structure;

    if ($c == '' || empty($structure['page'][$c])) return true;

    $children = cot_structure_children('page', $c, true, false, false);
    if (count($children) > 0) return false;

    // Хз стоит ли доверять $structure['page'][$c]['count'];
    // Но пока доверяем, иначе в листах много запросов
    if ($structure['page'][$c]['count'] > 0) return false;
    //if (bl_catPageCount($c, true) > 0) return false;

    return true;
}

/**
 * Количество страниц в категории
 * @param string $c - код категории
 * @param bool $inc_subcats - считать страницы в подкатегориях
 *    вернет количество страниц
 * @return bool|int
 *
 * @todo на листах много запросов получается
 */
function bl_catPageCount($c, $inc_subcats = false){
    global $structure, $db_structure, $db_pages, $db;

    if ($c == '' || empty($structure['page'][$c])) return true;

    static $pageCount = array();

    $items_count = 0;
    if ($inc_subcats){
        if ( !empty($pageCount[$c][1]) ){
            $items_count = $pageCount[$c][1];
        }else{
            $items_count = $db->query("SELECT COUNT(*) FROM $db_pages p LEFT JOIN $db_structure s ON (p.page_cat = s.structure_code)
                WHERE s.structure_area='page' AND
                    (s.structure_path LIKE '".$db->prep($structure['page'][$c]['rpath']).".%'
                            OR s.structure_path LIKE '".$db->prep($structure['page'][$c]['rpath'])."')")->fetchColumn();
            $pageCount[$c][1] = $items_count;
        }
    }else{
        if ( !empty($pageCount[$c][0]) ){
            $items_count = $pageCount[$c][0];
        }else{
            $items_count = $db->query("SELECT COUNT(*) FROM $db_pages  WHERE page_cat='".$db->prep($c)."'")->fetchColumn();
            $pageCount[$c][0] = $items_count;
        }
    }

    return $items_count;
}


/**
 * Generates page list widget
 *   Доработанная Alex'ом функция от Seditio.By
 * @author Seditio.By, trustmaster, Alex
 *
 * @param  string  $tpl        Template code
 * @param  integer $items      Number of items to show. 0 - all items
 * @param  string  $order      Sorting order (SQL)
 * @param  string  $condition  Custom selection filter (SQL)
 * @param  string  $cat        Custom parent category code
 * @param  string  $blacklist  Category black list, semicolon separated
 * @param  string  $whitelist  Category white list, simicolon separated
 * @param  boolean $sub        Include subcategories TRUE/FALSE
 * @param  string  $pagination Pagination parameter name for the URL, e.g. 'pld'. Make sure it does not conflict with other paginations.
 * @param  boolean $noself     Exclude the current page from the rowset for pages.
 * @param array $url_params
 * @param bool $ajaxPagination
 * @param array $ajaxPagParams
 * @return string              Parsed HTML
 */
function bl_pagesList($tpl = 'blogger.pageslist', $items = 0, $order = '', $condition = '', $cat = '', $blacklist = '', $whitelist = '',
                    $sub = true, $pagination = 'pld', $noself = false, $url_params = array(), $ajaxPagination = false,
                    $ajaxPagParams = array() )
{
    global $db, $db_pages, $db_users, $env, $structure, $cfg;

    // Compile lists
    if (!empty($blacklist)){
        $bl = explode(';', $blacklist);
    }

    if (!empty($whitelist)){
        $wl = explode(';', $whitelist);
    }

    // Если не переданы категории, берем все категории доски объявлений
    $getDefaultCats = true;
    if (is_array($condition) && !empty($condition['cat'])) $getDefaultCats = false;
    if (is_string($condition) && mb_strpos($condition, 'page_cat') !== false) $getDefaultCats = false;
    if (!empty($cat) || !empty($blacklist) || !empty($whitelist)) $getDefaultCats = false;
    if ($getDefaultCats){
        $wl = bl_readBlogCats();
        $whitelist = implode(';', $wl);
    }

    // Get the cats
    $cats = array();
    if (empty($cat) && (!empty($blacklist) || !empty($whitelist)))
    {
        // All cats except bl/wl
        foreach ($structure['page'] as $code => $row)
        {
            if (!empty($blacklist) && !in_array($code, $bl)
                || !empty($whitelist) && in_array($code, $wl))
            {
                $cats[] = $code;
            }
        }
    }
    elseif (!empty($cat) && $sub)
    {
        // Specific cat
        $cats = cot_structure_children('page', $cat, $sub);
    }

    if (count($cats) > 0)
    {
        if (!empty($blacklist))
        {
            $cats = array_diff($cats, $bl);
        }

        if (!empty($whitelist))
        {
            $cats = array_intersect($cats, $wl);
        }

        $where_cat = "AND page_cat IN ('" . implode("','", $cats) . "')";
    }
    elseif (!empty($cat))
    {
        $where_cat = "AND page_cat = " . $db->quote($cat);
    }

    if (is_array($condition)) $condition = implode(' AND ', $condition);

    $where_condition = (empty($condition)) ? '' : "AND $condition";

    if ($noself && defined('COT_PAGES') && !defined('COT_LIST'))
    {
        global $id;
        $where_condition .= " AND page_id != $id";
    }

    // Get pagination number if necessary
    if (!empty($pagination))
    {
        list($pg, $d, $durl) = cot_import_pagenav($pagination, $items);
    }
    else
    {
        $d = 0;
    }

    // Display the items
    $t = new XTemplate(cot_tplfile($tpl, 'plug'));

    /* === Hook === */
    foreach (array_merge(cot_getextplugins('customnews.query'), cot_getextplugins('pagelist.query')) as $pl)
    {
        include $pl;
    }
    /* ===== */

    $totalitems = $db->query("SELECT COUNT(*)
		FROM $db_pages AS p $cns_join_tables
		WHERE page_state='0' $where_cat $where_condition")->fetchColumn();

    $sql_order = empty($order) ? 'ORDER BY p.page_begin DESC' : "ORDER BY $order";
    $sql_limit = ($items > 0) ? "LIMIT $d, $items" : '';

    $res = $db->query("SELECT p.*, u.* $cns_join_columns
		FROM $db_pages AS p
			LEFT JOIN $db_users AS u ON p.page_ownerid = u.user_id
			$cns_join_tables
		WHERE page_state='0' $where_cat $where_condition
		$sql_order $sql_limit");

    $jj = 1;

    // Корневые категории блогов:
    $bl_rootCats = explode(',', $cfg['plugin']['blogger']['rootCat']);
    foreach ($bl_rootCats as $key => $val){
        $bl_rootCats[$key] = trim($bl_rootCats[$key]);
        if (!isset($structure['page'][$bl_rootCats[$key]])) unset($bl_rootCats[$key]) ;
    }

    $truncatetext = $cfg['page']['cat_' . $bl_rootCats[0]]['truncatetext'] ?
        $cfg['page']['cat_' . $bl_rootCats[0]]['truncatetext'] :
        $cfg['page']['cat___default']['truncatetext'];

    while ($row = $res->fetch()){
        $t->assign(cot_generate_pagetags($row, "PAGE_ROW_", $truncatetext));

        $t->assign(array(
            'PAGE_ROW_NUM'     => $jj,
            'PAGE_ROW_ODDEVEN' => cot_build_oddeven($jj),
            'PAGE_ROW_RAW'     => $row
        ));

        $t->assign(cot_generate_usertags($row, 'PAGE_ROW_OWNER_'));

        /* === Hook === */
        foreach (cot_getextplugins('pagelist.loop') as $pl){
            include $pl;
        }
        /* ===== */

        $t->parse("MAIN.PAGE_ROW");
        $jj++;
    }

    // Render pagination
    $url_area = defined('COT_PLUG') ? 'plug' : $env['ext'];
    if (empty($url_params)){
        if (defined('COT_LIST'))
        {
            global $list_url_path;
            $url_params = $list_url_path;
        }
        elseif (defined('COT_PAGES'))
        {
            global $al, $id, $pag;
            $url_params = empty($al) ? array('c' => $pag['page_cat'], 'id' => $id) :  array('c' => $pag['page_cat'], 'al' => $al);
        }
        else
        {
            $url_params = array();
        }
    }
    $url_params[$pagination] = $durl;
    if ($ajaxPagination){
        $targetDivId = 'reload_'.$pagination;
        is_string($ajaxPagParams) ? parse_str($ajaxPagParams, $ajax_args) : $ajax_args = $ajaxPagParams;
        $ajax_args['e'] = 'advboard';
        $pagenav = cot_pagenav($url_area, $url_params, $d, $totalitems, $items, $pagination,  '', true, $targetDivId,
            'plug', $ajax_args);
    }else{
        $pagenav = cot_pagenav($url_area, $url_params, $d, $totalitems, $items, $pagination);
    }

    $t->assign(array(
        'PAGE_TOP_PAGINATION'  => $pagenav['main'],
        'PAGE_TOP_PAGEPREV'    => $pagenav['prev'],
        'PAGE_TOP_PAGENEXT'    => $pagenav['next'],
        'PAGE_TOP_FIRST'       => $pagenav['first'],
        'PAGE_TOP_LAST'        => $pagenav['last'],
        'PAGE_TOP_CURRENTPAGE' => $pagenav['current'],
        'PAGE_TOP_TOTALLINES'  => $totalitems,
        'PAGE_TOP_MAXPERPAGE'  => $items,
        'PAGE_TOP_TOTALPAGES'  => $pagenav['total'],
        'PAGE_AJAX' => COT_AJAX,
        'PAGE_TOP_USE_AJAX' => $ajaxPagination,
        'PAGE_TOP_AJAX_DIV_ID' => ($ajaxPagination && ! COT_AJAX) ? $targetDivId : ''
    ));

    /* === Hook === */
    foreach (cot_getextplugins('pagelist.tags') as $pl)
    {
        include $pl;
    }
    /* ===== */

    $t->parse();
    return $t->text();
}