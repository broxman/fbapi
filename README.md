# Fusebill PHP Client Library

PHP Library for Fusebill API (https://developer.fusebill.com/v1.0/reference)


## Dependencies

PHP version >= 7 required.

The following PHP extensions are required:

* curl

## Install

Add Fusebill API to your `composer.json` file. 

```json
{
  "require": {
    "broxman/fbapi": "*"
  }
}
```

## Examples

### Initialize api 
```php
<?php
try {
    $fbl = new \Broxman\Fbapi\Fbapi('https://secure.fusebill.com/v1', 'APIKEY');
    // ...
}
catch (Exception $e){
    echo $e->getMessage() . "\n";
}
?>
```

### Create Customer 
```php
<?php
    $Customer = $fbl->postCustomers(null, null, [
        'firstName' => 'FirstName',
        'lastName' => 'LastName',
        'primaryEmail' => 'test@email.com',
        'primaryPhone' => '0000000',
        'reference' => '123456',
        'customerReference' => [
            'reference3' => 'Additional Reference Field',
        ],
    ]);
    print_r($Customer);
?>
```

### Activate Customer
```php
<?php
    $Customer = $fbl->postCustomerActivation(null, null, [
        "customerId" => 123456,
        "activateAllSubscriptions" => true,
        "activateAllDraftPurchases" => true,
        "temporarilyDisableAutoPost" => false,
    ]);
    print_r($Customer);
?>
```

### Update Customer
```php
<?php
    $Customer = $fbl->putcustomers(null, null, [
        'id' => 123456,
        'firstName' => 'FirstName',
        'lastName' => 'LastName',
        'primaryEmail' => 'test@email.com',
        'primaryPhone' => '0000000',
        'reference' => '123456',
        'customerReference' => [
            'reference3' => 'Additional Reference Field',
        ],
        'status' => 'Active',
    ]);
    print_r($Customer);
?>
```

### Get Customer
```php
<?php
    $Customer = $fbl->getcustomers(123456);
    print_r($Customer);
?>
```

### Create Purchase
```php
<?php
    $Purchase = $fbl->postPurchases(null, null, [
        'customerId' => 123456,
        'productId' => 123,
        'name' => 'Package Name',
        'quantity' => 1,
    ]);
    print_r($Purchase);
?>
```

### Finalize Purchase
```php
<?php
    $Purchase = $fbl->postPurchases('Purchase',[
//        'buyNow' => 'true',
        'preview' => 'false',
        'showZeroDollarCharges' => 'false',
        'temporarilyDisableAutoPost' => 'false',
    ], [
        'customerId' => 123456,
        'purchaseIds' => [
            12345,
        ],
        'invoiceCollectOptions' => [
            "useAnyAvailableFundsFirst" => 'true',
            "rollbackOnFailedPayment" => 'true',
            "paymentMethod" => "UseDefaultPaymentMethod",
//            "paymentMethodId" => '1234',
        ],
    ]);
    print_r($Purchase);
?>
```

### Remove Draft Purchase 
```php
<?php
    $fbl->deletePurchases(12345);
?>
```

### Get Customer's Invoices 
```php
<?php
    $Invoices = $fbl->getCustomers([123456, 'Invoices']);
    foreach ($Invoices as $j => $Invoice) {
        echo "{$j}. {$Invoice->id}\n";
    }
?>
```

### Get Invoice 
```php
<?php
    $Invoice = $fbl->getInvoices(1234567);
    print_r($Invoice);
?>
```

### Get Invoice as PDF 
```php
<?php
    $Invoicepdf = $fbl->getInvoices(['pdf', 1234567]);
    file_put_contents('invoice.pdf', $Invoicepdf);
?>
```

### Receive Responce as Array
```php
<?php
    // Create the handler
    $json_handler = new \Httpful\Handlers\JsonHandler(array('decode_as_array' => true));
    // Register it with Httpful
    \Httpful\Httpful::register('application/json', $json_handler);
?>
```

## Documentation

 * [Fusebill Official documentation](https://developer.fusebill.com/v1.0/reference)

## License

[The MIT License (MIT)](LICENSE.txt)
