<?php declare(strict_types=1);

namespace Dex\PriceCalculation\Model\Quote\Item;

use Dex\PriceCalculation\Model\Configuration;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Pricing\PriceCurrencyInterface;
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
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        PriceCurrencyInterface $priceCurrency
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->priceCurrency = $priceCurrency;
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
        $newPrice = $this->priceCurrency->convert(
            $productPrice * $priceMultiplier,
            $quoteItem->getStore()
        );

        $quoteItem->setCustomPrice($newPrice);
        $quoteItem->setOriginalCustomPrice($newPrice);
    }
}