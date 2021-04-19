<?php

/**
 * @file
 * Perfect Money menu items.
 *
 */

function uc_pm_complete($cart_id = 0) {
  if (!isset($_POST['PAYMENT_BATCH_NUM']) || !isset($_POST['PAYMENT_ID'])) {
    print t('An error has occurred during payment.  Please contact us to ensure your order has been submitted.');
    exit();
  }
  $txid = check_plain($_POST['PAYMENT_BATCH_NUM']);
  for ($i = 1; $i <= 10; $i++) {
    if (db_query("SELECT COUNT(txid) FROM {uc_payment_pm} WHERE txid = :s", array(':s' => $txid))->fetchField()) {break;}
	sleep (3);
  }
  watchdog('Perferct Money', 'Buyer returned back with payment completed status');
  $order = uc_order_load($_POST['PAYMENT_ID']);
  if ($order === FALSE) {
    print t('An error has occurred during payment.  Please contact us to ensure your order has been submitted.');
    exit();
  }
  if (uc_order_status_data($order->order_status, 'state') == 'in_checkout') {
    drupal_set_message(t('Your order will be processed as soon as your payment clears at Perfect Money.'));
    uc_order_comment_save($order->order_id, 0, t('Payment is pending approval at Perfect Money.'), 'admin');
  }
  // Empty that cart...
  if (!isset($_SESSION['cart_order'])){
	drupal_goto('cart');
  }
  $_SESSION['uc_checkout'][$_SESSION['cart_order']]['do_complete'] = TRUE;
  drupal_goto('cart/checkout/complete');
}

function uc_pm_failed($cart_id = 0) {
  watchdog('Perferct Money', 'Peyment cancelled by Perfect Money or buyer');
  if (!isset($_POST['PAYMENT_BATCH_NUM']) || !isset($_POST['PAYMENT_ID'])) {
    print t('An error has occurred during payment.  Please contact us to ensure your order has been submitted.');
    exit();
  }
  $order = uc_order_load($_POST['PAYMENT_ID']);

  if ($order === FALSE) {
    print t('An error has occurred during payment.  Please contact us to ensure your order has been submitted.');
    exit();
  }

  if (uc_order_status_data($order->order_status, 'state') == 'in_checkout') {
    drupal_set_message(t('Your payment was cancelled. Please try again or change the payment method.'));
    uc_order_comment_save($order->order_id, 0, t('Payment was cancelled at Perfect Money.'), 'admin');
  }
  
  drupal_goto('cart/checkout');
  return $output;
}


function uc_pm_finalize() {
  $passphrase = strtoupper(md5(variable_get('uc_pm_passphrase', '')));
  $string=
      $_POST['PAYMENT_ID'].':'.$_POST['PAYEE_ACCOUNT'].':'.
      $_POST['PAYMENT_AMOUNT'].':'.$_POST['PAYMENT_UNITS'].':'.
      $_POST['PAYMENT_BATCH_NUM'].':'.
      $_POST['PAYER_ACCOUNT'].':'.$passphrase.':'.
      $_POST['TIMESTAMPGMT'];
  $hash=strtoupper(md5($string));
  watchdog('Perfect Money', 'Receiving a payment confirmation for order #!order_id.', array('!order_id' => check_plain($_POST['PAYMENT_ID'])));
  $err = FALSE;
  $description = '';
  $order = uc_order_load((int)$_POST['PAYMENT_ID']);
  if ($order === FALSE) {
    print t('An error has occurred during payment.  Please contact us to ensure your order has submitted.');
    $err = TRUE;
	$description = 'Declined. No such order ID in the base.';
  }
  $order_state = uc_order_status_data($order->order_status, 'state');
  if ($order_state != 'in_checkout' && $order_state != 'post_checkout'){
    $err = TRUE;
	$description = 'Declined. The order is paid, complete or canceled.';
  }
  if (!$err){
    if($hash==$_POST['V2_HASH']){ // proccessing payment if only hash is valid
      if ($_POST['PAYMENT_AMOUNT'] == $order->order_total && $_POST['PAYEE_ACCOUNT'] == variable_get('uc_pm_payee_acc', '') && $_POST['PAYMENT_UNITS'] == variable_get('uc_pm_units', '')){
        // payment approved
        $order->billing_phone = $_POST['PHONE'];
        if (strtolower($_POST['EMAIL']) !== strtolower($order->primary_email)) {
          uc_order_comment_save($order->order_id, 0, t('Customer used a different e-mail address during payment: !email', array('!email' => check_plain($_POST['email']))), 'admin');
        }
        uc_order_save($order);
		watchdog('Perfect Money', 'Payment approved for order #!order_id.', array('!order_id' => check_plain($_POST['PAYMENT_ID'])));
        $comment = t('Paid. Perfect Money transaction Batch Number #!order.', array('!order' => check_plain($_POST['PAYMENT_BATCH_NUM'])));
        uc_payment_enter($order->order_id, 'perfectmoney', $_POST['PAYMENT_AMOUNT'], 0, NULL, $comment);
        uc_cart_complete_sale($order);
		drupal_set_message(t('Payment for your order has been recieved.'));
		$description = 'Approoved.';
      } else {
        if(!$err){
	      // save invalid payments for secuirty purposes
          uc_order_comment_save($order->order_id, 0, t('Attempted unverified Perfect Money transaction for this order.'), 'admin');
          watchdog('Perfect Money', 'Invalid payment transaction for order !order_id.', array('!order_id' => check_plain($_POST['PAYMENT_ID'])));
          drupal_set_header('HTTP/1.1 500 Internal Server Error');
          drupal_set_header('Status: 500 Internal Server Error');
          print t('An error has occurred during payment.  Please contact us to ensure your order has been submitted.');
          $err=TRUE;
		  $description = 'Declined. Invalid payment transaction. PAYMENT_UNITS = ' . $_POST['PAYMENT_UNITS'];
	    }
      }
    } else {
	  // save invalid payments for secuirty purposes
      uc_order_comment_save($order->order_id, 0, t('Attempted unverified Perfect Money completion for this order.'), 'admin');
      watchdog('Perfect Money', 'Invalid payment confirmation for order !order_id. Recieved hash: !rhash, our hash: !ohash, string: !string', array('!order_id' => check_plain($_POST['PAYMENT_ID']), '!rhash' => check_plain($_POST['V2_HASH']), '!ohash' => $hash, '!string' => $string));
      drupal_set_header('HTTP/1.1 500 Internal Server Error');
      drupal_set_header('Status: 500 Internal Server Error');
      print t('An error has occurred during payment.  Please contact us to ensure your order has submitted.');
      $err=TRUE;;
	  $description = 'Declined. Invalid payment signature.';
    }
  }
  $txid = check_plain($_POST['PAYMENT_BATCH_NUM']);
  $order_id = check_plain($_POST['PAYMENT_ID']);
  $amount = check_plain($_POST['PAYMENT_AMOUNT']);
  $payee = check_plain($_POST['PAYEE_ACCOUNT']);
  $payer = check_plain($_POST['PAYER_ACCOUNT']);
  if (db_query("SELECT COUNT(txid) FROM {uc_payment_pm} WHERE txid = :s", array(':s' => $txid))->fetchField()) {
    db_query("UPDATE {uc_payment_pm} SET description = :s WHERE txid = :d",
	  array(
	    ':s' => $description . ' update',
	    ':d' => $txid
	  )
	);
  }
  else {
    db_insert('uc_payment_pm')
      ->fields(array(
        'txid' => $txid,
        'order_id' => $order_id,
        'amount' => $amount,
        'payee' => $payee,
        'payer' => $payer,
        'description' => $description
      ))
      ->execute();
  }
}

