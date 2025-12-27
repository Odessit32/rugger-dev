{if $ENV !=='PRODUCTION'}
{* Fancybox - асинхронная загрузка CSS *}
<link rel="stylesheet" href="{$imagepath}fancybox/fancybox.css" media="print" onload="this.media='all'">
<script defer src="{$imagepath}fancybox/fancybox.umd.js"></script>
<script defer src="{$imagepath}fancybox/fancybox_init.js"></script>
{/if}
