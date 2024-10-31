<?php
/*
Plugin Name: Product Sold Count
Plugin URI:  http:\\bhaskardhote.in
Description: This Plugin will used to show the total product sale count & and total view of product .Nice and easy way to know your sale of each product.
Version: 3.0.1
Author: Bhaskar Dhote
Author URI: http:\\bhaskardhote.in
*/
define( 'WP_WOOSOLD_URL', plugin_dir_url(__FILE__) );
define( 'WP_WOOSOLD_PATH', plugin_dir_path(__FILE__) );
define( 'WP_WOOSOLD_SLUG','woo_soldcount' );

if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
global $wpdbb_content_dir;

if(!function_exists('wp_get_current_user')){
	include(ABSPATH."wp-includes/pluggable.php") ; // Include pluggable.php for current user	
}

function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "View: 0";
    }
    return 'Views: '.$count;
}
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

// Remove issues with prefetching adding extra views
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);


add_action( 'woocommerce_single_product_summary', 'wc_product_sold_count', 11 );
function wc_product_sold_count() {
	global $product;
	setPostViews(get_the_ID());
	$units_sold = get_post_meta( $product->id, 'total_sales', true );
	echo '<p>'. sprintf( __( 'Total Sold: %s', 'woocommerce' ), $units_sold ).' '.getPostViews(get_the_ID());'</p>';
}
add_action('inti','wc_product_sold_count');

?>