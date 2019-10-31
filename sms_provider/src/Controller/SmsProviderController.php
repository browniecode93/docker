<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class SmsProviderController extends AbstractController
{
    public function send_sms_to_users(Request $request)
    {
        if(round(rand(0,1), 0,PHP_ROUND_HALF_EVEN)) {
            $response = new Response("Sending fail", 500);
            return $response->send();
        }
        $response = new Response("Sending successfully", 200);
        return $response->send();
    }


}
