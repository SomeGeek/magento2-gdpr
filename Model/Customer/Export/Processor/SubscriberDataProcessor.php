<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Gdpr\Model\Customer\Export\Processor;

use Opengento\Gdpr\Model\Entity\DataCollectorInterface;
use Opengento\Gdpr\Model\Newsletter\SubscriberFactory;
use Opengento\Gdpr\Service\Export\Processor\AbstractDataProcessor;

/**
 * Class SubscriberDataProcessor
 */
final class SubscriberDataProcessor extends AbstractDataProcessor
{
    /**
     * @var \Opengento\Gdpr\Model\Newsletter\SubscriberFactory
     */
    private $subscriberFactory;

    /**
     * @param \Opengento\Gdpr\Model\Newsletter\SubscriberFactory $subscriberFactory
     * @param \Opengento\Gdpr\Model\Entity\DataCollectorInterface $dataCollector
     */
    public function __construct(
        SubscriberFactory $subscriberFactory,
        DataCollectorInterface $dataCollector
    ) {
        $this->subscriberFactory = $subscriberFactory;
        parent::__construct($dataCollector);
    }

    /**
     * @inheritdoc
     */
    public function execute(int $customerId, array $data): array
    {
        /** @var \Opengento\Gdpr\Model\Newsletter\Subscriber $subscriber */
        $subscriber = $this->subscriberFactory->create();
        $subscriber->loadByCustomerId($customerId);
        $data['subscriber'] = $this->collectData($subscriber);

        return $data;
    }
}
