<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Nexmo Message Library
 * Class Nexmo Message handles the methods and properties of sending an SMS message.
 * URL: http://www.nexmo.com/documentation/index.html
 * Author: Bo-Yi Wu <appleboy.tw@gmail.com>
 * Date: 2011-11-07
 */

class Nexmo {

    // using https by default
    const http_xml_url = 'https://rest.nexmo.com/sms/xml';
    const http_json_url = 'https://rest.nexmo.com/sms/json';
    public static $balance_url = 'http://rest.nexmo.com/account/get-balance';
    public static $pricing_url = 'http://rest.nexmo.com/account/get-pricing/outbound';
    public static $account_url = 'http://rest.nexmo.com/account/settings';
    public static $number_url = 'http://rest.nexmo.com/account/numbers';
    public static $search_url = 'http://rest.nexmo.com/number/search';
    public static $buy_url = 'http://rest.nexmo.com/number/buy';
    public static $cancel_url = 'http://rest.nexmo.com/number/cancel';
    
    private $_url_array = array('balance_url', 'pricing_url', 'account_url',
                           'number_url', 'search_url', 'buy_url', 'cancel_url');
    
    // codeigniter instance
    private $_ci;

    // api key and secret
    private $_api_key;
    private $_api_secret;
    private $_format = 'json';

    // debug mode
    private $_enable_debug = FALSE;
    // http reponse
    private $_http_status;
    private $_http_response;
    
    // curl init session
    protected $session;
    protected $options = array();
    protected $url;

    function __construct()
    {
        $this->_ci =& get_instance();
        $this->_ci->load->config('nexmo');
        $this->_api_key = $this->_ci->config->item("api_key");
        $this->_api_secret = $this->_ci->config->item("api_secret");
        
        $this->_initial();
    }

    /**
     * initial api url
     * return null
     */
    private function _initial()
    {
        foreach($this->_url_array as $key)
        {
            self::$$key = self::$$key . '/' . $this->_api_key . '/' . $this->_api_secret;
        }
    }

    /**
     * sending an SMS message
     *
     * @param string
     * @param string
     * @param array
     * @param string (text, binary or wappush)
     * return string
     */
    public function send_message($from, $to, $message, $type = 'text')
    {
        mb_internal_encoding("UTF-8");
        mb_http_output("UTF-8");
        switch($type)
        {
            case 'text':
                $data = array(
                    'text' => (isset($message['text'])) ? $message['text'] : '',
                    'type' => (isset($message['type'])) ? $message['type'] : 'unicode'
                );
            break;
            case 'binary':
                $data = array(
                    'body' => (isset($message['body'])) ? bin2hex($message['body']) : '',
                    'udh' => (isset($message['udh'])) ? bin2hex($message['udh']) : '',
                    'type' => (isset($message['type'])) ? $message['type'] : 'binary'
                );
            break;
            case 'wappush':
                $data = array(
                    'title' => (isset($message['title'])) ? $message['title'] : '',
                    'url' => (isset($message['url'])) ? $message['url'] : '',
                    'type' => (isset($message['type'])) ? $message['type'] : 'wappush',
                    'validity' => (isset($message['validity'])) ? $message['validity'] : 86400000,
                );
            break;
        }

        // handle data
        $post = array(
            'from' => $from,
            'to' => $to
        );
        $post = array_merge($post, $data);

        $params = array_merge(array('username' => $this->_api_key, 'password' => $this->_api_secret), $post);
        $url = ($this->_format == 'json') ? self::http_json_url : self::http_xml_url;
        
        $options = array(
            CURLOPT_POST => TRUE,
            CURLOPT_SSL_VERIFYHOST => 1,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_RETURNTRANSFER => TRUE
        );

        return $this->request('post', $url, $params, $options);
    }

    /**
     * Account - Get Balance
     * Retrieve your current account balance.
     *
     * return json or xml
     */
    public function get_balance()
    {
        $options = array(
            CURLOPT_HTTPHEADER => array("Accept: application/" . $this->_format)
        );

        return $this->request('get', self::$balance_url, NULL, $options);
    }
    
    /**
     * Account - Get Pricing
     * Retrieve our outbound pricing for a given country.
     *
     * @param string
     * return json or xml
     */
    public function get_pricing($country_code = 'TW')
    {
        $options = array(
            CURLOPT_HTTPHEADER => array("Accept: application/" . $this->_format)
        );
        
        self::$pricing_url = self::$pricing_url . '/' . $country_code;
        return $this->request('get', self::$pricing_url, NULL, $options);
    }

    /**
     * Account - Settings
     * Update your account settings.
     *
     * @param string
     * @param string
     * @param string
     * return json or xml
     */
    public function get_account_settings($newSecret = NULL, $moCallBackUrl = NULL, $drCallBackUrl = NULL)
    {
        $options = array(
            CURLOPT_HTTPHEADER => array("Accept: application/" . $this->_format),
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE
        );
 
        if(isset($newSecret))
            $params['newSecret'] = $newSecret;
        if(isset($moCallBackUrl))
            $params['moCallBackUrl'] = urlencode($moCallBackUrl);
        if(isset($drCallBackUrl))
            $params['drCallBackUrl'] = urlencode($drCallBackUrl);

        //self::$account_url = self::$account_url . (($params) ? '?' . http_build_query($params) : '');
        return $this->request('post', self::$account_url, $params, $options);
        //return $this->request('post', self::$account_url, NULL, $options);
    }
    
    /**
     * Account - Numbers
     * Get all inbound numbers associated with your Nexmo account.
     *
     * return json or xml
     */
    public function get_numbers()
    {
        $options = array(
            CURLOPT_HTTPHEADER => array("Accept: application/" . $this->_format)
        );
        
        return $this->request('get', self::$number_url, NULL, $options);
    }
    
    /**
     * Number - Search
     * Get available inbound numbers for a given country.
     *
     * @param string
     * @param string
     * return json or xml
     */
    public function get_number_search($country_code = 'TW', $pattern = NULL)
    {
        $options = array(
            CURLOPT_HTTPHEADER => array("Accept: application/" . $this->_format)
        );
        
        self::$search_url = self::$search_url . '/' . $country_code;

        if(isset($pattern))
        {
            $params = array(
                "pattern" => $params
            );
            self::$search_url = self::$search_url . '?' . http_build_query($params);
        }

        return $this->request('get', self::$search_url, NULL, $options);
    }
    
    /**
     * Number - Buy
     * Purchase a given inbound number.
     *
     * @param string
     * @param string
     * return json or xml
     */
    public function get_number_buy($country_code = 'TW', $msisdn = NULL)
    {
        if (!isset($msisdn))
        {
            echo('msisdn must be required');
            exit();
        }

        $options = array(
            CURLOPT_HTTPHEADER => array("Accept: application/" . $this->_format),
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE
        );
        
        self::$buy_url = self::$buy_url . '/' . $country_code . '/' . $msisdn;

        return $this->request('post', self::$buy_url, NULL, $options);
    }
    
    /**
     * Number - Cancel
     * Cancel a given inbound number subscription.
     *
     * @param string
     * @param string
     * return json or xml
     */
    public function get_number_cancel($country_code = 'TW', $msisdn = NULL)
    {
        if (!isset($msisdn))
        {
            echo('msisdn must be required');
            exit();
        }
        $options = array(
            CURLOPT_HTTPHEADER => array("Accept: application/" . $this->_format),
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE
        );
        
        self::$cancel_url = self::$cancel_url . '/' . $country_code . '/' . $msisdn;

        return $this->request('post', self::$cancel_url, NULL, $options);
    }

    /**
     * request data
     * Connect to Nexmo URL API
     *
     * @param array
     * return string
     */
    protected function request($method, $url, $params = array(), $options = array())
    {
        if ($method === 'get')
        {
            // If a URL is provided, create new session
            $this->create($url . ($params ? '?' . http_build_query($params) : ''));
        }
        else
        {
            $data = $params ? http_build_query($params) : '';
            $this->create($url);
            
            $options[CURLOPT_POSTFIELDS] = $data;

        }
        $this->options($options);
    
        return $this->execute();
    }

    protected function options($options = array())
    {
        // Set all options provided
        curl_setopt_array($this->session, $options);

        return $this;
    }

    protected function create($url)
    {
        $this->url = $url;
        $this->session = curl_init($this->url);
        return $this;
    }

    protected function execute()
    {
        // Execute the request & and hide all output
        $this->_http_response = curl_exec($this->session);
        $this->_http_status = curl_getinfo($this->session, CURLINFO_HTTP_CODE);

        curl_close($this->session);

        return $this->response();
    }

    /**
     *
     * set http format (json or xml)
     *
     * @param string
     * @return this
     */
    public function set_format($format = 'json')
    {
        if ($format != 'json' AND $format != 'xml')
            $format = 'json';

        $this->_format = $format;
        return $this;
    }

    /**
     *
     * get http response (json or xml)
     *
     * @return json or xml
     */
    protected function response()
    {
        switch($this->_format)
        {
            case 'xml':
                $response_obj = $this->_http_response;
            break;
            case 'json':
            default:
                $response_obj = json_decode($this->_http_response);
        }
        
        return $response_obj;
    }

    /**
     *
     * get http response status
     *
     * @return int
     */
    public function get_http_status()
    {
        return (int) $this->_http_status;
    }

    /**
     *
     * output debug message via using dump
     *
     * @param string
     */
    public function d_print($msg)
    {
        echo '<pre>';
        print_r($msg);
        echo '</pre>';
    }

    /**
     *
     * output debug message via using dump
     *
     * @param string
     */
    public function d_dump($msg)
    {
        echo '<pre>';
        var_dump($msg);
        echo '</pre>';
    }
    
    public function debug()
    {
        echo '<br />';
        echo '<p>url: ' . $this->url . '</p>';
    }
}

/* End of file nexmo.php */
/* Location: ./application/libraries/nexmo.php */