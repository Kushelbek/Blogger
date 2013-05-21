<!-- BEGIN: MAIN -->
<div id="breadcrumb">
    <a href="{PHP.cfg.mainurl}"><img src="{PHP.cfg.mainurl}/{PHP.cfg.themes_dir}/{PHP.theme}/img/icon-home.gif" width="16" height="16" align="absmiddle" title="{PHP.cfg.maintitle}" /></a>
{PHP.cfg.separator} {BREADCRUMBS}
</div>

<!-- IF {PAGE_TITLE} -->
<h1>{PAGE_TITLE}</h1>
<!-- ENDIF -->

{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}

<div class="block_light paddingleft10">
<table class="flat">
    <tr>
        <td>
            <img src="{PHP.cfg.plugins_dir}/blogger/tpl/img/alert.gif" />
        </td>
        <td style="vertical-align: middle">
            <h2>{PHP.L.blogger.del_blog_att}: <a href="{BLOG_URL}">{BLOG_TITLE}</a></h2>
        </td>
    </tr>
</table>
    <p class="desc">{PHP.L.blogger.del_blog_desc}</p>
</div>

<p class="margintop10">{PHP.L.blogger.del_blog_why}</p>

<form name="deleteblog" id="deleteblog" action="{FORM_ACTION}" method="post">
    <input type="hidden" name="act" value="deleteblog"  />
    <textarea style="width: 98%; height: 150px" id="del_blog_message" name="del_blog_message"></textarea>

    <p class="margintop10 textcenter"><strong>{PHP.L.blogger.del_blog_quest}?</strong></p>
    <div class="textcenter" style="text-align:center">
        <button type="submit" class="btn-danger"><i class="icon-trash icon-white"></i> {PHP.L.Yes}</button>
        <a class="btn btn-info" href="{BLOG_URL}"><i class="icon-ban-circle icon-white"></i> {PHP.L.Cancel}</a>
    </div>
</form>
<!-- END: MAIN -->
