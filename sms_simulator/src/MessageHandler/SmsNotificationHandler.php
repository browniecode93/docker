<?php
namespace App\MessageHandler;

use App\Message\SmsNotification;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SmsNotificationHandler implements MessageHandlerInterface
{
    public function __invoke(SmsNotification $message)
    {
        $body = $message->getBody();
        $number = $message->getNumber();

        $client = HttpClient::create();
        $client->request('GET', "http://nginx/sms/send?body=$body&number=$number");

    }

}
