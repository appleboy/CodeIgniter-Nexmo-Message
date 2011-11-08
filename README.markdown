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
    $this->load->spark('Nexmo-SMS-Message/1.0.0');
    // Load the library
    $this->load->library('nexmo');

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
    
Set response format: xml or json, default is json

    $this->nexmo->set_format('json');

Copyright
=======================

Copyright (C) 2011 Bo-Yi Wu ( appleboy AT gmail.com )

