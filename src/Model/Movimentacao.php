<?php

namespace App\Model;

class Movimentacao
{
    private int $id;
    private int $pessoaId;
    private ?int $pessoaDestinoId;
    private string $tipo;
    private float $valor;
    private ?string $createdAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getPessoaId(): int
    {
        return $this->pessoaId;
    }

    public function setPessoaId(int $pessoaId): void
    {
        $this->pessoaId = $pessoaId;
    }

    public function getPessoaDestinoId(): ?int
    {
        return $this->pessoaDestinoId;
    }

    public function setPessoaDestinoId(?int $pessoaDestinoId): void
    {
        $this->pessoaDestinoId = $pessoaDestinoId;
    }

    public function getTipo(): string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): void
    {
        $this->tipo = $tipo;
    }

    public function getValor(): float
    {
        return $this->valor;
    }

    public function setValor(float $valor): void
    {
        $this->valor = $valor;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}