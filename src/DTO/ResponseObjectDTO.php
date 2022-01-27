<?php

namespace App\DTO;

class ResponseObjectDTO
{
    private mixed $count;
    private string $randomKey;
    private string $randomHash;


    /**
     * @param mixed $count
     * @param string $randomKey
     * @param string $randomHash
     */

    public function __construct(mixed $count, string $randomKey, string $randomHash)
    {
        $this->count = $count;
        $this->randomKey = $randomKey;
        $this->randomHash = $randomHash;
    }

    /**
     * @return mixed
     */
    public function getCount(): mixed
    {
        return $this->count;
    }

    /**
     * @return string
     */
    public function getRandomKey(): string
    {
        return $this->randomKey;
    }

    /**
     * @return string
     */
    public function getRandomHash(): string
    {
        return $this->randomHash;
    }

}
