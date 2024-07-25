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

if (!class_exists(__NAMESPACE__ . '\\StylesForm')):

class StylesForm extends \OneTeamSoftware\WooCommerce\Admin\AbstractPageForm
{
	protected $id;
	protected $fontWeightOptions;
	protected $borderStyleOptions;
	protected $customAttributes;

	public function __construct($id)
	{
		$this->id = $id;

		parent::__construct($id . '-styles', 'manage_woocommerce');

		$this->displaySectionsMenu = true;

		$this->fontWeightOptions = array(
			'' => __('Use system default', $this->id),
			'normal' => __('Normal', $this->id),
			'lighter' => __('Lighter', $this->id),
			'bold' => __('Bold', $this->id),
			'bolder' => __('Bolder', $this->id),
		);

		for ($fontWeightNumber = 100; $fontWeightNumber <= 900; $fontWeightNumber += 100) {
			$this->fontWeightOptions[$fontWeightNumber] = $fontWeightNumber;
		}

		$this->borderStyleOptions = array(
			'' => __('Use system default', $this->id),
			'dotted' => __('Dotted', $this->id),
			'solid' => __('Solid', $this->id),
			'double' => __('Double', $this->id),
			'dashed' => __('Dashed', $this->id)
		);

		$this->customAttributes = array(
			'disabled' => 'yes'
		);
	}

	protected function getSuccessMessageText()
	{
		return __('Styles have been successfully saved', $this->id);
	}

	protected function getFormData()
	{
		return array();
	}

	protected function saveFormData(array &$data)
	{
		return true;
	}

	public function getFormFields()
	{
		$fields = array(
			'general_start' => array(
				'title' => __('General', $this->id),
				'type' => 'title',
				'id' => 'general',
			),

			'enableStyles' => array(
				'id' => 'enableStyles',
				'title' => __('Enable Styles', $this->id),
				'type' => 'checkbox',
				'default' => 'yes',
				'desc' => __('Do you want the following styles be enabled?', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			'general_end' => array(
				'type' => 'sectionend',
				'id' => 'general',
			),
		);
		
		$fields += $this->getSectionBaseFields('tooltip', 'Tooltip');
		$fields += $this->getLabelFields('label', 'Label');
		$fields += $this->getRadiosFields('radio', 'Radios');
		$fields += $this->getRadiosFields('radioHover', 'Radios (On Hover)');
		$fields += $this->getRadiosFields('radioChecked', 'Radios (Selected)');
		$fields += $this->getSectionAllFields('swatch', 'Swatches');
		$fields += $this->getSectionAllFields('swatchHover', 'Swatches (On Hover)');
		$fields += $this->getSectionAllFields('swatchChecked', 'Swatches (Selected)');

		$fields += array(
			'save' => array(
				'id' => 'save',
				'title' => __('Save Changes', $this->id),
				'type' => 'submit',
				'class' => 'button-primary',
				'custom_attributes' => $this->customAttributes
			),
		);

		return apply_filters($this->id . '_styles_form_fields', $fields);
	}

	protected function getLabelFields($prefix, $title)
	{
		$fields = array(
			$prefix . '_styles_start' => array(
				'title' => __($title, $this->id),
				'type' => 'title',
				'id' => $prefix,
			),
		);

		$fields += $this->getDimensionFields($prefix);
		$fields += $this->getBaseFields($prefix);

		$fields += array(
			$prefix . '_style_end' => array(
				'type' => 'sectionend',
				'id' => $prefix,
			),			
		);

		return $fields;
	}

	protected function getSectionBaseFields($prefix, $title)
	{
		$fields = array(
			$prefix . '_styles_start' => array(
				'title' => __($title, $this->id),
				'type' => 'title',
				'id' => $prefix,
			),
		);

		$fields += $this->getBaseFields($prefix);

		$fields += array(
			$prefix . '_style_end' => array(
				'type' => 'sectionend',
				'id' => $prefix,
			),			
		);

		return $fields;
	}

	protected function getSectionAllFields($prefix, $title)
	{
		$fields = array(
			$prefix . '_styles_start' => array(
				'title' => __($title, $this->id),
				'type' => 'title',
				'id' => $prefix,
			),
		);

		$fields += $this->getBaseFields($prefix);
		$fields += $this->getExtraFields($prefix);

		$fields += array(
			$prefix . '_style_end' => array(
				'type' => 'sectionend',
				'id' => $prefix,
			),			
		);

		return $fields;
	}

	protected function getRadiosFields($prefix, $title)
	{
		$fields = array(
			$prefix . '_styles_start' => array(
				'title' => __($title, $this->id),
				'type' => 'title',
				'id' => $prefix,
			),
			$prefix . 'RadioButtonColor' => array(
				'id' => $prefix . 'RadioButtonColor',
				'title' => __('Radio Button Color', $this->id),
				'type' => 'color',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^#[a-z0-9]{6}$/')),
				'optional' => true,
				'desc' => __('Choose a desired color with the color picker or enter it as a six hex digits long color code prefixed with #', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'RadioButtonSize' => array(
				'id' => $prefix . 'RadioButtonSize',
				'title' => __('Radio Button Size', $this->id),
				'type' => 'text',
				'css' => 'width: 200px',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^[0-9]+(\.[0-9]+)?(px|em|pt|%)?$/')),
				'optional' => true,
				'desc' => __('Numeric value immediately followed by the unit. Supported units are px, em, pt, %. Eg. 5px', $this->id),
				'custom_attributes' => array_merge(array('step' => '0.01'), $this->customAttributes)
			),		
		);

		$fields += $this->getBaseFields($prefix);
		$fields += $this->getExtraFields($prefix);

		$fields += array(
			$prefix . '_style_end' => array(
				'type' => 'sectionend',
				'id' => $prefix,
			),			
		);

		return $fields;
	}

	protected function getDimensionFields($prefix)
	{
		$fields = array(
			$prefix . 'Width' => array(
				'id' => $prefix . 'Width',
				'title' => __('Width', $this->id),
				'type' => 'text',
				'css' => 'width: 200px',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^[0-9]+(\.[0-9]+)?(px|em|pt|%)?$/')),
				'optional' => true,
				'desc' => __('Numeric value immediately followed by the unit. Supported units are px, em, pt, %. Eg. 5px', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'Height' => array(
				'id' => $prefix . 'Height',
				'title' => __('Height', $this->id),
				'type' => 'text',
				'css' => 'width: 200px',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^[0-9]+(\.[0-9]+)?(px|em|pt|%)?$/')),
				'optional' => true,
				'desc' => __('Numeric value immediately followed by the unit. Supported units are px, em, pt, %. Eg. 5px', $this->id),
				'custom_attributes' => $this->customAttributes
			),
		);

		return $fields;
	}

	protected function getExtraFields($prefix)
	{
		$fields = $this->getDimensionFields($prefix);

		$fields += array(
			$prefix . 'InnerBorderColor' => array(
				'id' => $prefix . 'InnerBorderColor',
				'title' => __('Inner Border Color', $this->id),
				'type' => 'color',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^#[a-z0-9]{6}$/')),
				'optional' => true,
				'desc' => __('Choose a desired color with the color picker or enter it as a six hex digits long color code prefixed with #', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'InnerBorderStyle' => array(
				'id' => $prefix . 'InnerBorderStyle',
				'title' => __('Inner Border Style', $this->id),
				'type' => 'select',
				'options' => $this->borderStyleOptions,
				'optional' => true,
				'css' => 'width: 200px',
				'desc' => __('Choose one of the options listed in the dropdown', $this->id),
				'custom_attributes' => $this->customAttributes
			),
			
			$prefix . 'InnerBorderWidth' => array(
				'id' => $prefix . 'InnerBorderWidth',
				'title' => __('Inner Border Width', $this->id),
				'type' => 'text',
				'css' => 'width: 200px',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^[0-9]+(\.[0-9]+)?(px|em|pt|%)?$/')),
				'optional' => true,
				'desc' => __('Numeric value immediately followed by the unit. Supported units are px, em, pt, %. Eg. 5px', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'ThumbnailWidth' => array(
				'id' => $prefix . 'ThumbnailWidth',
				'title' => __('Thumbnail Width', $this->id),
				'type' => 'text',
				'css' => 'width: 200px',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^[0-9]+(\.[0-9]+)?(px|em|pt|%)?$/')),
				'optional' => true,
				'desc' => __('Numeric value immediately followed by the unit. Supported units are px, em, pt, %. Eg. 5px', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'ThumbnailHeight' => array(
				'id' => $prefix . 'ThumbnailHeight',
				'title' => __('Thumbnail Height', $this->id),
				'type' => 'text',
				'css' => 'width: 200px',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^[0-9]+(\.[0-9]+)?(px|em|pt|%)?$/')),
				'optional' => true,
				'desc' => __('Numeric value immediately followed by the unit. Supported units are px, em, pt, %. Eg. 5px', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'ThumbnailBorderColor' => array(
				'id' => $prefix . 'ThumbnailBorderColor',
				'title' => __('Thumbnail Border Color', $this->id),
				'type' => 'color',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^#[a-z0-9]{6}$/')),
				'optional' => true,
				'desc' => __('Choose a desired color with the color picker or enter it as a six hex digits long color code prefixed with #', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'ThumbnailBorderStyle' => array(
				'id' => $prefix . 'ThumbnailBorderStyle',
				'title' => __('Thumbnail Border Style', $this->id),
				'type' => 'select',
				'options' => $this->borderStyleOptions,
				'optional' => true,
				'css' => 'width: 200px',
				'desc' => __('Choose one of the options listed in the dropdown', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'ThumbnailBorderWidth' => array(
				'id' => $prefix . 'ThumbnailBorderWidth',
				'title' => __('Thumbnail Border Width', $this->id),
				'type' => 'text',
				'css' => 'width: 200px',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^[0-9]+(\.[0-9]+)?(px|em|pt|%)?$/')),
				'optional' => true,
				'desc' => __('Numeric value immediately followed by the unit. Supported units are px, em, pt, %. Eg. 5px', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'ThumbnailBorderRadius' => array(
				'id' => $prefix . 'ThumbnailBorderRadius',
				'title' => __('Thumbnail Border Radius', $this->id),
				'type' => 'text',
				'css' => 'width: 200px',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^[0-9]+(\.[0-9]+)?(px|em|pt|%)?$/')),
				'optional' => true,
				'desc' => __('Numeric value immediately followed by the unit. Supported units are px, em, pt, %. Eg. 5px', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'ThumbnailMarginLeft' => array(
				'id' => $prefix . 'ThumbnailMarginLeft',
				'title' => __('Thumbnail Left Margin', $this->id),
				'type' => 'text',
				'css' => 'width: 200px',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^auto|([0-9]+(\.[0-9]+)?(px|em|pt|%)?)$/')),
				'optional' => true,
				'desc' => __('Numeric value immediately followed by the unit. Alternatively you can use auto as a value. Supported units are px, em, pt, %. Eg. 5px or auto', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'ThumbnailMarginRight' => array(
				'id' => $prefix . 'ThumbnailMarginRight',
				'title' => __('Thumbnail Right Margin', $this->id),
				'type' => 'text',
				'css' => 'width: 200px',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^auto|([0-9]+(\.[0-9]+)?(px|em|pt|%)?)$/')),
				'optional' => true,
				'desc' => __('Numeric value immediately followed by the unit. Alternatively you can use auto as a value. Supported units are px, em, pt, %. Eg. 5px or auto', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'NameMarginLeft' => array(
				'id' => $prefix . 'NameMarginLeft',
				'title' => __('Name Left Margin', $this->id),
				'type' => 'text',
				'css' => 'width: 200px',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^auto|([0-9]+(\.[0-9]+)?(px|em|pt|%)?)$/')),
				'optional' => true,
				'desc' => __('Numeric value immediately followed by the unit. Alternatively you can use auto as a value. Supported units are px, em, pt, %. Eg. 5px or auto', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'NameMarginRight' => array(
				'id' => $prefix . 'NameMarginRight',
				'title' => __('Name Right Margin', $this->id),
				'type' => 'text',
				'css' => 'width: 200px',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^auto|([0-9]+(\.[0-9]+)?(px|em|pt|%)?)$/')),
				'optional' => true,
				'desc' => __('Numeric value immediately followed by the unit. Alternatively you can use auto as a value. Supported units are px, em, pt, %. Eg. 5px or auto', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'StockFontColor' => array(
				'id' => $prefix . 'StockFontColor',
				'title' => __('Stock Font Color', $this->id),
				'type' => 'color',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^#[a-z0-9]{6}$/')),
				'optional' => true,
				'desc' => __('Choose a desired color with the color picker or enter it as a six hex digits long color code prefixed with #', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'StockFontWeight' => array(
				'id' => $prefix . 'StockFontWeight',
				'title' => __('Stock Font Weight', $this->id),
				'type' => 'select',
				'options' => $this->fontWeightOptions,
				'optional' => true,
				'css' => 'width: 200px',
				'desc' => __('Choose one of the options listed in the dropdown', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'StockFontSize' => array(
				'id' => $prefix . 'StockFontSize',
				'title' => __('Stock Font Size', $this->id),
				'type' => 'text',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^\d+(\.\d+)?(px|pt|em|%)?$/')),
				'optional' => true,
				'css' => 'width: 200px',
				'desc' => __('Numeric value immediately followed by the unit. Supported units are px, em, pt, %. Eg. 5px', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'StockMarginLeft' => array(
				'id' => $prefix . 'StockMarginLeft',
				'title' => __('Stock Left Margin', $this->id),
				'type' => 'text',
				'css' => 'width: 200px',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^auto|([0-9]+(\.[0-9]+)?(px|em|pt|%)?)$/')),
				'optional' => true,
				'desc' => __('Numeric value immediately followed by the unit. Alternatively you can use auto as a value. Supported units are px, em, pt, %. Eg. 5px or auto', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'StockMarginRight' => array(
				'id' => $prefix . 'StockMarginRight',
				'title' => __('Stock Right Margin', $this->id),
				'type' => 'text',
				'css' => 'width: 200px',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^auto|([0-9]+(\.[0-9]+)?(px|em|pt|%)?)$/')),
				'optional' => true,
				'desc' => __('Numeric value immediately followed by the unit. Alternatively you can use auto as a value. Supported units are px, em, pt, %. Eg. 5px or auto', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'PriceFontColor' => array(
				'id' => $prefix . 'PriceFontColor',
				'title' => __('Price Font Color', $this->id),
				'type' => 'color',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^#[a-z0-9]{6}$/')),
				'optional' => true,
				'desc' => __('Choose a desired color with the color picker or enter it as a six hex digits long color code prefixed with #', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'PriceFontWeight' => array(
				'id' => $prefix . 'PriceFontWeight',
				'title' => __('Price Font Weight', $this->id),
				'type' => 'select',
				'options' => $this->fontWeightOptions,
				'optional' => true,
				'css' => 'width: 200px',
				'desc' => __('Choose one of the options listed in the dropdown', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'PriceFontSize' => array(
				'id' => $prefix . 'PriceFontSize',
				'title' => __('Price Font Size', $this->id),
				'type' => 'text',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^\d+(\.\d+)?(px|pt|em|%)?$/')),
				'optional' => true,
				'css' => 'width: 200px',
				'desc' => __('Numeric value immediately followed by the unit. Supported units are px, em, pt, %. Eg. 5px', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'PriceMarginLeft' => array(
				'id' => $prefix . 'PriceMarginLeft',
				'title' => __('Price Left Margin', $this->id),
				'type' => 'text',
				'css' => 'width: 200px',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^auto|([0-9]+(\.[0-9]+)?(px|em|pt|%)?)$/')),
				'optional' => true,
				'desc' => __('Numeric value immediately followed by the unit. Alternatively you can use auto as a value. Supported units are px, em, pt, %. Eg. 5px or auto', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'PriceMarginRight' => array(
				'id' => $prefix . 'PriceMarginRight',
				'title' => __('Price Right Margin', $this->id),
				'type' => 'text',
				'css' => 'width: 200px',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^auto|([0-9]+(\.[0-9]+)?(px|em|pt|%)?)$/')),
				'optional' => true,
				'desc' => __('Numeric value immediately followed by the unit. Alternatively you can use auto as a value. Supported units are px, em, pt, %. Eg. 5px or auto', $this->id),
				'custom_attributes' => $this->customAttributes
			),
			
			$prefix . 'DescriptionFontColor' => array(
				'id' => $prefix . 'DescriptionFontColor',
				'title' => __('Description Font Color', $this->id),
				'type' => 'color',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^#[a-z0-9]{6}$/')),
				'optional' => true,
				'desc' => __('Choose a desired color with the color picker or enter it as a six hex digits long color code prefixed with #', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'DescriptionFontWeight' => array(
				'id' => $prefix . 'DescriptionFontWeight',
				'title' => __('Description Font Weight', $this->id),
				'type' => 'select',
				'options' => $this->fontWeightOptions,
				'optional' => true,
				'css' => 'width: 200px',
				'desc' => __('Choose one of the options listed in the dropdown', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'DescriptionFontSize' => array(
				'id' => $prefix . 'DescriptionFontSize',
				'title' => __('Description Font Size', $this->id),
				'type' => 'text',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^\d+(\.\d+)?(px|pt|em|%)?$/')),
				'optional' => true,
				'css' => 'width: 200px',
				'desc' => __('Numeric value immediately followed by the unit. Supported units are px, em, pt, %. Eg. 5px', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'DescriptionMarginLeft' => array(
				'id' => $prefix . 'DescriptionMarginLeft',
				'title' => __('Description Left Margin', $this->id),
				'type' => 'text',
				'css' => 'width: 200px',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^auto|([0-9]+(\.[0-9]+)?(px|em|pt|%)?)$/')),
				'optional' => true,
				'desc' => __('Numeric value immediately followed by the unit. Alternatively you can use auto as a value. Supported units are px, em, pt, %. Eg. 5px or auto', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'DescriptionMarginRight' => array(
				'id' => $prefix . 'DescriptionMarginRight',
				'title' => __('Description Right Margin', $this->id),
				'type' => 'text',
				'css' => 'width: 200px',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^auto|([0-9]+(\.[0-9]+)?(px|em|pt|%)?)$/')),
				'optional' => true,
				'desc' => __('Numeric value immediately followed by the unit. Alternatively you can use auto as a value. Supported units are px, em, pt, %. Eg. 5px or auto', $this->id),
				'custom_attributes' => $this->customAttributes
			),	
		);

		return $fields;
	}

	protected function getBaseFields($prefix)
	{
		$fields = array(
			$prefix . 'BackgroundColor' => array(
				'id' => $prefix . 'BackgroundColor',
				'title' => __('Background Color', $this->id),
				'type' => 'color',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^#[a-z0-9]{6}$/')),
				'optional' => true,
				'desc' => __('Choose a desired color with the color picker or enter it as a six hex digits long color code prefixed with #', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'Opacity' => array(
				'id' => $prefix . 'Opacity',
				'title' => __('Opacity', $this->id),
				'type' => 'number',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^\d+(\.\d+)?$/')),
				'optional' => true,
				'css' => 'width: 200px',
				'desc' => __('Numeric value', $this->id),
				'custom_attributes' => array_merge(array('step' => '0.01'), $this->customAttributes)
			),


			$prefix . 'Margin' => array(
				'id' => $prefix . 'Margin',
				'title' => __('Margin', $this->id),
				'type' => 'text',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^(\d+(\.\d+)?(px|em|pt)[ ]*){1,4}$/')),
				'optional' => true,
				'css' => 'width: 200px',
				'desc' => __('Numeric values immediately followed by the unit with space between them. Eg. 5px 10px 5px 10px', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'Padding' => array(
				'id' => $prefix . 'Padding',
				'title' => __('Padding', $this->id),
				'type' => 'text',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^(\d+(\.\d+)?(px|em|pt)[ ]*){1,4}$/')),
				'optional' => true,
				'css' => 'width: 200px',
				'desc' => __('Numeric values immediately followed by the unit with space between them. Eg. 5px 10px 5px 10px', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'BorderColor' => array(
				'id' => $prefix . 'BorderColor',
				'title' => __('Border Color', $this->id),
				'type' => 'color',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^#[a-z0-9]{6}$/')),
				'optional' => true,
				'desc' => __('Choose a desired color with the color picker or enter it as a six hex digits long color code prefixed with #', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'BorderStyle' => array(
				'id' => $prefix . 'BorderStyle',
				'title' => __('Border Style', $this->id),
				'type' => 'select',
				'options' => $this->borderStyleOptions,
				'optional' => true,
				'css' => 'width: 200px',
				'desc' => __('Choose one of the options listed in the dropdown', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'BorderWidth' => array(
				'id' => $prefix . 'BorderWidth',
				'title' => __('Border Width', $this->id),
				'type' => 'text',
				'css' => 'width: 200px',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^[0-9]+(\.[0-9]+)?(px|em|pt|%)?$/')),
				'optional' => true,
				'desc' => __('Numeric value immediately followed by the unit. Supported units are px, em, pt, %. Eg. 5px', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'BorderRadius' => array(
				'id' => $prefix . 'BorderRadius',
				'title' => __('Border Radius', $this->id),
				'type' => 'text',
				'css' => 'width: 200px',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^[0-9]+(\.[0-9]+)?(px|em|pt|%)?$/')),
				'optional' => true,
				'desc' => __('Numeric value immediately followed by the unit. Supported units are px, em, pt, %. Eg. 5px', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'FontColor' => array(
				'id' => $prefix . 'FontColor',
				'title' => __('Font Color', $this->id),
				'type' => 'color',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^#[a-z0-9]{6}$/')),
				'optional' => true,
				'desc' => __('Choose a desired color with the color picker or enter it as a six hex digits long color code prefixed with #', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'FontWeight' => array(
				'id' => $prefix . 'FontWeight',
				'title' => __('Font Weight', $this->id),
				'type' => 'select',
				'options' => $this->fontWeightOptions,
				'optional' => true,
				'css' => 'width: 200px',
				'desc' => __('Choose one of the options listed in the dropdown', $this->id),
				'custom_attributes' => $this->customAttributes
			),

			$prefix . 'FontSize' => array(
				'id' => $prefix . 'FontSize',
				'title' => __('Font Size', $this->id),
				'type' => 'text',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^\d+(\.\d+)?(px|pt|em|%)?$/')),
				'optional' => true,
				'css' => 'width: 200px',
				'desc' => __('Numeric value immediately followed by the unit. Supported units are px, em, pt, %. Eg. 5px', $this->id),
				'custom_attributes' => $this->customAttributes
			),
			
			$prefix . 'TextAlign' => array(
				'id' => $prefix . 'TextAlign',
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
		);

		return $fields;
	}
}

endif;
