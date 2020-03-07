<?php declare(strict_types=1);

namespace Dex\PriceCalculation\Model\Quote\Item;

use Dex\PriceCalculation\Model\Configuration;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote\Item;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class PriceCalculation
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * Multiply quote item product's price
     *
     * @param Item $quoteItem
     * @throws NoSuchEntityException
     */
    public function multiplyPrice(Item $quoteItem): void
    {
        $storeId = $this->storeManager->getStore()->getId();
        $priceMultiplier = $this->scopeConfig->getValue(
            Configuration::XML_PATH_PRODUCT_PRICE_MULTIPLIER,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        $productPrice = $quoteItem->getProduct()->getPrice();
        $newPrice = $productPrice * $priceMultiplier;

        $quoteItem->setCustomPrice($newPrice);
        $quoteItem->setOriginalCustomPrice($newPrice);
    }
}