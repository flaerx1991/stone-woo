<?php
namespace CmsmastersElementor\Modules\WidgetPresets\Controls;

use Elementor\Base_Data_Control;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class Control_Presets_Custom extends Base_Data_Control {

	/**
	 * Get control type.
	 *
	 * Retrieve the control type.
	 *
	 * @since 1.0.0
	 */
	public function get_type() {
		return 'cmsmasters_presets_custom';
	}

	/**
	 * Control content template.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * Note that the content template is wrapped by Base_Control::print_template().
	 *
	 * @since 1.0.0
	 */
	public function content_template() {
		?>
		<div class="elementor-control-field cmsmasters-control-presets-custom">
			<div class="cmsmasters-element-presets-custom-wrapper">
				<div class="cmsmasters-element-presets-custom-input-wrapper">
					<div class="elementor-control-field">
						<div class="elementor-control-input-wrapper">
							<input type="text" placeholder="<?php esc_attr_e( 'Preset Name', 'cmsmasters-elementor' ); ?>" class="cmsmasters-element-presets-custom-input" required>
						</div>
					</div>
					<div class="elementor-button-wrapper">
						<button class="elementor-button elementor-button-default cmsmasters-element-presets-custom-add-btn" type="button">
							<i class="elementor-icon eicon-save"></i>&nbsp;<?php esc_html_e( 'Save Preset', 'cmsmasters-elementor' ); ?>
						</button>
					</div>
				</div>
				<div class="cmsmasters-element-presets-custom loading">
					<div class="cmsmasters-element-presets-custom-loading">
						<span><?php esc_html_e( 'Loading Presets', 'cmsmasters-elementor' ); ?></span>
						<span style="display:inline-flex" class="elementor-control-spinner"><span class="fa fa-spinner fa-spin"></span>&nbsp;</span>
					</div>
					<#
					var widgetPresets = cmsmastersElementor.modules.widgetPresets.getPresetData( 'custom', obj.view.container.settings.get( 'widgetType' ) );

					if ( widgetPresets ) {
						_.each( widgetPresets, function( preset, preset_id ) {
							if ( ! preset ) {
								return;
							}

							#>
							<div class="cmsmasters-element-presets-custom-item{{{ preset_id === data.controlValue ? ' active' : ''}}}" data-preset-id="{{{preset_id}}}" data-user-id="{{{preset.user_id}}}">
								<div class="cmsmasters-element-presets-custom-item-title">
									{{preset.title}}
								</div>
								<div class="cmsmasters-element-presets-custom-item-delete tooltip-target" data-tooltip="<?php esc_html_e( 'Delete Preset', 'cmsmasters-elementor' ); ?>">
									<i class="fa fa-trash-o" ></i>
								</div>
								<div class="cmsmasters-element-presets-custom-item-apply tooltip-target" data-tooltip="<?php esc_html_e( 'Apply Preset', 'cmsmasters-elementor' ); ?>">
									<i class="fa fa-play-circle-o"></i>
								</div>
							</div>
						<#
						} );
					}
					#>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Get data control default value.
	 *
	 * Retrieve the default value of the data control. Used to return the default
	 * values while initializing the data control.
	 *
	 * @since 1.0.0
	 *
	 * @return string Control default value.
	 */
	public function get_default_value() {
		return '';
	}
}
