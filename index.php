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
    $mainPageHook = add_menu_page('Words To Filter', 'Word Filter', 'manage_options', 'ourwordfilter', array($this, 'wordFilterPage'), 'dashicons-smiley', 100);
    add_submenu_page('ourwordfilter', 'Words To Filter', 'Words List', 'manage_options', 'ourwordfilter', array($this, 'wordFilterPage'));
    add_submenu_page('ourwordfilter', 'Words Filter Options', 'Options', 'manage_options', 'word-filter-options', array($this, 'optionsSubPage'));
    add_action("load-{$mainPageHook}", array($this,'mainPageAssets'));
  }

  function mainPageAssets() {
    wp_enqueue_style('filterAdminCss', plugin_dir_url(__FILE__) . 'style.css');
  }

  function optionsSubPage() { ?>
  hello
  <?php }

  function wordFilterPage() { ?>
    <div class="wrap">
      <h1>Word Filter</h1>
      <form method="POST">
        <label for="plugin_word_to_filter"><p>Enter a comma-separeted list of words from your site's content</p></label>
        <div class="word-filter__flex-container">
          <textarea name="plugin_word_to_filter" id="" placeholder="bad, mean"></textarea>
        </div>
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Save changes">
      </form>
    </div>
  <?php }

}

$ourWordFilterPlugin = new OurWordFilterPlugin();