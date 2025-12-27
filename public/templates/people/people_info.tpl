<div class="page_content">
    <div class="title_caption profile_block_caption">
        {$staff_item.full_name}
        {include file="social.tpl"}
    </div>

    <div class="profile_block">
        <div class="profile_photo">
            {if $staff_item.photo_main.ph_med}<img class="profile_logo" src="{$imagepath}upload/photos{$staff_item.photo_main.ph_med}" alt="">{/if}
        </div>
        <div class="profile_content">
            <ul class="pr_list">
                {if !empty($staff_item.full_name) || !empty($staff_item.full_name_en)}
                    <li>
                        <div class="pr_name">Полное имя</div>
                        <div class="pr_value">{$staff_item.full_name} {if !empty($staff_item.full_name_en) && $staff_item.full_name_en != $staff_item.full_name}<span class="small">{$staff_item.full_name_en}</span>{/if}</div>
                    </li>
                {/if}
                {if !empty($staff_item.date_birth) && !empty($staff_item.age) && $staff_item.age > 5}
                    <li>
                        <div class="pr_name">Дата рождения</div>
                        <div class="pr_value">{$staff_item.date_birth|date_format:"%d.%m.%Y"}</div>
                    </li>
                {/if}
                {if !empty($staff_item.age) && $staff_item.age > 5}
                    <li>
                        <div class="pr_name">Возраст</div>
                        <div class="pr_value">{$staff_item.age} {if substr($staff_item.age, -1) == 1}год{elseif in_array(substr($staff_item.age, -1), array(2,3,4))}года{else}лет{/if}</div>
                    </li>
                {/if}
                {if !empty($staff_item.statistics.teams)}
                    <li>
                        <div class="pr_name">Команды</div>
                        <div class="pr_value">
                            {foreach key=key item=item from=$staff_item.statistics.teams name=team_title}
                                {$item.title}{if !$smarty.foreach.team_title.last}, {/if}
                            {/foreach}
                        </div>
                    </li>
                {/if}
                {if !empty($staff_item.statistics.app)}
                    <li>
                        <div class="pr_name">Позиция</div>
                        <div class="pr_value">
                            {foreach
                                item=item_st from=$staff_item.statistics.app name=app_groups}{foreach
                                    item=item from=$item_st name=app_title}{if !empty($item.title)}{if
                                        !$smarty.foreach.app_title.first}, {/if}{$item.title}{/if}{/foreach}{/foreach}
                        </div>
                    </li>
                {/if}
                {if !empty($staff_item.height)}
                    <li>
                        <div class="pr_name">Рост</div>
                        <div class="pr_value">{$staff_item.height} см</div>
                    </li>
                {/if}
                {if !empty($staff_item.weight)}
                    <li>
                        <div class="pr_name">Вес</div>
                        <div class="pr_value">{$staff_item.weight} кг</div>
                    </li>
                {/if}
            </ul>
        </div>
    </div>

    <div class="left_menu overflow">
        <ul>
            {if $staff_item.description!='' || $staff_item.text!=''}<li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$staff_item.address}" title="{$staff_item.title}"{if $staff_page == 'profile'} class="active"{/if}>Профиль</a></li>{/if}
            {if $staff_item.statistics}<li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$staff_item.address}/statistics" title="{$staff_item.title}"{if $staff_page == 'statistics'} class="active"{/if}>{$language.competition_staff_title}</a></li>{/if}
            {if !empty($staff_item.ph_count)}<li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$staff_item.address}/photos" title="{$staff_item.title}"{if $staff_page == 'photos'} class="active"{/if}>Фото</a></li>{/if}
            {if !empty($staff_item.v_count)}<li><a href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$staff_item.address}/video" title="{$staff_item.title}"{if $staff_page == 'videos'} class="active"{/if}>Видео</a></li>{/if}
        </ul>
    </div>

    <div class="content_post post{if $staff_page == 'statistics'} no-padding-sides{else} border{/if}">
        {if $staff_item}
            {if $staff_page == 'profile'}
                {if $staff_item.description!=''}
                    {$staff_item.description}
                {/if}
                {if $staff_item.text!=''}
                    {$staff_item.text}
                {/if}
            {/if}
            {if $staff_page == 'news'}
                {if $staff_item.news}
                    <div class="staff_news_list">
                        {foreach key=key item=item from=$staff_item.news name=staff_news}
                            <a href="{$sitepath}news/{$item.id}" class="staff_news_item">
                                {if $item.photo_main}
                                    <div class="staff_news_photo" style="background-image: url({$imagepath}upload/photos{$item.photo_main.ph_folder}{$item.photo_main.ph_med});"></div>
                                {/if}
                                <div class="staff_news_text">
                                    <div class="staff_news_date">{$item.date|date_format:"%d"} {$item.m} {$item.date|date_format:"%Y"}</div>
                                    <div class="staff_news_title">{$item.title}</div>
                                    <div class="staff_news_desc">{$item.description}</div>
                                </div>
                            </a>
                        {/foreach}
                    </div>
                {else}
                    <p>Новостей пока нет</p>
                {/if}
            {/if}
            {if $staff_page == 'photos'}
                {if $staff_item.photos}
                    <div class="photo_big">
                        <div id="photo_slider" class="flexslider gallery_slider">
                            <ul class="slides">
                                {foreach key=key item=item from=$staff_item.photos name=photo}
                                    <li class="gallery_slider_item">
                                    <a href="{$imagepath}{$item.ph_big}"
                                       onclick="return hs.expand(this)"
                                       title="{$staff_item.photo_gallery.title} {if $item.ph_about != ''}: {$item.ph_about}{/if}"
                                       style="background-image: url({$imagepath}{$item.ph_big});"></a>
                                    </li>{/foreach}
                            </ul>
                        </div>
                        <div id="photo_slider_nav" class="flexslider gallery_slider_nav">
                            <ul class="slides">
                                {foreach key=key item=item from=$staff_item.photos name=photo}
                                    <li class="gallery_slider_nav_item">
                                        <div class="image" style="background-image: url({$imagepath}{$item.ph_med});"></div>
                                    </li>
                                {/foreach}
                            </ul>
                        </div>
                    </div>
                {else}
                    <p>Фото пока не добавили</p>
                {/if}
            {/if}
            {if $staff_page == 'videos'}
                {if $staff_item.videos}
                    <div id="video_big" class="video_big">
                        {foreach key=v_key item=v_item from=$staff_item.videos}
                            <div class="video_item">
                                <p>{$v_item.title}</p>
                                {if $v_item.v_code != ''}
                                    <iframe width="100%" height="400"
                                            src="//www.youtube.com/embed/{$v_item.v_code}"
                                            frameborder="0" allowfullscreen></iframe>
                                {else}
                                    {$v_item.v_code_text}
                                {/if}
                            </div>
                        {/foreach}
                    </div>
                {else}
                    <p>Видео пока не добавили</p>
                {/if}
            {/if}
            {if $staff_page == 'statistics'}
                {if $staff_item.statistics}
                    {if $staff_item.statistics.by_table}
                        <table border="0" cellspacing="0" cellpadding="0" width="100%" class="statistics">
                            <tr>
                                <th>Клуб</th>
                                <th>Турнир</th>
                                <th>Сезон</th>
                                <th>Игры</th>
                                <th>Очков</th>
                                <th>Поп.</th>
                                <th>Рлз.</th>
                                <th>Штр.</th>
                                <th>ДГ</th>
                                <th>ЖК</th>
                                <th>КК</th>
                            </tr>
                            {foreach key=st_key item=st_item from=$staff_item.statistics.by_table}
                            <tr>
                                <td>{$st_item.team.title}</td>
                                <td>{$st_item.champ.chg_title}</td>
                                <td>{$st_item.champ.ch_title}</td>
                                <td>{$st_item.games.all}{if !empty($st_item.games.reserve)}({$st_item.games.reserve}){/if}</td>
                                <td>{if empty($st_item.actions.points)}-{else}{$st_item.actions.points}{/if}</td>
                                <td>{if empty($st_item.actions.pop)}-{else}{$st_item.actions.pop}{/if}</td>
                                <td>{if empty($st_item.actions.pez)}-{else}{$st_item.actions.pez}{/if}</td>
                                <td>{if empty($st_item.actions.sht)}-{else}{$st_item.actions.sht}{/if}</td>
                                <td>{if empty($st_item.actions.d_g)}-{else}{$st_item.actions.d_g}{/if}</td>
                                <td>{if empty($st_item.actions.y_c)}-{else}{$st_item.actions.y_c}{/if}</td>
                                <td>{if empty($st_item.actions.r_c)}-{else}{$st_item.actions.r_c}{/if}</td>
                            </tr>
                            {/foreach}
                        </table>
                    {/if}

                    {*
                    <table border="0" cellspacing="0" cellpadding="0" width="100%" class="statistics">
                    <tr>
                        <th colspan="2">В составе команд{if $staff_item.statistics.teams|@count == 1}ы{/if}</th>
                    </tr>
                    <tr>
                        <td colspan="2">
                            {if $staff_item.statistics.teams}
                                {foreach key=key item=item from=$staff_item.statistics.teams name=teams}
                                    <a href="{$sitepath}team/{$item.t_id}">{$item.title}</a>{if !$smarty.foreach.teams.last}, {/if}
                                {/foreach}
                            {/if}
                        </td>
                    </tr>
                    {if $staff_item.statistics.app.player OR $staff_item.statistics.app.head OR $staff_item.statistics.app.rest}
                            {if $staff_item.statistics.app.player}
                                <tr>
                                    <td class="title">позиция:</td>
                                    <td>
                                            {foreach key=key item=item from=$staff_item.statistics.app.player name=app}
                                                {if !$smarty.foreach.app.first}, {/if}{$item.title}
                                            {/foreach}
                                    </td>
                                </tr>
                            {/if}
                            {if $staff_item.statistics.app.head}
                                <tr>
                                    <td class="title">руководство:</td>
                                    <td align="center">
                                            {foreach key=key item=item from=$staff_item.statistics.app.head name=app}
                                                {if !$smarty.foreach.app.first}, {/if}{$item.title}
                                            {/foreach}
                                    </td>
                                </tr>
                            {/if}
                            {if $staff_item.statistics.app.rest}
                                <tr>
                                    <td class="title">другие должности:</td>
                                    <td align="center">
                                            {foreach key=key item=item from=$staff_item.statistics.app.rest name=app}
                                                {if !$smarty.foreach.app.first}, {/if}{$item.title}
                                            {/foreach}
                                    </td>
                                </tr>
                            {/if}
                    {/if}
                        <tr>
                            <th colspan="2">
                                Количество игр:
                            </th>
                        </tr>
                        <tr>
                            <td class="title">основной состав:</td>
                            <td>{if $staff_item.statistics.games_c.main == 0}-{else}{$staff_item.statistics.games_c.main}{/if}</td>
                        </tr>
                        <tr>
                            <td class="title">замена:</td>
                            <td>{if $staff_item.statistics.games_c.reserve == 0}-{else}{$staff_item.statistics.games_c.reserve}{/if}</td>
                        </tr>
                        <tr>
                            <th colspan="2">Результаты:</th>
                        </tr>
                        <tr>
                            <td class="title">Попыток:</td>
                            <td>{if $staff_item.statistics.ga.pop == 0}-{else}{$staff_item.statistics.ga.pop}{/if}</td>
                        </tr>
                        <tr>
                            <td class="title">Реализаций:</td>
                            <td>{if $staff_item.statistics.ga.pez == 0}-{else}{$staff_item.statistics.ga.pez}{/if}</td>
                        </tr>
                        <tr>
                            <td class="title">Штрафных ударов:</td>
                            <td>{if $staff_item.statistics.ga.sht == 0}-{else}{$staff_item.statistics.ga.sht}{/if}</td>
                        </tr>
                        <tr>
                            <td class="title">Дроп-голов:</td>
                            <td>{if $staff_item.statistics.ga.d_g == 0}-{else}{$staff_item.statistics.ga.d_g}{/if}</td>
                        </tr>
                        <tr>
                            <td class="title">Желтых карточек:</td>
                            <td>{if $staff_item.statistics.ga.y_c == 0}-{else}{$staff_item.statistics.ga.y_c}{/if}</td>
                        </tr>
                        <tr>
                            <td class="title">Красных карточек:</td>
                            <td>{if $staff_item.statistics.ga.r_c == 0}-{else}{$staff_item.statistics.ga.r_c}{/if}</td>
                        </tr>
                    </table>
                    *}
                {/if}
            {/if}
        {/if}

    </div>
</div>