<?php 

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class Jialivs_Shortcodes {

    public function __construct() {
        add_shortcode('jialivs_shortcode', [$this, 'jialivs_shortcode_function']);
    }

    public function jialivs_shortcode_function() {
        
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
                        <!-- Single Package -->
                        <div class="col-lg-4 col-md-4">
                            <div class="packages_wrapping bg-white">
                                <div class="packages_headers">
                                    <i class="lni-layers"></i>
                                    <h4 class="packages_pr_title">پکیج پایه</h4>
                                    <span class="packages_price-subtitle">با پکیج پایه شروع کنید!</span>
                                </div>
                                <div class="packages_price">
                                    <h4 class="pr-value">49</h4>
                                </div>
                                <div class="packages_middlebody">
                                    <ul>
                                        <li>یکماه دسترسی به مطالب VIP</li>
                                        <li>ارتباط مستقیم با واتساپ استاد</li>
                                        <li>الویت نمایش و بازدید بالا</li>
                                    </ul>
                                </div>
                                <div class="packages_bottombody">
                                    <a href="#" class="btn-pricing">انتخاب</a>
                                </div>
                                
                            </div>
                        </div>
                        
                        <!-- Single Package -->
                        <div class="col-lg-4 col-md-4">
                            <div class="packages_wrapping recommended">
                                
                                <div class="packages_headers">
                                    <i class="lni-diamond"></i>
                                    <h4 class="packages_pr_title">پکیج نقره ای</h4>
                                    <span class="packages_price-subtitle">با پکیج نقره ای شروع کنید!</span>
                                </div>
                                <div class="packages_price">
                                    <h4 class="pr-value">149</h4>
                                </div>
                                <div class="packages_middlebody">
                                    <ul>
                                        <li>دو ماه دسترسی به مطالب VIP</li>
                                        <li>ارتباط مستقیم با واتساپ استاد</li>
                                        <li>الویت نمایش و بازدید بالا</li>
                                    </ul>
                                </div>
                                <div class="packages_bottombody">
                                    <a href="#" class="btn-pricing">انتخاب</a>
                                </div>
                                
                            </div>
                        </div>
                        
                        <!-- Single Package -->
                        <div class="col-lg-4 col-md-4">
                            <div class="packages_wrapping bg-white">
                                
                                <div class="packages_headers">
                                    <i class="lni-invention"></i>
                                    <h4 class="packages_pr_title">پکیج طلایی</h4>
                                    <span class="packages_price-subtitle">با پکیج طلایی شروع کنید!</span>
                                </div>
                                <div class="packages_price">
                                    <h4 class="pr-value">199</h4>
                                </div>
                                <div class="packages_middlebody">
                                    <ul>
                                        <li>سه ماه دسترسی به مطالب VIP</li>
                                        <li>ارتباط مستقیم با واتساپ استاد</li>
                                        <li>بازدید خیلی بالا</li>
                                    </ul>
                                </div>
                                <div class="packages_bottombody">
                                    <a href="#" class="btn-pricing">انتخاب</a>
                                </div>
                                
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
                        
            </section>
            <!-- ============================ vip End ================================== -->
	
        <?php
        return ob_get_clean();
    }
}