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

	public function get_row_index( $row ) {
		$index = 0;

		if ( function_exists( 'jet_engine' ) ) {
			$index = jet_engine()->listings->data->get_index();
		}

		if ( is_numeric( $row ) ) {

			if ( str_starts_with( $row, '+' ) ) {
				$coff  = trim( $row, '+' );
				$row = $index + + $coff;
			}

			if ( str_starts_with( $row, '-' ) ) {
				$coff  = trim( $row, '-' );
				$row = $index - + $coff;
			}

			return $row;

		}

		return $index ?? 0;
	}

	public function get_content( $attrs = array() ) {

		$attrs = shortcode_atts( array(
			'id'             => '',
			'repeater_name'  => '',
			'row'            => '',
			'sub_field_name' => '',
		), $attrs, 'jet_repeater' );

		if ( empty( $attrs['repeater_name'] ) && empty( $attrs['sub_field_name'] ) ) {
			return 0;
		}

		if ( empty( $attrs['id'] ) ) {
			$attrs['id'] = $this->get_post_id();
		}

		if ( is_numeric( $attrs['row'] ) ) {
			$attrs['row'] = $this->get_row_index( $attrs['row'] );
		}

		$fields = get_post_meta( $attrs['id'], $attrs['repeater_name'], true );
		$row    = $fields[ 'item-' . $attrs['row'] ] ?? array();

		return $row[ $attrs['sub_field_name'] ] ?? 0;
	}
}

new Jet_Engine_Repeater_Shortcode();