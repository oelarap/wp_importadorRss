<?php

/**
 * Admin Menu
 */
class Importar_Noticia_Ucn {

    /**
     * Kick-in the class
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    }

    /**
     * Add menu items
     *
     * @return void
     */
    public function admin_menu() {
        /** Top Menu **/
        add_menu_page( __( 'Importador', 'ostech' ), __( 'Importador', 'ostech' ), 'manage_options', 'importador', array( $this, 'plugin_page' ), 'dashicons-book-alt', null );

        add_submenu_page( 'importador', __( 'Importador', 'ostech' ), __( 'Importador', 'ostech' ), 'manage_options', 'importador', array( $this, 'plugin_page' ) );
    }

    /**
     * Handles the plugin page
     *
     * @return void
     */
    public function plugin_page() {
        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
        $id     = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

        switch ($action) {
            default:

                $template = dirname( __FILE__ ) . '/importador.php';
                break;
        }

        if ( file_exists( $template ) ) {
            include $template;
        }
    }
}