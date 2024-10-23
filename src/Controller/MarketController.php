<?php

namespace App\Controller;
use App\Repository\MarketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Market;
use App\Form\MarketType;

class MarketController extends AbstractController
{
    #[Route('/market', name: 'app_market')]
    public function index(): Response
    {
        return $this->render('market/index.html.twig', [
            'controller_name' => 'MarketController',
        ]);
    }

    #[Route('/showmarket', name: 'app_showmarket')]
    public function showclient(MarketRepository $marketrep): Response
    {
        $marketdb= $marketrep->findAll();
        return $this->render('market/showmarket.html.twig', [
            'tabmarket' => $marketdb,
        ]);
    }

    #[Route('/addmarket', name: 'app_addmarket')]
    public function addformmarket( ManagerRegistry $managerRegistry, Request $req): Response   //injection dependance (reposotory)
    {
        $em=$managerRegistry->getManager();
        $market = new Market();
        $form=$this->createForm(MarketType::class,$market);
        $form->handleRequest($req);
        if($form->isSubmitted()&& $form->isValid()){  //controlle de saisie dans l'entite (isValid concatination entre entity and code php twig)
            $em->persist($market);
            $em->flush();
            return $this->redirectToRoute('app_showmarket');
         }
        
            return $this->render('market/addmarket.html.twig', [
                'marketform' => $form,
            ]);
        
    }

    #[Route('/updatemarket{id}', name: 'app_updatemarket')]
    public function addmarket(MarketRepository $marketrep,ManagerRegistry $m, Request $req,$id): Response
    {
        $manager= $m->getManager();
        $market= $marketrep->Find($id);
        $form= $this->createForm(MarketType::class,$market);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($market);
            $manager->flush();
            return $this->redirectToRoute('app_showmarket');
        }
        return $this->render('market/addmarket.html.twig', [
            'marketform' => $form,
        ]);
    }

    #[Route("/search/{name}",name:'asearch')]
    public function searchByName(MarketRepository $repository,Request $request ):Response
    {  
        $name=$request->get('name');
       
        $market=$repository->findByName($name);
      
        return $this->render('market/search.html.twig',
        ['list'=>$market]);

    }
}
