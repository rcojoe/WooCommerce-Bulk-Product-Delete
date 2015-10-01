<?php

/*
Name:        Admin Notice Helper
URI:         https://github.com/iandunn/admin-notice-helper
Version:     0.2
Author:      Ian Dunn
Author URI:  http://iandunn.name
License:     GPLv2
*/

/*  
 * Copyright 2014 Ian Dunn (email : ian@iandunn.name)
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as 
 * published by the Free Software Foundation.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied.' );
}

if ( ! class_exists( 'WooCommerce_Bulk_Product_Delete_Admin_Notice' ) ) {

	class WooCommerce_Bulk_Product_Delete_Admin_Notice {
		// Declare variables and constants
		protected static $instance;
		protected $notices, $notices_were_updated;

		/**
		 * Constructor
		 */
		function __construct() {
            $this->init();
			//add_action( 'init',          array( $this, 'init' ));         // needs to run before other plugin's init callbacks so that they can enqueue messages in their init callbacks
			add_action( 'admin_notices', array( $this, 'print_notices' ) );
			add_action( 'shutdown',      array( $this, 'shutdown' ) );
		}

		/**
		 * Provides access to a single instances of the class using the singleton pattern
		 *
		 * @mvc    Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 * @return object
		 */
		public static function get_singleton() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new WooCommerce_Bulk_Product_Delete_Admin_Notice();
			}

			return self::$instance;
		}

		/**
		 * Initializes variables
		 */
		public function init() {
			$default_notices             = array( 'update' => array(), 'error' => array() );
            
			$this->notices               = array_merge( $default_notices, get_option( 'anh_notices', array() ) );
			$this->notices_were_updated  = false;
		}

		/**
		 * Queues up a message to be displayed to the user
		 *
		 * @param string $message The text to show the user
		 * @param string $type    'update' for a success or notification message, or 'error' for an error message
		 */
		public function enqueue( $message, $type = 'update' ) {

            if(!empty($this->notices)){
                if(isset( $this->notices[ $type ])){
                    if ( in_array( $message, array_values( $this->notices[ $type ] ) ) ) {
                        return;
                    }
                }
            }

			$this->notices[ $type ][]   = (string) apply_filters( 'anh_enqueue_message', $message );
			$this->notices_were_updated = true;
		}

		/**
		 * Displays updates and errors
		 */
		public function print_notices() {
            
			foreach ( array( 'update', 'error' ) as $type ) {
                
				if ( count( $this->notices[ $type ] ) ) {
					$class = 'update' == $type ? 'updated' : 'error';

					?>

                    <div class="anh_message <?php esc_attr_e( $class ); ?>">
                        <?php foreach ( $this->notices[ $type ] as $notice ) : ?>
                            <p><?php echo wp_kses( $notice, wp_kses_allowed_html( 'post' ) ); ?></p>
                        <?php endforeach; ?>
                    </div>

                    <?php
                    

					$this->notices[ $type ]      = array();
					$this->notices_were_updated  = true;
				}
			}
		}

		/**
		 * Writes notices to the database
		 */
		public function shutdown() {
			if ( $this->notices_were_updated ) {
				update_option( 'anh_notices', $this->notices );
			}
		}
	} // end Admin_Notice_Helper

	//WooCommerce_Bulk_Product_Delete_Admin_Notice::get_singleton(); // Create the instance immediately to make sure hook callbacks are registered in time

	if ( ! function_exists( 'wc_bpd_notice' ) ) {
		function wc_bpd_notice( $message, $type = 'update' ) {
			WooCommerce_Bulk_Product_Delete_Admin_Notice::get_singleton()->enqueue( $message, $type );
		}
	}
}

?>