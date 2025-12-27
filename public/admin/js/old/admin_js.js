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
		req.open('GET', 'http://credo63.com/game_action.php?do=delete&ga_id='+ga_id, true)
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
		req.open('GET', 'http://credo63.com/game_action.php?do=edit&ga_id='+ga_id+'&min='+min, true)
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
		//alert('http://credo63.com/game_action.php?do=add&action='+action+'&min='+min+'&g_id='+g_id+'&t_id='+t_id+'&st_id='+st_id);
		alert('1');
		var req = getXmlHttp();
		alert('2');
		req.open('GET', 'http://credo63.com/game_action.php?do=add&action='+action+'&min='+min+'&g_id='+g_id+'&t_id='+t_id+'&st_id='+st_id, true);
		alert('3');
		req.send(null);
		alert('4');
		req.onreadystatechange = function() {
		alert ('123');
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
			El.style.display = "progid:DXImageTransform.Microsoft.Alpha(Opacity = 0)";
			El.style.opacity = 0;
			El.style.display = "block";
			for (i=1; i<=10; i++){
				o = 0.1*i;
				ie_o = Math.round(o*100);
				t = i*30;
				setTimeout('El.style.opacity = '+o, t);
				setTimeout('El.filters.item("DXImageTransform.Microsoft.Alpha").Opacity = '+ie_o, t);
			}
		} else {
			El.style.display = "progid:DXImageTransform.Microsoft.Alpha(Opacity = 100)";
			El.style.opacity = 1;
			
			for (i=1; i<=10; i++){
				o = 1-0.1*i;
				ie_o = Math.round(o*100);
				t = i*30;
				setTimeout('El.style.opacity = '+o, t);
				setTimeout('El.filters.item("DXImageTransform.Microsoft.Alpha").Opacity = '+ie_o, t);
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
		req.open('GET', 'http://credo63.com/get_city.php?pcountry='+pcountry+'&city='+city+'&stadium='+std, true)
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
		req.open('GET', 'http://credo63.com/get_stadium.php?city='+city+'&stadium='+stadium, true)
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
		req.open('GET', 'http://credo63.com/get_ch_team.php?ch='+ch+'&comp='+comp, true)
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
		req.open('GET', 'http://credo63.com/get_ch_team.php?ch='+ch+'&comp='+comp+'&a_t='+a_t+'&d_t='+d_t, true)
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
		req.open('GET', 'http://credo63.com/get_ch_team.php?ch='+ch+'&comp='+comp+'&a_t='+a_t+'&d_t='+d_t, true)
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
	