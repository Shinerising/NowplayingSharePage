<?php
//Name:				NowPlaying Pages
//Author:			Apollo Wayne
//Description:		Catch and Share the music information and Search the cover images
//Create Date:		2012.11.11
//Last Modified:	2013.3.28

	//set the timezone here
	date_default_timezone_set('Asia/Shanghai');
	
    header("Content-Type:text/html; charset=utf-8");
	
	$album = $_GET["album"];
    $artist = $_GET["artist"];
    $title = $_GET["title"];
	$length = $_GET["length"];
	$date = $_GET["date"];
	$c = $_GET["c"];
	$s = $_GET["s"];
	$from = $_GET["from"];
	
	$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];
	
	//create new database
	if(!file_exists("music.db")){
	$db = sqlite_open("music.db"); 
	$sql = "create table data(id INTEGER PRIMARY KEY,title text,artist text,album text,name text,time datatime)";
	$query= sqlite_query($db, $sql);
	}
	
	
	//catch cover images
	//use 3 API to get better result
	if($album!='')$searchwords=urlencode($album . ' ' . $artist);
	else $searchwords=urlencode($title);
	
	if($c==0){
	//API of Last.fm
	$searchurl = 'http://ws.audioscrobbler.com/2.0/?method=album.search&limit=1&api_key=e53ce37f691d7758b8320864c99b15ec&album=' . $searchwords;
	
	$doc = new DOMDocument();
	$doc->load($searchurl);
	
	$covers=$doc->getElementsByTagName( "image" );
	$coverimg=$covers->item(3)->nodeValue;
	$albumurls=$doc->getElementsByTagName( "url" );
	$albumurl=$albumurls->item(0)->nodeValue;
	
	$titleurl = 'http://www.last.fm/search?q=' . $title."+".$artist;
	$artisturl = 'http://www.last.fm/search?q=' . $artist;
	
	$jsonurl=$searchurl;
	}
	
	if(($coverimg=='' && $c==0) or $c==1){
	$c=1;
	//API of douban.com
	$searchurl = 'http://api.douban.com/music/subjects?start-index=1&max-results=1&q=' . $searchwords;
	
	$doc = new DOMDocument();
	$doc->load($searchurl);
	
	$covers=$doc->getElementsByTagName( "link" );
	$coverimg=$covers->item(2)->getAttribute('href');
	$coverimg=str_replace('/spic/','/lpic/',$coverimg);
	$albumurl=$covers->item(1)->getAttribute('href');
	$titleurl = 'http://music.douban.com/subject_search?search_text=' . $title."+".$artist;
	$artisturl = 'http://music.douban.com/subject_search?search_text=' . $artist;
	$jsonurl=$searchurl;
	
	}
		
	if($coverimg=='http://img3.douban.com/pics/music/default_cover/lpic/music-default.gif' or $c==2){
	$c=2;
	//API of Google Images Search
	$jsonurl = "https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=" . $searchwords;
	$result = json_decode(file_get_contents($jsonurl), true);
	$coverimg = $result['responseData']['results'][0]['url'];
	$albumurl = 'https://www.google.com/?q=' . $album;
	$titleurl = 'https://www.google.com/?q=' . $title."+".$artist;
	$artisturl = 'https://www.google.com/?q=' . $artist;
	}
	
	//Get the music player info
	$source = "O_O";
	if($from == 233)$source = "iTunes for Mac";
	else if($from == 482)$source = "foobar2000";
	else if($from == 288)$source = "iTunes for Windows";
	else if($from == 553)$source = "Google Music";
	else if($from == 623)$source = "Amarok";
	else if($from == 218)$source = "PowerAMP";
	
	//API of YouTube
	//offer music listenning from YouTube
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
	$yturl="https://www.youtube.com/embed/".$yt."?autohide=0&theme=light&color=white";
	if($s==3)$yturl=$yturl."&autoplay=1";
	
	//mobile device detect
	if(strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod') || strstr($_SERVER['HTTP_USER_AGENT'],'Windows Phone') || stripos($_SERVER['HTTP_USER_AGENT'],'Android') !== false){
		$csslink='style_mobile.css';
	}
	else $csslink='style.css';
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html itemtype="http://schema.org/Review" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head profile="http://www.w3.org/2005/10/profile">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title> NowPlaying - <?php echo stripslashes($title) ?></title>
	<link rel="icon" type="image/vnd.microsoft.icon" href="favicon.ico" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="title" property="og:title" content="<?php echo urldecode("%E2%99%AB") ?> NowPlaying - <?php echo stripslashes($title) ?>" />
	<meta name="description" property="og:description" content='<?php echo stripslashes($title) ?> - <?php echo stripslashes($artist) ?> [<?php echo stripslashes($album) ?>]' />
    <link rel="stylesheet" type="text/css" href="<?php echo $csslink ?>" media="screen" />
	<script type="text/javascript">
	var cc = <?php echo $c ?>;
	String.prototype.changeQuery = function(name,value){
    var reg = new RegExp("(^|)"+ name +"=([^&]*)(|$)");
    var tmp = name + "=" + value;
    if(this.match(reg) != null){
        return this.replace(eval(reg),tmp);
    }else{
        if(this.match("[\?]")){
            return this + "&" + tmp;
        }else{
            return this + "?" + tmp;
        }
    }
	}
	function coverchange(){
		if(cc==0)cc=1;
		else if(cc==1)cc=2;
		else if(cc==2)cc=0;
		
		var nurl=document.location.href.toString();
		nurl=nurl.changeQuery('c',cc);
		document.location.href=nurl;
	}
	function footopen(){
		document.getElementById("footer0").style.display="none";
		document.getElementById("footer").style.display="block";
	}
		
	var nurl = document.URL;
	nurl = nurl.changeQuery("s",3);
	var state ={
	title:document.title,
	url:nurl
	};
	history.pushState(state,document.title,nurl);
	
	</script>
</head>
<body itemscope itemtype="http://schema.org/Review">
<div id="back"></div>
	<script type="text/javascript">
	//show the blured background picture
	var img = new Image();
	img.onload = function() {
	img.style.display = "block";
	}
	document.getElementById('back').appendChild(img);
	img.src = "http://music.wayshine.us/backimg.php?url=<?php echo $coverimg ?>";
	img.style.width = "100%";
	img.style.height = "100%";
	img.style.display = "none";
	img.className = "backimg";
	</script>
<div itemprop="name" style="display:none;">
<?php 
	//page name for sharing
//echo urldecode('%E2%98%8A') ?><?php echo urldecode("%E2%99%AA") ?> Nowplaying: <?php
	echo stripslashes($title);
	echo " ";
	for($i=0;$i<30;$i++){
	$rd=rand(0,2);
	if($rd==0)echo ".";
	else if($rd==1)echo "ι";
	else if($rd==2)echo "l";
	}
	//ιllιlι.ιl..ιllιlι.ιl..ιι.ιl..ιllιlι.ι
?>

</div>
<div itemprop="description" id="description" style="display:none;">
	<p><?php //echo urldecode("%E2%9D%A4") ?> [Artist: <?php echo stripslashes($artist) ?>]</p>
	<p><?php //echo urldecode("%E2%9D%A4") ?> [Album: <?php echo stripslashes($album) ?>]</p>
	<p><?php //echo urldecode("%E2%9D%A4") ?> [Length: <?php echo $length ?>]</p>
	<p><?php //echo urldecode("%E2%9D%A4") ?> [From: <?php echo $source ?>] <?php //echo urldecode("%E2%9D%A4") ?></p>
</div>
	<div id="musicinfo">
		<div id="coverbox" class="box" >
		<img id="cover" src="<?php echo $coverimg ?>" from="<?php echo $jsonurl ?>" />
		<div id="coverchange" onclick="javascript:coverchange()">
		<div>Cover image from <?php if($c==0)echo 'last.fm';elseif($c==1)echo 'douban';elseif($c==2)echo 'Google'; ?></div>
		<div>Click here to try another cover</div>
		</div>
		</div>
		<div id="detail" class="box">
		<div id="head">
			<?php //echo urldecode("%E2%99%AB") ?><?php echo urldecode("%E2%99%AB") ?> <span><a href="http://music.wayshine.us/" target="_blank">NowPlaying</a></span> <?php echo " - " ?> <a href="#" onclick="window.open('http://music.wayshine.us/fm.php','newwindow','height=230,width=540,top=0,left=0,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no')" style="font-size:14px">I want to listen!</a>
		</div>
		<div id="frequent">
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
		<div class="line" id="title">
			<span class="t">Title: </span><a  target="_blank" href="<?php echo stripslashes($titleurl) ?>"><?php echo stripslashes($title) ?></a>
		</div>
		<div class="line" id="artist">
			<span class="t">Artist: </span><a  target="_blank" href="<?php echo stripslashes($artisturl) ?>"><?php echo stripslashes($artist) ?></a>
		</div>
		<div class="line" id="album">
			<span class="t">Album: </span><a  target="_blank" href="<?php echo stripslashes($albumurl) ?>"><?php echo stripslashes($album) ?></a>
		</div>
		<div id="share">
		<?php
		
		//share button for social
		if($s!=1){
		$tweet=urldecode("%E2%99%AB") . ' ' . stripslashes($title) . ' - ' . stripslashes($artist)	. ' [' . stripslashes($album) . ']';
		$url_arr=parse_url($url);
		parse_str($url_arr[query],$arr);
		$arr[s]='3';
		$turl=$url_arr[host].$url_arr[path].'?'.http_build_query($arr);
		$gurl=addslashes($turl);
		$gurl='http://'.$gurl;
		$turl=urlencode($turl);
		$surl=file_get_contents("http://is.gd/create.php?format=simple&url=$turl");
		$turl='http://'.$turl;
				
echo <<< eod
		<div id="share1" data-href="$gurl" >
			<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
			<div class="g-plus" data-action="share" data-width="340" data-href="$gurl" ></div>
			<a target="_blank" href="https://www.facebook.com/sharer.php?u=$turl&t=$tweet" ><div class="button"><img style="height:16px;width:16px;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABV0RVh0Q3JlYXRpb24gVGltZQA2LzI0LzA59sFr4wAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTNAay06AAAAFPSURBVDiNlZK9SgNBFIXPrBPUGIjEBH8KGwVry+39KbSxU5/AJkUsxEfQN7AUC0EQbNIIWtvFV7AxibgGk7hhf+69YxETskF2N7eaA/c7nDMz6qhyUwBmqsZYNiYYpeQF8PY1ka4uFLJ2qZiDUioVbIzBp/Njf7WoqsNQ2UpP4a3RmSQAcrMZhKGyNQmj7fqJAIUePPe7D88voe36IGFoJgazJMKXlW2srRYBAIdnDwAAJoYmFrCYWIOLERjAcJ9YoIkJJPEJ1v/gg9P7aDKm+Apu+yOiO60GAGAuv5iuwvN1+V+9V74bq5BwieMz2O9XiEmwdXILAHi6Oo7owTALNBMlPuMoENFE0CSMleV8KoPxPee9Di0s6La6mM5OJxoEXjA8+z0fwgJLTFBr1h34veTvPAo36w7EBDW1sXNeEso8GmNtpnYAoJS8Wjrc/QXn6cac0rbZ/wAAAABJRU5ErkJggg==" />
			<div style="margin-left:20px;margin-top:-18px;color:#227;">Share</div>
			</div></a>
			<a target="_blank" href="https://twitter.com/intent/tweet?hashtags=Nowplaying&original_referer=$turl&source=tweetbutton&text=$tweet&url=$surl" ><div class="button"><img style="height:16px;width:16px;" src="data:image/png;base64,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" />
			<div style="margin-left:20px;margin-top:-18px">Tweet</div>
			</div></a>
		</div>
eod;
}
		?>
			<script type="text/javascript" src="http://widget.renren.com/js/rrshare.js"></script>
			<a name="xn_share" onclick="shareClick()" type="button_medium" href="javascript:;"></a>
			<script type="text/javascript">
				function shareClick() {
					var rrShareParam = {
					resourceUrl : document.URL,
					srcUrl : '',	
					pic : '<?php echo $coverimg ?>',	
					title : '<?php echo urldecode("%E2%99%AB") ?> Nowplaying - <?php echo stripslashes($title) ?>',	
					description : '<?php echo stripslashes($title) ?> - <?php echo stripslashes($artist) ?> [<?php echo stripslashes($album) ?>]'
				};
				rrShareOnclick(rrShareParam);
				}
			</script>
		</div>
		<div id="play" data-href="<?php echo $ytsearch ?>">
		<div id="playtop"></div>
		<div id="ytbox"><iframe id="ytplayer" type="text/html" width="340" height="235" src="<?php echo $yturl ?>" frameborder="0"></iframe></div>
		</div>
		</div>
	<div id="history" class="box">
	<div>Recently played</div>
	<?php
	
	//get history from database
	$db = sqlite_open("music.db");
	$query = sqlite_query($db, "select * from data order by id desc limit 8");
	
	$title0='';
	
	while($res = sqlite_fetch_array($query)){
	$resurl = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].'?album='.urlencode(stripslashes(urldecode($res['album']))).'&title='.urlencode(stripslashes(urldecode($res['title']))).'&artist='.urlencode(stripslashes(urldecode($res['artist']))).'&c=0&s=3';
	echo '<div><a href="' . $resurl . '">' . urldecode("%E2%98%8A") . ' ' . stripslashes(urldecode($res['title'])) . ' - ' . stripslashes(urldecode($res['artist'])) . ' [' . stripslashes(urldecode($res['album'])) . ']</a></div>';
	if($title0=='')$title0=stripslashes(urldecode($res['title']));
	}
	
	//insert into database
	if($s!=3 && $title0!=$title && $title!="" && $album!=""){
	$now = date("y-m-d H:i:s");
	$title=urlencode($title);
	$artist=urlencode($artist);
	$album=urlencode($album);
	$coverimg=urlencode($coverimg);
	$result=sqlite_query($db, "insert into data (title,artist,album,name,time) values('$title','$artist','$album','$coverimg','$now')");
	}
	
	$result = sqlite_query($db, "SELECT * FROM data");
	$count = sqlite_num_rows($result);
	sqlite_close($db);
	
	?>
	</div>
	</div>
	<?php //useful infomation  ?>
	<div id="footer">
	Nowplaying Sharing Page By <a href="http://blog.wayshine.us">Wayne</a>.<br /><a href="http://music.wayshine.us/readme.html">Click here to get the user document</a><br /><?php echo $count ?> songs have been added.<br /><a target="_blank" href="http://music.wayshine.us/musicguess.php">Click here to play a little game!</a><br />Last modified time: <?php echo date("y-m-d  H:i:s", filemtime('nowplaying.php')); ?>.
	</div>
	<div id="footer0" onclick="javascript:footopen()">♪</div>
</body>
</html>