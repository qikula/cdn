<?php
error_reporting(0);
header('Content-Type:application/json');
//获取源代码函数
function getURL($url){
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, "gzip");
    $content = curl_exec($ch);
	curl_close($ch);
    return $content;
}
if(!isset($_GET["aid"]))
	echo "参数错误";
else
{
$j=file_get_contents("https://api.bilibili.com/x/web-interface/view?callback=cb_view&aid=".$_GET["aid"]);
$arr=json_decode($j,true);
if(isset($_GET["p"]))
	$cid=$arr['data']['pages'][$_GET["p"]-1]['cid'];
else
	$cid=$arr['data']['cid'];
$url = "https://api.bilibili.com/x/v1/dm/list.so?oid=".$cid;
$x=getURL($url);
$xml=simplexml_load_string($x);
$json=json_encode($xml,JSON_UNESCAPED_UNICODE);  
$arr=json_decode($json,true);  
$a=array();
$a["code"]=0;
$a["data"]=array();
for($i=0;$i<count($arr["d"]);$i++){
	$a["data"][$i]=array();
	$b=explode(",",(string)$xml->d[$i]['p']);
	$a["data"][$i][0]=(float)$b[0];
	$a["data"][$i][1]=(float)$b[5];
	$a["data"][$i][2]=(float)$b[3];
	$a["data"][$i][3]=$b[6];
	$a["data"][$i][4]=$arr["d"][$i];
}
$c=json_encode($a,JSON_UNESCAPED_UNICODE);
echo $c;
}
?>