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
<form action="{FORM_ACT}" method="POST" name="blogform" class="form-inline">
    <input type="hidden" name="act" value="save" />

    <table class="cells">
        <tr>
            <td style="width:176px;">{PHP.L.blogger.title}: *</td>
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
        <tr>
            <td colspan="2" class="valid">

                <button class="btn-primary"><i class="icon-ok icon-white"></i> {PHP.L.Submit}</button>

                <!-- IF {BLOG_CAT} -->
                <a href="{BLOG_CAT|cot_url('page', 'c=$this')}" class="btn btn-info"><i class="icon-ban-circle icon-white"></i> {PHP.L.Cancel}</a>
                <!-- ELSE -->
                <a href="{BLOGGER_ROOT_CAT|cot_url('page', 'c=$this')}" class="btn btn-info"><i class="icon-ban-circle icon-white"></i> {PHP.L.Cancel}</a>
                <!-- ENDIF -->
            </td>
        </tr>
    </table>
</form>
<!-- END: FORM -->

<!-- IF {NEW_CATEGORY} == 1 -->
<div class="margintop10">{PHP.L.blogger.new_category_desc}</div>
<!-- ENDIF -->
<!-- END: MAIN -->
