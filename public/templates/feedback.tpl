<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{if !empty($meta_seo_item.title)}{$meta_seo_item.title}{else}{if !empty($page_item.title)}{$page_item.title} :: {/if}{$conf_vars.title}{/if}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="content-language" content="{$sLang}" />
	<meta property="og:title" content="{$page_item.title}"/>
	<meta property="og:type" content="article"/>
	<meta property="og:url" content="{$sitepath}{$page_item.page_path}{$page_item.p_adress}"/>
	<meta property="og:site_name" content="{$conf_vars.title}"/>
	{if $page_item.description_meta != ''}<meta property="og:description" content="{$page_item.description_meta}"/>{/if}
	<link rel="icon" href="{$imagepath}favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="{$imagepath}favicon.ico" type="image/x-icon" />
	{if $ENV =='PRODUCTION'}
		<link rel="stylesheet" href="{$imagepath}css/styles.min.css?v=1.1.0" type="text/css">
	{else}
		<link rel="stylesheet" type="text/css" href="{$imagepath}css/jquery-ui.min.css">
		<link rel="stylesheet" href="{$imagepath}css/style.css?v=1.1.0" type="text/css">
	{/if}
	<script type="text/javascript" src="{$imagepath}jscripts/jquery-3.7.1.min.js"></script>
	<script type="text/javascript" src="{$imagepath}jscripts/jquery.cycle2.min.js"></script>
	<script type="text/javascript" src="{$imagepath}jscripts/index.js"></script>
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
</head>
<body>
<div class="content">
	{include file="header.tpl"}
	<div class="content_block">
		<div class="left_block">
			<div class="breadcrumbs">
				<a href="{$sitepath}">{$conf_vars.main}</a> <b>/</b>
				{if $pages}
					{foreach key=key item=item from=$pages name="b_m"}
						<a href="{$sitepath}{$item.page_path}{$item.p_adress}">{$item.title}</a> <b>/</b>
					{/foreach}
				{/if}
				{$page_item.title}
			</div>
			<div class="title_caption title_caption_page">
				{if $page_item.p_is_title == 'yes'}<h1>{$page_item.title}</h1>{/if}
			</div>

			<div class="page_content">
				<div class="content_post">




					{if !empty($message_item)}
						<div class="feedback">
							<h2>Содержание сообщения:</h2>
							<div class="item">
								<div class="title">{$language.Name}:</div><p>{$message_item.fb_name}</p>
							</div>
							{*<div class="item">
                                <div class="title">E-mail:</div><p>{$message_item.fb_email}</p>
                            </div>*}
							<div class="item">
								<div class="title">{$language.Theme}:</div><p>{$message_item.fb_title}</p>
							</div>
							<div class="item">
								<div class="title">{$language.Text}:</div><p>{$message_item.fb_text}</p>
							</div>
							{if $message_item.fb_response != ''}<br />
								<h2>{$language.Answer}:</h2>
								<div class="item">
									<div class="title">{$language.Text}:</div><p>{$message_item.fb_response}</p>
								</div>
							{/if}
							<div class="item">
								<div class="title">Статус:</div>
								{if $message_item.fb_is_viewed == 'yes'}
									<p>{$language.fb_is_viewed_message}</p>
								{else}
									<p>{$language.fb_is_not_viewed_message}</p>
								{/if}
								{if $message_item.fb_is_posted == 'yes'}
									<p>{$language.fb_is_posted_message}</p>
								{/if}
							</div>
						</div>
					{else}
						{if !empty($message) && empty($message.error)}
							<div class="post">
								{$language.fb_thx_message}
								<p>
									<a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$message.fb_locator}-{$message.fb_id}">
										{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$message.fb_locator}-{$message.fb_id}
									</a>
								</p>
							</div>
						{else}
							{if $page_item.p_is_description == 'yes'}<div class="description">{$page_item.description}</div>{/if}
							{if $page_item.p_is_text == 'yes'}
								{if !empty($ph_page_item) && $ph_page_item.pe_is_on_text == 'yes'}
									<div class="pe_photo">
										<a href="{$imagepath}upload/photos{$ph_page_item.photo_main.ph_big}" onclick="return hs.expand(this)" title="{$ph_page_item.photo_main.ph_alt}" >
											<img src="{$imagepath}upload/photos{$ph_page_item.photo_main.ph_med}" alt="{$ph_page_item.photo_main.ph_alt}" />
										</a>
									</div>
								{/if}
								{$page_item.text}
							{/if}
							<div class="feedback">
								<form method="post" name="user">
									{if !empty($message)}
										{if !empty($message.error.count)}
											<div class="error_">
												{$language.fb_error_count}
											</div>
										{/if}
										{if !empty($message.error.message)}
											<div class="error_">
												{$language.fb_error_unique}
											</div>
										{/if}
										{if !empty($message.error) && empty($message.error.message) && empty($message.error.count)}
											<div class="error_">
												{$language.fb_error_not_send}
											</div>
										{/if}
									{/if}
									<h2>{$language.Who_are_you}?<span class="red">*</span></h2>

									<div class="item">
										<div class="title">{$language.Name}:<span class="red">*</span></div>
										{if !empty($message.error.name)}<div class="error">{$language.fb_error_name}</div>{/if}
										<input type="text" name="name" value="{if !empty($smarty.post.name)}{$smarty.post.name}{/if}"
											   class="{if !empty($message.error.name)}error_{/if}input">
									</div>
									<div class="item">
										<div class="title">E-mail:<span class="red">*</span></div>
										{if !empty($message.error.email)}<div class="error">{$language.fb_error_email}</div>{/if}
										<input type="text" name="email" value="{if !empty($smarty.post.email)}{$smarty.post.email}{/if}"
											   class="{if !empty($message.error.email)}error_{/if}input">
									</div>

									<br><br>

									<h2>{$language.Your_message}<span class="red">*</span></h2>

									<div class="item">
										<div class="title">{$language.Theme}:</div>
										<input type="text" name="title" value="{if !empty($smarty.post.title)}{$smarty.post.title}{/if}" class="input">
									</div>
									<div class="item">
										<div class="title">{$language.Text}:<span class="red">*</span></div>
										{if !empty($message.error.text)}<div class="error">{$language.fb_error_text}</div>{/if}
										<textarea name="text" class="{if !empty($message.error.text)}error_{/if}textarea">{if !empty($smarty.post.text)}{$smarty.post.text}{/if}</textarea>
									</div>

									<div class="item">
										<div class="title"> </div>
										{if !empty($message.error.recaptcha)}<div class="error">{$language.fb_error_recaptcha}</div>{/if}
										<div class="g-recaptcha" data-sitekey="6LceQyAUAAAAAAvlfVYMaOqx1P348FvU-LIeDbjX"></div>
									</div>

									<div class="item">
										<div class="title"> </div>
										<input type="submit" name="send_message" value="{$language.Send}" class="submit_mail">
									</div>

								</form>
							</div>

							{if $page_item.files and $page_item.p_is_files == 'yes'}
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

						{/if}
					{/if}
				</div>
			</div>


		</div>
		<div class="right_block">
			{include file="right.tpl"}
		</div>
	</div>
	<div class="content_end"></div>
</div>
{include file="footer.tpl"}
<script src='https://www.google.com/recaptcha/api.js'></script>
</body>
</html>
