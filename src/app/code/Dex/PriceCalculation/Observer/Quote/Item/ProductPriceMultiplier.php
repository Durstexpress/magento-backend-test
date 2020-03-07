<?php declare(strict_types=1);

namespace Dex\PriceCalculation\Observer\Quote\Item;

use Dex\PriceCalculation\Model\Quote\Item\PriceCalculation;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote\Item;

class ProductPriceMultiplier implements ObserverInterface
{
    /**
     * @var PriceCalculation
     */
    private $priceCalculation;

    /**
     * @param PriceCalculation $priceCalculation
     */
    public function __construct(
        PriceCalculation $priceCalculation
    ) {
        $this->priceCalculation = $priceCalculation;
    }

    /**
     * Multiply product price after adding quote item
     *
     * @param Observer $observer
     * @throws NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        /** @var Item $quoteItem */
        $quoteItem = $observer->getEvent()->getQuoteItem();
        $this->priceCalculation->multiplyPrice($quoteItem);
    }
}