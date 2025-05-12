<?php 

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class JialivsPayment {

    protected static $merchant_id;
    protected static $amount;
    protected static $callback_url;
    protected static $description;
    protected static $metadata;
    protected static $refID;
    protected static $errCode;
    protected static $errMessage;


    public static function request() {
        $data = array( "merchant_id" => self::$merchant_id,
            "amount" => self::$amount,
            "callback_url" => site_url( self::$callback_url ),
            "description" => self::$description,
            "metadata" => [ "email" => self::$metadata['email'],"mobile"=>self::$metadata['mobile']],
        );
        $jsonData = json_encode($data);

        $ch = curl_init('https://sandbox.zarinpal.com/pg/v4/payment/request.json');
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ));

        $result = curl_exec($ch);
        $err = curl_error($ch);
        $result = json_decode($result, true, JSON_PRETTY_PRINT);
        curl_close($ch);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            if (empty($result['errors'])) {
                if ($result['data']['code'] == 100) {
                    header('Location: https://sandbox.zarinpal.com/pg/StartPay/' . $result['data']["authority"]);
                }
            } else {
                self::$errCode = $result['errors']['code'];
                self::$errMessage = $result['errors']['message'];
            }
        }

    }

    public static function paymentResult() {
        $Authority = $_GET['Authority'];
        $data = array("merchant_id" => self::$merchant_id, "authority" => $Authority, "amount" => self::$amount);
        $jsonData = json_encode($data);
        $ch = curl_init('https://sandbox.zarinpal.com/pg/v4/payment/verify.json');
        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v4');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ));

        $result = curl_exec($ch);
        $err = curl_error($ch);
        $result = json_decode($result, true, JSON_PRETTY_PRINT);
        curl_close($ch);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            if ( isset($result['data']['code']) && $result['data']['code'] == 100 ) {
                self::$refID = $result['data']['ref_id'];
                $transaction = new JialivsTransaction();
                $transaction->update( $result['data']['ref_id'], JialivsSession::get('user_plan_data')['order_number'] );
                $user_vip_plan = new JialivsUserVipPlan();
                $user_vip_plan->updateUserVipPlan( JialivsSession::get('user_plan_data')['user_id'], JialivsSession::get('user_plan_data')['plan_type'] );
                JialivsSession::unset('user_plan_data');
            } else {
                self::$errCode = $result['errors']['code'];
                self::$errMessage = $result['errors']['message'];
            }
        }
    }

    public static function setter($data) {
        self::$amount = isset( $data['price'] ) ? $data['price'] * 10 : '';
        self::$merchant_id = sanitize_text_field(get_option('_merchant_id'));
        self::$description = isset( $data['plan_type'] ) ? JialivsPlan::getPlanTitle($data['plan_type']) : '';
        self::$metadata = [
            'email' => isset( $data['email'] ) ? $data['email'] : '',
            'mobile' => isset( $data['mobile'] ) ? $data['mobile'] : '00000000000'
        ];
        // Set callback_url here safely
        $vip_settings = get_option('_vip_settings');
        self::$callback_url = isset($vip_settings['payment_result_slug']) ? $vip_settings['payment_result_slug'] : 'vip-plans-checkout';
    }

    public static function getRefID()
    {
        return self::$refID;
    }

    public static function getErrCode()
    {
        return self::$errCode;
    }

    public static function getErrMessage()
    {
        return self::$errMessage;
    }
}