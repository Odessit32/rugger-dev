	function change_news(n_item) {
		if (n_item == 0) {
			for (i = 1; i <= 4; i++) {
				if (document.getElementById("nmo_"+i).hasAttribute("class")) {
					if (i == 4) n_item = 1;
					else {
						n_item = i;
						n_item++;
					}
				}
			}
		}
		El_ph = document.getElementById('ph_'+n_item)
		El_t = document.getElementById('t_'+n_item)
		El_nav = document.getElementById('nav_'+n_item)
		El_nmo = document.getElementById('nmo_'+n_item)
		
		if (El_ph.style.display == "none" || El_ph.style.display=="") {
			for (i = 1; i <= 4; i++) {
				if (document.getElementById("ph_"+i)) document.getElementById("ph_"+i).style.display = "none";
				if (document.getElementById("t_"+i)) document.getElementById("t_"+i).style.display = "none";
				if (document.getElementById("nav_"+i)) document.getElementById("nav_"+i).style.display = "none";
				if (document.getElementById("nmo_"+i)) document.getElementById("nmo_"+i).removeAttribute("class")
			}
			El_nmo.setAttribute("class", "active");
			El_ph.style.display = El_t.style.display = "block";
			//El_ph.filters.item("DXImageTransform.Microsoft.Alpha").Opacity = 0;
			El_ph.style.opacity = 0;
			//El_t.filters.item("DXImageTransform.Microsoft.Alpha").Opacity = 50;
			El_t.style.opacity = 50;
			El_nav.style.display = "block";
			for (i=1; i<=5; i++){
				o = 0.5+0.1*i;
				ie_o = Math.round(o*100);
				t = i*30;
				setTimeout('El_ph.style.opacity = '+o, t);
				setTimeout('El_ph.filters.item("DXImageTransform.Microsoft.Alpha").Opacity = '+ie_o, t);
			}
			for (i=1; i<=5; i++){
				o = 0.2*i;
				ie_o = Math.round(o*100);
				t = i*30;
				setTimeout('El_t.style.opacity = '+o, t);
				setTimeout('El_t.filters.item("DXImageTransform.Microsoft.Alpha").Opacity = '+ie_o, t);
			}
		}
	}

	function getInfResultTable(ch_id){
		var req = getXmlHttp()
		req.open('GET', '/ajax.php?action=result_teble&ch_id='+ch_id, true)
		req.send(null) 
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(req.status == 200) {
					//alert(req.responseText)
					if (req.responseText!=""){
							document.getElementById('tables').innerHTML = req.responseText;
					}
				}
			}
		}
	}

	function showPlayerNav(el, c_el, p_id, t_id) 
	{
		for (i = 1; i <= c_el; i++) {
			na_el = document.getElementById("nav_"+i);
			if (na_el) {
				na_el.style.color = "#333";
				na_el.style.backgroundColor = "#eee";
				na_el.style.borderBottom = "1px solid #ddd";
				na_el.style.fontSize = "13px";
			}
		}
		A_El = document.getElementById('nav_'+el);
		A_El.style.color = "#d12028";
		A_El.style.backgroundColor = "#fff";
		A_El.style.borderBottom = "1px solid #fff";
		A_El.style.fontSize = "16px";
		
		af_player_stat(p_id, t_id, '');
	}
	
	function showPlayerNavPopup(el, c_el, p_id, t_id) 
	{
		for (i = 1; i <= c_el; i++) {
			na_el = document.getElementById("nav_"+i);
			if (na_el) {
				na_el.style.color = "#333";
				na_el.style.backgroundColor = "#eee";
				na_el.style.borderBottom = "1px solid #ddd";
				na_el.style.fontSize = "13px";
			}
		}
		A_El = document.getElementById('nav_'+el);
		A_El.style.color = "#d12028";
		A_El.style.backgroundColor = "#fff";
		A_El.style.borderBottom = "1px solid #fff";
		A_El.style.fontSize = "16px";
		
		af_player_stat_popup(p_id, t_id, '');
	}
	
	function af_player(p_id){
		
		var req = getXmlHttp()
		req.open('GET', '/ajax.php?action=pl_info&p_id='+p_id, true)
		req.send(null) 
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(req.status == 200) {
					//alert(req.responseText)
					if (req.responseText!=""){
						document.getElementById('popup_div_'+p_id).innerHTML = req.responseText;
					}
				}
			}
		}
		
	}
	
	function af_player_stat(p_id, t_id, ch_id){
		var req = getXmlHttp()
		req.open('GET', '/ajax.php?action=pl_stat&p_id='+p_id+'&t_id='+t_id+'&ch_id='+ch_id+'&popup=0', true)
		req.send(null) 
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(req.status == 200) {
					//alert(req.responseText)
					if (req.responseText!=""){
							document.getElementById('stat_text').innerHTML = req.responseText;
					}
				}
			}
		}
	}
	
	function af_player_stat_popup(p_id, t_id, ch_id){
		var req = getXmlHttp()
		req.open('GET', '/ajax.php?action=pl_stat&p_id='+p_id+'&t_id='+t_id+'&ch_id='+ch_id+'&popup=1', true)
		req.send(null) 
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(req.status == 200) {
					//alert(req.responseText)
					if (req.responseText!=""){
							document.getElementById('stat_text_'+p_id).innerHTML = req.responseText;
					}
				}
			}
		}
	}
	
	function af_player_slider(p_id, t_id, page){
		var req = getXmlHttp()
		req.open('GET', '/ajax.php?action=pl_slider&p_id='+p_id+'&t_id='+t_id+'&p='+page, true)
		req.send(null) 
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(req.status == 200) {
					//alert(req.responseText)
					if (req.responseText!=""){
							document.getElementById('player_slider').innerHTML = req.responseText;
					}
				}
			}
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

	function showBlockR(el_show, el_count) 
	{
		for (i = 1; i <= el_count; i++) if (document.getElementById('cont_r_'+i)) document.getElementById("cont_r_"+i).style.display = "none";
		if (document.getElementById('cont_r_'+el_show)) document.getElementById("cont_r_"+el_show).style.display = "block";
		
		for (i = 1; i <= el_count; i++) {
			if (document.getElementById('poonkt_'+i)) {
				document.getElementById('poonkt_'+i).style.borderBottom = "1px solid #ddd";
				document.getElementById('poonkt_'+i).style.background = "url(/images/poonkt.jpg) center top";
			}
		}
		
		if (document.getElementById('poonkt_'+el_show)) {
			document.getElementById('poonkt_'+el_show).style.borderBottom = "1px solid #fff";
			document.getElementById('poonkt_'+el_show).style.background = "#fff";
			document.getElementById('poonkt_'+el_show).A.style.color = "#ff0";
		}
	}

	function showBlock(el_show, el_count) 
	{
		
		for (i = 1; i <= el_count; i++) if (document.getElementById('cont_'+i)) document.getElementById("cont_"+i).style.display = "none";
		if (document.getElementById('cont_'+el_show)) document.getElementById("cont_"+el_show).style.display = "block";
		
		for (i = 1; i <= el_count; i++) {
			if (document.getElementById('m_poonkt_'+i)) {
				document.getElementById('m_poonkt_'+i).style.borderBottom = "1px solid #ddd";
				document.getElementById('m_poonkt_'+i).style.background = "url(/images/poonkt.jpg) center top";
			}
		}
		
		if (document.getElementById('m_poonkt_'+el_show)) {
			document.getElementById('m_poonkt_'+el_show).style.borderBottom = "1px solid #fff";
			document.getElementById('m_poonkt_'+el_show).style.background = "#fff";
			document.getElementById('m_poonkt_'+el_show).A.style.color = "#ff0";
		}
		
	}

	function showBlockNav() 
	{
		el = document.getElementById('nav_list');
		if (el.style.display == 'none' || el.style.display == '') el.style.display = 'block';
		else el.style.display = 'none';
	}

	function showBlockGames(el_show, el_count) 
	{
		document.getElementById('nav_list').style.display = "none";
		for (i = 0; i < el_count; i++) {
			if (document.getElementById("game_item_"+i)) document.getElementById("game_item_"+i).style.display = "none";
		}
		document.getElementById('game_item_'+el_show).style.display = "block";
		
		for (i = 0; i < el_count; i++) {
			if (document.getElementById("nav_item_"+i)) {
				document.getElementById("nav_item_"+i).style.background = "#fff";
				document.getElementById("nav_item_"+i).style.color = "#666666";
			}
		}
		document.getElementById("nav_item_"+el_show).style.background = "#666666";
		document.getElementById("nav_item_"+el_show).style.color = "#fff";
		if (document.getElementById("nav_current")) document.getElementById("nav_current").innerHTML = document.getElementById("nav_item_"+el_show).innerHTML;
		
		if (el_show>0) {
			el_prev = el_show;
			el_prev--;
			if (document.getElementById("nav_left")) {
				document.getElementById("nav_left").innerHTML = document.getElementById("nav_item_"+el_prev).innerHTML;
				document.getElementById("nav_left").setAttribute('onclick', "javascript:showBlockGames('"+el_prev+"', '"+el_count+"');");
			}
		} else document.getElementById("nav_left").innerHTML = '';
		
		
		if (el_show<el_count-1) {
			el_next = el_show;
			el_next++;
			if (document.getElementById("nav_right")) {
				document.getElementById("nav_right").innerHTML = document.getElementById("nav_item_"+el_next).innerHTML;
				document.getElementById("nav_right").setAttribute('onclick', "javascript:showBlockGames('"+el_next+"', '"+el_count+"');");
			}
		} else document.getElementById("nav_right").innerHTML = '';
	}

	

	// Ajax //////////////////////////////////////////////////////
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

	function af_g(id_div, action, id){
		var req = getXmlHttp()
		req.open('GET', '/ajax.php?action='+action+'&id='+id, true)
		req.send(null) 
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(req.status == 200) {
					//alert(req.responseText)
					if (req.responseText!=""){
						
							document.getElementById(id_div).innerHTML = req.responseText;
						
					}
				}
			}
		}
	}

	function af_s(id_div, action, id, f){
		var req = getXmlHttp()
		req.open('GET', '/ajax.php?action='+action+'&id='+id+'&f='+f, true)
		req.send(null) 
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if(req.status == 200) {
					//alert(req.responseText)
					if (req.responseText!=""){
						
							document.getElementById(id_div).innerHTML = req.responseText;
						
					}
				}
			}
		}
	}
	
	