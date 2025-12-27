function closePopup(){
	remDiv = document.getElementById("popup_bg");
	document.body.removeChild(remDiv);
	remDiv = document.getElementById("popup_win");
	document.body.removeChild(remDiv);
}
function get_ww() {
	var frameWidth=800; 
	if (self.innerWidth) frameWidth = self.innerWidth; 
	else if (document.documentElement && document.documentElement.clientWidth) frameWidth = document.documentElement.clientWidth; 
	else if (document.body) frameWidth = document.body.clientWidth; 
	return frameWidth; 
}
function get_wh() {
	var frameHeight=640; 
	if (self.innerHeight) frameHeight = self.innerHeight; 
	else if (document.documentElement && document.documentElement.clientHeight) frameHeight = document.documentElement.clientHeight; 
	else if (document.body) frameHeight = document.body.clientHeight; 
	return frameHeight; 
}

function bigImage(elem) {

	//alert (elem.getAttribute('href'));
	
	Img= new Image();
	Img.onload = function(){
		
		var h = Img.height;
		var w = Img.width;
		if (h>0) h = h+30;
		else h = 500;
		if (w>0) w = w+20;
		else w = 500;
		
		var parent = document.getElementsByTagName('BODY')[0];
	
		// popup_bg
		var newdiv_p_bg = document.createElement('div');
		newdiv_p_bg.id = 'popup_bg';
		newdiv_p_bg.style.left = '0';
		newdiv_p_bg.style.top = '0';
		newdiv_p_bg.innerHTML = " ";
		newdiv_p_bg.style.display = "block";
		newdiv_p_bg.setAttribute('onclick', "javascript:closePopup();");
		parent.appendChild(newdiv_p_bg);
		
		// popup_window
		var newdiv_p_w = document.createElement('div');
		
		newdiv_p_w.id = 'popup_win';
		var left = get_ww()/2-(w/2);
		newdiv_p_w.style.left = left+'px';
		var top = get_wh()/2-(h/2);
		if (top>150) top = 150;
		newdiv_p_w.style.top = top+'px';
		newdiv_p_w.style.width = w+'px';
		newdiv_p_w.style.height = h+'px';
		newdiv_p_w.innerHTML = '<a href="javascript:void(0)" onclick="javascript:closePopup();" title="Закрыть" id="popup_close" ><img src="images/popup_close.gif" border="0"/></a><div class="big_image"> <div class="image"><a href="javascript:void(0)" onclick="javascript:closePopup();"><img src="'+elem.getAttribute('href')+'" border="0"></a></div>   </div>';
		newdiv_p_w.style.display = "block";
		parent.appendChild(newdiv_p_w);
	}
	Img.src= elem.getAttribute('href');
	
	return false; 
}

function changeImage(id_elem) {
	elem = document.getElementById(id_elem);
	elem=elem.firstChild;
	while (elem.nodeType!=1) elem=elem.nextSibling; 
	
	Img= new Image();
	Img.onload = function(){
		next = get_nextsibling(elem);
		next_id = next.getAttribute('id')
		next_href = get_href(next);
		prev = get_prevsibling(elem);
		prev_id = prev.getAttribute('id')
		prev_href = get_href(prev);
		
		var h = Img.height;
		var w = Img.width;
		if (h>0) h = h+150;
		else h = 500;
		if (w>0) w = w+100;
		else w = 500;
		
		newdiv_p_w = document.getElementById('popup_win');
		var left = get_ww()/2-(w/2);
		newdiv_p_w.style.left = left+'px';
		var top = get_wh()/2-(h/2);
		if (top>150) top = 150;
		newdiv_p_w.style.top = top+'px';
		newdiv_p_w.style.width = w+'px';
		newdiv_p_w.style.height = h+'px';
		newdiv_p_w.innerHTML = '<a href="javascript:void(0)" onclick="javascript:closePopup();" title="Закрыть" id="popup_close" ><img src="../images/popup_close.gif" border="0"/></a><div class="big_image"><div class="b_prev"><a href="'+prev_href+'" onclick="return changeImage(\''+prev_id+'\');" style="height: '+Img.height+'px;"> </a></div>     <div class="b_next"><a href="'+next_href+'" onclick="return changeImage(\''+next_id+'\');" style="height: '+Img.height+'px;"> </a></div>            <div class="image"><a href="'+next_href+'" onclick="return changeImage(\''+next_id+'\');"><img src="'+elem.getAttribute('href')+'" border="0"></a></div>    </div><div class="image_text">'+elem.getAttribute('title')+'</div><div class="author_text"></div>';
	}
	Img.src= elem.getAttribute('href');
	
	return false; 
}
