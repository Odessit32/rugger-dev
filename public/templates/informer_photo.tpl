{strip}
{if $informer_photo && $informer_photo.photos|@count > 0}

{*<script type="text/javascript" src="{$imagepath}jscripts/jquery.cycle.all.js"></script>*}
<script>
    {literal}
    jQuery( document ).ready(function($) {
        if ($('#photo_informer').length>0) {
            // jQuery Cycle2 syntax
            $('#photo_informer').cycle({
                fx: 'fade',
                speed: 500,
                timeout: 3000,
                pauseOnHover: true,
                pager: '#phi_nav',
                pagerEvent: 'mouseover',
                pagerTemplate: '',
                slides: '> a'
            });
        }
        $("body").on('click', ".nav_control .nav_control_prev", function() {
            $('#photo_informer').cycle('prev');
        });

        $("body").on('click', ".nav_control .nav_control_next", function() {
            $('#photo_informer').cycle('next');
        });
    });
    {/literal}
</script>

<div class="informer inf_photo"><!-- photos -->
    <div class="title title_photo"><a href="{if $section_address}{$sitepath}{$section_address}{else}{$sitepath}{/if}{if $section_address == $sitepath || !$section_address}{/if}photos">{$conf_vars.inform_photo}</a></div>
    <div class="nav_control">
        <a href="#next" class="nav_control_next"></a>
        <a href="#prev" class="nav_control_prev"></a>
    </div>
	<div class="current photo_slider" id="photo_informer">
		{foreach key=key item=item from=$informer_photo.photos name=inf_photo}
		<a href="{if $section_address}{$sitepath}{$section_address}{else}{$sitepath}{/if}{if $section_address == $sitepath || !$section_address}{/if}photos/gal{$item.ph_gallery_id}" class="slide_item"
           title="{$item.ph_about}" style="background-image: url('{$imagepath}{$item.ph_big}');"></a>
		{/foreach}
	</div>

	<div class="preview photo_preview" id="phi_nav">
		<ul>
			{foreach key=key item=item from=$informer_photo.photos name=inf_photo}<li><a href="#"><img src="{$imagepath}{$item.ph_small}" alt="{$item.ph_about}" loading="lazy" /></a></li>{/foreach}
		</ul>
	</div>
	<a href="{if $section_address}{$sitepath}{$section_address}{else}{$sitepath}{/if}{if $section_address == $sitepath || !$section_address}{/if}photos/gal{$informer_photo.phg_id}" class="title_phg">{$informer_photo.phg_title}</a>
    <a href="{if $section_address}{$sitepath}{$section_address}{else}{$sitepath}{/if}{if $section_address == $sitepath || !$section_address}{/if}photos" class="all_photos">Перейти в фотогалерею</a>
</div>
{/if}
{/strip}