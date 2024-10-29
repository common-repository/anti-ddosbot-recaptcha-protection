<?php

/*
 * Plugin Name: Anti DDOS/BOT reCAPTCHA Protection
 * Plugin URI: http://wordpress.org/plugins/anti-ddosbot-recaptcha-protection
 * Description: Protect Your Website with Our Anti DDOS / BOT Protection, We not resolve the DDOS Problem, but we try to limit bad traffic , With our plugin, you'll be able to secure your website against potential DDOS and BOT attacks. Visitors will be required to complete a one-time reCaptcha verification That helps to minimize the amount of data transferred between the attacker and your website. Once the reCaptcha check has been passed, visitors will be granted full access to your website content.
 * Version: 1.0.0
 * Author: Webtouch.gr
 * Author URI : https://www.webtouch.gr
 * Requires at least: 5.0
 * License: LGPL v2.1
 * License URI: http://www.gnu.org/licenses/lgpl-2.1.html
 * Text Domain: anti-ddosbot-recaptcha-protection
 */

 error_reporting(E_ALL ^ E_WARNING);

 function anti_ddos_bot_recaptcha_admin_menu() {
    add_menu_page(
      'Anti DDOS/BOT reCAPTCHA Protection',
      'Anti DDOS/BOT',
      'manage_options',
      'anti-ddos-bot-recaptcha-admin',
      'anti_ddos_bot_admin_menu_page',
      'dashicons-cloud',
      99
    );
  }
  add_action('admin_menu', 'anti_ddos_bot_recaptcha_admin_menu');
  
  function adbr_checkRecaptchaKey(){
    $secretkey = get_option('secretkey');
    $response = sanitize_text_field($_POST["g-recaptcha-response"]);
    
    $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $secretkey . "&response=" . $response;
    
    $response = wp_remote_get($url);
    $responseBody = wp_remote_retrieve_body($response);
    $responseBody = json_decode($responseBody, true);

    if ($responseBody["success"] === true) {
        return true;
    } else {
        return false;
    }
  }

  function adbr_updateOption($optionPostName){
    if(isset($_POST[$optionPostName])){
      $value  = sanitize_text_field($_POST[$optionPostName] );
      update_option($optionPostName, $value);
    }
  }

  function adbr_updateUrlOption($optionPostName){
    if(isset($_POST[$optionPostName])){
      $value  = sanitize_url( $_POST[$optionPostName] );
      if(adbr_validate_input_data($value)){
        $value = str_replace("https://","",$value);
        $value = str_replace("http://","",$value);
        update_option($optionPostName, $value);
      }
    }
  }  

  function adbr_validate_input_data( $input_data ) {
    if ( $input_data == "" || preg_match( '/^[a-zA-Z0-9\-:,.!?=%\/\ ]+$/', $input_data ) ) {
        return true;
    } else {
        return false;
    }
  }

  function anti_ddos_bot_admin_menu_page() {
    if(isset($_POST['submit']) && $_POST['submit'] == "Save Changes") {
        adbr_updateOption('sitekey');
        adbr_updateOption('secretkey');
        adbr_updateOption('ddosrecaptchamsg1');
        adbr_updateOption('ddosrecaptchamsg2');
        adbr_updateOption('ddosrecaptchamsg3');
        adbr_updateOption('ddosrecaptchabutton');

        adbr_updateUrlOption('ddosrecaptchalockurl1');
        adbr_updateUrlOption('ddosrecaptchalockurl2');
        adbr_updateUrlOption('ddosrecaptchalockurl3');
    }

    if(isset($_POST['submit']) && $_POST['submit'] == "Enable Protection") {
      if(isset($_POST['ddosrecaptcha'])){
          if(adbr_checkRecaptchaKey())
            update_option('ddosrecaptcha', "1");
      }
      else
        update_option('ddosrecaptcha', "0");
    }    

    $sitekey = get_option('sitekey');
    $secretkey = get_option('secretkey');
    $ddosrecaptcha = get_option('ddosrecaptcha');
    $ddosrecaptchamsg1 = get_option('ddosrecaptchamsg1');
    $ddosrecaptchamsg2 = get_option('ddosrecaptchamsg2');
    $ddosrecaptchamsg3 = get_option('ddosrecaptchamsg3');
    $ddosrecaptchabutton = get_option('ddosrecaptchabutton');

    if( $ddosrecaptchamsg1 == "")
        $ddosrecaptchamsg1 = "We handle high volumes of traffic";
    if( $ddosrecaptchamsg2 == "")
        $ddosrecaptchamsg2 = "Website Access allowed only for humans";
    if( $ddosrecaptchamsg3 == "")
        $ddosrecaptchamsg3 = "Please Complete this Captcha";                
    if( $ddosrecaptchabutton == "")
        $ddosrecaptchabutton = "Allow my Access";                


    $ddosrecaptchalockurl1 = get_option('ddosrecaptchalockurl1');
    $ddosrecaptchalockurl2 = get_option('ddosrecaptchalockurl2');
    $ddosrecaptchalockurl3 = get_option('ddosrecaptchalockurl3');

    echo '<h1>Anti DDOS/BOT reCAPTCHA Protection</h1>';

    //echo '<table><tr>';
    echo '<div style="float:left;">';
    echo '<style>input[type="text"], input[type="password"] { width: 500px;}</style>';
    echo '<div class="wrap">';
    echo '    <form method="post">';
    echo '<h2>reCAPTCHA V2 Configuration : </h2>';
    echo '        <table class="form-table">';
    echo '            <tr>';
    echo '                <th scope="row">sitekey : </th>';
    echo '                <td><input type="text" name="sitekey" value="'.esc_html($sitekey).'"></td>';
    echo '            </tr>';
    echo '            <tr>';
    echo '               <th scope="row">secretkey : </th>';
    echo '                <td><input type="text" name="secretkey" value="'.esc_html($secretkey).'"></td>';
    echo '            </tr>';
    echo '        </table>';

    echo '<h2>Customize : </h2>';
    echo '        <table class="form-table">';
    echo '            <tr>';
    echo '                <th scope="row">Message Line 1 : </th>';
    echo '                <td><input type="text" name="ddosrecaptchamsg1" value="'.esc_html($ddosrecaptchamsg1).'"></td>';
    echo '            </tr>';
    echo '            <tr>';
    echo '               <th scope="row">Message Line 2 : </th>';
    echo '                <td><input type="text" name="ddosrecaptchamsg2" value="'.esc_html($ddosrecaptchamsg2).'"></td>';
    echo '            </tr>';
    echo '            <tr>';
    echo '               <th scope="row">Message Line 3 : </th>';
    echo '                <td><input type="text" name="ddosrecaptchamsg3" value="'.esc_html($ddosrecaptchamsg3).'"></td>';
    echo '            </tr>';
    echo '            <tr>';
    echo '               <th scope="row">Submit Button Text : </th>';
    echo '                <td><input type="text" name="ddosrecaptchabutton" value="'.esc_html($ddosrecaptchabutton).'"></td>';
    echo '            </tr>';        
    echo '        </table>';

    echo '<h2>Protect only specified URLs : </h2>';
    echo 'If attackers concentrate their efforts on specified URLs, you can secure only those URLs<br>';
    echo 'You can use either the entire URL or a portion of it, <br>';
    echo 'Using the word "product" then protection enabled for all URLs containing this word.<br>';
    echo 'To protect the entire website, leave the URLs field empty.';
    echo '        <table class="form-table">';
    echo '            <tr>';
    echo '                <th scope="row">Protect URL 1 : </th>';
    echo '                <td><input type="text" name="ddosrecaptchalockurl1" value="'.esc_html($ddosrecaptchalockurl1).'"></td>';
    echo '            </tr>';
    echo '            <tr>';
    echo '               <th scope="row">Protect URL 2 : </th>';
    echo '                <td><input type="text" name="ddosrecaptchalockurl2" value="'.esc_html($ddosrecaptchalockurl2).'"></td>';
    echo '            </tr>';
    echo '            <tr>';
    echo '               <th scope="row">Protect URL 3 : </th>';
    echo '                <td><input type="text" name="ddosrecaptchalockurl3" value="'.esc_html($ddosrecaptchalockurl3).'"></td>';
    echo '            </tr>';
    echo '        </table>';
    echo 'This field allows only alphabetic characters (both upper and lowercase) and digits, as well as the following special characters: (-:,.!=% and ?).<br>';
    echo '       <p class="submit">';
    echo '           <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">';
    echo '        </p>';
    echo '   </form>';

    echo '    <form method="post">';
    echo '<h2>Enable Protection : </h2>';
    echo 'To ensure the security of your site, the protection can only be activated once you have successfully completed the reCAPTCHA challenge.<br>';
    echo '        <table class="form-table">';
    echo '            <tr>';
    echo '               <th scope="row">Check to Enable : </th>';
    $checked = "";
    if($ddosrecaptcha == "1")
        $checked = "checked";

    echo '                <td><input type="checkbox" name="ddosrecaptcha" '.esc_html($checked).'></td>';
    echo '            </tr>';       
    echo '            <tr>';
    echo '               <th scope="row">Complete to Enable : </th>';
    echo '                <td><div class="g-recaptcha" data-sitekey="'.esc_html($sitekey).'"></div></td>';
    echo '            </tr>';
    echo '        </table>';

    echo '       <p class="submit">';
    echo '           <input type="submit" name="submit" id="submit" class="button button-primary" value="Enable Protection">';
    echo '        </p>';
    echo '   </form>';
    echo '</div>';

    echo '<h2>Details : </h2>';
    echo "Enable this Plugin Only if you are under DDOS/BOT Attacks <br>";
    echo "We not resolve the DDOS Problem, but we try to limit bad traffic <br>";
    echo "With our plugin, you'll be able to secure your website against potential DDOS and BOT attacks. <br>";
    echo "Visitors will be required to complete a one-time reCAPTCHA verification <br>";
    echo "That helps to minimize the amount of data transferred between your website and the attacker. <br>";
    echo "Once the reCAPTCHA check has been passed, visitors will be granted full access to your website content. <br>";
    echo '<h2>Advance : </h2>';
    echo 'If you can\'t access on wordpress control panel but you still have access with SSH or FTP<br>';
    echo 'You can enable / disable this protection adding in wp-config.php this option<br>';
    echo 'define( \'ANTI_DDOS_ENABLED\', true );<br>';
    echo 'Above of the line "require_once ABSPATH . \'wp-settings.php\';" <br>';
    echo 'Protection ignore the checkbox inside the plugin config page<br>';
    
    echo '</div>';

    echo '<div style="float:left">';

    echo '<center>';
    echo '<h2>Preview : </h2>';
    adbr_captchaBox(true);

    echo '<br><br>';
    echo '<h2>Payment : </h2>';
    echo '<h4>This plugin is Free !!!<br></h4>';
    echo '<img src="'.plugins_url( 'donate-qrcode.png', __FILE__ ).'" alt="donate-qr-code"><br>';
    echo '<div id="donate-button-container">
        <div id="donate-button"></div>
        <script src="https://www.paypalobjects.com/donate/sdk/donate-sdk.js" charset="UTF-8"></script>
        <script>
        PayPal.Donation.Button({
        env:"production",
        hosted_button_id:"3P7EH8SDT32SW",
        image: {
        src:"https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif",
        alt:"Donate with PayPal button",
        title:"PayPal - The safer, easier way to pay online!",
        }
        }).render("#donate-button");
        </script>
        </div>';
    echo '</center>';
    echo '</div>';
  }

// Start a PHP session
session_start();

$sitekey = get_option('sitekey');
$secretkey = get_option('secretkey');
$ddosrecaptcha = get_option('ddosrecaptcha');
$ddosrecaptchalockurl1 = get_option('ddosrecaptchalockurl1');
$ddosrecaptchalockurl2 = get_option('ddosrecaptchalockurl2');
$ddosrecaptchalockurl3 = get_option('ddosrecaptchalockurl3');

// Check if the user has already completed the reCAPTCHA
if ( $sitekey != "" && $secretkey != "" && ( (defined(ANTI_DDOS_ENABLED) && ANTI_DDOS_ENABLED) || $ddosrecaptcha == "1") && !isset( $_SESSION['recaptcha_verified']) ) {

  $finalReCaptchaLimitUrl = false;
  $finalReCaptchaCheck = false;

  $fullURL = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  if ($ddosrecaptchalockurl1 != ""  || $ddosrecaptchalockurl2 != ""  || $ddosrecaptchalockurl3 != "" )
    $finalReCaptchaLimitUrl = true;
  if ($ddosrecaptchalockurl1 != "" && strpos($fullURL, $ddosrecaptchalockurl1) !== false)
    $finalReCaptchaCheck = true;
  if ($ddosrecaptchalockurl2 != "" && strpos($fullURL, $ddosrecaptchalockurl2) !== false)
    $finalReCaptchaCheck = true;
  if ($ddosrecaptchalockurl3 != "" && strpos($fullURL, $ddosrecaptchalockurl3) !== false)
    $finalReCaptchaCheck = true;

  if(($finalReCaptchaLimitUrl && $finalReCaptchaCheck) || !$finalReCaptchaLimitUrl){
      // Check if the form was submitted
      if ( isset( $_POST['g-recaptcha-response'] ) ) {
        $recaptchaResponce = sanitize_text_field($_POST['g-recaptcha-response']);
        // Verify the reCAPTCHA response
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret='.esc_html($secretkey).'&response=' . esc_html($recaptchaResponce) ;
        $response = wp_remote_get($url);
        $responseBody = wp_remote_retrieve_body($response);
        $responseBody = json_decode($responseBody, true);

        // If the reCAPTCHA was successful, set the session variable
        if ($responseBody["success"] === true) {
          $_SESSION['recaptcha_verified'] = true;
        } else {
          // If the reCAPTCHA was unsuccessful, show an error message
          echo 'The reCAPTCHA was unsuccessful. Please try again.';
        }
      }
      if(!isset( $_SESSION['recaptcha_verified'] )){
        adbr_captchaBox();
        exit();
      }
  }
} else {
  // If the user has already completed the reCAPTCHA, show the content
}

function adbr_captchaBox($preview = false){
    $sitekey = get_option('sitekey');
    $ddosrecaptchamsg1 = get_option('ddosrecaptchamsg1');
    $ddosrecaptchamsg2 = get_option('ddosrecaptchamsg2');
    $ddosrecaptchamsg3 = get_option('ddosrecaptchamsg3');
    $ddosrecaptchabutton = get_option('ddosrecaptchabutton');    
    if(!$preview){
        echo "<style>
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
        }";
        echo ".center-div {
            background-color: darkgray;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }</style>";

        echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";

    }
    
    echo "<style>
    input[type='submit'],input[type='button'] {
        width: 305px;
        padding: 15px;
    }
    legend {
        text-align: center;
        font-size:150%;
        line-height:30px;
    }
    </style>";

    echo '<div class="overlay">';
    echo '<div class="center-div">';
    echo '<script src="https://www.google.com/recaptcha/api.js"></script>';
    echo '<form id="recaptcha-forms" action="" method="post">';
    echo '<legend>'.esc_html($ddosrecaptchamsg1).'</legend>';
    echo '<legend>'.esc_html($ddosrecaptchamsg2).'</legend>';
    echo '<legend>'.esc_html($ddosrecaptchamsg3).'<br><br></legend>';
    echo '<center>';
    echo '<div class="g-recaptcha" data-sitekey="'.esc_html($sitekey).'"></div>';
    if(!$preview)
        echo '<input type="submit" value="'.esc_html($ddosrecaptchabutton).'">';
    else
        echo '<input type="button" value="'.esc_html($ddosrecaptchabutton).'">';
    echo '</center>';
    echo '</form>';
    echo '</div>';
    echo '</div>';
}
