<!-- BEGIN: SELECT_SKIN -->
<!-- IF !{SS_AJAX} -->
<div id="blogger_new_theme">
<!-- ENDIF -->
	<!-- BEGIN: SELECT_SKIN_ROW -->
		<div id="{SS_ROW_BLOCK_ID}" style="float:left; width:{SS_BLOCK_WIDTH}%; text-align:center">
			<p class="strong">{SS_ROW_THEME_TITLE}</p>
			<div>
				<img src="{SS_ROW_THUMB}" title="{SS_ROW_THEME_TITLE} - {SS_ROW_SCHEME_TITLE}"
                     onclick="bl_selectTheme('{SS_ROW_THEME_CODE}', '{SS_ROW_SCHEME_CODE}')" style="cursor:pointer" />
			</div>
			<p>
                <label>
                <input type="radio" name="rad_theme_select" value="{SS_ROW_BLOCK_ID}"
                       onclick="bl_selectTheme('{SS_ROW_THEME_CODE}', '{SS_ROW_SCHEME_CODE}')" />
                <strong>{SS_ROW_SCHEME_TITLE}</strong>
                </label>
            </p>
		</div>
		<!-- IF {SS_ROW_STRING_COMPL} == 1 -->
			<div style="clear:both; width:10px;"></div>
		<!-- ENDIF -->
	<!-- END: SELECT_SKIN_ROW -->
	
	<div class="clear"></div>

    <!-- IF {SS_PAGNAV} -->
	<div class="pagination textright">{SS_PAGEPREV} {SS_PAGNAV} {SS_PAGENEXT}</div>
    <!-- ENDIF -->

<!-- IF !{SS_AJAX} -->
</div>
<!-- ENDIF -->
<!-- END: SELECT_SKIN -->
