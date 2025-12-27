<div class="conteiner_meta">
<div class="conteiner_meta_title">Мета теги для SEO</div>
<div class="conteiner_meta_content" style="display: none;">
<form method="post">
    <input type="hidden" name="meta_seo_item_type" value="{$meta_seo_item_type}">
    <input type="hidden" name="meta_seo_item_id" value="{$meta_seo_item_id}">
    <table width="95%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td align="center" valign="middle">
                <input type="submit" name="save_meta_seo" class="submitsave" value="Сохранить">
                <br><br>
            </td>
        </tr>
    </table>
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr><td colspan="3" height="1" bgcolor="#b2b2b2"></td></tr>
        <tr>
            {if $main_settings.rus.sl_is_active == 'yes'}<td width="33%" class="td_link active_left"><a href="#meta_seo_cont_1" class="meta_seo_lang">РУС</a></td>{/if}
            {if $main_settings.ukr.sl_is_active == 'yes'}<td width="34%" class="td_link notactive"><a href="#meta_seo_cont_2" class="meta_seo_lang">УКР</a></td>{/if}
            {if $main_settings.eng.sl_is_active == 'yes'}<td width="33%" class="td_link notactive"><a href="#meta_seo_cont_3" class="meta_seo_lang">ENG</a></td>{/if}
        </tr>
    </table>
    <br>
    <div class="meta_seo_cont" id="meta_seo_cont_1">
        <table width="80%" cellspacing="0" cellpadding="0" border="0" class="form_input">
            <tr>
                <th width="20%" align="center">Title (РУС): </th>
                <td width="80%"><input type="text" name="title_ru" value="{$meta_seo_item.title_ru}" class="input100"></td>
            </tr>
            <tr><td colspan="2" width="100%" align="center"><b>Description (РУС):</b> <br><textarea name="description_ru" rows="5" style="width: 100%;">{$meta_seo_item.description_ru}</textarea></td></tr>
            <tr><td colspan="2" width="100%" align="center"><b>Keywords (РУС):</b> <br><textarea name="keywords_ru" style="width: 100%;">{$meta_seo_item.keywords_ru}</textarea></td></tr>
        </table>
    </div>
    <div class="meta_seo_cont" id="meta_seo_cont_2">
        <table width="80%" cellspacing="0" cellpadding="0" border="0" class="form_input">
            <tr>
                <th width="20%" align="center">Title (УКР): </th>
                <td width="80%"><input type="text" name="title_ua" value="{$meta_seo_item.title_ua}" class="input100"></td>
            </tr>
            <tr><td colspan="2" width="100%" align="center"><b>Description (УКР):</b> <br><textarea name="description_ua" rows="5" style="width: 100%;">{$meta_seo_item.description_ua}</textarea></td></tr>
            <tr><td colspan="2" width="100%" align="center"><b>Keywords (УКР):</b> <br><textarea name="keywords_ua" style="width: 100%;">{$meta_seo_item.keywords_ua}</textarea></td></tr>
        </table>
    </div>
    <div class="meta_seo_cont" id="meta_seo_cont_3">
        <table width="80%" cellspacing="0" cellpadding="0" border="0" class="form_input">
            <tr>
                <th width="20%" align="center">Title (ENG): </th>
                <td width="80%"><input type="text" name="title_en" value="{$meta_seo_item.title_en}" class="input100"></td>
            </tr>
            <tr><td colspan="2" width="100%" align="center"><b>Description (ENG):</b> <br><textarea name="description_en" rows="5" style="width: 100%;">{$meta_seo_item.description_en}</textarea></td></tr>
            <tr><td colspan="2" width="100%" align="center"><b>Keywords (ENG):</b> <br><textarea name="keywords_en" style="width: 100%;">{$meta_seo_item.keywords_en}</textarea></td></tr>
        </table>
    </div>
</form>
</div>
</div>
{literal}
<script>
    jQuery(document).ready(function($){
        $(".conteiner_meta_title").on('click', function(){
            if ($(".conteiner_meta_content").is(":visible")) {
                $(".conteiner_meta_content").slideUp(400, 'swing')
            } else {
                $(".conteiner_meta_content").slideDown(400, 'swing')
            }
            return false;
        });
        $(".meta_seo_lang").on('click', function(){
            var _that = this;
            var _href = $(_that).attr('href');
            var _par = $(_that).parent('.td_link');
            if (!$(_href).is(":visible")){
                $(".meta_seo_cont").hide();
                $(_href).show();
                $(".td_link").removeClass("active").removeClass("active_left").removeClass("active_right").addClass("notactive");
                _par.removeClass("notactive");
                if (_par.index() == 0) {
                    _par.addClass("active_left");
                } else if (_par.index() == $('.td_link').length-1) {
                    _par.addClass("active_right");
                } else {
                    _par.addClass("active");
                }
            }
            return false;
        });
    });
</script>
{/literal}