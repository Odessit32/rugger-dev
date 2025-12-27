<!DOCTYPE html>
<html>
<head>
	<title>Административная панель</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="referrer" content="no-referrer-when-downgrade">
	<meta name="robots" content="noindex, nofollow">
	<!-- Отключаем prerendering и prefetching в Chrome -->
	<meta http-equiv="x-dns-prefetch-control" content="off">
	<!-- Bootstrap styles -->
	{*<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">*}
	<link rel="stylesheet" type="text/css" href="js/ui/jquery-ui.min.css" />
	<link rel="stylesheet" type="text/css" href="js/ui/jquery-ui.structure.min.css" />
	<link rel="stylesheet" type="text/css" href="js/ui/jquery-ui.theme.min.css" />
	<link rel="stylesheet" type="text/css" href="js/jquery.datetimepicker.css" />
	<link rel="stylesheet" href="../css/big_image.css" type="text/css" media="screen" />
	<link rel="stylesheet" type="text/css" href="style.css?2017-11-28" />
	<style>
	/* TinyMCE - убираем скругления для соответствия дизайну админки */
	.tox .tox-editor-container,
	.tox .tox-edit-area__iframe,
	.tox .tox-toolbar-overlord,
	.tox .tox-toolbar__primary,
	.tox .tox-toolbar__overflow,
	.tox .tox-statusbar,
	.tox .tox-tbtn,
	.tox .tox-split-button,
	.tox .tox-menubar,
	.tox.tox-tinymce {
		border-radius: 0 !important;
	}
	/* Блок "Читайте также" */
	.selected_related_news {
		display: inline-block;
		padding: 5px 10px;
		background: #e8f4e8;
		border: 1px solid #4a4;
		margin: 3px 0;
	}
	.selected_related_news a {
		color: #c00;
		text-decoration: none;
		margin-left: 10px;
	}
	.related_news_input {
		border: 1px solid #ccc;
	}
	</style>

	<link rel="shorcut icon" type="image/x-ico" href="favicon.ico" />
	<meta http-equiv="Content-language" content="ru-RU">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/admin_big_image.js"></script>
	<script type="text/javascript" src="js/ui/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/tinymce/tinymce.min.js?v=7.6.1.2"></script>
	<script type="text/javascript" src="js/jquery.datetimepicker.js"></script>
	<script type="text/javascript" src="js/admin_js.js?2025-12-26-v2"></script>

	<!-- file upload scripts BEGIN -->
	<script type="text/javascript" src="js/fileupload/vendor/jquery.ui.widget.js"></script>
	<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
	<script src="//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
	<!-- The Canvas to Blob plugin is included for image resizing functionality -->
	<script src="//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
	<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
	<script type="text/javascript" src="js/fileupload/jquery.iframe-transport.js"></script>
	<!-- The basic File Upload plugin -->
	<script type="text/javascript" src="js/fileupload/jquery.fileupload.js"></script>
	<!-- The File Upload processing plugin -->
	<script type="text/javascript" src="js/fileupload/jquery.fileupload-process.js"></script>
	<!-- The File Upload image preview & resize plugin -->
	<script type="text/javascript" src="js/fileupload/jquery.fileupload-image.js"></script>
	<!-- The File Upload audio preview plugin -->
	<script type="text/javascript" src="js/fileupload/jquery.fileupload-audio.js"></script>
	<!-- The File Upload video preview plugin -->
	<script type="text/javascript" src="js/fileupload/jquery.fileupload-video.js"></script>
	<!-- The File Upload validation plugin -->
	<script type="text/javascript" src="js/fileupload/jquery.fileupload-validate.js"></script>
	<!-- file upload scripts END -->

<script type="text/javascript">
{literal}
tinymce.init({
	selector: 'textarea:not(.mceNoEditor)',
	plugins: 'autolink image link media lists autoresize code fullscreen table',
	toolbar: 'undo redo | blocks | bold italic strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | table | removeformat code fullscreen',
	image_advtab: true,
	language: 'ru',
	autoresize_max_height: window.innerHeight - 100,
	promotion: false,
	branding: false,
	extended_valid_elements: 'script[charset|defer|language|src|type],iframe[src|width|height|frameborder|allow|allowfullscreen|loading|title|class|style]',
	license_key: 'gpl',
	// Отключаем sandbox для всех iframe
	sandbox_iframes: false,
	// Фикс для корректных путей к изображениям
	relative_urls: false,
	remove_script_host: true,
	convert_urls: true,
	// Размеры изображений по умолчанию: 100% ширина, авто высота
	image_dimensions: false,
	image_default_class: 'img-responsive',
	image_class_list: [
		{title: 'Адаптивное (100%)', value: 'img-responsive'},
		{title: 'Оригинальный размер', value: 'img-original'},
		{title: 'По центру', value: 'img-center'},
		{title: 'Слева', value: 'img-left'},
		{title: 'Справа', value: 'img-right'}
	],
	content_style: 'img { max-width: 100%; height: auto; } img.img-responsive { width: 100%; height: auto; } img.img-center { display: block; margin: 0 auto; } img.img-left { float: left; margin: 0 15px 15px 0; } img.img-right { float: right; margin: 0 0 15px 15px; }',
	// Очистка стилей при вставке из Word и других источников
	paste_as_text: false,
	paste_remove_styles_if_webkit: true,
	paste_strip_class_attributes: 'all',
	paste_retain_style_properties: '',
	paste_word_valid_elements: 'p,b,strong,i,em,h1,h2,h3,h4,h5,h6,ul,ol,li,a[href],br',
	paste_preprocess: function(plugin, args) {
		// Удаляем все inline стили
		args.content = args.content.replace(/\s*style="[^"]*"/gi, '');
		// Удаляем все классы кроме наших
		args.content = args.content.replace(/\s*class="(?!img-)[^"]*"/gi, '');
		// Удаляем font теги
		args.content = args.content.replace(/<\/?font[^>]*>/gi, '');
		// Удаляем span без атрибутов (остатки от Word)
		args.content = args.content.replace(/<span\s*>([^<]*)<\/span>/gi, '$1');
		// Удаляем пустые теги
		args.content = args.content.replace(/<(\w+)[^>]*>\s*<\/\1>/gi, '');
	},
	setup: function(editor) {
		editor.on('BeforeSetContent', function(e) {
			// Автоматически добавляем класс img-responsive к вставляемым изображениям
			if (e.content) {
				e.content = e.content.replace(/<img(?![^>]*class=)([^>]*)>/gi, '<img class="img-responsive"$1>');
			}
		});
	}
});
{/literal}

var param_sl = '{$session_login}';
</script>
</head>

<body{if !empty($smarty.get.cont)} onLoad="javascript:showTextLang('cont_{$smarty.get.cont}')"{/if}>

{if !empty($login_name)}
	
	<table width="980" height="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 10px auto; text-align: left;">
	<tr>
    	<td width="200" valign="top">
 			<br>
			<br>
			<a href="{$sitepath}" class="home_link" target="_blank">{$sitepath}</a>
			<div class="left_menu">
				<img src="images/transparent.gif" width="200" height="1" alt="" border="0">
				{include file='admin_menu.tpl'}
				<a href="?logout=1">Выход</a><br>
			</div>
		</td>
		<td width="780" valign="top" class="content">
			<br>
			{if !empty($ok_message)}<div class="message_ok">{$ok_message}</div>{/if}
			{if !empty($error_message)}<div class="message_error">{$error_message}{if !empty($error_code)}<br>{$error_code}{/if}</div>{/if}
			{include file='admin_content.tpl'}
		</td>
	</tr>
	</table>

{else}
    {literal}
    <script>
        $(document).ready(function(){
            document.forms['loginform'].elements['login_name'].focus();
        });
    </script>
    {/literal}
<div class="login">&nbsp;
    <div class="logo_login"></div>
	<div class="conteinersmall">
	{if !empty($error_message)}<b style="color:#f00;">{$error_message}</b>{/if}
    <form method="post" name="loginform" action="?{if $smarty.get}{foreach key=key item=item from=$smarty.get name=get}{$key}={$item}{if !$smarty.foreach.get.last}&{/if}{/foreach}{/if}">
         Логин:<br>
		 <input type="text" name="login_name" autocomplete="off"><br />
         Пароль:<br>
		 <input type="password" name="password" autocomplete="off"><br /><br>
         <input type="submit" name="login" value="&nbsp;&nbsp;&nbsp;Вход&nbsp;&nbsp;&nbsp;" class="submitok">
    </form>
	<br>
	</div>
</div>
{/if}

</body>
</html>
