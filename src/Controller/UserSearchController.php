<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserSearchType;
use App\Form\UserSearchForm;
use App\Service\Navigator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

class UserSearchController extends AbstractController
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

    /**
     * @Route("/szukanie", name="user_search")
     */
    public function userSearchAction(
        Request $request,
        int $listLimit,
        int $levelLimit
    ): Response {
        $userSearch = $request->query->get('user_search');
        $province = (int) ($userSearch['province'] ?? 0);
        $level = $request->query->getInt('level', 1);
        $searchUserList = [];
        $pageNavigator = '';

        $userSearchForm = new UserSearchForm();
        $userSearchForm->setProvince($province);
        $form = $this->createForm(
            UserSearchType::class,
            $userSearchForm,
            ['method' => 'GET']
        );
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $requestUri = $request->getRequestUri();
            $levelPosition = strrpos($requestUri, '&level=');
            $formUriLength = ($levelPosition !== false)
                ? $levelPosition : strlen($requestUri);
            $formUri = substr($requestUri, 0, $formUriLength);

            $ur = $this->em->getRepository(User::class);

            $searchUserList = $ur->getSearchUserList(
                (string) $userSearchForm->getName(),
                (string) $userSearchForm->getSurname(),
                $userSearchForm->getProvince(),
                $userSearchForm->getCity(),
                $level,
                $listLimit
            );
            $searchUserCount = $ur->getSearchUserCount(
                (string) $userSearchForm->getName(),
                (string) $userSearchForm->getSurname(),
                $userSearchForm->getProvince(),
                $userSearchForm->getCity()
            );
            $pageNavigator = $this->navigator->preparePageNavigator(
                $formUri . '&level=',
                $level,
                $listLimit,
                $searchUserCount,
                $levelLimit
            );
        }

        return $this->render('user_search/user_search.html.twig', [
            'activeMenu' => 'user_search',
            'form' => $form->createView(),
            'selectedCity' => (int) $userSearchForm->getCity(),
            'searchUserList' => $searchUserList,
            'pageNavigator' => $pageNavigator
        ]);
    }
}
