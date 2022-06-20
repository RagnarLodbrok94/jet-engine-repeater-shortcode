<?php
/**
 * Plugin Name: JetEngine - Get value from repeater field
 * Plugin URI: #
 * Description: Creates a new shortcode that allows get value from repeater field.
 * Version:     1.1.2
 * Author:      Crocoblock
 * Author URI:  https://crocoblock.com/
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


class Jet_Engine_Repeater_Shortcode {
	public function __construct() {
		add_shortcode( 'jet_repeater', [ $this, 'get_content' ] );
	}

	public function get_post_id() {
		if ( function_exists( 'jet_engine' ) ) {
			return jet_engine()->listings->data->get_current_object_id();
		}


		return get_the_ID();
	}

	public function get_content( $atts = array() ) {

		$atts = shortcode_atts( array(
			'id'             => '',
			'repeater_name'  => '',
			'row'            => '0',
			'sub_field_name' => '',
		), $atts, 'jet_repeater' );

		if ( empty( $atts['repeater_name'] ) && empty( $atts['sub_field_name'] ) ) {
			return 0;
		}

		if ( empty( $atts['id'] ) ) {
			$atts['id'] = $this->get_post_id();
		}

		$fields = get_post_meta( $atts['id'], $atts['repeater_name'], true );
		$row    = $fields[ 'item-' . $atts['row'] ] ?? array();

		return $row[ $atts['sub_field_name'] ] ?? 0;
	}
}

new Jet_Engine_Repeater_Shortcode();