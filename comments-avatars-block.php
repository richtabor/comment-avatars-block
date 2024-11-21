<?php
/**
 * Plugin Name:       Comment Avatars Block
 * Description:       A WordPress block to add commenters' avatars and the comment count to each post.
 * Plugin URI:        https://rich.blog/comment-avatars-block
 * Requires at least: 6.4
 * Requires PHP:      7.0
 * Version:           1.0.0
 * Author:            Rich Tabor
 * Author URI:        https://rich.blog/blocks
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       comment-avatars-block
 *
 * @package           tabor/comment-avatars-block
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Initializes the Comments Count block.
 *
 * Registers the block using metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @since 1.0.0
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 * @return void
 */
function tabor_comment_avatars_block_init(): void {

	register_block_type( __DIR__ . '/build' );

}
add_action( 'init', 'tabor_comment_avatars_block_init' );

/**
 * Gets the avatars of the latest commenters.
 *
 * Retrieves and formats the avatar images for the most recent commenters
 * on the current post, limited to 3 avatars.
 *
 * @since 1.0.0
 *
 * @return string HTML markup for the comment avatars.
 */
function tabor_comment_avatars_get_avatars(): string {
	$post_id = absint( get_the_ID() );

	$args = array(
		'post_id' => $post_id,
		'number'  => 3,
		'status'  => 'approve',
		'orderby' => 'comment_date',
		'order'   => 'DESC',
	);

	$comments = get_comments( $args );
	$wrapped_avatars = '';

	if ( ! empty( $comments ) ) {
		foreach ( $comments as $comment ) {
			$avatar = get_avatar( $comment->comment_author_email, 96 );
			if ( ! is_wp_error( $avatar ) ) {
				$wrapped_avatars .= sprintf(
					'<span class="comment-avatar">%s</span>',
					wp_kses_post( $avatar )
				);
			}
		}
	}

	return $wrapped_avatars;
}

/**
 * Gets the formatted comment count text.
 *
 * Returns a properly pluralized string of the comment count
 * for the current post.
 *
 * @since 1.0.0
 *
 * @global int $post Current post ID.
 * @return string|void Comment count text or void if no comments.
 */
function tabor_comment_avatars_get_comment_count(): ?string {
	$comment_count = absint( get_comments_number() );

	if ( 0 === $comment_count ) {
		return null;
	}

	return sprintf(
		/* translators: %d: number of comments */
		esc_html( _n(
			'%d reply',
			'%d replies',
			$comment_count,
			'comment-avatars-block'
		) ),
		$comment_count
	);
}

/**
 * Gets the most recent commenter's avatar.
 *
 * Retrieves the avatar for the most recent approved comment
 * on the current post.
 *
 * @since 1.0.0
 *
 * @return string HTML markup for the most recent comment avatar.
 */
function tabor_comment_avatars_get_last_avatar(): string {
	$post_id = absint( get_the_ID() );

	$args = array(
		'post_id' => $post_id,
		'number'  => 1,
		'status'  => 'approve',
		'orderby' => 'comment_date',
		'order'   => 'DESC',
	);

	$comments = get_comments( $args );

	if ( ! empty( $comments ) ) {
		$comment = $comments[0];
		$avatar = get_avatar( $comment->comment_author_email, 96 );
		return is_wp_error( $avatar ) ? '' : wp_kses_post( $avatar );
	}

	return '';
}
