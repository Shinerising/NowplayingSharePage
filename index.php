<?php
//	My Music Index Page
//	See the DEMO here: http://music.wayshine.us
//	Show the recently played musics with the Album Walls Style
//	This is a single page that used no javascript and all animation are played with CSS3

    header("Content-Type:text/html; charset=utf-8");
	
	date_default_timezone_set('Asia/Shanghai');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html itemtype="http://schema.org/Review" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="description" content="Music is our religion."/>
	<title>Music Collection</title>
	<style>
	html	{
	background: #eee;
	}
	
	body	{
	margin: 0;
	}
	
	#a	{
	overflow: visible;
	width: 100%;
	}
	
	a	{
	text-decoration: none;
	}
	
	#head	{
	height: 50px;
	width: 600px;
	margin: 0 auto;
	margin-top: 50px;
	line-height: 50px;
	text-align: center;
	font-size: 40px;
	font-family: Georgia, serif;
	font-style: italic;
	color: #eee;
	text-shadow: 0px 0px 10px #000;
	}
	
	#area	{
	width: 1000px;
	height: 810px;
	margin: 0 auto;
	margin-top: 50px;
	margin-bottom: 10px;
	}
	
	.covers	{
	width: 200px;
	height: 200px;
	float: left;
	position: relative;
	z-index: 0;
	}
	
	.covers:hover	{
	z-index: 100;
	}
	
	.coverimg	{
	width: 100%;
	height: 100%;
	margin-left: 0px;
	margin-top: 0px;
	z-index: -1;
	box-shadow: 0px 0px 1px #000;
	-webkit-animation: imgshow 5s;
	-moz-animation: imgshow 5s;
	}
	
	@-webkit-keyframes imgshow	{
	0%		{opacity:0;}
	100%	{opacity:1;}
	}
	
	@-moz-keyframes imgshow	{
	0%		{opacity:0;}
	100%	{opacity:1;}
	}
	
	.covera	{
	position: relative;
	width: 198px;
	height: 198px;
	margin-left: 1px;
	margin-top: 1px;
	background: #ddd;
	-webkit-transition: width 0.2s, height 0.2s, left 0.2s, top 0.2s;
	-moz-transition: width 0.2s, height 0.2s, left 0.2s, top 0.2s;
	-ms-transition: width 0.2s, height 0.2s, left 0.2s, top 0.2s;
	}
	
	.covera:hover	{
	width: 240px;
	height: 240px;
	left: -21px;
	top: -21px;
	box-shadow: 0px 0px 10px #000;
	}
	
	.label1	{
	position: absolute;
	width: 100%;
	height: 30px;
	overflow: hidden;
	margin-top: -52px;
	font-size: 12px;
	line-height: 40px;
	text-align: center;
	color: #fff;
	background-image: -webkit-gradient(linear,left top,left bottom,from(transparent),to(rgba(0,0,0,.7)));
	background-image: -moz-linear-gradient(top,transparent,rgba(0,0,0,.7));
	background-image: -o-linear-gradient(top,transparent,rgba(0,0,0,.7));
	background-image: -ms-linear-gradient(top,transparent,rgba(0,0,0,.7));
	background-image: linear-gradient(top,transparent,rgba(0,0,0,.7));
	}
	
	.label2	{
	position: absolute;
	width: 100%;
	height: 19px;
	overflow: hidden;
	margin-top: -22px;
	font-size: 12px;
	line-height: 19px;
	text-align: center;
	color: #fff;
	background-image: -webkit-gradient(linear,left top,left bottom,from(rgba(0,0,0,.7)),to(rgba(0,0,0,1)));
	background-image: -moz-linear-gradient(top,rgba(0,0,0,.7),rgba(0,0,0,1));
	background-image: -o-linear-gradient(top,rgba(0,0,0,.7),rgba(0,0,0,1));
	background-image: -ms-linear-gradient(top,rgba(0,0,0,.7),rgba(0,0,0,1));
	background-image: linear-gradient(top,rgba(0,0,0,.7),rgba(0,0,0,1));
	}
	
	#foot	{
	width: 100%;
	height: 40px;
	position: relative;
	margin:0 auto;
	margin-top: -40px;
	margin-bottom: 20px;
	border-bottom: solid 1px #AAAAAA;
	background: -webkit-gradient(linear,left bottom,left top,from(rgba(0, 0, 0, .9)),to(transparent));
	background-image: -moz-radial-gradient(bottom,ellipse farthest-side,rgba(0, 0, 0, .9),transparent);
	-webkit-mask-box-image: -webkit-gradient(linear,left bottom,right bottom,color-stop(0.0,rgba(0,0,0,0)),color-stop(0.5,rgba(0,0,0,.8)),color-stop(1.0,rgba(0,0,0,0)));
	z-index:1000;
	display: none;
	}
	
	#foot2	{
	height: 20px;
	width: 1200px;
	position: relative;
	margin: 0 auto;
	margin-top: -21px;
	background: #eee;
	z-index:1000;
	display: none;
	}
	
	@-webkit-keyframes open	{
	0%		{width:220px;height:220px;top:-20px;left:-11px;opacity:0;}
	100%	{width:198px;height:198px;top:0px;left:0px;opacity:1;}
	}
	
	@-moz-keyframes open	{
	0%		{width:220px;height:220px;top:-20px;left:-11px;opacity:0;}
	100%	{width:198px;height:198px;top:0px;left:0px;opacity:1;}
	}
	
	@-ms-keyframes open	{
	0%		{width:220px;height:220px;top:-20px;left:-11px;opacity:0;}
	100%	{width:198px;height:198px;top:0px;left:0px;opacity:1;}
	}
	
	#back	{
	position: fixed;
	width: 100%;
	height: 100%;
	left: 0px;
	top: 0px;
	z-index: -10;
	}
	
	.item	{
	height: 16px;
	width: 16px;
	top: -20px;
	left: 0px;
	position: fixed;
	color: rgba(155,155,155,.7);
	font-size: 16px;
	font-family: Arial;
	text-shadow: 0px 0px 5px #fff;
	}
	
	@-webkit-keyframes fall	{
	0%		{left:-10%;}
	100%	{left:110%;}
	}
	
	@-webkit-keyframes snow	{
	0%		{top:-50px}
	100%	{top:50px}
	}
	
	@-moz-keyframes fall	{
	0%		{left:-10%;}
	100%	{left:110%;}
	}
	
	@-moz-keyframes snow	{
	0%		{top:-50px}
	100%	{top:50px}
	}
	
	@-ms-keyframes fall	{
	0%		{left:-10%;}
	100%	{left:110%;}
	}
	
	@-ms-keyframes snow	{
	0%		{top:-50px}
	100%	{top:50px}
	}
	
</style>
</head>
<body>
<div id="a">
	<div id="head">Music is our religion.</div>
	<div id="area">
		<div>
<?php
	//get history from database
	$db = sqlite_open("music.db");
	$query = sqlite_query($db, "select * from data order by id desc limit 20");
	while($res = sqlite_fetch_array($query)){
	$coverimg=urldecode($res['name']);
	if($coverimg=='')$coverimg='nocover.png';
	$resurl = 'http://music.wayshine.us/nowplaying.php'.'?album='.urlencode(stripslashes(urldecode($res['album']))).'&title='.urlencode(stripslashes(urldecode($res['title']))).'&artist='.urlencode(stripslashes(urldecode($res['artist']))).'&c=0&s=3';
	$text = stripslashes(urldecode($res['artist'])) . ' - ' . stripslashes(urldecode($res['title']));
	$datetime_times=strtotime($res['time']);
	$now_times=time();
	$times=$now_times-$datetime_times;
	if($times>172800)$timetext='more than one day ago';
	elseif($times>86400)$timetext='1 day ago';
	elseif($times>7200)$timetext=(string)round($times/3600) . ' hours ago';
	elseif($times>3600)$timetext='1 hour ago';
	elseif($times>120)$timetext=(string)round($times/60) . ' minutes ago';
	elseif($times>60)$timetext='1 minute ago';
	else $timetext=(string)$times . ' seconds ago';
	$anisec=rand(1,20)/20+1;
	echo "<div class='covers'><a target='_blank' href='" . $resurl . "'><div class='covera' style='-webkit-animation:open 0.5s " . $anisec . "s backwards;-moz-animation:open 0.5s " . $anisec . "s backwards;-ms-animation:open 0.5s " . $anisec . "s backwards;' ><img class='coverimg' src='$coverimg' /><div class='label1'>$text</div><div class='label2'>$timetext</div></div></a></div>";
	}
	sqlite_close($db);
?>
		</div>
	</div>
	<div id='foot'></div>
	<div id='foot2'></div>
</div>
<div id="effect">
<?php
$note = array("%E2%99%A9", "%E2%99%AA", "%E2%99%AB", "%E2%99%AC");
for($i=0;$i<50;$i++)
{
$top=rand(1,100);
$s0=rand(1,40)/5;
$s1=rand(1,40)/5+18;
$s2=rand(1,20)/5+6;
echo "<div class='item' style='margin-left:-16px;margin-top:".$top."%;-webkit-animation: fall ease-in ".$s1."s ".$s0."s infinite, snow ".$s2."s both infinite alternate;-moz-animation: fall ease-in ".$s1."s ".$s0."s infinite, snow ".$s2."s both infinite alternate;-ms-animation: fall ease-in ".$s1."s ".$s0."s infinite, snow ".$s2."s both infinite alternate;'>".urldecode($note[rand(1,4)-1])."</div>";
}
?>
</div>
<img id="back" src="back.jpg" />
</body>
</html>