<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Example extends CI_Controller {

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

        // **********************************Text Message*************************************

        $from = 'xxxxxxxxxx';
        $to = 'xxxxxxxxxxxx';
        $message = array(
            'text' => 'test message'
        );
        $response = $this->nexmo->send_message($from, $to, $message);
        echo "<h1>Text Message</h1>";
        $this->nexmo->d_print($response);
        echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

        // *********************************Binary Message**************************************
        /*
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
        */
        /*
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

        // ********************************Number - Search*********************************
        $response = $this->nexmo->get_number_search('US', NULL);
        echo "<h1>Number - Search</h1>";
        $this->nexmo->d_print($response);
        echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

        // ********************************Number - Buy*********************************
        $response = $this->nexmo->get_number_buy('US', '34911067000');
        echo "<h1>Number - Buy</h1>";
        $this->nexmo->d_print($response);
        echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

        // ********************************Number - Cancel*********************************
        $response = $this->nexmo->get_number_cancel('US', '34911067000');
        echo "<h1>Number - Cancel</h1>";
        $this->nexmo->d_print($response);
        echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";
        */
    }
}
/* End of file example.php */
/* Location: ./application/controllers/example.php */
