<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=standalone
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
require_once cot_langfile('blogger');

// Роутер
// Only if the file exists...
if (!$m) $m = 'main';

if (file_exists(cot_incfile('blogger', 'plug', $m))) {
    require_once cot_incfile('blogger', 'plug', $m);
    /* Create the controller */
    $_class = ucfirst($m).'Controller';
    $controller = new $_class();

    // TODO кеширование
    /* Perform the Request task */
    $shop_action = $a.'Action';
    if (!$a && method_exists($controller, 'indexAction')){
        $content = $controller->indexAction();
    }elseif (method_exists($controller, $shop_action)){
        $content = $controller->$shop_action();
    }else{
        // Error page
        cot_die_message(404);
        exit;
    }

    //ob_clean();
    // todo дописать как вывод для плагинов
//    require_once $cfg['system_dir'] . '/header.php';
//    if (isset($content)) echo $content;
//    require_once $cfg['system_dir'] . '/footer.php';
}else{
    // Error page
    cot_die_message(404);
    exit;
}
/*
require_once($cfg["plugins_dir"].DS.'an_blogger'.DS.'inc'.DS.'an_blogger.functions.php');

//$an_blogger_base_url = SED_ABSOLUTE_URL.getRelativeURL(dirname(__FILE__));	// URL папки с плагином
$an_blogger_base_url = $cfg["plugins_dir"].'/an_blogger';

$an_blogger_act = sed_import('act','G','ALP');
$an_blogger_title = '';
$an_blogger_breadcrumb = '';

switch($an_blogger_act){
	
	// Новый блог
	case 'new':
		$an_blogger_title = $L['an_blogger']['new_blog'];
		$an_blogger_breadcrumb = '<a href="'.sed_url('list', 'c='.$cfg['plugin']['an_blogger']['rootCat']).'" >'.$sed_cat[$cfg['plugin']['an_blogger']['rootCat']]['title'].'</a> '.$cfg['separator'].' '.$an_blogger_title;
		$blog->new_blog();
		break;
	
	case 'createnew':
		$an_blogger_title = $L['an_blogger']['new_blog'];
		$an_blogger_breadcrumb = '<a href="'.sed_url('list', 'c='.$cfg['plugin']['an_blogger']['rootCat']).'" >'.$sed_cat[$cfg['plugin']['an_blogger']['rootCat']]['title'].'</a> '.$cfg['separator'].' '.$an_blogger_title;
		$blog->create_new_blog();
		break;
	
	// Удаление блога
	case 'del':
		$an_blogger_title = $L['an_blogger']['del_blog'];
		$an_blogger_breadcrumb = '<a href="'.sed_url('list', 'c='.$cfg['plugin']['an_blogger']['rootCat']).'" >'.$sed_cat[$cfg['plugin']['an_blogger']['rootCat']]['title'].'</a> '.$cfg['separator'].' '
			.'<a href="'.sed_url('list', 'c='.$blog->cur_blog_code).'" >'.$blog->cur_blog_name.'</a> '.$cfg['separator'].' '.$an_blogger_title;
		$blog->del_blog_form();
		break;
	
	// Редактирование блога
	case 'edit':
		$an_blogger_title = $L['an_blogger']['edit_blog'];
		$an_blogger_breadcrumb = '<a href="'.sed_url('list', 'c='.$cfg['plugin']['an_blogger']['rootCat']).'" >'.$sed_cat[$cfg['plugin']['an_blogger']['rootCat']]['title'].'</a> '.$cfg['separator'].' '
			.'<a href="'.sed_url('list', 'c='.$blog->cur_blog_code).'" >'.$blog->cur_blog_name.'</a> '.$cfg['separator'].' '.$an_blogger_title;
		$blog->edit_blog_form();
		break;
	
	// Редактирование раздела
	case 'editcat':
		$an_blogger_title = $L['an_blogger']['edit_category'];
		$an_blogger_breadcrumb = '<a href="'.sed_url('list', 'c='.$cfg['plugin']['an_blogger']['rootCat']).'" >'.$sed_cat[$cfg['plugin']['an_blogger']['rootCat']]['title'].'</a> '.$cfg['separator'].' '
			.'<a href="'.sed_url('list', 'c='.$blog->cur_blog_code).'" >'.$blog->cur_blog_name.'</a> '.$cfg['separator'].' '.$an_blogger_title;
		$blog->edit_category_form();
		break;
	
	// Форма создания новой категории в существующем блоге
	case 'newcat':
		$c = sed_import('c','G','TXT');
		$an_blogger_title = $L['an_blogger']['new_category'];
		
		$an_blogger_breadcrumb = '<a href="'.sed_url('list', 'c='.$cfg['plugin']['an_blogger']['rootCat']).'" >'.$sed_cat[$cfg['plugin']['an_blogger']['rootCat']]['title'].'</a> '.$cfg['separator'].' ';
		$an_blogger_breadcrumb .= '<a href="'.sed_url('list', 'c='.$blog->cur_blog_code).'" >'.$blog->cur_blog_name.'</a> '.$cfg['separator'].' '.$L['an_blogger']['new_category'];
		$blog->new_category($c);
		break;
	
	// Новая категория в существующем блоге
	case 'createnewcat':
		$c = sed_import('c','G','TXT');

		$an_blogger_title = $L['an_blogger']['new_category'];
		
		$an_blogger_breadcrumb = '<a href="'.sed_url('list', 'c='.$cfg['plugin']['an_blogger']['rootCat']).'" >'.$sed_cat[$cfg['plugin']['an_blogger']['rootCat']]['title'].'</a> '.$cfg['separator'].' ';
		$an_blogger_breadcrumb .= '<a href="'.sed_url('list', 'c='.$blog->cur_blog_code).'" >'.$blog->cur_blog_name.'</a> '.$cfg['separator'].' '.$L['an_blogger']['new_category'];
		$blog->create_new_category($c);
		break;
	
	// Удаляем категрию в блоге
	case 'deletecat':
		$c = sed_import('c','G','TXT');
		
		$an_blogger_title = $L['an_blogger']['del_category'].": ".$sed_cat[$c]['title'];
		
		$an_blogger_breadcrumb = '<a href="'.sed_url('list', 'c='.$cfg['plugin']['an_blogger']['rootCat']).'" >'.$sed_cat[$cfg['plugin']['an_blogger']['rootCat']]['title'].'</a> '.$cfg['separator'].' ';
		$an_blogger_breadcrumb .= '<a href="'.sed_url('list', 'c='.$blog->cur_blog_code).'" >'.$blog->cur_blog_name.'</a> '.$cfg['separator'].' '.$L['an_blogger']['del_category'];
		$blog->delete_category($c);
		break;
	
	default:
		// Для отладки отключать
		sed_redirect(sed_url('index', "", "", true));
		break;
}

if ($blog->message != ''){
	$t->assign(array(
		"MESSAGE" => $blog->message,
	));
	$t->parse("MAIN.BLOGGER_MSG");
}

if ($blog->error != ''){
	$t->assign(array(
		"ERROR" => $blog->error,
	));
	$t->parse("MAIN.BLOGGER_ERROR");
}

$t->assign(array(	
	"PLUGIN_TITLE" => $an_blogger_title,
	"BLOGGER_BREADCRUMB" => $an_blogger_breadcrumb,
));
?>
*/