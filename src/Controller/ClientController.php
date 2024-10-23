<?php

namespace App\Controller;


use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Client;
use App\Form\ClientType;




class ClientController extends AbstractController
{
    #[Route('/client', name: 'app_client')]
    public function index(): Response
    {
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }


    #[Route('/showClient', name: 'showClient')]
    public function showClient(ClientRepository $client): Response
    {
        
        $list=$client->findAll();
        return $this->render('client/showClient.html.twig', 
            ['list'=>$list]);
        ;
    }
 

    #[Route('/addclient', name: 'app_addclient')]
    public function addclient(ManagerRegistry $m, Request $req): Response
    {
        $manager= $m->getManager();
        $client= new Client();
        $form= $this->createForm(ClientType::class,$client);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $client->setValidation(true);
            $manager->persist($client);
            $manager->flush();
            return $this->redirectToRoute('showClient');
        }
        return $this->render('client/addclient.html.twig', [
            'clientform' => $form,
        ]);
    }

    #[Route("/delete/{id}", name: "app_delete_Client")]
    public function deletelibrary(int $id, ClientRepository $clientRepository, ManagerRegistry $doctrine): Response
    {
        
        $client = $clientRepository->find($id);
    
    
        $em = $doctrine->getManager();
        $em->remove($client); 
        $em->flush(); 
    
        return $this->redirectToRoute("showClient");
    }
    
}
