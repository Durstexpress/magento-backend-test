<?php declare(strict_types=1);

namespace Dex\PriceCalculation\Model\Quote\Item;

use Dex\PriceCalculation\Model\Configuration;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote\Item;
use Magento\Quote\Model\Quote\Item\Option;
use Magento\Quote\Model\Quote\Item\OptionFactory;

class PriceCalculation
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var OptionFactory
     */
    private $optionFactory;

    /**
     * @param Configuration $configuration
     * @param OptionFactory $optionFactory
     */
    public function __construct(
        Configuration $configuration,
        OptionFactory $optionFactory
    ) {
        $this->configuration = $configuration;
        $this->optionFactory = $optionFactory;
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

    /**
     * Apply 50% discount on item's price
     *
     * @param Item $quoteItem
     * @throws LocalizedException
     */
    public function applyHalfPriceDiscount(Item $quoteItem): void
    {
        if (null !== $quoteItem->getOptionByCode('discount_applied')) {
            return;
        }

        $quoteItem->setOriginalCustomPrice($quoteItem->getOriginalCustomPrice() / 2);

        /** @var Option $option */
        $option = $this->optionFactory->create();
        $option->setProduct($quoteItem->getProduct());
        $option->setCode('discount_applied');

        $quoteItem->addOption($option);
    }
}