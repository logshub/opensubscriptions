<?php
namespace Logshub\OpenSubscriptions\Block\Adminhtml\Services\Create\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId('oa_service_create_form');
        $this->setTitle(__('Service'));
    }

    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create([
            'data' => [
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/savenew', ['_current' => true]),
                'method' => 'post'
            ]
        ]);

        $form->setHtmlIdPrefix('service_');

        $fieldset = $form->addFieldset('base_fieldset', [
            'legend' => __('General Information'),
            'class' => 'fieldset-wide'
        ]);
        $fieldset->addField('product_id', 'text', [
            'name' => 'product_id',
            'label' => __('Product ID'),
            'title' => __('Product ID'),
            'required' => true,
            'note' => 'Service\'s submodule will be taken from the product.',
        ]);
        $fieldset->addField('customer_id', 'text', [
            'name' => 'customer_id',
            'label' => __('Customer ID'),
            'title' => __('Customer ID'),
            'required' => true,
            'note' => 'Customer with ownership of this service.',
        ]);
        $fieldset->addField('name', 'text', [
            'name' => 'name',
            'label' => __('Name'),
            'title' => __('Name')
        ]);
        $fieldset->addField('expire_at', 'date', [
            'name' => 'expire_at',
            'label' => __('Expire At'),
            'title' => __('Expire At'),
            'date_format' => 'yyyy-MM-dd',
            'time_format' => 'hh:mm:ss'
        ]);

        $form->setValues($this->_coreRegistry->registry('form_default'));
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
