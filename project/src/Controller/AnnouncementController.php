<?php

namespace App\Controller;

use App\Entity\Announcement;
use App\Form\AnnouncementFormType;
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
        $annoucemments = new Announcement();
        $form = $this->createForm(AnnouncementFormType::class, $annoucemments);
        $form->handleRequest($request);
        
        /** @var \App\Entity\User $user */ 
        $user = $this->getUser();
        $author = $user->getLastname(); 


        if ($form->isSubmitted() && $form->isValid()) {
            $annoucemments->setTitle($form->get('title')->getData());
            $annoucemments->setDescription($form->get('description')->getData());
            $annoucemments->setAuthor($author);
            $annoucemments->setAuthorId($user);
            $annoucemments->setCreatedAt(new \DateTimeImmutable());
            // $annoucemments->setImageFile($form->get('imageFile')->getData());
            // $annoucemments->setImageName($form->get('imageFile')->getData());
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
        $add = $em->getRepository(Announcement::class)->find($id);
        $form = $this->createForm(AnnouncementFormType::class, $add);
        $form->handleRequest($request);

        /** @var \App\Entity\User $user */ 
        $user = $this->getUser();
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
}
