{* SEO Head Include - Canonical, Hreflang, Schema.org *}

{* === PRELOAD для критических ресурсов === *}
{if $ENV == 'PRODUCTION'}
<link rel="preload" href="{$imagepath}css/styles.min.css?v=1.1.0" as="style">
<link rel="preload" href="{$imagepath}jscripts/jquery-3.7.1.min.js" as="script">
<link rel="preload" href="{$imagepath}js/scripts.min.js" as="script">
{/if}
<link rel="preload" href="/fonts/fonts.css" as="style">
<link rel="preload" href="/fonts/fontawesome/fontawesome.min.css" as="style">

{* === NOINDEX для страниц с фильтрами и пагинацией === *}
{* Проверяем URL на наличие фильтров/пагинации *}
{assign var="request_uri" value=$smarty.server.REQUEST_URI|default:''}
{if $request_uri|regex_replace:'/.*?(page\d|\/ch-|\/date-|\/week-|\/month-).*/':'FILTER' == 'FILTER'}
<meta name="robots" content="noindex, follow" />
{/if}

{* === CANONICAL URL === *}
{* Определяем canonical URL на основе типа страницы *}
{if isset($canonical_url) && $canonical_url}
    <link rel="canonical" href="{$canonical_url}" />
{elseif isset($news_item) && !empty($news_item)}
    <link rel="canonical" href="{$sitepath}news/{$news_item.id}" />
{elseif isset($post_item) && !empty($post_item)}
    <link rel="canonical" href="{$sitepath}blog/{$post_item.address}" />
{elseif isset($live_item) && !empty($live_item)}
    <link rel="canonical" href="{$sitepath}live/{$live_item.id}" />
{elseif isset($staff_item) && !empty($staff_item.st_address)}
    <link rel="canonical" href="{$sitepath}people/{$staff_item.st_address}" />
{elseif isset($team_item) && !empty($team_item)}
    <link rel="canonical" href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$team_item.address}" />
{elseif isset($page_item) && !empty($page_item.p_adress)}
    <link rel="canonical" href="{$sitepath}{$page_item.page_path}{$page_item.p_adress}" />
{elseif $smarty.server.REQUEST_URI == '/' || $smarty.server.REQUEST_URI == ''}
    <link rel="canonical" href="{$sitepath}" />
{else}
    <link rel="canonical" href="{$sitepath}{$smarty.server.REQUEST_URI|regex_replace:'/^\/|[\?#].*$/':''}" />
{/if}

{* === HREFLANG для мультиязычности === *}
{assign var="current_path" value=""}
{if isset($page_item.p_adress) && $page_item.p_adress}
    {assign var="current_path" value=$page_item.page_path|cat:$page_item.p_adress}
{elseif $smarty.server.REQUEST_URI && $smarty.server.REQUEST_URI != '/'}
    {assign var="current_path" value=$smarty.server.REQUEST_URI|regex_replace:'/^\/|[\?#].*$/':''}
{/if}
<link rel="alternate" hreflang="ru" href="{$sitepath}{$current_path}" />
<link rel="alternate" hreflang="uk" href="{$sitepath}ukr/{$current_path}" />
<link rel="alternate" hreflang="en" href="{$sitepath}eng/{$current_path}" />
<link rel="alternate" hreflang="x-default" href="{$sitepath}{$current_path}" />

{* === OG IMAGE (дефолтное изображение для соцсетей) === *}
{if !isset($news_item.photo_main) && !isset($post_item.photo_main) && !isset($team_item.photo_main)}
    <meta property="og:image" content="{$sitepath}images/OG-rugger.jpg" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
{/if}

{* === PRECONNECT для внешних ресурсов === *}
<link rel="preconnect" href="https://www.youtube.com" crossorigin />
<link rel="dns-prefetch" href="https://www.youtube.com" />
<link rel="preconnect" href="https://www.google-analytics.com" crossorigin />
<link rel="dns-prefetch" href="https://www.google-analytics.com" />
<link rel="preconnect" href="https://www.google.com" crossorigin />
<link rel="dns-prefetch" href="https://www.google.com" />

{* === SCHEMA.ORG JSON-LD === *}
{* Organization (на всех страницах) *}
<script type="application/ld+json">
{literal}
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Rugger.info",
    "url": "https://rugger.info",
    "logo": "https://rugger.info/images/logo_header.png",
    "sameAs": [
        "https://www.facebook.com/rugabordy"
    ],
    "contactPoint": {
        "@type": "ContactPoint",
        "contactType": "customer service",
        "availableLanguage": ["Russian", "Ukrainian", "English"]
    }
}
{/literal}
</script>

{* WebSite с SearchAction *}
<script type="application/ld+json">
{literal}
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "Rugger.info",
    "url": "https://rugger.info",
    "potentialAction": {
        "@type": "SearchAction",
        "target": "https://rugger.info/search/?q={search_term_string}",
        "query-input": "required name=search_term_string"
    }
}
{/literal}
</script>

{* Article/NewsArticle для новостей *}
{if isset($news_item) && !empty($news_item)}
<script type="application/ld+json">
{ldelim}
    "@context": "https://schema.org",
    "@type": "NewsArticle",
    "headline": "{$news_item.title|escape:'javascript'}",
    "datePublished": "{$news_item.date|date_format:'%Y-%m-%dT%H:%M:%S+03:00'}",
    "dateModified": "{$news_item.date|date_format:'%Y-%m-%dT%H:%M:%S+03:00'}",
    "author": {ldelim}
        "@type": "Organization",
        "name": "Rugger.info"
    {rdelim},
    "publisher": {ldelim}
        "@type": "Organization",
        "name": "Rugger.info",
        "logo": {ldelim}
            "@type": "ImageObject",
            "url": "https://rugger.info/images/logo_header.png"
        {rdelim}
    {rdelim},
    "description": "{$news_item.description_meta|escape:'javascript'|default:''}"
    {if isset($news_item.photo_main.ph_big)}
    ,"image": "{$imagepath}upload/photos{$news_item.photo_main.ph_big}"
    {else}
    ,"image": "https://rugger.info/images/OG-rugger.jpg"
    {/if}
{rdelim}
</script>
{/if}

{* BlogPosting для блога *}
{if isset($post_item) && !empty($post_item)}
<script type="application/ld+json">
{ldelim}
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    "headline": "{$post_item.title|escape:'javascript'}",
    "datePublished": "{$post_item.date_show|date_format:'%Y-%m-%dT%H:%M:%S+03:00'}",
    "dateModified": "{$post_item.date_show|date_format:'%Y-%m-%dT%H:%M:%S+03:00'}",
    "author": {ldelim}
        "@type": "Organization",
        "name": "Rugger.info"
    {rdelim},
    "publisher": {ldelim}
        "@type": "Organization",
        "name": "Rugger.info",
        "logo": {ldelim}
            "@type": "ImageObject",
            "url": "https://rugger.info/images/logo_header.png"
        {rdelim}
    {rdelim},
    "description": "{$post_item.description_meta|escape:'javascript'|default:''}"
    {if isset($post_item.photo_main.ph_big)}
    ,"image": "{$imagepath}upload/photos{$post_item.photo_main.ph_big}"
    {else}
    ,"image": "https://rugger.info/images/OG-rugger.jpg"
    {/if}
{rdelim}
</script>
{/if}

{* SportsTeam для команд *}
{if isset($team_item) && !empty($team_item)}
<script type="application/ld+json">
{ldelim}
    "@context": "https://schema.org",
    "@type": "SportsTeam",
    "name": "{$team_item.title|escape:'javascript'}",
    "sport": "Rugby",
    "url": "{$sitepath}{$page_item.page_path}{$page_item.p_adress}/{$team_item.address}"
    {if isset($team_item.photo_main.ph_big)}
    ,"logo": "{$imagepath}upload/photos{$team_item.photo_main.ph_big}"
    {/if}
    {if isset($team_item.stadium.title)}
    ,"location": {ldelim}
        "@type": "Place",
        "name": "{$team_item.stadium.title|escape:'javascript'}"
    {rdelim}
    {/if}
{rdelim}
</script>
{/if}

{* Person для страниц персон *}
{if isset($staff_item) && !empty($staff_item)}
<script type="application/ld+json">
{ldelim}
    "@context": "https://schema.org",
    "@type": "Person",
    "name": "{$staff_item.full_name|escape:'javascript'}",
    "url": "{$sitepath}people/{$staff_item.st_address|default:$staff_item.address}"
    {if isset($staff_item.date_birth)}
    ,"birthDate": "{$staff_item.date_birth|date_format:'%Y-%m-%d'}"
    {/if}
    {if isset($staff_item.photo_main.ph_big)}
    ,"image": "{$imagepath}upload/photos{$staff_item.photo_main.ph_big}"
    {/if}
{rdelim}
</script>
{/if}

{* BreadcrumbList *}
{if isset($page_item) && !empty($page_item)}
<script type="application/ld+json">
{ldelim}
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {ldelim}
            "@type": "ListItem",
            "position": 1,
            "name": "Главная",
            "item": "{$sitepath}"
        {rdelim}
        {if isset($page_item.title) && $page_item.title}
        ,{ldelim}
            "@type": "ListItem",
            "position": 2,
            "name": "{$page_item.title|escape:'javascript'}",
            "item": "{$sitepath}{$page_item.page_path}{$page_item.p_adress}"
        {rdelim}
        {/if}
        {if isset($news_item.title)}
        ,{ldelim}
            "@type": "ListItem",
            "position": 3,
            "name": "{$news_item.title|escape:'javascript'}",
            "item": "{$sitepath}news/{$news_item.id}"
        {rdelim}
        {/if}
    ]
{rdelim}
</script>
{/if}
