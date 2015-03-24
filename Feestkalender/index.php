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
        $helper = new FacebookRedirectLoginHelper("http://www.jensdc.com/kalender/", $app_id, $app_secret);
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
$i = 0;
       

        if (isset($session)) {
            if ($session) {
                $loggedIn = true;
                try {
                    // Logged in

                    //$request = new FacebookRequest($session, 'GET', '/search?q="Gent"&type=event&fields=id&since=1426546837&until=1427752837'); //-1 week +1 week
                    //-1 day +1day
                    //1427152911 //-
                    //1427325711 //+
$request = new FacebookRequest($session, 'GET', '/search?q="Gent"&type=event&fields=id&since=1427152911&until=1427325711');
                    $response = $request->execute();
                    $graphObject = $response->getGraphObject();
                    $graphObject = $graphObject->asArray();
echo "<pre>";
print_r($graphObject);
echo "</pre>";
                    foreach ($graphObject as $k => $value) {
                        foreach ($value as $l => $lol) {
                            if ($l >= 0) {
                                //$eventNaam = $graphObject["data"][$i]->{"name"};
                                $eventID = $graphObject["data"][$i]->{"id"};
                                settype($eventID, "integer");
                                $teller++;

                                // Aanwezige gasten

//                                $requestAttending = new FacebookRequest($session, 'GET', '/' . $eventID . '/attending');
//                                $responseAt = $requestAttending->execute();
//                                $graphObjectat = $responseAt->getGraphObject();
//                                $graphObjectat = $graphObjectat->asArray();
//                                $aanwezig = count($graphObjectat, COUNT_RECURSIVE); // tellen.
//                                echo "<pre>";
//                                print_r($graphObjectat);
//
//                                echo "</pre>";
//                               
//                                //Misschien aanwezige gasten
//                                
//                                $requestMA = new FacebookRequest($session, 'GET', '/' . $eventID . '/maybe');
//                                $reponseMA = $requestMA->execute();
//                                $graphObjectMA = $reponseMA->getGraphObject();
//                                $graphObjectMA = $graphObjectMA->asArray();
//                                $misschien = count($graphObjectMA, COUNT_RECURSIVE); // tellen.
                                //echo $eventNaam . " " . $eventID . "<br />";
                                //Details evenement ophalen. (Momenteel enkel description, later ook locatie ed.)
                                $requestPrivacy = new FacebookRequest($session, 'GET', '/' . $eventID .'?fields=name,location,attending_count,maybe_count,start_time');
                                $responsePrivacy = $requestPrivacy->execute();
                                $graphObjectPR = $responsePrivacy->getGraphObject();
                                $graphObjectPR = $graphObjectPR->asArray();
                                $aanwezig = $graphObjectPR["attending_count"];
                                $misschien = $graphObjectPR["maybe_count"];
                                $naam = $graphObjectPR["name"];
                                $loc = $graphObjectPR["location"];
                                $start = $graphObjectPR["start_time"];
                                
                                echo "Naam :" . $naam;
                                echo "<br />";
                                echo "Locatie: " . $loc;
                                echo "<br />";
                                echo "Start: " . $start;
                                echo "<br />";
                                echo "Aanwezigen: " . $aanwezig;
                                echo "<br />";
                                echo "Misschien :" . $misschien;
                                echo "<br />";
                                
//                                                        echo "<pre>";
//print_r($graphObjectPR);
//echo "</pre>";

                                //$beschrijving = $graphObjectPR['description'];
                               // echo $eventID . "Naam: " . $eventNaam . "<br />";
                                //echo "Aantal aanwezig: " . $aanwezig . " Mogelijk aanwezig: " . $misschien . "<br />";
                            }

                            $i++;
                        }
                        //echo "<pre>";
//print_r($graphObject);
//echo "</pre>";
                    }
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
