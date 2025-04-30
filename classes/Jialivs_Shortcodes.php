<?php 

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class Jialivs_Shortcodes {

    public function __construct() {
        add_shortcode('jialivs_plans_shortcode', [$this, 'shortcode_function']);
    }

    public function shortcode_function() {
        
        ob_start();
        ?>
            <!-- ============================ vip Start ================================== -->
            <section class="bg-light">
                <div class="container">		
                    <!-- ============================ Page Title Start================================== -->
                    <div class="page-title mb-5">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                        <h1 class="breadcrumb-title">اشتراک VIP</h1>
                                </div>
                            </div>
                    </div>
                    <!-- ============================ Page Title End ================================== -->	
                    <div class="row">

                        <?php 
                            $plan = new Jialivs_Plan(); 
                            $vip_plans = $plan->find();
                        ?>
                        <?php if( !is_wp_error($vip_plans) && !empty($vip_plans) ): ?>
                            <?php foreach( $vip_plans as $vip_plan ): ?>
                                <?php 
                                    $vip_plan_title = '';
                                    $vip_plan_icon = 'lni-layers';
                                    switch($vip_plan->type) {
                                        case 1:
                                            $vip_plan_title = 'پکیج طلایی';
                                            $vip_plan_icon = 'lni-layers';
                                            break;
                                        case 2:
                                            $vip_plan_title = 'پکیج نقره ای';
                                            $vip_plan_icon = 'lni-diamond';
                                            break;
                                        case 3:
                                            $vip_plan_title = 'پکیج برنزی';
                                            $vip_plan_icon = 'lni-invention';
                                            break;
                                    }
                                ?>
                                <!-- Single Package -->
                                <div class="col-lg-4 col-md-4">
                                    <div class="packages_wrapping <?php echo $vip_plan->recommended ? 'recommended' :'bg-white' ?>">
                                        <div class="packages_headers">
                                            <i class="<?php echo $vip_plan_icon ?>"></i>
                                            <h4 class="packages_pr_title"><?php echo $vip_plan_title ?></h4>
                                            <span class="packages_price-subtitle">با <?php echo $vip_plan_title ?> شروع کنید!</span>
                                        </div>
                                        <div class="packages_price">
                                            <h4 class="pr-value"><?php echo rtrim($vip_plan->price, '0') ?></h4>
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
                                            <a href="#" class="btn-pricing">انتخاب</a>
                                        </div>
                                        
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-danger">تا کنون پلنی ثبت نشده است!</div>
                        <?php endif; ?>
                        
                    </div>
                    
                </div>
                        
            </section>
            <!-- ============================ vip End ================================== -->
	
        <?php
        return ob_get_clean();
    }
}