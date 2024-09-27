<?php

namespace App\Infrastructure\Mercure;

use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;

class MercureCookieGenerator
{
    public function __construct(readonly private string $mercureKey)
    {
    }
    function generate(): string
    {
        $builder = new Builder(new JoseEncoder(), ChainedFormatter::default());
        $token = $builder->withClaim("mercure", [
            "subscribe" => ["/message"]
        ])->getToken(new Sha256, InMemory::plainText($this->mercureKey));
        return $token->toString();
        // return sprintf("mercureAuthorization=%s, Path=/.well-known/mercure; HttpOnly", $token->toString());
    }
}