<?php

namespace App\Controller;

use App\Repository\CryptoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CryptoPageController extends AbstractController
{
    #[Route('/page', name: 'app_crypto_page')]
    public function index(CryptoRepository $cryptoRepository): Response
    {
        // Récupérer toutes les crypto-monnaies depuis le repository
        $cryptos = $cryptoRepository->findAll();

        // Passer les crypto-monnaies à la vue
        return $this->render('crypto_page/cryptoPage.html.twig', [
            'cryptos' => $cryptos,
            'controller_name' => 'CryptoPageController',
        ]);
    }
}

