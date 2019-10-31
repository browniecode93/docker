<?php
// src/Controller/GetReportController.php
namespace App\Controller;

use App\Entity\Api;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetReportController extends AbstractController
{

    public function generateReport()
    {
        $api_repository = $this->getDoctrine()
            ->getRepository(Api::class);
        $sms_total_number = $api_repository
            ->findAllSuccessfulSent();

        $api_one_usage = $api_repository
            ->findApiUsage('81');

        $api_two_usage = $api_repository
            ->findApiUsage('82');

        $api_one_failure = $api_repository
            ->findApiFailure('81');

        $api_two_failure = $api_repository
            ->findApiFailure('82');

        $top_ten = $api_repository
            ->findTopTen();


        // the template path is the relative file path from `templates/`
        return $this->render('report.html.twig', [
            'sms_total_number' => $sms_total_number,
            'api_one_usage' => $api_one_usage,
            'api_two_usage' => $api_two_usage,
            'api_one_failure' => $api_one_failure,
            'api_two_failure' => $api_two_failure,
            'top_ten' => $top_ten
        ]);
    }

}