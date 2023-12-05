<?php
declare(strict_types=1);

namespace Jajuma\PotCustomers\Block;

use Jajuma\PowerToys\Block\PowerToys\Dashboard;
use Jajuma\PotCustomers\Helper\Config as HelperConfig;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Stdlib\DateTime\DateTimeFactory;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\ResourceModel\Online\Grid\CollectionFactory as CustomerOnlineCollectionFactory;

class Customers extends Dashboard
{
    /**
     * @var HelperConfig
     */
    protected HelperConfig $helperConfig;

    /**
     * @var DateTimeFactory
     */
    protected DateTimeFactory $dateTimeFactory;

    /**
     * @var CustomerFactory
     */
    protected CustomerFactory $customerFactory;

    /**
     * @var CustomerOnlineCollectionFactory
     */
    protected CustomerOnlineCollectionFactory $customerOnlineCollectionFactory;

    /**
     * @param Context $context
     * @param HelperConfig $helperConfig
     * @param DateTimeFactory $dateTimeFactory
     * @param CustomerFactory $customerFactory
     * @param CustomerOnlineCollectionFactory $customerOnlineCollectionFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        HelperConfig $helperConfig,
        DateTimeFactory $dateTimeFactory,
        CustomerFactory $customerFactory,
        CustomerOnlineCollectionFactory $customerOnlineCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helperConfig = $helperConfig;
        $this->dateTimeFactory = $dateTimeFactory;
        $this->customerFactory = $customerFactory;
        $this->customerOnlineCollectionFactory = $customerOnlineCollectionFactory;
    }

    /**
     * Is enable
     *
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->helperConfig->isEnable();
    }

    /**
     * Get last x hours
     *
     * @return mixed
     */
    public function getLastXHours()
    {
        return $this->helperConfig->getLastXHours();
    }

    /**
     * Get number new customers
     *
     * @return mixed
     */
    public function getNumberNewCustomers()
    {
        $lastHours = $this->getLastXHours();
        $currentDateTime = $this->dateTimeFactory->create()->gmtDate();
        $minusCurrentDateTime = Date('Y-m-d H:i:s', strtotime('-'. $lastHours .' hours', strtotime($currentDateTime)));
        $collection = $this->customerFactory->create()
            ->getCollection()->addFieldToFilter('created_at', ['gteq' => $minusCurrentDateTime]);
        return $collection->getSize();
    }

    /**
     * Get total customers
     *
     * @return mixed
     */
    public function getTotalCustomers()
    {
        $totalCollection = $this->customerFactory->create()->getCollection();
        return $totalCollection->getSize();
    }

    /**
     * Get pending customers
     *
     * @return mixed
     */
    public function getPendingCustomers()
    {
        $isCustomerConfirmationRequired = $this->helperConfig->isCustomerConfirmationRequired();
        if ($isCustomerConfirmationRequired) {
            $pendingCollection = $this->customerFactory->create()
                ->getCollection()->addFieldToFilter('confirmation', ['null' => true]);
            return $pendingCollection->getSize();
        }
        return 0;
    }

    /**
     * Get customers online
     *
     * @return mixed
     */
    public function getCustomersOnline()
    {
        $onlineCollection = $this->customerOnlineCollectionFactory->create()
            ->addFieldToFilter('customer_id', ['notnull' => true]);
        return $onlineCollection->getSize();
    }

    /**
     * Get guests online
     *
     * @return mixed
     */
    public function getGuestsOnline()
    {
        $guestsOnlineCollection = $this->customerOnlineCollectionFactory->create()
            ->addFieldToFilter('customer_id', ['null' => true]);
        return $guestsOnlineCollection->getSize();
    }
}
