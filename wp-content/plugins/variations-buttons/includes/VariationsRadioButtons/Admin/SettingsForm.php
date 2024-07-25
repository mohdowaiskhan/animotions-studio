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

if (!class_exists(__NAMESPACE__ . '\\SettingsForm')):

class SettingsForm extends \OneTeamSoftware\WooCommerce\Admin\AbstractPageForm
{
	private $id;

	public function __construct($id)
	{
		$this->id = $id;

		parent::__construct($id . '-settings', 'manage_woocommerce');
	}

	protected function getSuccessMessageText()
	{
		return __('Settings have been successfully saved', $this->id);
	}

	protected function getFormData()
	{
		$data = array_merge(
			array(
				'enabled' => true,
				'style' => 'radio',
			),
			get_option($this->id, array())
		);

		return $data;
	}

	protected function saveFormData(array &$data)
	{
		$data = array_merge($this->getFormData(), $data);

		update_option($this->id, $data);

		return true;
	}

	public function getFormFields()
	{
		$priorityOptions = array(
			0 => __('Do not display', $this->id),
			1 => 1,
			2 => 2,
			3 => 3,
			4 => 4,
		);

		$fields = array(
			$this->id . '_settings_start' => array(
				//'title' => __('Settings', $this->id),
				'type' => 'title',
				'desc' => sprintf('<div class="updated notice-warning inline"><p>%s<br/><li>%s<br/><li>%s</p></div>', 
							__('Configure how do you want Variations Radio Buttons plugin to behave.', $this->id),
							__('You can configure color and image used by options in Products -> Attributes -> Configure Terms -> Edit Term'),
							__('You can customize these settings for each Attribute in Products -> Attributes -> Edit Attribute')
						),
				'id' => $this->id . '_settings',
			),

			'enabled' => array(
				'id' => 'enabled',
				'title' => __('Enable by Default', $this->id),
				'type' => 'checkbox',
				'default' => 'yes',
				'desc' => __('If unchecked you will have to enable it per product.', $this->id),
			),

			'attributeLabelLocation' => array(
				'id' => 'attributeLabelLocation',
				'title' => __('Attribute Label Location', $this->id),
				'type' => 'select',
				'options' => apply_filters($this->id . '_attribute_label_locations', array()),
				'desc' => __('Where attribute label (name) should be displayed.', $this->id),
			),

			'selectorStyle' => array(
				'id' => 'selectorStyle',
				'title' => __('Selection Style', $this->id),
				'type' => 'select',
				'options' => apply_filters($this->id . '_selector_styles', array()),
				'desc' => __('What kind of selector should be used.', $this->id),
			),

			'optionsOrientation' => array(
				'id' => 'optionsOrientation',
				'title' => __('Options Orientation', $this->id),
				'type' => 'select',
				'options' => array(
					'vertical' => __('Vertical', $this->id),
					'horizontal' => __('Horizontal', $this->id),
				),
				'desc' => __('Choose if you want options to be displayed vertically or horizontally.', $this->id),
			),

			'displayTooltipSource' => array(
				'id' => 'displayTooltipSource',
				'title' => __('What to Display in Tooltip?', $this->id),
				'type' => 'select',
				'options' => array(
					'hidden' => __('Nothing', $this->id),
					'label' => __('Term Name', $this->id),
					'description' => __('Term Description', $this->id),
				),
			),

			'displayTooltipLocation' => array(
				'id' => 'displayTooltipLocation',
				'title' => __('Tooltip Location', $this->id),
				'type' => 'select',
				'options' => array(
					'top' => __('Top', $this->id),
					'bottom' => __('Bottom', $this->id),
				),
			),

			'borderRadius' => array(
				'id' => 'borderRadius',
				'title' => __('Border Radius', $this->id),
				'type' => 'text',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^[0-9]+(\.[0-9]+)?(px|em|pt|%)$/')),
				'optional' => true,
				'desc' => __('Make selectors more rounded. It will accept px, em, pt and % as a unit. E.g. 50%', $this->id),
			),

			'width' => array(
				'id' => 'width',
				'title' => __('Width', $this->id),
				'type' => 'text',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^[0-9]+(\.[0-9]+)?(px|em|pt|%)$/')),
				'optional' => true,
				'desc' => __('Numeric value immediately followed by the unit. Supported units are px, em, pt, %. Eg. 5px', $this->id),
			),

			'height' => array(
				'id' => 'height',
				'title' => __('Height', $this->id),
				'type' => 'text',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^[0-9]+(\.[0-9]+)?(px|em|pt|%)$/')),
				'optional' => true,
				'desc' => __('Numeric value immediately followed by the unit. Supported units are px, em, pt, %. Eg. 5px', $this->id),
			),

			'textAlign' => array(
				'id' => 'textAlign',
				'title' => __('Text Alignment', $this->id),
				'type' => 'select',
				'options' => array(
					'' => __('Do not change', $this->id),
					'left' => __('Left', $this->id),
					'right' => __('Right', $this->id),
					'center' => __('Center', $this->id),
					'justify' => __('Justify', $this->id)
				),
				'default' => '',
				'desc' => __('Choose one of the options listed in the dropdown', $this->id),
			),

			'displayThumbnailPriority' => array(
				'id' => 'displayThumbnailPriority',
				'title' => __('Display Thumbnail Priority', $this->id),
				'type' => 'select',
				'options' => $priorityOptions,
				'default' => '',
				'desc' => __('Thumbnail can only be displayed for the products with the single attribute or when term has an image attached to it', $this->id),
			),

			'displayNamePriority' => array(
				'id' => 'displayNamePriority',
				'title' => __('Display Name Priority', $this->id),
				'type' => 'select',
				'options' => $priorityOptions,
				'default' => 2,
				'desc' => __('Specify the order in which option name will be displayed', $this->id),
			),

			'displayStockPriority' => array(
				'id' => 'displayStockPriority',
				'title' => __('Display Stock Priority', $this->id),
				'type' => 'select',
				'options' => $priorityOptions,
				'default' => '',
				'desc' => __('Stock can only be displayed for the products with the single attribute.', $this->id),
			),

			'displayPricePriority' => array(
				'id' => 'displayPricePriority',
				'title' => __('Display Price Priority', $this->id),
				'type' => 'select',
				'options' => $priorityOptions,
				'default' => '',
				'desc' => __('Price can only be displayed for the products with the single attribute', $this->id),
			),

			'displayAttributeDescription' => array(
				'id' => 'displayAttributeDescription',
				'title' => __('Display Attribute Description', $this->id),
				'type' => 'checkbox',
				'default' => 'no',
				'desc' => __('Display term description under option name.', $this->id),
			),

			$this->id . '_settings_end' => array(
				'type' => 'sectionend',
				'id' => $this->id . '_settings',
			),

			'save' => array(
				'id' => 'save',
				'title' => __('Save Changes', $this->id),
				'type' => 'submit',
				'class' => 'button-primary',
			),
		);

		return apply_filters($this->id . '_settings_form_fields', $fields);
	}
}

endif;
