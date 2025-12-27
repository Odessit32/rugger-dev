// new part of scripts starts

jQuery(document).ready(function($){

	// auto load more news on front page. mobile only
	if ($(".show_more_index_news").length > 0) {
		if (window.innerWidth < 721) {
			var news_scroll_handler = false;
			var load_more_position_cklick = false;

			$(document).on("scroll", function () {
				news_scroll_handler = true;
				var load_more_position = $(".show_more_index_news").position().top - $(this).scrollTop() - window.innerHeight;

				if (load_more_position < 200 && !load_more_position_cklick) {
					load_more_position_cklick = true;
					$(".show_more_index_news").trigger("click");
					setTimeout(function () {
						load_more_position_cklick = false;
					}, 1000);
				}
			})
		}
	}

    // results informer
    if ($(".results").length > 0) {
        $(".results .results_block_title").bind("click", function(){
            if ($(this).next('div').css('display') != 'block'){
                $(".results .results_block_text").each(function(){
                    $(this).slideUp();
                });
                $(this).next(".results_block_text").slideDown();
            }
        });
        // tables
        if ($(".results select[name='results_taple_ch_list']").length >0){
            $(".results select[name='results_taple_ch_list']").on("change", function(){
                var ch_id = $(".results select[name='results_taple_ch_list'] option:selected").val();
                if (ch_id>0) {
                    $.ajax({
                        url: "/ajax.php",
                        type: "POST",
                        data: {
                            ch_id: ch_id,
                            action: 'inf_result_table'
                        },
                        dataType: 'html',
                        success: function(response, textStatus, jqXHR){
                            if (response != ''){
                                $(".results .results_block_text .table_data").html(response);
                            }
                        }
                    });
                }
            });
        }
        // soon
        if ($(".results #date_soon").length >0){
            $(".results #date_soon").on("change", function(){
                var date_soon = $(".results #date_soon").val();
                if (date_soon != '') {
                    $.ajax({
                        url: "/ajax.php",
                        type: "POST",
                        data: {
                            date_soon: date_soon,
                            action: 'inf_result_soon'
                        },
                        dataType: 'html',
                        success: function(response, textStatus, jqXHR){
                            if (response != ''){
                                $(".results #soon_games").html(response);
                            }
                        }
                    });
                }
            });
        }
        // game_list
        if ($(".results #date_game_list").length >0){
            $(".results #date_game_list").on("change", function(){
                var date_game_list = $(".results #date_game_list").val();
                if (date_game_list != '') {
                    $.ajax({
                        url: "/ajax.php",
                        type: "POST",
                        data: {
                            date_game_list: date_game_list,
                            action: 'inf_result_game_list'
                        },
                        dataType: 'html',
                        success: function(response, textStatus, jqXHR){
                            if (response != ''){
                                $(".results #was_games").html(response);
                            }
                        }
                    });
                }
            });
        }
    }

	// team tabs
	if ($(".team_item")) {
		$(".team_item .menu li:first").addClass("active");
		$(".team_item .post:first").css("display", "block");
	}

	// competition list of games. last games
	$(".games_list .last").each(function(){
        $(this).css('display', 'block');
    });

	// round nav menu
	$(".round_nav .nav_center #active").bind('click', function(){
		if ($("#nav_list").css('display') == 'none') {
			$("#nav_list").slideDown('fast');
			$(".round_nav_close").css('display','block');
		} else {
			$("#nav_list").slideUp('fast');
			$(".round_nav_close").css('display','none');
		}
		return false;
	});
	$(".round_nav_close").bind('click', function(){
		$("#nav_list").slideUp('fast');
		$(".round_nav_close").css('display','none');
	});
	$("#nav_list a").bind('click', function(){
		var $this = $(this);
		$("#nav_list a").each(function(){
			if ($(this).hasClass('last')) $(this).removeClass('last');
		});
		if ($this.hasClass('allgames')){
			$(".games_list .item").each(function(){
				if ($(this).hasClass('last')) $(this).removeClass('last');
				$(this).css('display', 'block');
			});
		} else {
			$(".games_list .item").each(function(){
				if ($(this).hasClass('last')) $(this).removeClass('last');
				if ($(this).hasClass($this.attr('class'))) {
					$(this).css('display', 'block');
					
				} else $(this).css('display', 'none');
			});
		}
		$this.addClass('last');
		$(".round_nav .nav_center #active").text($this.text());
		$(".round_nav .nav_center #active").attr('href', '#'+$this.text());
		if ($this.next().length == 0 || $this.next().hasClass('allgames')){
			$(".games_list .round_nav .nav_right").text('');
		} else {
			$(".games_list .round_nav .nav_right").text('>>>');
		}
		if ($this.prev().length == 0){
			$(".games_list .round_nav .nav_left").text('');
		} else {
			$(".games_list .round_nav .nav_left").text('<<<');
		}
		$("#nav_list").slideUp('fast');
		$(".round_nav_close").css('display','none');
		return false;
	});
	// next round
	$(".games_list .round_nav .nav_right").bind('click', function(){
		if ($(this).text() != ''){
			$("#nav_list a").each(function(){
				if ($(this).hasClass('last')) {
					var $this = $(this);
					if ($this.next().length > 0){
						if (!$this.next().hasClass('allgames')){
							$this.removeClass('last');
							$this = $this.next();
							$(".games_list .item").each(function(){
								if ($(this).hasClass('last')) $(this).removeClass('last');
								if ($(this).hasClass($this.attr('class'))) {
									$(this).css('display', 'block');
								} else $(this).css('display', 'none');
							});
							$this.addClass('last');
							$(".round_nav .nav_center #active").text($this.text());
							$(".round_nav .nav_center #active").attr('href', '#'+$this.text());
							if ($this.next().length == 0 || $this.next().hasClass('allgames')){
								$(".games_list .round_nav .nav_right").text('');
							} else {
								$(".games_list .round_nav .nav_right").text('>>>');
							}
							if ($this.prev().length == 0){
								$(".games_list .round_nav .nav_left").text('');
							} else {
								$(".games_list .round_nav .nav_left").text('<<<');
							}
							return false;
						}
					}
				}
			});
		}
	});
	// prev round
	$(".games_list .round_nav .nav_left").bind('click', function(){
		if ($(this).text() != ''){
			$("#nav_list a").each(function(){
				if ($(this).hasClass('last')) {
					var $this = $(this);
					if ($this.prev().length > 0){
						if (!$this.prev().hasClass('allgames')){
							$this.removeClass('last');
							$this = $this.prev();
							$(".games_list .item").each(function(){
								if ($(this).hasClass('last')) $(this).removeClass('last');
								if ($(this).hasClass($this.attr('class'))) {
									$(this).css('display', 'block');
								} else $(this).css('display', 'none');
							});
							$this.addClass('last');
							$(".round_nav .nav_center #active").text($this.text());
							$(".round_nav .nav_center #active").attr('href', '#'+$this.text());
							if ($this.next().length == 0 || $this.next().hasClass('allgames')){
								$(".games_list .round_nav .nav_right").text('');
							} else {
								$(".games_list .round_nav .nav_right").text('>>>');
							}
							if ($this.prev().length == 0){
								$(".games_list .round_nav .nav_left").text('');
							} else {
								$(".games_list .round_nav .nav_left").text('<<<');
							}
							return false;
						}
					}
				}
			});
		}
	});
});

// new part of scripts ends






function showSlide(el_sh) {
	El = document.getElementById("slide_"+el_sh);
	if (!El) return false;
	if (El.style.display == "none" || El.style.display=="") {
		for (i = 0; i <= 3; i++) {
			if (document.getElementById("slide_"+i)) document.getElementById("slide_"+i).style.display = "none";
			document.getElementById("nav_"+i).className = "hidden";
		}
		El.style.filter = "progid:DXImageTransform.Microsoft.Alpha(Opacity = 0)";
		El.style.opacity = 0;
		El.style.display = "block";
		for (i=1; i<=10; i++){
			o = 0.1*i;
			ie_o = Math.round(o*100);
			t = i*25;
			setTimeout('El.style.opacity = '+o, t);
			setTimeout("El.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity="+ie_o+")'", t);
		}
		document.getElementById("nav_"+el_sh).className = "";
	}
}

function showPhoto(el_sh) {
	El = document.getElementById("photo_"+el_sh);
	if (El.style.display == "none" || El.style.display=="") {
		for (i = 0; i <= 5; i++) {
			if (document.getElementById("photo_"+i)) document.getElementById("photo_"+i).style.display = "none";
		}
		El.style.filter = "progid:DXImageTransform.Microsoft.Alpha(Opacity = 0)";
		El.style.opacity = 0;
		El.style.display = "block";
		for (i=1; i<=10; i++){
			o = 0.1*i;
			ie_o = Math.round(o*100);
			t = i*25;
			setTimeout('El.style.opacity = '+o, t);
			setTimeout("El.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity="+ie_o+")'", t);
		}
	}
}

var n=0;
var menuTimeout;
var slideInterval;

function slideShow() {
	var sl = document.getElementById("slideshow");
	var mn = document.getElementById("menu");
	if (!sl) return false;
	if (!mn) return false;
	
	slideInterval = setInterval(function() {
			if (n==3) n=0;
			else n++;
			showSlide(n);
	}, 5000);
	
	sl.onmouseover = function() {
		clearInterval(slideInterval);
	};
	
	sl.onmouseout = function() {
		slideShow();
	};
}

window.onload = function() {
	showSlide(0);
	slideShow();
};

function showBlock(el_show, el_count) {
	for (i = 1; i <= el_count; i++) if (document.getElementById('cont_'+i)) document.getElementById("cont_"+i).style.display = "none";
	if (document.getElementById('cont_'+el_show)) document.getElementById("cont_"+el_show).style.display = "block";	
	for (i = 1; i <= el_count; i++) if (document.getElementById('m_'+i)) document.getElementById('m_'+i).setAttribute('class','');
	if (document.getElementById('m_'+el_show)) document.getElementById('m_'+el_show).setAttribute('class','active');
}

function getMenuC(el_m_c){
	if (el_m_c>0){
		$("#m_"+el_m_c).next("a").addClass("active_m");
		for (i = 1; i <= 10; i++) if (document.getElementById('m_'+i)) document.getElementById("m_"+i).style.display = "none";
		if (document.getElementById('m_'+el_m_c)) document.getElementById("m_"+el_m_c).style.display = "block";
		if (document.getElementById('menu_c_bg')) document.getElementById("menu_c_bg").style.display = "block";
	}
}

function closeMenuC(){
	for (i = 1; i <= 10; i++) {
		if (document.getElementById('m_'+i)) {
			document.getElementById("m_"+i).style.display = "none";
			$("#m_"+i).next("a").removeClass("active_m");
		}
	}
	if (document.getElementById('menu_c_bg')) document.getElementById("menu_c_bg").style.display = "none";
}