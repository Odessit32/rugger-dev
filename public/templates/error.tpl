<!DOCTYPE html>
<html lang="{$sLang|default:'ru'}">
<head>
	<title>Ошибка :: {$conf_vars.title}</title>
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
	<base href="{$sitepath_lang}">
</head>
<body>
<div class="content">
	{include file="header.tpl"}
	<div class="content_block">
		<div class="left_block">
			<div class="page_content">
				<div class="content_post border">
					<h1>Произошла ошибка</h1>
					<p>К сожалению, произошла ошибка при обработке вашего запроса.</p>
					<p><a href="/">Вернуться на главную</a></p>
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
