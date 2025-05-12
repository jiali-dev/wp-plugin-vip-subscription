<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class JialivsFlashMessage {

    const ERROR = 0;
    const SUCCESS = 1;

    public static function addMessage( $message = null, $type ) {
        if( isset($_SESSION['flash_message']) ) {
            $_SESSION['flash_message'] = [
                'message' => $message,
                'type' => $type
            ];
        } else {
            $_SESSION['flash_message'] = [];
            $_SESSION['flash_message'] = [
                'message' => $message,
                'type' => $type
            ];
        }
    }
    
    public static function showMessage(  ) {
        if( isset($_SESSION['flash_message']) ): ?>
            <div class="<?php echo $_SESSION['flash_message']['type'] == self::SUCCESS ? 'uk-alert-success' : 'uk-alert-danger' ?>" uk-alert>
                <p><?php echo $_SESSION['flash_message']['message'] ?></p>
            </div>
        <? 
            unset($_SESSION['flash_message']);
        endif;
    }
}