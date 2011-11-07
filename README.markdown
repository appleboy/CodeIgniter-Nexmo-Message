What is Nexmo?
=======================

Nexmo is a cloud based SMS API that lets you send
and receive high volume of messages at wholesale rates.
Web Site: http://www.nexmo.com/index.html

How It Works?
=======================

Please vist http://www.nexmo.com/how_it_works/index.html

Installation
=======================

Copy files to your applicaiotn folder

    $ cp config/nexmo.php application/config/
    $ cp libries/nexmo.php application/libries/
    $ cp controller/nexmo.php application/controller/

Open config/nexmo.php and put your api key and secret

    $config['api_key'] = 'xxxxxx';
    $config['api_secret'] = 'xxxxxx';

Open controller/nexmo.php and modified the following changes for sending message

    $from = 'xxxxxxxxxx';
    $to = 'xxxxxxxxxxxx';
    $message = array(
        'text' => 'test message'
    );

Open browser and load the following URL

    http://your_host/example

Copyright
=======================

Copyright (C) 2011 Bo-Yi Wu ( appleboy AT gmail.com )

