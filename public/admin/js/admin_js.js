// page list expand
$(function () {
    'use strict';
    // Change this to the location of your server-side upload handler:
    var url = '/admin/ajax.php?action=file_multi_upload_action',
        uploadButton = $('<button/>')
            .addClass('btn btn-primary')
            .prop('disabled', true)
            .text('Processing...')
            .on('click', function (e) {
                e.stopPropagation();
                e.preventDefault();
                var $this = $(this),
                    data = $this.data();
                $this
                    .off('click')
                    .text('Abort')
                    .on('click', function () {
                        $this.remove();
                        data.abort();
                    });
                data.submit().always(function () {
                    $this.remove();
                });
                return false;
            });
    $('#fileupload').fileupload({
        url: url,
        dataType: 'json',
        autoUpload: true,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 10485760,
        // Enable image resizing, except for Android and Opera,
        // which actually support image resizing, but fail to
        // send Blob objects via XHR requests:
        disableImageResize: /Android(?!.*Chrome)|Opera/
            .test(window.navigator.userAgent),
        previewMaxWidth: 64,
        previewMaxHeight: 64,
        previewCrop: true
    }).on('fileuploadadd', function (e, data) {
        $('#progress .progress-bar').removeAttr('style');
        data.context = $('<div/>').appendTo('#files');
        $.each(data.files, function (index, file) {
            var node = $('<p/>').addClass("file_item")
                .append($('<span/>').addClass("file_item_title").text(file.name));
            if (!index) {
                node
                    .append(uploadButton.clone(true).data(data));
            }
            node.appendTo(data.context);
        });
    }).on('fileuploadprocessalways', function (e, data) {
        var index = data.index,
            file = data.files[index],
            node = $(data.context.children()[index]);
        if (file.preview) {
            node
                // .prepend('<br>')
                .prepend(file.preview);
        }
        if (file.error) {
            node
                // .append('<br>')
                .append($('<span class="text-danger"/>').text(file.error));
        }
        if (index + 1 === data.files.length) {
            data.context.find('button').remove();
                // .text('Upload')
                // .prop('disabled', !!data.files.error);
        }
    }).on('fileuploadprogressall', function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#progress .progress-bar').css(
            'width',
            progress + '%'
        );
    }).on('fileuploaddone', function (e, data) {
        $.each(data.result.files, function (index, file) {
            if (file.url) {
                var link = $('<a>')
                    .attr('target', '_blank')
                    .prop('href', file.url);
                $(data.context.children()[index])
                    .wrap(link);
                var saved_file_array = []
                if ($("#dropzone_block_file_array").length > 0) {
                    if ($("#dropzone_block_file_array").val() != '') {
                        saved_file_array = JSON.parse(atob($("#dropzone_block_file_array").val()));
                    }
                    saved_file_array.push(file);
                    $("#dropzone_block_file_array").val( btoa(JSON.stringify(saved_file_array)));
                }
            } else if (file.error) {
                var error = $('<span class="text-danger"/>').text(file.error);
                $(data.context.children()[index])
                    .append('<br>')
                    .append(error);
            }
        });
    }).on('fileuploadfail', function (e, data) {
        $.each(data.files, function (index) {
            var error = $('<span class="text-danger"/>').text('File upload failed.');
            $(data.context.children()[index])
                .append('<br>')
                .append(error);
        });
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
});
jQuery(document).ready(function($){

    if ($("#staff_input_id").length > 0) {
        // Инициализация списка при загрузке страницы
        // Загружаем только тех людей, которые ещё не отображены в шаблоне
        var initStaffIds = $("#staff_auto_val").val();
        if (initStaffIds) {
            $.each(initStaffIds.split(','), function(i, staffId) {
                if (staffId) {
                    // Проверяем, не отображён ли уже этот человек в шаблоне
                    if ($(".staff_list_added .selected_staff_item[data-id='" + staffId + "']").length === 0) {
                        $.get("ajax.php?action=get_staff_by_id&id=" + staffId, function(data) {
                            if (data && data.id) {
                                // Ещё раз проверяем перед добавлением (асинхронность)
                                if ($(".staff_list_added .selected_staff_item[data-id='" + data.id + "']").length === 0) {
                                    $(".staff_list_added").append('<span class="selected_staff_item" data-id="' + data.id + '">' + data.value + '</span> ');
                                }
                            }
                        }, 'json');
                    }
                }
            });
        }

        $( "#staff_input_id" ).autocomplete({
            source: "ajax.php?action=get_staff_by_name",
            minLength: 2,
            select: function( event, ui ) {
                // Множественный выбор людей
                var currentVal = $("#staff_auto_val").val();
                var ids = currentVal ? currentVal.split(',') : [];

                // Проверка, не добавлен ли уже этот человек
                if (ids.indexOf(String(ui.item.id)) === -1) {
                    ids.push(ui.item.id);
                    $("#staff_auto_val").val(ids.join(','));
                    $(".staff_list_added").append('<span class="selected_staff_item" data-id="' + ui.item.id + '">' + ui.item.value + '</span> ');
                }
                $(this).val('');
                return false;
            }
        });

        // Блокируем submit формы по Enter в поле поиска людей
        $("#staff_input_id").on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                return false;
            }
        });

        // Удаление выбранного человека (клик по карточке)
        $('body').on('click', ".selected_staff_item", function(){
            var $item = $(this);
            var removeId = String($item.data('id'));
            var currentVal = $("#staff_auto_val").val();
            var ids = currentVal ? currentVal.split(',') : [];
            ids = ids.filter(function(id) { return id !== removeId; });
            $("#staff_auto_val").val(ids.join(','));
            $item.remove();
        });
    }

    // Autocomplete для поиска команд в чемпионатах
    if ($("#team_input_id").length > 0) {
        $( "#team_input_id" ).autocomplete({
            source: "ajax.php?action=get_teams_by_name",
            minLength: 2,
            select: function( event, ui ) {
                // Для формы добавления команды в чемпионат - одиночный выбор
                $("#team_auto_val").val(ui.item.id);
                $("#team_selected_container").html('<span class="selected_team_single" data-id="' + ui.item.id + '">' + ui.item.value + ' <a href="javascript:void(0);" class="remove_team_single">[удалить]</a></span>');
                $(this).val('').prop('disabled', true);
            }
        });

        // Удаление выбранной команды
        $('body').on('click', ".remove_team_single", function(){
            $("#team_auto_val").val('');
            $("#team_selected_container").html('');
            $("#team_input_id").prop('disabled', false).focus();
        });
    }

    // Autocomplete для блока "Читайте также" - поиск новостей
    if ($(".related_news_input").length > 0) {
        $(".related_news_input").each(function(){
            var $input = $(this);
            var inputIndex = $input.data('index');
            $input.autocomplete({
                source: "ajax.php?action=get_news_by_title",
                minLength: 2,
                select: function(event, ui) {
                    // Заполняем скрытые поля
                    $("#related_news_id_" + inputIndex).val(ui.item.id);
                    $("#related_news_url_" + inputIndex).val(ui.item.address);
                    // Показываем выбранную новость
                    $("#related_news_selected_" + inputIndex).html(
                        '<span class="selected_related_news">' + ui.item.value +
                        ' <a href="javascript:void(0);" class="remove_related_news" data-index="' + inputIndex + '">[удалить]</a></span>'
                    );
                    $(this).val('').hide();
                    return false;
                }
            });
        });

        // Удаление выбранной новости
        $('body').on('click', '.remove_related_news', function(){
            var idx = $(this).data('index');
            $("#related_news_id_" + idx).val('');
            $("#related_news_url_" + idx).val('');
            $("#related_news_selected_" + idx).html('');
            $(".related_news_input[data-index='" + idx + "']").val('').show().focus();
        });
    }

    if ($(".input_datetime").length > 0){
        $('.input_datetime').datetimepicker({step:30});
    }
    if ($(".input_date").length > 0){
        $('.input_date').datepicker({
            dateFormat: "dd.mm.yy"
        });
    }

    if ($(".game_date").length>0){
        $(".game_date").on("change", function(){
            var new_date = $(this).val();
            // console.log($(this).val());
            $.ajax({
                method: "GET",
                url: "ajax.php",
                data: {
                    action: "get_games_by_date",
                    date: new_date
                }
            }).done(function( msg ) {
                if (msg != ''){
                    $(".game_list_by_date").html(msg);
                }
            });
        });
        $(".game_list_by_date").on("change", function(){
            var new_game = $(this).find("option:selected").val();
            var new_game_text = $(this).find("option:selected").text();
            if (new_game != ''){
                var games_list = $("#games_auto_val").val();
                if (games_list != ''){
                    games_list = games_list.split(',');
                } else {
                    games_list = [];
                }
                if (games_list.indexOf(new_game)<0){
                    games_list.push(new_game);
                    $(".game_list_added").append('<span class="selected_game_s" data-id="' + new_game + '">' + new_game_text + '</span>');
                    $("#games_auto_val").val(games_list.join(','));
                }
            }
        });
        $('body').on('click', ".selected_game_s", function(){
            var games_list = $("#games_auto_val").val();
            if (games_list != ''){
                games_list = games_list.split(',');
                var remove_game = $(this).data('id').toString();
                var games_list_i = games_list.indexOf(remove_game);
                games_list.splice(games_list_i, 1);
                $("#games_auto_val").val(games_list.join(','));
                $(this).remove();
            }
        });
    }

    if ($(".section_top_n").length>0){
        if ($(".section_s.active_s").length>0){
            $(".section_s.active_s").slideDown();
        }
        if ($(".n_top_s.active_s").length>0){
            $(".n_top_s.active_s").slideDown();
        }

        $(".section_top_n").on("change", function(){
            var s_top_n = $(this).find("option:selected").val();
            if (s_top_n>0){
                $(".section_s").slideDown();
                $(".n_top_s").not(".n_top_s_"+s_top_n).slideUp();
                $(".n_top_s_"+s_top_n).slideDown();
            } else {
                $(".section_s").slideUp();
            }
        });

        $("#country_auto_s").on('change', function(){
            var new_country = $(this).find("option:selected").val();
            var new_country_text = $(this).find("option:selected").text();
            // console.log('new_country:', new_country);
            if (new_country != ''){
                var s_top_n = $(".section_top_n").find("option:selected").val();
                var countries = $("#country_auto_val_s_"+s_top_n).val();
                if (countries != ''){
                    countries = countries.split(',');
                } else {
                    countries = [];
                }
                if (countries.indexOf(new_country)<0){
                    countries.push(new_country);
                    $(".n_top_s_"+s_top_n).append('<span class="selected_country_s" data-id="' + new_country + '">' + new_country_text + '</span>');
                    $("#country_auto_val_s_"+s_top_n).val(countries.join(','));
                }
            }
        });
        $('body').on('click', ".selected_country_s", function(){
            var s_top_n = $(".section_top_n").find("option:selected").val();
            var countries = $("#country_auto_val_s_"+s_top_n).val();
            if (countries != ''){
                countries = countries.split(',');
                var remove_country = $(this).data('id').toString();
                var countries_i = countries.indexOf(remove_country);
                countries.splice(countries_i, 1);
                $("#country_auto_val_s_"+s_top_n).val(countries.join(','));
                $(this).remove();
            }
        });

        $("#champ_auto_s").on('change', function(){
            var new_championship = $(this).find("option:selected").val();
            var new_championship_text = $(this).find("option:selected").text();
            if (new_championship != ''){
                var s_top_n = $(".section_top_n").find("option:selected").val();
                var championships = $("#champ_auto_val_s_"+s_top_n).val();
                if (championships != ''){
                    championships = championships.split(',');
                } else {
                    championships = [];
                }
                if (championships.indexOf(new_championship)<0){
                    championships.push(new_championship);
                    $(".n_top_s_"+s_top_n).append('<span class="selected_championship_s" data-id="' + new_championship + '">' + new_championship_text + '</span>');
                    $("#champ_auto_val_s_"+s_top_n).val(championships.join(','));
                }
            }
        });
        $('body').on('click', ".selected_championship_s", function(){
            var s_top_n = $(".section_top_n").find("option:selected").val();
            var championships = $("#champ_auto_val_s_"+s_top_n).val();
            if (championships != ''){
                championships = championships.split(',');
                var remove_championship = $(this).data('id').toString();
                var championships_i = championships.indexOf(remove_championship);
                championships.splice(championships_i, 1);
                $("#champ_auto_val_s_"+s_top_n).val(championships.join(','));
                $(this).remove();
            }
        });
    }

    if ($('ul.page_list').length>0){
        $('ul.page_list li').each(function(){
            if ($(this).children("ul").length>0) {
                $(this).addClass("expand_off");
            }
        });
    }

    $("body").on('click', 'li.expand_off em, li.expand_on em', function(e){
        e.preventDefault();
        elemLi = $(this).parent("li");
        if (elemLi.hasClass("expand_off")){
            elemLi.removeClass("expand_off");
            elemLi.addClass("expand_on");
        } else if (elemLi.hasClass("expand_on")) {
            elemLi.removeClass("expand_on");
            elemLi.addClass("expand_off");
        }
    });
});


function getSelectTeam(element) {
    Elem = document.getElementById(element);
    Elem.style = 'position: absolute; height: 200px; width: 300px; display: block;';
}

function game_action(g_id, t_id, st_id, action) {
    Elem = document.getElementById(st_id+'_'+action);
    Elem.innerHTML = '<div id="add_ga"><form method="post" onsubmit="javascript: game_action_add('+g_id+', '+t_id+', '+st_id+', \''+action+'\', this.min.value); return false;"><input type="text" name="min" size="2"  style="width: 25px;"> м. <br> <input type="submit" value="" id="input_submit"> <a href="javascript: void(0);" onclick="javascript: game_action_cancel('+g_id+', '+t_id+', '+st_id+', \''+action+'\')" id="cancel">&nbsp;</a></form></div>';
}

function game_action_cancel(g_id, t_id, st_id, action) {
    Elem = document.getElementById(st_id+'_'+action);
    Elem.innerHTML = '<a href="javascript: void(0);" onclick="javascript: game_action('+g_id+', '+t_id+', '+st_id+', \''+action+'\')"  id="add">&nbsp;</a>';
}

function game_action_edit(ga_id, min) {
    Elem = document.getElementById(ga_id);
    Elem.innerHTML = '<div id="edit_ga"><form onsubmit="javascript: game_action_edit_yes('+ga_id+', this.min.value); return false;"><input type="text" name="min" value="'+min+'" size="2" style="width: 25px;"> м. <br> <input type="submit" value="" id="input_submit"> <a href="javascript: void(0);" onclick="javascript: game_action_edit_cancel('+ga_id+', '+min+')" id="cancel">&nbsp;</a></form><a href="javascript: void(0);" onclick="javascript: game_action_delete('+ga_id+', '+min+')" id="delete">&nbsp;</a></div>';
}

function game_action_edit_cancel (ga_id, min) {
    Elem = document.getElementById(ga_id);
    if (min>0) text = min+' м.';
    else text = '+1 шт.';
    Elem.innerHTML = '<a href="javascript: void(0);" onclick="javascript: game_action_edit('+ga_id+', '+min+')" >'+text+'</a>';
}

function game_action_delete(ga_id, min) {
    Elem = document.getElementById(ga_id);
    Elem.innerHTML = '<div id="delete_ga"><b>Удалить?</b><a href="javascript: void(0);" onclick="javascript: game_action_delete_yes('+ga_id+')" id="input_submit">&nbsp;</a><a href="javascript: void(0);" onclick="javascript: game_action_edit_cancel('+ga_id+', '+min+')" id="cancel">&nbsp;</a></div>';
}

function game_action_delete_yes(ga_id) {
    Elem = document.getElementById(ga_id);
    //if (min>0) text = min+' м.';
    //else text = '+1 шт.';
    //Elem.innerHTML = '<a href="javascript: void(0);" onclick="javascript: game_action_edit('+ga_id+', '+min+')" >'+text+'</a>';
    Elem.innerHTML = '1';

    var req = getXmlHttp()
    req.open('GET', '/game_action.php?do=delete&ga_id='+ga_id, true)
    req.send(null)
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if(req.status == 200) {
                if (req.responseText!="") Elem.style.display = 'none';
            }
        }
    }
}

function game_action_edit_yes(ga_id, min) {
    Elem = document.getElementById(ga_id);
    //if (min>0) text = min+' м.';
    //else text = '+1 шт.';
    //Elem.innerHTML = '<a href="javascript: void(0);" onclick="javascript: game_action_edit('+ga_id+', '+min+')" >'+text+'</a>';
    Elem.innerHTML = '';

    var req = getXmlHttp()
    req.open('GET', '/game_action.php?do=edit&ga_id='+ga_id+'&min='+min, true)
    req.send(null)
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if(req.status == 200) {
                if (req.responseText!="") Elem.innerHTML = req.responseText;
            }
        }
    }
}

function game_action_add(g_id, t_id, st_id, action, min) {

    Elem = document.getElementById(st_id+'_'+action);
    Elem.innerHTML = '<a href="javascript: void(0);" onclick="javascript: game_action('+g_id+', '+t_id+', '+st_id+', \''+action+'\')"  id="add">&nbsp;</a>';
    //alert('/game_action.php?do=add&action='+action+'&min='+min+'&g_id='+g_id+'&t_id='+t_id+'&st_id='+st_id);

    var req = getXmlHttp();

    req.open('GET', '/game_action.php?do=add&action='+action+'&min='+min+'&g_id='+g_id+'&t_id='+t_id+'&st_id='+st_id, true);

    req.send(null);

    req.onreadystatechange = function() {

        if (req.readyState == 4) {
            if(req.status == 200) {

                if (req.responseText!="") document.getElementById(st_id+'_'+action+'_A').innerHTML = req.responseText;
            }
        }
    }
}

function showBlock(el_sh) {
    El = document.getElementById(el_sh);
    if (El.style.display == "none" || El.style.display=="") {
//        El.style.display = "progid:DXImageTransform.Microsoft.Alpha(Opacity = 0)";
        El.style.opacity = 0;
        El.style.display = "block";
        for (i=1; i<=10; i++){
            o = 0.1*i;
            ie_o = Math.round(o*100);
            t = i*30;
            setTimeout('El.style.opacity = '+o, t);
//            setTimeout('El.filters.item("DXImageTransform.Microsoft.Alpha").Opacity = '+ie_o, t);
        }
    } else {
//        El.style.display = "progid:DXImageTransform.Microsoft.Alpha(Opacity = 100)";
        El.style.opacity = 1;

        for (i=1; i<=10; i++){
            o = 1-0.1*i;
            ie_o = Math.round(o*100);
            t = i*30;
            setTimeout('El.style.opacity = '+o, t);
//            setTimeout('El.filters.item("DXImageTransform.Microsoft.Alpha").Opacity = '+ie_o, t);
        }
        setTimeout('El.style.display = "none"', t);
    }
}

function showTextLang(el)
{
    InptEl = document.getElementById(el);
    if (InptEl.style.display == "none" || InptEl.style.display=="") {
        for (i = 1; i <= 6; i++) {
            if (document.getElementById("cont_"+i)) document.getElementById("cont_"+i).style.display = "none";
        }
        InptEl.style.display = "block";
        // Скрывать блок "Читайте также" на вкладке Галерея (cont_4)
        var relatedBlock = document.getElementById("related_news_block");
        if (relatedBlock) {
            relatedBlock.style.display = (el === "cont_4") ? "none" : "block";
        }
    }
}
function showBlokLang(el)
{
    InptEl = document.getElementById(el);
    if (InptEl.style.display == "none" || InptEl.style.display=="") {
        for (i = 1; i <= 3; i++) {
            document.getElementById("lang_"+i).style.display = "none";
        }
        InptEl.style.display = "block";
    }
}
function showImgLang(el)
{
    InptEl = document.getElementById(el);
    if (InptEl.style.display == "none" || InptEl.style.display=="") {
        for (i = 1; i <= 3; i++) {
            document.getElementById("img_lang_"+i).style.display = "none";
        }
        InptEl.style.display = "block";
    }
}

function showVarLang(vn, el)
{
    InptEl = document.getElementById(vn+el);
    if (InptEl.style.display == "none" || InptEl.style.display=="") {
        for (i = 1; i <= 3; i++) {
            document.getElementById(vn+i).style.display = "none";
        }
        InptEl.style.display = "block";
    }
}

function showGalBlock(el)
{
    InptEl = document.getElementById(el);
    if (InptEl.style.display == "none" || InptEl.style.display=="") {
        for (i = 1; i <= 2; i++) {
            document.getElementById("gal_"+i).style.display = "none";
        }
        InptEl.style.display = "block";
    }
}

// Список городов
function getXmlHttp(){
    var xmlhttp
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e1) {
            xmlhttp = false;
        }
    }

    if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
        xmlhttp = new XMLHttpRequest();
    }

    return xmlhttp;
}

function getCityList(pcountry, city, std){
    var req = getXmlHttp()
    req.open('GET', '/get_city.php?pcountry='+pcountry+'&city='+city+'&stadium='+std, true)
    req.send(null)
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if(req.status == 200) {
                //alert(req.responseText)
                if (req.responseText!=""){

                    document.getElementById("city").innerHTML = req.responseText;
                    getStadiumList(0, 0);

                }
            }
        }
    }
}

function getStadiumList(city, stadium){
    var req = getXmlHttp()
    req.open('GET', '/get_stadium.php?city='+city+'&stadium='+stadium, true)
    req.send(null)
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if(req.status == 200) {
                //alert(req.responseText)
                //if (req.responseText!=""){
                if(city!=0){
                    document.getElementById("stadium").innerHTML = req.responseText;
                } else {
                    document.getElementById("stadium").innerHTML = "нет стадионов";
                }
                //}
            }
        }
    }
}

function getCompTeam(ch, comp){
    var req = getXmlHttp()
    req.open('GET', '/get_ch_team.php?ch='+ch+'&comp='+comp, true)
    req.send(null)
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if(req.status == 200) {
                if (req.responseText!="") document.getElementById("owner_t").innerHTML = '<select name="g_owner_t_id" id="input100" onchange="getCompTeamGuest('+ch+', '+comp+', this.value, 0)">' + req.responseText + '</select>';
                else document.getElementById("owner_t").innerHTML = 'нет команд';

                if (req.responseText!="") document.getElementById("guest_t").innerHTML = '<select name="g_guest_t_id" id="input100" onchange="getCompTeamOwner('+ch+', '+comp+', this.value, 0)">' + req.responseText + '</select>';
                else document.getElementById("guest_t").innerHTML = 'нет команд';
            }
        }
    }
}

// Список команд Гостей в форме
// ch   - id чемпионата
// comp - id соревнования
// d_t  - id команды, которую не показать в списке Хозяев
// a_t  - id команды, которую отметить выбранной в списке Хозяев
function getCompTeamGuest(ch, comp, d_t, a_t){
    var req = getXmlHttp()
    req.open('GET', '/get_ch_team.php?ch='+ch+'&comp='+comp+'&a_t='+a_t+'&d_t='+d_t, true)
    req.send(null)
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if(req.status == 200) {
                if (req.responseText!="") document.getElementById("guest_t").innerHTML = '<select name="g_guest_t_id" id="input100" onchange="getCompTeamOwner('+ch+', '+comp+', this.value, \''+d_t+'\')">' + req.responseText + '</select>';
                else document.getElementById("guest_t").innerHTML = 'нет команд';
            }
        }
    }
}

// Список команд Хозяев в форме
// ch   - id чемпионата
// comp - id соревнования
// d_t  - id команды, которую не показать в списке Гостей
// a_t  - id команды, которую отметить выбранной в списке Гостей
function getCompTeamOwner(ch, comp, d_t, a_t){
    var req = getXmlHttp()
    req.open('GET', '/get_ch_team.php?ch='+ch+'&comp='+comp+'&a_t='+a_t+'&d_t='+d_t, true)
    req.send(null)
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if(req.status == 200) {
                if (req.responseText!="") document.getElementById("owner_t").innerHTML = '<select name="g_owner_t_id" id="input100" onchange="getCompTeamGuest('+ch+', '+comp+', this.value, \''+d_t+'\')">' + req.responseText + '</select>';
                else document.getElementById("owner_t").innerHTML = 'нет команд';
            }
        }
    }
}


$(document).ready(function(){
    // CHAMP
    // fot post
    $("#champ_auto").on('change', function(){
        var new_champ = $(this).find("option:selected").val();
        if (new_champ != ''){
            var countries = $("#champ_auto_val").val();
            if (countries != ''){
                countries = countries.split(',');
            } else {
                countries = [];
            }
            if (countries.indexOf(new_champ)<0){
                countries.push(new_champ);
                $(this).after('<span class="selected_champ" data-id="' + new_champ + '">' + $(this).find("option:selected").text() + '</span>');
                $("#champ_auto_val").val(countries.join(','));
            }
        }
    });
    $('body').on('click', ".selected_champ", function(){
        var countries = $("#champ_auto_val").val();
        if (countries != ''){
            countries = countries.split(',');
            var remove_champ = $(this).data('id').toString();
            var countries_i = countries.indexOf(remove_champ);
            countries.splice(countries_i, 1);
            $("#champ_auto_val").val(countries.join(','));
            $(this).remove();
        }
    });
    // for post gallery photos
    $("#ph_champ_auto").on('change', function(){
        var new_champ = $(this).find("option:selected").val();
        if (new_champ != ''){
            var countries = $("#ph_champ_auto_val").val();
            if (countries != ''){
                countries = countries.split(',');
            } else {
                countries = [];
            }
            if (countries.indexOf(new_champ)<0){
                countries.push(new_champ);
                $(this).after('<span class="ph_selected_champ" data-id="' + new_champ + '">' + $(this).find("option:selected").text() + '</span>');
                $("#ph_champ_auto_val").val(countries.join(','));
            }
        }
    });
    $('body').on('click', ".ph_selected_champ", function(){
        var countries = $("#ph_champ_auto_val").val();
        if (countries != ''){
            countries = countries.split(',');
            var remove_champ = $(this).data('id').toString();
            var countries_i = countries.indexOf(remove_champ);
            countries.splice(countries_i, 1);
            $("#ph_champ_auto_val").val(countries.join(','));
            $(this).remove();
        }
    });
    // for post gallery video
    $("#v_champ_auto").on('change', function(){
        var new_champ = $(this).find("option:selected").val();
        if (new_champ != ''){
            var countries = $("#v_champ_auto_val").val();
            if (countries != ''){
                countries = countries.split(',');
            } else {
                countries = [];
            }
            if (countries.indexOf(new_champ)<0){
                countries.push(new_champ);
                $(this).after('<span class="v_selected_champ" data-id="' + new_champ + '">' + $(this).find("option:selected").text() + '</span>');
                $("#v_champ_auto_val").val(countries.join(','));
            }
        }
    });
    $('body').on('click', ".v_selected_champ", function(){
        var countries = $("#v_champ_auto_val").val();
        if (countries != ''){
            countries = countries.split(',');
            var remove_champ = $(this).data('id').toString();
            var countries_i = countries.indexOf(remove_champ);
            countries.splice(countries_i, 1);
            $("#v_champ_auto_val").val(countries.join(','));
            $(this).remove();
        }
    });


    // CHAMPIONSHIP
    // fot post
    $("#championship_auto").on('change', function(){
        var new_championship = $(this).find("option:selected").val();
        if (new_championship != ''){
            var countries = $("#championship_auto_val").val();
            if (countries != ''){
                countries = countries.split(',');
            } else {
                countries = [];
            }
            if (countries.indexOf(new_championship)<0){
                countries.push(new_championship);
                $(this).after('<span class="selected_championship" data-id="' + new_championship + '">' + $(this).find("option:selected").text() + '</span>');
                $("#championship_auto_val").val(countries.join(','));
            }
        }
    });
    $('body').on('click', ".selected_championship", function(){
        var countries = $("#championship_auto_val").val();
        if (countries != ''){
            countries = countries.split(',');
            var remove_championship = $(this).data('id').toString();
            var countries_i = countries.indexOf(remove_championship);
            countries.splice(countries_i, 1);
            $("#championship_auto_val").val(countries.join(','));
            $(this).remove();
        }
    });

    // COUNTRY
    // fot post
    $("#country_auto").on('change', function(){
        var new_country = $(this).find("option:selected").val();
        if (new_country != ''){
            var countries = $("#country_auto_val").val();
            if (countries != ''){
                countries = countries.split(',');
            } else {
                countries = [];
            }
            if (countries.indexOf(new_country)<0){
                countries.push(new_country);
                $(this).after('<span class="selected_country" data-id="' + new_country + '">' + $(this).find("option:selected").text() + '</span>');
                $("#country_auto_val").val(countries.join(','));
            }
        }
    });
    $('body').on('click', ".selected_country", function(){
        var countries = $("#country_auto_val").val();
        if (countries != ''){
            countries = countries.split(',');
            var remove_country = $(this).data('id').toString();
            var countries_i = countries.indexOf(remove_country);
            countries.splice(countries_i, 1);
            $("#country_auto_val").val(countries.join(','));
            $(this).remove();
        }
    });
    // for post gallery photos
    $("#ph_country_auto").on('change', function(){
        var new_country = $(this).find("option:selected").val();
        if (new_country != ''){
            var countries = $("#ph_country_auto_val").val();
            if (countries != ''){
                countries = countries.split(',');
            } else {
                countries = [];
            }
            if (countries.indexOf(new_country)<0){
                countries.push(new_country);
                $(this).after('<span class="ph_selected_country" data-id="' + new_country + '">' + $(this).find("option:selected").text() + '</span>');
                $("#ph_country_auto_val").val(countries.join(','));
            }
        }
    });
    $('body').on('click', ".ph_selected_country", function(){
        var countries = $("#ph_country_auto_val").val();
        if (countries != ''){
            countries = countries.split(',');
            var remove_country = $(this).data('id').toString();
            var countries_i = countries.indexOf(remove_country);
            countries.splice(countries_i, 1);
            $("#ph_country_auto_val").val(countries.join(','));
            $(this).remove();
        }
    });
    // for post gallery video
    $("#v_country_auto").on('change', function(){
        var new_country = $(this).find("option:selected").val();
        if (new_country != ''){
            var countries = $("#v_country_auto_val").val();
            if (countries != ''){
                countries = countries.split(',');
            } else {
                countries = [];
            }
            if (countries.indexOf(new_country)<0){
                countries.push(new_country);
                $(this).after('<span class="v_selected_country" data-id="' + new_country + '">' + $(this).find("option:selected").text() + '</span>');
                $("#v_country_auto_val").val(countries.join(','));
            }
        }
    });
    $('body').on('click', ".v_selected_country", function(){
        var countries = $("#v_country_auto_val").val();
        if (countries != ''){
            countries = countries.split(',');
            var remove_country = $(this).data('id').toString();
            var countries_i = countries.indexOf(remove_country);
            countries.splice(countries_i, 1);
            $("#v_country_auto_val").val(countries.join(','));
            $(this).remove();
        }
    });

    $('body').on('click', '.form_disabled input[type="submit"]', function(e){
        e.preventDefault();
        e.stopPropagation();
        return false;
    });
});
