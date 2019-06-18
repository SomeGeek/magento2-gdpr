<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Gdpr\Model\Customer\Export\Processor;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Quote\Api\CartRepositoryInterface;
use Opengento\Gdpr\Model\Entity\DataCollectorInterface;
use Opengento\Gdpr\Service\Export\Processor\AbstractDataProcessor;

/**
 * Class QuoteDataProcessor
 */
final class QuoteDataProcessor extends AbstractDataProcessor
{
    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Opengento\Gdpr\Model\Entity\DataCollectorInterface $dataCollector
     */
    public function __construct(
        CartRepositoryInterface $quoteRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        DataCollectorInterface $dataCollector
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        parent::__construct($dataCollector);
    }

    /**
     * @inheritdoc
     */
    public function execute(int $customerId, array $data): array
    {
        $searchCriteria = $this->searchCriteriaBuilder->addFilter('customer_id', $customerId);
        $quoteList = $this->quoteRepository->getList($searchCriteria->create());

        /** @var \Magento\Quote\Model\Quote $quote */
        foreach ($quoteList->getItems() as $quote) {
            $key = 'quote_id_' . $quote->getId();
            $data['quotes'][$key] = $this->collectData($quote);

            /** @var \Magento\Quote\Model\Quote\Address|null $quoteAddress */
            foreach ([$quote->getBillingAddress(), $quote->getShippingAddress()] as $quoteAddress) {
                if ($quoteAddress) {
                    $data['quotes'][$key][$quoteAddress->getAddressType()] = $this->collectData($quoteAddress);
                }
            }
        }

        return $data;
    }
}
