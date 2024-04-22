<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Service\Navigator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainPageController extends AbstractController
{
    private EntityManagerInterface $em;
    private Navigator $navigator;

    public function __construct(
        EntityManagerInterface $em,
        Navigator $navigator
    ) {
        $this->em = $em;
        $this->navigator = $navigator;
    }

    #[Route('/', name: 'main_page')]
    public function mainPageAction(int $listLimit, int $levelLimit): Response
    {
        return $this->mainPageLevelAction(1, $listLimit, $levelLimit);
    }

    #[Route('/strona,{level<\d+>?0}', name: 'main_page_level')]
    public function mainPageLevelAction(
        int $level,
        int $listLimit,
        int $levelLimit
    ): Response {
        $ur = $this->em->getRepository(User::class);

        $userList = $ur->getUserList($level, $listLimit);
        $userCount = $ur->getUserCount();
        $pageNavigator = $this->navigator->preparePageNavigator(
            $this->generateUrl('main_page_level', ['level' => 0]) . ',',
            $level,
            $listLimit,
            $userCount,
            $levelLimit
        );

        return $this->render('main_page/main_page.html.twig', [
            'activeMenu' => 'main_page',
            'level' => $level,
            'listLimit' => $listLimit,
            'userList' => $userList,
            'pageNavigator' => $pageNavigator
        ]);
    }
}
