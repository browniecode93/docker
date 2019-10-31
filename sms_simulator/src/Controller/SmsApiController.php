<?php
// src/Controller/SmsApiController.php
namespace App\Controller;

use App\Entity\Api;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Users;
use Symfony\Component\HttpClient\HttpClient;
use Psr\Log\LoggerInterface;
use App\Message\SmsNotification;
use Symfony\Component\Messenger\MessageBusInterface;

// ...


class SmsApiController extends AbstractController
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function send(Request $request, MessageBusInterface $bus)
    {

        $body = $request->query->get('body');
        $number = $request->query->get('number');

        $entityManager = ($this->getDoctrine())->getManager();

        $api = new Api();

        $providers = [
            'http://nginx2:81',
            'http://nginx3:82'
        ];

        $this->logger->info("Getting request for number $number");

        $send_sms_result = 500;

        foreach ($providers as $provider) {
            $send_sms_result = $this->sendSMS($provider, $body, $number);
            $api->setApiCall($provider);
            $api->setApiStatus($send_sms_result);
            $api->setNumber($number);
            $entityManager->persist($api);
            $entityManager->flush();
            if ($send_sms_result == 200) {
                $this->logger->info("Sending request to API $provider for the number $number and result is $send_sms_result");
                break;
            }
            $this->logger->info("Sending request to API $provider for number $number and the result is $send_sms_result");
        }

        if ($send_sms_result === 500) {
            $this->logger->info("Add a job to redis for number $number as servers are down");
            $bus->dispatch(new SmsNotification($body, $number));
        }

        $response = new Response('Sending SMS', 200);
        return $response->send();
    }


    public function sendSMS($backend_url, $body, $number)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', "$backend_url/sms/send?body=$body&number=$number");
        $statusCode = $response->getStatusCode();

        return $statusCode;

    }

}
