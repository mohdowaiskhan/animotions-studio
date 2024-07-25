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

if (!class_exists(__NAMESPACE__ . '\\SettingsTab')):

class SettingsTab extends \OneTeamSoftware\WooCommerce\Admin\AbstractPageTab
{
	private $id;
	private $form;

    public function __construct($id) 
    {
		parent::__construct('settings', 'manage_woocommerce');

		$this->id = $id;
		$this->form = new SettingsForm($this->id);
	}

	public function getTabTitle()
	{
		return __('Settings', $this->id);
	}
	
	protected function getHeaderTitle()
	{
		return $this->getTabTitle();
	}

	protected function displayContents()
	{	
		echo sprintf('<div class="notice notice-info inline"><p>%s<br/><li><a href="%s" target="_blank">%s</a><br/><li><a href="%s" target="_blank">%s</a></p></div>', 
			__('Let your customers choose product variations using radio buttons instead of dropdowns.', $this->id),
			'https://1teamsoftware.com/contact-us/', 
			__('Do you have any questions or requests?', $this->id), 
			 'https://wordpress.org/plugins/variations-radio-buttons-for-woocommerce/', 
			 __('Do you like our plugin and can recommend to others?', $this->id)
		);
	
		$this->form->display();
	}
}

endif;
