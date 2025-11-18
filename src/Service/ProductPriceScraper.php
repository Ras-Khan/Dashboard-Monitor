<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;

class ProductPriceScraper
{
    public function scrapePrice(string $url): ?float
    {
        $client = HttpClient::create();
        try {
            $response = $client->request('GET', $url);
            $html = $response->getContent();
            $crawler = new Crawler($html);

            // Amazon price selector
            if (strpos($url, 'amazon.') !== false) {
                $priceNode = $crawler->filter('#priceblock_ourprice, #priceblock_dealprice, .a-price .a-offscreen');
                if ($priceNode->count()) {
                    $priceText = $priceNode->first()->text();
                    return $this->parsePrice($priceText);
                }
            }
            // IKEA price selector
            if (strpos($url, 'ikea.') !== false) {
                $priceNode = $crawler->filter('.pip-price__integer, .pip-price__value');
                if ($priceNode->count()) {
                    $priceText = $priceNode->first()->text();
                    return $this->parsePrice($priceText);
                }
            }
        } catch (\Throwable $e) {
            // Log error or handle gracefully
            return null;
        }
        return null;
    }

    private function parsePrice(string $priceText): ?float
    {
        // Remove currency symbols and formatting
        $priceText = preg_replace('/[^\d.,]/', '', $priceText);
        $priceText = str_replace(',', '.', $priceText);
        if (is_numeric($priceText)) {
            return (float)$priceText;
        }
        return null;
    }
}
