 <!-- PWA manifest -->
<link rel="manifest" href="/manifest.webmanifest" />
<script async src="/sw_install.js" ></script>
<link rel="apple-touch-icon-precomposed" sizes="57x57" href="/images/apple-touch-icon-57x57.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/images/apple-touch-icon-114x114.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/images/apple-touch-icon-72x72.png" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/images/apple-touch-icon-144x144.png" />
<link rel="apple-touch-icon-precomposed" sizes="60x60" href="/images/apple-touch-icon-60x60.png" />
<link rel="apple-touch-icon-precomposed" sizes="120x120" href="/images/apple-touch-icon-120x120.png" />
<link rel="apple-touch-icon-precomposed" sizes="76x76" href="/images/apple-touch-icon-76x76.png" />
<link rel="apple-touch-icon-precomposed" sizes="152x152" href="/images/apple-touch-icon-152x152.png" />
<link rel="icon" type="image/png" href="/images/favicon-196x196.png" sizes="196x196" />
<link rel="icon" type="image/png" href="/images/favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/png" href="/images/favicon-32x32.png" sizes="32x32" />
<link rel="icon" type="image/png" href="/images/favicon-16x16.png" sizes="16x16" />
<link rel="icon" type="image/png" href="/images/favicon-128.png" sizes="128x128" />
<meta name="application-name" content="&nbsp;"/>
<meta name="msapplication-TileColor" content="#FFFFFF" />
<meta name="msapplication-TileImage" content="/images/mstile-144x144.png" />
<meta name="msapplication-square70x70logo" content="/images/mstile-70x70.png" />
<meta name="msapplication-square150x150logo" content="/images/mstile-150x150.png" />
<meta name="msapplication-wide310x150logo" content="/images/mstile-310x150.png" />
<meta name="msapplication-square310x310logo" content="/images/mstile-310x310.png" />

{* Preload critical fonts *}
<link rel="preload" href="/fonts/open-sans/open-sans-400-cyrillic.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="/fonts/fontawesome/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin>

{* Local fonts - Open Sans *}
<link rel="stylesheet" href="/fonts/fonts.css">
{* Font Awesome - local *}
<link rel="stylesheet" href="/fonts/fontawesome/fontawesome.min.css">

<style type="text/css">
{literal}
.visually-hidden{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}
.loader-container {
    width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
    background: #000;
    z-index: 100;
    opacity: 0.7;
}

.spinner {
    width: 64px;
    height: 64px;
    border: 8px solid;
    border-color: #3d5af1 transparent #3d5af1 transparent;
    border-radius: 50%;
    animation: spin-anim 1.2s linear infinite;

    
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    position: fixed;
}

@keyframes spin-anim {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
{/literal}
</style>

 <link rel="apple-touch-startup-image" media="screen and (device-width: 430px) and (device-height: 932px) and (-webkit-device-pixel-ratio: 3) and 
(orientation: landscape)" href="splash_screens/iPhone_15_Pro_Max__iPhone_15_Plus__iPhone_14_Pro_Max_landscape.png"> <link rel="apple-touch-startup-image" 
media="screen and (device-width: 393px) and (device-height: 852px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)" 
href="splash_screens/iPhone_15_Pro__iPhone_15__iPhone_14_Pro_landscape.png"> <link rel="apple-touch-startup-image" media="screen and (device-width: 428px) and 
(device-height: 926px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)" 
href="splash_screens/iPhone_14_Plus__iPhone_13_Pro_Max__iPhone_12_Pro_Max_landscape.png"> <link rel="apple-touch-startup-image" media="screen and 
(device-width: 390px) and (device-height: 844px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)" 
href="splash_screens/iPhone_14__iPhone_13_Pro__iPhone_13__iPhone_12_Pro__iPhone_12_landscape.png"> <link rel="apple-touch-startup-image" media="screen and 
(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)" 
href="splash_screens/iPhone_13_mini__iPhone_12_mini__iPhone_11_Pro__iPhone_XS__iPhone_X_landscape.png"> <link rel="apple-touch-startup-image" media="screen 
and (device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)" 
href="splash_screens/iPhone_11_Pro_Max__iPhone_XS_Max_landscape.png"> <link rel="apple-touch-startup-image" media="screen and (device-width: 414px) and 
(device-height: 896px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="splash_screens/iPhone_11__iPhone_XR_landscape.png"> <link 
rel="apple-touch-startup-image" media="screen and (device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: 
landscape)" href="splash_screens/iPhone_8_Plus__iPhone_7_Plus__iPhone_6s_Plus__iPhone_6_Plus_landscape.png"> <link rel="apple-touch-startup-image" 
media="screen and (device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" 
href="splash_screens/iPhone_8__iPhone_7__iPhone_6s__iPhone_6__4.7__iPhone_SE_landscape.png"> <link rel="apple-touch-startup-image" media="screen and 
(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" 
href="splash_screens/4__iPhone_SE__iPod_touch_5th_generation_and_later_landscape.png"> <link rel="apple-touch-startup-image" media="screen and (device-width: 
1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="splash_screens/12.9__iPad_Pro_landscape.png"> 
<link rel="apple-touch-startup-image" media="screen and (device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2) and 
(orientation: landscape)" href="splash_screens/11__iPad_Pro__10.5__iPad_Pro_landscape.png"> <link rel="apple-touch-startup-image" media="screen and 
(device-width: 820px) and (device-height: 1180px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" 
href="splash_screens/10.9__iPad_Air_landscape.png"> <link rel="apple-touch-startup-image" media="screen and (device-width: 834px) and (device-height: 1112px) 
and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="splash_screens/10.5__iPad_Air_landscape.png"> <link rel="apple-touch-startup-image" 
media="screen and (device-width: 810px) and (device-height: 1080px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" 
href="splash_screens/10.2__iPad_landscape.png"> <link rel="apple-touch-startup-image" media="screen and (device-width: 768px) and (device-height: 1024px) and 
(-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="splash_screens/9.7__iPad_Pro__7.9__iPad_mini__9.7__iPad_Air__9.7__iPad_landscape.png"> 
<link rel="apple-touch-startup-image" media="screen and (device-width: 744px) and (device-height: 1133px) and (-webkit-device-pixel-ratio: 2) and 
(orientation: landscape)" href="splash_screens/8.3__iPad_Mini_landscape.png"> <link rel="apple-touch-startup-image" media="screen and (device-width: 430px) 
and (device-height: 932px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" 
href="splash_screens/iPhone_15_Pro_Max__iPhone_15_Plus__iPhone_14_Pro_Max_portrait.png"> <link rel="apple-touch-startup-image" media="screen and 
(device-width: 393px) and (device-height: 852px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" 
href="splash_screens/iPhone_15_Pro__iPhone_15__iPhone_14_Pro_portrait.png"> <link rel="apple-touch-startup-image" media="screen and (device-width: 428px) and 
(device-height: 926px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" 
href="splash_screens/iPhone_14_Plus__iPhone_13_Pro_Max__iPhone_12_Pro_Max_portrait.png"> <link rel="apple-touch-startup-image" media="screen and 
(device-width: 390px) and (device-height: 844px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" 
href="splash_screens/iPhone_14__iPhone_13_Pro__iPhone_13__iPhone_12_Pro__iPhone_12_portrait.png"> <link rel="apple-touch-startup-image" media="screen and 
(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" 
href="splash_screens/iPhone_13_mini__iPhone_12_mini__iPhone_11_Pro__iPhone_XS__iPhone_X_portrait.png"> <link rel="apple-touch-startup-image" media="screen and 
(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" 
href="splash_screens/iPhone_11_Pro_Max__iPhone_XS_Max_portrait.png"> <link rel="apple-touch-startup-image" media="screen and (device-width: 414px) and 
(device-height: 896px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="splash_screens/iPhone_11__iPhone_XR_portrait.png"> <link 
rel="apple-touch-startup-image" media="screen and (device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: 
portrait)" href="splash_screens/iPhone_8_Plus__iPhone_7_Plus__iPhone_6s_Plus__iPhone_6_Plus_portrait.png"> <link rel="apple-touch-startup-image" media="screen 
and (device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" 
href="splash_screens/iPhone_8__iPhone_7__iPhone_6s__iPhone_6__4.7__iPhone_SE_portrait.png"> <link rel="apple-touch-startup-image" media="screen and 
(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" 
href="splash_screens/4__iPhone_SE__iPod_touch_5th_generation_and_later_portrait.png"> <link rel="apple-touch-startup-image" media="screen and (device-width: 
1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="splash_screens/12.9__iPad_Pro_portrait.png"> <link 
rel="apple-touch-startup-image" media="screen and (device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2) and (orientation: 
portrait)" href="splash_screens/11__iPad_Pro__10.5__iPad_Pro_portrait.png"> <link rel="apple-touch-startup-image" media="screen and (device-width: 820px) and 
(device-height: 1180px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="splash_screens/10.9__iPad_Air_portrait.png"> <link 
rel="apple-touch-startup-image" media="screen and (device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2) and (orientation: 
portrait)" href="splash_screens/10.5__iPad_Air_portrait.png"> <link rel="apple-touch-startup-image" media="screen and (device-width: 810px) and 
(device-height: 1080px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="splash_screens/10.2__iPad_portrait.png"> <link 
rel="apple-touch-startup-image" media="screen and (device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2) and (orientation: 
portrait)" href="splash_screens/9.7__iPad_Pro__7.9__iPad_mini__9.7__iPad_Air__9.7__iPad_portrait.png">
<link rel="apple-touch-startup-image" media="screen and (device-width: 744px) and (device-height: 1133px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" href="splash_screens/8.3__iPad_Mini_portrait.png">
