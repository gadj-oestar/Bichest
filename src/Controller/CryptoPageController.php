<?php

namespace App\Controller;

use App\Service\CoinMarketCapService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CryptoPageController extends AbstractController
{
    private $coinMarketCapService;

    public function __construct(CoinMarketCapService $coinMarketCapService)
    {
        $this->coinMarketCapService = $coinMarketCapService;
    }

    #[Route('/page', name: 'app_crypto_page')]
    public function index(): Response
    {
        $cryptoData = $this->coinMarketCapService->getCryptoData();
        $cryptos = $cryptoData['data'] ?? [];

        return $this->render('crypto_page/cryptoPage.html.twig', [
            'cryptos' => $cryptos,
            'currencySymbol' => '€',
        ]);
    }
}
