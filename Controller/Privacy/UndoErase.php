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
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Opengento\Gdpr\Api\EraseCustomerManagementInterface;
use Opengento\Gdpr\Controller\AbstractPrivacy;
use Opengento\Gdpr\Model\Config;

/**
 * Action Undo Erase
 */
class UndoErase extends AbstractPrivacy
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $session;

    /**
     * @var \Opengento\Gdpr\Api\EraseCustomerManagementInterface
     */
    private $eraseCustomerManagement;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Opengento\Gdpr\Model\Config $config
     * @param \Magento\Customer\Model\Session $session
     * @param \Opengento\Gdpr\Api\EraseCustomerManagementInterface $eraseCustomerManagement
     */
    public function __construct(
        Context $context,
        Config $config,
        Session $session,
        EraseCustomerManagementInterface $eraseCustomerManagement
    ) {
        $this->session = $session;
        $this->eraseCustomerManagement = $eraseCustomerManagement;
        parent::__construct($context, $config);
    }

    /**
     * @inheritdoc
     */
    protected function executeAction()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        try {
            $this->eraseCustomerManagement->cancel((int) $this->session->getCustomerId());
            $this->messageManager->addSuccessMessage(new Phrase('You canceled your account deletion.'));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, new Phrase('Something went wrong, please try again later!'));
        }

        return $resultRedirect->setPath('customer/privacy/settings');
    }
}
