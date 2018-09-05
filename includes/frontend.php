<?php 

namespace Bideja\FacebookPosts;

if (!defined('ABSPATH')) {
    exit;
}

class Frontend
{
    private $page_id;
    private $access_token;
    private $params = 'feed?fields=id,message,story,created_time,description,attachments%7Bmedia,type,subattachments%7D&limit=4&format=json.';
    private $url;

    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'registerScripts' ));
        add_action('wp_ajax_get_facebook_data', array($this, 'getFacebookData'));
        add_action('wp_ajax_nopriv_get_facebook_data', array($this, 'getFacebookData'));
    }

    public function registerScripts()
    {
        wp_register_style('facebook-posts', plugins_url('../assets/css/facebook-posts.css', __FILE__));
        wp_register_script('vue', 'https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.17/vue.min.js', array('jquery'), false, true);
        //wp_register_script('lodash', 'https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.10/lodash.min.js', false, true);
        wp_register_script('facebook-posts', plugins_url('../assets/js/facebook-posts.js', __FILE__), array('jquery', 'vue'), false, true);
    }

    public function init()
    {
        wp_localize_script(
            'facebook-posts',
            'facebookposts',
            array(
                 'url' => admin_url('admin-ajax.php'),
                 'locale' => get_locale()
            )
        );
        wp_enqueue_style('facebook-posts');
        wp_enqueue_script('facebook-posts');
    }

    /**
     * Get settings from plugin page
     */
    public function getSettings()
    {
        $this->page_id = !empty($this->page_id) ? $this->page_id : get_option('bi_fp_page_id');
        $this->access_token = !empty($this->access_token) ? $this->access_token : get_option('bi_fp_access_token');
        $this->url = 'https://graph.facebook.com/v3.1/' . $this->page_id . '/' . $this->params . '&access_token=' . $this->access_token;
    }

    /**
     * Get post data from Facebook
     */
    public function getFacebookData()
    {
        $this->getSettings();

        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $this->url);
        $data = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        $data = json_decode($data, true);
        echo json_encode($data);

        wp_die();
    }

    public function showComponent()
    {
        $this->init();
        ob_start(); ?>

          <div id="js-facebook-posts" v-cloak>
               <facebook-posts></facebook-posts>
          </div>

          <template id="template-facebook-posts">
               <div class="fp">
                    <ul class="fp__posts">
                         <li v-for="post in data">
                              <time :content="getGregorianTime(post.created_time)">{{ getHumanTime(post.created_time) }}</time>
                              <p>{{ post.message }}</p>
                              <figure class="fp__img" v-if="getImageUrlIfExists(post)">
                                   <img :src="getImageUrlIfExists(post)" />
                              </figure>
                              <a class="btn" :href="getPostUrl(post.id)" target="_blank"><?php _e('Read more', 'bideja'); ?></a>
                         </li>
                    </ul>
                    <a href="#" class="fp__pagination"></a>
               </div>
          </template>

          <?php
          return ob_get_clean();
    }
}
