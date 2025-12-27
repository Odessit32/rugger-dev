<!DOCTYPE html>
<html lang="{$sLang|default:'ru'}">
<head>
	<title>{if $meta_seo_item.title}{$meta_seo_item.title}{else}{if $page_item.title}{$page_item.title} :: {/if}{$conf_vars.title}{/if}</title>
	{if $meta_seo_item.description}<meta name="description" content="{$meta_seo_item.description}" />
	{/if}{if $meta_seo_item.keywords}<meta name="keywords" content="{$meta_seo_item.keywords}" />
	{/if}<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="content-language" content="{$sLang}" />
	<meta property="og:title" content="{if $meta_seo_item.title}{$meta_seo_item.title}{else}{$page_item.title}{/if}"/>
	<meta property="og:type" content="article"/>
	<meta property="og:url" content="{$sitepath}{$page_item.page_path}{$page_item.p_adress}"/>
	<meta property="og:site_name" content="{$conf_vars.title}"/>
	{if $page_item.description_meta != ''}<meta property="og:description" content="{$page_item.description_meta}"/>{/if}
	<link rel="icon" href="{$imagepath}favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="{$imagepath}favicon.ico" type="image/x-icon" />
	<link rel="manifest" href="/manifest.webmanifest" />
	
	{if $ENV =='PRODUCTION'}
		<link rel="stylesheet" href="{$imagepath}css/styles.min.css?v=1.1.0" type="text/css">
	{else}
		<link rel="stylesheet" type="text/css" href="{$imagepath}css/jquery-ui.min.css">
		<link rel="stylesheet" href="{$imagepath}css/style.css?v=1.1.0" type="text/css">
	{/if}
	<script type="text/javascript" src="{$imagepath}jscripts/jquery-3.7.1.min.js"></script>
	<script type="text/javascript" src="{$imagepath}jscripts/jquery.cycle2.min.js"></script>
	<base href="{$sitepath_lang}">
	<!--[if lte IE 7]>
	<link rel="stylesheet" type="text/css" href="{$sitepath}css/main_ie.css" />
	<![endif]-->
	<!--[if lte IE 8]>
	<script src="{$sitepath}jscripts/DD_belatedPNG.js" type="text/javascript"></script>
	<script>
		DD_belatedPNG.fix("#logo");
	</script>
	<![endif]-->
	{include file="highslide.tpl"}
	{include file="google_analitics.tpl"}
    {include file="seo_head.tpl"}
</head>
<body>
<div class="content">
	{include file="header.tpl"}
	<div class="content_block">
		<div class="left_block">
			<div class="page_content">

				<div class="gray_caption">
					{if $page_item.p_is_title == 'yes'}<h1>{$page_item.title}</h1>{/if}
					{if !empty($admin_user) && (!empty($admin_user.admin_status) || !empty($admin_user.publisher_status))}&nbsp; [<a href="{$imagepath}admin/?show=pages&get=edit&item={$page_item.p_id}" target="_blank">edit</a>]{/if}
					{if $page_item.p_is_socials == 'yes'}{include file="social.tpl"}{/if}
				</div>

                <div class="content_post border">
                
                    {if $page_item.p_is_description == 'yes'}
                        <div class="description">{$page_item.description}</div>
                    {/if}
                
                    {if $page_item.p_is_text == 'yes'}
                        {if $ph_page_item && is_array($ph_page_item) && $ph_page_item.pe_is_on_text == 'yes' && !empty($ph_page_item.photo_main)}
                            <div class="pe_photo">
                                <a href="{$imagepath}upload/photos{$ph_page_item.photo_main.ph_big}" data-fancybox="gallery" data-caption="{$ph_page_item.photo_main.ph_alt}" title="{$ph_page_item.photo_main.ph_alt}" >
                                    <img src="{$imagepath}upload/photos{$ph_page_item.photo_main.ph_med}" alt="{$ph_page_item.photo_main.ph_alt}" />
                                </a>
                            </div>
                        {/if}
                        {$page_item.text}
                    {/if}
                </div>

				{if !empty($page_item.scrol)}
					{foreach key=key item=item from=$page_item.scrol name="b_m"}
						{if $item.title != ''}
							<div class="post">
								<div class="scrol_title"><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$item.adress}">{$item.title}</a></div>
								{if $item.photo.ph_id}
									<div class="descr_photo">
										<div class="photo">
											<a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$item.adress}">
												<img src="{$imagepath}{$item.photo.ph_med}" border="0">
											</a>
										</div>
										{$item.description}
									</div>
								{else}
									{$item.description}
								{/if}
							</div>
						{/if}
					{/foreach}
				{/if}

				{if !empty($ph_page_item)}
					{if $ph_page_item.pe_style == 1}
						<div class="gallery_item_med">
							<ul id="gallery_med">
								{foreach key=key item=item from=$ph_page_item.photos name="photos"}
									<li class="item" id="i_{$smarty.foreach.photos.iteration}">
										<a href="{$imagepath}upload/photos{$item.ph_big}" data-fancybox="gallery"><img src="{$imagepath}upload/photos{$item.ph_med}" border="0" alt=""></a>
										<div class="about">{$item.ph_about}</div>
									</li>
								{/foreach}
							</ul>
						</div>
					{/if}
				{/if}

				{if !empty($page_item.files) && $page_item.p_is_files == 'yes'}
					<div class="files">
						{if $page_item.p_title_files != ''}<h3>{$page_item.p_title_files}</h3>{/if}
						{foreach key=key item=item from=$page_item.files}
							<div class="item">
								<div class="text">
									{$item.about}<br>
									<a href="{$imagepath}upload/files{$item.f_folder}{$item.f_path}">{$item.f_path}</a> <i>({$item.size}).</i>
								</div>
								<div class="ico">
									<a href="{$imagepath}upload/files{$item.f_folder}{$item.f_path}"><img src="{$imagepath}{$item.ico}" border="0"></a>
								</div>
							</div>
						{/foreach}
					</div>
				{/if}

				{if !empty($page_item.pvs)}
					<div class="pvs">
						{foreach key=key item=item from=$page_item.pvs}
							{if $item.pvs_service == 'youtube'}<iframe width="460" height="400" src="//www.youtube.com/embed/{$item.pvs_code}" frameborder="0" allowfullscreen></iframe>{/if}
						{/foreach}
					</div>
				{/if}

			</div>


		</div>
		<div class="right_block">
			{include file="right.tpl"}
		</div>
	</div>
	<div class="content_end"></div>
</div>
{include file="footer.tpl"}

</body>
</html>
