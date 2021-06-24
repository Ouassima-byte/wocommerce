<?php

namespace WPDesk\FlexibleInvoices\Addons\Sending\Fields;

use WPDeskFIVendor\WPDesk\Forms\Field\InputTextField;

/**
 * Define multiple input text field.
 *
 * @package WPDesk\FIS\Settings\Fields
 */
class MultipleInputTextField extends InputTextField {

	/**
	 * @return string
	 */
	public function get_template_name() {
		return 'input-text-multiple';
	}
}
