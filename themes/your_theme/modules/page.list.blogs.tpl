<!-- BEGIN: MAIN -->
<div id="breadcrumb">
	<div class="rss-icon-title">
		<a href="{LIST_CAT_RSS}"><img src="{PHP.cfg.mainurl}/{PHP.cfg.themes_dir}/{PHP.theme}/img/rss-icon.png" alt="" /></a>
	</div>
	<a href="{PHP.cfg.mainurl}"><img src="{PHP.cfg.mainurl}/{PHP.cfg.themes_dir}/{PHP.theme}/img/icon-home.gif" width="16" height="16" align="absmiddle" title="{PHP.cfg.maintitle}" /></a>
    {PHP.cfg.separator} {LIST_CATPATH}
</div>
		
<h1>{LIST_CATTITLE}</h1>

<!-- IF {PPVE_PRINT_VERSION} != '' OR {PPVE_PDF_VERSION} != '' OR {PPVE_EMAIL_TO_FRIEND} -->
<div style="text-align:right; margin:5px;">
	<noindex>{PPVE_PRINT_VERSION} {PPVE_PDF_VERSION} {PPVE_EMAIL_TO_FRIEND}</noindex>
</div>
<!-- ENDIF -->

{FILE "{PHP.cfg.themes_dir}/alex-natty-2/warnings.tpl"}

<!-- IF {LIST_CAT_TEXT} != '' AND {PHP.d} < 2 AND {PHP.dc} < 2 -->
   <div class="content">{LIST_CAT_TEXT}</div>
<!-- ELSE -->
   <div class="content text-justify">{LIST_CATDESC}</div>
<!-- ENDIF -->


<!-- IF {PHP.usr.isadmin} -->
<div style="margin-top: 20px;" class="text-right">
    <a class="btn btn-info btn-small" href="/admin.php?m=structure&n=page&id={PHP.cat.id}&x={PHP.sys.xk}" >
        <i class="icon-folder-open icon-white"></i> Редактировать описание раздела</a>
</div>
<!-- ENDIF -->



<!-- IF {LIST_BLOGGER_ADD} -->
<div class="margintop10 marginbottom10 text-right">{LIST_BLOGGER_ADD}</div>
<!-- ENDIF -->
<!-- IF {LIST_SUBMITNEWPAGE} != '' OR {LIST_GOTO_MYBLOG} != '' OR {LIST_ADD_RECORD} != '' OR {BLOGGER_LIST_EDIT} != '' || {LIST_BLOGGER_DEL} != '' -->
<div class="margintop10 marginbottom10 text-right">
    {LIST_SUBMITNEWPAGE} {LIST_ADD_RECORD} {LIST_GOTO_MYBLOG} {LIST_BLOGGER_EDIT} {LIST_BLOGGER_DEL}
</div>
<!-- ENDIF -->

<!-- IF {LISTCAT_PAGNAV} -->
<div class="pagination text-right">
    {LISTCAT_PAGEPREV}{LISTCAT_PAGNAV}{LISTCAT_PAGENEXT}
</div>
<!-- ENDIF -->

<!-- BEGIN: LIST_ROWCAT -->
    <div class="block_list">
        <table class="flat">
			<tr>
                <!-- IF {LIST_ROWCAT_OWNER_ID} -->
                <td style="width: 60px" class="centerall comment_avatar small lhn">
                    {LIST_ROWCAT_OWNER_NAME}<br />
                    <a href="{LIST_ROWCAT_URL}">{LIST_ROWCAT_OWNER_AVATAR}</a>
                </td>
                <!-- ENDIF -->
                 <td<!-- IF {LIST_ROWCAT_OWNER_ID} --> class="width90"<!-- ENDIF -->>
					<span style="float:left; margin-right:10px;">{LIST_ROWCAT_ICON}</span>
					<h3><a href="{LIST_ROWCAT_URL}">{LIST_ROWCAT_TITLE} ...</a></h3>
					<div class="desc" style="margin:0 0 0 15px">{LIST_ROWCAT_DESC}</div>
                    <!-- IF {LIST_ROWCAT_EDIT_URL} -->
                     <div class="text-right">{LIST_ROWCAT_EDIT} {LIST_ROWCAT_DEL}</div>
                    <!-- ENDIF -->
				</td>
			</tr>
		</table>
    </div>
<!-- END: LIST_ROWCAT -->

<!-- IF {LISTCAT_PAGNAV} -->
<div class="pagination text-right">
    {LISTCAT_PAGEPREV}{LISTCAT_PAGNAV}{LISTCAT_PAGENEXT}
</div>
<!-- ENDIF -->


<!-- IF {LIST_BLOGGER_ROOT} -->
<h3 style="margin-top: 40px">{PHP.L.blogger.recent_pages}:</h3>
{PHP|bl_pagesList('blogger.pageslist', 10)}
<div class="clearfix" style="margin-bottom: 20px"></div>
<!-- ENDIF -->


<!-- IF {LIST_TOP_TOTALPAGES} > 1 -->
<noindex>
    <div class="text-right desc small" style="margin-top: 20px">
    {PHP.L.ant.sort}:<br />{PHP.alex_natty.sort_select} {PHP.alex_natty.sort_wayImg}
    </div>
</noindex>
<!-- ENDIF -->

<!-- IF {LIST_TOP_PAGINATION} -->
<div class="pagination text-right" style="margin-top: 5px">
    {LIST_TOP_PAGEPREV}{LIST_TOP_PAGINATION}{LIST_TOP_PAGENEXT}
</div>
<!-- ENDIF -->

<!-- BEGIN: LIST_ROW -->
<div class="block_list">
    <h3><a href="{LIST_ROW_URL}" title="{LIST_ROW_SHORTTITLE}">{LIST_ROW_SHORTTITLE}</a>  {LIST_ROW_FILE_ICON}</h3>
    <div class="text-justify">
        <span class="desc">({LIST_ROW_BEGIN}) </span>
        <!-- IF {LIST_ROW_ID|att_count('page',$this,'images')} > 0 -->
            <a href="{LIST_ROW_URL}"><img src="{LIST_ROW_ID|att_get('page',$this)|att_thumb($this,123,74,'auto')}" alt="{LIST_ROW_SHORTTITLE}" align="left" style="margin: 0 5px 5px 0;" /></a>
        <!-- ENDIF -->
        <!-- IF {LIST_ROW_DESC} -->
            {LIST_ROW_DESC}
        <!-- ELSE -->
             {LIST_ROW_TEXT_CUT}
        <!-- ENDIF -->
    </div>
    <div class="text-right small desc">
        {LIST_ROW_RATINGS_DISPLAY} &nbsp;
            <!-- IF {LIST_ROW_AUTHOR} -->
                {PHP.L.Author}: <strong>{LIST_ROW_AUTHOR}</strong> |
            <!-- ENDIF -->
            <!-- IF {PHP.usr.isadmin} == TRUE -->
                ({PHP.L.Hits}: {LIST_ROW_COUNT}) |
            <!-- ENDIF -->
             {PHP.L.comments_comments}: {LIST_ROW_COMMENTS}
    </div>

    <!-- IF {PHP.usr.id} > 0 AND ( {LIST_ROW_OWNERID} == {PHP.usr.id} OR  {PHP.usr.isadmin} == 1 ) -->
    <div class="text-right">
        <a href="{LIST_ROW_ADMIN_EDIT_URL}" class="btn btn-mini">
            <i class="icon-edit"></i> {PHP.L.Edit}</a>

        <!-- IF {PHP.usr.isadmin} -->
        <a href="{LIST_ROW_ADMIN_UNVALIDATE_URL}" class="btn btn-mini confirmLink">
            <!-- IF {LIST_ROW_STATE} == 1 -->
                <i class="icon-check"></i> {PHP.L.Validate}
            <!-- ELSE -->
                <i class="icon-time"></i> {PHP.L.Putinvalidationqueue}
            <!-- ENDIF --></a>
        <!-- ENDIF -->
        <!-- IF {LIST_ROW_ADMIN_DELETE_URL} -->
        <a href="{LIST_ROW_ADMIN_DELETE_URL}" class="btn btn-mini confirmLink">
            <i class="icon-trash"></i> {PHP.L.Delete}</a>
        <!-- ENDIF -->
    </div>
    <!-- ENDIF -->
</div>
<!-- END: LIST_ROW -->

<!-- IF {LIST_TOP_PAGINATION} -->
<div class="pagination text-right">
    {LIST_TOP_PAGEPREV}{LIST_TOP_PAGINATION}{LIST_TOP_PAGENEXT}<br />
     <div class="desc italic small">
        {PHP.L.Page}: {LIST_TOP_CURRENTPAGE} {PHP.L.Of} {LIST_TOP_TOTALPAGES} {PHP.cfg.separator} {PHP.L.ant.linesperpage}: {LIST_TOP_MAXPERPAGE} {PHP.cfg.separator} {PHP.L.Total}: {LIST_TOP_TOTALLINES}
     </div>
</div>
<!-- ENDIF -->

<!-- IF {PHP.cfg.menu1} -->
<div style="margin:20px 0 10px 0">{PHP.cfg.menu1}</div>
<!-- ENDIF -->

{LIST_COMMENTS_DISPLAY}

<!-- IF {PHP.cfg.plugin.tags.noindex} == 1 -->
<noindex>
<!-- ENDIF -->
<div class="block">
    <h4 class="tags">{PHP.L.Tags}</h4>
    {LIST_TAG_CLOUD}
    {LIST_TAG_CLOUD_ALL_LINK} 
</div>
<!-- IF {PHP.cfg.plugin.tags.noindex} == 1 -->
</noindex>
<!-- ENDIF -->
<!-- END: MAIN -->
