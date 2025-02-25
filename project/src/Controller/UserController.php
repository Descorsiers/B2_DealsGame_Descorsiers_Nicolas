<?php

namespace App\Controller;

use App\Entity\Announcement;
use App\Entity\User;
use App\Form\ChangeUserInformationsType;
use App\Form\DeleteAllAnnouncementType;
use App\Form\DeleteAnnouncementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/user/{id}', name: 'app_user')]
    public function index(EntityManagerInterface $em, int $id, Request $request): Response
    {
        $announcement = new  Announcement();
        $user = $em->getRepository(User::class)->find($id);
        $updateForm = $this->createForm(ChangeUserInformationsType::class, $user);
        $deleteAnnouncementForm = $this->createForm(DeleteAnnouncementType::class, $announcement);
        $updateForm->handleRequest($request);

        if ($updateForm->isSubmitted() && $updateForm->isValid()) {
            $user->setLastName($updateForm->get('lastname')->getData());
            $user->setFirstName($updateForm->get('firstname')->getData());
            $user->setEmail($updateForm->get('email')->getData());

            $em->flush();

            return $this->redirectToRoute('app_user', ['id' => $id]);
        }

        return $this->render('user/index.html.twig', [
            'userForm' => $updateForm,
            'deleteAnnouncementForm' => $deleteAnnouncementForm,
            'user' => $user

        ]);
    }
}
