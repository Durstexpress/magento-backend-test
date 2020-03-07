<?php declare(strict_types=1);

namespace Dex\PriceCalculation\Plugin\Model\Type;

use Dex\PriceCalculation\Model\Quote\Item\PriceCalculation;
use Magento\Checkout\Model\Type\Onepage as OnepageOriginalModel;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote\Item\OptionFactory;

class Onepage
{
    /**
     * @var PriceCalculation
     */
    private $priceCalculation;

    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @param PriceCalculation $priceCalculation
     * @param CartRepositoryInterface $cartRepository
     */
    public function __construct(
        PriceCalculation $priceCalculation,
        CartRepositoryInterface $cartRepository
    ) {
        $this->priceCalculation = $priceCalculation;
        $this->cartRepository = $cartRepository;
    }

    /**
     * Apply 50% discount on all quote items
     *
     * @param OnepageOriginalModel $subject
     * @return array
     * @throws LocalizedException
     */
    public function beforeInitCheckout(OnepageOriginalModel $subject): array
    {
        $quote = $subject->getQuote();
        foreach ($quote->getItems() as $quoteItem) {
            $this->priceCalculation->applyHalfPriceDiscount($quoteItem);
        }

        $quote->setTotalsCollectedFlag(false);
        $quote->collectTotals();

        $this->cartRepository->save($quote);

        return [];
    }
}