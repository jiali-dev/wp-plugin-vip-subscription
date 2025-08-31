<?php 

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

// Add filter
function jialivs_filter_vip_content( $content ) {
    $user_vip_plan = new JialivsUserVipPlan();
    if( is_single(  ) && !$user_vip_plan->isUserVip( get_current_user_id(  ) ) ) {
        return mb_substr($content, 0, 500, 'UTF-8' ) . '...' . '<div class="alert alert-danger">.'.__('To read more, create a VIP account.', 'jialivs').'</div>';
    }
    return $content;
}
add_filter('the_content', 'jialivs_filter_vip_content');

