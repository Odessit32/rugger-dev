<H1>Backup базы данных:</H1>

	<div id="conteiner">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td width="33%" id="{if empty($smarty.get.get)}active_left{else}notactive{/if}"><a href="?show=db_backup">Список файлов</a></td>
				<td width="33%" id="{if $smarty.get.get == 'new_backup'}active_right{else}notactive{/if}"><a href="?show=db_backup&get=new_backup">Сделать новый backup</a></td>
			</tr>
		</table>
		
	{if empty($smarty.get.get)}
		<h1>Список файлов</h1>
		<center>
		{if $backup_list}
		<table width="80%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			<tr>
				<th>файл</th>
				<th>дата</th>
				<th>размер</th>
			</tr>
			{foreach key=key item=item from=$backup_list name=backup_list}
			<tr onmouseover="javascript: this.style.background = '#eee'" onmouseout="javascript: this.style.background = ''">
				<td align="center"><b>{$item.name}</b></td>
				<td align="center">{$item.edit_time|date_format:"%d.%m.%Y %H:%M:%S"}</td>
				<td align="center">{$item.size}</td>
			</tr>
			{/foreach}
			
		</table>
		{else}
			Список пуст
		{/if}
			<br>
		</center>
	{/if}
		
	{if $smarty.get.get == 'new_backup'}
		<h1>Добавление нового backup</h1>
		<center>
			<form method="post">
				<input type="submit" name="add_new_backup" id="submitsave" value="Создать backup базы данных" style="width: 200px">
			</form>
			<br>
		</center>
	{/if}
	
	
	
	<br>
	</div>