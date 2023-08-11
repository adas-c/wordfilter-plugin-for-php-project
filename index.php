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
    add_action('admin_init', array($this, 'ourSettings'));
    if (get_option('plugin-word-to-filter')) add_filter('the_content', 'filterLogic');
  }

  function ourSettings() {
    add_settings_section('replacement-text-section', null, null, 'word-filter-options');
    register_setting('replacmentFields', 'replacementText');
    add_settings_field('replacment-text', 'Filtered Text', array($this, 'replacmentFieldHTML'), 'word-filter-options', 'replacement-text-section');
  }

  function replacmentFieldHTML() { ?>
    <input type="text" name='replacementText' value="<?php echo esc_attr(get_option('replacementText', '***')); ?>" >
    <p class="description">Leave blank to simply remove the filtered words.</p>
  <?php }

  function filterLogic($content) {
    $badWords = explode(',', get_option('plugin-word-to-filter'));
    $badWordsTrimmed = array_map('trim', $badWords);
    return str_ireplace($badWordsTrimmed, esc_html(get_option('replacementText', '****')), $content);
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
    <div class="wrap">
      <h1>Word Filter Options</h1>
      <form action="options.php" method="POST">
        <?php 
          settings_errors();
          settings_fields('replacmentFields');
          do_settings_sections('word-filter-options');
          submit_button();
        ?>
      </form>
    </div>
  <?php }

  function handleForm() {
    $nonce = $_POST['ourNonce'] ?? null;
    if (wp_verify_nonce($nonce, 'saveFilterWords') &&  current_user_can('manage_options')) {
      update_option('plugin_word_to_filter', sanitize_text_field($_POST['plugin_word_to_filter'])); ?>
      <div class="updated">
        <p>Your filtered words were saved</p>
      </div>
    <?php } else { ?>
      <div class="error">Sorry you do not permission to perform that action.</div>
    <?php }
   }

  function wordFilterPage() { ?>
    <div class="wrap">
      <h1>Word Filter</h1>
      <?php 
        $sendPost = $_POST['justsubmitted'] ?? null;
        if ($sendPost) echo $this->handleForm(); 
      ?>
      <form method="POST">
        <input type="hidden" name="justsubmitted" value="true">
        <?php wp_nonce_field('saveFilterWords', 'ourNonce'); ?>
        <label for="plugin_word_to_filter"><p>Enter a comma-separeted list of words from your site's content</p></label>
        <div class="word-filter__flex-container">
          <textarea name="plugin_word_to_filter" id="" placeholder="bad, mean"><?php echo esc_textarea(get_option('plugin_word_to_filter')); ?></textarea>
        </div>
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Save changes">
      </form>
    </div>
  <?php }

}

$ourWordFilterPlugin = new OurWordFilterPlugin();