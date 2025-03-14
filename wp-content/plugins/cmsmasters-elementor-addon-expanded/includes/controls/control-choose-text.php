<?php
namespace CmsmastersElementor\Controls;

use CmsmastersElementor\Controls_Manager;

use Elementor\Control_Choose;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Addon choose text control.
 *
 * Customized Elementor `choose` control for with titles instead of icons.
 * Displays radio buttons styled as groups of buttons with title
 * for each option.
 *
 * @since 1.0.0
 */
class Control_Choose_Text extends Control_Choose {

	/**
	 * Get control type.
	 *
	 * Retrieve the control type.
	 *
	 * @since 1.0.0
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return Controls_Manager::CHOOSE_TEXT;
	}

	/**
	 * Render control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS template.
	 * The variables for the class are available using `data` JS object.
	 *
	 * @since 1.0.0
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid( '{{ value }}' );
		?>
		<div class="elementor-control-field">
			<label class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper elementor-control-unit-5">
				<div class="elementor-choices">
				<#
				_.each( data.options, function( option, value ) {
					var title = option,
						description = tooltip = tooltipClass = '';

					if ( _.isObject( option ) ) {
						title = option.title;
						description = option.description;

						if ( _.isEmpty( description ) ) {
							description = title;
						}

						tooltipClass = ' tooltip-target';
						tooltip = ' data-tooltip="' + description + '"';
					}
				#>
					<input id="<?php echo $control_uid; ?>" type="radio" name="elementor-choose-{{ data.name }}-{{ data._cid }}" value="{{ value }}">
					<label class="elementor-choices-label{{tooltipClass}}" for="<?php echo $control_uid; ?>"{{{ tooltip }}} title="{{ title }}">
						<span>{{{ title }}}</span>
					</label>
				<# } ); #>
				</div>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}

	/**
	 * Get control default settings.
	 *
	 * Retrieve the default settings of the control. Used to return the
	 * default settings while initializing the control.
	 *
	 * @since 1.0.0
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		$parent_settings = parent::get_default_settings();

		$control_settings = array(
			'toggle' => false,
			'label_block' => true,
		);

		return array_merge( $parent_settings, $control_settings );
	}

}
