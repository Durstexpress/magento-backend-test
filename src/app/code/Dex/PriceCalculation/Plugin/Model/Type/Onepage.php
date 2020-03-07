<?php declare(strict_types=1);

namespace Dex\PriceCalculation\Plugin\Model\Type;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Api\CartItemRepositoryInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote\Item\Option;
use Magento\Quote\Model\Quote\Item\OptionFactory;

class Onepage
{
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var CartItemRepositoryInterface
     */
    private $cartItemRepository;

    /**
     * @var OptionFactory
     */
    private $optionFactory;

    public function __construct(
        CartRepositoryInterface $cartRepository,
        CartItemRepositoryInterface $cartItemRepository,
        PriceCurrencyInterface $priceCurrency,
        OptionFactory $optionFactory
    ) {
        $this->cartRepository = $cartRepository;
        $this->cartItemRepository = $cartItemRepository;
        $this->priceCurrency = $priceCurrency;
        $this->optionFactory = $optionFactory;
    }

    /**
     * Apply 50% discount to all quote items
     *
     * @param \Magento\Checkout\Model\Type\Onepage $subject
     * @return array
     */
    public function beforeInitCheckout(\Magento\Checkout\Model\Type\Onepage $subject): array
    {
        $quote = $subject->getQuote();
        foreach ($quote->getItems() as $quoteItem) {
            if (null !== $quoteItem->getOptionByCode('discount_applied')) {
                continue;
            }

            $discountAmount = $quoteItem->getOriginalCustomPrice() / 2;
            $newPrice = $this->priceCurrency->convert(
                $quoteItem->getOriginalCustomPrice() - $discountAmount,
                $quote->getStore()
            );

            $quoteItem->setOriginalCustomPrice($newPrice);

            /** @var Option $option */
            $option = $this->optionFactory->create();
            $option->setProduct($quoteItem->getProduct());
            $option->setCode('discount_applied');

            $quoteItem->addOption($option);
        }

        $quote->setTotalsCollectedFlag(false);
        $quote->collectTotals();

        $this->cartRepository->save($quote);

        return [];
    }
}