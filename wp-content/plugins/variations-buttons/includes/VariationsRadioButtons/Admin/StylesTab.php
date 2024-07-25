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

if (!class_exists(__NAMESPACE__ . '\\StylesTab')):

class StylesTab extends \OneTeamSoftware\WooCommerce\Admin\AbstractPageTab
{
	private $id;
	private $form;

    public function __construct($id) 
    {
		parent::__construct('styles', 'manage_woocommerce');

		$this->id = $id;
		$this->form = new StylesForm($this->id);
	}

	public function getTabTitle()
	{
		return __('Styles', $this->id);
	}
	
	protected function getHeaderTitle()
	{
		return $this->getTabTitle();
	}

	protected function displayContents()
	{	
		echo sprintf('<p>%s</p>', apply_filters($this->id . '_pro_version_feature_message', ''));
		echo sprintf('<p>%s</p>', 
			__('Here you can overwrite default / global style settings. If value is not set then previous or default value will be used.', $this->id)
		);

		$this->form->display();
	}
}

endif;
