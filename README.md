# PayU
This is small Nette Framework wrapper for PayU gateway.

## Installation
The easiest way to install library is via Composer.

````sh
$ composer require lzaplata/payu: dev-master
````
or edit `composer.json` in your project

````json
"require": {
        "lzaplata/payu": "dev-master"
}
````

You have to register the library as extension in `config.neon` file.

````neon
extensions:
        payu: LZaplata\PayU\DI\Extension
````

Now you can set parameters...

````neon
payu:
        posId           : *
        clientId        : *
        clientSecret    : *
        key2            : *
        sandbox         : true
````

...and autowire library to presenter

````php
use LZaplata\PayU\Service;

/** @var Service @inject */
public $payu;
````
## Usage
In first step you must create order instantion.

````php
$order = $this->payu->createOrder([
        "description" => $description,          
        "currencyCode" => $currency,            
        "totalAmount" => $price,                    // order price in lowest currency unit (1 CZK = 100)
        "extOrderId" => $id,                        // eshop unique id
        "notifyUrl" => $notifyUrl,                  // url form sending notifications from PayU  
        "continueUrl" => $continueUrl,              // url to redirect after successful payment     
        "products" => [
                0 => [
                        "name" => $productName,
                        "unitPrice" => $unitPrice,  // product price in lowest currency unit (1 CZK = 100)
                        "quantity" => $quantity
                ]
        ],
        "buyer" => [
                "email" => $email,
                "phone" => $phone,
                "firstName" => $name,
                "lastName" => $surname
        ]
]);
````

Second step decides if creating order is successful...

````php
try {
        $response = $this->payu->pay($order);
} catch (\OpenPayU_Exception $e) {
        print $e->getMessage();
}
````

...and finally you can redirect to gateway.

````php
$this->sendResponse($response);
````