<!-- BEGIN: MAIN -->
<div id="breadcrumb">
    <a href="{PHP.cfg.mainurl}"><img src="{PHP.cfg.mainurl}/{PHP.cfg.themes_dir}/{PHP.theme}/img/icon-home.gif" width="16" height="16" align="absmiddle" title="{PHP.cfg.maintitle}" /></a>
{PHP.cfg.separator} {BREADCRUMBS}
</div>

<!-- IF {PAGE_TITLE} -->
<h1>{PAGE_TITLE}</h1>
<!-- ENDIF -->

{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}

<!-- BEGIN: FORM -->
<div class="margin10">
<!-- IF {FORM_ID} > 0 -->{PHP.L.blogger.edit_blog_desc}<!-- ELSE -->{PHP.L.blogger.new_blog_desc}<!-- ENDIF -->
</div>

<form action="{FORM_ACT}" method="POST" name="blogform" class="form-inline">
    <input type="hidden" name="rid" value="{FORM_ID}" />
    <input type="hidden" name="act" value="save" />

    <table class="cells">
        <tr>
            <td style="width:176px;">{PHP.L.blogger.title} :</td>
            <td class="inp-full">{FORM_TITLE}</td>
        </tr>
        <tr>
            <td>{PHP.L.blogger.desc} :</td>
            <td class="inp-full">{FORM_DESC}</td>
        </tr>
        <!-- IF {FORM_TEXT} -->
        <tr>
            <td colspan="2">{PHP.L.blogger.full_desc} :<br />
            {FORM_TEXT}
            {FORM_TEXT_PFS} &nbsp;&nbsp; {FORM_TEXT_SFS}
            </td>
        </tr>
        <!-- ENDIF -->
        <!-- IF {FORM_COMNOTIFY} -->
        <tr>
            <td>{PHP.L.blogger.comnotify} :</td>
            <td>
                {FORM_COMNOTIFY}
                <div class="desc">{PHP.L.blogger.comnotify_desc}</div>
            </td>
        </tr>
        <!-- ENDIF -->
        <!-- IF {FORM_ID} > 0 -->
        <tr>
            <td>{PHP.L.blogger.theme}:</td>
            <td class="strong textcenter">
                {FORM_CURR_THEME_TITLE}<br />
                <img src="{FORM_CURR_THEME_THUMB}" /><br />
                {FORM_CURR_SCHEME_TITLE}
            </td>
        </tr>
        <!-- ENDIF -->
        <!-- IF {FORM_SELECT_THEME} -->
        <tr>
            <td colspan="2">
                {PHP.L.blogger.new_theme}:
                {FORM_SELECT_THEME}
            </td>
        </tr>
        <!-- ENDIF -->
        <tr>
            <td colspan="2" class="valid">
                <input type="hidden" name="rtheme" value="{FORM_CURR_THEME}" />
                <input type="hidden" name="rscheme" value="{FORM_CURR_SCHEME}" />

                <button class="btn-primary"><i class="icon-ok icon-white"></i> {PHP.L.Submit}</button>

                <!-- IF {BLOG_CAT} -->
                <a href="{BLOG_CAT|cot_url('page', 'c=$this')}" class="btn btn-info"><i class="icon-ban-circle icon-white"></i> {PHP.L.Cancel}</a>
                <!-- ELSE -->
                <a href="{BLOGGER_ROOT_CAT|cot_url('page', 'c=$this')}" class="btn btn-info"><i class="icon-ban-circle icon-white"></i> {PHP.L.Cancel}</a>
                <!-- ENDIF -->
            </td>
        </tr>
    </table>

    <!-- IF {FORM_ID} == 0 -->
    <div class="margintop10">{PHP.L.blogger.new_blog_desc2}</div>
    <!-- ENDIF -->

<!-- END: FORM -->

<!-- END: MAIN -->
