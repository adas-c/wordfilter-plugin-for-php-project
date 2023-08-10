<?php 

/*
  Plugin Name: Filter Plugin Word
  Version: 1.0
  Author: Adam

*/

if (!defined('ABSPATH')) exit;


class OurWordFilterPlugin {
  function __construct() {
    add_action('admin_menu', array($this, 'ourMenu'));
  }

  function ourMenu() {
    add_menu_page('Words To Filter', 'Word Filter', 'manage_options', 'ourwordfilter', array($this, 'wordFilterPage'), 'dashicons-smiley', 100);
    add_submenu_page('ourwordfilter', 'Words To Filter', 'Words List', 'manage_options', 'ourwordfilter', array($this, 'wordFilterPage'));
    add_submenu_page('ourwordfilter', 'Words Filter Options', 'Options', 'manage_options', 'word-filter-options', array($this, 'optionsSubPage'));
  }

  function optionsSubPage() { ?>
  hello
  <?php }

  function wordFilterPage() { ?>
  hello
  <?php }

}

$ourWordFilterPlugin = new OurWordFilterPlugin();