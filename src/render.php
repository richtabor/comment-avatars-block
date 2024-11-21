<?php
/**
 * Render template for the Comment Avatars block.
 *
 * @package tabor/comment-avatars
 */

$count              = absint( get_comments_number() );
$has_avatar         = ! empty( tabor_comment_avatars_get_last_avatar() );
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class'      => sprintf( 'has-%d-comments', $count ),
		'role'       => 'region',
		'aria-label' => sprintf(
			/* translators: %s: post title */
			__( 'Comments section for "%s"', 'comment-avatars-block' ),
			get_the_title()
		),
	)
);

$link_text = $has_avatar ?
	sprintf(
		/* translators: %d: number of comments */
		_n( '%d reply', '%d replies', $count, 'comment-avatars-block' ),
		$count
	) :
	'';

$link_aria = $has_avatar ?
	sprintf(
		/* translators: %d: number of commenters */
		_n( 'Avatar of %d commenter', 'Avatars of %d recent commenters', $count, 'comment-avatars-block' ),
		min( $count, 3 )
	) :
	'';

// Get block attributes for colors
$block_attributes = (array) $block->attributes;

// Create style attribute for background color
$background_style = '';
if ( isset( $block_attributes['backgroundColor'] ) ) {
	$background_style = sprintf(
		'background-color: var(--wp--preset--color--%s);',
		$block_attributes['backgroundColor']
	);
}

$text_class = isset( $block_attributes['textColor'] ) ? 'has-' . $block_attributes['textColor'] . '-color' : '';
?>

<div <?php echo wp_kses_data( $wrapper_attributes ); ?>>
	<a href="<?php echo esc_url( get_comments_link() ); ?>"
		class="wp-block-tabor-comment-avatars__link">
		<?php if ( ! $has_avatar ) : ?>
			<span class="wp-block-tabor-comment-avatars__avatar-wrapper"
				style="<?php echo esc_attr( $background_style ); ?>"
				aria-hidden="true">
				<span class="wp-block-tabor-comment-avatars__avatar">
					<span class="wp-block-tabor-comment-avatars__plus <?php echo esc_attr( $text_class ); ?>"></span>
				</span>
			</span>
		<?php else : ?>
			<span class="wp-block-tabor-comment-avatars__avatars"
				role="group"
				aria-label="<?php echo esc_attr( $link_aria ); ?>">
				<?php echo wp_kses_post( tabor_comment_avatars_get_avatars() ); ?>
			</span>
		<?php endif; ?>
		<?php if ( $has_avatar ) : ?>
			<span class="wp-block-tabor-comment-avatars__text <?php echo esc_attr( $text_class ); ?>">
				<?php echo esc_html( $link_text ); ?>
			</span>
		<?php endif; ?>
	</a>
</div>
