<?php

namespace App\Controller;

use App\Service\CoinMarketCapService;
use App\Service\PurchaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PurchaseController extends AbstractController
{
    private $coinMarketCapService;
    private $purchaseService;

    // Le constructeur permet d'injecter les services nécessaires pour gérer les cryptomonnaies et les achats
    public function __construct(CoinMarketCapService $coinMarketCapService, PurchaseService $purchaseService)
    {
        $this->coinMarketCapService = $coinMarketCapService;
        $this->purchaseService = $purchaseService;
    }

    #[Route('/buy', name: 'app_buy_crypto')]
    public function buy(Request $request): Response
    {
        $currency = 'EUR'; // Devise souhaitée
        $cryptoData = $this->coinMarketCapService->getCryptoData($currency);
        $cryptos = $cryptoData['data'] ?? [];

        if ($request->isMethod('POST')) {
            $cryptoId = $request->request->get('crypto_id');
            $amount = $request->request->get('amount');

            try {
                $this->purchaseService->savePurchase($cryptoId, $amount, 'buy');
                $this->addFlash('success', 'Votre achat a été confirmé.');
                return $this->redirectToRoute('app_purchase_history');
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('purchase/buy.html.twig', [
            'cryptos' => $cryptos,
            'currency' => $currency, // Passer la devise ici
        ]);
    }

    
   

    // Route pour confirmer l'achat (uniquement en POST)
   
    // Route pour la vente de cryptomonnaies
    #[Route('/sell', name: 'app_sell_crypto')]
    public function sell(Request $request): Response
    {
        // On récupère les données des cryptomonnaies à partir de l'API de CoinMarketCap
        $cryptoData = $this->coinMarketCapService->getCryptoData();
        $cryptos = $cryptoData['data'] ?? []; // Utilisation d'un tableau vide par défaut

        // Vérification si la méthode de la requête est POST (soumission du formulaire de vente)
        if ($request->isMethod('POST')) {
            $cryptoId = $request->request->get('crypto_id'); // Récupération de l'ID de la cryptomonnaie sélectionnée
            $amount = $request->request->get('amount'); // Récupération du montant à vendre

            // Recherche de la cryptomonnaie sélectionnée dans la liste
            $selectedCrypto = null;
            foreach ($cryptos as $crypto) {
                if ($crypto['id'] == $cryptoId) {
                    $selectedCrypto = $crypto;
                    break;
                }
            }

            // Si la cryptomonnaie n'est pas trouvée, on affiche un message d'erreur
            if (!$selectedCrypto) {
                $this->addFlash('error', 'Cryptomonnaie non valide.');
                return $this->redirectToRoute('app_sell_crypto');
            }

            // Calcul du prix total basé sur le prix de la cryptomonnaie et le montant à vendre
            $cryptoPrice = $selectedCrypto['quote']['EUR']['price'] ?? 0;
            $totalValue = $cryptoPrice * $amount;

            // On redirige vers la page de confirmation de vente avec les détails
            return $this->render('purchase/confirm_sell.html.twig', [
                'cryptoName' => $selectedCrypto['name'],
                'cryptoSymbol' => $selectedCrypto['symbol'],
                'amount' => $amount,
                'cryptoPrice' => $cryptoPrice,
                'totalValue' => $totalValue,
            ]);
        }

        // Si la requête n'est pas en POST, on affiche le formulaire de vente
        return $this->render('purchase/sell.html.twig', [
            'cryptos' => $cryptos,
        ]);
    }

    // Route pour confirmer la vente (uniquement en POST)
    #[Route('/confirm-sell', name: 'app_confirm_sell', methods: ['POST'])]
    public function confirmSell(Request $request): Response
    {
        // Récupération des informations de la requête
        $cryptoId = $request->request->get('crypto_id');
        $amount = $request->request->get('amount');
        $cryptoPrice = $request->request->get('crypto_price');
        $totalValue = $cryptoPrice * $amount;
    
        // Vous devez créer une méthode dans PurchaseService pour enregistrer la vente
        $this->purchaseService->saveSale($cryptoId, $amount, $cryptoPrice, $totalValue);
    
        // Ajout d'un message de succès et redirection
        $this->addFlash('success', 'Votre vente a été confirmée.');
        return $this->redirectToRoute('app_purchase_history');
    }

    // Route pour consulter l'historique des achats
    #[Route('/history', name: 'app_purchase_history')]
    public function history(): Response
    {
        // Récupération de l'historique des achats depuis le service PurchaseService
        $purchases = $this->purchaseService->getPurchases(); // Vérifiez que cette ligne fonctionne correctement
    
        // Affichage de la vue 'history' avec la liste des achats
        return $this->render('purchase/history.html.twig', [
            'purchases' => $purchases, // Passage de la variable 'purchases' au template
        ]);
    }
}
