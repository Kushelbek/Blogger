<!-- BEGIN: MAIN -->

<!-- BEGIN: PAGE_ROW -->
<div class="block_list">

    <table class="flat">
        <tr>
            <td style="width: 60px" class="centerall comment_avatar small lhn">
                {PAGE_ROW_OWNER_NAME}<br />
                <a href="{PAGE_ROW_URL}">{PAGE_ROW_OWNER_AVATAR}</a>
            </td>

            <td class="width90">
                <h3><a href="{PAGE_ROW_URL}" title="{PAGE_ROW_SHORTTITLE}">{PAGE_ROW_SHORTTITLE}</a>  {PAGE_ROW_FILE_ICON}</h3>
                <div class="textjustify">
                    <span class="desc">({PAGE_ROW_BEGIN}) </span>
                    <!-- IF {PAGE_ROW_DESC} -->
                        {PAGE_ROW_DESC}
                    <!-- ELSE -->
                        {PAGE_ROW_TEXT_CUT}
                    <!-- ENDIF -->
                </div>
                <div class="textright small desc">
                    <!-- IF {PAGE_ROW_AUTHOR} -->
                {PHP.L.Author}: <strong>{PAGE_ROW_AUTHOR}</strong> |
                    <!-- ENDIF -->
                    <!-- IF {PHP.usr.isadmin} == TRUE -->
                    ({PHP.L.Hits}: {PAGE_ROW_COUNT}) |
                    <!-- ENDIF -->
                {PHP.L.comments_comments}: {PAGE_ROW_COMMENTS}
                </div>

                <!-- IF {PHP.usr.id} > 0 AND ( {PAGE_ROW_OWNERID} == {PHP.usr.id} OR  {PHP.usr.isadmin} == 1 ) -->
                <div class="textright">
                    <a href="{PAGE_ROW_ADMIN_EDIT_URL}" class="btn btn-mini">
                        <i class="icon-edit"></i> {PHP.L.Edit}</a>

                    <!-- IF {PHP.usr.isadmin} -->
                    <a href="{PAGE_ROW_ADMIN_UNVALIDATE_URL}" class="btn btn-mini confirmLink">
                        <!-- IF {PAGE_ROW_STATE} == 1 -->
                        <i class="icon-check"></i> {PHP.L.Validate}
                        <!-- ELSE -->
                        <i class="icon-time"></i> {PHP.L.Putinvalidationqueue}
                        <!-- ENDIF --></a>
                    <!-- ENDIF -->
                    <!-- IF {PAGE_ROW_ADMIN_DELETE_URL} -->
                    <a href="{PAGE_ROW_ADMIN_DELETE_URL}" class="btn btn-mini confirmLink">
                        <i class="icon-trash"></i> {PHP.L.Delete}</a>
                    <!-- ENDIF -->
                </div>
                <!-- ENDIF -->

            </td>
        </tr>
    </table>

</div>
<!-- END: PAGE_ROW -->

<!-- END: MAIN -->