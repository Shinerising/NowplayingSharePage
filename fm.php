<?php

    header("Content-Type:text/html; charset=utf-8");
	
	date_default_timezone_set('Asia/Shanghai');
	
	$doc = new DOMDocument();
	$doc->load("http://music.wayshine.us/api.php");
	$title=urldecode($doc->getElementsByTagName("title")->item(0)->nodeValue);
	$artist=urldecode($doc->getElementsByTagName("artist")->item(0)->nodeValue);
	$album=urldecode($doc->getElementsByTagName("album")->item(0)->nodeValue);
	$coverimg=urldecode($doc->getElementsByTagName("coverimg")->item(0)->nodeValue);
		
	sqlite_close($db);
	
	$ytsearch="https://gdata.youtube.com/feeds/api/videos?max-results=1&q=".$title."+".$artist;
	$doc = new DOMDocument();
	$doc->load($ytsearch);
	
	$yts=$doc->getElementsByTagName( "id" );
	$yt=$yts->item(1)->nodeValue;
	if($yt==null){
		$ytsearch="https://gdata.youtube.com/feeds/api/videos?max-results=1&q=".$title;
		$doc = new DOMDocument();
		$doc->load($ytsearch);
	
		$yts=$doc->getElementsByTagName( "id" );
		$yt=$yts->item(1)->nodeValue;
	}
	$yt=substr($yt, -11);
	$yturl="https://www.youtube.com/embed/".$yt."?autohide=0&theme=light&color=white&autoplay=1";
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html itemtype="http://schema.org/Review" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head profile="http://www.w3.org/2005/10/profile">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo urldecode("%E2%98%8A") ?> NowListening - <?php echo stripslashes($title) ?> </title>
	<link rel="icon" type="image/vnd.microsoft.icon" href="favicon.ico" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
    <style type="text/css">
	html	{
	}
	
	a	{
	text-decoration: none;
	color:#222;
	}
	
	a:hover	{
	color:#aaa;
	}
	
	#musicinfo	{
	width: 500px;
	height: auto;
	margin: 0 auto;
	margin-top: 20px;
	font-size: 14px;
	font-family: Georgia, Arial, serif;
	line-height: 20px;
	}
	
	#coverbox	{
	float: left;
	font-size: 14px;
	text-align: center;
	color: #000;
	width: 165px;
	height: 165px;
	background: #FFF;
	border: rgba(255,255,255,.7) 10px solid;
	box-shadow: -10px 10px 50px #666;
	overflow:hidden;
	}
	
	#nextbutton	{
	position: absolute;
	width: 165px;
	height: 165px;
	font-size: 30px;
	text-align: center;
	line-height: 165px;
	font-weight: bold;
	font-family: Arial, Sans-serif;
	color: #FFF;
	opacity: 0;
	background: rgba(100,100,100,0.8);
	cursor: pointer;
	}
	
	#nextbutton:hover	{
	opacity: 1;
	}
	
	#cover	{
	width: 165px;
	}
	
	#detail	{
	float: right;
	width: 260px;
	height: 145px;
	overflow: visible;
	padding: 10px;
	border: rgba(255,255,255,.7) 10px solid;
	background: rgba(250,250,250,.5);
	box-shadow: 10px 10px 50px #666;
	}
	
	#head	{
	font-size: 16px;
	line-height: 22px;
	}
	
	#subhead	{
	font-size: 14px;
	}
	
	#frequent	{
	width:260px;
	height:30px;
	}
	
	.line	{
	padding-top:5px;
	padding-bottom:5px;
	height:20px;
	line-height:21px;
	overflow:hidden;
	width:10000px;
	}
	
	#textarea	{
	overflow:hidden;
	}
	
	.t	{
	font-weight:bold;
	}
	
	.f	{
	float:left;
	width:22px;
	margin-left: 2px;
	margin-right: 2px;
	margin-top: 20px;
	height: 0px;
	background: #000;
	-webkit-animation-play-state: paused;
	-moz-animation-play-state: paused;
	}
	
	.f:hover	{
	background: transparent;
	}
	
	#f1, #f0{-webkit-animation: fr2 1s 3s infinite forwards alternate;-moz-animation: fr2 1s 3s infinite forwards alternate;}
	#f2, #f9{-webkit-animation: fr2 0.9s 3s infinite forwards alternate;-moz-animation: fr2 0.9s 3s infinite forwards alternate;}
	#f3, #f8{-webkit-animation: fr 0.8s 3s infinite forwards alternate;-moz-animation: fr2 0.8s 3s infinite forwards alternate;}
	#f4, #f7{-webkit-animation: fr 0.7s 3s infinite forwards alternate;-moz-animation: fr2 0.7s 3s infinite forwards alternate;}
	#f5, #f6{-webkit-animation: fr 0.6s 3s infinite forwards alternate;-moz-animation: fr2 0.6s 3s infinite forwards alternate;}
	
	@-webkit-keyframes fr
	{
		0%		{margin-top: 10px;height: 10px;}
		50%		{margin-top: 5px;height: 15px;}
		100%	{margin-top: 15px;height: 5px;}
	}
	
	@-webkit-keyframes fr2
	{
		0%		{margin-top: 10px;height: 10px;}
		50%		{margin-top: 20px;height: 0px;}
		100%	{margin-top: 15px;height: 5px;}
	}
	
	@-moz-keyframes fr
	{
		0%		{margin-top: 10px;height: 10px;}
		50%		{margin-top: 5px;height: 15px;}
		100%	{margin-top: 15px;height: 5px;}
	}
	
	@-moz-keyframes fr2
	{
		0%		{margin-top: 10px;height: 10px;}
		50%		{margin-top: 20px;height: 0px;}
		100%	{margin-top: 15px;height: 5px;}
	}
	
	
	#play	{
	margin-left: -10px;
	margin-top: 25px;
	width: 280px;
	height: 10px;
	overflow: hidden;
	-webkit-transition: margin-top 0.5s, height 0.5s;
	}
	
	#play:hover	{
	margin-top: -10px;
	height: 45px;
	}
	
	#playtop	{
	height: 9px;
	border-bottom: 1px #000 solid;
	background: gradient(linear,left bottom,left top,from(rgba(0, 0, 0, .9)),to(transparent));
	background: -webkit-gradient(linear,left bottom,left top,from(rgba(0, 0, 0, .9)),to(transparent));
	background-image: -moz-radial-gradient(top,ellipse farthest-side,rgba(0, 0, 0, .8),transparent);
	-webkit-mask-box-image: -webkit-gradient(linear,left top,right top,color-stop(0.0,rgba(0,0,0,0)),color-stop(0.5,rgba(0,0,0,.8)),color-stop(1.0,rgba(0,0,0,0)));
	}
	
	#ytbox	{
	height: 35px;
	overflow: hidden;
	}
	
	#ytplayer	{
	margin-top: -165px;
	}
	
	#back	{
	position: fixed;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	z-index: -10;
	background-color: #bbb;
	background-image: -webkit-gradient(linear,left top,left bottom,from(#eee),to(#bbb));
	background-image: -moz-linear-gradient(top,#eee,#bbb);
	background-image: -o-linear-gradient(top,#eee,#bbb);
	background-image: -ms-linear-gradient(top,#eee,#bbb);
	background-image: linear-gradient(top,#eee,#bbb);
	}
	
	.backimg, .box, .line, #cover	{
	-moz-animation: backshow 1s;
	-webkit-animation: backshow 1s;
	}
	
	@-webkit-keyframes backshow
	{
	from	{opacity:0}
	to		{opacity:1}
	}
	
	@-moz-keyframes backshow
	{
	from	{opacity:0}
	to		{opacity:1}
	}
	</style>
	<script type="text/javascript">
	
	var next = {title:'',artist:'',album:'',coverimg:'',ytid:'',state:'none'};
	
	function css(selector, property, value) {
		for (var i=0; i<document.styleSheets.length;i++) {//Loop through all styles
			//Try add rule
			try { document.styleSheets[i].insertRule(selector+ ' {'+property+':'+value+'}', document.styleSheets[i].cssRules.length);
			} catch(err) {try { document.styleSheets[i].addRule(selector, property+':'+value);} catch(err) {}}//IE
		}
	}
	
	function stripslashes(str) {
		str=str.replace(/\\'/g,'\'');
		str=str.replace(/\\"/g,'"');
		str=str.replace(/\\0/g,'\0');
		str=str.replace(/\\\\/g,'\\');
		return str;
	}
	
	function loadnext() {
		next.state='running';
		if (window.XMLHttpRequest)
                {// code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp=new XMLHttpRequest();
                }
                else
                {// code for IE6, IE5
                    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                }
            xmlhttp.open("GET","http://music.wayshine.us/api.php",false);
            xmlhttp.send();
            xmlDoc=xmlhttp.responseXML;
			try{
		next.title=decodeURIComponent(xmlDoc.getElementsByTagName('title')[0].childNodes[0].nodeValue);
		next.artist=decodeURIComponent(xmlDoc.getElementsByTagName('artist')[0].childNodes[0].nodeValue);
		next.album=decodeURIComponent(xmlDoc.getElementsByTagName('album')[0].childNodes[0].nodeValue);
		next.coverimg=decodeURIComponent(xmlDoc.getElementsByTagName('coverimg')[0].childNodes[0].nodeValue);
		next.ytid=xmlDoc.getElementsByTagName('ytid')[0].childNodes[0].nodeValue;
		next.state='done';
		return true;
		} catch(err){loadnext();}
	}
	
	var timerID;
	var titletext;
	function rolltitle(){
		timerID = window.setInterval("newtext()", 500); //定时器 间隔500ms 调用一次 newtext()
	}
	function stoprolltitle(){
		window.clearInterval(timerID);
	}
	function newtext(){
		text = titletext;
		titletext=text.substring(1,text.length)+text.substring(0,1)
		top.document.title=titletext;
	}
	var pline=[0,0,0];
	var pleft=[0,0,0];
	function rollline(){
		for(var i=0;i<3;i++){
			var element=document.getElementsByClassName('line')[i];
			var elewidth=element.childNodes[1].offsetWidth+element.childNodes[2].offsetWidth;
			if(elewidth>260){
				if(pline[i]==0)pline[i]=-1;
				else{
					pleft[i]=pleft[i]+pline[i];
					if(260-elewidth>=pleft[i] || pleft[i]>=0)pline[i]=0-pline[i];
				}
				element.style.marginLeft=pleft[i]+'px';
			}
			else{
				pleft[i]=0;
				pline[i]=0;
				element.style.marginLeft=pleft[i]+'px';
			}
		}
	}
	var timerID2;
	timerID2 = window.setInterval("rollline()", 200);
	
	</script>
</head>
<body itemscope itemtype="http://schema.org/Review">
<div id="back"></div>
	<script type="text/javascript">
	var img = new Image();
	img.onload = function() {
	img.style.display = "block";
	}
	document.getElementById('back').appendChild(img);
	img.src = "http://music.wayshine.us/backimg.php?url=<?php echo $coverimg ?>";
	img.style.width = "100%";
	img.style.height = "100%";
	img.style.display = "none";
	img.id = "backimg";
	</script>
	<div id="musicinfo">
		<div id="coverbox">
		<div id="nextbutton">Next</div>
		<img id="cover" class="box" src="<?php echo $coverimg ?>"/>
		</div>
		<div id="detail" class="box">
		<div id="textarea">
		<div class="line" id="head">
			<span><?php echo urldecode("%E2%98%8A%20") ?></span><span id="title"><?php echo stripslashes($title) ?></span>
		</div>
		<div class="line">
			<span class="t">Artist: </span><span id="artist"><?php echo stripslashes($artist) ?></span>
		</div>
		<div class="line">
			<span class="t">Album: </span><span id="album"><?php echo stripslashes($album) ?></span>
		</div>
		<div class="line" id="load">
			Loading Music ...
		</div>
		</div>
		<div id="frequent" style="display:none">
		<div class="f" id="f1"></div>
		<div class="f" id="f2"></div>
		<div class="f" id="f3"></div>
		<div class="f" id="f4"></div>
		<div class="f" id="f5"></div>
		<div class="f" id="f6"></div>
		<div class="f" id="f7"></div>
		<div class="f" id="f8"></div>
		<div class="f" id="f9"></div>
		<div class="f" id="f0"></div>
		</div>
		<div id="play" data-href="<?php echo $ytsearch ?>">
		<div id="playtop"></div>
		<div id="ytbox">
    <iframe id="ytplayer" type="text/html" width="280" height="200" src="<?php echo $yturl ?>" frameborder="0"></iframe>
	<script src="https://www.youtube.com/iframe_api" type="text/javascript"></script>
    <script>
	  
	  loadnext();
	  titletext=document.title;
	  
      var player;
      function onYouTubeIframeAPIReady() {
        player = new YT.Player('ytplayer', {
          events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange,
			'onError': onPlayerError
          }
        });
      }

	  function onPlayerError(event)	{
		nextmusic()
	  }
	  
      function onPlayerReady(event) {
        event.target.playVideo();
      }

      var done = false;
      function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.PLAYING && !done) {
			css('.f','-webkit-animation-play-state','running');
			css('.f','-moz-animation-play-state','running');
			rolltitle();
			document.getElementById('load').style.display="none";
			document.getElementById('frequent').style.display="block";
			done = true;
        }
		else if (event.data == YT.PlayerState.PLAYING) {
			css('.f','-webkit-animation-play-state','running');
			css('.f','-moz-animation-play-state','running');
			rolltitle();
        }
		else if (event.data == YT.PlayerState.PAUSED) {
			css('.f','-webkit-animation-play-state','paused');
			css('.f','-moz-animation-play-state','paused');
			stoprolltitle();
		}
		else if (event.data == YT.PlayerState.ENDED) {
			nextmusic();
		}
      }
      function stopVideo() {
        player.stopVideo();
      }
	  function nextmusic() {
		if(next.state=='none'){loadnext();setTimeout(nextmusic, 1000)}
		else if(next.state=='running')setTimeout(nextmusic, 1000);
		else if(next.state=='done'){
			css('.f','-webkit-animation-play-state','paused');
			css('.f','-moz-animation-play-state','paused');
			img.style.display="none";
			img.src = "http://music.wayshine.us/backimg.php?url="+next.coverimg;
			document.title=decodeURIComponent("%E2%98%8A")+" NowListening - "+stripslashes(next.title);
			titletext=document.title;
			document.getElementById('cover').style.display="none";
			document.getElementById('title').style.display="none";
			document.getElementById('artist').style.display="none";
			document.getElementById('album').style.display="none";
			document.getElementById('cover').src=next.coverimg;
			document.getElementById('title').innerHTML=stripslashes(next.title);
			document.getElementById('artist').innerHTML=stripslashes(next.artist);
			document.getElementById('album').innerHTML=stripslashes(next.album);
			document.getElementById('cover').style.display="block";
			document.getElementById('title').style.display="inline";
			document.getElementById('artist').style.display="inline";
			document.getElementById('album').style.display="inline";
			player.loadVideoById(next.ytid, 0, "small");
			document.getElementById('load').style.display="block";
			document.getElementById('frequent').style.display="none";
			done = false;
			loadnext();
			pline=[0,0,0];
			pleft=[0,0,0];
		}
	  }
	  document.getElementById('nextbutton').onclick=function(){nextmusic()};
    </script>
		</div>
		</div>
		</div>
	</div>
</body>
</html>