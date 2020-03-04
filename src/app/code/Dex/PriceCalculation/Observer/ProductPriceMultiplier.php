<?php declare(strict_types=1);

namespace Dex\PriceCalculation\Observer;

use Dex\PriceCalculation\Model\Configuration;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote\Item;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class ProductPriceMultiplier implements ObserverInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * Multiply product price after adding quote item
     *
     * @param Observer $observer
     * @throws NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        $storeId = $this->storeManager->getStore()->getId();
        $priceMultiplier = $this->scopeConfig->getValue(
            Configuration::XML_PATH_PRODUCT_PRICE_MULTIPLIER,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        /** @var Item $quoteItem */
        $quoteItem = $observer->getEvent()->getQuoteItem();

        $productPrice = $quoteItem->getProduct()->getPrice();
        $newPrice = $productPrice * $priceMultiplier;

        $quoteItem->setCustomPrice($newPrice);
        $quoteItem->setOriginalCustomPrice($newPrice);
    }
}