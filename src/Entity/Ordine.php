<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\OrdineRepository")]
class Ordine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $OrderID;

    #[ORM\Column(type: "string", length: 255)]
    private $Nome;

    #[ORM\Column(type: "string", length: 255)]
    private $Cognome;

    #[ORM\Column(type: "datetime")]
    private $Data;

    #[ORM\Column(type: "integer")]
    private $ProductID;

    
    // Getter e Setter per Nome
    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    // Getter e Setter per Cognome
    public function getCognome(): ?string
    {
        return $this->cognome;
    }

    public function setCognome(string $cognome): self
    {
        $this->cognome = $cognome;

        return $this;
    }

    // Getter e Setter per Data
    public function getData(): ?\DateTimeInterface
    {
        return $this->data;
    }

    public function setData(\DateTimeInterface $data): self
    {
        $this->data = $data;

        return $this;
    }

    // Getter e Setter per ProductId
    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    public function __construct()
    {
        $this->data = new \DateTime();
    }
}
