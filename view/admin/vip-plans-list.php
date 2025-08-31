<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

if( $_SERVER['REQUEST_METHOD'] == 'GET' ) {

    $action = isset($_GET['action']) ? sanitize_text_field( $_GET['action'] ) : '';
    $plan_id = isset($_GET['id']) ? intval( $_GET['id'] ) : '';

    if( $action == 'delete' && !empty($plan_id) ) {
        if (!current_user_can('manage_options')) {
            wp_die(__('You are not allowed to do this', 'jialivs'));
        }
        $vip_plan = new JialivsPlan();
        $vip_plan->delete( $plan_id ); 
        JialivsFlashMessage::addMessage( __('plan successfuly deleted', 'jialivs'), 0 );
        wp_redirect( remove_query_arg( ['action', 'id'] ) );
        exit;
    }

    if( $action == 'update' && !empty($plan_id) ) {

        $plan = new JialivsPlan();
        $vip_plan = $plan->findByID($plan_id);
        ?>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    UIkit.modal('#update-plan-modal').show();
                });
            </script>
            <!-- This is the modal -->
            <div id="update-plan-modal" uk-modal>
                <div class="uk-modal-dialog uk-modal-body">
                    <h2 class="uk-modal-title"><?php echo __( 'Update plan', 'jialivs' ) ?></h2>
                    <form method="post">
                        <fieldset class="uk-fieldset">

                            <div class="uk-alert-primary" uk-alert>
                                <div class="uk-alert-primary"><?php echo $plan::getPlanTitle($vip_plan->type); ?></div>
                            </div>

                            <div class="uk-margin">
                                <input class="uk-input" type="text" placeholder="<?php echo __( 'Price', 'jialivs' ) ?>" name="price" aria-label="Price" value="<?php echo $vip_plan->price; ?>">
                            </div>
                            
                            <div class="uk-margin">
                                <div class="uk-form-label"><?php echp __('Suggestion', 'jialivs') ?></div>
                                <div class="uk-form-controls">
                                    <label><input class="uk-radio" type="radio" name="recommended" value="1" <?php checked( $vip_plan->recommended, 1, true ) ?>><?php echo __('Yes', 'jialivs') ?></label><br>
                                    <label><input class="uk-radio" type="radio" name="recommended" value="0" <?php checked( $vip_plan->recommended, 0, true ) ?>><?php echo __('No', 'jialivs') ?></label>
                                </div>
                            </div>

                            <div class="uk-margin">
                                <div class="uk-form-label"><?php echo __( Status', 'jialivs' ) ?></div>
                                <div class="uk-form-controls">
                                    <label><input class="uk-radio" type="radio" name="status" value="1" <?php checked( $vip_plan->status, 1, true ) ?> ><?php echo __('Active', 'jialivs') ?></label><br>
                                    <label><input class="uk-radio" type="radio" name="status" value="0" <?php checked( $vip_plan->status, 0, true ) ?>><?php echo __('Inactive', 'jialivs') ?></label>
                                </div>
                            </div>

                            <div class="uk-margin">
                                <textarea class="uk-textarea" name="benefits" rows="5" placeholder="<?php echo __('Benefits', 'jialivs') ?>" aria-label="Textarea">
                                    <?php echo trim( esc_html( $vip_plan->benefits ) ) ?>
                                </textarea>
                            </div>

                            <div class="uk-margin">
                                <button class="uk-button uk-button-default" name="vip-update-plan-btn"><?php echo __('Edit plan', 'jialivs') ?></button>
                                <?php wp_nonce_field( 'vip-update-plan-nonce', 'vip-update-plan-nonce' ) ?>
                            </div>

                        </fieldset>
                    </form>
                </div>
            </div>
        <?php
    }

}

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

   $plan_id = isset($_GET['id']) ? intval( $_GET['id'] ) : '';

    if( isset($_POST['vip-update-plan-btn']) ) {

        try {

            // Check nonce
            if ( empty($_POST['vip-update-plan-nonce']) || !isset($_POST['vip-update-plan-nonce']) || !wp_verify_nonce($_POST['vip-update-plan-nonce'], 'vip-update-plan-nonce') )
                throw new Exception( __( 'Security error!', 'jialivs' ) , 403 );

            $price = isset($_POST['price']) ? sanitize_text_field($_POST['price']) : '';
            $recommended = isset($_POST['recommended']) ? intval($_POST['recommended']) : '';
            $status = isset($_POST['status']) ? intval($_POST['status']) : '';
            $benefits = isset($_POST['benefits']) ? sanitize_textarea_field($_POST['benefits']) : '';
            if( empty($plan_id) || empty($price) || $recommended == '' || $status == '' || empty($benefits))
                throw new Exception( __( 'Fill All fields!', 'jialivs' ) , 403 );

            $vip_plan = new JialivsPlan();
            $update = $vip_plan->editVipPlan( $plan_id, $price, $recommended, $status, $benefits);
            JialivsFlashMessage::addMessage( __('ّUpdate completed successfully!', 'jialivs') , 1 );

            wp_redirect( remove_query_arg( ['action', 'id'] ) );

        } catch( Exception $ex )
        {
            JialivsFlashMessage::addMessage( $ex->getMessage(), 0 );
            wp_redirect( remove_query_arg( ['action', 'id'] ) );
        }
        
    }

}

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
 
    if( isset($_POST['vip-add-plan-btn']) ) {

        try {

            // Check nonce
            if ( empty($_POST['vip-add-plan-nonce']) || !isset($_POST['vip-add-plan-nonce']) || !wp_verify_nonce($_POST['vip-add-plan-nonce'], 'vip-add-plan-nonce') )
                throw new Exception( __( 'Security error!', 'jialivs' ) , 403 );

            $user_email = isset($_POST['user_email']) ? sanitize_email( $_POST['user_email'] ) : '';
            $plan_id = isset($_POST['plan_type']) ? intval($_POST['plan_type']) : '';

            if( empty($plan_id) || empty($user_email) )
                throw new Exception( __( 'Fill All fields!', 'jialivs' ) , 403 );

            $user = get_user_by( 'email', $user_email );

            if( !$user )
                throw new Exception( __( 'User is not exist!', 'jialivs' ) , 403 );

            $plan_id = $user->ID;
            $user_plan = new JialivsPlan();
            $user_plan->updateUserVipPlan( $plan_id, $plan_id );
            JialivsFlashMessage::addMessage( __('Plan submited successfuly!', 'jialivs'), 1 );

        } catch( Exception $ex )
        {
            JialivsFlashMessage::addMessage( $ex->getMessage(), 0 );
            exit;
        }
        
    }
 
}
?>

<div class="uk-container">
    <?php JialivsFlashMessage::showMessage( ); ?>
    <div class="uk-flex uk-flex-between">
        <h1 class="uk-heading-divider">
            <?php echo get_admin_page_title(  ) ?>
        </h1>
    </div>
    <table class="uk-table uk-table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th><?php echo __('Title', 'jialivs') ?></th>
                <th><?php echo __('Price', 'jialivs') ?></th>
                <th><?php echo __('Benefits', 'jialivs') ?></th>
                <th><?php echo __('Suggestion', 'jialivs') ?></th>
                <th><?php echo __('Status', 'jialivs') ?></th>
                <th><?php echo __('Action', 'jialivs') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $plans = new JialivsPlan();
                $results = $plans->find();
            ?>
            <?php if( $results ): ?>
                <?php foreach( $results as $item ): ?>
                    <tr>
                        <td><?php echo $item->id ?></td>
                        <td><?php echo $plans->getPlanTitle($item->type) ?></td>
                        <td><?php echo $item->price ?></td>
                        <td><?php echo $item->benefits ?></td>
                        <td><?php echo $item->recommended ? '<span class="uk-alert-success" >'. __( 'Yes', 'jialivs' ) .'</span>' : '<span class="uk-alert-danger" >'. __( 'No', 'jialivs' ) .'</span>' ?></td>
                        <td><?php echo $item->status ? '<span class="uk-alert-success" >'. __('Active', 'jialivs') .'</span>' : '<span class="uk-alert-danger" >'.  __('Inactive', 'jialivs') .' </span>' ?></td>
                        <td>
                            <a uk-tooltip="title: <?php echo __( 'Delete plan', 'jialivs' ) ?>" href="<?php echo add_query_arg( ['action' => 'delete', 'id' => $item->id ] ) ?>" uk-icon="icon: trash"></a>
                            <a uk-tooltip="title: <?php echo __('Edit plan', 'jialivs') ?>" href="<?php echo add_query_arg( ['action' => 'update', 'id' => $item->id ] ) ?>" uk-icon="icon: pencil"></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</div>
