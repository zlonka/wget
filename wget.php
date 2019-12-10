<?php
$url="http://webcam.abaco-digital.es/pilar/image.jpg";
$dir="pilar5";
$delay=60;	// in seconds

date_default_timezone_set("Europe/Paris");

if (isset($argv[1])) $url=$argv[1];
if (isset($argv[2])) $dir=$argv[2];
if (isset($argv[3])) $delay=$argv[3];

getUrl($url, $dir, $delay);

function getUrl($url, $dir, $delay){
	echo "dumping $url to $dir every $delay seconds...\n";
	@mkdir($dir);
	$finfo=$dir."/info.txt";
	if (!file_exists($finfo)){
		$f=fopen($finfo,"w") or die("Can't create file $finfo.");
		fwrite($f, "**************************
Date : ".date("d/m/Y")."
URL : ".$url."
Save as : ".$dir."
Interval : ".$delay."sec
");
		fclose($f);
	}
	$ici=getcwd();
	chdir($dir);
	$delay*=1000;	// ms
	// find previous files
	$now=date("Y-m-d");
	if (!file_exists($now)) mkdir($now);
	for($i=1;;$i++){
		$nb="".$i;
		while(strlen($nb)<5) $nb="0".$nb;
		$fic=$now."/".$dir."_".$nb.".jpg";
		// $fic=$dir."_".$nb.".jpg";
		if (!file_exists($fic)) break;
	}
	// start saving
	for(;;$i++){
		$time_start = microtime_float();	// s
		$nb="".$i;
		while(strlen($nb)<5) $nb="0".$nb;
		// save as XXX/2011-10-09/XXX_00003.jpg
		$now=date("Y-m-d");
		if (!file_exists($now)) mkdir($now);
		$fic=$now."/".$dir."_".$nb.".jpg";
		echo chr(13)."get $fic...";
		$urlLocal=$url;
		// $urlLocal.="?".rand();
		copy($urlLocal, $fic);
		echo "done.";
		$time_end = microtime_float();		// s
		$time = floor(($time_end - $time_start)*1000);	// ms
		// Sleep for a while
		if ($time<$delay){
			$dt=($delay-$time)*1000;	//micros
			echo "usleep($dt);\r";
			usleep($dt);
		}
	}
	chdir($ici);
}
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
?>

