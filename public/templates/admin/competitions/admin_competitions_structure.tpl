<h1>Соревнования в чемпионате</h1>

			{if $competitions_list_tour}
			{foreach key=key_t item=item_t from=$competitions_list_tour name=tour}
				{if $championship_item.ch_chc_id == 2}
                    <h2 style="background: #eee;">
                    <form method="post">
                    <input type="hidden" name="ch_id" value="{$smarty.get.ch}" />
                    <input type="hidden" name="tour" value="{$key_t}" />
                        {if $main_settings.rus.sl_is_active == 'yes'}рус:&nbsp;<input type="text" name="tour_title_ru" value="{if $championship_item.ch_settings.tourTitle.ru.$key_t}{$championship_item.ch_settings.tourTitle.ru.$key_t}{else}Тур {$key_t+1}{/if}" class="input_h2" />{/if}
                        {if $main_settings.ukr.sl_is_active == 'yes'}укр:&nbsp;<input type="text" name="tour_title_ua" value="{if $championship_item.ch_settings.tourTitle.ua.$key_t}{$championship_item.ch_settings.tourTitle.ua.$key_t}{else}Тур {$key_t+1}{/if}" class="input_h2" />{/if}
                        {if $main_settings.eng.sl_is_active == 'yes'}eng:&nbsp;<input type="text" name="tour_title_en" value="{if $championship_item.ch_settings.tourTitle.en.$key_t}{$championship_item.ch_settings.tourTitle.en.$key_t}{else}Tour {$key_t+1}{/if}" class="input_h2" />{/if}
                        дата: <input type="text" class="input_datetime input" name="tour_datetime" value="{$championship_item.ch_settings.tourDateTime.$key_t}">
                        <input type="submit" name="save_tour_title" value="Сохранить" class="input_submit_h2">
                    </form>
                    </h2>
                {/if}
				{if $item_t}
					{foreach key=key_s item=item_s from=$item_t name=stage}
						{if $item_s}
						<div class="cp_row">
                            <div class="cp_title">
                                <form method="post">
                                    <input type="hidden" name="ch_id" value="{$smarty.get.ch}" />
                                    <input type="hidden" name="tour" value="{$key_t}" />
                                    <input type="hidden" name="stage" value="{$key_s}" />
                                    {if $main_settings.rus.sl_is_active == 'yes'}рус:&nbsp;<input type="text" name="stage_title_ru" value="{if !empty($championship_item.ch_settings.stageTitle.ru.$key_t.$key_s)}{$championship_item.ch_settings.stageTitle.ru.$key_t.$key_s}{/if}" class="input50" /><br>{/if}
                                    {if $main_settings.ukr.sl_is_active == 'yes'}укр:&nbsp;<input type="text" name="stage_title_ua" value="{$championship_item.ch_settings.stageTitle.ua.$key_t.$key_s}" class="input50" /><br>{/if}
                                    {if $main_settings.eng.sl_is_active == 'yes'}eng:&nbsp;<input type="text" name="stage_title_en" value="{$championship_item.ch_settings.stageTitle.en.$key_t.$key_s}" class="input50" /><br>{/if}
                                    <label><input type="checkbox" name="is_show_one_page" {if !empty($championship_item.ch_settings.isShowOnePage.$key_t.$key_s) && $championship_item.ch_settings.isShowOnePage.$key_t.$key_s == 1}checked="checked" {/if} /> все на одной странице</label>
                                    дата: <input type="text" class="input_datetime input50" name="stage_datetime" value="{if !empty($championship_item.ch_settings.stageDateTime.$key_t.$key_s)}{$championship_item.ch_settings.stageDateTime.$key_t.$key_s}{/if}"><br>
                                    <label><input type="checkbox" name="is_show_stage_datetime" {if !empty($championship_item.ch_settings.isShowStageDateTime.$key_t.$key_s) && $championship_item.ch_settings.isShowStageDateTime.$key_t.$key_s == 1}checked="checked" {/if} /> показать время</label>
                                    <input type="submit" name="stage_title_save" value="сохранить" id="submitsave" />
                                </form>
                            </div>
							{foreach key=key item=item from=$item_s name=comp}
								<div class="cp_item{if $item.cp_is_active == 'no'}_n{/if}">
									<a href="?show=competitions&ch={$smarty.get.ch}&get=edit&item={$item.cp_id}&tour={$key_t}{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">{if !empty($item.title)}{$item.title}{else}Без названия{/if}</a>
									{if $item.c_games}игры: {$item.c_games}{else}<i>нет игр</i>{/if}
								</div>
							{/foreach}
						</div>
						{/if}
					{/foreach}
				{/if}
			{/foreach}	
			{else}
				Структура соревнования не определена.<br><a href="?show=competitions&ch={$smarty.get.ch}&get=add{if !empty($smarty.get.country)}&country={$smarty.get.country}{/if}">Добавить структуру</a><br>
			{/if}
			
			<br>
