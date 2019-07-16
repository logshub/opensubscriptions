<?php
namespace Logshub\OpenSubscriptions\Model\ResourceModel\Service;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'service_id';
    protected $_eventPrefix = 'logshub_opensubscriptions_service_collection';
    protected $_eventObject = 'service_collection';

    protected function _construct()
    {
        $this->_init('Logshub\OpenSubscriptions\Model\Service', 'Logshub\OpenSubscriptions\Model\ResourceModel\Service');
    }

    public function filterByCustomer(int $customerId)
    {
        return $this->addFieldToFilter('main_table.customer_id', $customerId);
    }

    public function filterByProduct(int $productId)
    {
        return $this->addFieldToFilter('main_table.product_id', $productId);
    }

    public function filterBySubmodule($submodule)
    {
        return $this->addFieldToFilter('main_table.submodule', $submodule);
    }

    public function filterByStatus(array $status)
    {
        return $this->addFieldToFilter('main_table.status', $status);
    }
    
    public function filterByNotDeleted()
    {
        $this->getSelect()->where('main_table.status != ?', 'deleted');
        
        return $this;
    }

    public function orderByDate(bool $desc = true)
    {
        return $this->setOrder('created_at', $desc ? 'desc' : 'asc');
    }

    /**
     * Hook before grid rendering
     */
    protected function _renderFiltersBefore()
    {
        $joinOaConnectionsTable = $this->getTable('opensubscriptions_connections');
        $joinProductsTable = $this->getTable('catalog_product_entity');
        $joinCustomersTable = $this->getTable('customer_entity');
        $this->getSelect()
            ->joinLeft($joinOaConnectionsTable.' as oac', 'main_table.connection_id = oac.connection_id', ['connection_name' => 'name'])
            ->joinLeft($joinProductsTable.' as cp', 'main_table.product_id = cp.entity_id', ['product_sku' => 'sku'])
            ->joinLeft($joinCustomersTable.' as c', 'main_table.customer_id = c.entity_id', ['customer_email' => 'email']);

        parent::_renderFiltersBefore();
    }
}
