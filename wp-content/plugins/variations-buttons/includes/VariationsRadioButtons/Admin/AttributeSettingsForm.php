<?php
/*********************************************************************/
/* PROGRAM    (C) 2022 FlexRC                                        */
/* PROPERTY   3-7170 Ash Cres                                        */
/* OF         Vancouver, BC V6P3K7                                   */
/*            CANADA                                                 */
/*            Voice (604) 800-7879                                   */
/*********************************************************************/

namespace OneTeamSoftware\WooCommerce\VariationsRadioButtons\Admin;

defined('ABSPATH') || exit;

if (!class_exists(__NAMESPACE__ . '\\AttributeSettingsForm')):

class AttributeSettingsForm
{
	protected $id;
	protected $priorityOptions;
	protected $form;

	public function __construct($id) 
    {
		$this->id = $id;

		$this->priorityOptions = array(
			'' => __('Use global settings', $this->id),
			0 => __('Do not display', $this->id),
			1 => 1,
			2 => 2,
			3 => 3,
			4 => 4,
		);

		$this->form = new \OneTeamSoftware\WooCommerce\Admin\Form();
	}

	public function register()
	{
		add_action('woocommerce_after_edit_attribute_fields', array($this, 'display'));
	}

	public function display()
	{
		$this->form->setFields($this->getFormFields());
		$this->form->display();
	}

	protected function getFormFields()
	{
		$customAttributes = array();
		$proFeatureMessage = apply_filters($this->id . '_pro_version_feature_message', '');
		if (!empty($proFeatureMessage)) {
			$proFeatureMessage = '<br/>' . $proFeatureMessage;
			$customAttributes = array(
				'disabled' => 'yes'
			);
		}

		$formFields = array(
			array(
				'type' => 'sectionend',
			),

			array(
				'type' => 'title',
				'title' => __('Radio Buttons and Swatches', $this->id),
				'desc' => __('Customize look and behavior of this attribute.', $this->id) . $proFeatureMessage
			),

			array(
				'id' => sprintf('%s[enabled]', $this->id),
				'type' => 'select',
				'title' => __('Enabled', $this->id),
				'options' => array(
					'' => __('Use global settings', $this->id),
					1 => __('Enabled', $this->id),
					0 => __('Disabled', $this->id)
				),
				'custom_attributes' => $customAttributes
			),

			array(
				'id' => sprintf('%s[attributeLabelLocation]', $this->id),
				'type' => 'select',
				'title' => __('Attribute Label Location', $this->id),
				'options' => array_merge(array(
					'' => __('Use global settings', $this->id),
				), apply_filters($this->id . '_attribute_label_locations', array())),
				'custom_attributes' => $customAttributes
			),

			array(
				'id' => sprintf('%s[selectorStyle]', $this->id),
				'type' => 'select',
				'title' => __('Selection Style', $this->id),
				'options' => array_merge(array(
					'' => __('Use global settings', $this->id),
				), apply_filters($this->id . '_selector_styles', array())),
				'custom_attributes' => $customAttributes
			),
				
			array(
				'id' => sprintf('%s[optionsOrientation]', $this->id),
				'type' => 'select',
				'title' => __('Options Orientation', $this->id),
				'options' => array(
					'' => __('Use global settings', $this->id),
					'vertical' => __('Vertical', $this->id),
					'horizontal' => __('Horizontal', $this->id),
				),
				'custom_attributes' => $customAttributes
			),
			
			array(
				'id' => sprintf('%s[displayTooltipSource]', $this->id),
				'type' => 'select',
				'title' => __('What to Display in Tooltip?', $this->id),
				'options' => array(
					'' => __('Use global settings', $this->id),
					'hidden' => __('Nothing', $this->id),
					'label' => __('Term Name', $this->id),
					'description' => __('Term Description', $this->id),
				),
				'custom_attributes' => $customAttributes
			),
			
			array(
				'id' => sprintf('%s[displayTooltipLocation]', $this->id),
				'type' => 'select',
				'title' => __('Tooltip Location', $this->id),
				'options' => array(
					'' => __('Use global settings', $this->id),
					'top' => __('Top', $this->id),
					'bottom' => __('Bottom', $this->id),
				),
				'custom_attributes' => $customAttributes
			),

			array(
				'id' => sprintf('%s[borderRadius]', $this->id),
				'title' => __('Border Radius', $this->id),
				'type' => 'text',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^[0-9]+(\.[0-9]+)?(px|em|pt|%)$/')),
				'optional' => true,
				'custom_attributes' => $customAttributes
			),

			array(
				'id' => sprintf('%s[width]', $this->id),
				'title' => __('Width', $this->id),
				'type' => 'text',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^[0-9]+(\.[0-9]+)?(px|em|pt|%)$/')),
				'optional' => true,
				'custom_attributes' => $customAttributes
			),

			array(
				'id' => sprintf('%s[height]', $this->id),
				'title' => __('Height', $this->id),
				'type' => 'text',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^[0-9]+(\.[0-9]+)?(px|em|pt|%)$/')),
				'optional' => true,
				'custom_attributes' => $customAttributes
			),

			array(
				'id' => sprintf('%s[textAlign]', $this->id),
				'title' => __('Text Alignment', $this->id),
				'type' => 'select',
				'options' => array(
					'' => __('Use global settings', $this->id),
					'left' => __('Left', $this->id),
					'right' => __('Right', $this->id),
					'center' => __('Center', $this->id),
					'justify' => __('Justify', $this->id)
				),
				'optional' => true,
				'custom_attributes' => $customAttributes
			),

			array(
				'id' => sprintf('%s[displayThumbnailPriority]', $this->id),
				'type' => 'select',
				'title' => __('Display Thumbnail Priority', $this->id),
				'options' => $this->priorityOptions,
				'custom_attributes' => $customAttributes
			),

			array(
				'id' => sprintf('%s[displayNamePriority]', $this->id),
				'type' => 'select',
				'title' => __('Display Name Priority', $this->id),
				'options' => $this->priorityOptions,
				'custom_attributes' => $customAttributes
			),

			array(
				'id' => sprintf('%s[displayStockPriority]', $this->id),
				'type' => 'select',
				'title' => __('Display Stock Priority', $this->id),
				'options' => $this->priorityOptions,
				'custom_attributes' => $customAttributes
			),

			array(
				'id' => sprintf('%s[displayPricePriority]', $this->id),
				'type' => 'select',
				'title' => __('Display Price Priority', $this->id),
				'options' => $this->priorityOptions, 
				'custom_attributes' => $customAttributes
			),

			array(
				'id' => sprintf('%s[displayAttributeDescription]', $this->id),
				'type' => 'select',
				'title' => __('Display Attribute Description', $this->id),
				'options' => array(
					'' => __('Use global settings', $this->id),
					false => __('Do not display', $this->id),
					true => __('Display', $this->id),
				),
				'custom_attributes' => $customAttributes
			),
		);

		return apply_filters($this->id . '_attribute_form_fields', $formFields);
	}
};

endif;
