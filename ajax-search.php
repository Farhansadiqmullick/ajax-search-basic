<?php
/*
Plugin Name: AJAX Search Basic
Plugin URI: //github.com/farhansadiqmullick/ajax-search-basic
Description: Ajax Basic Search Filter
Version: 1.0
Author: Farhan Mullick
Author URI: 
License: GPLv2 or later
Text Domain: ajseaarch
Domain Path: /languages/
*/

class ajaxsearch
{
    public function __construct()
    {
        add_action('plugins_loaded', array($this, 'ajax_search_loaded'));
        add_action('wp_enqueue_scripts', array($this, 'ajax_search_assets'));
        add_shortcode('search_form_shortcode', array($this, 'ajax_search_shortcode'));
        add_action('pre_get_posts', array($this, 'ajax_custom_post_type'));
        add_action('wp_ajax_ajax-search-handle', array($this, 'ajax_data_fetch'));
        add_action('wp_ajax_nopriv_ajax-search-handle',  array($this, 'ajax_data_fetch'));
    }

    function ajax_search_loaded()
    {
        load_plugin_textdomain('ajaxsearch', false, dirname(__FILE__) . "/languages");
    }

    function ajax_search_assets()
    {
        $ajaxurl = admin_url("admin-ajax.php");
        wp_enqueue_style('ajax-search-css', plugin_dir_url(__FILE__) . "assets/public/css/ajax_search.css", null, rand(111, 999), 'all');
        wp_enqueue_script('ajax-search-js', plugin_dir_url(__FILE__) . "assets/public/js/script.js", ['jquery'], rand(111, 999), true);
        wp_enqueue_script('ajax-search-handle', plugin_dir_url(__FILE__) . "assets/public/js/ajax_search.js", ['jquery'], rand(111, 999), true);
        wp_localize_script('ajax-search-handle', 'fetch', array('ajaxurl' => $ajaxurl));
    }

    function ajax_search_shortcode()
    {
        $search = get_query_var('s');
        $home_url = home_url('/');
        $shortcode = <<<EOD
        <div class="search_bar">
            <form action="/" method="get" autocomplete="off">
                <input type="text" name="s" placeholder="Enter Post Name..." id="keyword" value="{$search}" class="input_search">
                <input type="submit" value="Submit">
            </form>
            <div class="search_result" id="datafetch">
                <ul>
                    <li>Please wait..</li>
                </ul>
            </div>
        </div>
EOD;
        return $shortcode;
    }

    function ajax_custom_post_type($query)
    {
        if ($query->is_main_query() && $query->is_search() && !is_admin()) {
            $query->set('post_type', array('post', 'page'));
        }
    }

    function ajax_data_fetch()
    {
        $args = array(
            'posts_per_page' => -1,
            's' => esc_attr($_POST['keyword']),
            'post_type' => array('page', 'post'),
        );
        $the_query = new WP_Query($args);
        if ($the_query->have_posts()) :
            echo '<ul>';
            while ($the_query->have_posts()) : $the_query->the_post(); ?>

                <li><a href="<?php echo esc_url(the_permalink()); ?>" target="_blank"><?php the_title(); ?></a></li>

<?php endwhile;
            echo '</ul>';
            wp_reset_postdata();
        endif;
        die();
    }
}

new ajaxsearch();
