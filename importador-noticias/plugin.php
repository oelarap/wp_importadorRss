<?php
/*
Plugin Name: Noticias UCN
Plugin URI: https://wordpress.org/plugins/health-check/
Description: Importador de noticias UCN
Version: 0.1.0
Author: Osvaldo E. Lara Palacios
Author URI: http://health-check-team.example.com
Text Domain: health-check
Domain Path: /languages
*/

add_action('init', function(){
    include dirname(__FILE__) . '/class-importar-noticia-ucn.php';
    new Importar_Noticia_Ucn();
	if(strtolower($_GET["page"]) == strtolower('importador-noticias-ucn')){
	}
});