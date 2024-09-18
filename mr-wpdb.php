<?php

/*
 * Plugin Name: MR WPDB
*/


if( ! defined( 'ABSPATH' ) ){
    exit();
}

class Mr_Wpdb{
    private static $instance;

    public static function get_instance(){
        if( ! isset( self::$instance ) ){
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct(){
        register_activation_hook( __FILE__, array( $this, 'register_custom_db' ) );
        add_action( 'admin_menu', array( $this, 'db_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts_and_css' ) );
        add_action( 'wp_ajax_save_contact_data', array( $this, 'ajax_save_contact_data' ) );
    }

    public function register_custom_db(){
        global $wpdb;

        $table_name = $wpdb->prefix . 'contacts';
        $table_charset = $wpdb->get_charset_collate();

        $create_table = "CREATE TABLE IF NOT EXISTS $table_name (
            id INT AUTO_INCREMENT PRIMARY KEY,
            person_name VARCHAR(255) NOT NULL,
            person_phone VARCHAR(20) NOT NULL,
            person_email VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) $table_charset";

        if( ! function_exists( 'dbDelta' ) ){
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }

        dbDelta( $create_table );

    }

    public function enqueue_scripts_and_css(){
        wp_enqueue_script( 'ajax-js', plugin_dir_url( __FILE__ ) . 'assets/js/ajax.js', );

        // Contact
        if( isset( $_POST['contact_submit'] ) ){
            $person_name = isset($_POST['person_name']) ? $_POST['person_name'] : null;
            $person_phone = isset($_POST['person_phone']) ? $_POST['person_phone'] : null;
            $person_email = isset($_POST['person_email']) ? $_POST['person_email'] : null;
        }

        $data = array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'ajax_nonce' => wp_create_nonce( 'contact-ajax' )
        );

        wp_localize_script( 'ajax-js', 'contactData', $data );
    }

    public function db_admin_menu(){
        add_menu_page( 'MR WPDB', 'MR WPDB', 'manage_options', 'mr-wpdb', array( $this, 'mr_wpdb_callback' ) );
    }

    public function mr_wpdb_callback(){

        ?>
            <div class="wrap">
                <div id="mr-contact-submit">
                    <form action="" method="post">
                        <label for="">Name</label> <br>
                        <input type="text" name="person_name" style="margin-bottom:10px;"> <br>
                        <label for="">Phone</label> <br>
                        <input type="text" name="person_phone" style="margin-bottom:10px;"> <br>
                        <label for="">Email</label> <br>
                        <input type="email" name="person_email" style="margin-bottom:10px;"> <br>
                        <input type="hidden" name="action" value="save_contact_data">
                        <?php wp_nonce_field(); ?>
                        <input type="submit" name="contact_submit" value="Submit" class="button button-primary">
                    </form>
                </div>
                <div class="mr-notification">
                    <p style="color:#000"></p>
                </div>
            </div>
        <?php
    }

    public function ajax_save_contact_data(){
        check_ajax_referer( 'contact-ajax' );

        global $wpdb;
        $table_name = $wpdb->prefix . 'contacts';

        $person_name = isset($_POST['person_name']) ? $_POST['person_name'] : '';
        $person_phone = isset($_POST['person_phone']) ? $_POST['person_phone'] : '';
        $person_email = isset($_POST['person_email']) ? $_POST['person_email'] : '';

        $data = array(
            'person_name' => $person_name,
            'person_phone' => $person_phone,
            'person_email' => $person_email
        );

        $format = array(
            '%s',
            '%s',
            '%s',
        );        

        $wpdb->insert( $table_name, $data, $format  );

        wp_send_json( 'Form data submitted successfully!' );
    }
}

Mr_Wpdb::get_instance();