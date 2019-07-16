<?php
namespace OpenSubscriptions\OpenSubscriptions\Model\Catalog\Product\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\Data\OptionSourceInterface;

class Submodule extends AbstractSource implements OptionSourceInterface
{
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
            foreach (\OpenSubscriptions\OpenSubscriptions\Model\Submodule::allIds() as $id) {
                $this->_options[] = [
                    'label' => $id,
                    'value' => $id,
                ];
            }
        }
        return $this->_options;
    }
}
