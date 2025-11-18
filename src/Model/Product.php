<?php

namespace App\Model;

use DateTimeImmutable;

/**
 * Data Transfer Object (DTO) for a Product.
 * Used to define the structure of the data loaded from the JSON file.
 */
class Product
{
    public function __construct(
        public ?int $id, // Now nullable for new products
        public string $name,
        public ?string $url,
        public float $currentPrice,
        public DateTimeImmutable $lastUpdated,
        public array $priceHistory = [] // Array of [price, timestamp]
    ) {}
}