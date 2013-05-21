/**
 * Cotonti Blogger plugin
 *
 * @package Blogger
 * @subpakage Java Script
 * @author Alex - Studio Portal30
 * @copyright Portal30 2010-2013 http://portal30.ru
 */


/**
 * Выбираем тему кликая по элементу выбора
 * Параметры:
 * theme - Новая тема
 * scheme - Схема
 */
function bl_selectTheme(theme, scheme){
	document.blogform.rtheme.value=theme;
	document.blogform.rscheme.value=scheme;
    $('input[name="rad_theme_select"]').val([theme +'_'+ scheme]);
}

/**
 * Запросит подтверждение, если получен положительный ответ, то передет на URL - action
 * Параметры:
 * @param string text - Сообщение для подтверждения
 * @param string title - заголовок окна подтверждения (работает только при jQuery.UI)
 * @param string action - URL для перехода в случае положительного ответа
 * @param bool toHistory - сохранить ли переход на этот URL в истории браузера
 */
function bl_confirm(text, title, action, toHistory){
    title = title || '';
    action = action || '';
    toHistory = toHistory || true;

    if (action == '') return confirm(text);

    blDialog({
        title: title,
        text: text,
        dialogClass: 'warning',
        resizable: false,
        buttons: [
            {text: blogLocale.ok,
                click: function() {
                    if (toHistory){
                        window.location.assign(action);
                    }else{
                        window.location.replace(action); // В истории браузера не сохранится переход на этот URL
                    }
                    blDialogClose();
                }},
            {text: blogLocale.cancel,
                click: function() { blDialogClose(); }}
        ]
    });

    return false;
}
