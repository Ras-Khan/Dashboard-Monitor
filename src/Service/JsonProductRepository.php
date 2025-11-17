<?php

namespace App\Service;

use App\Model\Product;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Custom service to act as a data repository, reading from a JSON file.
 */
class JsonProductRepository
{
    private string $dataFile;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        // Use the kernel.project_dir parameter to reliably locate the JSON file
        $projectDir = $parameterBag->get('kernel.project_dir');
        $this->dataFile = Path::join($projectDir, 'src', 'Data', 'products.json');
    }

    /**
     * Reads the JSON file and returns an array of Product models.
     * @return Product[]
     */
    public function findAll(): array
    {
        if (!file_exists($this->dataFile)) {
            return [];
        }

        try {
            $jsonContent = file_get_contents($this->dataFile);
            $data = json_decode($jsonContent, true);
        } catch (\Throwable $e) {
            // Handle JSON parsing or file read error gracefully
            return [];
        }

        $products = [];
        // Map raw array data to the structured Product DTOs
        foreach ($data as $item) {
            $products[] = new Product(
                id: $item['id'],
                name: $item['name'],
                url: $item['url'],
                currentPrice: (float) $item['currentPrice'],
                lastUpdated: new \DateTimeImmutable($item['lastUpdated'])
            );
        }

        return $products;
    }
}