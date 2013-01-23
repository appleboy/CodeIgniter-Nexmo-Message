What is Nexmo?
=======================

Nexmo is a cloud based SMS API that lets you send
and receive high volume of messages at wholesale rates.
Web Site: http://www.nexmo.com/index.html

How It Works?
=======================

Please vist http://www.nexmo.com/how_it_works/index.html

Requirements
=======================

* CodeIgniter 2.0.0+
* PHP 5.2.0+
* PHP extension: openssl, pcre, json, xml, curl

Usage
=======================

    // Load the spark
    $this->load->spark('Nexmo-SMS-Message/1.0.3');

Open config/nexmo.php and put your api key and secret

    $config['api_key'] = 'xxxxxx';
    $config['api_secret'] = 'xxxxxx';

Send Text Message

    $from = 'xxxxxxxxxx';
    $to = 'xxxxxxxxxxxx';
    $message = array(
        'text' => 'test message'
    );

    $response = $this->nexmo->send_message($from, $to, $message);
    echo "<h1>Text Message</h1>";
    $this->nexmo->d_print($response);
    echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

Send Binary Message

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

Send Wappush Message

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

Account - Get Balance

    $response = $this->nexmo->get_balance();
    echo "<h1>Account - Get Balance</h1>";
    $this->nexmo->d_print($response);
    echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

Account - Get Pricing

    $response = $this->nexmo->get_pricing('TW');
    echo "<h1>Account - Get Pricing</h1>";
    $this->nexmo->d_print($response);
    echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

Account - Settings

    $response = $this->nexmo->get_account_settings(NULL, 'http://mlb.mlb.com', 'http://www.facebook.com');
    echo "<h1>Account - Settings</h1>";
    $this->nexmo->d_print($response);
    echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

Account - Numbers

    $response = $this->nexmo->get_numbers();
    echo "<h1>Account - Numbers</h1>";
    $this->nexmo->d_print($response);
    echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

Number - Search

    $response = $this->nexmo->get_number_search('US', NULL);
    echo "<h1>Number - Search</h1>";
    $this->nexmo->d_print($response);
    echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

Number - Buy

    $response = $this->nexmo->get_number_buy('US', '34911067000');
    echo "<h1>Number - Buy</h1>";
    $this->nexmo->d_print($response);
    echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

Number - Cancel

    $response = $this->nexmo->get_number_cancel('US', '34911067000');
    echo "<h1>Number - Cancel</h1>";
    $this->nexmo->d_print($response);
    echo "<h3>Response Code: " . $this->nexmo->get_http_status() . "</h3>";

Set response format: xml or json, default is json

    $this->nexmo->set_format('json');

Get http reponse code:

    $this->nexmo->get_http_status();

Change Log
=======================
Please vist [API documentation](http://nexmo.com/documentation/index.html) first

Date: 2013-01-23 (Developer API)

* Account: Top-up
* Number: Update
* Search: Message
* Search: Messages
* Search: Rejections

Date: 2011-11-19 (Developer API)

* Account: Get Balance
* Account: Get Pricing
* Account: Settings
* Account: Numbers
* Number: Search
* Number: Buy
* Number: Cancel

Date: 2011-10-07 (Messaging References)

* Send a Message
* Send Binary Message
* Send WAP Push Message

Copyright
=======================

Copyright (C) 2011 Bo-Yi Wu ( appleboy AT gmail.com )

