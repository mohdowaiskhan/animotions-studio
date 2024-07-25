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
if (!class_exists(__NAMESPACE__ . '\\AbstractPageTab')):

abstract class AbstractPageTab
{
	protected $tabId;
	protected $capability;

   	/**
     * Constructor
     */
    public function __construct($tabId, $capability = '') 
    {
		$this->tabId = $tabId;
		$this->capability = $capability;
	}

	/**
     * Returns tab id
	 * 
	 * @return string
     */
	public function getTabId()
	{
		return $this->tabId;
	}

	/**
     * Returns tab title text
	 * 
	 * @return string
     */
	public abstract function getTabTitle();

	/**
     * Returns tab header title
	 * 
	 * @return string
     */
	protected function getHeaderTitle()
	{
		return '';
	}

	/**
     * Displays contents of the tab
     */
	protected abstract function displayContents();

	/**
	 * Returns array of header buttons
	 *
	 *  Format:
	 *  array(
	 * 		array(
	 * 			'title' => 'Button #1',
	 * 			'url' => 'someurl'
 	 * 		)
	 *  )
	 * 
	 * @return array
	 */
	protected function getHeaderButtons()
	{
		return array();
	}

	/**
	 * Display table header in this method
	 *
	 * @return void
	 */
	protected function displayHeader()
	{
		$title = $this->getHeaderTitle();
		if (!empty($title)) {
			echo '<h1 class="wp-heading-inline">' . esc_html($title) . '</h1>';
		}

		$buttons = $this->getHeaderButtons();
		if (!empty($buttons) && is_array($buttons)) {
			foreach ($buttons as $button) {
				if (isset($button['url']) && isset($button['title'])) {
					echo '<a href="' . esc_attr($button['url']) . '" class="page-title-action">' . esc_html($button['title']) . '</a>';
				}
			}
		}

		echo '<hr class="wp-header-end">';
	}

	/**
     * Displays contents of the tab
     */
	public function display()
	{
		if (!empty($this->capability) && !current_user_can($this->capability)) {
			wp_die(__( 'You do not have sufficient permissions to access this page.'));
		}

		echo '<div class="wrap">';
		$this->displayHeader();
		$this->displayContents();
		echo '</div>';
	}
}

endif;
