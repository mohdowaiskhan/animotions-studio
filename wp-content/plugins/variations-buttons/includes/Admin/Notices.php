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
if (!class_exists(__NAMESPACE__ . '\\Notices')):

class Notices
{
	protected $id;
	protected $displayWithoutNotices;
	protected $type;
	protected $title;
	protected $delay;
	protected $permanent;
	protected $dismissible;
	protected $createdAt;
	protected $dismissed;
	protected $notices;
	protected $dismissAction;
	protected $loaded;

	public function __construct($id = 'admin_notices', $properties = array())
	{
		$this->id = $id;
		$this->displayWithoutNotices = false;
		$this->type = 'error';
		$this->title = '';
		$this->delay = 0;
		$this->permanent = false;
		$this->dismissible = false;
		$this->createdAt = time();
		$this->dismissed = false;
		$this->notices = array();
		$this->dismissAction = $this->id . '_dismiss';
		$this->loaded = false;
		$this->setProperties($properties);

		add_action('wp_ajax_' . $this->dismissAction, array($this, 'dismiss'));
		add_action('admin_notices', array($this, 'display'));
		add_action('shutdown', array($this, 'save'));
		add_filter('wp_redirect', array($this, 'onRedirect'), 100, 2);
	}

	public function dismiss()
	{
		if (wp_verify_nonce($_REQUEST['nonce'], $this->dismissAction)) {
			$this->load();
			$this->dismissed = true;
			wp_send_json_success();
		}
	}

	public function __get($property)
	{
		if (property_exists($this, $property)) {
			return $this->$property;
		}

		return null;
	}
	
	public function __set($property, $value) {
		if (property_exists($this, $property)) {
			if (is_numeric($this->property)) {
				$this->$property = floatval($value);
			} else if (is_bool($this->property)) {
				$this->$property = filter_var($value, FILTER_VALIDATE_BOOLEAN);
			} else {
				$this->$property = $value;
			}
		}
	
		return $this;
	}

	public function add($notice, $noticeKey = null)
	{
		if (empty($notice)) {
			return;
		}

		if (empty($noticeKey)) {
			$noticeKey = md5($notice);
		}

		$this->notices[$noticeKey] = $notice;
	}

	public function display()
	{
		$this->load();

		if (!$this->canDisplay()) {
			return;
		}

		$this->displayNotices();

		if (!$this->permanent) {
			$this->notices = array();
			$this->displayWithoutNotices = false;
		}
	}

	public function onRedirect($location, $status)
	{
		$this->save();

		return $location;
	}

	public function save()
	{
		if (!$this->loaded && empty($this->notices) && !$this->displayWithoutNotices) {
			return;
		}

		$cacheKey = $this->getCacheKey();
		delete_transient($cacheKey);

		if (!empty($this->notices) || $this->displayWithoutNotices) {
			set_transient($cacheKey, get_object_vars($this));
		
			// extra effort to save dismissed to work around cache
			if ($this->dismissed) {
				update_user_meta(get_current_user_id(), $cacheKey . '_dismissed', true);
			}	
		}		
	}

	private function setProperties(array $properties)
	{
		foreach ($properties as $property => $value) {
			$this->__set($property, $value);
		}
	}

	private function load()
	{
		if ($this->loaded)
		{
			return;
		}

		$cacheKey = $this->getCacheKey();
		$properties = get_transient($cacheKey, null);
		if (is_array($properties)) {
			$this->setProperties($properties);
		}

		if (empty($this->dismissed) && get_user_meta(get_current_user_id(), $cacheKey . '_dismissed', true)) {
			$this->dismissed = true;
		}

		$this->loaded = true;
	}

	private function getCacheKey()
	{
		// slashes are not supported by get_user_meta
		return str_replace('\\', '', get_class($this) . '_' . $this->id);
	}

	private function canDisplay()
	{
		if (!is_admin()) {
			return false;
		}
		
		if (empty($this->notices) && (!$this->displayWithoutNotices || empty($this->title))) {
			return false;
		}
		
		if ($this->dismissed) {
			return false;
		}

		if ($this->createdAt > 0 && $this->delay > 0 && strtotime("-{$this->delay} seconds") < $this->createdAt) {
			return false;
		}

		return true;
	}

	private function displayNotices()
	{
		$noticeBoxId = $this->id . '_box';
		$noticeBoxClass = 'notice notice-' . $this->type . ' ' . $this->type;
		if ($this->permanent && $this->dismissible) {
			$noticeBoxClass .= ' is-dismissible';
		}

		echo '<div id="' . $noticeBoxId . '" class="' . $noticeBoxClass . '">';
		$nonce = wp_create_nonce($this->dismissAction);
		
		if ($this->permanent && $this->dismissible) {
			echo '<button class="notice-dismiss" onclick="jQuery.post(ajaxurl, {action: \'' . $this->dismissAction . '\', nonce: \'' . $nonce . '\'}).done(function() { jQuery(\'#' . $noticeBoxId . '\').slideUp(); });">';
			echo '<span class="screen-reader-text">';
			echo _e("Dismiss", $this->id);
			echo '</span>';
			echo '</button>';
		}

		if (!empty($this->title)) {
			echo '<p><strong>'; 
			echo wp_kses_post(_e($this->title, $this->id)); 
			echo '</strong></p>';
		}

		foreach ((array)$this->notices as $notice) {
			echo '<p>';
			echo  wp_kses_post(_e($notice, $this->id)); 
			echo '</p>';
		}

		echo '</div>';
	}
}

endif;
