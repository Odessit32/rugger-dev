var currentTimeZone = 'GMT';

if ($("#stat_grid").length>0){
    var grid = document.getElementById('stat_grid');


    grid.onclick = function(e) {
        var target = e && e.target || window.event.srcElement;

        if (target.tagName != 'TH') return;

        // Если TH -- сортируем
        sortGrid(target.cellIndex, target.getAttribute('data-type'));
    };

    function sortGrid(colNum, type) {
        var tbody = grid.getElementsByTagName('tbody')[0];

        // Составить массив из TR
        var rowsArray = [];
        for(var i = 0; i<tbody.children.length; i++) {
            rowsArray.push(tbody.children[i]);
        }


        // определить функцию сравнения, в зависимости от типа
        var compare;

        switch(type) {
            case 'number':
                compare = function(rowA, rowB) {
                    var valA = rowA.cells[colNum].innerHTML;
                    valA = (valA == '-')?0:valA;
                    var valB = rowB.cells[colNum].innerHTML
                    valB = (valB == '-')?0:valB;
                    return valB - valA;
                };
                break;
            case 'string':
                compare = function(rowA, rowB) {
                    return rowA.cells[colNum].innerHTML > rowB.cells[colNum].innerHTML ? 1 : -1;
                };
                break;
        }

        // сортировать
        rowsArray.sort(compare);

        // Убрать tbody из большого DOM документа для лучшей производительности
        grid.removeChild(tbody);


        // Убрать TR из TBODY.
        // Присваивание tbody.innerHTML = '' не работает в IE
        //
        // на самом деле без этих строк можно обойтись!
        // при добавлении appendChild все узлы будут сами перемещены на правильное место!
        while(tbody.firstChild) {
            tbody.removeChild(tbody.firstChild);
        }


        // добавить результат в нужном порядке в TBODY
        for(var i=0; i<rowsArray.length; i++) {
            tbody.appendChild(rowsArray[i]);
        }

        grid.appendChild(tbody);

    }
}

//////////////////////////////////////////////

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function getNewsArticleData(data, _that){
    $.ajax({
        url: "/ajax.php",
        type: "POST",
        data: data,
        dataType: 'html',
        success: function(response, textStatus, jqXHR){
            if (response != ''){
                _that.parent('div').next('.inf_newsarticle_list').html(response);
                _that.parent('div').data('page', data.page);
            }
        }
    });
}

function getTitleTimeZone(tz){
    var resultText = '';
    var timeZones = {
        'Europe/Kiev':'Киев',
        'Europe/Zaporozhye':'Запорожье',
        'Europe/Simferopol':'Симферополь',
        'Europe/Minsk':'Минск',
        'Europe/Kaliningrad':'Калининград',
        'Europe/Moscow':'Москва',
        'Europe/Samara':'Самара',
        'Asia/Ashgabat':'Ашхабад',
        'Asia/Tbilisi':'Тбилиси',
        'Asia/Tashkent':'Ташкент',
        'Asia/Samarkand':'Самарканд',
        'Asia/Vladivostok':'Владивосток',
        'Asia/Yakutsk':'Якутск',
        'Asia/Omsk':'Омск',
        'Asia/Novosibirsk':'Новосибирск',
        'Asia/Novokuznetsk':'Новокузнецк',
        'Asia/Magadan':'Магадан',
        'Asia/Krasnoyarsk':'Красноярск',
        'Asia/Kamchatka':'Камчатка',
        'Asia/Irkutsk':'Иркутск',
        'Asia/Chita':'Чита',
        'Asia/Bishkek':'Бишкек',
        'Asia/Baku':'Баку',
        'Asia/Yekaterinburg':'Екатеринбург',
        'Asia/Yerevan':'Ереван',
    };
    if (typeof tz != "undefined" &&
        typeof tz != "null" &&
        tz != '' &&
        typeof timeZones[tz] != "undefined" &&
        typeof timeZones[tz] != "null"
    ){
        resultText = timeZones[tz];
    }
    return resultText;
}

function getTitleTimeZoneCode(tz){
    var resultText = '';
    var timeZones = {
        'EEST':'Украина, Болгария, Греция, Израиль, Молдавия, Румыния, Турция',
        'EET':'Украина, Болгария, Греция, Израиль, Молдавия, Румыния, Турция',
        'MSK':'Россия, Белоруссия'
    };
    if (typeof tz != "undefined" &&
        typeof tz != "null" &&
        tz != '' &&
        typeof timeZones[tz] != "undefined" &&
        typeof timeZones[tz] != "null"
    ){
        resultText = timeZones[tz];
    }
    return resultText;
}

function setTimeZone(){

    //var cookieAr = document.cookie.split(';');
    //var cookieAr_a = {};
    //var temp = '';
    //for(var i=0; i<cookieAr.length; i++) {
    //    while (cookieAr[i].charAt(0)==' ') cookieAr[i] = cookieAr[i].substring(1);
    //    var temp = cookieAr[i].split('=');
    //    if (typeof temp[1] != "undefined" && typeof temp[1] != "null"){
    //        cookieAr_a[temp[0]] = temp[1];
    //    }
    //}
    //if (typeof cookieAr_a.timezone == "undefined" || typeof cookieAr_a.timezone == "null"){
    //    $.ajax({
    //        url: "http://ip-api.com/json",
    //        type: "GET",
    //        data: '',
    //        dataType: 'json',
    //        success: function(response, textStatus, jqXHR){
    //            if (response != ''){
    //                currentTimeZone = response.timezone;
    //                var dateTZ = new Date(new Date().getTime() + 60 * 60 * 1000);
    //                document.cookie = "timezone=" + currentTimeZone + "; path=/; expires=" + dateTZ.toUTCString();
    //                getDateCalculate();
    //            }
    //        }
    //    });
    //} else {
    //    currentTimeZone = cookieAr_a.timezone
    //    getDateCalculate();
    //}
    getDateCalculate();
}

function toTimeZone(time, zone) {
    // Using Day.js instead of Moment.js for better performance
    var format = 'HH:mm';
    if (typeof dayjs !== 'undefined' && dayjs.tz) {
        var gmt = dayjs.tz(time, "Atlantic/Reykjavik");
        return gmt.tz(zone).format(format);
    }
    // Fallback to native Date if Day.js not available
    var date = new Date(time);
    return ('0' + date.getHours()).slice(-2) + ':' + ('0' + date.getMinutes()).slice(-2);
}

function getDateCalculate() {
    if ($(".date_calculate").length > 0){
        $(".date_calculate").each(function(){
            console.log($(this).data('date-gmt'));
            if (typeof $(this).data('date-gmt') != "undefined" &&
                typeof $(this).data('date-gmt') != "null" &&
                $(this).data('date-gmt') != ''){
                var date_gmt_ = new Date($(this).data('date-gmt'));
                var minutes = '0'+date_gmt_.getMinutes();
                var hours = '0'+date_gmt_.getHours();
                var time_zone_code = (new Date).toString().split('(')[1].slice(0, -1);
                var time_zone_code_title = getTitleTimeZoneCode(time_zone_code)
                $(this).html('(' + hours.slice(-2) + ':' + minutes.slice(-2) + ' <span title="' + time_zone_code_title + '">' + time_zone_code +'</span>, '+$(this).text().substr(1));
            }
        });
        $(".date_calculate span").tooltip({
            position: { my: "center top+25", at: "center top" }
        });
    }
}

jQuery(document).ready(function($){

    if ($(".notification_list").length>0) {
        $(".notification_list .notification_item").each(function () {
            var time_show = $(this).data('time_show')*1000;
            var time_hide = $(this).data('time_hide')*1000;
            var _that = $(this);
            setTimeout(function () {
                _that.addClass("active");
                setTimeout(function () {
                    _that.removeClass("active");
                }, time_hide);
            }, time_show);
        });
        $("body").on("click", ".notification_list .notification_item .close", function () {
            $(this).closest(".notification_item").removeClass("active");
        });
    }

    setTimeZone();
    if ($(".toggle_block").length>0) {
        $(window).on("click", ".toggle_block .toggle_block_title", function(){
            if (!$(this).hasClass("active")){
                $(this).addClass("active");
                $(this).next(".toggle_block_content").slideDown();
            } else {
                $(this).removeClass("active");
                $(this).next(".toggle_block_content").slideUp();
            }
        });
    }



    if ($(".comp_item.games_table").length > 0) {
        var key_m = '';
        if ($(".comp_item.games_table .heading_month.active").length>0){
            key_m = $(".comp_item.games_table .heading_month.active").data('key_m');
        } else {
            key_m = $(".comp_item.games_table .heading_month:last").data('key_m');
            $(".comp_item.games_table .heading_month:last").addClass('active');
        }
        $(".comp_item.games_table .gib_"+key_m).slideDown();

        $(window).on("click", ".comp_item.games_table .heading_month", function(){
            console.log("!");
            if (!$(this).hasClass("active")){
                $(".comp_item.games_table .heading_month").removeClass("active");
                $(this).addClass("active");
                $(".comp_item.games_table .game_item_block").slideUp();
                key_m = $(this).data('key_m');
                console.log(".comp_item.games_table .gib_"+key_m);
                $(".comp_item.games_table .gib_"+key_m).slideDown();
            }
        });
    }

    $(window).on("click", ".game_link_popup", function(){
        hs.htmlExpand(this, {
            objectType: 'iframe',
            width: '500',
            minWidth: '510',
            headingEval: 'this.a.title',
            wrapperClassName: 'titlebar'
        } );
        return false;
    });

    if ($("table th").length>0){
        $( "table th" ).tooltip();
    }
    if ($(".show-tooltip").length>0){
        $( ".show-tooltip" ).tooltip({
            position: { my: "left top+20", at: "left top" }
        });
    }

    // informer news article
    if ($(".inf_newsarticle_ajax").length>0){
        $(".inf_newsarticle_ajax .inf_news_next").on("click", function(){
            var _that = $(this);
            var section_id = _that.parent('div').data('section');
            if (typeof section_id == "undefined" || typeof section_id == "null"){
                section_id = 0
            } else {
                section_id = parseInt(section_id);
            }
            var section_type = _that.parent('div').data('sectiontype');
            if (typeof section_type == "undefined" || typeof section_type == "null"){
                section_type = '';
            }
            var page = parseInt(_that.parent('div').data('page'))+1;
            var data = {
                page: page,
                section_id: 2,
                action: 'inf_newsarticle_loadmore'
            };
            if (section_id > 0) {
                data.section_id = section_id;
                data.section_type = section_type;
            }
            getNewsArticleData(data, _that);
        });
        $(".inf_newsarticle_ajax .inf_news_prev").on("click", function(){
            var _that = $(this);
            var section_id = _that.parent('div').data('section');
            if (typeof section_id == "undefined" || typeof section_id == "null"){
                section_id = 0
            } else {
                section_id = parseInt(section_id);
            }
            var section_type = _that.parent('div').data('sectiontype');
            if (typeof section_type == "undefined" || typeof section_type == "null"){
                section_type = '';
            }
            var page = parseInt(_that.parent('div').data('page'));
            if (page > 0) {
                page = page-1;
                var data = {
                    page: page,
                    section_id: 2,
                    action: 'inf_newsarticle_loadmore'
                };
                if (section_id > 0) {
                    data.section_id = section_id;
                    data.section_type = section_type;
                }
                getNewsArticleData(data, _that);
            }
        });
    }

    // informer newsfeed
    if ($(".inf_newsfeed_ajax").length>0){
        $(".inf_newsfeed_ajax .inf_newsfeed_loadmore").on("click", function(){
            var _that = $(this);
            var c_li = _that.prev(".inf_newsfeed_list").children('li.item').length;
            var page = parseInt(_that.data('page'));

            var section_id = _that.data('section');
            if (typeof section_id == "undefined" || typeof section_id == "null"){
                section_id = 0
            } else {
                section_id = parseInt(section_id);
            }
            var category = _that.data('category');
            if (typeof category == "undefined" || typeof category == "null"){
                category = 0
            } else {
                category = parseInt(category);
            }
            var section_type = _that.data('sectiontype');
            if (typeof section_type == "undefined" || typeof section_type == "null"){
                section_type = '';
            }
            var data = {
                page: page,
                action: 'inf_newsfeed_loadmore',
                category: category
            };
            if (section_id > 0) {
                data.section_id = section_id;
                data.section_type = section_type;
            }
            $.ajax({
                url: "/ajax.php",
                type: "POST",
                data: data,
                dataType: 'html',
                success: function(response, textStatus, jqXHR){
                    if (response != ''){
                        _that.prev(".inf_newsfeed_list").html(response);
                        _that.data('page', page+1);
                        if (_that.prev(".inf_newsfeed_list").children('li.item').length < c_li) {
                            _that.remove();
                        }
                    } else {
                        _that.remove();
                    }
                }
            });
            return false;
        });
    }

    if ($(".to_the_top").length>0){
        $("body").on("click", ".to_the_top", function(e){
            var _that = this;
            $("html, body").animate({scrollTop:0}, '500', 'swing', function() {
                $(_that).removeClass('visible');
            });
        });

        $(document).scroll(function() {
            var wHeight = $(window).innerHeight();
            var bScroll = $(window).scrollTop();
            if (wHeight < bScroll) {
                $(".to_the_top").addClass('visible');
            } else {
                $(".to_the_top").removeClass('visible');
            }
        });
    }



    if ($(".no-link").length>0){
        $("body").on("click", ".no-link", function(e){
            e.preventDefault();
            e.stopPropagation();
            return false;
        });
    }

    if ($(".show_more_index_news").length >0 ){
        $("body").on("click", ".show_more_index_news", function(e){
            e.preventDefault();
            e.stopPropagation();
            var i_n_page = parseInt($(this).data('page'));
            var _that = this;
            $(_that).addClass('loading_spinner');
            $.ajax({
                url: "/ajax.php",
                type: "POST",
                data: {
                    offset: parseInt($(this).data('offset')),
                    page: i_n_page,
                    section_id: parseInt($(this).data('section_id')),
                    section_type: $(this).data('section_type'),
                    section_address: $(this).data('section_address'),
                    action: 'inf_index_news'
                },
                dataType: 'html',
                success: function(response, textStatus, jqXHR){
                    if (response != ''){
                        $(_that).before(response);
                        $(_that).data('page', i_n_page+1);
                    }
                    $(_that).removeClass('loading_spinner');
                }
            });
        });
    }

    if ($(".about_position_list_page").length>0){
        if ($(".about_position_list_page .about_position_page_item.active").length == 0){
            $(".about_position_list_page .about_position_page_item").eq( 0 ).addClass('active');
            $(".about_position_list_page .about_position_page_item").eq( 2 ).addClass('active');
        }
        if ($(".about_position_menu .about_position_menu_item.active").length  == 0){
            $('.about_position_menu .about_position_menu_item').eq( 0 ).addClass('active');
            $('.about_position_menu .about_position_menu_item').eq( 2 ).addClass('active');
        }
        $(".about_position_list_page").height($(".about_position_list_page .about_position_page_item.active").height());
        var timeAboutPosition;
        $('.about_position_menu .about_position_menu_item').on('click', function(){
            if ($(this).data('id')>0) {
                var _that = this;
                clearTimeout(timeAboutPosition);
                timeAboutPosition = setTimeout(function(){
                    $(".about_position_list_page .about_position_page_item").removeClass('active');
                    $(".about_position_menu .about_position_menu_item").removeClass('active');
                    $(_that).addClass('active');
//                    console.log($(_that).index());
                    switch ($(_that).index()){
                        case 0 :
                            $('.about_position_menu .about_position_menu_item').eq( 2 ).addClass('active');
                            $(".about_position_list_page .about_position_page_item").eq( 0 ).addClass('active');
                            break;
                        case 2 :
                            $('.about_position_menu .about_position_menu_item').eq( 0 ).addClass('active');
                            $(".about_position_list_page .about_position_page_item").eq( 0 ).addClass('active');
                            break;
                        case 3 :
                            $('.about_position_menu .about_position_menu_item').eq( 4 ).addClass('active');
                            $(".about_position_list_page .about_position_page_item").eq( 3 ).addClass('active');
                            break;
                        case 4 :
                            $('.about_position_menu .about_position_menu_item').eq( 3 ).addClass('active');
                            $(".about_position_list_page .about_position_page_item").eq( 3 ).addClass('active');
                            break;
                        case 5 :
                            $('.about_position_menu .about_position_menu_item').eq( 6 ).addClass('active');
                            $(".about_position_list_page .about_position_page_item").eq( 5 ).addClass('active');
                            break;
                        case 6 :
                            $('.about_position_menu .about_position_menu_item').eq( 5 ).addClass('active');
                            $(".about_position_list_page .about_position_page_item").eq( 5 ).addClass('active');
                            break;
                        case 10 :
                            $('.about_position_menu .about_position_menu_item').eq( 13 ).addClass('active');
                            $(".about_position_list_page .about_position_page_item").eq( 10 ).addClass('active');
                            break;
                        case 13 :
                            $('.about_position_menu .about_position_menu_item').eq( 10 ).addClass('active');
                            $(".about_position_list_page .about_position_page_item").eq( 10 ).addClass('active');
                            break;
                        case 11 :
                            $('.about_position_menu .about_position_menu_item').eq( 12 ).addClass('active');
                            $(".about_position_list_page .about_position_page_item").eq( 11 ).addClass('active');
                            break;
                        case 12 :
                            $('.about_position_menu .about_position_menu_item').eq( 11 ).addClass('active');
                            $(".about_position_list_page .about_position_page_item").eq( 11 ).addClass('active');
                            break;
                        default :
                            $(".about_position_list_page .about_position_page_item[data-id='"+$(_that).data('id')+"']").addClass('active');
                    }
                    $(".about_position_list_page").height($(".about_position_list_page .about_position_page_item.active").height());
                }, 300);
                return false;
            }
        });
//        $('.about_position_menu .about_position_menu_item').on('mouseout', function(){
//            clearTimeout(timeAboutPosition);
//        });
    }

    if ($(".news_main_content").length>0){
        if ($(".news_main_content .news_main_content_item.active").length < 1){
            $(".news_main_content .news_main_content_item:first-child").addClass('active');
        }
//        $(".news_main_content").height($(".news_main_content .news_main_content_item.active").height());
        var timeMainNews;
        $('.news_main_list .list_item').on('mouseover', function(){
            if ($(this).data('id')>0) {
                var _that = this;
                clearTimeout(timeMainNews);
                timeMainNews = setTimeout(function(){

                    $(".news_main_content .news_main_content_item").removeClass('active');
                    $(".news_main_content .news_main_content_item[data-id='"+$(_that).data('id')+"']").addClass('active');
//                    $(".news_main_content").height($(".news_main_content .news_main_content_item.active").height());
                    $(".news_main_list .list_item").removeClass('active');
                    $(_that).addClass('active');
                }, 300);
            }
        });
        $('.news_main_list .list_item').on('mouseout', function(){
            clearTimeout(timeMainNews);
        });
    };

    if ($("#photo_slider").length>0) {
        $('#photo_slider_nav').flexslider({
            animation: "slide",
            directionNav: true,
            controlNav: false,
            animationLoop: false,
            slideshow: false,
            itemWidth: 120,
            itemMargin: 0,
            asNavFor: '#photo_slider',
            easing: "swing"
        });

        $('#photo_slider').flexslider({
            animation: "fade",
            directionNav: true,
            animationLoop: false,
            easing: "swing",
            slideshow: true,
            sync: "#photo_slider_nav",
            slideshowSpeed: 5000
        });
    }
    if ($("#video_slider_nav").length>0) {
        $('#video_slider_nav').flexslider({
            animation: "slide",
            directionNav: true,
            controlNav: false,
            animationLoop: false,
            slideshow: false,
            itemWidth: 120,
            itemMargin: 0,
            easing: "swing"
        });
    }
















    if ($(".video_gallery_item").length>0) {
        $('#carousel').flexslider({
            animation: "slide",
            slideshow: false,
            controlNav: false,
            animationLoop: false,
            itemWidth: 70,
            itemMargin: 5
        });
    } else {
        if ($("#carousel").length>0){
            // The slider being synced must be initialized first
            $('#carousel').flexslider({
                animation: "slide",
                slideshow: false,
                controlNav: false,
                animationLoop: false,
                itemWidth: 70,
                itemMargin: 5,
                asNavFor: '#slider'
            });
        }
        if ($("#slider").length>0){
            $('#slider').flexslider({
                animation: "slide",
                slideshow: true,
                controlNav: false,
                animationLoop: false,
                slideshowSpeed: 5000,
                keyboardNav: true,
                directionNav: true,
                pauseOnHover: true,
                sync: "#carousel"
            });
        }
    }

    if ($("#slideshow").length>0){
        $('#slideshow').cycle({
            fx:			'fade',
            speed:		500,
            timeout:	3000,
            pause:		true,
            cleartype:	true,
            cleartypeNoBg: true,
            pager: '#nav',
            pagerEvent:	'mouseover',
            pauseOnPagerHover:	true,
            pagerAnchorBuilder: function(idx, slide) {
                return '#nav li:eq(' + (idx) + ')';
            }
        });

        $('#nav li').mouseover(function() {
            $('#slideshow').cycle('pause');
        });
    }

    if ($(".prog_list").length>0){
        $(".prog_list li.item").on("click", function(){
            if ($(this).children(".hidden_text").length>0){
                var hidden_text = $(this).children(".hidden_text");
                if (hidden_text.is(":visible")){
                    hidden_text.slideUp();
                    $(this).removeClass("active");
                } else {
                    $(".prog_list li.item").removeClass("active");
                    $(".prog_list li.item .hidden_text").slideUp();
                    $(this).addClass("active");
                    hidden_text.slideDown();
                }
            }
        });
    }

    $(".header_menu .ul_sub_menu").on("mouseover", function(){
        $(this).prev('a').addClass('over');
    });
    $(".header_menu .ul_sub_menu").on("mouseout", function(){
        $(this).prev('a').removeClass('over');
    });

    var w_sub_menu_timeout_open;
    var w_sub_menu_timeout_close;
    if ($(".left_menu .ul_sub_menu").length>0) {
        $(".left_menu .w_sub_menu").on("click", function(){
            var _that = $(this);
            $(".left_menu li").removeClass('hover');
            _that.parent('li').addClass('hover');

//            clearTimeout(w_sub_menu_timeout_open);
//            w_sub_menu_timeout_open = setTimeout(function(){
//                $(".left_menu li").removeClass('hover');
//                _that.parent('li').addClass('hover');
//            }, 500);
            return false;
        });
        $("body").on("click", function(event) {
            if (!$(event.target).is(".left_menu .ul_sub_menu") && !$(event.target).is(".left_menu .w_sub_menu")) {
                $('.left_menu li').removeClass('hover');
            }
        });
//        $(".left_menu .w_sub_menu").on("mouseout", function(){
//            clearTimeout(w_sub_menu_timeout_close);
//            clearTimeout(w_sub_menu_timeout_open);
//            var _that = $(this);
//            w_sub_menu_timeout_close = setTimeout(function(){
//                _that.parent('li').removeClass('hover');
//            }, 500);
//        });
//        $(".left_menu .ul_sub_menu").on("click", function(){
//            clearTimeout(w_sub_menu_timeout_close);
//            clearTimeout(w_sub_menu_timeout_open);
//            $(this).parent('li').addClass('hover');
//            $(this).prev('a').addClass('over');
//        });
//        $(".left_menu .ul_sub_menu").on("mouseout", function(){
//            clearTimeout(w_sub_menu_timeout_close);
//            clearTimeout(w_sub_menu_timeout_open);
//            var _that = $(this);
//            w_sub_menu_timeout_close = setTimeout(function(){
//                _that.parent('li').removeClass('hover');
//                _that.prev('a').removeClass('over');
//            }, 500);
//        });
    }

    $("body").on("click", ".header_menu_icon", function(){
        if ($(".header_menu").hasClass("active")){
            //$(".header_menu").slideUp();
            $(".header_menu").removeClass("active");
            $(".header_menu_icon").removeClass("active");
        } else {
            //$(".header_menu").slideDown();
            $(".header_menu").addClass("active");
            $(".header_menu_icon").addClass("active");
            mobileHeightSubMenu();
        }
    });
    mobileHeightSubMenu();
    $(window).on("resize", function(){
        mobileHeightSubMenu();
    });

    gameTitleFixPosition();
    $(window).on('resize', function(){
        gameTitleFixPosition();
    });

    if ($(".interkassa_custom").length > 0) {
        if ($(".interkassa_custom form input[name='ik_pm_no']").length > 0) {
            // var new_ik_pm_no = GetTimeStamp() + '_' + CryptoJS.MD5(navigator.userAgent);
            var new_ik_pm_no = GetTimeStamp() + getRandomInt(0, 100);
            $(".interkassa_custom form input[name='ik_pm_no']").val( "ID_" + new_ik_pm_no );
        }
    }
});

function GetTimeStamp() {
    return new Date().getTime();
}

function gameTitleFixPosition () {
    if ($(".game-title-team-owner").length > 0) {
        $(".game-title-team-owner").removeClass('one-line');
        $(".game-title-points").removeClass('one-line');
        $(".game-title-team-guest").removeClass('one-line');
        if ($(".game-title-team-owner").offset().top != $(".game-title-points").offset().top ||
            $(".game-title-team-owner").offset().top != $(".game-title-team-guest").offset().top) {
            $(".game-title-team-owner").addClass('one-line');
            $(".game-title-points").addClass('one-line');
            $(".game-title-team-guest").addClass('one-line');
        }
    }
}

function mobileHeightSubMenu(){
    // console.log('mobileHeightSubMenu');
    if ($("body").width() < 640){
        if ($(".sub_menu_pages").length > 0){
            var sub_menu_height = [],
                sub_menu_items_height = 0,
                sub_menu_items_height_all = 0,
                i = i_len = $(".sub_menu").length;

            $(".sub_menu").each(function(){
                i--;
                sub_menu_height[i] = $(this).outerHeight()*1;
                sub_menu_items_height_all += sub_menu_height[i];
            });
            console.log("sub_menu_height:", sub_menu_height);
            console.log("sub_menu_items_height_all:", sub_menu_items_height_all);
            $(".header_menu .center_block").css({"margin-top":sub_menu_items_height_all+"px"});
            i=1;
            sub_menu_items_height = sub_menu_items_height_all;
            $(".sub_menu").each(function(){
                sub_menu_items_height = sub_menu_items_height-sub_menu_height[i_len-i]*1;
                $(this).css({"margin-top":(sub_menu_items_height*1+47)+"px"});
                i++;
            });
        }
    } else {
        $(".header_menu .center_block").removeAttr('style');
        $(".sub_menu").each(function(){
            $(this).removeAttr('style');
        });
    }
}