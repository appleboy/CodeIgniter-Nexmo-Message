<?php (! defined('BASEPATH')) and exit('No direct script access allowed');

/*
 * Nexmo Message Library
 * Class Nexmo Message handles the methods and properties of sending an SMS message.
 * URL: http://www.nexmo.com/documentation/index.html
 * Author: Bo-Yi Wu <appleboy.tw@gmail.com>
 * Date: 2011-11-07
 */

class nexmo
{
    // using https by default
    const http_xml_url = 'https://rest.nexmo.com/sms/xml';
    const http_json_url = 'https://rest.nexmo.com/sms/json';
    public static $balance_url = 'http://rest.nexmo.com/account/get-balance';
    public static $pricing_url = 'http://rest.nexmo.com/account/get-pricing/outbound';
    public static $account_url = 'http://rest.nexmo.com/account/settings';
    public static $number_url = 'http://rest.nexmo.com/account/numbers';
    public static $top_up_url = 'http://rest.nexmo.com/account/top-up';
    public static $search_url = 'http://rest.nexmo.com/number/search';
    public static $buy_url = 'http://rest.nexmo.com/number/buy';
    public static $cancel_url = 'http://rest.nexmo.com/number/cancel';
    public static $update_url = 'http://rest.nexmo.com/number/update';
    public static $message_url = 'http://rest.nexmo.com/search/message';
    public static $messages_url = 'http://rest.nexmo.com/search/messages';
    public static $rejections_url = 'http://rest.nexmo.com/search/rejections';
    public static $insight_url = 'https://rest.nexmo.com/ni';

    public static $verify_base_url = 'https://api.nexmo.com/verify';

    private $_url_array = array('balance_url', 'pricing_url', 'account_url',
                           'number_url', 'top_up_url', 'search_url', 'buy_url',
                           'update_url', 'cancel_url', 'message_url', 'messages_url',
                           'rejections_url',);

    // codeigniter instance
    private $_ci;

    // api key and secret
    private $_api_key;
    private $_api_secret;
    private $_format = 'json';
    //either assoc for (assoc array) or class for ( stdClass )
    private $_json_format = 'assoc';

    // debug mode
    private $_enable_debug = false;

    // http request
    private $_request_url;
    private $_request_headers;
    private $_request_body;

    // http reponse
    private $_http_status;
    private $_http_response;

    // curl init session
    protected $session;
    protected $options = array();
    protected $url;

    public function __construct()
    {
        $this->_ci = & get_instance();
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
        foreach ($this->_url_array as $key) {
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
        switch ($type) {
            case 'text':
                $data = array(
                    'text' => (isset($message['text'])) ? $message['text'] : '',
                    'type' => (isset($message['type'])) ? $message['type'] : 'unicode',
                );
            break;
            case 'binary':
                $data = array(
                    'body' => (isset($message['body'])) ? bin2hex($message['body']) : '',
                    'udh'  => (isset($message['udh'])) ? bin2hex($message['udh']) : '',
                    'type' => (isset($message['type'])) ? $message['type'] : 'binary',
                );
            break;
            case 'wappush':
                $data = array(
                    'title'    => (isset($message['title'])) ? $message['title'] : '',
                    'url'      => (isset($message['url'])) ? $message['url'] : '',
                    'type'     => (isset($message['type'])) ? $message['type'] : 'wappush',
                    'validity' => (isset($message['validity'])) ? $message['validity'] : 86400000,
                );
            break;
        }

        // handle data
        $post = array(
            'from' => $from,
            'to'   => $to,
        );
        $post = array_merge($post, $data);
        $params = array_merge(array('api_key' => $this->_api_key, 'api_secret' => $this->_api_secret), $post);
        $url = ($this->_format === 'json') ? self::http_json_url : self::http_xml_url;

        $options = array(
            CURLOPT_POST           => TRUE,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => 0,
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
            CURLOPT_HTTPHEADER => array("Accept: application/" . $this->_format),
        );

        return $this->request('get', self::$balance_url, null, $options);
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
            CURLOPT_HTTPHEADER => array("Accept: application/" . $this->_format),
        );

        self::$pricing_url = self::$pricing_url . '/' . $country_code;

        return $this->request('get', self::$pricing_url, null, $options);
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
    public function get_account_settings($newSecret = null, $moCallBackUrl = null, $drCallBackUrl = null)
    {
        $options = array(
            CURLOPT_HTTPHEADER => array("Accept: application/" . $this->_format),
            CURLOPT_POST       => TRUE,
        );

        if (isset($newSecret)) {
            $params['newSecret'] = $newSecret;
        }
        if (isset($moCallBackUrl)) {
            $params['moCallBackUrl'] = $moCallBackUrl;
        }
        if (isset($drCallBackUrl)) {
            $params['drCallBackUrl'] = $drCallBackUrl;
        }

        return $this->request('post', self::$account_url, $params, $options);
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
            CURLOPT_HTTPHEADER => array("Accept: application/" . $this->_format),
        );

        return $this->request('get', self::$number_url, null, $options);
    }

    /**
     * Account - Top-up
     * Top-up your account, only if you have turn-on the 'auto-reload' feature.
     * The top-up amount is the one associated with your 'auto-reload' transaction.
     *
     * return json or xml
     */
    public function get_top_up($trx = null)
    {
        $options = array(
            CURLOPT_HTTPHEADER => array("Accept: application/" . $this->_format),
        );

        if (isset($trx)) {
            $params['trx'] = $trx;
        }

        return $this->request('get', self::$top_up_url, $params, $options);
    }

    /**
     * Number - Search
     * Get available inbound numbers for a given country.
     *
     * @param string
     * @param string
     * return json or xml
     */
    public function get_number_search($country_code = 'TW', $pattern = null)
    {
        $params = null;

        $options = array(
            CURLOPT_HTTPHEADER => array("Accept: application/" . $this->_format),
        );

        $search_url = self::$search_url . '/' . $country_code;

        if (isset($pattern)) {
            $params = array("pattern" => $params);
        }

        return $this->request('get', $search_url, $params, $options);
    }

    /**
     * Number - Buy
     * Purchase a given inbound number.
     *
     * @param string
     * @param string
     * return json or xml
     */
    public function get_number_buy($country_code = 'TW', $msisdn = null)
    {
        if (!isset($msisdn)) {
            echo('msisdn must be required');
            exit();
        }

        $options = array(
            CURLOPT_HTTPHEADER => array("Accept: application/" . $this->_format),
            CURLOPT_POST       => TRUE,
        );

        self::$buy_url = self::$buy_url . '/' . $country_code . '/' . $msisdn;

        return $this->request('post', self::$buy_url, null, $options);
    }

    /**
     * Number - Cancel
     * Cancel a given inbound number subscription.
     *
     * @param string
     * @param string
     * return json or xml
     */
    public function get_number_cancel($country_code = 'TW', $msisdn = null)
    {
        if (!isset($msisdn)) {
            echo('msisdn must be required');
            exit();
        }
        $options = array(
            CURLOPT_HTTPHEADER => array("Accept: application/" . $this->_format),
            CURLOPT_POST       => TRUE,
        );

        $_url = self::$cancel_url . '/' . $country_code . '/' . $msisdn;

        return $this->request('post', $_url, null, $options);
    }

    /**
     * Number - Update
     * Update your number callback.
     *
     * @param string
     * @param string
     * @param array
     * return json or xml
     */
    public function get_number_update($country_code = 'TW', $msisdn = null, $params = array())
    {
        if (!isset($msisdn)) {
            echo('msisdn must be required');
            exit();
        }
        $options = array(
            CURLOPT_HTTPHEADER => array("Accept: application/" . $this->_format),
            CURLOPT_POST       => TRUE,
        );

        if (!is_array($params) or empty($params)) {
            $params = array();
        }

        $_url = self::$update_url . '/' . $country_code . '/' . $msisdn . '?' . http_build_query($params);

        return $this->request('post', $_url, null, $options);
    }

    /**
     * Search - Message
     * Search a previously sent message for a given message id.
     * Please note a message become searchable a few minutes
     * after submission for real-time delivery notification implement our DLR call back.
     *
     * @param string
     * return json or xml
     */
    public function search_message($id = null)
    {
        if (!isset($id) or empty($id)) {
            echo('Your message id must be required');
            exit();
        }
        $options = array(
            CURLOPT_HTTPHEADER => array("Accept: application/" . $this->_format),
        );

        $_url = self::$message_url . '/' . $id;

        return $this->request('get', $_url, null, $options);
    }

    /**
     * Search - Messages
     * Search sent messages.
     * Please note a message become searchable a few minutes
     * after submission for real-time delivery notification implement our DLR call back.
     *
     * @param string
     * @param string
     * @param string
     * return json or xml
     */
    public function search_messages($ids = array(), $date = null, $to = null)
    {
        $options = array(
            CURLOPT_HTTPHEADER => array("Accept: application/" . $this->_format),
        );

        if (isset($ids) and !empty($ids) and is_array($ids)) {
            $url_string = '';
            foreach ($ids as $row) {
                $url_string .= ((!empty($url_string)) ? '&' : '?') . 'ids=' . $row;
            }
            $_url = self::$messages_url . $url_string;
        }

        if (isset($date) and isset($to)) {
            $params = array(
                'date' => $date,
                'to'   => $to,
            );
            $_url = self::$messages_url;
        }

        return $this->request('get', $_url, $params, $options);
    }

    /**
     * Search - Rejections
     * Search rejected messages. Please note a message become searchable a few minutes after submission.
     *
     * @param string
     * @param string
     * return json or xml
     */
    public function search_rejections($date = null, $to = null)
    {
        $options = array(
            CURLOPT_HTTPHEADER => array("Accept: application/" . $this->_format),
        );

        $params = array(
            'date' => $date,
            'to'   => $to,
        );

        return $this->request('get', self::$rejections_url, $params, $options);
    }

    /**
     * Verify
     * The Verification request API call enables you to request Nexmo to kick off the verification process for the number you provided. Nexmo will handle all the retries and PIN generation.
     * https://docs.nexmo.com/index.php/verify/verify
     *
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * return json or xml
     */
    public function verify_request($to, $brand, $sender_id = null, $code_length = null, $lg = null, $require_type = null)
    {
        $options = array(
            CURLOPT_HTTPHEADER => array("Accept: application/" . $this->_format),
        );

        $params = array(
            'number'       => $to,
            'brand'        => $brand,
            'sender_id'    => $sender_id,
            'code_length'  => $code_length,
            'lg'           => $lg,
            'require_type' => $require_type,
        );

        return $this->request('get', $this->verify_url($params), null, $options);
    }

    /**
     * Verify Check
     * The Verification Check API call enables you to check whether the PIN code you got from your end user matches the one Nexmo sent.
     * https://docs.nexmo.com/index.php/verify/check
     *
     * @param string
     * @param string
     * @param string
     * return json or xml
     */
    public function verify_check($request_id, $code, $ip_address = null)
    {
        $options = array(
            CURLOPT_HTTPHEADER => array("Accept: application/" . $this->_format),
        );

        $params = array(
            'request_id' => $request_id,
            'code'       => $code,
            'ip_address' => $ip_address,
        );

        return $this->request('get', $this->verify_url($params, "check"), null, $options);
    }

    /**
     * Verify Search
     * The Verification Search API call enables you to get the status and many other attributes of a verification request even if the verification is still in progress.
     * https://docs.nexmo.com/index.php/verify/search
     *
     * @param array
     * return json or xml
     */
    public function verify_search($request_ids)
    {
        $options = array(
            CURLOPT_HTTPHEADER => array("Accept: application/" . $this->_format),
        );

        if (count($request_ids) === 1) {
            $params = array(
                "request_id" => $request_ids,
            );
            $url = $this->verify_url($params, "search");
        }

        $url = $this->verify_url(array(), "search");
        foreach ($request_ids as $row) {
            $url .= "&request_ids=" . $row;
        }

        return $this->request('get', $url, null, $options);
    }

    /**
     * Number Insight
     * Number Insight API allows you to retrieve information such as number validity, number type, number presence, current carrier, porting and roaming for a given number. These customer identifiers can be used by your application/business for decision making.
     * https://docs.nexmo.com/index.php/number-insight
     *
     * @param string
     * @param string
     * @param string
     * @param string
     * @param integer
     * @param string
     * return json or xml
     */
    public function insight_request($number, $callback, $callback_timeout = null, $callback_method = null, $features = null, $client_ref = null)
    {
       $options = array(
            CURLOPT_HTTPHEADER => array("Accept: application/" . $this->_format),
        );

        $params = array(
            'api_key'           => $this->_api_key,
            'api_secret'        => $this->_api_secret,
            'number'            => $number,
            'features'          => $features,
            'callback'          => $callback,
            'callback_timeout'  => $callback_timeout,
            'callback_method'   => $callback_method,
            'client_ref'        => $client_ref
        );

        return $this->request('get', self::$insight_url . '/' . $this->_format, $params, $options);
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
        if ($method === 'get') {
            $uri = $url . ($params ? '?' . http_build_query($params) : '');
            $this->create($uri);
            $this->_request_url = $uri;
        } else {
            $data = $params ? http_build_query($params) : '';
            $this->create($url);
            $this->_request_url = $url;

            $options[CURLOPT_POSTFIELDS] = $data;
            $this->_request_body = $options[CURLOPT_POSTFIELDS];
        }
        // TRUE to return the transfer as a string of the return value of curl_exec()
        // instead of outputting it out directly.
        $options[CURLOPT_RETURNTRANSFER] = true;
        $options[CURLINFO_HEADER_OUT] = true;

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
        $this->_request_headers = curl_getinfo($this->session, CURLINFO_HEADER_OUT);

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
        if ($format !== 'json' and $format !== 'xml') {
            $format = 'json';
        }

        $this->_format = $format;

        return $this;
    }
    
    /**
     * Set the json return format
     * 
     * @param       String  $   JSON format, assoc or class for ASSOC ARRAY and stdClass respectively
     * 
     * @return      NULL
     */ 
    public function set_json_format( $format = 'assoc' )
    {
        if( $format !== 'assoc' and $format !== 'class'  )
            $format = 'assoc';
        $this->_json_format = $format;
    }

    /**
     *
     * get http response (json or xml)
     *
     * @return json or xml
     */
    protected function response()
    {
        switch ($this->_format) {
            case 'xml':
                $response_obj = $this->_http_response;
            break;
            case 'json':
            default:
            {
                $is_assoc = ( $this->_json_format === 'assoc' ) ? TRUE : FALSE;
                $response_obj = json_decode($this->_http_response , $is_assoc );
            }
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

    public function get_request()
    {
        //return $this->_request_url . "\n" . trim($this->_request_headers) . "\n" . trim($this->_request_body);
      return trim($this->_request_headers) . "\n" . trim($this->_request_body);
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

    /**
     *
     * output verify base url
     *
     * @param string
     */
    public function verify_url($params, $type = "request")
    {
        switch ($type) {
            case 'check':
                $type = "check/";
                break;
            case 'search':
                $type = "search/";
                break;
            case 'request':
            default:
                $type = null;
                break;
        }
        $params["api_key"] = $this->_api_key;
        $params['api_secret'] = $this->_api_secret;

        return self::$verify_base_url . "/" . $type . $this->_format . "?" . http_build_query($params);
    }
}

/* End of file nexmo.php */
/* Location: ./application/libraries/nexmo.php */
