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
        $vip_plan = new Jialivs_Plan();
        $vip_plan->delete( $plan_id ); 
        Jialivs_Flash_Message::addMessage( 'پلن با موفقیت حذف شد!', 0 );
        wp_redirect( remove_query_arg( ['action', 'id'] ) );
        exit;
    }

    if( $action == 'update' && !empty($plan_id) ) {

        $plan = new Jialivs_Plan();
        $vip_plan = $plan->find_by_id($plan_id);
        ?>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    UIkit.modal('#update-plan-modal').show();
                });
            </script>
            <!-- This is the modal -->
            <div id="update-plan-modal" uk-modal>
                <div class="uk-modal-dialog uk-modal-body">
                    <h2 class="uk-modal-title">آپدیت پلن</h2>
                    <form method="post">
                        <fieldset class="uk-fieldset">

                            <div class="uk-alert-primary" uk-alert>
                                <div class="uk-alert-primary"><?php echo $plan::get_plan_title($vip_plan->type); ?></div>
                            </div>

                            <div class="uk-margin">
                                <input class="uk-input" type="text" placeholder="قیمت" name="price" aria-label="Price" value="<?php echo $vip_plan->price; ?>">
                            </div>
                            
                            <div class="uk-margin">
                                <div class="uk-form-label">پیشنهادی</div>
                                <div class="uk-form-controls">
                                    <label><input class="uk-radio" type="radio" name="recommended" value="1" <?php checked( $vip_plan->recommended, 1, true ) ?>>بله</label><br>
                                    <label><input class="uk-radio" type="radio" name="recommended" value="0" <?php checked( $vip_plan->recommended, 0, true ) ?>>خیر</label>
                                </div>
                            </div>

                            <div class="uk-margin">
                                <div class="uk-form-label">وضعیت</div>
                                <div class="uk-form-controls">
                                    <label><input class="uk-radio" type="radio" name="status" value="1" <?php checked( $vip_plan->status, 1, true ) ?> >فعال</label><br>
                                    <label><input class="uk-radio" type="radio" name="status" value="0" <?php checked( $vip_plan->status, 0, true ) ?>>غیرفعال</label>
                                </div>
                            </div>

                            <div class="uk-margin">
                                <textarea class="uk-textarea" name="benefits" rows="5" placeholder="مزایا" aria-label="Textarea">
                                    <?php echo trim( esc_html( $vip_plan->benefits ) ) ?>
                                </textarea>
                            </div>

                            <div class="uk-margin">
                                <button class="uk-button uk-button-default" name="vip-update-plan-btn">ویرایش پلن</button>
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
            // wp_die(jve_pretty_var_dump( empty($recommended)));
            if( empty($plan_id) || empty($price) || $recommended == '' || $status == '' || empty($benefits))
                throw new Exception( __( 'Fill All fields!', 'jialivs' ) , 403 );

            $vip_plan = new Jialivs_Plan();
            $update = $vip_plan->edit_vip_plan( $plan_id, $price, $recommended, $status, $benefits);
            Jialivs_Flash_Message::addMessage( 'بروزرسانی با موفقیت انجام شد!', 1 );

            wp_redirect( remove_query_arg( ['action', 'id'] ) );
            exit;

        } catch( Exception $ex )
        {
            Jialivs_Flash_Message::addMessage( $ex->getMessage(), 0 );
            wp_redirect( remove_query_arg( ['action', 'id'] ) );
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

            $plan_id = $user->ID;
            $user_plan = new Jialivs_Plan();
            $user_plan->update_user_vip_plan( $plan_id, $plan_id );
            Jialivs_Flash_Message::addMessage( 'پلن با موفقیت ثبت شد!', 1 );

        } catch( Exception $ex )
        {
            Jialivs_Flash_Message::addMessage( $ex->getMessage(), 0 );
            exit;
        }
        
    }
 
}
?>

<div class="uk-container">
    <?php Jialivs_Flash_Message::showMessage( ); ?>
    <div class="uk-flex uk-flex-between">
        <h1 class="uk-heading-divider">
            <?php echo get_admin_page_title(  ) ?>
        </h1>
    </div>
    <table class="uk-table uk-table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>عنوان</th>
                <th>قیمت</th>
                <th>مزایا</th>
                <th>پیشنهاد</th>
                <th>وضعیت</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $plans = new Jialivs_Plan();
                $results = $plans->find();
            ?>
            <?php if( $results ): ?>
                <?php foreach( $results as $item ): ?>
                    <tr>
                        <td><?php echo $item->id ?></td>
                        <td><?php echo $plans->get_plan_title($item->type) ?></td>
                        <td><?php echo $item->price ?></td>
                        <td><?php echo $item->benefits ?></td>
                        <td><?php echo $item->recommended ? '<span class="uk-alert-success" >بله</span>' : '<span class="uk-alert-danger" >خیر</span>' ?></td>
                        <td><?php echo $item->status ? '<span class="uk-alert-success" >فعال</span>' : '<span class="uk-alert-danger" >غیر فعال</span>' ?></td>
                        <td>
                            <a uk-tooltip="title: حذف پلن" href="<?php echo add_query_arg( ['action' => 'delete', 'id' => $item->id ] ) ?>" uk-icon="icon: trash"></a>
                            <a uk-tooltip="title: ویرایش پلن" href="<?php echo add_query_arg( ['action' => 'update', 'id' => $item->id ] ) ?>" uk-icon="icon: pencil"></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</div>
