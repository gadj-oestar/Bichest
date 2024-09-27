<?php

namespace App\Controller;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WalletController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    // Injection du service EntityManagerInterface
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/wallet', name: 'wallet_index')]
    public function index(): Response
    {
        // Récupération de tous les clients
        $clients = $this->entityManager->getRepository(Client::class)->findAll();

        return $this->render('wallet/index.html.twig', [
            'clients' => $clients,
        ]);
    }

    #[Route('/wallet/{id}', name: 'wallet_show')]
    public function show(Client $client): Response
    {
        return $this->render('wallet/show.html.twig', [
            'client' => $client
        ]);
    }

    // Vous pouvez ajouter d'autres méthodes pour créer, modifier, et supprimer des clients
}
