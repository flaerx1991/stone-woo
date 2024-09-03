<?php
namespace CmsmastersElementor\Modules\WidgetPresets\Controls;

use Elementor\Base_Data_Control;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class Control_Presets_Native extends Base_Data_Control {

	/**
	 * Get control type.
	 *
	 * Retrieve the control type.
	 *
	 * @since 1.0.0
	 */
	public function get_type() {
		return 'cmsmasters_presets_native';
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
		<div class="elementor-control-field cmsmasters-control-presets-native">
			<div class="cmsmasters-element-presets-native-wrapper">
				<#
				var widgetPresets = cmsmastersElementor.modules.widgetPresets.getPresetData( 'native', obj.view.container.settings.get( 'widgetType' ) );
				#>
				<div class="cmsmasters-element-presets-native">
					<# if ( _.isEmpty( widgetPresets ) ) { #>
						<div class="cmsmasters-element-presets-native-404">
							<?php esc_html_e( 'Presets Not Found', 'cmsmasters-elementor' ); ?>
						</div>
					<# } #>

					<div class="cmsmasters-element-presets-native-loading">
						<?php esc_html_e( 'Loading Presets', 'cmsmasters-elementor' ); ?>
						<span style="display:inline-flex" class="elementor-control-spinner"><span class="fa fa-spinner fa-spin"></span>&nbsp;</span>
					</div>

					<# if ( ! _.isEmpty( widgetPresets ) ) { #>
						<div class="cmsmasters-element-presets-native-items">
							<# _.each( widgetPresets, function( preset, preset_id ) { #>
								<div class="cmsmasters-element-presets-native-item{{{ preset_id === data.controlValue ? ' active' : ''}}}" data-preset-id='{{{preset_id}}}'>
									<i class="fa fa-check"></i>
									<# if ( preset.url_image_demo ) { #>
										<img src="{{{preset.url_image_demo}}}" title="{{{preset.title}}}" alt="{{{preset.title}}}">
									<# } else { #>
										<span class="cmsmasters-element-presets-native-item-title">{{{preset.title}}}</span>
									<# } #>
								</div>
							<# } ); #>
						</div>
					<# } #>
				</div>
			</div>
		</div>
		<?php
	}
}
