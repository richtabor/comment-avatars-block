<?php
/**
 * Render template for the Comment Avatars block.
 *
 * @package tabor/comment-avatars-block
 */

$count              = absint( get_comments_number() );
$has_comments       = $count > 0;
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class' => sprintf( 'has-%d-comments', $count ),
		'role'  => 'region',
		'aria-label' => sprintf(
			/* translators: %s: post title */
			__( 'Comments section for "%s"', 'comment-avatars-block' ),
			get_the_title()
		),
	)
);

$link_text = $has_comments ?
	sprintf(
		/* translators: %d: number of comments */
		_n( '%d reply', '%d replies', $count, 'comment-avatars-block' ),
		$count
	) :
	__( 'Leave a comment', 'comment-avatars-block' );

$link_aria = $has_comments ?
	sprintf(
		/* translators: %d: number of commenters */
		_n( 'Avatar of %d commenter', 'Avatars of %d recent commenters', $count, 'comment-avatars-block' ),
		min( $count, 3 )
	) :
	'';
?>

<div <?php echo wp_kses_data( $wrapper_attributes ); ?>>
	<a href="<?php echo esc_url( get_comments_link() ); ?>"
		class="wp-block-tabor-comment-avatars__link"
		aria-label="<?php echo esc_attr( $link_text ); ?>">
		<?php if ( ! $has_comments ) : ?>
			<span class="wp-block-tabor-comment-avatars__empty" aria-hidden="true"></span>
		<?php else : ?>
			<span class="wp-block-tabor-comment-avatars__avatars"
				role="group"
				aria-label="<?php echo esc_attr( $link_aria ); ?>">
				<?php echo wp_kses_post( taborcommentavatarsblock_get_avatars() ); ?>
			</span>
		<?php endif; ?>
		<?php if ( $has_comments ) : ?>
			<span class="wp-block-tabor-comment-avatars__text">
				<?php echo esc_html( $link_text ); ?>
			</span>
		<?php endif; ?>
	</a>
</div>
