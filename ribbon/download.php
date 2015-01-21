<?php
/*
set_include_path(get_include_path() . PATH_SEPARATOR . './Facebook');
require 'Facebook/HttpClients/FacebookCurl.php';
require 'Facebook/HttpClients/FacebookHttpable.php';
require 'Facebook/HttpClients/FacebookCurlHttpClient.php';
require 'Facebook/Entities/AccessToken.php';
require 'Facebook/FacebookSession.php';
require 'Facebook/FacebookResponse.php';
require 'Facebook/FacebookRequest.php';
require 'Facebook/GraphObject.php';
require 'Facebook/GraphUser.php';

Facebook\FacebookSession::setDefaultApplication('1536468699952801', '7c5b5da328247c3e45a90139a57569ef');

$session = new Facebook\FacebookSession($_POST['tk']);

$me = (new Facebook\FacebookRequest(
$session, 'GET', '/me/picture',
[
'redirect' => false,
'height' => '400',
'type' => 'normal'
]
))->execute()->getGraphObject();

$photoUrl = $me->getProperty('url');
*/

function imagecopymerge_alpha($base_im, $ribbon_im, $base_x, $base_y, $ribbon_x, $ribbon_y, $ribbon_w, $ribbon_h, $pct){
  // creating a cut resource
  $cut = imagecreatetruecolor($ribbon_w, $ribbon_h);

  // copying relevant section from background to the cut resource
  imagecopy($cut, $base_im, 0, 0, $base_x, $base_y, $ribbon_w, $ribbon_h);

  // copying relevant section from watermark to the cut resource
  imagecopy($cut, $ribbon_im, 0, 0, $ribbon_x, $ribbon_y, $ribbon_w, $ribbon_h);

  // insert cut resource to destination image
  imagecopymerge($base_im, $cut, $base_x, $base_y, 0, 0, $ribbon_w, $ribbon_h, $pct);
}

$photoUrl = $_POST['photo'];
$color = $_POST['color'];

if(@$_POST['lang'] == 'en')
  $filename = 'profile-photo.png';
else
  $filename = 'foto-perfil.png';

header('Content-Description: File Transfer');
header('Content-Type: image/png');
header('Content-Disposition: attachment; filename=' . $filename);
header('Content-Transfer-Encoding: binary');
header('Connection: Keep-Alive');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
//header('Content-Length: ' . $size);

// Load the stamp and the photo to apply the watermark to

$base = imagecreatefromstring(file_get_contents($photoUrl));
$ribbon = imagecreatefrompng('img/ribbons/'.$color.'.png');

$x = imagesx($base) - imagesx($ribbon) - 2;
$y = imagesy($base) - imagesy($ribbon);

// Merge the stamp onto our photo with an opacity of 50%
imagecopymerge_alpha($base, $ribbon, $x, $y, 0, 0, imagesx($ribbon), imagesy($ribbon), 100);

// Save the image to file and free memory


imagepng($base);

imagedestroy($base);
imagedestroy($ribbon);

// http://www.facebook.com/photo.php?fbid=[PID]&makeprofile=1
