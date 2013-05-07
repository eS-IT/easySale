<?php
/**
 * ipn-Class
 * @author      pfroch <info@easySolutionsIT.de>
 * @link        http://easySolutionsIT.de
 * @copyright   e@sy Solutions IT 2013
 * @license     LGPL
 * @package     eS_easysale
 * @filesource  ipn.php
 * @version     1.0.0
 * @since       11.02.13 - 09:33
 */


/**
 * Namespace
 */
#namespace es_easysale;


// Post-Daten umspeichern, wegen dem Contao-Request-Token
$arrPost = $_POST;
unset($_POST);


// Load contao
define('TL_MODE', 'FE');
$contaoSystem = str_replace('/modules/es_easysale/public', '', dirname(__FILE__));
require($contaoSystem  . '/initialize.php');



/**
 * Class es_paypal_inc
 *
 * @copyright  e@sy Solutions IT 2013
 * @author     Patrick Froch <patrick.froch@easySolutionsIT.de>
 * @package    eS_easysale
 */
class ipn extends \System{


    /**
     * Instanz der PayPal-Klasse
     * @var es_paypal_inc|null
     */
    private $paypal = null;


    /**
     * Erstellt eine Instanz der Klasse.
     */
    public function __construct(){
        $this->import('es_paypal_inc');
        $this->import('es_easysale');

        if($GLOBALS['EASY_SALE']['PP']['use_sandbox'] || $GLOBALS['TL_CONFIG']['usesandbox']){
            $this->es_paypal_inc->useSandbox();
        }
    }


    public function testIpn(){
        global $arrPost;
        $arrData = $this->es_paypal_inc->validate_ipn($arrPost);
        $this->es_easysale->mailCustomer($arrData);
    }
}


/**
 * Instanz der Klasse erzeugen und IPN testen.
 */
$ipn = new ipn();
$ipn->testIpn();