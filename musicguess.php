<?php
//	Music Guess page
//	A little music album guess game
//	NowplayingSharePage database needed

    header("Content-Type:text/html; charset=utf-8");
	
	date_default_timezone_set('Asia/Shanghai');
	
	$p = $_GET["p"];
	
	//open database
	if(!file_exists("guessme.db")){
	$db = sqlite_open("guessme.db"); 
	$sql = "create table data(id INTEGER PRIMARY KEY,sname text,answer text,time datatime,result text)";
	$query= sqlite_query($db, $sql);
	}
	else $db = sqlite_open("guessme.db"); 
	
	if($p==null or $p=="" or $p=="new"){
	//build new game
		$gdb = sqlite_open("music.db");
		for($i=0;$i<30;$i++){
			$query = sqlite_query($gdb, "select * from data order by random() limit 1");
			$res = sqlite_fetch_array($query);
			if($i<10){
				$title[$i] = stripslashes(urldecode($res['title']));
				$artist[$i] = stripslashes(urldecode($res['artist']));
				$album[$i] = stripslashes(urldecode($res['album']));
				$coverimg[$i]=urldecode($res['name']);
				for($j=0;$j<$i;$j++){
					if($coverimg[$j]==$coverimg[$i] or $title[$j]==$title[$i] or $artist[$j]==$artist[$i] or $album[$j]==$album[$i]){
						$j=$i;
						$i--;
					}
				}
			}
			else{
				$coverimg[$i]=urldecode($res['name']);
				for($j=0;$j<$i;$j++){
					if($coverimg[$j]==$coverimg[$i]){
						$j=$i;
						$i--;
					}
				}
			}
			if($coverimg[$i]==null or $coverimg[$i]=="" or $coverimg[$i]=="http://img3.douban.com/pics/music/default_cover/lpic/music-default.gif")$i--;
		}
		sqlite_close($gdb);
		$a[0]=2;
		$a[1]=3;
		$a[2]=5;
		$answer='';
		for($i=0;$i<10;$i++){
			do{$j=rand(0,2);}while($a[$j]==0);
			$a[$j]--;
			$ac[$i]=$j;
			$as[$j]=rand(0,2);
			if($as[$j]==0){
			$img1[$i]=$coverimg[$i];
			$img2[$i]=$coverimg[$i+10];
			$img3[$i]=$coverimg[$i+20];
			}
			else if($as[$j]==1){
			$img2[$i]=$coverimg[$i];
			$img1[$i]=$coverimg[$i+10];
			$img3[$i]=$coverimg[$i+20];
			}
			else{
			$img3[$i]=$coverimg[$i];
			$img1[$i]=$coverimg[$i+10];
			$img2[$i]=$coverimg[$i+20];
			}
			$answer=$answer.$as[$j];
		}
		$result = "no";
		$sname = "^o^";
		$time = date("y-m-d H:i:s");
		sqlite_query($db, "insert into data (sname,answer,time,result) values('$sname','$answer','$time','$result')");
		$id=sqlite_last_insert_rowid($db);
		sqlite_close($db);
		$answer='';
	}
	else if($p=='start'){
	//start game
		$id = $_GET["id"];
		$sname = urlencode($_GET["name"]);
		$time = date("y-m-d H:i:s");
		$r=sqlite_query($db, "update data set sname = '$sname', time = '$time' where id=$id");
		exit($r);
		
	}
	else if($p=='check'){
	//check the user's answer
		$id = $_GET["id"];
		$ans = $_GET["answer"];
	
		$query = sqlite_query($db, "select * from data where id=$id");
		$res = sqlite_fetch_array($query);
		$answer = $res['answer'];
		$sname = urldecode($res['sname']);
		
		$time = strtotime($res['time']);
		$nowtime = time();
		$tim=$nowtime - $time;
		
		$rest=0;
		for($i=0;$i<10;$i++)
		{
			if(substr($ans,$i,1)==substr($answer,$i,1))$rest++;
		}
		$result = $rest;
		$star="";
		$words="";
		for($i=0;$i<$rest;$i++)$star=$star."★";
		if($rest<4)$words="You are really unlucky~~";
		else if($rest<6)$words="Not bad, but you could be better!";
		else if($rest<8)$words="Nice work! You can go higher!";
		else if($rest<10)$words="Very good! Almost Perfect!";
		else if($rest==10)$words="Perfect job! Brilliant!";
		$des=$sname." got ".$result." points in the game!";
		if($tim>3600)$timewords="more than 1 hour";
		else if($tim>60)$timewords=(string)round($tim/60)."min".($tim%60)."s";
		else $timewords=$tim."s";
		$timewords="Used time: ".$timewords;
		$r=sqlite_query($db, "update data set time = $tim, result = $result where id=$id");
		sqlite_close($db);
	}
	else if($p=='result'){
	//output the result for sharing
		$id = $_GET["id"];
	
		$query = sqlite_query($db, "select * from data where id=$id");
		$res = sqlite_fetch_array($query);
		$result = $res['result'];
		$sname = urldecode($res['sname']);
		$tim = $res['time'];
		$star="";
		$words="";
		$rest = intval($result);
		for($i=0;$i<$rest;$i++)$star=$star."★";
		if($rest<4)$words="You are really unlucky~~";
		else if($rest<6)$words="Not bad, but you could be better!";
		else if($rest<8)$words="Nice work! You can go higher!";
		else if($rest<10)$words="Very good! Almost Perfect!";
		else if($rest==10)$words="Perfect job! Brilliant!";
		if($tim>3600)$timewords="more than 1 hour";
		else if($tim>60)$timewords=(string)round($tim/60)."min".($tim%60)."s";
		else $timewords=$tim."s";
		$timewords="Used time: ".$timewords;
		$des=$sname." got ".$result." points in the game!";
		sqlite_close($db);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html itemtype="http://schema.org/Review" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Music Guessing</title>
	<meta property="og:title" content="Music Guessing Result: <?php echo $star ?>" />
	<meta property="og:image" content="http://i840.photobucket.com/albums/zz321/Zhaoyang_Wayne/Something/<?php echo $rest?>.jpg" />
	<meta property="og:description" content="<?php echo $des." - ".$timewords.'['.$words.']' ?>" />
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
	<script type="text/javascript" src="https://apis.google.com/js/plusone.js">{parsetags: 'explicit'}</script>
	<script type="text/javascript">
	
	$(document).ready(function() {
		gapi.plus.go();
	});
	
	var slct = new Array(-1,-1,-1,-1,-1,-1,-1,-1,-1,-1);
	var finish = 'no';
	
	function imgclk(ele,n,pid){
	if(finish=='no'){
		if(slct[pid]!=-1)
		{
			document.getElementsByClassName('imgarea')[pid].getElementsByClassName('imgactive')[0].className='imgbox';
		}
		if(pid<10)
		{
			document.getElementsByClassName('box')[pid].className='box b2';
			document.getElementsByClassName('box')[pid+1].className='box b1';
		}
		slct[pid]=n;
		ele.className='imgactive';
	}
	}
	
	function guessstart(){
		var name=$('#nameinput').val();
		if(name==null || name==''){
			$('#namebox').animate({marginTop:'-=10',height:'+=10'},100);
			$('#namebox').animate({marginTop:'+=10',height:'-=10'},100);
			$('#namebox').animate({marginTop:'-=10',height:'+=10'},100);
			$('#namebox').animate({marginTop:'+=10',height:'-=10'},100);
		}
		else{
			var surl='musicguess.php?p=start&name='+name+'&id=<?php echo $id ?>';
			$.get(surl,function(){
				document.getElementById('welcome').style.display="none";
				document.getElementById('guessbox').style.display="block";
			});
		}
	}
	
	function check(){
		document.getElementById('finishtext').innerHTML='Please wait a moment~~~';
		var answer='';
		for(var i=0;i<10;i++){
			answer=answer+slct[i];
		}
		var surl='musicguess.php?p=check&answer='+answer+'&id='+<?php echo $id ?>+' #result';
		$('#resultbox').load(surl,function(){
			document.getElementById('submit').style.display="none";
			var result=document.getElementById('cresult').innerHTML;
			document.getElementById('resultbox').style.display="block";
			gapi.plus.go();
			finish = 'yes';
			answer=document.getElementById('canswer').innerHTML;
			for(var i=0;i<10;i++){
				document.getElementsByClassName('box')[i].className='box b0';
				document.getElementsByClassName('imgarea')[i].getElementsByTagName('div')[slct[i]].className='imgwrong';
				document.getElementsByClassName('imgarea')[i].getElementsByTagName('div')[Number(answer.substr(i,1))].className='imgright';
			}
		});
	}
	
	function newgame(){
		document.location="<?php echo "http://".$_SERVER['HTTP_HOST']."/musicguess.php" ?>"
	}
	
	</script>
	<style  type="text/css">
	
	html{
	background: #eee;
	}
	
	body{
	margin: 0;
	}

	#a{
	overflow: visible;
	width: 100%;
	}
	
	a{
	text-decoration: none;
	}
	
	#title{
	height: 50px;
	width: 640px;
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
	
	#area{
	width: 50%;
	min-width: 640px;
	margin: 0 auto;
	}
	
	#welcome{
	width: 500px;
	height: 300px;
	margin: 0 auto;
	margin-top: 40px;
	background: rgba(200,200,200,0.9);
	border: #eee 10px solid;
	border-radius: 20px;
	overflow: hidden;
	background-image: url(http://i840.photobucket.com/albums/zz321/Zhaoyang_Wayne/Something/welcome.jpg);
	}
	
	.boxtext, .boxtext2{
	width: 400px;
	height: 40px;
	margin: 0 auto;
	text-align: center;
	font-size: 20px;
	line-height: 40px;
	font-family: Georgia, serif;
	text-shadow: 0px 0px 5px #aaa;
	}
	
	.boxtext2{
	font-size: 16px;
	}
	
	#nameinput{
	height: 16px;
	width: 120px;
	border-radius: 5px;
	border: 1px solid rgba(100,100,100,.5);
	padding: 4px;
	font-family: Georgia, serif;
	}
	
	#guessbox{
	width: 640px;
	margin: 0 auto;
	margin-top: 40px;
	-webkit-perspective: 2000;
	-webkit-animation: boxshow 0.5s;
	}
	
	@-webkit-keyframes boxshow{
	from	{opacity:0}
	to		{opacity:1}
	}
	
	#resultbox{
	width: 640px;
	height: 300px;
	background: rgba(200,200,200,0.9);
	background-image: url(http://i840.photobucket.com/albums/zz321/Zhaoyang_Wayne/finish.jpg);
	border: #eee 10px solid;
	border-radius: 20px;
	margin: 0 auto;
	margin-top: 40px;
	overflow: hidden;
	}
	
	.box{
	width: 640px;
	background: rgba(200,200,200,0.9);
	border: #eee 10px solid;
	border-radius: 20px;
	overflow: hidden;
	}
	
	.b0{
	margin-top: 40px;
	height: 300px;
	opacity: 1;
	}
	
	.b1{
	-webkit-animation: show 1s both;
	margin-top: 40px;
	height: 300px;
	opacity: 1;
	}
	
	.b2{
	-webkit-animation: hide 1s backwards;
	margin-top: -20px;
	height: 0px;
	opacity: 0;
	}
	
	.b3{
	display: none;
	}
	
	@-webkit-keyframes show{
	0%		{-webkit-transform: rotatex(90deg);height:0px;margin-top:-20px;opacity:1;}
	100%	{-webkit-transform: rotatex(0deg);height:300px;margin-top:40px;opacity:1;}
	}
	
	@-webkit-keyframes hide{
	0%		{-webkit-transform: rotatex(0deg);height:300px;margin-top:40px;opacity:1;}
	99%		{-webkit-transform: rotatex(-90deg);height:0px;margin-top:-20px;opacity:1;}
	100%	{opacity:0;}
	}
	
	.guesstitle1, .guesstitle2{
	font-size: 16px;
	font-family: Georgia, serif;
	text-align: center;
	width: 500px;
	margin: 0 auto;
	line-height: 20px;
	}
	
	.guesstitle1{
	height: 20px;
	margin-top: 20px;
	}
	
	.guesstitle2{
	height: 40px;
	overflow: hidden;
	}
	
	.bigtext{
	text-align: center;
	font-size: 32px;
	font-family: Georgia, serif;
	color: #222;
	text-shadow: 0px 0px 10px #fff;
	}
	
	.stext{
	font-size: 18px;
	font-weight: bold;
	}
	
	.imgarea{
	margin: 0 auto;
	height: 200px;
	width: 600px;
	}
	
	.imgbox{
	float: left;
	margin: 10px;
	border: #eee 10px solid;
	cursor: pointer;
	}
	
	.imgbox:hover{
	box-shadow: 0px 0px 10px #000;
	}
	
	.imgactive{
	float: left;
	margin: 10px;
	cursor: pointer;
	border: #222 10px solid;
	}
	
	.imgwrong{
	float: left;
	margin: 10px;
	cursor: pointer;
	border: #f44 10px solid;
	}
	
	.imgright{
	float: left;
	margin: 10px;
	cursor: pointer;
	border: #4f4 10px solid;
	}
	
	.gimg{
	width: 160px;
	height: 160px;
	}
	
	#submit{
	background-image:url(http://i840.photobucket.com/albums/zz321/Zhaoyang_Wayne/Something/submit.png);
	}
	
	.button{
	width: 120px;
	height: 50px;
	text-align: center;
	line-height: 50px;
	font-size: 22px;
	font-weight: bold;
	font-family: Verdana, Arial;
	margin: 0 auto;
	background: rgba(240,250,240,0.8);
	border-radius: 10px;
	box-shadow: inset 0px -5px 10px #fff, 0px 0px 5px #000;
	cursor: pointer;
	}
	
	.button:hover{
	box-shadow: inset 0px 0px 20px #777;
	}
	
	#back{
	position: fixed;
	width: 100%;
	height: 100%;
	left: 0px;
	top: 0px;
	z-index: -10;
	}
	
	</style>
</head>
<body>
	<div id="a">
		<div id="title">
		Music Guessing! <?php echo $r ?>
		</div>
		<div id="area">
<?php
	if($p=='succeed2013'){
		echo "<div class='box'><div class='bigtext'>Check your Gift:</div><div class='bigtext'>$10 Google Play Gift Card!</div>";
		echo "<div class='boxtext'>5DGB KHSD CHY8 HRN5 JQSX</div>";
		echo "</div>";
	}
?>
			<div id="welcome" <?php if(($p=='result')||($p=='succeed2013'))echo "style='display:none'"?>>
			<div class="boxtext">Love music albums? Now try to guess!</div>
			<div class="boxtext2" id="namebox">Type your name here: <input type="text" name="name" id="nameinput"></input></div>
			<div class="boxtext"></div>
			<div class="boxtext"></div>
			<div class="button" onclick="guessstart()">Start!</div>
			</div>
			<div id="resultbox" <?php if($p!='result')echo "style='display:none'"?>>
			<div id="result">
			<div id="checkresult" style="display:none">
			<div id="canswer"><?php echo $answer ?></div>
			<div id="ctime"><?php echo $time ?></div>
			<div id="cresult"><?php echo $result ?></div>
			</div>
<?php
	if($p=='check' or $p=='result'){
		$surl='http://'.$_SERVER['HTTP_HOST'].'/musicguess.php?p=result&id='.$id;
		echo "<div class='bigtext'>".$star."</div>";
		echo "<div class='bigtext'>".$des."</div>";
		echo "<div class='boxtext'>".$words."</div>";
		echo "<div class='boxtext'>".$timewords."</div>";
		echo "<div class='boxtext' data-href=$surl><div class='g-plus' data-action='share' data-width='180' data-href=$surl></div></div>";
		echo "<div class='button' onclick='newgame()'>Replay!</div>";
	}
?>
			</div>
			</div>
			<div id="guessbox" style="display:none">
<?php

	for($i=0;$i<10;$i++){
		$j=$i+1;
		if($i==0)echo "<div class='box'>";
		else echo "<div class='box b3'>";
		echo "<div class='guesstitle1' >Q $j . Which Album</div>";
		if($ac[$i]==0)echo "<div class='guesstitle2' >is <span class='stext'>".$album[$i]."</span> ?</div>";
		else if($ac[$i]==1)echo "<div class='guesstitle2' >is played by <span class='stext'>".$artist[$i]."</span> ?</div>";
		else if($ac[$i]==2)echo "<div class='guesstitle2' >includes the song <span class='stext'>".$title[$i]."</span> ?</div>";
		echo "<div class='imgarea'>";
		echo "<div class='imgbox' onclick='imgclk(this,0,$i)'><img class='gimg' src='".$img1[$i]."' /></div>";
		echo "<div class='imgbox' onclick='imgclk(this,1,$i)'><img class='gimg' src='".$img2[$i]."' /></div>";
		echo "<div class='imgbox' onclick='imgclk(this,2,$i)'><img class='gimg' src='".$img3[$i]."' /></div>";
		echo "</div>";
		echo "</div>";
	}
	echo "<div class='box b3' id='submit'><div class='boxtext' id='finishtext' >You have finished all questions!</div><div class='boxtext'></div><div class='boxtext'></div><div class='button' onclick='check()'>Check!</div></div>";
?>
			</div>
		</div>
	</div>
	<img id="back" src="back.jpg" />
</body>
</html>