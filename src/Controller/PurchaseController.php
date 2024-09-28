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
    #[Route('/sell', name: 'app_sell_crypto')]
    public function sell(Request $request): Response
    {
        $currency = 'EUR'; // Devise souhaitée
        $cryptoData = $this->coinMarketCapService->getCryptoData($currency);
        $cryptos = $cryptoData['data'] ?? [];
    
        if ($request->isMethod('POST')) {
            $cryptoId = $request->request->get('crypto_id');
            $amount = $request->request->get('amount');
    
            // Validation des entrées
            if ($cryptoId && is_numeric($amount) && $amount > 0) {
                try {
                    $this->purchaseService->saveSale($cryptoId, $amount); // Enregistrer la vente
                    $this->addFlash('success', 'Votre vente a été confirmée.'); // Ajouter un message flash
                    return $this->redirectToRoute('app_sale_history'); // Rediriger vers l'historique des ventes
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors de la vente : ' . $e->getMessage());
                }
            } else {
                $this->addFlash('error', 'Quantité invalide ou cryptomonnaie non sélectionnée.');
            }
        }
    
        return $this->render('purchase/sell.html.twig', [
            'cryptos' => $cryptos,
            'currency' => $currency, // Passer la devise au template
        ]);
    }
    

    #[Route('/sale-history', name: 'app_sale_history')]
    public function saleHistory(): Response
    {
        $sales = $this->purchaseService->getSales();
        return $this->render('purchase/historySell.html.twig', [
            'sales' => $sales,
        ]);
    }

    
}