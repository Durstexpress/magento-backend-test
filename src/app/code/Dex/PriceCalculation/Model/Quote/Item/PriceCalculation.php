<?php declare(strict_types=1);

namespace Dex\PriceCalculation\Model\Quote\Item;

use Dex\PriceCalculation\Model\Configuration;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote\Item;

class PriceCalculation
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @param Configuration $configuration
     */
    public function __construct(
        Configuration $configuration
    ) {
        $this->configuration = $configuration;
    }

    /**
     * Multiply quote item product's price
     *
     * @param Item $quoteItem
     * @throws NoSuchEntityException
     */
    public function multiplyPrice(Item $quoteItem): void
    {
        $priceMultiplier = $this->configuration->getProductPriceMultiplier();

        $productPrice = $quoteItem->getProduct()->getPrice();
        $newPrice = $productPrice * $priceMultiplier;

        $quoteItem->setCustomPrice($newPrice);
        $quoteItem->setOriginalCustomPrice($newPrice);
    }
}