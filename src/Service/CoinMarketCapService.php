<?php namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;


class CoinMarketCapService
{
    private $client; // Client HTTP pour faire les requêtes API
    private $apiKey; // Clé API pour accéder à l'API CoinMarketCap

    // Le constructeur injecte le client HTTP et la clé API (vous devez vous assurer que la clé est injectée correctement depuis vos paramètres de configuration)
    public function __construct(HttpClientInterface $client, string $apiKey)
    {
        $this->client = $client; // On assigne le client HTTP injecté
        $this->apiKey = $apiKey; // On assigne la clé API injectée
    }

    // Méthode pour récupérer les données des cryptomonnaies via l'API CoinMarketCap
    public function getCryptoData(string $currency = 'USD'): array
    {
        try {
            $response = $this->client->request('GET', 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest', [
                'headers' => [
                    'X-CMC_PRO_API_KEY' => $this->apiKey,
                    'Accepts' => 'application/json',
                ],
                'query' => [
                    'start' => 1,
                    'limit' => 10,
                    'convert' => $currency, // Utiliser la devise passée en paramètre
                ],
            ]);
            
            return $response->toArray();
        } catch (TransportExceptionInterface $e) {
            throw new \RuntimeException('Erreur lors de la récupération des données de crypto-monnaie: ' . $e->getMessage());
        }
    }
    
}
