<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\{City, Province, User};
use App\Form\AddUserForm;
use App\Form\Type\AddUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;

class AddUserController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/dodawanie", name="add_user")
     */
    public function addUserAction(Request $request): Response
    {
        $addUser = $request->request->get('add_user');
        $province = (int) ($addUser['province'] ?? 0);

        $addUserForm = new AddUserForm();
        $addUserForm->setProvince($province);
        $form = $this->createForm(
            AddUserType::class,
            $addUserForm
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User();
            $user->setProvince(
                $this->em->getRepository(Province::class)
                    ->find($addUserForm->getProvince())
            );
            $user->setCity(
                $this->em->getRepository(City::class)
                    ->find($addUserForm->getCity())
            );
            $user->setActive(true);
            $user->setName($addUserForm->getName());
            $user->setSurname($addUserForm->getSurname());
            $user->setDescription((string) $addUserForm->getDescription());
            $user->setRanking(0);
            $user->setNumber(0);
            $user->setIp($request->getClientIp());
            $user->setDate(new \DateTime());
            $this->em->persist($user);

            try {
                $this->em->flush();

                return $this->render(
                    'add_user/user_added_info.html.twig',
                    ['activeMenu' => 'add_user']
                );
            } catch (\Exception $e) {
                return $this->render(
                    'add_user/user_not_added_info.html.twig',
                    ['activeMenu' => 'add_user']
                );
            }
        }

        return $this->render('add_user/add_user.html.twig', [
            'activeMenu' => 'add_user',
            'form' => $form->createView(),
            'selectedCity' => (int) $addUserForm->getCity()
        ]);
    }
}
