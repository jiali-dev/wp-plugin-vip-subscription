<?php 

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class Jialivs_Payment {

    protected static $merchant_id;
    protected static $amount;
    protected static $callback_url = 'payment-result';
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

    public static function payment_result() {
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
            if ($result['data']['code'] == 100) {
                self::$refID = $result['data']['ref_id'];
                $transaction = new Jialivs_Transaction();
                $transaction->update( $result['data']['ref_id'], Jialivs_Session::get('user_plan_data')['order_number'] );
                $user_vip_plan = new Jialivs_User_Vip_Plan();
                $user_vip_plan->update_user_vip_plan( Jialivs_Session::get('user_plan_data')['plan_type'], Jialivs_Session::get('user_plan_data')['user_id'] );
                Jialivs_Session::unset('user_plan_data');
            } else {
                self::$errCode = $result['errors']['code'];
                self::$errMessage = $result['errors']['message'];
            }
        }
    }

    public static function setter($data) {
        self::$amount = $data['price'] * 10;
        self::$merchant_id = get_option('_merchant_id');
        self::$description = Jialivs_Plan::get_plan_title($data['plan_type']);
        self::$metadata = [
            'email' => $data['email'],
            'mobile' => '09123456789'
        ];
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