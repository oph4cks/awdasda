<?php
/*
copyright @ medantechno.com
Modified @ Farzain - zFz
2017

*/

require_once('./line_class.php');
require_once('./unirest-php-master/src/Unirest.php');

$channelAccessToken = 'dokrtNAuDLJDFuQbWlodBP/Ae4PxsHE8A6oU5C6orM+1QrQ5ReVkZqxpSEPMfo5ZLW9vDANxpHqeHVhkezg+3B0HyR8i/E1QEfwv/eDfWGoom50n9Atpj6YMKLgqiwAI5KBuXyXg4My80GnYR3A7bQdB04t89/1O/w1cDnyilFU='; //sesuaikan 
$channelSecret = 'd41b0abc149bcbce8709bffd89cd6378';//sesuaikan

$client = new LINEBotTiny($channelAccessToken, $channelSecret);

$userId 	= $client->parseEvents()[0]['source']['userId'];
$groupId 	= $client->parseEvents()[0]['source']['groupId'];
$replyToken = $client->parseEvents()[0]['replyToken'];
$timestamp	= $client->parseEvents()[0]['timestamp'];
$type 		= $client->parseEvents()[0]['type'];

$message 	= $client->parseEvents()[0]['message'];
$messageid 	= $client->parseEvents()[0]['message']['id'];

$profil = $client->profil($userId);

$pesan_datang = explode(" ", $message['text']);

$command = $pesan_datang[0];
$options = $pesan_datang[1];
if (count($pesan_datang) > 2) {
    for ($i = 2; $i < count($pesan_datang); $i++) {
        $options .= '+';
        $options .= $pesan_datang[$i];
    }
}

#-------------------------[Function]-------------------------#
function cuaca($keyword) {
    $uri = "http://api.openweathermap.org/data/2.5/weather?q=" . $keyword . ",ID&units=metric&appid=e172c2f3a3c620591582ab5242e0e6c4";
    $response = Unirest\Request::get("$uri");
    $json = json_decode($response->raw_body, true);
    $result = "「Weather Result」";
    $result .= "\n\nİsim şehir:";
	  $result .= $json['name'];
	  $result .= "\n\nHava : ";
	  $result .= $json['weather']['0']['main'];
	  $result .= "\nTanım : ";
	  $result .= $json['weather']['0']['description'];
    return $result;
}
function ig($keyword) { 
    $uri = "https://pesananmaskevin.herokuapp.com/data/ig.php?id=" . $keyword; 

    $response = Unirest\Request::get("$uri"); 

    $json = json_decode($response->raw_body, true); 
    $parsed = array(); 
    $parsed['username'] = $json['username']; 
    $parsed['nama'] = $json['full_name']; 
    $parsed['followers'] = $json['followed_by']['count']; 
    $parsed['following'] = $json['follows']['count']; 
    $parsed['dp'] = $json['profile_pic_url_hd']; 
    return $parsed; 
}
function ig_pict($keyword) { 
    $uri = "https://pesananmaskevin.herokuapp.com/data/media.php?id=" . $keyword; 

    $response = Unirest\Request::get("$uri"); 

    $json = json_decode($response->raw_body, true); 
    $result = $json['shortcode_media']['display_resources']['1']['src']; 
    return $result; 
} 
function ig_vid($keyword) { 
    $uri = "https://pesananmaskevin.herokuapp.com/data/media.php?id=" . $keyword; 

    $response = Unirest\Request::get("$uri"); 

    $json = json_decode($response->raw_body, true); 
    $result = $json['shortcode_media']['video_url']; 
    return $result; 
} 
function ig_dp($keyword) { 
    $uri = "https://pesananmaskevin.herokuapp.com/data/ig.php?id=" . $keyword; 

    $response = Unirest\Request::get("$uri"); 

    $json = json_decode($response->raw_body, true); 
    $result = $json['profile_pic_url_hd']; 
    return $result; 
}
function tts($keyword) { 
    $uri = "https://translate.google.com/translate_tts?ie=UTF-8&tl=id-ID&client=tw-ob&q=" . $keyword; 

    $response = Unirest\Request::get("$uri"); 
    $result = $uri; 
    return $result; 
}
function quote($keyword) {
    $uri = "https://pesananmaskevin.herokuapp.com/data/quote.php";
	
	$hasil = file_get_contents($uri);
	
    $response = Unirest\Request::get("$uri");
	$result = str_replace("<br />","",$hasil); 
    return $result;
}
function lokasi($keyword) { 
    $uri = "https://time.siswadi.com/pray/" . $keyword; 
 
    $response = Unirest\Request::get("$uri"); 
 
    $json = json_decode($response->raw_body, true); 
 $result['address'] .= $json['location']['address'];
 $result['latitude'] .= $json['location']['latitude'];
 $result['longitude'] .= $json['location']['longitude'];
    return $result; 
}
function bitly($keyword) {
    $uri = "https://api-ssl.bitly.com/v3/shorten?access_token=497e74afd44780116ed281ea35c7317285694bf1&longUrl=" . $keyword;

    $response = Unirest\Request::get("$uri");

    $json = json_decode($response->raw_body, true); 
	$checker = $json['data']['long_url'];
	if ($checker == "") {
	$result = "Böyle: @urlkısalt https://google.com/";
	return $result;
	} else {
    $result = "Tamamlandı!\nURL Orjinal: ";
	$result .= $json['data']['long_url'];
	$result .= "\nURL Kısaltma: ";
	$result .= $json['data']['url'];
    return $result;
	}
}
function qrcode($keyword) {
    $uri = "http://chart.googleapis.com/chart?cht=qr&chs=300x300&chl=" . $keyword;

    return $uri;
}
function ytdownload($keyword) {
    $uri = "http://wahidganteng.ga/process/api/b82582f5a402e85fd189f716399bcd7c/youtube-downloader?url=" . $keyword;

    $response = Unirest\Request::get("$uri");

    $json = json_decode($response->raw_body, true);
    $result = "Baslık : \n";
	$result .= $json['title'];
	$result .= "\nTip : ";
	$result .= $json['data']['type'];
	$result .= "\nOlcmek : ";
	$result .= $json['data']['size'];
	$result .= "\nLink : ";
	$result .= $json['data']['link'];
    return $result;
}
#-------------------------[Function]-------------------------#
function thumbnail($keyword) {
    $uri = "http://rahandiapi.herokuapp.com/youtubeapi/search?key=betakey&q=" . $keyword;

    $response = Unirest\Request::get("$uri");

    $json = json_decode($response->raw_body, true);
	$result .= $json['result']['thumbnail'];
    return $result;
}
#-------------------------[Function]-------------------------#
function ytsearch($keyword) {
    $uri = "http://rahandiapi.herokuapp.com/youtubeapi/search?key=betakey&q=" . $keyword;

    $response = Unirest\Request::get("$uri");

    $json = json_decode($response->raw_body, true);
    $result = "Channel : ";
	$result .= $json['result']['author'];
	$result .= "\nBaslık : ";
	$result .= $json['result']['title'];
	$result .= "\nSüre : ";
	$result .= $json['result']['duration'];
	$result .= "\nLikes : ";
	$result .= $json['result']['likes'];
	$result .= "\nDislike : ";
	$result .= $json['result']['dislikes'];
	$result .= "\nSeyirci : ";
	$result .= $json['result']['viewcount'];
	$result .= "\nLink Thumbnail : ";
	$result .= $json['result']['thumbnail'];
    return $result;
}
#-------------------------[Function]-------------------------#
function quotes($keyword) {
    $uri = "http://quotes.rest/qod.json?category=" . $keyword;

    $response = Unirest\Request::get("$uri");

    $json = json_decode($response->raw_body, true);
    $result = "Result : ";
	$result .= $json['success']['total'];
	$result .= "\nQuotes : ";
	$result .= $json['contents']['quotes']['quote'];
	$result .= "\nAuthor : ";
	$result .= $json['contents']['quotes']['author'];
    return $result;
}
#-------------------------[Function]-------------------------#
function film_syn($keyword) {
    $uri = "http://www.omdbapi.com/?t=" . $keyword . '&plot=full&apikey=d5010ffe';

    $response = Unirest\Request::get("$uri");

    $json = json_decode($response->raw_body, true);
    $result = "Judul : \n";
	$result .= $json['Title'];
	$result .= "\n\nSinopsis : \n";
	$result .= $json['Plot'];
    return $result;
}
#-------------------------[Function]-------------------------#
function film($keyword) {
    $uri = "http://www.omdbapi.com/?t=" . $keyword . '&plot=full&apikey=d5010ffe';

    $response = Unirest\Request::get("$uri");

    $json = json_decode($response->raw_body, true);
    $result = "Baslık : ";
	$result .= $json['Title'];
	$result .= "\nPiyasa : ";
	$result .= $json['Released'];
	$result .= "\nTip : ";
	$result .= $json['Genre'];
	$result .= "\nAktorler : ";
	$result .= $json['Actors'];
	$result .= "\nDil : ";
	$result .= $json['Language'];
	$result .= "\nUlke : ";
	$result .= $json['Country'];
    return $result;
}
#-------------------------[Function]-------------------------#
#-------------------------[Function]-------------------------#
function qibla($keyword) { 
    $uri = "https://time.siswadi.com/qibla/" . $keyword; 
 
    $response = Unirest\Request::get("$uri"); 
 
    $json = json_decode($response->raw_body, true); 
 $result .= $json['data']['image'];
    return $result; 
}
#-------------------------[Function]-------------------------#
function ps($keyword) { 
    $uri = "https://translate.yandex.net/api/v1.5/tr.json/translate?key=trnsl.1.1.20171227T171852Z.fda4bd604c7bf41f.f939237fb5f802608e9fdae4c11d9dbdda94a0b5&text=" . $keyword . "&lang=id-id"; 
 
    $response = Unirest\Request::get("$uri"); 
 
    $json = json_decode($response->raw_body, true); 
    $result .= "Name : ";
    $result .= $json['text']['0'];
    $result .= "\nLink: ";
    $result .= "https://play.google.com/store/search?q=" . $keyword . "";
    $result .= "\n\nArama : PlayStore";
    return $result; 
}
#-------------------------[Function]-------------------------#
function anime_syn($title) {
    $parsed = anime($title);
    $result = "Başlık : " . $parsed['title'];
    $result .= "\n\nÖzet :\n" . $parsed['synopsis'];
    return $result;
}
#-------------------------[Function]-------------------------#
function manga_syn($title) {
    $parsed = manga($title);
    $result = "Başlık : " . $parsed['title'];
    $result .= "\n\nÖzet :\n" . $parsed['synopsis'];
    return $result;
}
#-------------------------[Function]-------------------------#
function lirik($keyword) { 
    $uri = "http://ide.fdlrcn.com/workspace/yumi-apis/joox?songname=" . $keyword . ""; 
 
    $response = Unirest\Request::get("$uri"); 
 
    $json = json_decode($response->raw_body, true); 
    $result = "====[Lyrics]====";
    $result .= "\nBaşlık : ";
    $result .= $json['0']['0'];
    $result .= "\nŞarkı sözleri :\n";
    $result .= $json['0']['5'];
    $result .= "\n\nArama : Google";
    $result .= "\n====[Lyrics]====";
    return $result; 
}
#-------------------------[Function]-------------------------#
function music($keyword) { 
    $uri = "http://ide.fdlrcn.com/workspace/yumi-apis/joox?songname=" . $keyword . ""; 
 
    $response = Unirest\Request::get("$uri"); 
 
    $json = json_decode($response->raw_body, true); 
    $result = "====[Music]====";
    $result .= "\nBaşlık : ";
    $result .= $json['0']['0'];
    $result .= "\nSüre : ";
    $result .= $json['0']['1'];
    $result .= "\nLink : ";
    $result .= $json['0']['4'];
    $result .= "\n\nArama : Google";
    $result .= "\n====[Music]====";
    return $result; 
}
#-------------------------[Function]-------------------------#
function githubrepo($keyword) { 
    $uri = "https://api.github.com/search/repositories?q=" . $keyword; 
 
    $response = Unirest\Request::get("$uri"); 
 
    $json = json_decode($response->raw_body, true); 
    $result = "====[GithubRepo]====";
    $result .= "\n====[1]====";
    $result .= "\nResult : ";
    $result .= $json['total_count'];
    $result .= "\nName Repository : ";
    $result .= $json['items']['data']['name'];
    $result .= "\nName Github : ";
    $result .= $json['items']['full_name'];
    $result .= "\nLanguage : ";
    $result .= $json['items']['language'];
    $result .= "\nUrl Github : ";
    $result .= $json['items']['owner']['html_url'];
    $result .= "\nUrl Repository : ";
    $result .= $json['items']['html_url'];
    $result .= "\nPrivate : ";
    $result .= $json['items']['private'];
    $result .= "\n====[2]====";
    $result .= "\nResult : ";
    $result .= $json['total_count'];
    $result .= "\nName Repository : ";
    $result .= $json['items'][['name']];
    $result .= "\nName Github : ";
    $result .= $json['items']['full_name'];
    $result .= "\nLanguage : ";
    $result .= $json['items']['language'];
    $result .= "\nUrl Github : ";
    $result .= $json['items']['owner']['html_url'];
    $result .= "\nUrl Repository : ";
    $result .= $json['items']['html_url'];
    $result .= "\nPrivate : ";
    $result .= $json['items']['private'];
    $result .= "\n====[3]====";
    $result .= "\nResult : ";
    $result .= $json['total_count'];
    $result .= "\nName Repository : ";
    $result .= $json['items']['name'];
    $result .= "\nName Github : ";
    $result .= $json['items']['full_name'];
    $result .= "\nLanguage : ";
    $result .= $json['items']['language'];
    $result .= "\nUrl Github : ";
    $result .= $json['items']['owner']['html_url'];
    $result .= "\nUrl Repository : ";
    $result .= $json['items']['html_url'];
    $result .= "\nPrivate : ";
    $result .= $json['items']['private'];
    $result .= "\n====[GithubRepo]====\n";
    $result .= "\n\nArama : Google";
    $result .= "\n====[GithubRepo]====";
    return $result; 
}
function stickerlist($keyword) {
    $listnya = array(
	    "1",
	    "2",
	    "3",
	    "4",
	    "13",
	    "10",
	    "402",
	    "401",
	    "17",
	    "16",
	    "00000",
	    "405",
	    "5",
	    "404",
	    "406",
	    "21",
	    "9",
	    "103",
	    "102",
	    "00000",
	    "8",
	    "101",
	    "00000",
	    "6",
	    "104",
	    "00000",
	    "108",
	    "109",
	    "00000",
	    "110",
	    "00000",
	    "111",
	    "112",
	    "113",
	    "00000",
	    "114",
	    "115",
	    "116",
	    "117",
	    "00000",
	    "118",
	    "00000",
	    "407",
	    "00000",
	    "408",
	    "409",
	    "00000",
	    "410",
	    "411",
	    "412",
	    "00000",
	    "413",
	    "414",
	    "00000",
	    "415",
	    "416",
	    "00000",
	    "417",
	    "418",
	    "419",
	    "00000",
	    "420",
	    "421",
	    "422",
	    "00000",
	    "423",
	    "424",
	    "00000",
	    "425",
	    "426",
	    "427",
	    "00000",
	    "428",
	    "429",
	    "430",
	    "00000",
	    "119",
	    "120",
	    "121",
	    "122",
	    "00000",
	    "123",
	    "124",
	    "00000",
	    "125",
	    "126",
	    "127",
	    "128",
	    "00000",
	    "129",
	    "00000",
	    "130",
	    "131",
	    "00000",
	    "132",
	    "133",
	    "00000",
	    "134",
	    "135",
	    "00000",
	    "136",
	    "137",
	    "138",
	    "00000",
	    "139",
	    );
            $jaws = array_rand($listnya);
            $result = $listnya[$jaws];
    return $result;
}
function say($keyword) { 
    $uri = "https://script.google.com/macros/exec?service=AKfycbw7gKzP-WYV2F5mc9RaR7yE3Ve1yN91Tjs91hp_jHSE02dSv9w&nama=" . $keyword . "&tanggal=10-05-2003"; 
 
    $response = Unirest\Request::get("$uri"); 
 
    $json = json_decode($response->raw_body, true);
 $result .= $json['data']['nama'];  
    return $result; 
}
function instapoto($keyword) {
    $uri = "https://ari-api.herokuapp.com/instagram?username=" . $keyword;
    $response = Unirest\Request::get("$uri");
    $json = json_decode($response->raw_body, true);
    $result = $json['result']['profile_pic_url'];
    return $result;
}
function insta($keyword) {
    $uri = "https://ari-api.herokuapp.com/instagram?username=" . $keyword;
    $response = Unirest\Request::get("$uri");
    $json = json_decode($response->raw_body, true);
    $result .= " ▂▂▂▂▂▂▂▂▂▂▂▂\n\n";
    $result .= "┇Cyͥbeͣrͫ TK\n";
    $result .= "┇ Kicker & Security\n";
    $result .= "┇   İnstagramSystemsV.3.5.0\n";
    $result .= " ▂▂▂▂▂▂▂▂▂▂▂▂\n\n";
    $result .= " ▂▂▂▂▂▂▂▂▂▂▂▂▂\n";
    $result .= "▎「PROFILE INSTAGRAM」 ▎\n";
    $result .= "▔▔▔▔▔▔▔▔▔▔▔▔\n";
    $result .= "✭『İsim』 ➜ ";
    $result .= $json['result']['full_name'];
    $result .= "\n✭『 Kullanıcıadı 』➜ ";
    $result .= $json['result']['username'];
    $result .= "\n✭『 Private 』➜ ";
    $result .= $json['result']['is_private'];
    $result .= "\n✭『 Takipçi 』➜ ";
    $result .= $json['result']['byline'];
    $result .= "\n\n✭ https://www.instagram.com/" . $keyword;
    return $result;
}
function google_image($keyword) {
    $uri = "https://ari-api.herokuapp.com/images?q=" . $keyword;
    $response = Unirest\Request::get("$uri");
    $json = json_decode($response->raw_body, true);
    $result = $json['result'][0];	
    return $result;
}
function image_neon($keyword) {
    $uri = "https://ari-api.herokuapp.com/neon?text=" . $keyword;	
    return $uri;
}

function youtube($keyword) {
    $uri = "https://www.googleapis.com/youtube/v3/search?part=snippet&order=relevance&regionCode=lk&q=" . $keyword . "&key=AIzaSyB5cpL7DYDn_2c7QuExnGOZ1Wmg4AQmx8c&maxResults=10&type=video";
	

    $response = Unirest\Request::get("$uri");

    $json = json_decode($response->raw_body, true);
    $parsed = array();
    $parsed['a1'] = $json['items']['0']['id']['videoId'];
	$parsed['b1'] = $json['items']['0']['snippet']['title'];
	$parsed['c1'] = $json['items']['0']['snippet']['thumbnails']['high']['url'];
    $parsed['a2'] = $json['items']['1']['id']['videoId'];
	$parsed['b2'] = $json['items']['1']['snippet']['title'];
	$parsed['c2'] = $json['items']['1']['snippet']['thumbnails']['high']['url'];
    $parsed['a3'] = $json['items']['2']['id']['videoId'];
	$parsed['b3'] = $json['items']['2']['snippet']['title'];
	$parsed['c3'] = $json['items']['2']['snippet']['thumbnails']['high']['url'];
    $parsed['a4'] = $json['items']['3']['id']['videoId'];
	$parsed['b4'] = $json['items']['3']['snippet']['title'];
	$parsed['c4'] = $json['items']['3']['snippet']['thumbnails']['high']['url'];
    $parsed['a5'] = $json['items']['4']['id']['videoId'];
	$parsed['b5'] = $json['items']['4']['snippet']['title'];
	$parsed['c5'] = $json['items']['4']['snippet']['thumbnails']['high']['url'];
    $parsed['a6'] = $json['items']['5']['id']['videoId'];
	$parsed['b6'] = $json['items']['5']['snippet']['title'];
	$parsed['c6'] = $json['items']['5']['snippet']['thumbnails']['high']['url'];
    $parsed['a7'] = $json['items']['6']['id']['videoId'];
	$parsed['b7'] = $json['items']['6']['snippet']['title'];	
	$parsed['c7'] = $json['items']['6']['snippet']['thumbnails']['high']['url'];
    $parsed['a8'] = $json['items']['7']['id']['videoId'];
	$parsed['b8'] = $json['items']['7']['snippet']['title'];
	$parsed['c8'] = $json['items']['7']['snippet']['thumbnails']['high']['url'];
    $parsed['a9'] = $json['items']['8']['id']['videoId'];
	$parsed['b9'] = $json['items']['8']['snippet']['title'];
	$parsed['c9'] = $json['items']['8']['snippet']['thumbnails']['high']['url'];
    $parsed['a10'] = $json['items']['9']['id']['videoId'];
	$parsed['b10'] = $json['items']['9']['snippet']['title'];	
	$parsed['c10'] = $json['items']['9']['snippet']['thumbnails']['high']['url'];
    return $parsed;
}
function wib($keyword) {
    $uri = "https://time.siswadi.com/timezone/?address=Turkey";

    $response = Unirest\Request::get("$uri");

    $json = json_decode($response->raw_body, true);
	$parsed = array(); 
	$parsed['time'] = $json['time']['time'];
	$parsed['date'] = $json['time']['date'];
    return $parsed;
}
function wit($keyword) {
    $uri = "https://time.siswadi.com/timezone/?address=Turkey";

    $response = Unirest\Request::get("$uri");

    $json = json_decode($response->raw_body, true);
	$parsed = array(); 
	$parsed['time'] = $json['time']['time'];
	$parsed['date'] = $json['time']['date'];
    return $parsed;
}
function wita($keyword) {
    $uri = "https://time.siswadi.com/timezone/?address=Turkey";

    $response = Unirest\Request::get("$uri");

    $json = json_decode($response->raw_body, true);
	$parsed = array(); 
	$parsed['time'] = $json['time']['time'];
	$parsed['date'] = $json['time']['date'];
    return $parsed;
}
function adfly($url, $key, $uid, $domain = 'adf.ly', $advert_type = 'int')
{
  // base api url
  $api = 'http://api.adf.ly/api.php?';

  // api queries
  $query = array(
    '7970aaad57427df04129cfe2cfcd0584' => $key,
    '16519547' => $uid,
    'advert_type' => $advert_type,
    'domain' => $domain,
    'url' => $url
  );

  // full api url with query string
  $api = $api . http_build_query($query);
  // get data
  if ($data = file_get_contents($api))
    return $data;
}
function youtubelist($keyword) {
    $uri = "https://ari-api.herokuapp.com/youtube/search?q=" . $keyword;
    $response = Unirest\Request::get("$uri");
    $json = json_decode($response->raw_body, true);
    $parsed .= " ▂▂▂▂▂▂▂▂▂▂▂▂\n\n";
    $parsed .= "┇Cyͥbeͣrͫ TK\n";
    $parsed .= "┇  Kicker & Security\n";
    $parsed .= "┇      YotubeSystemsV.3.5.0\n";
    $parsed .= " ▂▂▂▂▂▂▂▂▂▂▂▂n\n";
    $parsed .= "YOUTUBE LIST\n\n";
    $parsed .= "ID: ";
    $parsed .= $json['result'][0]['id'];
    $parsed .= "\nTITLE\n";
    $parsed .= $json['result'][0]['title'];
    $parsed .= "\nURL\n";
    $parsed .= $json['result'][0]['link'];
    $parsed .= "\n\nID: ";
    $parsed .= $json['result'][1]['id'];
    $parsed .= "\nTITLE\n";
    $parsed .= $json['result'][1]['title'];
    $parsed .= "\nURL\n";
    $parsed .= $json['result'][1]['link'];
    $parsed .= "\n\nID: ";
    $parsed .= $json['result'][2]['id'];
    $parsed .= "\nTITLE\n";
    $parsed .= $json['result'][2]['title'];
    $parsed .= "\nURL\n";
    $parsed .= $json['result'][2]['link'];
    $parsed .= "\n\nID: ";
    $parsed .= $json['result'][3]['id'];
    $parsed .= "\nTITLE\n";
    $parsed .= $json['result'][3]['title'];
    $parsed .= "\nURL\n";
    $parsed .= $json['result'][3]['link'];
    $parsed .= "\n\nID: ";
    $parsed .= $json['result'][4]['id'];
    $parsed .= "\nTITLE\n";
    $parsed .= $json['result'][4]['title'];
    $parsed .= "\nURL\n";
    $parsed .= $json['result'][4]['link'];
    return $parsed;
}
function jawabs(){
    $list_jwb = array(
		'Efendim',
	        'Ne diyorsun',
	        'Anlayamıyorum seni',
	        'Buraya konuş Elime',
	        'Sesin Alcaktan geliyor',
	        'Son kez tekrar',
		'Anlaşılmazsın',
		'Sadece şakaydı',	    
		);
    $jaws = array_rand($list_jwb);
    $jawab = $list_jwb[$jaws];
    return($jawab);
}
function waktu($keyword) {
    $uri = "https://time.siswadi.com/pray/" . $keyword;
    $response = Unirest\Request::get("$uri");
    $json = json_decode($response->raw_body, true);
    $result = "====[Time]====";
    $result .= "\nLocation : ";
	$result .= $json['location']['address'];
	$result .= "\nSaat : ";
	$result .= $json['time']['time'];
	$result .= "\nGündoğumu : ";
	$result .= $json['debug']['sunrise'];
	$result .= "\nGünbatımı : ";
	$result .= $json['debug']['sunset'];
	$result .= "\n====[Time]====";
    return $result;
}
function send($input, $rt){
    $send = array(
        'replyToken' => $rt,
        'messages' => array(
            array(
                'type' => 'text',					
                'text' => $input
            )
        )
    );
    return($send);
}
function twitter($keyword) {
    $uri = "https://farzain.xyz/api/twitter.php?apikey=9YzAAXsDGYHWFRf6gWzdG5EQECW7oo&id=";
    $response = Unirest\Request::get("$uri");
    $json = json_decode($response->raw_body, true);
    $result = "「Twitter Result」\n\n";
    $result .= "DisplayName: ";
    $result .= $json[0]['user']['name'];
    $result .= "UserName: ";
    $result .= $json[0]['user']['screen_name'];
    return $result;
}
function shalat($keyword) {
    $uri = "https://time.siswadi.com/pray/" . $keyword;
    $response = Unirest\Request::get("$uri");
    $json = json_decode($response->raw_body, true);
    $result = "「NamazSaati Program」\n\n";
	  $result .= $json['location']['address'];
	  $result .= "\nTarih : ";
	  $result .= $json['time']['date'];
	  $result .= "\n\nSafak : ";
	  $result .= $json['data']['Fajr'];
	  $result .= "\nÖglen : ";
	  $result .= $json['data']['Dhuhr'];
	  $result .= "\nAksam : ";
	  $result .= $json['data']['Asr'];
	  $result .= "\nİkindi : ";
	  $result .= $json['data']['Maghrib'];
	  $result .= "\nYatsı : ";
	  $result .= $json['data']['Isha'];
    return $result;
}
//show menu, saat join dan command /menu
if ($type == 'join' || $command == 'Help') {
    $text .= "┇──────────────\n";
    $text .= "┇☆Grupa Davet Ettiğiniz için\n";
    $text .= "┇☆Teşekkürler!\n";
    $text .= "┇☆Yazılı Menü için ✰Help✰\n";
    $text .= "┇☆Görsel Menü için ✰#help✰\n";
    $text .= "┇──────────────\n";
    $text .= "┇Cyͥbeͣrͫ TK\n";
    $text .= "┇      Kicker Security  ChatBotV.3.5.0\n";
    $text .= "┇────────────────\n";
    $text .= "┇ Help\n";
    $text .= "┇ #help\n";
    $text .= "┇ @youtube [sarkı adı]\n";
    $text .= "┇ @urlkısalt [url]\n";
    $text .= "┇ @playstore [uygulama adı]\n";
    $text .= "┇ #saat \n";
    $text .= "┇ @film [yazı]\n";
    $text .= "┇ @bot\n";
    $text .= "┇ merhaba\n";
    $text .= "┇ selam\n";
    $text .= "┇ @myinfo\n";
    $text .= "┇ #location [sehir adı]\n";
    $text .= "┇ #hava [sehir adı]\n";
    $text .= "┇ #youtube  [parca adı]\n";
    $text .= "┇ #namaz  [sehir adı]\n";
    $text .= "┇ #time  [sehir adı]\n";
    $text .= "┇ #imagegoogle  [yazı & isim]\n";
    $text .= "┇ #imagerenkli [yazı]\n";
    $text .= "┇ #kıble  [sehir adı]\n";
    $text .= "┇ #instagram  [kullanıcı adı]\n";
    $text .= "┇ #twitter  [kullanıcı adı]\n";
    $text .= "┇ #bot\n";
    $text .= "┇ #owner\n";
    $text .= "┇ #say [yazı]\n";
    $text .= "┇────────────────\n\n";
    $text .= "﹋﹋﹋﹋﹋﹋﹋﹋﹋﹋﹋﹋﹋﹋\n";
    $text .= "+-+ +-+ +-+ +-+ +-+ +-+ +-+\n";
    $text .= " |C| |y| |b| |e| |r| |T| |K|\n";
    $text .= "+-+ +-+ +-+ +-+ +-+ +-+ +-+\n";
    $text .= "❚⊛[Chat-Bot-Creator ↓]\n";
    $text .= "❚⊛『http://line.me/ti/p/~cybertk0』\n";
    $text .= "﹏﹏﹏﹏﹏﹏﹏﹏﹏﹏﹏﹏﹏﹏\n";
    $balas = array(
        'replyToken' => $replyToken,
        'messages' => array(
            array(
                'type' => 'text',
                'text' => $text
            )
        )
    );
}
if($message['type']=='text') {
	    if ($command == '#hava') {
        $result = cuaca($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'text',
                    'text' => $result
                )
            )
        );
    }
}
if($message['type']=='text') {
	    if ($command == '#namaz') {
        $result = shalat($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'text',
                    'text' => $result
                )
            )
        );
    }
}
if($message['type']=='text') {
	    if ($command == '#location') {
        $result = lokasi($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'location',
                    'title' => 'Lokasyon',
                    'address' => $result['address'],
                    'latitude' => $result['latitude'],
                    'longitude' => $result['longitude']
                ),
            )
        );
    }
}
if($message['type']=='text') {
	    if ($command == '#twitter') {
        $result = twitter($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'text',
                    'text' => $result
                )
            )
        );
    }
}

// fitur instagram
if($message['type']=='text') {
	    if ($command == '#instagram') {
        $resultnya = instapoto($options);
        $result = insta($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
		array(
                  'type' => 'image',
                  'originalContentUrl' => $resultnya,
                  'previewImageUrl' => $resultnya
                ),
                array(
                    'type' => 'text',
                    'text' => $result
                )
            )
        );
    }
}
if($message['type']=='text') {
	    if ($command == '#time') {
        $result = waktu($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'text',
                    'text' => $result
                )
            )
        );
    }
}
//fitur gambar kiblat
if($message['type']=='text') {
	    if ($command == '#kıble') {
        $hasil = qibla($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'image',
                    'originalContentUrl' => $hasil,
                    'previewImageUrl' => $hasil
                )
            )
        );
    }
}

if($message['type']=='text') {
	    if ($command == '#imagegoogle') {
        $result = google_image($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                  'type' => 'image',
                  'originalContentUrl' => $result,
                  'previewImageUrl' => $result
                )
            )
        );
    }
}
if($message['type']=='text') {
	    if ($command == '#imagerenkli') {
        $result = image_neon($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                  'type' => 'image',
                  'originalContentUrl' => $result,
                  'previewImageUrl' => $result
                )
            )
        );
    }
}

if($message['type']== 'text'){
    $pesan_datang = strtolower($message['text']);
    $filter = explode(' ', $pesan_datang);
    if($filter[0] == '#bot') {
        $balas = send(jawabs(), $replyToken);
    }
}
//fitur kata 
if($message['type']=='text') {
	    if ($command == '#say') {
        $result = deneme($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array (
                    'type' => 'text',
                    'text' => $result
              
                )
            )
        );
    }
}
if ($command == '#help') {
    $balas = array(
        'replyToken' => $replyToken,
        'messages' => array(
          array (
  'type' => 'template',
  'altText' => 'CyberTKChatBot-V-3.5.0',
  'template' =>
  array (
    'type' => 'carousel',
    'columns' =>
    array (
        0 =>
      array (
        'thumbnailImageUrl' => 'https://4.bp.blogspot.com/-L3sjf-JDwVQ/WUEKWMUiDqI/AAAAAAAAoYs/OzuQmmiSm-gdHZrpntvtM31asc3UAVp6wCLcBGAs/s1600/instagram-icon.jpg',
        'imageBackgroundColor' => '#00FFFF',
        'title' => 'INSTAGRAM',
        'text' => 'Anahtar kelimelere dayalı instagram hesap bilgilerini aramak',
        'defaultAction' =>
        array (
          'type' => 'uri',
          'label' => 'View detail',
          'uri' => 'http://instagram.com/_aquariusman',
        ),
        'actions' =>
        array (
          0 =>
          array (
            'type' => 'message',
            'label' => 'TIKLA',
            'text' => 'yazman gereken #instagram <kullanıcıadı>',
          ),
        ),
      ),
      1 =>
      array (
        'thumbnailImageUrl' => 'https://s3.amazonaws.com/urgeio-versus/photo-editor-by-aviary/front/front-1391686692704.variety.jpg',
        'imageBackgroundColor' => '#00FFFF',
        'title' => 'IMAGE NEON',
        'text' => 'image editor',
        'defaultAction' =>
        array (
          'type' => 'uri',
          'label' => 'View detail',
          'uri' => 'http://example.com/page/123',
        ),
        'actions' =>
        array (
          0 =>
          array (
            'type' => 'message',
            'label' => 'TIKLA',
            'text' => 'yazman gereken #imagerenkli <yazistedigini>',
          ),
        ),
      ),
      2 =>
      array (
        'thumbnailImageUrl' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/YouTube_social_white_square_%282017%29.svg/2000px-YouTube_social_white_square_%282017%29.svg.png',
        'imageBackgroundColor' => '#00FFFF',
        'title' => 'YOUTUBE',
        'text' => 'Anahtar kelimeler temelinde youtubedaki videoları arayın',
        'defaultAction' =>
        array (
          'type' => 'uri',
          'label' => 'View detail',
          'uri' => 'http://example.com/page/123',
        ),
        'actions' =>
        array (
          0 =>
          array (
            'type' => 'message',
            'label' => 'TIKLA',
            'text' => 'yazman gereken #youtube <sarkı adı>',
          ),
        ),
      ),
      3 =>
      array (
        'thumbnailImageUrl' => 'https://d500.epimg.net/cincodias/imagenes/2015/05/25/lifestyle/1432541958_414675_1432542807_noticia_normal.jpg',
        'imageBackgroundColor' => '#00FFFF',
        'title' => 'GOOGLE IMAGE',
        'text' => 'Googleda anahtar kelimelere dayalı tüm görselleri arayın',
        'defaultAction' =>
        array (
          'type' => 'uri',
          'label' => 'View detail',
          'uri' => 'http://example.com/page/123',
        ),
        'actions' =>
        array (
          0 =>
          array (
            'type' => 'message',
            'label' => 'TIKLA',
            'text' => 'yazman gereken #image <yazı>',
          ),
        ),
      ),
      4 =>
      array (
        'thumbnailImageUrl' => 'https://cdn.icon-icons.com/icons2/1238/PNG/512/smallwallclock_83790.png',
        'imageBackgroundColor' => '#00FFFF',
        'title' => 'TIME',
        'text' => 'Anahtar Kelimeler temelinde Anime Bilgi Bulma',
        'defaultAction' =>
        array (
          'type' => 'uri',
          'label' => 'View detail',
          'uri' => 'http://example.com/page/123',
        ),
        'actions' =>
        array (
          0 =>
          array (
            'type' => 'message',
            'label' => 'TIKLA',
            'text' => 'yazman gereken #time <sehir>',
          ),
        ),
      ),
      5 =>
      array (
        'thumbnailImageUrl' => 'https://is3-ssl.mzstatic.com/image/thumb/Purple62/v4/cc/68/6c/cc686c29-ffd2-5115-2b97-c4821b548fe3/AppIcon-1x_U007emarketing-85-220-6.png/246x0w.jpg',
        'imageBackgroundColor' => '#00FFFF',
        'title' => 'PRAYTIME',
        'text' => 'Dünyadaki Namazın Takvimini Öğrenin',
        'defaultAction' =>
        array (
          'type' => 'uri',
          'label' => 'View detail',
          'uri' => 'http://example.com/page/123',
        ),
        'actions' =>
        array (
          0 =>
          array (
            'type' => 'message',
            'label' => 'TIKLA',
            'text' => 'yazman gereken #namaz <sehir>',
          ),
        ),
      ),
       6 =>
      array (
        'thumbnailImageUrl' => 'https://s2.bukalapak.com/img/2245927012/w-1000/Arah_Kiblat.jpg',
        'imageBackgroundColor' => '#00FFFF',
        'title' => 'KIBLE',
        'text' => 'kıble yönünün yerini bulmak',
        'defaultAction' =>
        array (
          'type' => 'uri',
          'label' => 'View detail',
          'uri' => 'http://example.com/page/123',
        ),
        'actions' =>
        array (
          0 =>
          array (
            'type' => 'message',
            'label' => 'TIKLA',
            'text' => 'yazman gereken #kıble <lokasyon>',
          ),
        ),
      ),
      7 =>
      array (
        'thumbnailImageUrl' => 'https://taisy0.com/wp-content/uploads/2015/07/Google-Maps.png',
        'imageBackgroundColor' => '#00FFFF',
        'title' => 'GOOGLEMAP',
        'text' => 'Yeri ve Koordinat Yeri Adını bilmek',
        'defaultAction' =>
        array (
          'type' => 'uri',
          'label' => 'View detail',
          'uri' => 'http://example.com/page/123',
        ),
        'actions' =>
        array (
          0 =>
          array (
            'type' => 'message',
            'label' => 'TIKLA',
            'text' => 'yazman gereken #location <sehir>',
          ),
        ),
      ),
      8 =>
      array (
        'thumbnailImageUrl' => 'https://st3.depositphotos.com/3921439/12696/v/950/depositphotos_126961774-stock-illustration-the-tv-icon-television-and.jpg',
        'imageBackgroundColor' => '#00FFFF',
        'title' => 'TELEVISION',
        'text' => '....',
        'defaultAction' =>
        array (
          'type' => 'uri',
          'label' => 'View detail',
          'uri' => 'http://example.com/page/123',
        ),
        'actions' =>
        array (
          0 =>
          array (
            'type' => 'message',
            'label' => 'TIKLA',
            'text' => 'yazman gereken ...',
          ),
        ),
      ),
      9 =>
      array (
        'thumbnailImageUrl' => 'https://4vector.com/i/free-vector-cartoon-weather-icon-05-vector_018885_cartoon_weather_icon_05_vector.jpg',
        'imageBackgroundColor' => '#00FFFF',
        'title' => 'WEATHER STATUS',
        'text' => 'Dünya Hava Tahminini Bilin',
        'defaultAction' =>
        array (
          'type' => 'uri',
          'label' => 'View detail',
          'uri' => 'http://example.com/page/222',
        ),
        'actions' =>
        array (
          0 =>
          array (
            'type' => 'message',
            'label' => 'TIKLA',
            'text' => 'yazman gereken #hava <sehir>',
          ),
        ),
      ),
    ),
    'imageAspectRatio' => 'rectangle',
    'imageSize' => 'cover',
  ),
)
)
);
}
if($message['type']=='text') {
	    if ($command == '@Bot' || $command == '@bot' ) {

        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'text',
                    'text' => $profil->displayName.' Ne istiyorsun ?'
                )
            )
        );
    }
}
//pesan bergambar
if($message['type']=='text') {
	    if ($command == 'attack' || $command == 'attack' ) {

        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'text',
                    'text' => $profil->displayName.' 🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷🇹🇷'
                )
            )
        );
    }
}
//pesan bergambar
if($message['type']=='text') {
	    if ($command == 'selam' || $command == 'Selam' ) {

        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'text',
                    'text' => 'Selam '.$profil->displayName
                )
            )
        );
    }
}
if($message['type']=='text') {
	    if ($command == '@myinfo') {

        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(

										'type' => 'text',					
										'text' => '====[InfoProfile]====
Name: '.$profil->displayName.'

Status: '.$profil->statusMessage.'

Picture: '.$profil->pictureUrl.'

Group: '.$profil->groupId.'


====[InfoProfile]===='
									)
							)
						);
				
	}
}
if($message['type']=='text') {
	    if ($command == '@playstore') {

        $result = ps($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'text',
                    'text'  => 'Searching...'
                ),
                array(
                    'type' => 'text',
                    'text' => $result
                )
            )
        );
    }

}
	if ($command == '@urlkısalt') {

        $result = bitly($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'text',
                    'text' => $result
                )
            )
        );
	}
	if ($command == '@youtube') {

        $result = youtube($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
				array (
				  'type' => 'template',
				  'altText' => 'Youtube',
				  'template' => 
				  array (
				    'type' => 'carousel',
				    'columns' => 
				    array (
				      0 => 
				      array (
				        'thumbnailImageUrl' => $result['c1'],
				        'imageBackgroundColor' => '#FFFFFF',
				        'text' => preg_replace('/[^a-z0-9_ ]/i', '', substr($result['b1'], 0, 47)).'...',
				        'actions' => 
				        array (
				          0 => 
				          array (
				            'type' => 'message', 
				            'label' => 'Tıkla Video İçin',
				            'text' => 'Youtube videosu İzlemek istiyorsan Tıkla Lütfen videoya',
				            'text' => 'https://www.youtube.com/watch?v='.$result['a1'],
				          ),
				          1 => 
				          array (
				            'type' => 'uri',
				            'label' => 'Videoya bak',
				            'uri' => 'https://youtu.be/'.$result['a1'],
				          ),
				          2 => 
				          array (
				            'type' => 'uri',
				            'label' => 'Cyͥbeͣrͫ TK',
				            'uri' => 'http://line.me/ti/p/~cybertk0',
				          ),
				        ),
				      ),
				      1 => 
				      array (
				        'thumbnailImageUrl' => $result['c2'],
				        'imageBackgroundColor' => '#000000',
				        'text' => preg_replace('/[^a-z0-9_ ]/i', '', substr($result['b2'], 0, 47)).'...',
				        'actions' => 
				        array (
				          0 => 
				          array (
				            'type' => 'message', 
				            'label' => 'Tıkla Video İçin',
				            'text' => 'Youtube videosu İzlemek istiyorsan Tıkla Lütfen videoya',
				            'text' => 'https://www.youtube.com/watch?v='.$result['a1'],
				          ),
				          1 => 
				          array (
				            'type' => 'uri',
				            'label' => 'Videoya bak',
				            'uri' => 'https://youtu.be/'.$result['a2'],
				          ),
				          2 => 
				          array (
				            'type' => 'uri',
				            'label' => 'Cyͥbeͣrͫ TK',
				            'uri' => 'http://line.me/ti/p/~cybertk0',
				          ),
				        ),
				      ),
				      2 => 
				      array (
				        'thumbnailImageUrl' => $result['c3'],
				        'imageBackgroundColor' => '#FFFFFF',
				        'text' => preg_replace('/[^a-z0-9_ ]/i', '', substr($result['b3'], 0, 47)).'...',
				        'actions' => 
				        array (
				          0 => 
				          array (
				            'type' => 'message', 
				            'label' => 'Tıkla Video İçin',
				            'text' => 'Youtube videosu İzlemek istiyorsan Tıkla Lütfen videoya',
				            'text' => 'https://www.youtube.com/watch?v='.$result['a1'],
				          ),
				          1 => 
				          array (
				            'type' => 'uri',
				            'label' => 'Videoya bak',
				            'uri' => 'https://youtu.be/'.$result['a3'],
				          ),
				          2 => 
				          array (
				            'type' => 'uri',
				            'label' => 'Cyͥbeͣrͫ TK',
				            'uri' => 'http://line.me/ti/p/~cybertk0',
				          ),
				        ),
				      ),			  
				      3 => 
				      array (
				        'thumbnailImageUrl' => $result['c4'],
				        'imageBackgroundColor' => '#FFFFFF',
				        'text' => preg_replace('/[^a-z0-9_ ]/i', '', substr($result['b4'], 0, 47)).'...',
				        'actions' => 
				        array (
				          0 => 
				          array (
				            'type' => 'message', 
				            'label' => 'Tıkla Video İçin',
				            'text' => 'Youtube videosu İzlemek istiyorsan Tıkla Lütfen videoya',
				            'text' => 'https://www.youtube.com/watch?v='.$result['a1'],
				          ),
				          1 => 
				          array (
				            'type' => 'uri',
				            'label' => 'Videoya bak',
				            'uri' => 'https://youtu.be/'.$result['a4'],
				          ),
				          2 => 
				          array (
				            'type' => 'uri',
				            'label' => 'Cyͥbeͣrͫ TK',
				            'uri' => 'http://line.me/ti/p/~cybertk0',
				          ),
				        ),
				      ),
				      4 => 
				      array (
				        'thumbnailImageUrl' => $result['c5'],
				        'imageBackgroundColor' => '#FFFFFF',
				        'text' => preg_replace('/[^a-z0-9_ ]/i', '', substr($result['b5'], 0, 47)).'...',
				        'actions' => 
				        array (
				          0 => 
				          array (
				            'type' => 'message', 
				            'label' => 'Tıkla Video İçin',
				            'text' => 'Youtube videosu İzlemek istiyorsan Tıkla Lütfen videoya',
				            'text' => 'https://www.youtube.com/watch?v='.$result['a1'],
				          ),
				          1 => 
				          array (
				            'type' => 'uri',
				            'label' => 'Videoya bak',
				            'uri' => 'https://youtu.be/'.$result['a5'],
				          ),
				          2 => 
				          array (
				            'type' => 'uri',
				            'label' => 'Cyͥbeͣrͫ TK',
				            'uri' => 'http://line.me/ti/p/~cybertk0',
				          ),
				        ),
				      ),
				      5 => 
				      array (
				        'thumbnailImageUrl' => $result['c6'],
				        'imageBackgroundColor' => '#FFFFFF',
				        'text' => preg_replace('/[^a-z0-9_ ]/i', '', substr($result['b6'], 0, 47)).'...',
				        'actions' => 
				        array (
				          0 => 
				          array (
				            'type' => 'message', 
				            'label' => 'Tıkla Video İçin',
				            'text' => 'Youtube videosu İzlemek istiyorsan Tıkla Lütfen videoya',
				            'text' => 'https://www.youtube.com/watch?v='.$result['a1'],
				          ),
				          1 => 
				          array (
				            'type' => 'uri',
				            'label' => 'Videoya bak',
				            'uri' => 'https://youtu.be/'.$result['a6'],
				          ),
				          2 => 
				          array (
				            'type' => 'uri',
				            'label' => 'Cyͥbeͣrͫ TK',
				            'uri' => 'http://line.me/ti/p/~cybertk0',
				          ),
				        ),
				      ),				  
				      6 => 
				      array (
				        'thumbnailImageUrl' => $result['c7'],
				        'imageBackgroundColor' => '#FFFFFF',
				        'text' => preg_replace('/[^a-z0-9_ ]/i', '', substr($result['b7'], 0, 47)).'...',
				        'actions' => 
				        array (
				          0 => 
				          array (
				            'type' => 'message', 
				            'label' => 'Tıkla Video İçin',
				            'text' => 'Youtube videosu İzlemek istiyorsan Tıkla Lütfen videoya',
				            'text' => 'https://www.youtube.com/watch?v='.$result['a1'],
				          ),
				          1 => 
				          array (
				            'type' => 'uri',
				            'label' => 'Videoya bak',
				            'uri' => 'https://youtu.be/'.$result['a7'],
				          ),
				          2 => 
				          array (
				            'type' => 'uri',
				            'label' => 'Cyͥbeͣrͫ TK',
				            'uri' => 'http://line.me/ti/p/~cybertk0',
				          ),
				        ),
				      ),				  
				      7 => 
				      array (
				        'thumbnailImageUrl' => $result['c8'],
				        'imageBackgroundColor' => '#FFFFFF',
				        'text' => preg_replace('/[^a-z0-9_ ]/i', '', substr($result['b8'], 0, 47)).'...',
				        'actions' => 
				        array (
				          0 => 
				          array (
				            'type' => 'message', 
				            'label' => 'Tıkla Video İçin',
				            'text' => 'Youtube videosu İzlemek istiyorsan Tıkla Lütfen videoya',
				            'text' => 'https://www.youtube.com/watch?v='.$result['a1'],
				          ),
				          1 => 
				          array (
				            'type' => 'uri',
				            'label' => 'Videoya bak',
				            'uri' => 'https://youtu.be/'.$result['a8'],
				          ),
				          2 => 
				          array (
				            'type' => 'uri',
				            'label' => 'Cyͥbeͣrͫ TK',
				            'uri' => 'http://line.me/ti/p/~cybertk0',
				          ),
				        ),
				      ),
            ),
				    'imageAspectRatio' => 'rectangle',
				    'imageSize' => 'cover',
				  ),
				)		
            )
        );
}
if($message['type']=='text') {
	    if ($command == '@gitclone') {

        $result = githubrepo($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'text',
                    'text' => $result
                )
            )
        );
    }
}
if ($command == '#saat') { 
     
        $result = wib($options); 
		$result2 = wit($options); 
		$result3 = wita($options); 
        $balas = array( 
            'replyToken' => $replyToken, 
            'messages' => array( 
                array ( 
                  'type' => 'template', 
                  'altText' => 'Saat Turkey', 
                  'template' =>  
                  array ( 
                    'type' => 'carousel', 
                    'columns' =>  
                    array ( 
                      0 =>  
                      array ( 
                        'thumbnailImageUrl' => 'https://img.youtube.com/vi/CEb0dBnPCZU/maxresdefault.jpg', 
                        'imageBackgroundColor' => '#FFFFFF', 
                        'title' => 'SUAN', 
                        'text' => 'Saat Turkey', 
                        'actions' =>  
                        array ( 
                          0 =>  
                          array ( 
                            'type' => 'postback', 
                            'label' => $result['time'], 
                            'data' => $result['time'], 
                          ), 
                          1 =>  
                          array ( 
                            'type' => 'postback', 
                            'label' => $result['date'],
                            'data' => $result['date'],
                          ), 
                        ), 
                      ), 
                      1 =>  
                      array ( 
                        'thumbnailImageUrl' => 'https://img.youtube.com/vi/CEb0dBnPCZU/maxresdefault.jpg', 
                        'imageBackgroundColor' => '#000000', 
                        'title' => 'SONRASI', 
                        'text' => 'Saat Turkey', 
                        'actions' =>  
                        array ( 
                          0 =>  
                          array ( 
                            'type' => 'postback', 
                            'label' => $result2['time'], 
                            'data' => $result2['time'], 
                          ), 
                          1 =>  
                          array ( 
                            'type' => 'postback', 
                            'label' => $result2['date'],
                            'data' => $result2['date'],
                          ), 
                        ), 
                      ), 
					            2 =>  
                      array ( 
                        'thumbnailImageUrl' => 'https://img.youtube.com/vi/CEb0dBnPCZU/maxresdefault.jpg', 
                        'imageBackgroundColor' => '#000000', 
                        'title' => 'DAHA SONRASI', 
                        'text' => 'Saat Turkey', 
                        'actions' =>  
                        array ( 
                          0 =>  
                          array ( 
                            'type' => 'postback', 
                            'label' => $result3['time'], 
                            'data' => $result3['time'], 
                          ), 
                          1 =>  
                          array ( 
                            'type' => 'postback', 
                            'label' => $result3['date'],
                            'data' => $result3['date'],
                          ), 
                        ),  
                      ),
                    ), 
                  ), 
                ) 
            ) 
        ); 
}
if($message['type']=='text') {
	    if ($command == '@film') {

        $result = film($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'text',
                    'text' => $result
                )
            )
        );
    }
}
if($message['type']=='text') {
	    if ($command == '#youtube') {
        $result = youtubelist($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'text',
                    'text' => $result
                )
            )
        );
    }
}

elseif($message['type']=='sticker'){	
	$result = stickerlist($options);
	$balas = array(
		'replyToken' => $replyToken,														
		'messages' => array(
			array(
		            'type' => 'sticker', // sesuaikan
                            'packageId' => 1, // sesuaikan
                            'stickerId' => $result// sesuaikan										
									
									)
							)
						);
						
}
    if ($command == '#owner') { 
     
        $balas = array( 
            'replyToken' => $replyToken, 
            'messages' => array( 
                array ( 
                        'type' => 'template', 
                          'altText' => 'About Owner', 
                          'template' =>  
                          array ( 
                            'type' => 'buttons', 
                            'thumbnailImageUrl' => 'https://img.youtube.com/vi/CEb0dBnPCZU/maxresdefault.jpg', 
                            'imageAspectRatio' => 'rectangle', 
                            'imageSize' => 'cover', 
                            'imageBackgroundColor' => '#FFFFFF', 
                            'title' => 'Tolg KR', 
                            'text' => 'Creator CyberTK', 
                            'actions' =>  
                            array ( 
                              0 =>  
                              array ( 
                                'type' => 'uri', 
                                'label' => 'Contact', 
                                'uri' => 'https://line.me/ti/p/~cybertk0', 
                              ), 
                            ), 
                          ), 
                        ) 
            ) 
        ); 
    }
if ($command == 'Selamunaleykum' || $command == 'Selamunaleyküm' || $command == 'selamunaleyküm' || $command == 'selam' || $command == 'sa' || $command == 'merhaba' ) {    $balas = array(
        'replyToken' => $replyToken,
        'messages' => array(
          array (
  'type' => 'template',
  'altText' => 'CyberTKChatBot-V-3.5.0',
  'template' =>
  array (
    'type' => 'carousel',
    'columns' =>
    array (
        0 =>
      array (
        'thumbnailImageUrl' => 'https://4.bp.blogspot.com/-L3sjf-JDwVQ/WUEKWMUiDqI/AAAAAAAAoYs/OzuQmmiSm-gdHZrpntvtM31asc3UAVp6wCLcBGAs/s1600/instagram-icon.jpg',
        'imageBackgroundColor' => '#00FFFF',
        'title' => 'Cyͥbeͣrͫ TK Kicker & Security',
        'text' => 'CyberTKChatBot-V-3.5.0',
        'defaultAction' =>
        array (
          'type' => 'uri',
          'label' => 'CyberTK',
          'uri' => 'http://line.me/ti/p/~cybertk0',
        ),
        'actions' =>
        array (
          0 =>
          array (
            'type' => 'message',
            'label' => 'İletişim İçin Tıkla',
            'text' => 'http://line.me/ti/p/~cybertk0',
          ),
        ),
      ),
      1 =>
      array (
        'thumbnailImageUrl' => 'https://s3.amazonaws.com/urgeio-versus/photo-editor-by-aviary/front/front-1391686692704.variety.jpg',
        'imageBackgroundColor' => '#00FFFF',
        'title' => 'INSTAGRAM',
        'text' => 'Anahtar kelimelere dayalı instagram hesap bilgilerini aramak',
        'defaultAction' =>
        array (
          'type' => 'uri',
          'label' => 'View detail',
          'uri' => 'http://instagram.com/_aquariusman',
        ),
        'actions' =>
        array (
          0 =>
          array (
            'type' => 'message',
            'label' => 'TIKLA',
            'text' => 'yazman gereken #instagram <kullanıcıadı>',
          ),
        ),
      ),
      2 =>
      array (
        'thumbnailImageUrl' => 'https://s3.amazonaws.com/urgeio-versus/photo-editor-by-aviary/front/front-1391686692704.variety.jpg',
        'imageBackgroundColor' => '#00FFFF',
        'title' => 'IMAGE NEON',
        'text' => 'image editor',
        'defaultAction' =>
        array (
          'type' => 'uri',
          'label' => 'View detail',
          'uri' => 'http://example.com/page/123',
        ),
        'actions' =>
        array (
          0 =>
          array (
            'type' => 'message',
            'label' => 'TIKLA',
            'text' => 'yazman gereken #imagerenkli <yazistedigini>',
          ),
        ),
      ),
      3 =>
      array (
        'thumbnailImageUrl' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/YouTube_social_white_square_%282017%29.svg/2000px-YouTube_social_white_square_%282017%29.svg.png',
        'imageBackgroundColor' => '#00FFFF',
        'title' => 'YOUTUBE',
        'text' => 'Anahtar kelimeler temelinde youtubedaki videoları arayın',
        'defaultAction' =>
        array (
          'type' => 'uri',
          'label' => 'View detail',
          'uri' => 'http://example.com/page/123',
        ),
        'actions' =>
        array (
          0 =>
          array (
            'type' => 'message',
            'label' => 'TIKLA',
            'text' => 'yazman gereken #youtube <sarkı adı>',
          ),
        ),
      ),
      4 =>
      array (
        'thumbnailImageUrl' => 'https://d500.epimg.net/cincodias/imagenes/2015/05/25/lifestyle/1432541958_414675_1432542807_noticia_normal.jpg',
        'imageBackgroundColor' => '#00FFFF',
        'title' => 'GOOGLE IMAGE',
        'text' => 'Googleda anahtar kelimelere dayalı tüm görselleri arayın',
        'defaultAction' =>
        array (
          'type' => 'uri',
          'label' => 'View detail',
          'uri' => 'http://example.com/page/123',
        ),
        'actions' =>
        array (
          0 =>
          array (
            'type' => 'message',
            'label' => 'TIKLA',
            'text' => 'yazman gereken #image <yazı>',
          ),
        ),
      ),
      5 =>
      array (
        'thumbnailImageUrl' => 'https://cdn.icon-icons.com/icons2/1238/PNG/512/smallwallclock_83790.png',
        'imageBackgroundColor' => '#00FFFF',
        'title' => 'TIME',
        'text' => 'Anahtar Kelimeler temelinde Anime Bilgi Bulma',
        'defaultAction' =>
        array (
          'type' => 'uri',
          'label' => 'View detail',
          'uri' => 'http://example.com/page/123',
        ),
        'actions' =>
        array (
          0 =>
          array (
            'type' => 'message',
            'label' => 'TIKLA',
            'text' => 'yazman gereken #time <sehir>',
          ),
        ),
      ),
      6 =>
      array (
        'thumbnailImageUrl' => 'https://is3-ssl.mzstatic.com/image/thumb/Purple62/v4/cc/68/6c/cc686c29-ffd2-5115-2b97-c4821b548fe3/AppIcon-1x_U007emarketing-85-220-6.png/246x0w.jpg',
        'imageBackgroundColor' => '#00FFFF',
        'title' => 'PRAYTIME',
        'text' => 'Dünyadaki Namazın Takvimini Öğrenin',
        'defaultAction' =>
        array (
          'type' => 'uri',
          'label' => 'View detail',
          'uri' => 'http://example.com/page/123',
        ),
        'actions' =>
        array (
          0 =>
          array (
            'type' => 'message',
            'label' => 'TIKLA',
            'text' => 'yazman gereken #namaz <sehir>',
          ),
        ),
      ),
       7 =>
      array (
        'thumbnailImageUrl' => 'https://s2.bukalapak.com/img/2245927012/w-1000/Arah_Kiblat.jpg',
        'imageBackgroundColor' => '#00FFFF',
        'title' => 'KIBLE',
        'text' => 'kıble yönünün yerini bulmak',
        'defaultAction' =>
        array (
          'type' => 'uri',
          'label' => 'View detail',
          'uri' => 'http://example.com/page/123',
        ),
        'actions' =>
        array (
          0 =>
          array (
            'type' => 'message',
            'label' => 'TIKLA',
            'text' => 'yazman gereken #kıble <lokasyon>',
          ),
        ),
      ),
      8 =>
      array (
        'thumbnailImageUrl' => 'https://taisy0.com/wp-content/uploads/2015/07/Google-Maps.png',
        'imageBackgroundColor' => '#00FFFF',
        'title' => 'GOOGLEMAP',
        'text' => 'Yeri ve Koordinat Yeri Adını bilmek',
        'defaultAction' =>
        array (
          'type' => 'uri',
          'label' => 'View detail',
          'uri' => 'http://example.com/page/123',
        ),
        'actions' =>
        array (
          0 =>
          array (
            'type' => 'message',
            'label' => 'TIKLA',
            'text' => 'yazman gereken #location <sehir>',
          ),
        ),
      ),
      9 =>
      array (
        'thumbnailImageUrl' => 'https://st3.depositphotos.com/3921439/12696/v/950/depositphotos_126961774-stock-illustration-the-tv-icon-television-and.jpg',
        'imageBackgroundColor' => '#00FFFF',
        'title' => 'TELEVISION',
        'text' => '....',
        'defaultAction' =>
        array (
          'type' => 'uri',
          'label' => 'View detail',
          'uri' => 'http://example.com/page/123',
        ),
        'actions' =>
        array (
          0 =>
          array (
            'type' => 'message',
            'label' => 'TIKLA',
            'text' => 'yazman gereken ...',
          ),
        ),
      ),
      10 =>
      array (
        'thumbnailImageUrl' => 'https://4vector.com/i/free-vector-cartoon-weather-icon-05-vector_018885_cartoon_weather_icon_05_vector.jpg',
        'imageBackgroundColor' => '#00FFFF',
        'title' => 'WEATHER STATUS',
        'text' => 'Dünya Hava Tahminini Bilin',
        'defaultAction' =>
        array (
          'type' => 'uri',
          'label' => 'View detail',
          'uri' => 'http://example.com/page/222',
        ),
        'actions' =>
        array (
          0 =>
          array (
            'type' => 'message',
            'label' => 'TIKLA',
            'text' => 'yazman gereken #hava <sehir>',
          ),
        ),
      ),
    ),
    'imageAspectRatio' => 'rectangle',
    'imageSize' => 'cover',
  ),
)
)
);
}
if (isset($balas)) {
    $result = json_encode($balas);
//$result = ob_get_clean();

    file_put_contents('./balasan.json', $result);


    $client->replyMessage($balas);
}
?>
