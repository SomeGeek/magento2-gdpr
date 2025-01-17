<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Gdpr\Controller\Privacy;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Phrase;
use Opengento\Gdpr\Api\EraseCustomerCheckerInterface;
use Opengento\Gdpr\Controller\AbstractPrivacy;
use Opengento\Gdpr\Model\Config;

/**
 * Action Index Erase
 */
class Erase extends AbstractPrivacy
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $session;

    /**
     * @var \Opengento\Gdpr\Api\EraseCustomerCheckerInterface
     */
    private $eraseCustomerChecker;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Opengento\Gdpr\Model\Config $config
     * @param \Magento\Customer\Model\Session $session
     * @param \Opengento\Gdpr\Api\EraseCustomerCheckerInterface $eraseCustomerChecker
     */
    public function __construct(
        Context $context,
        Config $config,
        Session $session,
        EraseCustomerCheckerInterface $eraseCustomerChecker
    ) {
        $this->session = $session;
        $this->eraseCustomerChecker = $eraseCustomerChecker;
        parent::__construct($context, $config);
    }

    /**
     * @inheritdoc
     */
    protected function executeAction()
    {
        if ($this->eraseCustomerChecker->exists((int) $this->session->getCustomerId())) {
            $this->messageManager->addErrorMessage(new Phrase('Your account is already being removed.'));
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setRefererOrBaseUrl();

            return $resultRedirect;
        }

        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
