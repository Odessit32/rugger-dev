		<br>
		<table width="100%" cellspacing="0" cellpadding="0" border="0" id="form_input">
			{if $championship_item.competitions_done}
			<tr>
				<td colspan="2" align="center">
					В чемпионате есть структура и игры!<br>
					Удаление команды вызовет удаление части игр и структуры!
				</td>
			</tr>
			{/if}
			<tr>
				<td width="50%" valign="top">
				<div id="list">
				<div id="conteiner" style="margin: 10px; padding: 0;">
				<div style="width: 175px; display: block; line-height: 20px; text-align: center; float: left;"><b>Список:</b></div>
				<div style="margin: 30px 10px 0 10px;">
					{if $championship_team_list.on}
						<ol style="margin: 5px 10px 0px 20px; ">
						{foreach key=key item=item from=$championship_team_list.on name=tsl}
							<li><a href="?show=championship&get=edit&item={$smarty.get.item}&cont=6&team={$item.cntch_id}">{$item.t_title_ru}</a>{if $item.cntch_is_technical == 'yes'} (тех.){/if}</li>
						{/foreach}
						</ol>
						<br>
					{else}
						<br><br><br><center>список пуст</center><br><br><br>
					{/if}
				</div>
				</div>
				</div>
				</td>
				<td width="50%" valign="top">
				{if !empty($ch_team_item)}
				<div id="conteiner" style="margin: 10px; background: #efefef">
					<center><b>Редактирование: </b>
					<form method="post" action="?show=championship&get=edit&item={$smarty.get.item}&cont=6" onsubmit="if (!confirm('Вы уверены?')) return false" style="margin-top: 20px; margin-bottom: 20px;">
					<input type="Hidden" name="cntch_id" value="{$ch_team_item.cntch_id}">
					техническая команда в чемпионате: <input type="checkbox" name="cntch_is_technical"{if $ch_team_item.cntch_is_technical == 'yes'} checked{/if}>
					<br><br>
					<b>«{$ch_team_item.t_title_ru}»</b>
					<br><br>
					c 
					<select name="app_date_day">
					{section name = day start = 1 loop = 32}
						<option value="{$smarty.section.day.index}"{if $ch_team_item.cntch_date_add|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
					{/section}
					</select>
					<select name="app_date_month">
					{section name = month start = 1 loop = 13}
						<option value="{$smarty.section.month.index}"{if $ch_team_item.cntch_date_add|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
					{/section}
					</select>
					<select name="app_date_year">
					{assign var="now_year" value=$smarty.now|date_format:"%Y"}
					{assign var="now_year" value=$now_year+2}
					{section name = year start = 1900 loop = $now_year}
						<option value="{$smarty.section.year.index}"{if $ch_team_item.cntch_date_add|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
					{/section}
					</select>
					<br><br>
					<input type="submit" name="edit_championship_team" id="submitsave" value="Сохранить">
					</form>
					</center>
				</div>
				<div id="conteiner" style="margin: 10px; background: #faa">
					<center><b>Удалить: </b>
					<form method="post"  action="?show=championship&get=edit&item={$smarty.get.item}&cont=6" onsubmit="if (!confirm('Вы уверены?')) return false" style="margin-top: 20px; margin-bottom: 20px;">
					<input type="Hidden" name="cntch_id" value="{$ch_team_item.cntch_id}">
					<b>«{$ch_team_item.t_title_ru}»</b>
					<br><br>
					принят:
					<br>
					<b>{$ch_team_item.cntch_date_add|date_format:"%e. %m. %Y"}</b>
					<br><br>
					{*if $ch_team_item.cntch_date_quit != '0000-00-00 00:00:00'}
					уволен:
					<br>
					<b>{$ch_team_item.cntch_date_quit|date_format:"%e. %m. %Y"}</b>
					<br><br>
					{/if*}
					<input type="submit" name="delete_championship_team" id="submitsave" value="Удалить">
					</form>
					</center>
				</div>
				{/if}
				<div id="conteiner" style="margin: 10px;">
					<center><b>Добавление: </b>
					<form method="post"  action="?show=championship&get=edit&item={$smarty.get.item}&cont=6" onsubmit="return confirmMultipleTeams();" style="margin-top: 20px; margin-bottom: 20px;">
					<input type="Hidden" name="ch_id" value="{$championship_item.ch_id}">
					<input type="Hidden" name="team_ids" id="team_ids_val">
					техническая команда в чемпионате: <input type="checkbox" name="cntch_is_technical" id="cntch_is_technical_global">
					<br><br>
					<!-- Autocomplete поиск команды -->
					<input type="text" id="team_input_id" placeholder="Начните вводить название команды..." style="width: 300px;">
					<br><br>
					<div id="team_selected_container" style="text-align: left; max-width: 500px; margin: 0 auto;"></div>
					<br><br>
					c 
					<select name="app_date_day">
					{section name = day start = 1 loop = 32}
						<option value="{$smarty.section.day.index}"{if $smarty.now|date_format:"%d" == $smarty.section.day.index} selected{/if}>{$smarty.section.day.index}</option>
					{/section}
					</select>
					<select name="app_date_month">
					{section name = month start = 1 loop = 13}
						<option value="{$smarty.section.month.index}"{if $smarty.now|date_format:"%m" == $smarty.section.month.index} selected{/if}>{$smarty.section.month.index}</option>
					{/section}
					</select>
					<select name="app_date_year">
					{assign var="now_year" value=$smarty.now|date_format:"%Y"}
					{assign var="now_year" value=$now_year+2}
					{section name = year start = 1900 loop = $now_year}
						<option value="{$smarty.section.year.index}"{if $smarty.now|date_format:"%Y" == $smarty.section.year.index} selected{/if}>{$smarty.section.year.index}</option>
					{/section}
					</select>
					<br><br>
					<input type="submit" name="add_championship_team" id="submitsave" value="<<< Добавить">
					</form>
					</center>
				</div>
				
				</td>
			</tr>
		</table>
	<br>

<script>
jQuery(document).ready(function($){
    var selectedTeams = []; // Массив выбранных команд

    // Функция обновления списка выбранных команд
    function updateSelectedTeams() {
        var container = $("#team_selected_container");
        container.empty();

        if (selectedTeams.length === 0) {
            container.html('<span style="color: #999;">Команды не выбраны</span>');
        } else {
            var html = '<div style="margin-bottom: 10px;"><b>Выбрано команд: ' + selectedTeams.length + '</b></div>';
            selectedTeams.forEach(function(team, index) {
                html += '<div style="padding: 5px; margin: 3px 0; background: #f0f0f0; border-radius: 3px;">' +
                        '<span>' + team.name + '</span> ' +
                        '<a href="javascript:void(0);" class="remove_team_multi" data-index="' + index + '" style="color: red; text-decoration: none; font-weight: bold;">[удалить]</a>' +
                        '</div>';
            });
            container.html(html);
        }

        // Обновляем скрытое поле с ID команд
        var teamIds = selectedTeams.map(function(team) { return team.id; });
        $("#team_ids_val").val(teamIds.join(','));
    }

    // Autocomplete для поиска команд
    if ($("#team_input_id").length > 0) {
        console.log('Initializing team autocomplete (multiple selection)');

        updateSelectedTeams(); // Показываем начальное состояние

        $("#team_input_id").autocomplete({
            source: "ajax.php?action=get_teams_by_name",
            minLength: 2,
            select: function( event, ui ) {
                console.log('Team selected:', ui.item);

                // Проверяем, не добавлена ли уже эта команда
                var alreadyAdded = selectedTeams.some(function(team) {
                    return team.id === ui.item.id;
                });

                if (alreadyAdded) {
                    alert('Эта команда уже добавлена в список!');
                } else {
                    // Добавляем команду в массив
                    selectedTeams.push({
                        id: ui.item.id,
                        name: ui.item.value
                    });
                    updateSelectedTeams();
                }

                // Очищаем поле ввода
                $(this).val('');
                return false;
            }
        });

        // Удаление команды из списка
        $('body').on('click', ".remove_team_multi", function(){
            var index = $(this).data('index');
            console.log('Removing team at index:', index);
            selectedTeams.splice(index, 1);
            updateSelectedTeams();
        });
    } else {
        console.log('team_input_id element not found!');
    }
});

// Функция подтверждения при отправке формы
function confirmMultipleTeams() {
    var teamIds = $("#team_ids_val").val();
    if (!teamIds || teamIds === '') {
        alert('Выберите хотя бы одну команду!');
        return false;
    }

    var count = teamIds.split(',').length;
    var message = count === 1
        ? 'Вы уверены, что хотите добавить 1 команду?'
        : 'Вы уверены, что хотите добавить ' + count + ' команд(ы)?';

    return confirm(message);
}
</script>