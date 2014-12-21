<?php  (! defined('BASEPATH')) and exit('No direct script access allowed');

class example extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // load library
        $this->load->library('nexmo');
        // set response format: xml or json, default json
        $this->nexmo->set_format('json');
        /*
        // **********************************Text Message*************************************
        $from = 'xxxxxxxx';
        $to = 'xxxxxxx';
        $message = array(
            'text' => 'test message',
        );
        $response = $this->nexmo->send_message($from, $to, $message);
        echo "<h1>Text Message</h1>";
        $this->nexmo->d_print($response);
        echo "<h3>Response Code: ".$this->nexmo->get_http_status()."</h3>";

        // *********************************Binary Message**************************************
        
        $from = 'xxxxxxxx';
        $to = 'xxxxxxxxxx';
        $message = array(
            'body' => 'body message',
            'udh' => 'xxxxxxx'
        );
        $response = $this->nexmo->send_message($from, $to, $message);
        echo "<h1>Binary Message</h1>";
        $this->nexmo->d_print($response);
        echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";
        */
        // *********************************Wappush Message**************************************
        /*
        $from = 'xxxxxxxx';
        $to = 'xxxxxxxxxx';
        $message = array(
            'title' => 'xxxxxx',
            'url' => 'xxxxxxx',
            'validity' => 'xxxxxx'
        );
        $response = $this->nexmo->send_message($from, $to, $message);
        echo "<h1>Wappush Message</h1>";
        $this->nexmo->d_print($response);
        echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

        // *********************************Account - Get Balance*********************************
        $response = $this->nexmo->get_balance();
        echo "<h1>Account - Get Balance</h1>";
        $this->nexmo->d_print($response);
        echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

        // ********************************Account - Get Pricing*********************************
        $response = $this->nexmo->get_pricing('TW');
        echo "<h1>Account - Get Pricing</h1>";
        $this->nexmo->d_print($response);
        echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

        // ********************************Account - Settings*********************************
        $response = $this->nexmo->get_account_settings(NULL, 'http://mlb.mlb.com', 'http://www.facebook.com');
        echo "<h1>Account - Settings</h1>";
        $this->nexmo->d_print($response);
        echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

        // ********************************Account - Numbers*********************************
        $response = $this->nexmo->get_numbers();
        echo "<h1>Account - Numbers</h1>";
        $this->nexmo->d_print($response);
        echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

        // ********************************Account - Top-up*********************************
        $trx = 'xxxxxxxxxxxxx';
        $response = $this->nexmo->get_top_up($trx);
        echo "<h1>Account - Top-up</h1>";
        $this->nexmo->d_print($response);
        echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

        // ********************************Number - Search*********************************
        $response = $this->nexmo->get_number_search('US', NULL);
        echo "<h1>Number - Search</h1>";
        $this->nexmo->d_print($response);
        echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

        // ********************************Number - Buy*********************************
        $response = $this->nexmo->get_number_buy('US', 'xxxxxxxxxxxxx');
        echo "<h1>Number - Buy</h1>";
        $this->nexmo->d_print($response);
        echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

        // ********************************Number - Cancel*********************************
        $response = $this->nexmo->get_number_cancel('US', 'xxxxxxxxxxxxx');
        echo "<h1>Number - Cancel</h1>";
        $this->nexmo->d_print($response);
        echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

        // ********************************Number - Update*********************************
        $params = array(
            'moHttpUrl' => 'http://xxxxxx'
            'moSmppSysType' => 'inbound'
        );
        $response = $this->nexmo->get_number_update('TW', 'xxxxxxxxxxxxx', null);
        echo "<h1>Number - Update</h1>";
        $this->nexmo->d_print($response);
        echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

        // ********************************Search - Message*********************************
        $response = $this->nexmo->search_message('xxxxxxxxxxxxx');
        echo "<h1>Search - Message</h1>";
        $this->nexmo->d_print($response);
        echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

        // ********************************Search - Messages*********************************
        $ids = array('xxxxxxxxxxxxx', 'xxxxxxxxxxxxx');
        $response = $this->nexmo->search_messages($ids);
        echo "<h1>Search - Messages</h1>";
        $this->nexmo->d_print($response);
        echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

        $response = $this->nexmo->search_messages(null, '2013-01-23', 'xxxxxxxxxxxxx');
        echo "<h1>Search - Messages</h1>";
        $this->nexmo->d_print($response);
        echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

        // ********************************Search - Rejections*********************************
        $response = $this->nexmo->search_rejections('2013-01-23', 'xxxxxxxxxxxxx');
        echo "<h1>Search - Message</h1>";
        $this->nexmo->d_print($response);
        echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

        // ********************************Verify - Request*********************************
        $to = '447478717821';
        $brand = 'Testing';
        $sender_id = 'Luke'; //optional
        $code_length = '4'; //optional 4 or 6
        $lg = 'en-us'; //optional nexmo default en-us https://docs.nexmo.com/index.php/voice-api/text-to-speech#languages
        $require_type = null; //optional enabled on request by nexmo
        $response = $this->nexmo->verify_request($to, $brand, $sender_id, $code_length, $lg, $require_type);
        echo "<h1>Verify - Request</h1>";
        $this->nexmo->d_print($response);
        echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";
        
        // ********************************Verify - Check*********************************
        $request_id = 'xxxxxxxxxx';
        $code = 'xxxxxxxxxxxxxxxxxx';
        $ip_address = 'xxxxxxxxxxx'; //optional
        $response = $this->nexmo->verify_check($request_id, $code, $ip_address);
        echo "<h1>Verify - Request</h1>";
        $this->nexmo->d_print($response);
        echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

        // ********************************Verify - Search*********************************
        $request_ids = array('xxxxxxxx'); //single request id
        //$request_ids = array('xxxxxxxx', 'xxxxxxxx'); //multiple request ids
        $response = $this->nexmo->verify_search($request_ids);
        echo "<h1>Verify - Request</h1>";
        $this->nexmo->d_print($response);
        echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";
        */
    }
}
/* End of file example.php */
/* Location: ./application/controllers/example.php */