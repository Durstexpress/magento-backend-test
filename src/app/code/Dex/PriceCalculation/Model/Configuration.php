<?php declare(strict_types=1);

namespace Dex\PriceCalculation\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Configuration
{
    public const XML_PATH_PRODUCT_PRICE_MULTIPLIER = 'dex/price_calculation/product_price_multiplier';

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
     * Get price multiplier value from system configuration
     *
     * @return int
     * @throws NoSuchEntityException
     */
    public function getProductPriceMultiplier(): int
    {
        $storeId = $this->storeManager->getStore()->getId();

        return (int)$this->scopeConfig->getValue(
            self::XML_PATH_PRODUCT_PRICE_MULTIPLIER,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}