<?php

namespace App\Controller;

use App\Entity\Announcement;
use App\Form\AnnouncementFormType;
use App\Form\DeleteAnnouncementType;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AnnouncementController extends AbstractController
{
    #[Route('/announcement', name: 'app_announcement')]
    public function index(EntityManagerInterface $em): Response
    {
        $repository = $em->getRepository(Announcement::class);
        $annoucemments = $repository->findAll();
        return $this->render('announcement/index.html.twig', [
            'controller_name' => 'AnnouncementController',
            'announcements' => $annoucemments ,
        ]);
    
    }
    #[Route('/newAnnouncement', name: 'create_announcement')]
    public function add(EntityManagerInterface $em, Request $request): Response
    {
        /** @var \App\Entity\User $user */ 
        $user = $this->getUser();

        if(!$user){
            return $this->redirectToRoute('app_login');
        }

        $annoucemments = new Announcement();
        $form = $this->createForm(AnnouncementFormType::class, $annoucemments);
        $form->handleRequest($request);
        $author = $user->getLastname(); 


        if ($form->isSubmitted() && $form->isValid()) {
            $annoucemments->setTitle($form->get('title')->getData());
            $annoucemments->setDescription($form->get('description')->getData());
            $annoucemments->setAuthor($author);
            $annoucemments->setAuthorId($user);
            $annoucemments->setCreatedAt(new \DateTimeImmutable());
            $annoucemments->setCategory($form->get('category')->getData());

            $em->persist($annoucemments);
            $em->flush();   

            return $this->redirectToRoute('app_announcement');
        }

        return $this->render('announcement/add.html.twig', [
            'announcementForm' => $form,
            'formTitle' => 'Ajouter une annonce !',
            'author' => $author,
            'buttonText' => 'Créer mon annonce'
        ]);
    }
    #[Route('/announcementUpdate/{id}', name: 'update_announcement')]
    public function update(EntityManagerInterface $em, Request $request, int $id): Response
    {
        /** @var \App\Entity\User $user */ 
        $user = $this->getUser();

        if(!$user){
            return $this->redirectToRoute('app_login');
        }

        $add = $em->getRepository(Announcement::class)->find($id);
        $form = $this->createForm(AnnouncementFormType::class, $add);
        $form->handleRequest($request);
        $author = $user->getLastname(); 

        if ($form->isSubmitted() && $form->isValid()) {
            $add->setTitle($form->get('title')->getData());
            $add->setDescription($form->get('description')->getData());
            $add->setAuthor($author);
            $add->setAuthorId($user);
            $image = $form->get('imageFile')->getData();
            if($image){
                $add->setImageFile($image);
                
            }
            $add->setCategory($form->get('category')->getData());
 
            $em->flush();   

            return $this->redirectToRoute('app_announcement');
        }

        return $this->render('announcement/add.html.twig', [
            'announcementForm' => $form,
            'formTitle' => 'Mettre à jour l\'annonce.',
            'author' => $author,
            'add' => $add,
            'buttonText' => 'Mettre à jour'
        ]);
    }

    #[Route('/announcement/{id}', name: 'details_announcement')]
    public function seeOne(EntityManagerInterface $em, int $id): Response
    {
        $annoucemment = $em->getRepository(Announcement::class)->find($id);  

        return $this->render('announcement/details.html.twig', [
            'announcement' => $annoucemment
        ]);
    }

    #[Route('/announcement/delete/{id}', name: 'delete_announcement')]
    public function deleteOne(EntityManagerInterface $em, int $id, Request $request): Response
    {
        /** @var \App\Entity\User $user */ 
        $user = $this->getUser();
        if(!$user){
            return $this->redirectToRoute('app_login');
        }
        $userId = $user->getId();
        $announcement = $em->getRepository(Announcement::class)->find($id); 
        $deleteAnnouncementForm = $this->createForm(DeleteAnnouncementType::class, $announcement);
        $deleteAnnouncementForm->handleRequest($request);

        if ($deleteAnnouncementForm->isSubmitted() && $deleteAnnouncementForm->isValid()){
            if ($user->getId() != $announcement->getAuthorId()->getId()) {
                $this->addFlash('notice', 'Vous n\'avez pas les droits pour effectuer cette action !');
            }
            else{
                $this->addFlash('notice', 'Votre annonce à bien était supprimé !');
                $em->remove($announcement);
                $em->flush();
            }
        }

        return $this->redirectToRoute('app_user', ['id' => $userId]);
    }

    #[Route('/announcement/deleteAll', name: 'delete_announcement')]
    public function deleteAll(EntityManagerInterface $em, int $id, Request $request): Response
    {

        /** @var \App\Entity\User $user */ 
        $user = $this->getUser();
        if(!$user){
            return $this->redirectToRoute('app_login');
        }
        $userId = $user->getId();
        $announcement = $em->getRepository(Announcement::class)->findAll($id); 
        $deleteAnnouncementForm = $this->createForm(DeleteAnnouncementType::class, $announcement);
        $deleteAnnouncementForm->handleRequest($request);

        if ($deleteAnnouncementForm->isSubmitted() && $deleteAnnouncementForm->isValid()){
            if ($user->getId() != $announcement[0]->getAuthorId()->getId()) {
                $this->addFlash('notice', 'Vous n\'avez pas les droits pour effectuer cette action !');
            }
            else{
                $this->addFlash('notice', 'Toutes les annonces ont étaient supprimés');
                $em->getRepository(Announcement::class)->deleteAllByAuthorId($userId);
            }
        }

        return $this->redirectToRoute('app_user', ['id' => $userId]);
    }
}
