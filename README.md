# paddlin-php

Interact with the Paddle Billing API

A very lightweight library that doesn't force dependencies on you, only requires PSR-7 and PSR-18, so that you get to choose which implementations you want to use.

### Basic usage

First require `biohzrdmx/paddlin-php` with Composer.

Then you just need to create a `Paddlin\Client` instance, for that you must pass four parameters:

- An instance of an `Psr\Http\Client\ClientInterface` implementation
- An instance of an `Psr\Http\Message\ServerRequestFactoryInterface` implementation
- An API key that you've created on your Paddle Dashboard
- A `bool` specifying whether to use the sandbox environment or not

For example, if you're using Guzzle you can use something like the following code:

```php
use Paddlin\Client;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7\HttpFactory;

$http_factory = new HttpFactory();
$http_client = new HttpClient();
$client = new Client($http_client, $http_factory, 'YOUR_API_KEY', true);
```

Once you have a `Paddlin\Client` instance you can use any of the supported resources to interact with the API; so to get all of the products you may use the `list` method of the `products` resource:

```php
$products = $client->products->list();
```

If you want a single product, you may use the `get` method:

```php
$product = $client->products->get('pro_01gsz4t5hdjse780zja8vvr7jg');
```

To create a product you would use the `create` method, as shown in the next example:

```php
$payload = [
    "name" => "AeroEdit Student",
    "tax_category" => "standard",
    "description" => "Essential tools for student pilots to manage flight logs, analyze performance, and plan routes, and ensure compliance. Valid student pilot certificate from the FAA required.",
    "image_url" => "https://paddle.s3.amazonaws.com/user/165798/bT1XUOJAQhOUxGs83cbk_pro.png",
    "custom_data" => [
        "features" => [
            "aircraft_performance" => true,
            "compliance_monitoring" => false,
            "flight_log_management" => true,
            "payment_by_invoice" => false,
            "route_planning" => true,
            "sso" => false
        ],
        "suggested_addons" => [
            "pro_01h1vjes1y163xfj1rh1tkfb65",
            "pro_01gsz97mq9pa4fkyy0wqenepkz"
        ],
        "upgrade_description" => null
    ]
];
$client->products->create($payload);
```

And to update an existing product, the `update` method can be called as shown:

```php
$payload = [
  "name" => "AeroEdit for learner pilots"
];
$client->products->update('pro_01htz88xpr0mm7b3ta2pjkr7w2', $payload);
```

For more details on the available resources and its related data structures, check out the Paddle API reference: https://developer.paddle.com/api-reference/overview

_Note: Paddlin currently does not support the Reports, Events, Notifications nor Simulation resources._

#### Dot-notation access

Paddlin includes a handy feature to access the fields inside an entity, some of which may or may not be present; just call the `get` method using dot-notation, for example:

```php
$product = $client->products->get('pro_01gsz4t5hdjse780zja8vvr7jg');
$suggested_addons = $product->get('custom_data.features.suggested_addons');
```

You may also specify a default value should the specified one doesn't exist:

```php
$product = $client->products->get('pro_01gsz4t5hdjse780zja8vvr7jg');
$special_requirements = $product->get('custom_data.features.special_requirements', []); # Return an empty array instead of null if the key does not exist
```

### Verifying webhooks

You may verify your webhook notifications too, just create a `Paddlin\Webhook\Webhook` instance, passing the `$request` object (a `ServerRequestInterface` inmplementation) and your webhook secret:

```php
use Paddlin\Webhook\Webhook;

$webhook = new Webhook($request, 'YOUR_WEBHOOK_SECRET');
```

The first thing is to verify the webhook signature, to do so call the `verify` method:

```php
if ( $webhook->verify() ) {
    # The webhook is valid
}
```

And then get the related notification with the `getNotification` method:

```php
$notification = $webhook->getNotification();

switch($notification->event_type) {
    case 'subscription.created':
        # Process subscription.created event
    break;
}
```

For more details on the available events and its related notifications, check out the Paddle Webhooks reference: https://developer.paddle.com/webhooks/overview

### Licensing

This software is released under the MIT license.

Copyright © 2024 biohzrdmx

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

### Credits

**Lead coder:** biohzrdmx [github.com/biohzrdmx](http://github.com/biohzrdmx)
