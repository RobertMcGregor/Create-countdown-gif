<?php

date_default_timezone_set('America/Halifax');
include 'GIFEncoder.class.php';
 
$time = "07/01/14";
$future_date = new DateTime(date('r',strtotime($time)));
$time_now = time();
$now = new DateTime(date('r', $time_now));
 
 //echo $time_now;
 
$frames = array();
$delays = array();
 
 
$image = imagecreatefrompng('countdown.png');
$delay = 100; // milliseconds
$font = array(
	'size'=>40,
	'angle'=>0,
	'x-offset'=>10,
	'y-offset'=>70,
	'file'=>'DIGITALDREAM.ttf',
	'color'=>imagecolorallocate($image, 255, 255, 255),
);
for($i = 0; $i <= 60; $i++){
	$interval = date_diff($future_date, $now);
	if($future_date < $now){

		// Open the first source image and add the text.
		$image = imagecreatefrompng('countdown.png');;
		$text = $interval->format('00:00:00:00');
		imagettftext ($image , $font['size'] , $font['angle'] , $font['x-offset'] , $font['y-offset'] , $font['color'] , $font['file'], $text );
		ob_start();
		imagegif($image);
		$frames[]=ob_get_contents();
		$delays[]=$delay;
                $loops = 1; 
		ob_end_clean();
		break;
	} else {
		// Open the first source image and add the text.
		$image = imagecreatefrompng('countdown.png');;
		$text = $interval->format('%a:%H:%I:%S');
		// %a is weird in that it doesn’t give you a two digit number
		// check if it starts with a single digit 0-9
		// and prepend a 0 if it does
		if(preg_match('/^[0-9]\:/', $text)){
			$text = '0'.$text;
		}
		imagettftext ($image , $font['size'] , $font['angle'] , $font['x-offset'] , $font['y-offset'] , $font['color'] , $font['file'], $text );
		ob_start();
		imagegif($image);
		$frames[]=ob_get_contents();
		$delays[]=$delay;
                $loops = 0;
		ob_end_clean();
	}
	$now->modify('+1 second');
}
//expire this image instantly
header( 'Expires: Sat, 26 Jul 2013 05:00:00 GMT' );
header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
header( 'Cache-Control: no-store, no-cache, must-revalidate' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' ); 
$gif = new AnimatedGif($frames,$delays,$loops);
//$gif->getAnimation();
$gif->display();