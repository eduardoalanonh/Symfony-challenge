<?php

namespace App\Services;

use App\DTO\ResponseObjectDTO;
use Symfony\Component\String\ByteString;
use function Symfony\Component\String\u;


class MakeHashService
{

    public function makeHash($input): ResponseObjectDTO
    {
        $count = 0;
        do {
            $count++;
            $randomKey = ByteString::fromRandom(8)->toCodePointString();
            $randomHash = md5($input . $randomKey->toString());

        } while (!u($randomHash)->startsWith('0000'));

        return new ResponseObjectDTO(count: $count, randomKey: $randomKey->toString(), randomHash: $randomHash);
    }

}
