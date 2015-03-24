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
        $helper = new FacebookRedirectLoginHelper("http://www.jensdc.com/kalender/", $app_id, $app_secret);
        try {
            $session = $helper->getSessionFromRedirect();
        } catch (FacebookRequestException $ex) {
            
        } catch (\Exception $ex) {
            
        }

        $loggedIn = false;

        if (isset($session)) {
            if ($session) {
                $loggedIn = true;
                try {
                    // Logged in

                    $request = new FacebookRequest($session, 'GET', '/search?q=Gent&type=event&fields=id,name&since=1426546837&until=1427752837');

                    $response = $request->execute();
                    $graphObject = $response->getGraphObject();
                    $graphObject = $graphObject->asArray();
//echo "<pre>";
//print_r($graphObject);
//echo "</pre>";
                    foreach ($graphObject as $k => $value) {
                        foreach ($value as $l => $lol) {
                            if ($l >= 1) {
                                $eventNaam = $graphObject["data"][$i]->{"name"};
                                $eventID = $graphObject["data"][$i]->{"id"};
                                settype($eventID, "integer");
                                $teller++;

                                // Aanwezige gasten

                                $requestAttending = new FacebookRequest($session, 'GET', '/' . $eventID . '/attending' );
                                $responseAt = $requestAttending->execute();
                                $graphObjectat = $responseAt->getGraphObject();
                                $graphObjectat = $graphObjectat->asArray();
                                $aanwezig = count($graphObjectat, COUNT_RECURSIVE); // tellen.
//                                echo "<pre>";
//                                print_r($graphObjectat);
//
//                                echo "</pre>";
//                               
//                                //Misschien aanwezige gasten
//                                
                                $requestMA = new FacebookRequest($session, 'GET', '/' . $eventID . '/maybe');
                                $reponseMA = $requestMA->execute();
                                $graphObjectMA = $reponseMA->getGraphObject();
                                $graphObjectMA = $graphObjectMA->asArray();
                                $misschien = count($graphObjectMA, COUNT_RECURSIVE); // tellen.
                                
                                //echo $eventNaam . " " . $eventID . "<br />";
                                //Details evenement ophalen. (Momenteel enkel description, later ook locatie ed.)
                                $requestPrivacy = new FacebookRequest($session, 'GET', '/' . $eventID);
                                $responsePrivacy = $requestPrivacy->execute();
                                $graphObjectPR = $responsePrivacy->getGraphObject();
                                $graphObjectPR = $graphObjectPR->asArray();


                                $beschrijving = $graphObjectPR['description'];
                                echo "Naam: " . $eventNaam . "<br />";
                                echo "Aantal aanwezig: " . $aanwezig . " Mogelijk aanwezig: " . $misschien . "<br />";
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