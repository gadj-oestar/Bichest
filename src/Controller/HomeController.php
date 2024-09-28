<?php

namespace App\Controller;

use App\Service\CoinMarketCapService; // Importez votre service
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    private CoinMarketCapService $coinMarketCapService; // Service pour récupérer les données des cryptomonnaies

    public function __construct(CoinMarketCapService $coinMarketCapService)
    {
        $this->coinMarketCapService = $coinMarketCapService; // Assignez le service à une propriété
    }

    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        $currency = 'EUR'; // Définir la devise par défaut
        $cryptoData = $this->coinMarketCapService->getCryptoData($currency); // Récupérer les données des cryptomonnaies

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'cryptos' => $cryptoData['data'], // Passez les données des cryptomonnaies à la vue
            'currency' => $currency, // Passez la devise à la vue
        ]);
    }
}
