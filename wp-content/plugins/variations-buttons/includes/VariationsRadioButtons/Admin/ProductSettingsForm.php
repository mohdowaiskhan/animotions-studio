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

if (!class_exists(__NAMESPACE__ . '\\ProductSettingsForm')):

class ProductSettingsForm
{
	protected $id;
	protected $settings;
	protected $productSettings;
	protected $priorityOptions;
	protected $formFilter;
	protected $metaBoxForm;

    public function __construct($id, $settings = array()) 
    {
		$this->id = $id;
		$this->settings = $settings;
		$this->productSettings = array();

		$this->priorityOptions = array(
			'' => __('Use global settings', $this->id),
			0 => __('Do not display', $this->id),
			1 => 1,
			2 => 2,
			3 => 3,
			4 => 4,
		);

		$this->formFilter = new \OneTeamSoftware\WooCommerce\Admin\FormFilter();
		$this->metaBoxForm = new \OneTeamSoftware\WooCommerce\Admin\MetaBoxForm();
    }

	public function register()
	{
		add_filter('woocommerce_product_data_tabs', array($this, 'onProductDataTabs'));
		add_filter('woocommerce_product_data_panels', array($this, 'onProductDataPanels'));
		add_action('admin_head', array($this, 'onAdminHead'));

		add_action('woocommerce_admin_process_product_object', array($this, 'onSaveProduct'));
		add_action('woocommerce_save_product_variation', array($this, 'onSaveProductVariation'), 1, 2);
	}

	public function onAdminHead()
	{
?>
		<style>
			#woocommerce-product-data ul.wc-tabs li.<?php echo $this->id; ?>_tab a:before { font-family: WooCommerce; content: '\e015'; }
			#<?php echo $this->id; ?>_settings .postbox { border: none; }
		</style>
<?php
	}

	public function onProductDataTabs($tabs)
	{
		$tabs[$this->id] = array(
			'label' => esc_html__('Radio Buttons and Swatches', $this->id),
			'target' => $this->id . '_settings',
			'class' => array('show_if_variable')
		);

		return $tabs;
	}

	public function onProductDataPanels()
	{
		$this->displayForm();
	}
	
	protected function displayForm()
	{
		global $post;

		$productId = 0;
		if (!empty($post->ID)) {
			$productId = $post->ID;
		}

		$product = wc_get_product($productId);
		$attributes = $product->get_attributes('edit');
?>
		<div id="<?php echo $this->id; ?>_settings" class="panel wc-metaboxes-wrapper woocommerce_options_panel">
			<div class="toolbar toolbar-top">
				<strong><?php esc_html_e('Radio Buttons and Swatches', $this->id); ?></strong>
				<div class="variations-pagenav">
					<span class="expand-close">
						<a href="#" class="expand_all">Expand</a> / <a href="#" class="close_all">Close</a>
					</span>
				</div>
				<div class="clear"></div>
				<div><?php esc_html_e('Customize look and behavior of attribute selectors of this product.', $this->id); ?></div>
			</div>
			<div class="wc-metaboxes">
<?php
			$attributeIdx = 0;
			foreach ($attributes as $attribute) {
				if (is_object($attribute) && $attribute->get_variation()) {
					$this->displayAttributeSettings($product, $attribute, $attributeIdx);
					$attributeIdx++;
				}
			}

?>
			</div>
		</div>
<?php
	}

	protected function displayAttributeSettings($product, $attribute, $attributeIdx)
	{
		if (!is_object($product) || !is_object($attribute) || !$attribute->get_variation()) {
			return;
		}

		$settings = $this->getProductSettings($product->get_id());
		if (empty($settings)) {
			$settings = array();
		} else {
			$newSettings[$this->id][$product->get_id()] = $settings;
			$settings = $newSettings;
		}

		$attributeName = esc_attr(sanitize_title($attribute->get_name()));

		$formFields = $this->getAttributeFormFields($product, $attributeName, $attributeIdx);
		$this->formFilter->setFields($formFields);
		$this->metaBoxForm->setFields($this->formFilter->getFields($settings));
?>
		<div class="woocommerce_variation wc-metabox postbox">
			<h3>
				<div class="handlediv" title="Click to toggle" aria-expanded="false"></div>
				<strong class="attribute_name"><?php echo wc_attribute_label($attributeName); ?></strong>
			</h3>
			<div class="woocommerce_variable_attributes wc-metabox-content">
				<div class="data">
<?php
				echo $this->getAttributeProVersionMessage($attributeIdx);
				$this->metaBoxForm->display();
?>
				</div>
			</div>
		</div>
<?php
	}

	protected function getAttributeProVersionMessage($attributeIdx)
	{
		$message = apply_filters($this->id . '_pro_version_feature_message', '');
		if ($attributeIdx == 0) {
			$message = '';
		}

		if (!empty($message)) {
			$message = sprintf('<p>%s</p>', $message);
		}

		return $message;
	}

	protected function getProductSettings($productId)
	{
		$settings = array();
		if (isset($this->productSettings[$productId])) {
			$settings = $this->productSettings[$productId];
		} else {
			$settings = get_post_meta($productId, $this->id, true);
			if (empty($settings)) {
				$settings = array();
			}
			$settings = array_merge($this->settings, $settings);

			$this->productSettings[$productId] = $settings;
		}
		
		return $settings;
	}

	public function onSaveProduct($product)
	{
		if (empty($product)) {
			return;
		}

		$this->saveProductSettings($product);
	}

	public function onSaveProductVariation($productId, $i)
	{
		if (empty($productId)) {
			return;
		}

		$this->saveProductSettings(wc_get_product($productId));
	}

	protected function saveProductSettings($product)
	{
		if (!is_object($product)) {
			return;
		}

		$errors = array();
		$settings = array();

		$attributeIdx = 0;
		$attributes = $product->get_attributes('edit');
		foreach ($attributes as $attribute) {
			if (is_object($attribute) && $attribute->get_variation()) {
				$attributeName = esc_attr(sanitize_title($attribute->get_name()));
				$settings += $this->getPostedAttributeSettings($product, $attributeName, $attributeIdx, $errors);
				$attributeIdx++;
			}
		}
		
		if (empty($errors)) {
			if (!empty($settings)) {
				update_post_meta($product->get_id(), $this->id, $settings);
			} else {
				delete_post_meta($product->get_id(), $this->id);
			}
		}
	}

	protected function getPostedAttributeSettings($product, $attributeName, $attributeIdx, &$errors)
	{
		if (!is_object($product) || empty($attributeName)) {
			return array();
		}

		$settings = array();
		if (empty($this->getAttributeProVersionMessage($attributeIdx))) {
			$formFields = $this->getAttributeFormFields($product, $attributeName, $attributeIdx);
			$this->formFilter->setFields($formFields);
			$attributeData = $this->formFilter->filter($_POST);
			if ($attributeData === false) {
				$errors += $this->formFilter->getErrors();
			} else {
				$settings = $attributeData[$this->id][$product->get_id()];
			}
		}

		return apply_filters($this->id . '_posted_attribute_settings', $settings, $product->get_id(), $attributeName, $attributeIdx);
	}

	protected function getAttributeFormFieldCustomAttributes($product, $attributeName, $attributeIdx)
	{
		$customAttributes = array();
		if (!empty($this->getAttributeProVersionMessage($attributeIdx))) {
			$customAttributes = array(
				'disabled' => 'yes'
			);
		}

		return $customAttributes;
	}

	protected function getAttributeFormFields($product, $attributeName, $attributeIdx)
	{
		if (!is_object($product) || empty($attributeName)) {
			return array();
		}

		$customAttributes = $this->getAttributeFormFieldCustomAttributes($product, $attributeName, $attributeIdx);

		$formFields = array(
			array(
				'id' => sprintf('%s[%s][%s][enabled]', $this->id, $product->get_id(), $attributeName),
				'type' => 'select',
				'label' => __('Enabled', $this->id),
				'options' => array(
					'' => __('Use global settings', $this->id),
					1 => __('Enabled', $this->id),
					0 => __('Disabled', $this->id)
				),
				'wrapper_class' => 'show_if_variable',
				'custom_attributes' => $customAttributes
			),

			array(
				'id' => sprintf('%s[%s][%s][attributeLabelLocation]', $this->id, $product->get_id(), $attributeName),
				'type' => 'select',
				'label' => __('Attribute Label Location', $this->id),
				'wrapper_class' => 'show_if_variable',
				'options' => array_merge(array(
					'' => __('Use global settings', $this->id),
				), apply_filters($this->id . '_attribute_label_locations', array())),
				'custom_attributes' => $customAttributes
			),

			array(
				'id' => sprintf('%s[%s][%s][selectorStyle]', $this->id, $product->get_id(), $attributeName),
				'type' => 'select',
				'label' => __('Selection Style', $this->id),
				'wrapper_class' => 'show_if_variable',
				'options' => array_merge(array(
					'' => __('Use global settings', $this->id),
				), apply_filters($this->id . '_selector_styles', array())),
				'custom_attributes' => $customAttributes
			),
				
			array(
				'id' => sprintf('%s[%s][%s][optionsOrientation]', $this->id, $product->get_id(), $attributeName),
				'type' => 'select',
				'label' => __('Options Orientation', $this->id),
				'wrapper_class' => 'show_if_variable',
				'options' => array(
					'' => __('Use global settings', $this->id),
					'vertical' => __('Vertical', $this->id),
					'horizontal' => __('Horizontal', $this->id),
				),
				'custom_attributes' => $customAttributes
			),
			
			array(
				'id' => sprintf('%s[%s][%s][displayTooltipSource]', $this->id, $product->get_id(), $attributeName),
				'type' => 'select',
				'label' => __('What to Display in Tooltip?', $this->id),
				'wrapper_class' => 'show_if_variable',
				'options' => array(
					'' => __('Use global settings', $this->id),
					'hidden' => __('Nothing', $this->id),
					'label' => __('Term Name', $this->id),
					'description' => __('Term Description', $this->id),
				),
				'custom_attributes' => $customAttributes
			),
			
			array(
				'id' => sprintf('%s[%s][%s][displayTooltipLocation]', $this->id, $product->get_id(), $attributeName),
				'type' => 'select',
				'label' => __('Tooltip Location', $this->id),
				'wrapper_class' => 'show_if_variable',
				'options' => array(
					'' => __('Use global settings', $this->id),
					'top' => __('Top', $this->id),
					'bottom' => __('Bottom', $this->id),
				),
				'custom_attributes' => $customAttributes
			),

			array(
				'id' => sprintf('%s[%s][%s][borderRadius]', $this->id, $product->get_id(), $attributeName),
				'label' => __('Border Radius', $this->id),
				'type' => 'text',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^[0-9]+(\.[0-9]+)?(px|em|pt|%)$/')),
				'optional' => true,
				'custom_attributes' => $customAttributes
			),

			array(
				'id' => sprintf('%s[%s][%s][width]', $this->id, $product->get_id(), $attributeName),
				'label' => __('Width', $this->id),
				'type' => 'text',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^[0-9]+(\.[0-9]+)?(px|em|pt|%)$/')),
				'optional' => true,
				'custom_attributes' => $customAttributes
			),

			array(
				'id' => sprintf('%s[%s][%s][height]', $this->id, $product->get_id(), $attributeName),
				'label' => __('Height', $this->id),
				'type' => 'text',
				'filter' => FILTER_VALIDATE_REGEXP,
				'filter_options' => array('options' => array('regexp' => '/^[0-9]+(\.[0-9]+)?(px|em|pt|%)$/')),
				'optional' => true,
				'custom_attributes' => $customAttributes
			),

			array(
				'id' => sprintf('%s[%s][%s][textAlign]', $this->id, $product->get_id(), $attributeName),
				'label' => __('Text Alignment', $this->id),
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
				'id' => sprintf('%s[%s][%s][displayThumbnailPriority]', $this->id, $product->get_id(), $attributeName),
				'type' => 'select',
				'label' => __('Display Thumbnail Priority', $this->id),
				'wrapper_class' => 'show_if_variable',
				'options' => $this->priorityOptions,
				'custom_attributes' => $customAttributes
			),

			array(
				'id' => sprintf('%s[%s][%s][displayNamePriority]', $this->id, $product->get_id(), $attributeName),
				'type' => 'select',
				'label' => __('Display Name Priority', $this->id),
				'wrapper_class' => 'show_if_variable',
				'options' => $this->priorityOptions,
				'custom_attributes' => $customAttributes
			),
		);

		$numberOfAttributes = 0;
		$attributes = $product->get_attributes('edit');
		foreach ($attributes as $attribute) {
			if (is_object($attribute) && $attribute->get_variation()) {
				$numberOfAttributes++;
			}
		}

		if ($numberOfAttributes == 1) {
			$formFields = array_merge($formFields, array(	
				array(
					'id' => sprintf('%s[%s][%s][displayStockPriority]', $this->id, $product->get_id(), $attributeName),
					'type' => 'select',
					'label' => __('Display Stock Priority', $this->id),
					'wrapper_class' => 'show_if_variable',
					'options' => $this->priorityOptions,
					'custom_attributes' => $customAttributes
				),
	
				array(
					'id' => sprintf('%s[%s][%s][displayPricePriority]', $this->id, $product->get_id(), $attributeName),
					'type' => 'select',
					'label' => __('Display Price Priority', $this->id),
					'wrapper_class' => 'show_if_variable',
					'options' => $this->priorityOptions, 
					'custom_attributes' => $customAttributes
				),
			));
		}

		$formFields = array_merge($formFields, array(
			array(
				'id' => sprintf('%s[%s][%s][displayAttributeDescription]', $this->id, $product->get_id(), $attributeName),
				'type' => 'select',
				'label' => __('Display Attribute Description', $this->id),
				'wrapper_class' => 'show_if_variable',
				'options' => array(
					'' => __('Use global settings', $this->id),
					false => __('Do not display', $this->id),
					true => __('Display', $this->id),
				),
				'custom_attributes' => $customAttributes
			),
		));

		return apply_filters($this->id . '_product_attribute_form_fields', $formFields, $product->get_id(), $attributeName, $attributeIdx);
	}
};

endif;
