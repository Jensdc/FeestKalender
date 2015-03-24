<?php
session_start();
 
require_once('Facebook/FacebookSession.php');
require_once('Facebook/FacebookRedirectLoginHelper.php');
require_once('Facebook/FacebookRequest.php');
require_once('Facebook/FacebookResponse.php');
require_once('Facebook/FacebookSDKException.php');
require_once('Facebook/FacebookRequestException.php');
require_once('Facebook/FacebookAuthorizationException.php');
require_once('Facebook/GraphObject.php');
require_once('Facebook/HttpClients/FacebookCurl.php');
require_once('Facebook/HttpClients/FacebookHttpable.php');
require_once('Facebook/HttpClients/FacebookCurlHttpClient.php');
require_once('Facebook/Entities/AccessToken.php');
require_once('Facebook/GraphUser.php');
//require_once('autoload.php');
 
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\HttpClients\FacebookCurl;
use Facebook\HttpClients\FacebookHttpable;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\Entities\AccessToken;
use Facebook\GraphUser;
 
$app_id = '874524282610914';
$app_secret = 'dd1cf0fba3f33660dcbe78eccc106bef';
 
FacebookSession::setDefaultApplication($app_id, $app_secret);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Facebook SDK examples</title>
</head>
<body>
<?php
//GET /oauth/access_token?
//     client_id={app-id}
//    &client_secret={app-secret}
//    &grant_type=client_credentials
 
            
// 
$helper = new FacebookRedirectLoginHelper("http://www.jensdc.com/kalender/raw.php", $app_id, $app_secret); 
try {
    $session = $helper->getSessionFromRedirect();
}
catch(FacebookRequestException $ex) { } 
catch(\Exception $ex) { }
 
$loggedIn = false;
 
if (isset($session)){
    if ($session) {
        $loggedIn = true;
        try {
          // Logged in
//          
                  
//           $user_photos =  (new FacebookRequest(
//            $session, 'GET', '/oauth/access_token?client_id=$app_id&client_secret=$app_secret&grant_type=client_credentials'))->execute()->getGraphObject(GraphUser::className());
//          $user_photos = $user_photos->asArray();
//         // $pic = $user_photos["data"][0]->{"source"};
//          print_r($user_photos);
//     
//            $n = "903454059668190";
//$request = new FacebookRequest(
//  $session,
//  'GET',
//  '/'.$n.'/events'
//);
$request = new FacebookRequest($session,
        'GET',
        '/search?q=Gent&type=event'
        );
        
$response = $request->execute();
$graphObject = $response->getGraphObject();
 $graphObject = $graphObject->asArray();
echo "<pre>";
print_r($graphObject);
echo "</pre>";


//echo $pic;

//
//$pic = $user_photos["data"][0]->{"source"};
//
//Array
//(
//    [data] => Array
//        (
//            [0] => stdClass Object
//                (
//                    [id] => 10204675593704510
//                    [created_time] => 2015-02-21T21:38:05+0000
//                    [from] => stdClass Object
//                        (
//                            [id] => 10204885152063338
//                            [name] => Jens De Clercq
//                        )
//
//                    [height] => 540
//                    [icon] => https://fbstatic-a.akamaihd.net/rsrc.php/v2/yz/r/StEh3RhPvjk.gif
//                    [images] => Array
//                        (
//                            [0] => stdClass Object
//                                (
//                                    [height] => 720
//                                    [source] => https://scontent.xx.fbcdn.net/hphotos-xaf1/v/t1.0-9/10177321_10204675593704510_5754131288301730964_n.jpg?oh=e7aa11588f8531bb1eb49a0ee4e936f4&oe=55AFF490
//                                    [width] => 960
//                                )


          
          
        } catch(FacebookRequestException $e) {
            echo "Exception occured, code: " . $e->getCode();
            echo " with message: " . $e->getMessage();
        }   
    }
}
if (!$loggedIn){
  $loginUrl = $helper->getLoginUrl(array('user_events','user_groups'));
  echo "<a href='$loginUrl'>Login";
}
?>
</body>
</html>