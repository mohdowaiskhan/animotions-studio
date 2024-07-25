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
if (!class_exists(__NAMESPACE__ . '\\MetaBoxForm')):

class MetaBoxForm
{
	protected $fields;

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
		$this->fields = $fields;
	}

	public function setFields(array $fields)
	{
		$this->fields = $fields;
	}

	public function getFields()
	{
		return $this->fields;
	}

	public function display()
	{
		if (empty($this->fields)) {
			return;
		}

		foreach ($this->fields as $field) {
			if (!empty($field['type']) && !empty($field['id'])) {
				$functionName = $this->getTypeFunctionName($field['type']);
				if (!empty($functionName)) {
					call_user_func($functionName, $field);
				}
			}
		}
	}

	protected function getTypeFunctionName($type)
	{
		$functionName = 'woocommerce_wp_';

		if (in_array($type, array('radio', 'checkbox', 'select'))) {
			$functionName .= $type;
		} else if (in_array($type, array('textarea', 'hidden', 'text'))) {
			$functionName .= $type . '_input';
		} else {
			$functionName .= 'text_input';
		}
		
		if (!function_exists($functionName)) {
			$functionName = false;
		}

		return $functionName;
	}
}

endif;
