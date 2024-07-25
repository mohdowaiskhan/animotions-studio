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

if (!class_exists(__NAMESPACE__ . '\\SettingsPage')):

class SettingsPage extends \OneTeamSoftware\WooCommerce\Admin\AbstractPage
{
	private $id;
	private $pageTabs;

	public function __construct($id, $name)
	{
		parent::__construct($id, 'oneteamsoftware', $name, $name, 'manage_woocommerce');

		$this->id = $id;
		$this->pageTabs = new \OneTeamSoftware\WooCommerce\Admin\PageTabs($this->id);

		add_action('plugins_loaded', array($this, 'onPluginsLoaded'));
	}

	public function register()
	{
		\OneTeamSoftware\WooCommerce\Admin\OneTeamSoftware::instance()->register();
	}

	public function onPluginsLoaded()
	{
		$this->pageTabs->addTab(new SettingsTab($this->id));

		do_action($this->id . '_settings_tabs', $this->pageTabs);
	}

	public function display()
	{
		$this->pageTabs->display();
	}

}

endif;
