<?php
namespace Yota\Order\Model\ResourceModel\Post;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'status_id';
    protected $_eventPrefix = 'memail_to_customer_after_14day_collection';
    protected $_eventObject = 'status_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Yota\Order\Model\Status', 'Yota\Order\Model\ResourceModel\Status');
    }

}
