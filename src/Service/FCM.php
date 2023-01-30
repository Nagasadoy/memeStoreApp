<?php

namespace App\Service;

use Google\Auth\ApplicationDefaultCredentials;
use Google\Service\FirebaseCloudMessaging\FcmOptions;
use Google\Service\FirebaseCloudMessaging\WebpushConfig;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Kreait\Firebase\Contract\AppCheck;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class FCM
{

    private string $googleServiceCredentialFile;

    public function __construct(string $googleServiceCredentialFile)
    {
        $this->googleServiceCredentialFile = $googleServiceCredentialFile;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send(): void
    {
        $factory = (new Factory())
            ->withServiceAccount($this->googleServiceCredentialFile)
            ->withDatabaseUri('https://mytestfcm-7404d-default-rtdb.firebaseio.com');

        $cloudMessaging = $factory->createMessaging();

        $message = CloudMessage::withTarget('token', 'cUGFHwD9QeD9bJLQ8uPzrn:APA91bGQhHiumvfiCDdPuxSh4PugSzx8ulOR3zMfDkfylEEO6M0bDcUOtbf5iKFU9ECt8NwVQvhV3EF-qdO7af7Dun_qMrQJ2QohsovdB15qsUmimB5V1eDQvnsB164tunPdsV4toQMH')
            ->withNotification(Notification::create('fsdfsdfsd', 'fvxcvcxvxcvxvcxvx'));

        try {
            $response = $cloudMessaging->send($message);
            dd($response);
        } catch (\Throwable $exception) {
            dd($exception);
        }
    }

        // $appCheckTokenString = '';
        //
        // $token = $this->appCheck->createToken();
        //
        // try {
        //    $this->appCheck->verifyToken($appCheckTokenString);
        // } catch (FailedToVerifyToken $e) {
        //    dd($e);

        // $scopes = [
        //    'https://www.googleapis.com/auth/userinfo.email',
        //    'https://www.googleapis.com/auth/firebase.database',
        // ];
        //
        // putenv('GOOGLE_APPLICATION_CREDENTIALS='.$this->googleServiceCredentialFile);
        //
        // $client = new \Google_Client();  // Specify the CLIENT_ID of the app that accesses the backend
        // $client->useApplicationDefaultCredentials();
        // $client->addScope($scopes);
        // $client->useApplicationDefaultCredentials();
        // $accessToken = $client->getAccessToken();
        //
        // echo $accessToken['access_token']; die;
        //
        // $response = $this->client->request(
        //    'POST',
        //    'https://fcm.googleapis.com/v1/projects/mytestfcm-7404d/messages:send',
        //    [
        //        'auth_bearer' => $accessToken['access_token'],
        //        'body' => [
        //            'message' => [
        //                'token' => 'token',
        //                'notification' => [
        //                    'body' => 'This is an FCM notification message!',
        //                    'title' => 'FCM message',
        //                ],
        //            ],
        //        ],
        //    ]
        // );
        //
        // dd($response->getContent());

    // $middleware = ApplicationDefaultCredentials::getMiddleware($scopes);
    // $stack = HandlerStack::create();
    // $stack->push($middleware);

//    // create the HTTP client
// $client = new Client([
// 'handler' => $stack,
// 'base_uri' => 'https://www.googleapis.com',
// 'auth' => 'google_auth'  // authorize all requests
// ]);
//
//    // make the request
// $response = $client->get('drive/v2/files');
//
// // show the result!
// print_r((string)$response->getBody());
//
//
// $response = $this->client->request(
// 'POST',
// 'https://fcm.googleapis.com/v1/projects/mytestfcm-7404d/messages:send',
// [
// 'auth_basic' => [
// 'key' => '4beb1b2f88502fe044ec00867167499b5183a7dd'
// ],
// 'body' => [
// 'message' => [
// 'token' => 'token',
// 'notification' => [
// 'body' => 'This is an FCM notification message!',
// 'title' => 'FCM message'
// ]
// ]
// ]
// ]
// );
//
// dd($response->getContent());

    // function sendNotification()
    // {
    //    $url = "https://fcm.googleapis.com/fcm/send";
    //
    //    $fields = array(
    //        "to" => $_REQUEST['token'],
    //        "notification" => array(
    //            "body" => $_REQUEST['message'],
    //            "title" => $_REQUEST['title'],
    //            "icon" => $_REQUEST['icon'],
    //            "click_action" => "https://shinerweb.com"
    //        )
    //    );
    //
    //    $headers = array(
    //        'Authorization: key=BEYcksyho6ja89uXckuYjzcBZgGtEKZCLYgtmQEUMp8JcrsOhG10VzMsTG4d9NQ-ylfkZ8M2pHwi0I3KFSz_9Ts',
    //        'Content-Type:application/json'
    //    );
    //
    //    $ch = curl_init();
    //    curl_setopt($ch, CURLOPT_URL, $url);
    //    curl_setopt($ch, CURLOPT_POST, true);
    //    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    //    $result = curl_exec($ch);
    //    print_r($result);
    //    curl_close($ch);
    // }
}
