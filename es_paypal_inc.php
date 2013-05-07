<?php
/**
 * PayPal-Class
 * @package   eS_easysale
 * @author    Patrick Froch <patrick.froch@easySolutionsIT.de>
 * @link      http://easySolutionsIT.de
 * @license   LGPL
 * @copyright e@sy Solutions IT 2013
 */


/**
 * Namespace
 */
#namespace es_easysale;


/**
 * Class es_paypal_inc
 *
 * @copyright  e@sy Solutions IT 2013
 * @author     Patrick Froch <patrick.froch@easySolutionsIT.de>
 * @package    eS_easysale
 */
class es_paypal_inc extends \System{


    /**
     * URL der PayPal-Sandbox
     * @var string
     */
    private $sandbox = 'https://www.sandbox.paypal.com/cgi-bin/webscr';


    /**
     * PayPal-Adresse
     * @var string
     */
    private $paypal = 'https://www.paypal.com/cgi-bin/webscr';


    /**
     * Adresse des IPN-Scripts
     * @var string
     */
    private $ipn = '';


    /**
     * Soll die Sandbox genutzt werden?
     * @var bool
     */
    private $useSandbox = false;


    /**
     * Print debug infos
     * @var bool
     */
    private $debug = false;


    /**
     * Zeigt das Formular mit den PayPal-Daten an und
     * schaltet die automatische Weiterleitung ab.
     * @var bool
     */
    private $showForm = false;


    /**
     * Nimmt die Formulardaten auf.
     * @var array
     */
    private $data = array();


    /**
     * Daten, die von PayPal uebermittelt wurden.
     * @var array
     */
    private $posted_data=array();


    /**
     * PayPal-Error
     * @var null
     */
    private $error = null;


    /**
     * Erstellt eine Instanz der Klasse.
     * @param bool $bolUseSendbock
     */
    public function __construct($bolUseSendbock = false){
        $this->import('es_easysale');
        $this->debug        = ($GLOBALS['EASY_SALE']['PP']['debug'] || $GLOBALS['TL_CONFIG']['easysaledebug']) ? true : false;
        $this->showForm     = $GLOBALS['EASY_SALE']['PP']['show_form'];
        $this->useSandbox   = $bolUseSendbock;
        $this->type         ='payment';
        $this->add('cmd','_xclick');
        $this->add('no_note', 1);
        $this->add('no_shipping', 1);
        $this->add('currency_code', 'EUR');
        $this->add('notify_url', $this->ipn);
    }


    /* ============ *
     * IPN-Methoden *
     * ============ */

    /**
     * Nimmt die Zahlungsvalidierung vor.
     * @param $arrPost
     * @return array|int
     */
    public function validate_ipn($arrPost){
        if(!empty($arrPost)){
            $this->es_easysale->writeLog('Daten von PayPal empfangen', $arrPost, true);
            $strPayPalUrl       = ($this->useSandbox) ? $this->sandbox : $this->paypal;

            // Die URL muss hier angepasst werden, da die IPN-VErbindung anders ist, als die Weiterleitung zu PayPal.
            $strPayPalUrl       = str_replace('/cgi-bin/webscr', '', $strPayPalUrl);
            $strPayPalUrl       = str_replace('https://', 'ssl://', $strPayPalUrl);
            $strPayPalUrl       = ($this->useSandbox) ? str_replace('www.', '', $strPayPalUrl) : $strPayPalUrl;

            $intPayPalPort      = 443;
            $this->posted_data  = $arrPost;
            $postvars           = $this->getPostData();
            $errstr             = '';
            $errno              = '';
            $str                = '';
            $strSend            = '';

            $this->es_easysale->writeLog('Oeffne Socket-Verbindung zu PayPal', $strPayPalUrl . ':' . $intPayPalPort);
            $fp                 = @fsockopen($strPayPalUrl,$intPayPalPort,$errno,$errstr,30);
            $this->es_easysale->writeLog('Rueckgabewerts des Verbindungsaufbaus', $fp);

            if(!$fp){
                $this->error="fsockopen error no. $errno: $errstr";
                $this->es_easysale->writeLog($this->error, fsockopen($strPayPalUrl,$intPayPalPort,$errno,$errstr,30));
                return $this->formatError();
            }

            $strSend   .= "POST /cgi-bin/webscr HTTP/1.1\r\n";
            $strSend   .= "Host: " . str_replace('ssl://', '', $strPayPalUrl) . "\r\n";
            $strSend   .= "Content-type: application/x-www-form-urlencoded\r\n";
            $strSend   .= "Content-length: ".strlen($postvars)."\r\n";
            $strSend   .= "Connection: close\r\n";
            $strSend   .= "\r\n$postvars\r\n\r\n";

            $this->es_easysale->writeLog('Sende Daten an PayPal', $strSend);
            @fputs($fp, $strSend);

            while(!feof($fp)){
                $str.=@ fgets($fp,1024);
            }

            @fclose($fp);

            if(preg_match('/VERIFIED/i',$str)){
                $this->es_easysale->writeLog('Zahlung von PayPal verifiziert!', $this->posted_data);

                if(!preg_match('/subscr/',$this->posted_data['txn_type'])){

                    if($this->posted_data['payment_status']=='Completed'){
                        $this->es_easysale->writeLog('Zahlung bei PayPal komplett!', $this->posted_data);
                        $this->type='payment';

                        if($this->testTransaction($this->posted_data)){
                            $this->es_easysale->writeLog('Speichere Zahlung');
                            $arrNewDbData = $this->es_easysale->saveTransaction($this->posted_data);
                            $this->es_easysale->writeLog('Zahlung gespeichert!', $arrNewDbData);
                            return $arrNewDbData; // Ganze Tabellenzeile zurueckgeben, statt nur der POST-Datan!
                        } else {
                            $this->error='POST-Data-Error. Daten passen nicht zur Transaktion!';
                            $this->es_easysale->writeLog($this->error, $this->posted_data);
                            return $this->formatError();
                        }
                    }
                }

                return $this->posted_data;
            } else {
                $this->error='IPN verification failed.';
                $this->es_easysale->writeLog($this->error, $str);
                return $this->formatError();
            }
        } else {
            $this->error='No POST-Data found.';
            $this->es_easysale->writeLog($this->error, $this->posted_data);
            return $this->formatError();
        }
    }


    /**
     * Erstellt ein POST-String auf die von PayPal vorgegebene Weise.
     * @return string
     */
    private function getPostData(){
        $raw_post_data              = file_get_contents('php://input');
        $raw_post_array             = explode('&', $raw_post_data);
        $get_magic_quotes_exists    = false;
        $myPost                     = array();

        foreach($raw_post_array as $keyval){
            $keyval = explode ('=', $keyval);
            if (count($keyval) == 2){
                $myPost[$keyval[0]] = urldecode($keyval[1]);
            }
        }

        // read the post from PayPal system and add 'cmd'
        $req = 'cmd=_notify-validate';

        if(function_exists('get_magic_quotes_gpc')){
            $get_magic_quotes_exists = true;
        }

        foreach($myPost as $key => $value){
            if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1){
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }

            $req .= "&$key=$value";
        }

        return $req;
    }


    /**
     * Gleicht PayPal-Daten mit der DB ab.
     * @param $arrData
     * @return bool
     */
    public function testTransaction($arrData){
        $arrDbData  = $this->es_easysale->getTransByInvoice($arrData['invoice']);
        $arrTests   = array('quantity', 'item_number', 'item_name', 'mc_gross', 'invoice');
        $this->es_easysale->writeLog('Pruefe Daten [PayPal]', $arrData);
        $this->es_easysale->writeLog('Pruefe Daten [Local]', $arrDbData);

        foreach ($arrTests as $strField) {
            if($arrData[$strField] != $arrDbData[$strField]){
                $this->es_easysale->writeLog('Fehlerhafte Daten gefunden', array('field' => $strField, 'local' => $arrDbData[$strField], 'paypal' => $arrData[$strField]));
                return false;
            }
        }

        return true;
    }


    /**
     * Gibt ein Array mit der Fehlermeldung und der Transaktions-Id (invoice-id, nicht db-id) zurueck.
     * @return array
     */
    private function formatError(){
        return array('code' => $this->posted_data['invoice'], 'error' => $this->error);
    }


    /* ================ *
     * Zahlungsmethoden *
     * ================ */

    /**
     * Stellt auf die Sandbox um, feur Testzwecke
     */
    public function useSandbox(){
        $this->useSandbox = true;
    }


    /**
     * Fuegt ein Array mit Daten in das Fromular ein.
     * @param $arrData
     */
    public function addArray($arrData){
        foreach ($arrData as $name => $value) {
            $this->add($name, $value);
        }

    }


    /**
     * Fuegt eine Vatiable in das Formular ein.
     * @param $name
     * @param $value
     */
    public function add($name, $value){
        switch(strtolower($name)){
            case 'ipn':
                $this->ipn = $value;
                $this->data['notify_url'] = $this->ipn;
                break;

            default:
                $this->data[$name] = $value;
        }
    }


    /**
     * Loesscht eine Variable aus dem Formular.
     * @param $name
     */
    function remove($name){
        switch(strtolower($name)){
            case 'ipn':
                $this->ipn = '';
                $this->data['notify_url'] = '';
                break;

            default:
                unset($this->data[$name]);
        }
    }


    /**
     * Erstellt das Formular fuer die Weiterleitung zur PayPal.
     * @return mixed|string
     */
    public function parseForm(){
        $arrData = array(
            'headline'          => $GLOBALS['TL_LANG']['MSC']['easySale']['redirect']['paypalHeadline'],
            'formAction'        => ($this->useSandbox) ? $this->sandbox : $this->paypal,
            'formSubmitValue'   => $GLOBALS['TL_LANG']['MSC']['easySale']['redirect']['paypalLabel'],
            'redirect'          => ($this->debug && $this->showForm) ? 'false' : 'true'
        );

        $strForm                = file_get_contents('system/modules/es_easysale/templates/tl_easysale_paypal_form.html5');
        $strFieldKind           = ($this->debug && $this->showForm) ? 'text' : 'hidden';
        $strDisplay             = ($this->debug && $this->showForm) ? '' : 'style="display: none;"';

        foreach($this->data as $k => $v){
            $arrData['formFields'] .= ($this->debug && $this->showForm) ? $k : '';
            $arrData['formFields'] .= '<input type="' . $strFieldKind . '" name="' . $k . '" value="' . $v . '" ' . $strDisplay . ' />';
            $arrData['formFields'] .= ($this->debug && $this->showForm) ? "\n<br />" : '';
        }

        foreach($arrData as $key => $value){
            $strForm = str_replace('<#' . $key . '#>', $value, $strForm);
        }

        return $strForm;
    }
}
