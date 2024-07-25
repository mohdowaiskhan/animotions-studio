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
if (!class_exists(__NAMESPACE__ . '\\AbstractPageForm')):

abstract class AbstractPageForm
{
	protected $formId;
	protected $capability;
	protected $displaySectionsMenu;
	protected $form;
	protected $notices;

   /**
     * Constructor
     */
    public function __construct($formId, $capability = '') 
    {
		$this->formId = $formId;
		$this->capability = $capability;
		$this->displaySectionsMenu = false;

		require_once(__DIR__ . '/Form.php');
		$this->form = new Form();

		require_once(__DIR__ . '/Notices.php');
		$this->notices = new Notices($formId);

		add_action('init', array($this, 'onInit'));
		add_action('admin_post_' . $formId, array($this, 'onAdminPost'));
	}

	/**
     * Returns text that has will be used as form title
	 * 
	 * @return string
	 */
	//protected abstract function getFormTitleText();

	/**
     * Saves data and returns true or false and it can also modify input data
	 * 
	 * @return bool
	 */
	protected abstract function saveFormData(array &$data);

	/**
     * Returns fields for the plugin settings form
	 * 
	 * Example:
	 * return array(    
	 *		$this->id . '_supplier_start' => array(
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
	 *      $this->id . '_supplier_end' => array(
	 *			'type' => 'sectionend', 
	 *			'id' => $this->id . '_supplier_end'
	 *		),
	 * );
	 * 
	 * @return array
     */
	protected abstract function getFormFields();

	/**
     * Returns data that will be displayed in the form
	 * 
	 * @return array
	 */
	protected function getFormData()
	{
		return array();
	}

	/**
     * Return success message
     */
	protected function getSuccessMessageText()
	{
		return __('Form data have been successfully saved', 'woocommerce');
	}

	/**
     * Return error message
     */
	protected function getErrorMessageText()
	{
		return __('Unable to save form data', 'woocommerce');
	}

	/**
     * We have to initialize form fields on init event in order for taxonomies to work
     */
	public function onInit()
	{
		$this->form->setFields($this->getFormFields());
	}

	/**
     * Saves current form settings
     */
	public function onAdminPost()
	{
		if (!empty($this->capability) && !current_user_can($this->capability)) {
			wp_die(__( 'You do not have sufficient permissions to access this page.'));
		}

		$inputData = $_POST;
		
		// make sure that we can verify nonce field
		if (isset($inputData['_wpnonce']) && wp_verify_nonce($inputData['_wpnonce'], $this->formId)) {
			$data = $this->form->filter($inputData);
			$errors = $this->form->getErrors();
			
			if (!empty($data) && is_array($data)) {
				$result = $this->saveFormData($data);
				if ($result === true) {
					do_action('woocommerce_settings_saved');

					// overwrite/extend original data with new data
					$inputData = array_merge($inputData, $data);

					$fields = $this->getFormFields();

					// remove field's data after we've saved data
					foreach ($fields as $field) {
						if (isset($field['id'])) {
							$key = $field['id'];
							$keyLower = strtolower($key);

							if (isset($inputData[$key])) {
								unset($inputData[$key]);
							}
							if (isset($inputData[$keyLower])) {
								unset($inputData[$keyLower]);
							}
						}
					}
				} else if (is_array($result)) {
					$errors = $result;
				}
			}

			if (empty($errors)) {
				$this->notices->type = 'updated';
				$this->notices->displayWithoutNotices = true;
				$this->notices->title = $this->getSuccessMessageText();
				
			} else {
				$this->notices->type = 'error';
				$this->notices->title = $this->getErrorMessageText();

				foreach ($errors as $error) {
					$this->notices->add($error);
				}
			}
		}

		// build GET query arguments from input data what were not used by the form
		$queryArgs = array();
		foreach ($inputData as $key => $val) {
			if (!empty($val) && !in_array($key, array('action', 'action2', '_wpnonce', '_wp_http_referer'))) {
				$queryArgs[$key] = stripslashes($val);
			}
		}
		
		// use php function to build URL because wordpress one messes up tags
		$redirectUrl = add_query_arg(array(), 'admin.php');
		if (!empty($queryArgs)) {
			$redirectUrl .= '?' . http_build_query($queryArgs);
		}
		
		//return wp_redirect(add_query_arg($queryArgs, 'admin.php'));
		return wp_redirect($redirectUrl);
	}

	/**
     * Displays this form
     */
	public function display()
	{
		if (!empty($this->capability) && !current_user_can($this->capability)) {
			wp_die(__( 'You do not have sufficient permissions to access this page.'));
		}

		$data = $this->getFormData();
		
		if ($this->displaySectionsMenu) {
			$this->form->displaySectionsMenu();
		}
?>
		<form method="post" action="admin-post.php" enctype="multipart/form-data">
			<input type="hidden" name="action" value="<?php echo $this->formId; ?>"/>
			<input type="hidden" name="page" value="<?php echo (isset($_GET['page']) ? $_GET['page'] : ''); ?>"/>
			<input type="hidden" name="tab" value="<?php echo (isset($_GET['tab']) ? $_GET['tab'] : ''); ?>"/>
<?php	
		wp_nonce_field($this->formId);

		foreach ($_REQUEST as $key => $value) {
			if (!empty($value) && strpos($key, 'action_') === false && !in_array($key, array('action', 'action2', '_wpnonce', '_wp_http_referer'))) {
				echo '<input type="hidden" name="' . sanitize_key($key) . '" value="' . sanitize_text_field($value) .'" />';
			}
		}

		$this->form->display($data);
?>
	</form>
<?php
	}
}

endif;
