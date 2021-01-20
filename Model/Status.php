<?php
namespace Yota\Order\Model;
class Status extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'email_to_customer_after_14day ';

    protected $_cacheTag = 'email_to_customer_after_14day';

    protected $_eventPrefix = 'email_to_customer_after_14day';

    protected function _construct()
    {
        $this->_init('Yota\Order\Model\ResourceModel\Status');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}