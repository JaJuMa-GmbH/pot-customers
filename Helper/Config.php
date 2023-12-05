<?php
declare(strict_types=1);

namespace Jajuma\PotCustomers\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Config extends AbstractHelper
{
    public const XML_PATH_IS_CONFIRM = 'customer/create_account/confirm';

    public const XML_PATH_ENABLE = 'power_toys/pot_customers/is_enabled';

    public const XML_PATH_LAST_X_HOUR = 'power_toys/pot_customers/last_x_hour';

    /**
     * Is enable
     *
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLE);
    }

    /**
     * Get last x hours
     *
     * @return mixed
     */
    public function getLastXHours()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_LAST_X_HOUR);
    }

    /**
     * Is customer confirmation required
     *
     * @return mixed
     */
    public function isCustomerConfirmationRequired()
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_IS_CONFIRM);
    }
}
