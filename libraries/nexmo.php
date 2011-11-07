<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Nexmo Message Library
 * Class Nexmo Message handles the methods and properties of sending an SMS message. 
 * URL: http://www.nexmo.com/documentation/index.html 
 * Author: Bo-Yi Wu <appleboy.tw@gmail.com>
 * Date: 2011-11-07
 */

class Nexmo {

    private $_http_xml_url = 'http://rest.nexmo.com/sms/xml';
    private $_http_json_url = 'http://rest.nexmo.com/sms/json';

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
        
    function __construct()
    {
        $this->_ci =& get_instance();
        $this->_ci->load->config('nexmo');
        $this->_api_key = $this->_ci->config->item("api_key");
        $this->_api_secret = $this->_ci->config->item("api_secret");
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

		return $this->request($post);    
    }
    
    /**
     * request data
     * Connect to Nexmo URL API     
     *
     * @param array
     * return string                           
     */ 
    function request($data = array()) 
    {
        $data = array_merge(array('username' => $this->_api_key, 'password' => $this->_api_secret), $data);
        
        $data = http_build_query($data);
        
        $url = ($this->_format == 'json') ? $this->_http_json_url : $this->_http_xml_url; 
        
        if (function_exists('curl_version')) 
        {
            $ch = curl_init();
    
            /* POST Url */
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    
            $result = curl_exec($ch);
    
            // show error message
            if($this->_enable_debug)
            {
                if(!curl_errno($ch))
                {
                    $info = curl_getinfo($ch);
                    echo 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url'] . "<br />";
                }
                else
                {
                    echo 'Curl error: ' . curl_error($ch) . "<br />";
                }
            }
    
            $this->_http_response = $result;
            $this->_http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
            curl_close($ch);        
        }
        else if (ini_get('allow_url_fopen'))
        {
			$opts = array('http' =>
				array(
					'method'  => 'POST',
					'header'  => 'Content-type: application/x-www-form-urlencoded',
					'content' => $data
				)
			);
			$context = stream_context_create($opts);
			$result = file_get_contents($url, false, $context);
			
            // get http response code
            preg_match('/.*\s(\d+)\s(.*)$/', $http_response_header[0], $matches);
			$this->_http_status = $matches[1];
			
            $this->_http_response = $result;        
        }

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
    public function response()
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
}

/* End of file nexmo.php */
/* Location: ./application/libraries/nexmo.php */