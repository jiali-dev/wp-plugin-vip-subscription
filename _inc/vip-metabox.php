<?php 

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

// Register Meta Box
function jialivs_add_meta_box() {
    add_meta_box(
        'jialivs_more_settings',          // ID of the meta box
        'پست VIP',                        // Title of the meta box
        'jialivs_meta_box_callback',   // Callback function
        array( 'post', 'technology' ),                              // Screen (Post type)
        'normal',                              // Context (normal, side, advanced)
        'default'                            // Priority (default, high, low)
    );
}
add_action('add_meta_boxes', 'jialivs_add_meta_box');

// Meta Box Callback Function
function jialivs_meta_box_callback($post) {
    $vip = get_post_meta($post->ID, '_jialivs_post_vip', true);
    wp_nonce_field('jialivs_save_meta_box_data', 'jialivs_meta_box_nonce');
    ?>
    <p><?php _e('Is this a VIP post?', 'jialivs'); ?></p>
    <label>
        <input type="radio" name="jialivs_post_vip" value="1" <?php checked($vip, 1); ?> />
        <?php _e('No', 'jialivs'); ?>
    </label><br>
    <label>
        <input type="radio" name="jialivs_post_vip" value="2" <?php checked($vip, 2); ?> />
        <?php _e('Yes', 'jialivs'); ?>
    </label>
    <?php
}


// Save Meta Box Data
function jialivs_save_meta_box_data($post_id) {
    
    // Check if our nonce is set
    if (!isset($_POST['jialivs_meta_box_nonce'])) {
        return;
    }

    // Verify that the nonce is valid
    if (!wp_verify_nonce($_POST['jialivs_meta_box_nonce'], 'jialivs_save_meta_box_data')) {
        return;
    }

    // Check if this is an autosave, avoid overwriting
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions (optional but recommended)
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Check if the field is set and sanitize the input
    if (isset($_POST['jialivs_post_vip'])) {
        $sanitized_value = intval($_POST['jialivs_post_vip']);
        update_post_meta($post_id, '_jialivs_post_vip', $sanitized_value);
    }
}
add_action('save_post', 'jialivs_save_meta_box_data');
