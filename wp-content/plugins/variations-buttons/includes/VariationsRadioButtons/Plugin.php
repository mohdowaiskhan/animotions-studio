<?php
/*********************************************************************/
/* PROGRAM    (C) 2022 FlexRC                                        */
/* PROPERTY   3-7170 Ash Cres                                        */
/* OF         Vancouver, BC V6P3K7                                   */
/*            CANADA                                                 */
/*            Voice (604) 800-7879                                   */
/*********************************************************************/

namespace OneTeamSoftware\WooCommerce\VariationsRadioButtons;

defined('ABSPATH') || exit;

if (!class_exists(__NAMESPACE__ . '\\Plugin')):

class Plugin
{
	protected $id;
	protected $version;
	protected $name;
	protected $pluginPath;
	protected $selectorStyles;
	protected $settings;
	protected $productSettings;
	protected $productVariations;
	protected $addedToProducts;

	public function __construct($pluginPath, $version, $name)
	{
		$this->id = str_replace('-pro', '', basename($pluginPath, '.php'));
		$this->pluginPath = $pluginPath;
		$this->version = $version;
		$this->name = $name;

		$this->selectorStyles = array(
			'default' => __('WooCommerce Default', $this->id),
			'radio' => __('Radio Buttons', $this->id),
			'swatch' => __('Swatches', $this->id)
		);

		$this->attributeLabelLocations = array(
			'default' => __('WooCommerce Default', $this->id),
			'left' => __('Left', $this->id),
			'top' => __('Top', $this->id)
		);
		
		$this->settings = array(
			'enabled' => true,
			'selectorStyle' => 'radio',
			'attributeLabelLocation' => 'left',
			'optionsOrientation' => 'vertical',
			'displayTooltipSource' => 'hidden',
			'displayThumbnailPriority' => '',
			'displayNamePriority' => 2,
			'displayStockPriority' => '',
			'displayPricePriority' => '',
			'displayAttributeDescription' => false,
		);

		$this->productSettings = array();
		$this->productVariations = array();
		$this->addedToProducts = array();
	}

	protected function isActive()
	{
		$pluginDependency = new \OneTeamSoftware\WooCommerce\Utils\PluginDependency($this->id, $this->name);
		$pluginDependency->add('woocommerce/woocommerce.php', __('WooCommerce', $this->id), admin_url('/plugin-install.php?tab=plugin-information&plugin=woocommerce&TB_iframe=true&width=600&height=550'));
		$pluginDependency->register();

		if (!$pluginDependency->validate()) {
			return false;
		}

		$proPluginName = preg_replace('/(\.php|\/)/i', '-pro\\1', plugin_basename($this->pluginPath));
		if (is_plugin_active($proPluginName)) {
			return false;
		}

		return true;
	}

	public function register()
	{
		if (!$this->isActive()) {
			return false;
		}

		$this->loadSettings();
		
		add_filter('plugin_action_links_' . plugin_basename($this->pluginPath), array($this, 'onPluginActionLinks'), 1, 1);
		add_filter($this->id . '_selector_styles', array($this, 'onSelectorStyles'), 1, 1);
		add_filter($this->id . '_attribute_label_locations', array($this, 'onAttributeLabelLocations'), 1, 1);
		add_filter($this->id . '_pro_version_feature_message', array($this, 'onProVersionFeatureMessage'), 1, 1);

		if (is_admin()) {
			add_action($this->id . '_settings_tabs', array($this, 'onSettingsTabs'));

			$this->registerAdminModules();
		} else {
			add_action('init', array($this, 'onInit'));
		}

		return true;
	}

	protected function registerAdminModules()
	{
		(new Admin\ProductSettingsForm($this->id, $this->settings))->register();
		(new Admin\SettingsPage($this->id, $this->name))->register();
		(new Admin\TermExtras($this->id))->register();
		(new Admin\AttributeSettingsForm($this->id))->register();
	}

	public function onInit()
	{
		add_filter('woocommerce_locate_template', array($this, 'onLocateTemplate'), PHP_INT_MAX, 3);
		add_action('wp_enqueue_scripts', array($this, 'addToPage'), 1);
		add_filter('woocommerce_dropdown_variation_attribute_options_html', array($this, 'onAttributeOptionsHtml'), PHP_INT_MAX, 2);	
		
		add_filter($this->id . '_label_style', array($this, 'onLabelStyle'), 1, 2);
		add_filter($this->id . '_tooltip_title', array($this, 'onTooltipTitle'), 1, 2);
		add_filter($this->id . '_tooltip_at', array($this, 'onTooltipAt'), 1, 2);
		add_filter($this->id . '_tooltip_my', array($this, 'onTooltipMy'), 1, 2);
	}

	public function onPluginActionLinks($links)
	{
		$link = sprintf('<a href="%s">%s</a>', admin_url('admin.php?page=' . $this->id), __('Settings', $this->id));
		array_unshift($links, $link);

		return $links;
	}

	public function onSelectorStyles($selectorStyles = array())
	{
		return array_replace_recursive($this->selectorStyles, $selectorStyles);
	}

	public function onAttributeLabelLocations($attributeLabelLocations = array())
	{
		return array_replace_recursive($this->attributeLabelLocations, $attributeLabelLocations);
	}

	public function onSettingsTabs($pageTabs)
	{
		if (!is_object($pageTabs)) {
			return;
		}

		$pageTabs->addTab(new Admin\StylesTab($this->id));
	}

	public function onProVersionFeatureMessage($message)
	{
		return sprintf('<strong>%s <a href="%s" target="_blank">%s</a></strong>', 
				__('The following feature requires', $this->id), 
				'https://1teamsoftware.com/product/' . $this->id . '-pro/',
				__('PRO Version', $this->id)
			);
	}

	public function onLocateTemplate($template, $templateName, $templatePath)
	{
		$pluginPath = plugin_dir_path($this->pluginPath) . 'templates/';

		if (file_exists($pluginPath . $templateName)) {
			$template = $pluginPath . $templateName;
		}

		return $template;
	}

	public function enqueueScripts()
	{
		$cssExt = 'min.css';
		$jsExt = 'min.js';
		if (defined('WP_DEBUG') && WP_DEBUG) {
			$cssExt = 'css';
			$jsExt = 'js';
		}

		wp_register_style($this->id . '_tooltip', plugins_url('assets/css/tooltip.' . $cssExt, str_replace('phar://', '', $this->pluginPath)), array(), $this->version);
		wp_enqueue_style($this->id . '_tooltip');

		wp_register_script($this->id . '_tooltip', plugins_url('assets/js/tooltip.' . $jsExt, str_replace('phar://', '', $this->pluginPath)), array('jquery', 'jquery-ui-tooltip'), $this->version);
		wp_enqueue_script($this->id . '_tooltip');

		wp_register_script($this->id, plugins_url('assets/js/VariationsRadioButtons.' . $jsExt, str_replace('phar://', '', $this->pluginPath)), array('wc-add-to-cart-variation'), $this->version);
		wp_enqueue_script($this->id);

		wp_register_style($this->id, plugins_url('assets/css/VariationsRadioButtons.' . $cssExt, str_replace('phar://', '', $this->pluginPath)), array(), $this->version);
		wp_enqueue_style($this->id);
	}

	public function addToPage()
	{
		$this->addToProduct(wc_get_product());
	}

	protected function addToProduct($product)
	{
		if (!($product instanceof \WC_Product_Variable)) {
			return;
		}

		$settings = $this->getProductSettings($product);
		if (empty($settings['enabled'])) {
			return;
		}

		if (!empty($this->addedToProducts[$product->get_id()])) {
			return;
		}
		$this->addedToProducts[$product->get_id()] = true;

		$this->enqueueScripts();
		$this->enqueueProductStyle($product);
		$this->loadProductVariations($product);
	}

	public function onLabelStyle($style, $params)
	{
		$settings = $this->getProductSettingsByParams($params);

		if (empty($settings['selectorStyle']) || $settings['selectorStyle'] != 'swatch') {
			return $style;
		}

		if (!empty($params['color'])) {
			if (empty($style)) {
				$style = '';
			}

			$style .= 'background-color: ' . $params['color'] . ' !important;';
		}

		return $style;
	}

	public function onTooltipTitle($title, $params)
	{
		$settings = $this->getProductSettingsByParams($params);
		if (empty($settings['displayTooltipSource'])) {
			return $title;
		}

		$source = $settings['displayTooltipSource'];
		if (!empty($params[$source])) {
			$title = $params[$source];
		}

		return $title;
	}

	public function onTooltipAt($at, $params)
	{
		$settings = $this->getProductSettingsByParams($params);
		if (empty($settings['displayTooltipLocation'])) {
			return $at;
		}

		if ($settings['displayTooltipLocation'] == 'top') {
			$at = 'left top';
		} else {
			$at = 'left bottom';
		}

		return $at;
	}

	public function onTooltipMy($my, $params)
	{
		$settings = $this->getProductSettingsByParams($params);
		if (empty($settings['displayTooltipLocation'])) {
			return $my;
		}

		if ($settings['displayTooltipLocation'] == 'top') {
			$my = 'left bottom';
		} else {
			$my = 'left top';
		}

		return $my;
	}

	public function onAttributeOptionsHtml($html, $args)
	{		
		if (empty($args['product']) || !($args['product'] instanceof \WC_Product_Variable)) {
			return $html;
		}

		if (!isset($args['options']) || !isset($args['attribute'])) {
			return $html;
		}

		$attribute = esc_attr(sanitize_title($args['attribute']));
		$args['attribute'] = $attribute;
		$product = $args['product'];
		$settings = $this->getProductSettings($product);
		if (empty($settings[$attribute]['enabled'])) {
			return $html;
		}

		if (isset($settings[$attribute])) {
			$this->setItemLocations($settings[$attribute]);
		}

		if (isset($settings[$attribute]['selectorStyle'])) {
			$args['selectorStyle'] = $settings[$attribute]['selectorStyle'];
		} else {
			$args['selectorStyle'] = 'radio';
		}

		if (empty($args['name'])) {
			$args['name'] = 'attribute_' . sanitize_title($attribute);
		}

		$options = $args['options'];
		if (empty($options) && !empty($product) && !empty($attribute)) {
			$attributes = $product->get_variation_attributes();
			$options = $attributes[$attribute];
		}

		$newOptions = array();

		if ($product && taxonomy_exists($attribute)) {
			$terms = wc_get_product_terms($product->get_id(), $attribute, array('fields' => 'all'));

			foreach ($terms as $term) {
				if (in_array($term->slug, $options)) {
					$newOption = array(
						'label' => $term->name,
						'description' => $term->description,
						'value' => $term->slug,
						'selected' => $term->slug == sanitize_title($args['selected'])
					);

					$termExtras = get_term_meta($term->term_id, $this->id, true);
					if (is_array($termExtras)) {
						$newOption = array_replace_recursive($newOption, $termExtras);
					}

					$newOptions[] = $newOption;
				}
			}
		} else {
			foreach ($options as $value) {
				$newOptions[] = array(
					'label' => $value,
					'description' => '',
					'value' => $value,
					'selected' => sanitize_title($value) == sanitize_title($args['selected'])
				);
			}
		}
		
		$args['options'] = $newOptions;
		$args['id'] = $this->id;
		
		$html = wc_get_template_html($this->id . '/selector.php', $args);

		return $html;
	}

	public function displayThumbnail($params)
	{
		$imageId = 0;
		if (!empty($params['imageId'])) {
			$imageId = $params['imageId'];
		} else {
			$variation = $this->getProductVariation($params);
			if (!empty($variation['image_id'])) {
				$imageId = $variation['image_id'];
			}
		}

		if (!empty($imageId)) {
			echo wp_get_attachment_image($imageId, 'thumbnail');
		}
	}

	public function displayPrice($params)
	{
		$variation = $this->getProductVariation($params);

		if (!empty($variation['display_price'])) {
			if (isset($variation['display_regular_price']) && $variation['display_regular_price'] != $variation['display_price']) {
				echo wc_format_sale_price(wc_price($variation['display_regular_price']), wc_price($variation['display_price']));
			} else {
				echo wc_price($variation['display_price']);
			}
		}
	}

	public function displayName($params)
	{
		echo sprintf('<span class="name">%s</span>', esc_html(apply_filters('woocommerce_variation_option_name', $params['label'])));
	}

	public function displayStock($params)
	{
		$variation = $this->getProductVariation($params);

		if (isset($variation['is_in_stock']) && !empty($variation['is_purchasable'])) {
			$html = wc_get_template_html('single-product/stock.php', array(
				'product' => $params['product'],
				'class' => !empty($variation['is_in_stock']) ? 'in-stock' : 'out-of-stock',
				'availability' => !empty($variation['is_in_stock']) ? __('In stock', $this->id) : __('Out of stock', $this->id),
			));

			echo apply_filters('woocommerce_get_stock_html', $html, $params['product']);
		}
	}

	public function displayAttributeDescription($params)
	{
		if (!empty($params['description'])) {
			echo sprintf('<div class="description">%s</div>', $params['description']);
		}
	}

	protected function loadSettings()
	{
		$this->settings = array_replace_recursive($this->settings, get_option($this->id, array()));
	}

	protected function setItemLocations($settings)
	{
		remove_all_actions($this->id . '_label');
		remove_all_actions($this->id . '_after_label');

		if (!empty($settings['displayThumbnailPriority']) && is_numeric($settings['displayThumbnailPriority'])) {
			add_action($this->id . '_label', array($this, 'displayThumbnail'), $settings['displayThumbnailPriority'], 1);
		}

		if (!empty($settings['displayNamePriority']) && is_numeric($settings['displayNamePriority'])) {
			add_action($this->id . '_label', array($this, 'displayName'), $settings['displayNamePriority'], 1);
		}

		if (!empty($settings['displayStockPriority']) && is_numeric($settings['displayStockPriority'])) {
			add_action($this->id . '_label', array($this, 'displayStock'), $settings['displayStockPriority'], 1);
		}

		if (!empty($settings['displayPricePriority']) && is_numeric($settings['displayPricePriority'])) {
			add_action($this->id . '_label', array($this, 'displayPrice'), $settings['displayPricePriority'], 1);
		}

		if (!empty($settings['displayAttributeDescription'])) {
			add_action($this->id . '_after_label', array($this, 'displayAttributeDescription'), 1, 1);
		}
	}

	protected function getProductSettingsByParams($params)
	{
		if (empty($params['product']) || empty($params['attribute'])) {
			return array();
		}

		$settings = $this->getProductSettings($params['product']);
		if (empty($settings[$params['attribute']])) {
			$settings = array();
		} else {
			$settings = $settings[$params['attribute']];
		}

		return $settings;
	}

	protected function getAttributeSettings($termId)
	{
		return array();
	}

	protected function getProductSettings($product)
	{
		$productId = $product->get_id();
		if (isset($this->productSettings[$productId])) {
			return $this->productSettings[$productId];
		}

		$settings = get_post_meta($productId, $this->id, true);
		if (empty($settings)) {
			$settings = array();
		}

		$settings = array_replace_recursive(
			array(
				'enabled' => true
			), 
			$settings
		);
		
		$attributes = $product->get_attributes('edit');
		$index = 1;
		foreach ($attributes as $attribute) {
			if (is_object($attribute) && $attribute->get_variation()) {
				$attributeName = esc_attr(sanitize_title($attribute->get_name()));
				if (empty($settings[$attributeName])) {
					$settings[$attributeName] = array();
				}

				$settings[$attributeName]['index'] = $index++;

				$attributeSettings = $this->getAttributeSettings($attribute->get_id());
				foreach ($attributeSettings as $key => $value) {
					if (!isset($settings[$attributeName][$key]) || (empty($settings[$attributeName][$key]) && !is_numeric($settings[$attributeName][$key]))) {
						$settings[$attributeName][$key] = $value;
					}
				}

				foreach ($this->settings as $key => $value) {
					if (!isset($settings[$attributeName][$key]) || (empty($settings[$attributeName][$key]) && !is_numeric($settings[$attributeName][$key]))) {
						$settings[$attributeName][$key] = $value;
					}
				}
			}
		}

		$this->productSettings[$productId] = $settings;
		
		return $settings;
	}

	protected function loadProductVariations($product)
	{
		$this->productVariations[$product->get_id()] = array();
		$productVariations = $product->get_available_variations();

		foreach ($productVariations as $variation) {
			if (count($variation['attributes']) == 1) {
				$name = current(array_keys($variation['attributes']));
				$value = current(array_values($variation['attributes']));
	
				$this->productVariations[$product->get_id()][$name][$value] = $variation;	
			}
		}
	}
	
	protected function getProductVariation($params)
	{
		if (empty($params['name']) || empty($params['value']) || !is_object($params['product'])) {
			return array();
		}

		$name = $params['name'];
		$value = $params['value'];
		$product = $params['product'];
		
		$variation = array();
		if (isset($this->productVariations[$product->get_id()][$name][$value])) {
			$variation = $this->productVariations[$product->get_id()][$name][$value];
		}
		
		return $variation;
	}

	protected function enqueueProductStyle($product)
	{
		$settings = $this->getProductSettings($product);

		$style = $this->getInlineStyle($settings);
		if (!empty($style)) {
			$styleId = $this->id . '-inline-css';

			wp_register_style($styleId, false);
    		wp_enqueue_style($styleId);
			wp_add_inline_style($styleId, $style);
		}
	}

	protected function getInlineStyle($settings)
	{
		$style = apply_filters($this->id . '_inline_style', '', $settings);
		foreach ($settings as $attributeName => $attributeSettings) {
			if (empty($attributeSettings['enabled'])) {
				continue;
			}

			$selectorStyle = $attributeSettings['selectorStyle'];

			$style .= sprintf(".variations :nth-child(%s) > .label { padding: 0 !important; }", $attributeSettings['index']);

			if ($attributeSettings['attributeLabelLocation'] == 'top') {
				$style .= sprintf(".variations :nth-child(%s) > .label { display: block !important; }\n", $attributeSettings['index']);
			} else if ($attributeSettings['attributeLabelLocation'] == 'left') {
				$style .= sprintf(".woocommerce form.cart .variations > :nth-child(%s), .woocommerce form.cart .variations > :nth-child(%s) > * { display: table-row; }", $attributeSettings['index'], $attributeSettings['index']);
				$style .= sprintf(".variations :nth-child(%s) > .label { display: table-cell !important; }\n", $attributeSettings['index']);
			}

			if ($attributeSettings['optionsOrientation'] == 'horizontal') {
				$style .= sprintf(".variations .value .%s.%s { display: flex !important; flex-wrap: wrap !important; }\n", 
					$attributeName,
					$selectorStyle);
			}

			if (!empty($attributeSettings['borderRadius'])) {
				$style .= sprintf(".variations .value .%s.%s label { border-radius: %s !important; }\n", 
					$attributeName,
					$selectorStyle,
					$attributeSettings['borderRadius']
				);

				$style .= sprintf(".variations .value .%s.%s label div.label { overflow: hidden; border-radius: %s !important; }\n", 
					$attributeName,
					$selectorStyle,
					$attributeSettings['borderRadius']
				);
			}

			if (!empty($attributeSettings['width'])) {
				$style .= sprintf(".variations .value .%s.%s label { width: %s !important; }\n", 
					$attributeName,
					$selectorStyle,
					$attributeSettings['width']
				);
			}

			if (!empty($attributeSettings['height'])) {
				$style .= sprintf(".variations .value .%s.%s label { height: %s !important; }\n", 
					$attributeName,
					$selectorStyle,
					$attributeSettings['height']
				);
			}
			
			if (!empty($attributeSettings['textAlign'])) {				
				$style .= sprintf(".variations .value .%s.%s label { text-align: %s !important; }\n", 
					$attributeName,
					$selectorStyle,
					$attributeSettings['textAlign']
				);

				$justifyContent = '';
				switch ($attributeSettings['textAlign']) {
					case 'right':
						$justifyContent = 'flex-end';
						break;
					case 'center':
						$justifyContent = 'center';
						break;
					case 'justify':
						$justifyContent = 'space-around';
						break;
					default:
						$justifyContent = 'flex-start';
				}

				$style .= sprintf(".variations .value .%s.%s label div.label { justify-content: %s !important; }\n", 
					$attributeName,
					$selectorStyle,
					$justifyContent
				);
			}
		}

		return $style;
	}
}

endif;
