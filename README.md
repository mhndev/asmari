### Asmari Company PHP API Client

you can find usage of this library down here,

```php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "vendor/autoload.php";

$wsdl = "http://example.com/wrapper/travel/soap/?wsdl";
$session_id = "xyz";

$asmari_client = new \mhndev\asmari\AsmariSoapClient($wsdl, $session_id);


#### 1) method get_zone_by_country

for($i=1; $i<=113; $i++) {
    $res = $asmari_client->getZoneByCountry($i);
    $returned_zones[] = $res;
    $zone_countries[$res][] = $i;
}


#### 2) method get_price

$get_price_data_object = (new \mhndev\asmari\GetPriceDataObject())
    ->setBirthDate(DateTime::createFromFormat('Y-m-d', '2011-05-01'))
    ->setZoneId(1)
    ->setDurationId(2);

$res = $asmari_client->getPrice($get_price_data_object);


var_dump($res);
die();




#### 4) method issue


$issue_data_object = (new \mhndev\asmari\IssueDataObject())
    ->setBirthDate(DateTime::createFromFormat('Y-m-d', '2011-05-01'))
    ->setZoneId(1)
    ->setDurationId(2)
    ->setFirstName('Majid')
    ->setLastName('Abdolhosseini')
    ->setPassportNumber('J97634522')
    ->setNationalCode('0014297884');
$res = $asmari_client->issue($issue_data_object);

var_dump($res);
die();



```
