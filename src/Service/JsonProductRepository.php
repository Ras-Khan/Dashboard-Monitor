<?php
namespace App\Service;

use App\Model\Product;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Custom service to act as a data repository, reading and writing to a JSON file.
 */
class JsonProductRepository
{
    private string $dataFile;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $projectDir = $parameterBag->get('kernel.project_dir');
        $this->dataFile = Path::join($projectDir, 'src', 'Data', 'products.json');
    }

    /**
     * Updates an existing Product in the JSON file by ID.
     */
    public function update(Product $product): void
    {
        $allData = $this->loadRawData();
        foreach ($allData as &$item) {
            if ($item['id'] === $product->id) {
                $item['name'] = $product->name;
                $item['url'] = $product->url;
                $item['currentPrice'] = $product->currentPrice;
                $item['lastUpdated'] = $product->lastUpdated->format(\DateTimeImmutable::ATOM);
                $item['priceHistory'] = $product->priceHistory;
            }
        }
        file_put_contents($this->dataFile, json_encode($allData, JSON_PRETTY_PRINT));
    }

// ...existing code...
    public function findAll(): array
    {
        if (!file_exists($this->dataFile)) {
            return [];
        }

        try {
            $jsonContent = file_get_contents($this->dataFile);
            $data = json_decode($jsonContent, true);
        } catch (\Throwable) {
            return [];
        }

        $products = [];
        foreach ($data as $item) {
            $products[] = new Product(
                id: $item['id'],
                name: $item['name'],
                url: $item['url'],
                currentPrice: (float) $item['currentPrice'],
                lastUpdated: new \DateTimeImmutable($item['lastUpdated']),
                priceHistory: $item['priceHistory'] ?? []
            );
        }

        return $products;
    }

    /**
     * Saves a new Product to the JSON file.
     */
    public function save(Product $newProduct): void
    {
        $products = $this->findAll();
        $allData = $this->loadRawData();

        // 1. Generate a new ID (simple max ID + 1 strategy)
        $newId = 1;
        if (!empty($products)) {
            $newId = max(array_map(fn(Product $p) => $p->id, $products)) + 1;
        }

        // 2. Set the ID and update the timestamp
        $newProduct->id = $newId;
        $newProduct->lastUpdated = new \DateTimeImmutable();

        // 3. Format the new product for JSON storage
        $newProductData = [
            'id' => $newProduct->id,
            'name' => $newProduct->name,
            'url' => $newProduct->url,
            'currentPrice' => $newProduct->currentPrice,
            'lastUpdated' => $newProduct->lastUpdated->format(\DateTimeImmutable::ATOM),
            'priceHistory' => $newProduct->priceHistory,
        ];

        // 4. Add to the raw data array and write back to file
        $allData[] = $newProductData;
        file_put_contents($this->dataFile, json_encode($allData, JSON_PRETTY_PRINT));
    }

    /**
     * Helper to load the raw array data for saving.
     */
    private function loadRawData(): array
    {
        if (!file_exists($this->dataFile)) {
            return [];
        }
        $jsonContent = file_get_contents($this->dataFile);
        $data = json_decode($jsonContent, true);
        return is_array($data) ? $data : [];
    }
}