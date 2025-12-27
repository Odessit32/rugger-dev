{if $informer_votes}
    <div class="informer inf_voting"><!-- voting -->
        <div class="title title_voting">{$conf_vars.inform_votes}</div>

        <div id="informer_votes">
            {if $informer_votes.img}
                <p class="question">{$informer_votes.vt_question}</p>
                {foreach key=key item=item from=$informer_votes.answers}
                    <div class="answer">{$item.vta_answer}</div><div class="res"><div class="res_b"><img src="{$imagepath}images/vote_bg_b.gif" height="20" width="{$item.percent}%" border="0" /></div><div class="res_text{if $item.percent<50}_w{/if}">{$item.percent}%</div></div>
                {/foreach}
            {else}
                <p class="question">{$informer_votes.vt_question}</p>
                <form method="post" name="vote" id="vote">
                    <input type="hidden" name="c" value="{$vote_code}" />
                    <input type="hidden" name="vt_id" value="{$informer_votes.vt_id}" />
                    <ul class="vote_list">
                        {foreach key=key item=item from=$informer_votes.answers}
                            <li class="item">
                                <input type="radio" name="answer" value="{$item.vta_id}" id="radio_val{$key}">
                                <label for="radio_val{$key}">{$item.vta_answer}</label>
                            </li>
                        {/foreach}
                    </ul>
                    <div class="button"><input type="submit" class="submit_b submit_vote" name="submitvote" value="{$language.vote}"></div>
                </form>
            {/if}
        </div>
    </div>
    <script type="text/javascript" src="{$imagepath}jscripts/votes.js"></script>
{/if}