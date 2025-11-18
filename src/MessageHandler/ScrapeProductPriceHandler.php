<?php

namespace App\MessageHandler;

use App\Message\ScrapeProductPrice;
use App\Service\ProductPriceScraper;
use App\Service\JsonProductRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ScrapeProductPriceHandler
{
    public function __construct(
        private ProductPriceScraper $scraper,
        private JsonProductRepository $repository
    ) {}

    public function __invoke(ScrapeProductPrice $message): void
    {
        $products = $this->repository->findAll();
        foreach ($products as $product) {
            if ($product->id === $message->productId) {
                $scrapedPrice = $this->scraper->scrapePrice($message->url);
                if ($scrapedPrice !== null) {
                    $product->priceHistory[] = [
                        'price' => $product->currentPrice,
                        'timestamp' => $product->lastUpdated->format(\DateTimeImmutable::ATOM)
                    ];
                    $product->currentPrice = $scrapedPrice;
                    $product->lastUpdated = new \DateTimeImmutable();
                    $this->repository->update($product);
                }
                break;
            }
        }
    }
}
