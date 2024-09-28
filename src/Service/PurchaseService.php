<?php namespace App\Service;

use App\Entity\Purchase;
use Doctrine\ORM\EntityManagerInterface;

class PurchaseService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function savePurchase($cryptoId, $amount, $type = 'buy')
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Le montant doit être supérieur à zéro.');
        }

        $purchase = new Purchase();
        $purchase->setCryptoId($cryptoId);
        $purchase->setAmount($amount);
        $purchase->setType($type);
        $purchase->setCreatedAt(new \DateTime());

        $this->entityManager->persist($purchase);
        $this->entityManager->flush();
    }

    public function getPurchases()
    {
        return $this->entityManager->getRepository(Purchase::class)->findAll();
    }

    public function saveSale($cryptoId, $amount)
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Le montant doit être supérieur à zéro.');
        }

        $sale = new Purchase();
        $sale->setCryptoId($cryptoId);
        $sale->setAmount($amount);
        $sale->setType('sell');
        $sale->setCreatedAt(new \DateTime());

        $this->entityManager->persist($sale);
        $this->entityManager->flush();
    }

    public function getSales()
    {
        return $this->entityManager->getRepository(Purchase::class)->findAll();
    }
}
