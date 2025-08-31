<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

if( $_SERVER['REQUEST_METHOD'] == 'GET' ) {

    $action = isset($_GET['action']) ? sanitize_text_field( $_GET['action'] ) : '';
    $user_id = isset($_GET['id']) ? intval( $_GET['id'] ) : '';

    if( $action == 'delete' && !empty($user_id) ) {
        if (!current_user_can('manage_options')) {
            wp_die(__('You are not allowed to do this', 'jialivs'));
        }
        $users_plans = new JialivsUserVipPlan();
        $users_plans->deleteUserVipPlan( $user_id ); 
        JialivsFlashMessage::addMessage( __('Plan deleted successfuly', 'jialivs'), 0 );
        wp_redirect( remove_query_arg( ['action', 'id'] ) );
        exit;
    }

    if( $action == 'update' && !empty($user_id) ) {

        $user_info = get_userdata( $user_id );
        $vip_user_plan = new JialivsUserVipPlan();
        $user_plan = $vip_user_plan->find($user_id);
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
                                <div class="uk-alert-primary"><?php echo $user_info->display_name; ?></div>
                            </div>
                            <div class="uk-alert-primary" uk-alert>
                                <div class="uk-alert-primary"><?php echo $user_info->user_email; ?></div>
                            </div>

                            <div class="uk-margin">
                                <select name="plan_type" class="uk-select" aria-label="Select">
                                    <option <?php selected( $user_plan->plan_type, 1, true ) ?> value="3" ><?php echo __('Bronze package', 'jialivs') ?></option>
                                    <option <?php selected( $user_plan->plan_type, 2, true ) ?> value="2" ><?php echo __('Silver result', 'jialivs') ?></option>
                                    <option <?php selected( $user_plan->plan_type, 3, true ) ?> value="1" ><?php echo __('Golden result', 'jialivs') ?></option>
                                </select>
                            </div>
                            <div class="uk-margin">
                                <input class="uk-input" type="text" placeholder="<?php echo __('Start date', 'jialivs') ?>" name="start_date" aria-label="Start date" value="<?php echo jdate('Y-m-d', strtotime($user_plan->start_date), '', '' ,'en' ); ?>" data-jdp>
                            </div>
                            <div class="uk-margin">
                                <input class="uk-input" type="text" placeholder="<?php echo __('End date', 'jialivs') ?>" name="expiration_date" aria-label="Expiration date" value="<?php echo jdate( 'Y-m-d', strtotime($user_plan->expiration_date), '', '' ,'en' ); ?>" data-jdp>
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

   $user_id = isset($_GET['id']) ? intval( $_GET['id'] ) : '';

    if( isset($_POST['vip-update-plan-btn']) ) {

        try {

            // Check nonce
            if ( empty($_POST['vip-update-plan-nonce']) || !isset($_POST['vip-update-plan-nonce']) || !wp_verify_nonce($_POST['vip-update-plan-nonce'], 'vip-update-plan-nonce') )
                throw new Exception( __( 'Security error!', 'jialivs' ) , 403 );

            $plan_id = isset($_POST['plan_type']) ? intval($_POST['plan_type']) : '';
            $start_date = isset($_POST['start_date']) ? gdate(sanitize_text_field($_POST['start_date'])) : '';
            $expiration_date = isset($_POST['expiration_date']) ? gdate(sanitize_text_field($_POST['expiration_date'])) : '';

            if( empty($plan_id) || empty($start_date) || empty($expiration_date) )
                throw new Exception( __( 'Fill All fields!', 'jialivs' ) , 403 );

            $user_plan = new JialivsUserVipPlan();
            $user_plan->editUserVipPlan( $user_id, $plan_id,$start_date, $expiration_date);
            JialivsFlashMessage::addMessage( __('Update completed successfully!', 'jialivs'), 1 );
            wp_redirect( remove_query_arg( ['action', 'id'] ) );
            exit;

        } catch( Exception $ex )
        {
            JialivsFlashMessage::addMessage( $ex->getMessage(), 0 );
            exit;
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

            $user_id = $user->ID;
            $user_plan = new JialivsUserVipPlan();
            $user_plan->updateUserVipPlan( $user_id, $plan_id );
            JialivsFlashMessage::addMessage( __( 'Plan submitted successfully!', 'jialivs' ), 1 );

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
        <button uk-toggle="target: #add_user_plan_modal" type="button" class="uk-button uk-button-primary uk-button-small"><?php echp __( 'Add plan', 'jialivs' )?> </button>
    </div>
    <table class="uk-table uk-table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th><?php echo __( 'Fullname', 'jialivs' ) ?></th>
                <th><?php echo __( 'Email', 'jialivs' ) ?></th>
                <th><?php echo __( 'Account type', 'jialivs' ) ?></th>
                <th><?php echo __( 'Start date', 'jialivs' ) ?></th>
                <th><?php echo __( 'End date', 'jialivs' ) ?></th>
                <th><?php echo __( 'Remaining days', 'jialivs' ) ?></th>
                <th><?php echo __( 'Account status', 'jialivs' ) ?></th>
                <th><?php echo __( 'Action', 'jialivs' ) ?></th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $users_plans = new JialivsUserVipPlan();
                $results = $users_plans->getUsersVipPlans();
            ?>
            <?php if( $results ): ?>
                <?php foreach( $results as $item ): 
                    $user_info = get_userdata( $item->user_id );
                ?>
                    <tr>
                        <td><?php echo $user_info->ID ?></td>
                        <td><?php echo $user_info->display_name ?></td>
                        <td><?php echo $user_info->user_email ?></td>
                        <td><?php echo JialivsPlan::getPlanTitle($item->plan_type) ?></td>
                        <td><?php echo jdate('Y-m-d', strtotime($item->start_date) ) ?></td>
                        <td><?php echo jdate('Y-m-d', strtotime($item->expiration_date) ) ?></td>
                        <td><?php echo JialivsUserVipPlan::calculateRemainingTime($item->expiration_date) ?></td>
                        <td><?php echo $item->expiration_date >= date('Y-m-d') ? '<span class="uk-alert-success" >'. __( 'Active', 'jialivs' ) .'</span>' : '<span class="uk-alert-danger" >' echo __( 'Inactive', 'jialivs' ) .'</span>' ?></td>
                        <td>
                            <a uk-tooltip="title: <?php echo __( 'Delete plan', 'jialivs' ) ?>" href="<?php echo add_query_arg( ['action' => 'delete', 'id' => $user_info->ID ] ) ?>" uk-icon="icon: trash"></a>
                            <a uk-tooltip="title: <?php echo __( 'Edit plan', 'jialivs' ) ?>" href="<?php echo add_query_arg( ['action' => 'update', 'id' => $user_info->ID ] ) ?>" uk-icon="icon: pencil"></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <!-- This is the modal -->
    <div id="add_user_plan_modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body">
            <h2 class="uk-modal-title"><?php echo __( 'Update plan', 'jialivs' ) ?></h2>
            <form method="post">
                <fieldset class="uk-fieldset">
                    <div class="uk-margin">
                        <input class="uk-input" type="text" placeholder="<?php echo __( 'Email', 'jialivs' ) ?>" name="user_email" aria-label="User email" >
                    </div>
                    <div class="uk-margin">
                        <select name="plan_type" class="uk-select" aria-label="Select">
                            <option value="3" > <?php echo __( 'Bronze package', 'jialivs' ) ?> </option>
                            <option value="2" > <?php echo __( 'Silver package', 'jialivs' ) ?> </option>
                            <option value="1" > <?php echo __( 'Golden package', 'jialivs' ) ?> </option>
                        </select>
                    </div>
                    <div class="uk-margin">
                        <button class="uk-button uk-button-default" name="vip-add-plan-btn"><?php echo __( 'Add plan', 'jialivs' ) ?> </button>
                        <?php wp_nonce_field( 'vip-add-plan-nonce', 'vip-add-plan-nonce' ) ?>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
