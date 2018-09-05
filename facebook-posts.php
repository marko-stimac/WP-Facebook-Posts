<?php
/**
 * Plugin Name: BI Facebook posts
 * Description: Shows latest Facebook posts
 * Version: 1.0.0
 */

namespace Bideja\FacebookPosts;

if (!defined('ABSPATH')) {
    exit;
}

require_once 'includes/backend.php';
require_once 'includes/frontend.php';

new Backend();
$facebook_posts = new Frontend();
add_shortcode('facebook-posts', array($facebook_posts, 'showComponent'));
