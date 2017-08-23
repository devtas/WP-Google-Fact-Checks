<?php

class wpgfc {
	private static $initiated = false;

	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

	public static function init_hooks() {
		self::$initiated = true;

		add_action( 'add_meta_boxes', array( 'wpgfc', 'wp_gfc_register_meta_boxes' ));
		add_action( 'save_post', array( 'wpgfc', 'wp_gfc_save_meta_box' ), 10, 2);
	}

	/**
	 * Register meta box.
	 */
	public static function wp_gfc_register_meta_boxes() {
	    add_meta_box( 'pwgfc-box', __( 'Google Fact Checks', 'wpgfc' ), array( 'wpgfc', 'wp_gfc_display_callback' ), 'post' );
	}

	/**
	 * Meta box display callback.
	 *
	 * @param WP_Post $post Current post object.
	 */
	public static function wp_gfc_display_callback( $post ) {
	    // Display code/markup goes here. Don't forget to include nonces!
	    wp_nonce_field( basename( __FILE__ ), 'wpgfc_nonce' );
	    $prfx_stored_meta = get_post_meta( $post->ID );
	   	?>
	    <p>
	        <label for="wpgfc_alternate_name1" class="wpgfc-row-title">
	        	<input type="radio" name="wpgfc_alternate_name" id="wpgfc_alternate_name1"> <?php _e( 'Verdadeiro', 'wpgfc' )?>
	        </label>
	        <br />

	        <label for="wpgfc_alternate_name2" class="wpgfc-row-title">
	        	<input type="radio" name="wpgfc_alternate_name" id="wpgfc_alternate_name2"> <?php _e( 'Falso', 'wpgfc' )?>
	        </label>
	        <br />

	        <label for="wpgfc_alternate_name3" class="wpgfc-row-title">
	        	<input type="radio" name="wpgfc_alternate_name" id="wpgfc_alternate_name3"> <?php _e( 'Insustentável', 'wpgfc' )?>
	        </label>
	        <br />

	        <label for="wpgfc_alternate_name4" class="wpgfc-row-title">
	        	<input type="radio" name="wpgfc_alternate_name" id="wpgfc_alternate_name4"> <?php _e( 'Exagerado', 'wpgfc' )?>
	        </label>
	        <br />
	    </p>
	    <?php
	}

	/**
	 * Save meta box content.
	 *
	 * @param int $post_id Post ID
	 */
	function wp_gfc_save_meta_box( $post_id ) {
	    /* Verify the nonce before proceeding. */
		if ( !isset( $_POST['wpgfc_alternate_name_nonce'] ) || !wp_verify_nonce( $_POST['wpgfc_alternate_name_nonce'], basename( __FILE__ ) ) )
			return $post_id;

		/* Get the post type object. */
  		$post_type = get_post_type_object( $post->post_type );

  		/* Check if the current user has permission to edit the post. */
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
			return $post_id;

		/* Get the posted data and sanitize it for use as an HTML class. */
		$new_meta_value = ( isset( $_POST['wpgfc_alternate_name'] ) ? sanitize_html_class( $_POST['wpgfc_alternate_name'] ) : '' );

		/* Get the meta key. */
		$meta_key = 'wpgfc_alternate_name';

		/* Get the meta value of the custom field key. */
		$meta_value = get_post_meta( $post_id, $meta_key, true );

		/* If a new meta value was added and there was no previous value, add it. */
		if ( $new_meta_value && '' == $meta_value )
			add_post_meta( $post_id, $meta_key, $new_meta_value, true );

		/* If the new meta value does not match the old value, update it. */
		elseif ( $new_meta_value && $new_meta_value != $meta_value )
			update_post_meta( $post_id, $meta_key, $new_meta_value );

		/* If there is no new meta value but an old value exists, delete it. */
		elseif ( '' == $new_meta_value && $meta_value )
			delete_post_meta( $post_id, $meta_key, $meta_value );
	}
}




