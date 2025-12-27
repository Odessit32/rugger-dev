<!DOCTYPE html>
<html lang="{$sLang|default:'ru'}">
<head>
	<title>{if $conf_vars.404}{$conf_vars.404} :: {/if}{$conf_vars.title} </title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="content-language" content="{$sLang}" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="{$imagepath}favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="{$imagepath}favicon.ico" type="image/x-icon">
	{if $ENV =='PRODUCTION'}
		<link rel="stylesheet" href="{$imagepath}css/styles.min.css?v=1.1.0" type="text/css">
	{else}
		<link rel="stylesheet" type="text/css" href="{$imagepath}css/jquery-ui.min.css">
		<link rel="stylesheet" href="{$imagepath}css/style.css?v=1.1.0" type="text/css">
	{/if}
	<script type="text/javascript" src="{$imagepath}jscripts/jquery-3.7.1.min.js"></script>
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
			<div class="page_content">
				<div class="gray_caption">
					<a href="{$sitepath}">{$conf_vars.main}</a> <b>/</b>
					Ошибка 404
				</div>
				<div class="content_post">
					{if !empty($page_item.p_is_title) && $page_item.p_is_title == 'yes'}<h1>{$page_item.title}</h1>{/if}
					<h1>Ошибка - страница не существует или перемещена в другой раздел</h1>
					<p>Извините, но такой страницы нет. Попробуйте повторить попытку или вернитесь на главную - <a href="{$sitepath}">{$sitepath}</a></p>
					<br>
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

</body>
</html>
