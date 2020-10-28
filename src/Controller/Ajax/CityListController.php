<?php

declare(strict_types=1);

namespace App\Controller\Ajax;

use App\Entity\City;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Twig\Environment;

class CityListController extends AbstractController
{
    private EntityManagerInterface $em;
    private Environment $twig;

    public function __construct(EntityManagerInterface $em, Environment $twig)
    {
        $this->em = $em;
        $this->twig = $twig;
    }

    public function cityListAction(Request $request): Response
    {
        $selectId = $request->get('selectId');
        $selectName = $request->get('selectName');
        $selectedProvince = (int) $request->get('selectedProvince');
        $selectedCity = (int) $request->get('selectedCity');

        $cr = $this->em->getRepository(City::class);
        $cityList = $cr->getCityList($selectedProvince);

        $citySelect = $this->twig->render('ajax/_city_select.html.twig', [
            'selectId' => $selectId,
            'selectName' => $selectName,
            'cityList' => $cityList,
            'selectedCity' => $selectedCity
        ]);

        $response = [
            'code' => 100,
            'success' => true,
            'citySelect' => $citySelect
        ];

        return new JsonResponse($response);
    }
}
