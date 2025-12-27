{strip}
{if $informer_video}
    <div class="informer inf_video"><!-- videos -->
        <div class="title title_video"><a href="{if $section_address}{$sitepath}{$section_address}{else}{$sitepath}{/if}{if $section_address == $sitepath || $section_address == ''}{/if}video">{$conf_vars.inform_video}</a></div>
        <ul class="media_list">
            {foreach key=key item=item from=$informer_video}
                <li class="item">
                    <a href="{if $section_address}{$sitepath}{$section_address}{else}{$sitepath}{/if}{if $section_address == $sitepath || $section_address == ''}{/if}video/gal{$item.vg_id}">
                        <div class="video">
                            <img src="{$imagepath}upload/video_thumbs{$item.v_folder}{$item.v_id}-small.jpg" loading="lazy" />
                        </div>
                        <div class="date">{$item.vg_datetime_pub|date_format:"%d"} {assign var="month_name" value=$item.vg_datetime_pub|date_format:"%m"} {$month.$month_name}</div>
                        <p>{$item.vg_title}</p>
                    </a>
                </li>
            {/foreach}
        </ul>
        <a href="{if $section_address}{$sitepath}{$section_address}{else}{$sitepath}{/if}{if $section_address == $sitepath || $section_address == ''}{/if}video" class="all_videos">Перейти в видеогалерею</a>
    </div>
{/if}
{/strip}