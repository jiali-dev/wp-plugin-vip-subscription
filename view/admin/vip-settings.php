<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

// Get vip settings
$vip_settings = get_option( '_vip_settings' );
$merchant_id = sanitize_text_field( $vip_settings['merchant_id'] );
$gateway_slug = sanitize_text_field( $vip_settings['gateway_slug'] );
$checkout_slug = sanitize_text_field( $vip_settings['checkout_slug'] );
$payment_result_slug = sanitize_text_field( $vip_settings['payment_result_slug'] );

?>

<?php 
    if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

        if( isset($_POST['vip-settings-btn'])) {
            try {
                if( !isset( $_POST['vip-settings-nonce'] ) || !wp_verify_nonce( $_POST['vip-settings-nonce'], 'vip-settings-nonce' ) )
                    throw new Exception( __( 'Security error!', 'jialivs' ) , 403 );

                $merchant_id = sanitize_text_field( $_POST['merchant_id'] );
                $gateway_slug = sanitize_text_field( $_POST['gateway_slug'] );
                $checkout_slug = sanitize_text_field( $_POST['checkout_slug'] );
                $payment_result_slug = sanitize_text_field( $_POST['payment_result_slug'] );
            
                if( empty( $merchant_id ) || empty( $gateway_slug ) || empty( $checkout_slug ) || empty( $payment_result_slug ) ) 
                    throw new Exception( __( 'Fill All fields!', 'jialivs' ) , 403 );

                $settings = [
                    'merchant_id' => $merchant_id,
                    'gateway_slug' => $gateway_slug,
                    'checkout_slug' => $checkout_slug,
                    'payment_result_slug' => $payment_result_slug,
                ];
        
                jve_pretty_var_dump(update_option( '_vip_settings', $settings ));
        
                Jialivs_Flash_Message::addMessage( 'تنظیمات با موفقیت ذخیره شد!', 1 );
                wp_redirect( admin_url( 'admin.php?page=jialivs_vip_settings' ) );
                exit;

            } catch( Exception $ex )
            {
                Jialivs_Flash_Message::addMessage( $ex->getMessage(), 0 );
                wp_redirect( admin_url( 'admin.php?page=jialivs_vip_settings' ) );
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
    <form method="post">
        <fieldset class="uk-fieldset">
            <div class="uk-margin">
                <input class="uk-input" type="text" placeholder="مرچنت آی دی" name="merchant_id" aria-label="Merchant id" value="<?php echo $merchant_id; ?>">
            </div>
            <div class="uk-margin">
                <input class="uk-input" type="text" placeholder="نامک گیت وی" name="gateway_slug" aria-label="Gateway slug" value="<?php echo $gateway_slug; ?>">
            </div>
            <div class="uk-margin">
                <input class="uk-input" type="text" placeholder="نامک چک اوت" name="checkout_slug" aria-label="Checkout slug" value="<?php echo $checkout_slug; ?>">
            </div>
            <div class="uk-margin">
                <input class="uk-input" type="text" placeholder="نامک نتیجه پرداخت" name="payment_result_slug" aria-label="Payment result slug" value="<?php echo $payment_result_slug; ?>">
            </div>

            <div class="uk-margin">
                <button class="uk-button uk-button-primary" name="vip-settings-btn">ذخیره</button>
                <?php wp_nonce_field( 'vip-settings-nonce', 'vip-settings-nonce' ) ?>
            </div>
        </fieldset>
    </form>
</div>