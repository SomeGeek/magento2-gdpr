<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Gdpr\Model\Customer\Delete\Processor;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Opengento\Gdpr\Service\Erase\ProcessorInterface;

/**
 * Class OrderDataProcessor
 */
final class OrderDataProcessor implements ProcessorInterface
{
    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @inheritdoc
     */
    public function execute(int $customerId): bool
    {
        $searchCriteria = $this->searchCriteriaBuilder->addFilter(OrderInterface::CUSTOMER_ID, $customerId);
        $orderList = $this->orderRepository->getList($searchCriteria->create());

        foreach ($orderList->getItems() as $order) {
            $this->orderRepository->delete($order);
        }

        return true;
    }
}
