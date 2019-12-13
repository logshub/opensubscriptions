<?php
namespace Logshub\OpenSubscriptions\Block\Adminhtml\Connections;

/**
 * @see http://www.magebuzz.com/blog/magento-2-create-edit-form-backend-part-1/
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize staff grid edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'connection_id';
        $this->_blockGroup = 'Logshub_OpenSubscriptions';
        $this->_controller = 'adminhtml_connections';

        parent::_construct();

        if ($this->_isAllowedAction('Logshub_OpenSubscriptions::save')) {
            $this->buttonList->update('save', 'label', __('Save Connection'));
        } else {
            $this->buttonList->remove('save');
        }

        if ($this->_isAllowedAction('Logshub_OpenSubscriptions::connection_delete')) {
            $this->buttonList->update('delete', 'label', __('Delete Connection'));
        } else {
            $this->buttonList->remove('delete');
        }

        $this->buttonList->remove('reset');
        $this->updateButton('back', 'label', 'Back'); // "Back" translation retuns garbage

        // TODO: send by ajax, make it work
        $this->addButton('test', [
            'label' => __('Test Connection'),
            'onclick' => 'setLocation(window.location.href)',
            'class' => 'default'
        ], -1);
    }

    /**
     * Retrieve text for header element depending on loaded blocklist
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('oa_connection')->getId()) {
            return __("Edit Connection '%1'", $this->escapeHtml($this->_coreRegistry->registry('oa_connection')->getName()));
        } else {
            return __('New Connection');
        }
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
    }
}
