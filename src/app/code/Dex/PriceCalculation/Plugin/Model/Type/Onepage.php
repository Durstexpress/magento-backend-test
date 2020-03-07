<?php declare(strict_types=1);

namespace Dex\PriceCalculation\Plugin\Model\Type;

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
     * @var OptionFactory
     */
    private $optionFactory;

    public function __construct(
        CartRepositoryInterface $cartRepository,
        OptionFactory $optionFactory
    ) {
        $this->cartRepository = $cartRepository;
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

            $quoteItem->setOriginalCustomPrice($quoteItem->getOriginalCustomPrice() / 2);

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