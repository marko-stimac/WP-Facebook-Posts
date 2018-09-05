<?php 
/**
 * Register setting fields for plugin
 */

namespace Bideja\FacebookPosts;

if (!defined('ABSPATH')) {
    exit;
}

class Backend
{
    public function __construct()
    {
        add_action('admin_menu', array( $this, 'createOptionsPage'));
        add_action('admin_init', array( $this, 'registerOptionsSettings'));
    }

    /**
     * Create page for settings
     */
    public function createOptionsPage()
    {
        add_options_page(
            'Facebook Posts',
            'Facebook Posts',
            'manage_options',
            'facebook-posts',
            array( $this, 'createOptionsFields' )
            );
    }

    /**
     * Register fields for settings
     */
    public function registerOptionsSettings()
    {
        register_setting('ga-settings-group', 'bi_fp_page_id');
        register_setting('ga-settings-group', 'bi_fp_access_token');
    }

    /**
     * Create form for settings
     */
    public function createOptionsFields()
    {
        ?>
          <div class="wrap">
               <h1>Facebook Posts</h1>
               <form method="post" action="options.php">
                    <?php settings_fields('ga-settings-group'); ?>
                    <?php do_settings_sections('ga-settings-group'); ?>

                    <table class="form-table">
                         <tr valign="top">
                              <th scope="row">Page ID:</th>
                              <td>
                              <input type="text" name="bi_fp_page_id" value="<?php echo  get_option('bi_fp_page_id'); ?>" size="50" />
                              </td>
                         </tr>
                         <tr valign="top">
                              <th scope="row">Access token:</th>
                              <td>
                              <input type="text" name="bi_fp_access_token" value="<?php echo  get_option('bi_fp_access_token'); ?>" size="50" />
                              </td>
                         </tr>
                    </table>
                    <?php submit_button(); ?>
               </form>
          </div>
		<?php
    }
}
