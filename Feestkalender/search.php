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
require_once('Facebook/GraphSessionInfo.php');

//require_once('autoload.php');

use Facebook\GraphSessionInfo;
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
        <title>Feestkalender</title>
    </head>
    <body>
        <?php
        $helper = new FacebookRedirectLoginHelper("http://www.jensdc.com/kalender/search.php", $app_id, $app_secret);
        if (isset($_SESSION['fb_token'])) {
            // use a saved access token - will get to this later
            $session = new FacebookSession($_SESSION['fb_token']);
            try {
                if (!$session->validate()) {
                    $session = null;
                    $loggedIn = false;
                }
            } catch (Exception $e) {
                $session = null;
            }
        } else {
            try {
                $session = $helper->getSessionFromRedirect();
            } catch (FacebookRequestException $ex) {
                
            } catch (\Exception $ex) {
                
            }
        }



        if (isset($session)) {
            if ($session) {
                $loggedIn = true;
                try {
                    // Logged in

                    $request = new FacebookRequest($session, 'GET', '/search?q=Gent&type=event&since=1426546837&until=1427752837');

                    $response = $request->execute();
                    $graphObject = $response->getGraphObject();
                    $graphObject = $graphObject->asArray();
                    $tel = count($graphObject,COUNT_RECURSIVE);
echo "Aantal resultaten: " . $tel . "<br />";
                    echo "<pre>";
print_r($graphObject);
echo "</pre>";
                } catch (FacebookRequestException $e) {
                    echo "Exception occured, code: " . $e->getCode();
                    echo " with message: " . $e->getMessage();
                }
            }
        }
        if (!$loggedIn) {
            $loginUrl = $helper->getLoginUrl(array('scope' => 'user_events', 'user_groups'));
            echo "<a href='$loginUrl'>Login</a> <br />";
        }
        if ($teller > 0) {
            echo "Aantal keer door loop: " . $teller;
        } else {
            echo "Druk op login, geef de toestemming om events en groepen op de halen en wacht (vooral dat laatste dan)";
        }
        ?> 
    </body>
</html>
