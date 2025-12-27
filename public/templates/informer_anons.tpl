{strip}
{if $announce_informer}
				<div class="anons">
					<div class="center_rubrika"><strong>Анонс</strong></div>
					<div id="result_text">
					{if $announce_informer.an_type == 'game'}
						<p class="time_center">{if $announce_informer.an_link != ''}<a href="{$announce_informer.an_link}">{/if}{$announce_informer.an_datetime_m}{if $announce_informer.an_link != ''}</a>{/if}</p>
						
						<div class="game_left">
							{if $announce_informer.an_link != ''}<a href="{$announce_informer.an_link}">{/if}
							{if $announce_informer.an_owner_t_id_photo}<img src="{$imagepath}upload/photos{$announce_informer.an_owner_t_id_photo.ph_small}" alt="" border="0" loading="lazy" />
							{elseif $announce_informer.an_owner_t_logo != ''}<img src="{$sitepath}{$announce_informer.an_owner_t_logo}" alt="" border="0" />
							{else}<img src="{$sitepath}images/def_logo.jpg" alt="" border="0">{/if}
							<br>
							{if $announce_informer.an_owner_t_id_title != ''}{$announce_informer.an_owner_t_id_title}{else}{$announce_informer.an_owner_t_title}{/if}
							{if $announce_informer.an_link != ''}</a>{/if}
						</div>
						{if $announce_informer.an_link != ''}<a href="{$announce_informer.an_link}">{/if}<img src="{$sitepath}images/vs.gif" alt="" border="0" class="vs"/>{if $announce_informer.an_link != ''}</a>{/if}
						<div class="game_right">
							{if $announce_informer.an_link != ''}<a href="{$announce_informer.an_link}">{/if}
							{if $announce_informer.an_guest_t_id_photo}<img src="{$imagepath}upload/photos{$announce_informer.an_guest_t_id_photo.ph_small}" alt="" border="0" loading="lazy" />
							{elseif $announce_informer.an_guest_t_logo != ''}<img src="{$sitepath}{$announce_informer.an_guest_t_logo}" alt=""  border="0" />
							{else}<img src="{$sitepath}images/def_logo.jpg" alt="" border="0">{/if}
							<br>
							{if $announce_informer.an_guest_t_id_title != ''}{$announce_informer.an_guest_t_id_title}{else}{$announce_informer.an_guest_t_title}{/if}
							{if $announce_informer.an_link != ''}</a>{/if}
						</div>
						<div class="game_description">
							{if $announce_informer.an_link != ''}<a href="{$announce_informer.an_link}">{/if}
							{$announce_informer.description}
							{if $announce_informer.an_link != ''}</a>{/if}
						</div>
					{/if}
					{if $announce_informer.an_type == 'event'}
						{if $announce_informer.an_link != ''}<a href="{$announce_informer.an_link}">{/if}
						{if $announce_informer.an_photo_event != ''}<img src="{$sitepath}{$announce_informer.an_photo_event}" alt="" border="0" class="anons_image" />{/if}
						{$announce_informer.description}
						{if $announce_informer.an_link != ''}</a>{/if}
					{/if}
					</div>
		
				</div>
<div id="delim">&nbsp;</div>
{/if}
{/strip}