# What is Nexmo?

Nexmo is a cloud based SMS API that lets you send
and receive high volume of messages at wholesale rates.
Web Site: http://www.nexmo.com/index.html

# How It Works?

Please vist https://www.nexmo.com/tour/

# Requirements

* CodeIgniter 2.0.0+
* PHP 5.2.0+
* PHP extension: openssl, pcre, json, xml, curl

# Usage

You can install via http://getsparks.org/packages/Nexmo-SMS-Message/versions/HEAD/show

```bash
$ php tools/spark install -v1.0.4 Nexmo-SMS-Message
```

or referrer the following steps.

Copy files to your applicaiotn folder

```bash
$ cp config/nexmo.php application/config/
$ cp libries/nexmo.php application/libries/
$ cp controller/nexmo.php application/controller/
```

Open config/nexmo.php and put your api key and secret

```php
$config['api_key'] = 'xxxxxx';
$config['api_secret'] = 'xxxxxx';
```

Open controller/nexmo.php and modified the following changes for sending message

```php
$from = 'xxxxxxxxxx';
$to = 'xxxxxxxxxxxx';
$message = array(
    'text' => 'test message'
);
```

Open browser and load the following URL

    http://your_host/example

# Change Log

Please vist [API documentation](https://docs.nexmo.com/) first

Date: 2014-12-16 (Developer API)

* Verify: Request
* Verify: Check
* Verify: Search

Added by @lukeprentice202

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

# Copyright

Copyright (C) 2011-2014 Bo-Yi Wu ( appleboy AT gmail.com )

