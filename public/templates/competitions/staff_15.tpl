{* Staff for 15 - jQuery version *}
<br>

<div class="title_caption caption_staff_table">{$language.competition_staff_title}</div>

<div class="comp_item comp_staff_table" id="competition-staff-table">
    <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <thead>
        <tr>
            <th> </th>
            <th class="title">{$language.Name}</th>
            <th class="sorting-title" data-type="p" title="{$language.Pp_title}">{$language.P}</th>
            <th class="sorting-title" data-type="t" title="{$language.T_title}">{$language.T}</th>
            <th class="sorting-title" data-type="sh" title="{$language.Sh_title}">{$language.Sh}</th>
            <th class="sorting-title" data-type="r" title="{$language.R_title}">{$language.R}</th>
            <th class="sorting-title" data-type="dg" title="{$language.DG_title}">{$language.DG}</th>
            <th class="sorting-title" data-type="zhk" title="{$language.Zhk_title}">{$language.ZhK}</th>
            <th class="sorting-title" data-type="kk" title="{$language.KK_title}">{$language.KK}</th>
        </tr>
        </thead>
        <tbody>
        {* Initial data rendered by JavaScript from JSON *}
        </tbody>
    </table>
</div>

<script>
{literal}
(function initStaffTable() {
    if (typeof CompetitionStaff !== 'undefined' && typeof jQuery !== 'undefined') {
        $(document).ready(function() {
            CompetitionStaff.init('competition-staff-table', {/literal}{$competition_staff_json}{literal});
        });
        return; // Exit after initialization
    }
    // Wait for CompetitionStaff.js to load
    setTimeout(initStaffTable, 50);
})();
{/literal}
</script>
