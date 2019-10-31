<?php


namespace App;


use App\Entity\Api;
use App\Entity\Users;
use App\Message\SmsNotification;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Messenger\MessageBusInterface;

class SmsSender
{
    protected $em;
    public function __construct($em)
    {
        $this->em = $em;
    }
    /**
     * @param MessageBusInterface $bus
     * @param $number
     * @param $body
     */
    public function smsSendAttempt(MessageBusInterface $bus, $number, $body): void
    {
        $entityManager = $this->em;

        $user = new Users();
        $user->setPhoneNumber($number);
        $user->setSmsBody($body);
        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($user);
        $entityManager->flush();

        $api = new Api();

        $providers = [
            'http://nginx2:81',
            'http://nginx3:82'
        ];

//        $api->setApiCall($providers[0]);
//        $api->setApiStatus('Pending');
//        $entityManager->persist($api);
//        $entityManager->flush();

        $api1_result = $this->sendSMS($providers[0], $body, $number);
        $api->setApiCall($providers[0]);
        $api->setApiStatus($api1_result);
        $entityManager->persist($api);
        $entityManager->flush();


        if ($api1_result === 500) {
            $api2_result = $this->sendSMS($providers[1], $body, $number);
            $api = new Api();
            $api->setApiCall($providers[1]);
            $api->setApiStatus($api2_result);
            $entityManager->persist($api);
            $entityManager->flush();
            if ($api2_result === 500) {
                $bus->dispatch(new SmsNotification($body, $number));
            }
        }
    }




    public function sendSMS($backend_url, $body, $number)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', "$backend_url/sms/send?body=$body&number=$number");
        $statusCode = $response->getStatusCode();

        return $statusCode;

    }
}