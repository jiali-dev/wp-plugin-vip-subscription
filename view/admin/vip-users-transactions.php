<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

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
                <th><?php echo __( 'Fullname', 'jialivs' ) ?></th>
                <th><?php echo __( 'Email', 'jialivs' ) ?></th>
                <th><?php echo __( 'Payment amount', 'jialivs' ) ?></th>
                <th><?php echo __( 'Order number', 'jialivs' ) ?></th>
                <th><?php echo __( 'Transaction number', 'jialivs' ) ?></th>
                <th><?php echo __( 'Payment status', 'jialivs' ) ?></th>
                <th><?php echo __( 'Payment date', 'jialivs' ) ?></th>
                <th><?php echo __( 'Plan type', 'jialivs' ) ?></th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $transactions = new JialivsTransaction();
                $results = $transactions->find();
            ?>
            <?php if( $results ): ?>
                <?php foreach( $results as $item ): 
                    $user_info = get_userdata( $item->user_id );
                ?>
                    <tr>
                        <td><?php echo $user_info->ID ?></td>
                        <td><?php echo $user_info->display_name ?></td>
                        <td><?php echo $user_info->user_email ?></td>
                        <td><?php echo $item->price ?></td>
                        <td><?php echo $item->order_number ?></td>
                        <td><?php echo $item->ref_id ?></td>
                        <td><?php echo $item->status ? '<span class="uk-alert-success" >موفق</span>' : '<span class="uk-alert-danger" >'. __( 'unsuccessful', 'jialivs' ) .'</span>' ?></td>
                        <td><?php echo jdate($item->created_at) ?></td>
                        <td><?php echo JialivsPlan::getPlanTitle($item->plan_type) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
