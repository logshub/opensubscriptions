<?php
namespace OpenSubscriptions\OpenSubscriptions\Model\Catalog\Product\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\Data\OptionSourceInterface;

class ConnectionId extends AbstractSource implements OptionSourceInterface
{
    private $connectionCollectionFactory;

    public function __construct(
        \OpenSubscriptions\OpenSubscriptions\Model\ResourceModel\Connection\CollectionFactory $connectionCollectionFactory
    ) {
        $this->connectionCollectionFactory = $connectionCollectionFactory;
    }

    /**
     * Get list of all available submodules
     *
     * @return array
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = [[
                'label' => '-',
                'value' => 0,
            ]];
            $collection = $this->connectionCollectionFactory->create();
            foreach ($collection as $connection) {
                $this->_options[] = [
                    'label' => $connection->getSubmodule() . ' - ' . $connection->getName(),
                    'value' => $connection->getConnectionId(),
                ];
            }
        }
        return $this->_options;
    }
}
