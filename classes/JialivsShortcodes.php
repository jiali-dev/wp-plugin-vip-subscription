<?php 

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class JialivsShortcodes {

    public function __construct() {
        add_shortcode('jialivs_plans_shortcode', [$this, 'jialivsPlansShortcode']);
        add_shortcode('jialivs_plans_checkout_shortcode', [$this, 'jialivsPlansCheckoutShortcode']);
        add_shortcode('jialivs_plans_gateway_shortcode', [$this, 'jialivsPlansGatewayShortcode']);
        add_shortcode('jialivs_payment_result_shortcode', [$this, 'jialivsPaymentResultShortcode']);
    }

    public function jialivsPlansShortcode() {
        
        ob_start();
        ?>
            <!-- ============================ vip Start ================================== -->
            <section class="bg-light">
                <div class="container">		
                    <!-- ============================ Page Title Start================================== -->
                    <div class="page-title mb-5">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <h1 class="breadcrumb-title"><?php echo __('VIP subscription', 'jialivs') ?></h1>
                                </div>
                            </div>
                    </div>
                    <!-- ============================ Page Title End ================================== -->	
                    <div class="row">

                        <?php 
                            $plan = new JialivsPlan(); 
                            $vip_plans = $plan->find();
                        ?>
                        <?php if( !is_wp_error($vip_plans) && !empty($vip_plans) ): ?>
                            <?php foreach( $vip_plans as $vip_plan ): ?>
                               
                                <!-- Single Package -->
                                <div class="col-lg-4 col-md-4">
                                    <div class="packages_wrapping <?php echo $vip_plan->recommended ? 'recommended' :'bg-white' ?>">
                                        <div class="packages_headers">
                                            <i class="<?php echo $plan->getPlanIcon($vip_plan->type) ?>"></i>
                                            <h4 class="packages_pr_title"><?php echo $plan->getPlanTitle($vip_plan->type) ?></h4>
                                            <span class="packages_price-subtitle"><?php echo __('Start with', 'jialivs') ?> <?php echo $plan->getPlanTitle($vip_plan->type) ?> </span>
                                        </div>
                                        <div class="packages_price">
                                            <h4 class="pr-value"><?php echo $vip_plan->price/1000 ?></h4>
                                        </div>
                                        <div class="packages_middlebody">
                                            <?php 
                                                $vip_plan_benefits = explode( '|', $vip_plan->benefits);
                                            ?>
                                            <ul>
                                                <?php foreach( $vip_plan_benefits as $benefit ): ?>
                                                    <li><?php echo $benefit ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                        <div class="packages_bottombody">
                                            <form action="<?php echo site_url(sanitize_text_field(get_option('_vip_settings')['gateway_slug'])) ?>" method="post" >
                                                <input type="hidden" name="plan_id" value="<?php echo $vip_plan->id ?>">
                                                <?php wp_nonce_field( 'vip-plan-nonce', 'vip-plan-nonce' ) ?>
                                                <input class="btn-pricing" type="submit" value="<?php echo __('choose ...', 'jialivs') ?>">
                                            </form>
                                        </div>
                                        
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-danger"><?php echo __('There is no plan!', 'jialivs') ?></div>
                        <?php endif; ?>
                        
                    </div>
                    
                </div>
                        
            </section>
            <!-- ============================ vip End ================================== -->
	
        <?php
        return ob_get_clean();
    }

    // Plans gateway
    public function jialivsPlansGatewayShortcode() {
        ob_start();

        if ( empty($_POST['vip-plan-nonce']) || !isset($_POST['vip-plan-nonce']) || !wp_verify_nonce( $_POST['vip-plan-nonce'], 'vip-plan-nonce' ) )
            wp_redirect(home_url( ));
            
        if( !is_user_logged_in(  ) )
            wp_redirect(home_url( ));

        if( !isset($_POST['plan_id']) || empty($_POST['plan_id']))
            wp_redirect(home_url( ));

        $plan_id = intval($_POST['plan_id']);
        $plan = new JialivsPlan(); 
        $vip_plan = $plan->findByID($plan_id);
        
        $current_user_info = wp_get_current_user();

        JialivsSession::set( 'user_plan_data', [
            'sesseion_id' => session_id(),
            'plan_type' => $vip_plan->type,
            'user_id' => $current_user_info->ID,
            'first_name' => $current_user_info->first_name,
            'last_name' => $current_user_info->last_name,
            'email' => $current_user_info->user_email,
            'price' => $vip_plan->price,
            'order_number' => JialivsHelper::orderNumber()
        ]);
        if( JialivsSession::has( 'user_plan_data' ) )
        {
            wp_redirect( home_url( sanitize_text_field(get_option('_vip_settings')['checkout_slug']) ) );
        } else 
        {
            wp_redirect( home_url() );
        }
        return ob_get_clean();
    }

    // Plans Checkout
    public function jialivsPlansCheckoutShortcode() {
        // Handle form submission BEFORE any output
        if ( isset($_POST['pay']) ) {
            $transaction = new JialivsTransaction();
            $result = $transaction->save(JialivsSession::get('user_plan_data'));
            if( $result )
            {
                JialivsPayment::setter(JialivsSession::get('user_plan_data'));
                JialivsPayment::request();

                // Redirect to payment gateway
                // wp_redirect( 'https://sandbox.zarinpal.com/pg/StartPay/4c2f3b5d-0a1e-4b7c-8f6d-9a0e1f3b5d0e' );
                exit;
            } else {
                echo '<div class="alert alert-danger">'.__('An error was occured', 'jialivs').'</div>';
            }

        }

        ob_start();

        $user_plan_data = JialivsSession::get('user_plan_data');

        ?>
            <div class="order-checkout">
                <div class="col-lg-4 col-md-4">
                    <div class="packages_wrapping bg-white">
                        <div class="packages_headers">
                            <i class="lni-paypal"></i>
                            <h4 class="packages_pr_title"><?php echo JialivsPlan::getPlanTitle($user_plan_data['plan_type']) ?></h4>
                            <div class="packages_date">
                                <span><?php echo __('Date', 'jialivs') ?></span>
                                <span><?php echo jdate("d-m-Y") ?></span>
                            </div>
                            <div class="packages_number">
                                <span><?php echo __('Order number', 'jialivs') ?></span>
                                <span><?php echo $user_plan_data['order_number'] ?></span>
                            </div>
                        </div>
                        <div class="packages_price">
                            <h4 class="pr-value"><?php echo $user_plan_data['price'] ?></h4>
                        </div>
                        <div class="packages_bottombody">
                            <form action="<?php //echo htmlspecialchars( site_url(get_permalink( )) ) ?>" method="post" >
                                <input class="btn-pricing" name="pay" type="submit" value="<?php echo __('Pay!', 'jialivs') ?>">
                            </form>
                        </div>
                        
                    </div>
                </div>
            </div>
        <?php

        return ob_get_clean();

    }

    // Payment Result
    public function jialivsPaymentResultShortcode() {
        ob_start();

        $user_plan_data = JialivsSession::get('user_plan_data');
        JialivsPayment::setter(JialivsSession::get('user_plan_data'));
        JialivsPayment::paymentResult();
        ?>
        <?php if( JialivsPayment::getRefID() ): ?>
            <div class="order-checkout">
                <div class="col-lg-4 col-md-4">
                    <div class="packages_wrapping bg-white">
                        <div class="packages_headers">
                            <i class="lni-paypal"></i>
                            <h4 class="packages_pr_title"><?php echo __('Payment reciept for:', 'jialivs') ?> <?php echo JialivsPlan::getPlanTitle($user_plan_data['plan_type']) ?></h4>
                            <div class="packages_ref">
                                <span><?php echo __('Transaction number', 'jialivs') ?></span>
                                <span><?php echo JialivsPayment::getRefID() ?></span>
                            </div>
                        </div>
                        <div class="packages_price">
                            <h4 class="pr-value"><?php echo $user_plan_data['price'] ?></h4>
                        </div>
                        <div class="packages_bottombody">
                            <a href="<?php echo home_url(  ) ?>"><?php echo __('Return to home', 'jialivs') ?></a>
                        </div>
                        
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger"><?php echo __('There is no transaction!', 'jialivs') ?></div>
        <?php endif; ?> 
        <?php
        return ob_get_clean();
    }
}