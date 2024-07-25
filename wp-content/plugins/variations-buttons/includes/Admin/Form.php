<?php
/*********************************************************************/
/* PROGRAM    (C) 2022 FlexRC                                        */
/* PROPERTY   3-7170 Ash Cres                                        */
/* OF         Vancouver, BC V6P3K7                                   */
/*            CANADA                                                 */
/*            Voice (604) 800-7879                                   */
/*********************************************************************/

namespace OneTeamSoftware\WooCommerce\Admin;

//declare(strict_types=1);

defined('ABSPATH') || exit;

// make sure that we will include shared class only once
if (!class_exists(__NAMESPACE__ . '\\Form')):

class Form
{
	protected $formFilter;
	protected $fields;
	protected $colorPickClasses;
	protected $images;
	protected $sections;
	protected $isMenuDisplayed;

   /**
     * Constructor
	 * 
	 * Example:
	 * array(   
	 *		'supplier_start' => array(
	 *			'title' => __('Inventory Supplier Settings', $this->id),
	 *			'type' => 'title',
	 *			'id' => $this->id . '_supplier_start'
	 *		),
     *       'enabled' => array(
	 *			'id' => 'enabled',
     *           'title' => __('Enable', $this->id),
     *           'type' => 'checkbox',
     *           'desc_tip' => __('Enable this inventory supplier.', $this->id),
	 *			'default' => 'yes',
	 *		),
	 * 		'image_rule' => array(
	 *			'id' => 'image_rule',
     *           'title' => __('Images', $this->id),
     *           'placeholder' => __('Rule for parsing product images?', $this->id),
	 *			'type' => 'text',
	 *			'desc_tip' => __('Rule that is used for parsing images of the product.', $this->id),
	 *			'filter' => FILTER_VALIDATE_REGEXP,
	 *			'filter_options' => array('options' => array('regexp' => '/^.{0,255}$/')),
	 *			'optional' => true,
	 *			'sanitize_function' => 'sanitize_text_field',	
	 *		),
	 *      'supplier_end' => array(
	 *			'type' => 'sectionend', 
	 *			'id' => $this->id . '_supplier_end'
	 *		),
	 *);
	 * 
	 * @param array
	 * @param string
     */
    public function __construct(array $fields = array()) 
    {
		require_once(__DIR__ . '/FormFilter.php');
		$this->formFilter = new FormFilter($fields);

		$this->setFields($fields);

		add_action('admin_enqueue_scripts', array($this, 'onEnqueueScripts'));
		add_filter('woocommerce_screen_ids', array($this, 'setScreenIds'));
	}

	/**
     * Add required scripts and styles
	 * 
	 * @return void
     */
	public function onEnqueueScripts()
	{
		if (!function_exists('wp_enqueue_media') && file_exists(ABSPATH . 'wp-includes/media.php')) {
			include_once(ABSPATH . 'wp-includes/media.php');
		}

		if (!function_exists('wp_get_default_extension_for_mime_type') && file_exists(ABSPATH . 'wp-includes/functions.php')) {
			include_once(ABSPATH . 'wp-includes/functions.php');
		}

		wp_enqueue_media();
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wp-color-picker');
	}

	/**
     * Register current screen in woocommerce, so all required scripts and styles will be loaded
	 * 
	 * @return array
     */
	public function setScreenIds($screenIds)
	{
		$screen = get_current_screen();
		if (is_object($screen)) {
			$screenIds[] = $screen->id;
		}
		
		return $screenIds;
	}

	/**
     * Set fields
	 * 
	 * @return void
     */
	public function setFields(array $fields)
	{
		$this->fields = $fields;
		$this->formFilter->setFields($fields);

		$this->colorPickClasses = array();
		$this->images = array();
		$this->sections = array();
		$this->isMenuDisplayed = false;
	}

	/**
     * Returns form fields
	 * 
	 * @return array
     */
	public function getFields(array $data = array())
	{
		return $this->formFilter->getFields($data);
	}

	/**
     * Returns sections
	 * 
	 * @return array
     */
	public function getSections()
	{
		if (!empty($this->sections)) {
			return $this->sections;
		}

		foreach ($this->fields as $field) {
			if (!empty($field['id']) && !empty($field['type']) && !empty($field['title']) && $field['type'] == 'title') {
				$this->sections[$field['id']] = $field['title'];
			}
		}

		return $this->sections;
	}

	/** 
	 * Returns errors occured during validation
	 * 
	 * @return array
	*/
	public function getErrors()
	{
		return $this->formFilter->getErrors();
	}

	/**
     * Filters and Validates submitted data
	 * 
	 * @param array
	 * @return array|false
     */
	public function filter(array $data)
	{
		return $this->formFilter->filter($data);
	}

	/**
	 * Modified version of WC_Admin_Settings::get_field_description
	 * that always uses tool tip when desc_tip is set
	 */
	protected function getFieldDescription($value)
	{
		$description  = '';
		$tooltip_html = '';

		if ( true === $value['desc_tip'] ) {
			$tooltip_html = $value['desc'];
		} elseif ( ! empty( $value['desc_tip'] ) ) {
			$description  = $value['desc'];
			$tooltip_html = $value['desc_tip'];
		} elseif ( ! empty( $value['desc'] ) ) {
			$description  = $value['desc'];
		}

		if ( $description && in_array( $value['type'], array( 'textarea', 'radio' ) ) ) {
			$description = '<p style="margin-top:0">' . wp_kses_post( $description ) . '</p>';
		} elseif ( $description && in_array( $value['type'], array( 'checkbox' ) ) ) {
			$description = wp_kses_post( $description );
		} elseif ( $description ) {
			$description = '<p class="description">' . wp_kses_post( $description ) . '</p>';
		}

		if ( $tooltip_html ) {
			$tooltip_html = wc_help_tip( $tooltip_html );
		}

		return array(
			'description'  => $description,
			'tooltip_html' => $tooltip_html
		);
	}

	/**
	 * Displays form sections as a menu
	 */
	public function displaySectionsMenu()
	{
		$menu = '';
		$sections = $this->getSections();
		$sectionIdx = 0;
		foreach ($sections as $sectionId => $title) {
			if (!empty($menu)) {
				$menu .= ' | ';
			}

			$menu .= sprintf('<a href="#%s"%s>%s</a>', 
				esc_attr($sectionId), 
				$sectionIdx == 0 ? ' class="current"' : '',
				esc_html($title));

			$sectionIdx++;
		}

		if (!empty($menu)) {
			$formId = md5(uniqid(rand(), true));

			echo sprintf('<p class="subsubsub" data-form="%s">%s</p><br class="clear"/>', esc_attr($formId), $menu);
			echo sprintf('
				<script>
				jQuery(window).on("load", function() {
					var currentMenuLink = jQuery();
					var hash = window.location.hash;
					if (hash && hash.length > 0) {
						currentMenuLink = jQuery(\'p[data-form="%s"] a[href="#\' + hash.substring(1) + \'"]\');
					}
					
					if (currentMenuLink.length === 0) {
						currentMenuLink = jQuery(\'p[data-form="%s"] a[href]:first\');
					}

					currentMenuLink.click();
				});

				jQuery(document).on(\'click\', \'p[data-form="%s"] a[href]\', function() {
					var currentMenuLink = jQuery(this).parent().find(\'a.current\');
					if (currentMenuLink.length > 0) {
						currentMenuLink.removeClass(\'current\');

						var sectionId = currentMenuLink.attr(\'href\').substr(1);
						if (sectionId) {
							jQuery(\'[data-section="\' + sectionId + \'"]\').hide();
						}
					}

					currentMenuLink = jQuery(this);
					var sectionId = currentMenuLink.attr(\'href\').substr(1);
					if (sectionId) {
						currentMenuLink.addClass(\'current\');
						jQuery(\'[data-section="\' + sectionId + \'"]\').show();
					}
				});
				</script>
				', 
				esc_attr($formId),
				esc_attr($formId),
				esc_attr($formId)
			);

			$this->isMenuDisplayed = true;
		}
	}

	/**
     * Displays this form
	 * 
	 * @see WC_Admin_Settings::output_fields - the difference is that we add field type class to TR tag
	 * @param array
     */
	public function display(array $data = array())
	{
		//woocommerce_admin_fields($this->getFields($data));
		$options = $this->getFields($data);
		
		$currentSectionId = current(array_keys($this->getSections()));

		$style = '';

		foreach ($options as $value) {
			if (!isset($value['type'])) {
				continue;
			}
			if (!isset($value['id'])) {
				$value['id'] = '';
			}
			if (!isset($value['title'])) {
				$value['title'] = isset($value['name']) ? $value['name'] : '';
			}
			if (!isset($value['class'])) {
				$value['class'] = '';
			}
			if (!isset($value['css'])) {
				$value['css'] = '';
			}
			if (!isset($value['default'])) {
				$value['default'] = '';
			}
			if (!isset($value['desc'])) {
				$value['desc'] = '';
			}
			if (!isset($value['desc_tip'])) {
				$value['desc_tip'] = false;
			}
			if (!isset($value['placeholder'])) {
				$value['placeholder'] = '';
			}
			if (!isset($value['suffix'])) {
				$value['suffix'] = '';
			}

			// Custom attribute handling.
			$custom_attributes = array();

			if (!empty($value['custom_attributes']) && is_array($value['custom_attributes'])) {
				foreach ($value['custom_attributes'] as $attribute => $attribute_value) {
					$custom_attributes[] = esc_attr($attribute) . '="' . esc_attr($attribute_value) . '"';
				}
			}

			// Description handling.
			//$field_description = \WC_Admin_Settings::get_field_description($value);
			$field_description = $this->getFieldDescription($value);
			$description       = $field_description['description'];
			$tooltip_html      = $field_description['tooltip_html'];

			// ADDED CODE:
			$typeAttribute = esc_attr(sanitize_title($value['type']));

			$sectionId = null;

			// Switch based on type.
			switch ($value['type']) {

				// Section Titles.
				case 'title':
					if (!empty($value['id'])) {
						$sectionId = $value['id'];
					}

					if ($this->isMenuDisplayed && $sectionId != $currentSectionId) {
						$style = 'display: none';
					}			

					if (!empty($value['title'])) {
						echo '<h2 data-section="' . $sectionId . '" style="' . $style . '">' . esc_html($value['title']) . '</h2>';
					}
					if (!empty($value['desc'])) {
						echo '<div id="' . esc_attr(sanitize_title($value['id'])) . '-description" data-section="' . $sectionId . '" style="' . $style . '">';
						echo wp_kses_post(wpautop(wptexturize($value['desc'])));
						echo '</div>';
					}
					echo '<table class="form-table" data-section="' . $sectionId . '" style="' . $style . '">' . "\n\n";
					if (!empty($value['id'])) {
						do_action('woocommerce_settings_' . sanitize_title($value['id']));
					}
					break;

				// Section Ends.
				case 'sectionend':
					$sectionId = null;

					if (!empty($value['id'])) {
						do_action('woocommerce_settings_' . sanitize_title($value['id']) . '_end');
					}
					echo '</table>';
					if (!empty($value['id'])) {
						do_action('woocommerce_settings_' . sanitize_title($value['id']) . '_after');
					}

					$style = '';

					break;

				case 'hidden':
					?>
					<input 
						name="<?php echo esc_attr($value['id']); ?>"
						id="<?php echo esc_attr($value['id']); ?>"
						type="<?php echo esc_attr($value['type']); ?>"
						value="<?php echo esc_attr($option_value); ?>"
						<?php echo implode(' ', $custom_attributes); // WPCS: XSS ok. ?>
						/>
					<?php
					break;

				// Standard text inputs and subtypes like 'number'.
				case 'text':
				case 'password':
				case 'datetime':
				case 'datetime-local':
				case 'date':
				case 'month':
				case 'time':
				case 'week':
				case 'number':
				case 'email':
				case 'url':
				case 'tel':
					$option_value = $value['default'];

					?><tr valign="top" class="form-table-row-<?php echo $typeAttribute; ?>">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
						</th>
						<td class="forminp forminp-<?php echo esc_attr(sanitize_title($value['type'])); ?>">
							<input
								name="<?php echo esc_attr($value['id']); ?>"
								id="<?php echo esc_attr($value['id']); ?>"
								type="<?php echo esc_attr($value['type']); ?>"
								style="<?php echo esc_attr($value['css']); ?>"
								value="<?php echo esc_attr($option_value); ?>"
								class="<?php echo esc_attr($value['class']); ?>"
								placeholder="<?php echo esc_attr($value['placeholder']); ?>"
								<?php echo implode(' ', $custom_attributes); // WPCS: XSS ok. ?>
								/><?php echo esc_html($value['suffix']); ?> <?php echo $description; // WPCS: XSS ok. ?>
						</td>
					</tr>
					<?php
					break;

				// Color picker.
				case 'color':
					$option_value = $value['default'];

					$colorPickClass = esc_attr($value['class']) . 'colorpick';
					$this->colorPickClasses[$colorPickClass] = $colorPickClass;

					?>
					<tr valign="top" class="form-table-row-<?php echo $typeAttribute; ?>">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
						</th>
						<td class="forminp forminp-<?php echo esc_attr(sanitize_title($value['type'])); ?>">&lrm;
							<input
								name="<?php echo esc_attr($value['id']); ?>"
								id="<?php echo esc_attr($value['id']); ?>"
								type="text"
								dir="ltr"
								style="<?php echo esc_attr($value['css']); ?>"
								value="<?php echo esc_attr($option_value); ?>"
								class="<?php echo esc_attr($value['class']); ?>colorpick"
								placeholder="<?php echo esc_attr($value['placeholder']); ?>"
								<?php echo implode(' ', $custom_attributes); // WPCS: XSS ok. ?>
								/>&lrm; <?php echo $description; // WPCS: XSS ok. ?>
								<div id="colorPickerDiv_<?php echo esc_attr($value['id']); ?>" class="colorpickdiv" style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;"></div>
						</td>
					</tr>
					<?php
					break;

				// Textarea.
				case 'textarea':
					$option_value = $value['default'];

					?>
					<tr valign="top" class="form-table-row-<?php echo $typeAttribute; ?>">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
						</th>
						<td class="forminp forminp-<?php echo esc_attr(sanitize_title($value['type'])); ?>">
							<?php echo $description; // WPCS: XSS ok. ?>

							<textarea
								name="<?php echo esc_attr($value['id']); ?>"
								id="<?php echo esc_attr($value['id']); ?>"
								style="<?php echo esc_attr($value['css']); ?>"
								class="<?php echo esc_attr($value['class']); ?>"
								placeholder="<?php echo esc_attr($value['placeholder']); ?>"
								<?php echo implode(' ', $custom_attributes); // WPCS: XSS ok. ?>
								><?php echo esc_textarea($option_value); // WPCS: XSS ok. ?></textarea>
						</td>
					</tr>
					<?php
					break;

				// Select boxes.
				case 'select':
				case 'multiselect':
					$option_value = $value['default'];

					?>
					<tr valign="top" class="form-table-row-<?php echo $typeAttribute; ?>">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
						</th>
						<td class="forminp forminp-<?php echo esc_attr(sanitize_title($value['type'])); ?>">
							<select
								name="<?php echo esc_attr($value['id']); ?><?php echo ('multiselect' === $value['type']) ? '[]' : ''; ?>"
								id="<?php echo esc_attr($value['id']); ?>"
								style="<?php echo esc_attr($value['css']); ?>"
								class="<?php echo esc_attr($value['class']); ?>"
								<?php echo implode(' ', $custom_attributes); // WPCS: XSS ok. ?>
								<?php echo 'multiselect' === $value['type'] ? 'multiple="multiple"' : ''; ?>
								>
								<?php
								foreach ($value['options'] as $key => $val) {
									?>
									<option value="<?php echo esc_attr($key); ?>"
										<?php

										if (is_array($option_value)) {
											selected(in_array((string) $key, $option_value, true), true);
										} else {
											selected($option_value, (string) $key);
										}

									?>
									>
									<?php echo esc_html($val); ?></option>
									<?php
								}
								?>
							</select> <?php echo $description; // WPCS: XSS ok. ?>
						</td>
					</tr>
					<?php
					break;

				// Radio inputs.
				case 'radio':
					$option_value = $value['default'];

					?>
					<tr valign="top" class="form-table-row-<?php echo $typeAttribute; ?>">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
						</th>
						<td class="forminp forminp-<?php echo esc_attr(sanitize_title($value['type'])); ?>">
							<fieldset>
								<?php echo $description; // WPCS: XSS ok. ?>
								<ul>
								<?php
								foreach ($value['options'] as $key => $val) {
									?>
									<li>
										<label><input
											name="<?php echo esc_attr($value['id']); ?>"
											value="<?php echo esc_attr($key); ?>"
											type="radio"
											style="<?php echo esc_attr($value['css']); ?>"
											class="<?php echo esc_attr($value['class']); ?>"
											<?php echo implode(' ', $custom_attributes); // WPCS: XSS ok. ?>
											<?php checked($key, $option_value); ?>
											/> <?php echo esc_html($val); ?></label>
									</li>
									<?php
								}
								?>
								</ul>
							</fieldset>
						</td>
					</tr>
					<?php
					break;

				// Checkbox input.
				case 'checkbox':
					$option_value     = $value['default'];
					$visibility_class = array();

					if (!isset($value['hide_if_checked'])) {
						$value['hide_if_checked'] = false;
					}
					if (!isset($value['show_if_checked'])) {
						$value['show_if_checked'] = false;
					}
					if ('yes' === $value['hide_if_checked'] || 'yes' === $value['show_if_checked']) {
						$visibility_class[] = 'hidden_option';
					}
					if ('option' === $value['hide_if_checked']) {
						$visibility_class[] = 'hide_options_if_checked';
					}
					if ('option' === $value['show_if_checked']) {
						$visibility_class[] = 'show_options_if_checked';
					}

					if (!isset($value['checkboxgroup']) || 'start' === $value['checkboxgroup']) {
						?>
							<tr valign="top" class="form-table-row-<?php echo $typeAttribute; ?> <?php echo esc_attr(implode(' ', $visibility_class)); ?>">
								<th scope="row" class="titledesc">
									<label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
								</th>
								<td class="forminp forminp-checkbox">
									<fieldset>
						<?php
					} else {
						?>
							<fieldset class="<?php echo esc_attr(implode(' ', $visibility_class)); ?>">
						<?php
					}

					if (!empty($value['title'])) {
						?>
							<legend class="screen-reader-text"><span><?php echo esc_html($value['title']); ?></span></legend>
						<?php
					}

					?>
						<label for="<?php echo esc_attr($value['id']); ?>">
							<input
								name="<?php echo esc_attr($value['id']); ?>"
								id="<?php echo esc_attr($value['id']); ?>"
								type="checkbox"
								class="<?php echo esc_attr(isset($value['class']) ? $value['class'] : ''); ?>"
								value="1"
								<?php checked($option_value, 'yes'); ?>
								<?php echo implode(' ', $custom_attributes); // WPCS: XSS ok. ?>
							/> <?php echo $description; // WPCS: XSS ok. ?>
						</label>
					<?php

					if (!isset($value['checkboxgroup']) || 'end' === $value['checkboxgroup']) {
									?>
									</fieldset>
								</td>
							</tr>
						<?php
					} else {
						?>
							</fieldset>
						<?php
					}
					break;

				// Image width settings. @todo deprecate and remove in 4.0. No longer needed by core.
				case 'image_width':
					$image_size       = str_replace('_image_size', '', $value['id']);
					$size             = wc_get_image_size($image_size);
					$width            = isset($size['width']) ? $size['width'] : $value['default']['width'];
					$height           = isset($size['height']) ? $size['height'] : $value['default']['height'];
					$crop             = isset($size['crop']) ? $size['crop'] : $value['default']['crop'];
					$disabled_attr    = '';
					$disabled_message = '';

					if (has_filter('woocommerce_get_image_size_' . $image_size)) {
						$disabled_attr    = 'disabled="disabled"';
						$disabled_message = '<p><small>' . esc_html__('The settings of this image size have been disabled because its values are being overwritten by a filter.', 'woocommerce') . '</small></p>';
					}

					?>
					<tr valign="top" class="form-table-row-<?php echo $typeAttribute; ?>">
						<th scope="row" class="titledesc">
						<label><?php echo esc_html($value['title']); ?> <?php echo $tooltip_html . $disabled_message; // WPCS: XSS ok. ?></label>
					</th>
						<td class="forminp image_width_settings">

							<input name="<?php echo esc_attr($value['id']); ?>[width]" <?php echo $disabled_attr; // WPCS: XSS ok. ?> id="<?php echo esc_attr($value['id']); ?>-width" type="text" size="3" value="<?php echo esc_attr($width); ?>" /> &times; <input name="<?php echo esc_attr($value['id']); ?>[height]" <?php echo $disabled_attr; // WPCS: XSS ok. ?> id="<?php echo esc_attr($value['id']); ?>-height" type="text" size="3" value="<?php echo esc_attr($height); ?>" />px

							<label><input name="<?php echo esc_attr($value['id']); ?>[crop]" <?php echo $disabled_attr; // WPCS: XSS ok. ?> id="<?php echo esc_attr($value['id']); ?>-crop" type="checkbox" value="1" <?php checked(1, $crop); ?> /> <?php esc_html_e('Hard crop?', 'woocommerce'); ?></label>

							</td>
					</tr>
					<?php
					break;

				// Single page selects.
				case 'single_select_page':
					$args = array(
						'name'             => $value['id'],
						'id'               => $value['id'],
						'sort_column'      => 'menu_order',
						'sort_order'       => 'ASC',
						'show_option_none' => ' ',
						'class'            => $value['class'],
						'echo'             => false,
						'selected'         => absint($value['default']),
						'post_status'      => 'publish,private,draft',
					);

					if (isset($value['args'])) {
						$args = wp_parse_args($value['args'], $args);
					}

					?>
					<tr valign="top" class="form-table-row-<?php echo $typeAttribute; ?> single_select_page">
						<th scope="row" class="titledesc">
							<label><?php echo esc_html($value['title']); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
						</th>
						<td class="forminp">
							<?php echo str_replace(' id=', " data-placeholder='" . esc_attr__('Select a page&hellip;', 'woocommerce') . "' style='" . $value['css'] . "' class='" . $value['class'] . "' id=", wp_dropdown_pages($args)); // WPCS: XSS ok. ?> <?php echo $description; // WPCS: XSS ok. ?>
						</td>
					</tr>
					<?php
					break;

				// Single country selects.
				case 'single_select_country':
					$country_setting = (string)$value['default'];

					if (strstr($country_setting, ':')) {
						$country_setting = explode(':', $country_setting);
						$country         = current($country_setting);
						$state           = end($country_setting);
					} else {
						$country = $country_setting;
						$state   = '*';
					}
					?>
					<tr valign="top" class="form-table-row-<?php echo $typeAttribute; ?>">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
						</th>
						<td class="forminp"><select name="<?php echo esc_attr($value['id']); ?>" style="<?php echo esc_attr($value['css']); ?>" data-placeholder="<?php esc_attr_e('Choose a country&hellip;', 'woocommerce'); ?>" aria-label="<?php esc_attr_e('Country', 'woocommerce'); ?>" class="wc-enhanced-select">
							<?php WC()->countries->country_dropdown_options($country, $state); ?>
						</select> <?php echo $description; // WPCS: XSS ok. ?>
						</td>
					</tr>
					<?php
					break;

				// Country multiselects.
				case 'multi_select_countries':
					$selections = (array)$value['default'];

					if (!empty($value['options'])) {
						$countries = $value['options'];
					} else {
						$countries = WC()->countries->countries;
					}

					asort($countries);
					?>
					<tr valign="top" class="form-table-row-<?php echo $typeAttribute; ?>">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
						</th>
						<td class="forminp">
							<select multiple="multiple" name="<?php echo esc_attr($value['id']); ?>[]" style="width:350px" data-placeholder="<?php esc_attr_e('Choose countries&hellip;', 'woocommerce'); ?>" aria-label="<?php esc_attr_e('Country', 'woocommerce'); ?>" class="wc-enhanced-select">
								<?php
								if (!empty($countries)) {
									foreach ($countries as $key => $val) {
										echo '<option value="' . esc_attr($key) . '"' . wc_selected($key, $selections) . '>' . esc_html($val) . '</option>'; // WPCS: XSS ok.
									}
								}
								?>
							</select> <?php echo ($description) ? $description : ''; // WPCS: XSS ok. ?> <br /><a class="select_all button" href="#"><?php esc_html_e('Select all', 'woocommerce'); ?></a> <a class="select_none button" href="#"><?php esc_html_e('Select none', 'woocommerce'); ?></a>
						</td>
					</tr>
					<?php
					break;

				// Days/months/years selector.
				case 'relative_date_selector':
					$periods      = array(
						'days'   => __('Day(s)', 'woocommerce'),
						'weeks'  => __('Week(s)', 'woocommerce'),
						'months' => __('Month(s)', 'woocommerce'),
						'years'  => __('Year(s)', 'woocommerce'),
					);
					$option_value = wc_parse_relative_date_option($value['default']);
					?>
					<tr valign="top" class="form-table-row-<?php echo $typeAttribute; ?>">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
						</th>
						<td class="forminp">
						<input
								name="<?php echo esc_attr($value['id']); ?>[number]"
								id="<?php echo esc_attr($value['id']); ?>"
								type="number"
								style="width: 80px;"
								value="<?php echo esc_attr($option_value['number']); ?>"
								class="<?php echo esc_attr($value['class']); ?>"
								placeholder="<?php echo esc_attr($value['placeholder']); ?>"
								step="1"
								min="1"
								<?php echo implode(' ', $custom_attributes); // WPCS: XSS ok. ?>
							/>&nbsp;
							<select name="<?php echo esc_attr($value['id']); ?>[unit]" style="width: auto;">
								<?php
								foreach ($periods as $value => $label) {
									echo '<option value="' . esc_attr($value) . '"' . selected($option_value['unit'], $value, false) . '>' . esc_html($label) . '</option>';
								}
								?>
							</select> <?php echo ($description) ? $description : ''; // WPCS: XSS ok. ?>
						</td>
					</tr>
					<?php
					break;

				// Image
				case 'image':
					$option_value = $value['default'];
					$imageSrc = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNgYAAAAAMAASsJTYQAAAAASUVORK5CYII=';
					if (!empty($option_value)) {
						$image = wp_get_attachment_image_src($option_value, 'thumbnail');
						if (!empty($image) && is_array($image)) {
							$imageSrc = $image[0];
						}
					}

					$id = esc_attr($value['id']);

					$this->images[$id] = $id;

					?>
					<tr valign="top" class="form-table-row-<?php echo $typeAttribute; ?>">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
						</th>
						<td class="forminp forminp-<?php echo esc_attr(sanitize_title($value['type'])); ?>">
							<img src="<?php echo $imageSrc; ?>" style="display: block; width: 150px; height: 150px; border-radius: 4px; border: 1px solid #7e8993; margin-bottom: 5px;"/>
							<input type="hidden" name="<?php echo esc_attr(!empty($value['name']) ? $value['name'] : $value['id']); ?>" value="<?php echo esc_attr($option_value); ?>"/>
							<button
								id="<?php echo esc_attr($value['id']); ?>"
								type="button"
								style="<?php echo esc_attr($value['css']); ?>"
								class="<?php echo esc_attr($value['class']); ?>"
								<?php echo implode(' ', $custom_attributes); // WPCS: XSS ok. ?>
								><?php echo esc_html(!empty($value['text']) ? $value['text'] : $value['title']); ?></button><?php echo esc_html($value['suffix']); ?> <?php echo $description; // WPCS: XSS ok. ?>&nbsp;
						</td>
					</tr>
					<?php
					break;

					case 'button':
					case 'submit':
						?>
								<button
									name="<?php echo esc_attr(!empty($value['name']) ? $value['name'] : $value['id']); ?>"
									id="<?php echo esc_attr($value['id']); ?>"
									type="<?php echo esc_attr($value['type']); ?>"
									style="<?php echo esc_attr($value['css']); ?>"
									value="<?php echo esc_attr(!empty($value['default']) ? $value['default'] : $value['title']); ?>"
									class="<?php echo esc_attr($value['class']); ?>"
									<?php echo implode(' ', $custom_attributes); // WPCS: XSS ok. ?>
									><?php echo esc_html($value['title']); ?></button><?php echo esc_html($value['suffix']); ?> <?php echo $description; // WPCS: XSS ok. ?>&nbsp;
						<?php
					break;

					case 'html':
						if (isset($value['html'])) {
							echo $value['html'];
						}
					break;
	
				// Default: run an action.
				default:
					do_action('woocommerce_admin_field_' . $value['type'], $value);
					break;
			}

			$this->activateColorPicker();
			$this->activateMediaSelection();
		}
	}

	protected function activateColorPicker()
	{
		if (empty($this->colorPickClasses)) {
			return;
		}
		$selector = '.' . implode(', .', $this->colorPickClasses);
		echo sprintf('
			<script>
			jQuery(window).on("load", function() { 
				jQuery("%s").wpColorPicker(); 
			});
			</script>', 
			$selector);
	}

	protected function activateMediaSelection()
	{
		if (empty($this->images)) {
			return;
		}

		$selector = '[id=\'' . implode('\'], [id=\'', $this->images) . '\']';
		echo sprintf('
			<script>
			jQuery(document).on("click", "%s", function(event) {
				event.preventDefault();
                event.stopPropagation();

				var target = jQuery(event.target);

				if (!(wp.media.frames["%s"])) {
					var frame = wp.media.frames["%s"] = wp.media();

					frame.on("select", function() { 
						var attachment = wp.media.frame.state().get("selection").first().toJSON();
						if (attachment.id !== "") {
							var url = typeof attachment.sizes.thumbnail == "undefined" ? attachment.sizes.full.url : attachment.sizes.thumbnail.url;
							console.log(url);
							target.siblings("input[type=hidden]").val(attachment.id);
							target.siblings("img").attr("src", url);
						}
					});

					frame.on("open", function() {
						var selection = frame.state().get("selection");
						var attachmentId = target.siblings("input[type=hidden]").val();
						var attachment = wp.media.attachment(attachmentId);
						attachment.fetch();
						selection.add(attachment ? [attachment] : []);
					});
				}

				wp.media.frames["%s"].open();
			});
			</script>',
			$selector,
			$selector,
			$selector,
			$selector);
	}

}

endif;
