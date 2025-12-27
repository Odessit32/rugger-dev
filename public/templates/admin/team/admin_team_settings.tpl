{literal}
<style>
	.app_list {display: block; width: 50%; margin: 0 auto; padding: 0 0 0 30px;}
	.app_list:hover {background: #eeeeee;}
	.app_list input[type='checkbox'] {float: left; margin: 0 0 0 -30px;}
	.input_list {display: block; width: 50%; margin: 0 auto 5px auto; padding: 0;}
	.input_list:hover {background: #eeeeee;}
	.input_list span {width: 130px; display: inline-block;}
	.input_list input[type='checkbox'] {float: left; margin: 0;}
	.app_listsubmit { margin: 20px auto 0 auto; display: block;}
</style>
{/literal}
<div class="content_block">
	<h2>Общая информация о команде</h2>
	<p>Показать в общей информации позиции:</p>
	<form method="post" action="/admin/?show=team&get=edit&item={$smarty.get.item}&cont=6" onsubmit="if (!confirm('Вы уверены?')) return false">
		<input type="hidden" name="t_id" value="{$team_item.t_id}">
		{if !empty($team_categories_list)}
		{foreach key=key item=item from=$team_categories_list name=team_category}
			<label class="app_list">
				{$item.app_title_ru} ({$item.app_type} - {if !empty($item.app_is_active) && $item.app_is_active == 'yes'}вкл{else}откл{/if})
<input type="checkbox" name="show_app[]" value="{$item.app_id}" {if !empty($team_item.t_info.show_app) && is_array($team_item.t_info.show_app) && in_array($item.app_id, $team_item.t_info.show_app)} checked{/if}/>			</label>
		{/foreach}
			<input type="submit" name="save_team_general_appointment_show" value="Сохранить" class="submitsave app_listsubmit" />
		{/if}
	</form>
	<p>Названия для вкладок:</p>
	<form method="post" action="/admin/?show=team&get=edit&item={$smarty.get.item}&cont=6" onsubmit="if (!confirm('Вы уверены?')) return false">
		<input type="hidden" name="t_id" value="{$team_item.t_id}">
		<label class="input_list">
			<span>Профиль:</span>
			<input type="text" name="tab_titles[1]" value="{if !empty($team_item.t_info.tab_titles[1])}{$team_item.t_info.tab_titles[1]}{/if}"/>
		</label>
		<label class="input_list">
			<span>Состав:</span>
			<input type="text" name="tab_titles[2]" value="{if !empty($team_item.t_info.tab_titles[2])}{$team_item.t_info.tab_titles[2]}{/if}"/>
		</label>
		<label class="input_list">
			<span>Результаты:</span>
			<input type="text" name="tab_titles[3]" value="{if !empty($team_item.t_info.tab_titles[3])}{$team_item.t_info.tab_titles[3]}{/if}"/>
		</label>
		<label class="input_list">
			<span>Статьи:</span>
			<input type="text" name="tab_titles[4]" value="{if !empty($team_item.t_info.tab_titles[4])}{$team_item.t_info.tab_titles[4]}{/if}"/>
		</label>
		<label class="input_list">
			<span>Фото:</span>
			<input type="text" name="tab_titles[5]" value="{if !empty($team_item.t_info.tab_titles[5])}{$team_item.t_info.tab_titles[5]}{/if}"/>
		</label>
		<label class="input_list">
			<span>Видео:</span>
			<input type="text" name="tab_titles[6]" value="{if !empty($team_item.t_info.tab_titles[6])}{$team_item.t_info.tab_titles[6]}{/if}"/>
		</label>
		<input type="submit" name="save_team_general_tab_titles" value="Сохранить" class="submitsave app_listsubmit" />
	</form>
</div>