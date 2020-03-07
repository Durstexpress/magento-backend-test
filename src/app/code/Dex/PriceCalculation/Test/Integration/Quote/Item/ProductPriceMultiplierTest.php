<?php declare(strict_types=1);

namespace Dex\PriceCalculation\Test\Integration\Quote\Item;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Item;
use PHPUnit\Framework\TestCase;
use Magento\TestFramework\Helper\Bootstrap;

class ProductPriceMultiplierTest extends TestCase
{
    /**
     * @magentoAppIsolation enabled
     * @magentoAppArea frontend
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     * @magentoConfigFixture current_store dex/price_calculation/product_price_multiplier 4
     * @return void
     */
    public function testProductPriceMultiplicationAfterAddingToCart(): void
    {
        $priceMultiplierValue = 4;

        $objectManager = Bootstrap::getObjectManager();

        $productRepository = $objectManager->create(ProductRepositoryInterface::class);
        $product = $productRepository->get('simple');

        $quoteItem = $objectManager->create(Item::class);
        $quoteItem->setProduct($product);

        $quote = $objectManager->create(Quote::class);
        $quote->addItem($quoteItem);

        $this->assertEquals($product->getPrice() * $priceMultiplierValue, $quoteItem->getCalculationPrice());
    }
}