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
if (!class_exists(__NAMESPACE__ . '\\PageTabs')):

require_once __DIR__ . '/AbstractPageTab.php';

class PageTabs
{
	protected $page;
	protected $tabs;

    public function __construct($page) 
    {
		$this->page = $page;
		$this->tabs = array();
	}

	/** 
	 * Register new tab
	 */
	public function addTab(AbstractPageTab $tab)
	{
		$this->tabs[$tab->getTabId()] = $tab;
	}

	/**
	 * Renders tabs
	 */
	public function display()
	{
		if (empty($this->tabs) || empty($_GET['page']) || $_GET['page'] != $this->page) {
			return;
		}

		$currentTabId = '';
		if (isset($_GET['tab']) && isset($this->tabs[$_GET['tab']])) {
			$currentTabId = sanitize_key($_GET['tab']);
		} else {
			$currentTabId = key($this->tabs);
		}
		
		$this->displayTabs($currentTabId);
		$this->displayTabContents($currentTabId);
	}

	/**
	 * Render the admin plugin page tabs
	 */
	protected function displayTabs($currentTabId)
	{	
		echo '<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">';
			foreach ($this->tabs as $tabId => $tab) {
				$text = esc_html($tab->getTabTitle());
				$url = admin_url('admin.php?page=' . $this->page . '&tab=' . $tabId);
				
				//if ($currentTabId == $tabId) {
				//	$url = '#';
				//}

				echo '<a href="' . $url . '" class="nav-tab ' . ($currentTabId == $tabId ? 'nav-tab-active' : '') . '">' . $text . '</a>';
			}
		echo '</h2>';
	}

	/**
	 * Render contents of a current admin page tab
	 */
	protected function displayTabContents($currentTabId)
	{
		if (isset($this->tabs[$currentTabId])) {
			$this->tabs[$currentTabId]->display();
		}
	}
};

endif;
