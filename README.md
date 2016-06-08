# What is Nexmo?

Nexmo is a cloud based SMS API that lets you send
and receive high volume of messages at wholesale rates.
Web Site: http://www.nexmo.com/index.html

# Requirements

* CodeIgniter 2.0.0+
* PHP 5.4.0+
* PHP extension: openssl, pcre, json, xml, curl

# Install

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

Date: 2014-12-21 (Developer API)

* [Insight: Request](https://docs.nexmo.com/index.php/number-insight/request)

Added by @lukeprentice202

Date: 2014-12-16 (Developer API)

* [Verify: Request](https://docs.nexmo.com/index.php/verify/verify)
* [Verify: Check](https://docs.nexmo.com/index.php/verify/check)
* [Verify: Search](https://docs.nexmo.com/index.php/verify/search)

Added by @lukeprentice202

Date: 2013-01-23 (Developer API)

* [Account: Top-up](https://docs.nexmo.com/index.php/developer-api/account-top-up)
* [Number: Update](https://docs.nexmo.com/index.php/developer-api/number-update)
* [Search: Message](https://docs.nexmo.com/index.php/developer-api/search-message)
* [Search: Messages](https://docs.nexmo.com/index.php/developer-api/search-messages)
* [Search: Rejections](https://docs.nexmo.com/index.php/developer-api/search-rejections)

Date: 2011-11-19 (Developer API)

* [Account: Get Balance](https://docs.nexmo.com/index.php/developer-api/account-get-balance)
* [Account: Get Pricing](https://docs.nexmo.com/index.php/developer-api/account-pricing)
* [Account: Settings](https://docs.nexmo.com/index.php/developer-api/account-settings)
* [Account: Numbers](https://docs.nexmo.com/index.php/developer-api/account-numbers)
* [Number: Search](https://docs.nexmo.com/index.php/developer-api/number-search)
* [Number: Buy](https://docs.nexmo.com/index.php/developer-api/number-buy)
* [Number: Cancel](https://docs.nexmo.com/index.php/developer-api/number-cancel)

Date: 2011-10-07 (Messaging References)

* [Send a Message](https://docs.nexmo.com/index.php/sms-api/send-message)
* [Send Binary Message](https://docs.nexmo.com/index.php/how-to/send-binary-message)
* [Send WAP Push Message](https://docs.nexmo.com/index.php/how-to/send-wap-push-message)

# Copyright

Copyright (C) 2011-2014 Bo-Yi Wu ( appleboy AT gmail.com )
