<?php

namespace App\Entity;

use App\Repository\SellRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SellRepository::class)]
class Sell
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $cryptoId = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime(); // Initialise createdAt
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCryptoId(): ?string
    {
        return $this->cryptoId;
    }

    public function setCryptoId(string $cryptoId): static
    {
        $this->cryptoId = $cryptoId;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('Sell ID: %d, Crypto ID: %s, Amount: %.2f, Created At: %s', 
            $this->id, 
            $this->cryptoId, 
            $this->amount, 
            $this->createdAt->format('Y-m-d H:i:s')
        );
    }
}
