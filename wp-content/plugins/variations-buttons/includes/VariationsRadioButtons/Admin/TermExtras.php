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

if (!class_exists(__NAMESPACE__ . '\\TermExtras')):

class TermExtras
{
	protected $id;

	public function __construct($id)
	{
		$this->id = $id;
		$this->notices = new \OneTeamSoftware\WooCommerce\Admin\Notices($this->id . '_' . get_class($this));

		$this->initFormFields();
	}

	public function register()
	{
		add_action('admin_init', array($this, 'onAdminInit'));
	}

	public function onAdminInit()
	{
		$taxonomies = wc_get_attribute_taxonomies();

		if (!empty($taxonomies)) {
			foreach ($taxonomies as $attribute) {
				$attributeName = 'pa_' . $attribute->attribute_name;

				add_action($attributeName . '_edit_form_fields', array($this, 'onFormFields'), PHP_INT_MAX, 2);
				add_filter('manage_edit-' . $attributeName . '_columns', array($this, 'onColumns'), PHP_INT_MAX, 1);
				add_filter('manage_' . $attributeName . '_custom_column', array($this, 'onColumn'), PHP_INT_MAX, 3);
			}
		}

		add_action('created_term', array($this, 'onSave'), PHP_INT_MAX, 3);
		add_action('edit_term', array($this, 'onSave'), PHP_INT_MAX, 3);
	}

	public function onFormFields($term, $taxonomy)
	{
		$form = new \OneTeamSoftware\WooCommerce\Admin\Form($this->formFields);
		$data = array();
		if (is_object($term)) {
			$data = get_term_meta($term->term_id, $this->id, true);
			if (is_array($data)) {
				$data = array($this->id => $data);
			} else {
				$data = array();
			}		
		}

		$form->display($data);
	}

	public function onColumns($columns)
	{
		$columns[$this->id . '_preview'] = '';

		return $columns;
	}

	public function onColumn($columns, $column, $termId)
	{
		if ($column != $this->id . '_preview') {
			return;
		}

		$data = get_term_meta($termId, $this->id, true);
		if (is_array($data)) {
			$colorValue = !empty($data['color']) ? $data['color'] : 'none';
			$imageValue = 'none';

			if (!empty($data['imageId'])) {
				$image = wp_get_attachment_image_src($data['imageId'], 'thumbnail');
				if (!empty($image) && is_array($image)) {
					$imageValue = sprintf('url(\'%s\')', $image[0]);
				}
			}

			echo sprintf('<span style="display: inline-block; width: 30px;height: 30px; background: %s;"></span>', 
				$colorValue);

			echo sprintf(' <span style="display: inline-block; width: 30px;height: 30px; background-size: 30px 30px; background-image: %s;"></span>',
				$imageValue);
		}
	}

	public function onSave($termId, $termTaxonomyId = '', $taxonomy = '')
	{
		$form = new \OneTeamSoftware\WooCommerce\Admin\Form($this->formFields);
		$data = $form->filter($_POST);
		if (is_array($data)) {
			$data = $data[$this->id];
			update_term_meta($termId, $this->id, $data);
		} else {
			$this->notices->type = 'error';

			$errors = $form->getErrors();
			foreach ($errors as $error) {
				$this->notices->add($error);
			}
		}
	}

	protected function initFormFields()
	{
		$this->formFields = array(
			$this->id . '[color]' => array(
				'id' => $this->id . '[color]',
				'type' => 'color',
				'title' => __('Color', $this->id),
				'filter' => FILTER_VALIDATE_REGEXP,
	 			'filter_options' => array('options' => array('regexp' => '/^#[a-z0-9]{6}$/')),
				 'optional' => true,
				),
			$this->id . '[imageId]' => array(
				'id' => $this->id . '[imageId]',
				'type' => 'image',
				'class' => 'button button-primary',
				'title' => __('Image', $this->id),
				'text' => __('Upload an Image', $this->id),
				'filter' => FILTER_VALIDATE_INT,
				'optional' => true,
			),
		);		
	}
}

endif;