<H1>Главная страница сайта:</H1>

	<div id="conteiner">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="50%" id="{if empty($smarty.get.get) || $smarty.get.get !== 'footer'}active_left{else}notactive{/if}"><a href="?show=main">настройки</a></td>
				<td width="50%" id="{if !empty($smarty.get.get) && $smarty.get.get == 'footer'}active_right{else}notactive{/if}"><a href="?show=main&get=footer">Подвал</a></td>
			</tr>
		</table>
	
		<h1>Настройки</h1>
		
		<br>
		<center>
		{if empty($smarty.get.get) || $smarty.get.get !== 'footer'}
			<select id="input" style="width: 200px;" onchange="document.location.href='?show=main&get='+this.value">
				<option value=""{if empty($smarty.get.get)} selected{/if}>Заголовок</option>
				<option value="meta"{if !empty($smarty.get.get) && $smarty.get.get == 'meta'} selected{/if}>Meta</option>
				<option value="lang"{if !empty($smarty.get.get) && $smarty.get.get == 'lang'} selected{/if}>Языки</option>
				<option value="vars"{if !empty($smarty.get.get) && $smarty.get.get == 'vars'} selected{/if}>Переменные</option>
				<option value="mbi_counts"{if !empty($smarty.get.get) && $smarty.get.get == 'mbi_counts'} selected{/if}>Количество баннеров и информеров</option>
				<option value="banner_informer"{if !empty($smarty.get.get) && $smarty.get.get == 'banner_informer'} selected{/if}>Баннеры и информеры</option>
				<option value="caching"{if !empty($smarty.get.get) && $smarty.get.get == 'caching'} selected{/if}>Кеширование</option>
			</select>
		{/if}
		
		
		
		{if empty($smarty.get.get)}
			{include file="main/admin_main_title.tpl"}
		{else}
			{if $smarty.get.get == 'meta'}{include file="main/admin_main_meta.tpl"}{/if}
			{if $smarty.get.get == 'lang'}{include file="main/admin_main_lang.tpl"}{/if}
			{if $smarty.get.get == 'vars'}{include file="main/admin_main_vars.tpl"}{/if}
			{if $smarty.get.get == 'mbi_counts'}{include file="main/admin_main_mbi_counts.tpl"}{/if}
			{if $smarty.get.get == 'banner_informer'}{include file="main/admin_main_banner_informer.tpl"}{/if}
			
			{if $smarty.get.get == 'footer'}{include file="main/admin_main_footer.tpl"}{/if}
			{if $smarty.get.get == 'caching'}{include file="main/admin_main_cache.tpl"}{/if}
		{/if}
		</center>
		<br>
	
	</div>