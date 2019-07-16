<?php
namespace OpenSubscriptions\OpenSubscriptions\Block\Adminhtml\Connections\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
    protected $_status;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('oa_connection_form');
        $this->setTitle(__('Connection Information'));
    }

    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('oa_connection');
        $values = $model->getData();

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $form->setHtmlIdPrefix('connection_');

        $fieldset = $form->addFieldset('base_fieldset', [
            'legend' => __('General Information'),
            'class' => 'fieldset-wide'
        ]);
        if ($model->getId()) {
            $fieldset->addField('connection_id', 'hidden', [
                'name' => 'connection_id'
            ]);
        }
        $fieldset->addField('name', 'text', [
            'name' => 'name',
            'label' => __('Name'),
            'title' => __('Name'),
            'required' => true
        ]);
        $fieldset->addField('enabled', 'select', [
            'label' => __('Enabled'),
            'title' => __('Enabled'),
            'name' => 'enabled',
            'required' => true,
            'options' => ['1' => __('Enabled'), '0' => __('Disabled')]
        ]);
        $fieldset->addField('submodule', 'select', [
            'label' => __('Submodule'),
            'title' => __('Submodule'),
            'name' => 'submodule',
            'required' => true,
            'readonly' => $model->getId() ? true : false, // TODO: not working?
            'options' => $this->getSubmodulesOptions()
        ]);
        $fieldset->addField('hostname', 'text', [
            'name' => 'hostname',
            'label' => __('Hostname'),
            'title' => __('Hostname'),
        ]);
        $fieldset->addField('port', 'text', [
            'name' => 'port',
            'label' => __('Port'),
            'title' => __('Port'),
        ]);
        $fieldset->addField('secure', 'select', [
            'label' => __('Secure'),
            'title' => __('Secure'),
            'name' => 'secure',
            'required' => true,
            'options' => ['1' => __('Enabled'), '0' => __('Disabled')]
        ]);
        $fieldset->addField('username', 'text', [
            'name' => 'username',
            'label' => __('Username'),
            'title' => __('Username'),
        ]);
        $fieldset->addField('password', 'password', [
            'name' => 'password',
            'label' => __('Password'),
            'title' => __('Password'),
        ]);
        
        if ($model->getId()) {
            foreach ($model->getSubmoduleConnection()->getSettingsDefinitions() as $settingDefinition) {
                $values[$settingDefinition->getName()] = $model->getSetting($settingDefinition->getName());
                $fieldset->addField($settingDefinition->getName(), 'text', [
                    'name' => $settingDefinition->getName(),
                    'label' => __($settingDefinition->getLabel()),
                    'title' => __($settingDefinition->getLabel()),
                    'note' => $settingDefinition->getNote(),
                ]);
            }
        }

        $form->setValues($values);
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    private function getSubmodulesOptions()
    {
        // TODO: from source model
        $options = ['' => ''];
        foreach (\OpenSubscriptions\OpenSubscriptions\Model\Submodule::allIds() as $id) {
            $options[$id] = $id;
        }

        return $options;
    }
}
