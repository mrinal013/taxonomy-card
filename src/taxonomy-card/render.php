<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
if ( isset( $attributes['categoryID'] ) ) {
	$category_id            = (int) $attributes['categoryID'];
	$category_name          = get_the_category_by_ID( $category_id );
	$category_thumbnail_id  = get_term_meta( $category_id, 'thumbnail_id', true );
	$category_thumbnail_url = wp_get_attachment_url( $category_thumbnail_id );
	$category_link          = get_category_link( $category_id );
	$category_description   = category_description( $category_id );
	?>
	<div <?php echo get_block_wrapper_attributes(); ?>>
		<a href="<?php echo esc_url( $category_link ); ?>">
			<div class="full-area-wrap">
				<div class="image-wrap">
					<img src="<?php echo esc_url( $category_thumbnail_url ); ?>" alt="">
				</div>
				<div class="main-wrap">
					<div class="box">
						<?php if ( ! isset( $attributes['className'] ) || ( $attributes['className'] !== 'is-style-secondary' ) ) { ?>
							<div class="left"></div>
							<div class="main">
								<div class="name"><?php echo esc_html( $category_name ); ?></div>
								<div class="discover">
									<div><?php echo esc_html( $attributes['promoButton'] ); ?></div>
								</div>
							</div>
							<div class="right"></div>
						<?php } else { ?>
							<div class="main">
							<div class="name-area">
								<div class="name"><?php echo esc_html( $category_name ); ?></div>
								<div class="target-mark"></div>
							</div>
							<div class="description-area">
								<div class="description"><?php echo wp_kses_post( $category_description ); ?></div>
							</div>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</a>
	</div>
	<?php
}
