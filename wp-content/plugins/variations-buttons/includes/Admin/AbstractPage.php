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
if (!class_exists(__NAMESPACE__ . '\\AbstractPage')):

abstract class AbstractPage
{
	protected $page;
	protected $parentMenu;
	protected $pageTitle;
	protected $menuTitle;
	protected $capability;

    protected function __construct($page, $parentMenu, $pageTitle, $menuTitle, $capability = '') 
    {
		$this->page = $page;	
		$this->parentMenu = $parentMenu;
		$this->pageTitle = $pageTitle;
		$this->menuTitle = $menuTitle;
		$this->capability = $capability;

		add_action('admin_menu', array($this, 'onAdminMenu'));		
		add_filter('woocommerce_screen_ids', array($this, 'onScreenIds'));
	}

	/**
	 * Adds submenu under WooCommerce menu
	 */
	public function onAdminMenu()
	{
		add_submenu_page($this->parentMenu, 
			$this->pageTitle,               
			$this->menuTitle,                 
			$this->capability,                
			$this->page,
			array($this, 'displayPage') 
		);
	}
	
	/**
	 * Registers this page with woocommerce, so it will add required resources
	 */
	public function onScreenIds($screenIds)
	{
		// we need to register page with woocommerce_page prefix as it is its submenu so it will activate all the resources we need
		$screenIds[] = 'woocommerce_page_' . $this->page;

		return $screenIds;
	}

	/**
	 * Render the admin page
	 */
	public function displayPage() {
		// Check the user capabilities
		if (!$this->canUserViewThisPage()) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}

		$this->displayHeader();
		$this->display();
		$this->displayFooter();
	}

	/**
	 * Returns true when user can view this page
	 */
	protected function canUserViewThisPage()
	{
		if (!function_exists(('current_user_can'))) {
			include_once(ABSPATH . 'wp-includes/pluggable.php');
		}

		if (empty($this->capability) || current_user_can($this->capability)) {
			return true;
		}

		return false;
	}

	/**
	 * Render the admin plugin page header
	 */
	protected function displayHeader()
	{
		echo '<div class="wrap woocommerce">';
	}

	/**
	 * Render the admin plugin page footer
	 */
	protected function displayFooter()
	{
		echo '</div>';
	}

	/**
	 * Renders admin page
	 */
	public abstract function display();
};

endif;
