<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Service\Cache;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class UserDataController extends AbstractController
{
    private EntityManagerInterface $em;
    private Environment $twig;
    private Cache $cache;

    public function __construct(
        EntityManagerInterface $em,
        Environment $twig,
        Cache $cache
    ) {
        $this->em = $em;
        $this->twig = $twig;
        $this->cache = $cache;
    }

    /**
     * @Route("/osoba,{user<\d+>?0}", name="user_data")
     */
    public function userDataAction(
        int $user,
        string $cacheList,
        int $cacheTime,
        int $userLimit
    ): Response {
        $cacheList = str_replace('{user}', $user, $cacheList);
        $cacheFile = '../templates/' . $cacheList;

        if (!$this->em->getRepository(User::class)->isUserData($user)) {
            throw $this->createNotFoundException('No user data found');
        }

        if (
            !file_exists($cacheFile)
            || filemtime($cacheFile) <= time() - $cacheTime
        ) {
            $randomUserList = $this->em->getRepository(User::class)
                ->getRandomUserList($userLimit);
            $content = $this->twig->render(
                'user_data/_user_data_list.html.twig',
                ['randomUserList' => $randomUserList]
            );
            $this->cache->cachePage($cacheFile, $content);
        }

        $this->em->getRepository(User::class)->updateUserNumber($user);
        $this->em->getRepository(User::class)->updateUserRanking($user);
        $userData = $this->em->getRepository(User::class)->getUserData($user);
        $this->em->refresh($userData);

        $title = ($userData->getName() && $userData->getSurname())
            ? $userData->getName() . ' ' . $userData->getSurname() . (
                ($userData->getProvince() || $userData->getCity()) ? ' -' . (
                    ($userData->getCity()) ? ' '
                        . $userData->getCity()->getName() : ''
                ) . (
                    ($userData->getProvince()) ? ' '
                        . $userData->getProvince()->getName() : ''
                ) : ''
            ) : '';

        return $this->render('user_data/user_data.html.twig', [
            'activeMenu' => 'user_data',
            'cacheList' => $cacheList,
            'userData' => $userData,
            'title' => $title
        ]);
    }
}
