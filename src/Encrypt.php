<?php

namespace Hongyi\ApiEncrypt;

use Hongyi\ApiEncrypt\Services\AesEncrypt;
use Hongyi\ApiEncrypt\Services\HmacSign;
use Hongyi\ApiEncrypt\Services\RsaEncrypt;

class Encrypt
{
    public function aes(): AesEncrypt
    {
        return new AesEncrypt();
    }

    public function hmac(): HmacSign
    {
        return new HmacSign();
    }

    public function rsa(): RsaEncrypt
    {
        return new RsaEncrypt();
    }
}