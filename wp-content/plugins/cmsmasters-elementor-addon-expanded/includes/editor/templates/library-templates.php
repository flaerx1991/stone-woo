<?php
namespace CmsmastersElementor;

use CmsmastersElementor\Plugin;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


?>
<script type="text/template" id="tmpl-cmsmasters-template-library-header-actions">
	<div id="cmsmasters-template-library-header-import" class="elementor-templates-modal__header__item">
		<i class="eicon-upload-circle-o" aria-hidden="true" title="<?php esc_attr_e( 'Import Template', 'cmsmasters-elementor' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Import Template', 'cmsmasters-elementor' ); ?></span>
	</div>
	<div id="cmsmasters-template-library-header-sync" class="elementor-templates-modal__header__item">
		<i class="eicon-sync" aria-hidden="true" title="<?php esc_attr_e( 'Sync Library', 'cmsmasters-elementor' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Sync Library', 'cmsmasters-elementor' ); ?></span>
	</div>
	<div id="cmsmasters-template-library-header-save" class="elementor-templates-modal__header__item">
		<i class="eicon-save-o" aria-hidden="true" title="<?php esc_attr_e( 'Save', 'cmsmasters-elementor' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Save', 'cmsmasters-elementor' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-cmsmasters-template-library-header-menu">
	<# jQuery.each( tabs, ( tab, args ) => { #>
		<div class="elementor-component-tab cmsmasters-template-library-menu-item" data-tab="{{{ tab }}}">{{{ args.title }}}</div>
	<# } ); #>
</script>

<script type="text/template" id="tmpl-cmsmasters-template-library-header-preview">
	<div id="cmsmasters-template-library-header-preview-insert-wrapper" class="cmsmasters-templates-modal__header__item">
		{{{ cmsmastersElementor.templatesLibrary.layout.getTemplateActionButton( obj ) }}}
	</div>
</script>

<script type="text/template" id="tmpl-cmsmasters-template-library-header-back">
	<i class="eicon-" aria-hidden="true"></i>
	<span><?php esc_html_e( 'Back to Library', 'cmsmasters-elementor' ); ?></span>
</script>

<script type="text/template" id="tmpl-cmsmasters-template-library-loading">
	<div class="elementor-loader-wrapper">
		<div class="elementor-loader">
			<div class="elementor-loader-boxes">
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
			</div>
		</div>
		<div class="elementor-loading-title"><?php esc_html_e( 'Loading', 'cmsmasters-elementor' ); ?></div>
	</div>
</script>

<script type="text/template" id="tmpl-cmsmasters-template-library-templates">
	<#
		var activeSource = cmsmastersElementor.templatesLibrary.getFilter( 'source' );
	#>
	<div id="cmsmasters-template-library-toolbar">
		<# if ( 'remote' === activeSource ) {
			var activeType = cmsmastersElementor.templatesLibrary.getFilter('type');
			#>
			<div id="cmsmasters-template-library-filter-toolbar-remote" class="cmsmasters-template-library-filter-toolbar">
				<# if ( 'page' === activeType ) { #>
					<div id="cmsmasters-template-library-order">
						<input type="radio" id="cmsmasters-template-library-order-new" class="cmsmasters-template-library-order-input" name="cmsmasters-template-library-order" value="date">
						<label for="cmsmasters-template-library-order-new" class="cmsmasters-template-library-order-label"><?php esc_html_e( 'New', 'cmsmasters-elementor' ); ?></label>
						<input type="radio" id="cmsmasters-template-library-order-trend" class="cmsmasters-template-library-order-input" name="cmsmasters-template-library-order" value="trendIndex">
						<label for="cmsmasters-template-library-order-trend" class="cmsmasters-template-library-order-label"><?php esc_html_e( 'Trend', 'cmsmasters-elementor' ); ?></label>
						<input type="radio" id="cmsmasters-template-library-order-popular" class="cmsmasters-template-library-order-input" name="cmsmasters-template-library-order" value="popularityIndex">
						<label for="cmsmasters-template-library-order-popular" class="cmsmasters-template-library-order-label"><?php esc_html_e( 'Popular', 'cmsmasters-elementor' ); ?></label>
					</div>
				<# } else {
					var config = cmsmastersElementor.templatesLibrary.getConfig( activeType );
					if ( config.categories ) { #>
						<div id="cmsmasters-template-library-filter">
							<select id="cmsmasters-template-library-filter-subtype" class="cmsmasters-template-library-filter-select" data-elementor-filter="subtype">
								<option></option>
								<# config.categories.forEach( function( category ) {
									var selected = category === cmsmastersElementor.templatesLibrary.getFilter( 'subtype' ) ? ' selected' : '';
									#>
									<option value="{{ category }}"{{{ selected }}}>{{{ category }}}</option>
								<# } ); #>
							</select>
						</div>
					<# }
				} #>
				<div id="cmsmasters-template-library-my-favorites">
					<# var checked = cmsmastersElementor.templatesLibrary.getFilter( 'favorite' ) ? ' checked' : ''; #>
					<input id="cmsmasters-template-library-filter-my-favorites" type="checkbox"{{{ checked }}}>
					<label id="cmsmasters-template-library-filter-my-favorites-label" for="cmsmasters-template-library-filter-my-favorites">
						<i class="eicon" aria-hidden="true"></i>
						<?php esc_html_e( 'My Favorites', 'cmsmasters-elementor' ); ?>
					</label>
				</div>
			</div>
		<# } else { #>
			<div id="cmsmasters-template-library-filter-toolbar-local" class="cmsmasters-template-library-filter-toolbar"></div>
		<# } #>
		<div id="cmsmasters-template-library-filter-text-wrapper">
			<label for="cmsmasters-template-library-filter-text" class="elementor-screen-only"><?php esc_html_e( 'Search Templates:', 'cmsmasters-elementor' ); ?></label>
			<input id="cmsmasters-template-library-filter-text" placeholder="<?php esc_attr_e( 'Search', 'cmsmasters-elementor' ); ?>">
			<i class="eicon-search"></i>
		</div>
	</div>
	<# if ( 'local' === activeSource ) { #>
		<div id="cmsmasters-template-library-order-toolbar-local">
			<div class="cmsmasters-template-library-local-column-1">
				<input type="radio" id="cmsmasters-template-library-order-local-title" class="cmsmasters-template-library-order-input" name="cmsmasters-template-library-order-local" value="title" data-default-ordering-direction="asc">
				<label for="cmsmasters-template-library-order-local-title" class="cmsmasters-template-library-order-label"><?php esc_html_e( 'Name', 'cmsmasters-elementor' ); ?></label>
			</div>
			<div class="cmsmasters-template-library-local-column-2">
				<input type="radio" id="cmsmasters-template-library-order-local-type" class="cmsmasters-template-library-order-input" name="cmsmasters-template-library-order-local" value="type" data-default-ordering-direction="asc">
				<label for="cmsmasters-template-library-order-local-type" class="cmsmasters-template-library-order-label"><?php esc_html_e( 'Type', 'cmsmasters-elementor' ); ?></label>
			</div>
			<div class="cmsmasters-template-library-local-column-3">
				<input type="radio" id="cmsmasters-template-library-order-local-author" class="cmsmasters-template-library-order-input" name="cmsmasters-template-library-order-local" value="author" data-default-ordering-direction="asc">
				<label for="cmsmasters-template-library-order-local-author" class="cmsmasters-template-library-order-label"><?php esc_html_e( 'Created By', 'cmsmasters-elementor' ); ?></label>
			</div>
			<div class="cmsmasters-template-library-local-column-4">
				<input type="radio" id="cmsmasters-template-library-order-local-date" class="cmsmasters-template-library-order-input" name="cmsmasters-template-library-order-local" value="date">
				<label for="cmsmasters-template-library-order-local-date" class="cmsmasters-template-library-order-label"><?php esc_html_e( 'Creation Date', 'cmsmasters-elementor' ); ?></label>
			</div>
			<div class="cmsmasters-template-library-local-column-5">
				<div class="cmsmasters-template-library-order-label"><?php esc_html_e( 'Actions', 'cmsmasters-elementor' ); ?></div>
			</div>
		</div>
	<# } #>
	<div id="cmsmasters-template-library-templates-container"></div>
	<# if ( 'remote' === activeSource ) { #>
		<div id="cmsmasters-template-library-footer-banner">
			<img class="elementor-nerd-box-icon" src="<?php echo ELEMENTOR_ASSETS_URL . 'images/information.svg'; ?>" />
			<div class="elementor-excerpt"><?php esc_html_e( 'Stay tuned! More awesome templates coming real soon.', 'cmsmasters-elementor' ); ?></div>
		</div>
	<# } #>
</script>

<script type="text/template" id="tmpl-cmsmasters-template-library-template-remote">
	<div class="cmsmasters-template-library-template-body">
		<# if ( 'page' === type ) { #>
			<div class="cmsmasters-template-library-template-screenshot" style="background-image: url({{ thumbnail }});"></div>
		<# } else { #>
			<img src="{{ thumbnail }}">
		<# } #>
		<div class="cmsmasters-template-library-template-preview">
			<i class="eicon-zoom-in-bold" aria-hidden="true"></i>
		</div>
	</div>
	<div class="cmsmasters-template-library-template-footer">
		{{{ cmsmastersElementor.templatesLibrary.layout.getTemplateActionButton( obj ) }}}
		<div class="cmsmasters-template-library-template-name">{{{ title }}} - {{{ type }}}</div>
		<div class="cmsmasters-template-library-favorite">
			<input id="cmsmasters-template-library-template-{{ template_id }}-favorite-input" class="cmsmasters-template-library-template-favorite-input" type="checkbox"{{ favorite ? " checked" : "" }}>
			<label for="cmsmasters-template-library-template-{{ template_id }}-favorite-input" class="cmsmasters-template-library-template-favorite-label">
				<i class="eicon-heart-o" aria-hidden="true"></i>
				<span class="elementor-screen-only"><?php esc_html_e( 'Favorite', 'cmsmasters-elementor' ); ?></span>
			</label>
		</div>
	</div>
</script>

<script type="text/template" id="tmpl-cmsmasters-template-library-template-local">
	<div class="cmsmasters-template-library-template-name cmsmasters-template-library-local-column-1">{{{ title }}}</div>
	<div class="cmsmasters-template-library-template-meta cmsmasters-template-library-template-type cmsmasters-template-library-local-column-2">{{{ elementor.translate( type ) }}}</div>
	<div class="cmsmasters-template-library-template-meta cmsmasters-template-library-template-author cmsmasters-template-library-local-column-3">{{{ author }}}</div>
	<div class="cmsmasters-template-library-template-meta cmsmasters-template-library-template-date cmsmasters-template-library-local-column-4">{{{ human_date }}}</div>
	<div class="cmsmasters-template-library-template-controls cmsmasters-template-library-local-column-5">
		<div class="cmsmasters-template-library-template-preview">
			<i class="eicon-preview-medium" aria-hidden="true"></i>
			<span class="cmsmasters-template-library-template-control-title"><?php esc_html_e( 'Preview', 'cmsmasters-elementor' ); ?></span>
		</div>
		<button class="cmsmasters-template-library-template-action cmsmasters-template-library-template-insert elementor-button elementor-button-success">
			<i class="eicon-file-download" aria-hidden="true"></i>
			<span class="elementor-button-title"><?php esc_html_e( 'Insert', 'cmsmasters-elementor' ); ?></span>
		</button>
		<div class="cmsmasters-template-library-template-more-toggle">
			<i class="eicon-ellipsis-h" aria-hidden="true"></i>
			<span class="elementor-screen-only"><?php esc_html_e( 'More actions', 'cmsmasters-elementor' ); ?></span>
		</div>
		<div class="cmsmasters-template-library-template-more">
			<div class="cmsmasters-template-library-template-delete">
				<i class="eicon-trash-o" aria-hidden="true"></i>
				<span class="cmsmasters-template-library-template-control-title"><?php esc_html_e( 'Delete', 'cmsmasters-elementor' ); ?></span>
			</div>
			<div class="cmsmasters-template-library-template-export">
				<a href="{{ export_link }}">
					<i class="eicon-sign-out" aria-hidden="true"></i>
					<span class="cmsmasters-template-library-template-control-title"><?php esc_html_e( 'Export', 'cmsmasters-elementor' ); ?></span>
				</a>
			</div>
		</div>
	</div>
</script>

<script type="text/template" id="tmpl-cmsmasters-template-library-insert-button">
	<a class="cmsmasters-template-library-template-action cmsmasters-template-library-template-insert elementor-button">
		<i class="eicon-file-download" aria-hidden="true"></i>
		<span class="elementor-button-title"><?php esc_html_e( 'Insert', 'cmsmasters-elementor' ); ?></span>
	</a>
</script>

<script type="text/template" id="tmpl-cmsmasters-template-library-activate-license-button">
	<a class="cmsmasters-template-library-template-action elementor-button cmsmasters-activate-license" href="<?php admin_url( 'admin.php?page=cmsmasters-options' ); ?>" target="_blank">
		<i class="eicon-external-link-square" aria-hidden="true"></i>
		<span class="elementor-button-title"><?php esc_html_e( 'Activate', 'cmsmasters-elementor' ); ?></span>
	</a>
</script>

<script type="text/template" id="tmpl-cmsmasters-template-library-save-template">
	<div class="cmsmasters-template-library-blank-icon">
		<i class="eicon-library-save" aria-hidden="true"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Save', 'cmsmasters-elementor' ); ?></span>
	</div>
	<div class="cmsmasters-template-library-blank-title">{{{ title }}}</div>
	<div class="cmsmasters-template-library-blank-message">{{{ description }}}</div>
	<form id="cmsmasters-template-library-save-template-form">
		<input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>">
		<input id="cmsmasters-template-library-save-template-name" name="title" placeholder="<?php esc_attr_e( 'Enter Template Name', 'cmsmasters-elementor' ); ?>" required>
		<button id="cmsmasters-template-library-save-template-submit" class="elementor-button elementor-button-success">
			<span class="elementor-state-icon">
				<i class="eicon-loading eicon-animation-spin" aria-hidden="true"></i>
			</span>
			<?php esc_html_e( 'Save', 'cmsmasters-elementor' ); ?>
		</button>
	</form>
	<div class="cmsmasters-template-library-blank-footer">
		<?php esc_html_e( 'Want to learn more about the Elementor library?', 'cmsmasters-elementor' ); ?>
		<a class="cmsmasters-template-library-blank-footer-link" href="https://go.elementor.com/docs-library/" target="_blank"><?php esc_html_e( 'Click here', 'cmsmasters-elementor' ); ?></a>
	</div>
</script>

<script type="text/template" id="tmpl-cmsmasters-template-library-import">
	<form id="cmsmasters-template-library-import-form">
		<div class="cmsmasters-template-library-blank-icon">
			<i class="eicon-library-upload" aria-hidden="true"></i>
		</div>
		<div class="cmsmasters-template-library-blank-title"><?php esc_html_e( 'Import Template to Your Library', 'cmsmasters-elementor' ); ?></div>
		<div class="cmsmasters-template-library-blank-message"><?php esc_html_e( 'Drag & drop your .JSON or .zip template file', 'cmsmasters-elementor' ); ?></div>
		<div id="cmsmasters-template-library-import-form-or"><?php esc_html_e( 'or', 'cmsmasters-elementor' ); ?></div>
		<label for="cmsmasters-template-library-import-form-input" id="cmsmasters-template-library-import-form-label" class="elementor-button elementor-button-success"><?php esc_html_e( 'Select File', 'cmsmasters-elementor' ); ?></label>
		<input id="cmsmasters-template-library-import-form-input" type="file" name="file" accept=".json,.zip" required/>
		<div class="cmsmasters-template-library-blank-footer">
			<?php esc_html_e( 'Want to learn more about the Elementor library?', 'cmsmasters-elementor' ); ?>
			<a class="cmsmasters-template-library-blank-footer-link" href="https://go.elementor.com/docs-library/" target="_blank"><?php esc_html_e( 'Click here', 'cmsmasters-elementor' ); ?></a>
		</div>
	</form>
</script>

<script type="text/template" id="tmpl-cmsmasters-template-library-templates-empty">
	<div class="cmsmasters-template-library-blank-icon">
		<img src="<?php echo ELEMENTOR_ASSETS_URL . 'images/no-search-results.svg'; ?>" class="cmsmasters-template-library-no-results" />
	</div>
	<div class="cmsmasters-template-library-blank-title"></div>
	<div class="cmsmasters-template-library-blank-message"></div>
	<div class="cmsmasters-template-library-blank-footer">
		<?php esc_html_e( 'Want to learn more about the Elementor library?', 'cmsmasters-elementor' ); ?>
		<a class="cmsmasters-template-library-blank-footer-link" href="https://go.elementor.com/docs-library/" target="_blank"><?php esc_html_e( 'Click here', 'cmsmasters-elementor' ); ?></a>
	</div>
</script>

<script type="text/template" id="tmpl-cmsmasters-template-library-preview">
	<iframe></iframe>
</script>

<script type="text/template" id="tmpl-cmsmasters-template-library-connect">
	<div id="cmsmasters-template-library-connect-logo" class="e-logo-wrapper">
		<i class="eicon-elementor" aria-hidden="true"></i>
	</div>
	<div class="cmsmasters-template-library-blank-title">
		{{{ title }}}
	</div>
	<div class="cmsmasters-template-library-blank-message">
		{{{ message }}}
	</div>
	<?php $url = Plugin::elementor()->common->get_component( 'connect' )->get_app( 'library' )->get_admin_url( 'authorize' ); ?>
	<a id="cmsmasters-template-library-connect__button" class="elementor-button elementor-button-success" href="<?php echo esc_attr( $url ); ?>">
		{{{ button }}}
	</a>
	<?php
	$base_images_url = $this->get_assets_base_url() . '/assets/images/library-connect/';

	$images = [ 'left-1', 'left-2', 'right-1', 'right-2' ];

	foreach ( $images as $image ) : ?>
		<img id="cmsmasters-template-library-connect__background-image-<?php echo esc_attr( $image ); ?>" class="cmsmasters-template-library-connect__background-image" src="<?php echo esc_attr( $base_images_url . $image ); ?>.png" draggable="false"/>
	<?php endforeach; ?>
</script>
