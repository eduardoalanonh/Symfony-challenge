<?php

namespace App\Entity;

use App\Repository\HashRepository;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

#[ORM\Entity(repositoryClass: HashRepository::class)]
class Hash implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $block_number;

    #[ORM\Column(type: 'string', length: 255)]
    private $input_string;

    #[ORM\Column(type: 'string', length: 255)]
    private $key_found;

    #[ORM\Column(type: 'string', length: 255)]
    private $hash;

    #[ORM\Column(type: 'integer')]
    private $attempts;

    #[ORM\Column(type: 'datetimetz')]
    private $time_stamp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBlockNumber(): ?int
    {
        return $this->block_number;
    }

    public function setBlockNumber(int $block_number): self
    {
        $this->block_number = $block_number;

        return $this;
    }

    public function getInputString(): ?string
    {
        return $this->input_string;
    }

    public function setInputString(string $input_string): self
    {
        $this->input_string = $input_string;

        return $this;
    }

    public function getKeyFound(): ?string
    {
        return $this->key_found;
    }

    public function setKeyFound(string $key_found): self
    {
        $this->key_found = $key_found;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getAttempts(): ?int
    {
        return $this->attempts;
    }

    public function setAttempts(int $attempts): self
    {
        $this->attempts = $attempts;

        return $this;
    }

    public function getTimeStamp(): ?\DateTimeInterface
    {
        return $this->time_stamp;
    }

    public function setTimeStamp(\DateTimeInterface $time_stamp): self
    {
        $this->time_stamp = $time_stamp;

        return $this;
    }

    #[ArrayShape(['input_string' => "int|string", 'batch' => "\DateTimeInterface|null", 'attempts' => "int|null", 'hash' => "null|string", 'key_found' => "null|string", 'block_number' => "int|null"])] #[Pure]
    public function jsonSerialize(): array
    {
        return [
            'batch' => $this->getTimeStamp()->format('Y-m-d h:i:s'),
            'block_number' => $this->getBlockNumber(),
            'attempts' => $this->getAttempts(),
            'input_string' => $this->getInputString(),
            'key_found' => $this->getKeyFound(),
        ];
    }
}
