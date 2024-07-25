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
if (!class_exists(__NAMESPACE__ . '\\FormFilter')):

class FormFilter
{
	protected $fields;
	protected $errors;
	protected $prefix;

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
		$this->errors = array();
		$this->prefix = '';
	}

	public function setPrefix($prefix)
	{
		$this->prefix = $prefix;
	}

	public function setFields(array $fields)
	{
		$this->fields = $fields;
	}

	public function getFields(array $data = array())
	{
		return $this->fillFields($data);
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function filter(array $data, $allowErrors = false)
	{
		$this->errors = array();

		$values = array();

		foreach ($this->fields as $key => $field) {
			$dataKey = $this->getFieldDataKey($key, $field);
			if (is_null($dataKey)) {
				continue;
			}

			$value = $this->getFieldValue($data, $dataKey, $field);

			if ($value === false) {
				$label = $key;
				if (!empty($field['title'])) {
					$label = $field['title'];
				} else if (!empty($field['label'])) {
					$label = $field['label'];
				}

				$this->errors[$key] = $label . ' ' . __(' is invalid', 'woocommerce');
			} else {
				$values = $this->setValueTo($values, $dataKey, $value);
			}
		}
		
		if (!empty($this->errors) && !$allowErrors) {
			$values = false;
		}
		
		return $values;
	}

	protected function fillFields(array $data)
	{
		$data = $this->filter($data, true);
		if (empty($data) || !is_array($data)) {
			return $this->fields;
		}

		$fields = $this->fields;
		
		foreach ($fields as $key => $field) {
			$dataKey = $this->getFieldDataKey($key, $field);
			if (is_null($dataKey)) {
				continue;
			}

			$value = $this->getFieldValue($data, $dataKey, $field);

			if (isset($value)) {
				if ($field['type'] == 'checkbox') {
					if (empty($value)) {
						$field['value'] = $field['default'] = 'no';
					} else {
						$field['value'] = $field['default'] = 'yes';
					}
				} else {
					if (empty($value) && !is_numeric($value)) {
						$field['value'] = $field['default'] = '';
					} else {
						$field['value'] = $field['default'] = $value;
					}
				}
			}

			$fields[$key] = $field;
		}

		return $fields;
	}

	protected function getFieldDataKey($key, array $field)
	{
		if (empty($field['type']) || in_array($field['type'], array('title', 'sectionend', 'submit'))) {
			return null;
		}

		if (isset($field['id'])) {
			$key = $field['id'];
		} else if (isset($field['name'])) {
			$key = $field['name'];
		}

		return $key;
	}

	protected function getFieldValue(array $value, $key, array $field)
	{
		$value = $this->getValueFrom($value, $key);

		if ($field['type'] == 'checkbox') {
			if (isset($value)) {
				$value = filter_var($value, FILTER_VALIDATE_BOOLEAN) === true ? 1 : 0;
			} else {
				$value = 0;
			}
		}
		
		if (!empty($field['filter'])) {
			$filter = $field['filter'];
			$filterOptions = isset($field['filter_options']) ? $field['filter_options'] : array();

			if (empty($value)) {
				if (empty($field['optional']) && $field['type'] != 'checkbox') {
					$value = false;
				}
			} else {
				$value = filter_var($value, $filter, $filterOptions);
			}
		}

		if ($value !== false && isset($field['options'])) {
			foreach ((array)$value as $optionToCheck) {
				if (!isset($field['options'][$optionToCheck])) {
					$value = false;

					break;
				}
			}
		}
		
		if ($value !== false && isset($field['sanitize_function']) && function_exists($field['sanitize_function'])) {
			if (is_array($value)) {
				$value = array_map($field['sanitize_function'], $value);
			} else {
				$value = call_user_func($field['sanitize_function'], $value);
			}
		}

		if (is_string($value) && !is_numeric($value)) {
			$value = stripslashes_deep($value);
		}

		return $value;
	}

	protected function getValueFrom(array $value, $key)
	{
		$keyParts = explode('[', $this->prefix . $key);

		foreach ($keyParts as $keyPart) {
			$keyPart = trim($keyPart, ']');
			if (!isset($value[$keyPart])) {
				$value = null;
				break;
			}

			$value = &$value[$keyPart];
		}

		return $value;
	}

	protected function setValueTo(array $data, $key, $value)
	{
		if (empty($key)) {
			return $data;
		}
		
		$keyParts = explode('[', $key);

		$valueRef = &$data;
		foreach ($keyParts as $keyPart) {
			$keyPart = trim($keyPart, ']');
			$valueRef = &$valueRef[$keyPart];
		}

		$valueRef = $value;

		return $data;
	}
}

endif;
