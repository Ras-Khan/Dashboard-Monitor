<?php

namespace App\Message;

class ScrapeProductPrice
{
    public function __construct(
        public int $productId,
        public string $url
    ) {}
}
