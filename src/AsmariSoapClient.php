<?php
namespace mhndev\asmari;

use mhndev\asmari\Exception\ApiResponseConnectException;
use mhndev\asmari\Exception\APIResponseException;
use SoapClient;
use SoapFault;

/**
 * Class AsmariSoapClient
 * @package mhndev\asmari
 */
class AsmariSoapClient implements iAsmariClient
{

    /**
     * @var string
     */
    private $session_id;

    /**
     * @var string
     */
    private $wsdl_url;

    /**
     * @var SoapClient
     */
    private $soap_client = null;


    /**
     * AsmariSoapClient constructor.
     * @param string $wsdl_url
     * @param string $session_id
     */
    function __construct(string $wsdl_url, string $session_id)
    {
        $this->session_id = $session_id;
        $this->wsdl_url = $wsdl_url;
    }


    /**
     * @return SoapClient
     * @throws APIResponseException
     * @throws ApiResponseConnectException
     */
    private function getSoapClient()
    {
        if(! is_null($this->soap_client)) {
            return $this->soap_client;
        }

        try{
            $this->soap_client = new SoapClient(
                $this->wsdl_url,
                ['exception' => true, 'trace' => 1]
            );

            return $this->soap_client;
        }

        catch (\Exception $e) {

            if (
                get_class($e) == SoapFault::class &&
                strpos($e->getMessage(), "SOAP-ERROR: Parsing WSDL: Couldn't load from") !== false
            ) {
                throw new ApiResponseConnectException(
                    sprintf("Exception Class : %s , Exception Message is : %s", get_class($e), $e->getMessage())
                );
            }


            else {
                throw new APIResponseException(
                    sprintf("Exception Class : %s , Exception Message is : %s", get_class($e), $e->getMessage())
                );
            }
        }

    }

    /**
     * @param GetPriceDataObject $get_price_data_object
     * @return mixed
     * @throws APIResponseException
     */
    function getPrice(GetPriceDataObject $get_price_data_object)
    {
        $session_id = $this->session_id;
        $birth_date = $get_price_data_object->getBirthDateString();
        $zone_id = $get_price_data_object->getZoneId();
        $duration_id = $get_price_data_object->getDurationId();
        $discount_id = 0;

        $params = compact('session_id', 'birth_date', 'zone_id', 'duration_id', 'discount_id');

        try{
            $result = $this->getSoapClient()->get_price($params)->get_priceResult;
            $array_result = json_decode($result, true)[0];

            if($array_result['response_code'] != 0 ) {
                throw new APIResponseException(json_encode(['api_response' => $array_result, 'inputs' => $params]));
            }

            return ['premium' => $array_result['premium'], 'tax' => $array_result['tax']];
        }
        catch (\Exception $e) {

            if(get_class($e) != ApiResponseConnectException::class) {
                throw new APIResponseException(
                    sprintf("Exception Class : %s , Exception Message is : %s", get_class($e), $e->getMessage())
                );
            }
            else {
                $exception_class = get_class($e);

                throw new $exception_class(
                    sprintf("Exception Class : %s , Exception Message is : %s", get_class($e), $e->getMessage())
                );

            }
        }


    }

    /**
     * @param IssueDataObject $issue_data_object
     * @return array
     * @throws APIResponseException
     */
    function issue(IssueDataObject $issue_data_object)
    {
        $session_id = $this->session_id;

        $latin_first_name = $issue_data_object->getFirstName();
        $latin_last_name = $issue_data_object->getLastName();
        $passport_no = $issue_data_object->getPassportNumber();
        $national_code = $issue_data_object->getNationalCode();
        $birth_date = $issue_data_object->getBirthDateString();
        $zone_id = $issue_data_object->getZoneId();
        $duration_id = $issue_data_object->getDurationId();
        $discount_id = 0;

        $params = compact(
            'session_id',
            'birth_date',
            'zone_id',
            'duration_id',
            'discount_id',
            'latin_first_name',
            'latin_last_name',
            'passport_no',
            'national_code'
        );

        $result = $this->getSoapClient()->issue($params)->issueResult;

        $array_result = json_decode($result, true)[0];


        if(
            !array_key_exists('premium', $array_result) &&
            !array_key_exists('tax', $array_result) &&
            !array_key_exists('idinsu', $array_result)
        ) {
            throw new APIResponseException($result);
        }

        /*
         * @ example error
         *
         * array:2 [
         *     "response_code" => 2
         *     "message" => "مشخصات وارد شده فاقد نرخ در سیستم می باشد . با واحد فنی تماس بگیرید."
         *  ]
         *
         */

        return [
            'premium' => $array_result['premium'],
            'tax' => $array_result['tax'],
            'pid' => $array_result['pid'],
            'idinsu' => $array_result['idinsu']
        ];

    }

    /**
     * @param int $country_id
     * @return int
     * @throws APIResponseException
     * @throws ApiResponseConnectException
     */
    function getZoneByCountry(int $country_id)
    {
        $session_id = $this->session_id;

        $params = [
            'session_id' => $session_id ,
            'country_id' => $country_id
        ];

        $result = $this->getSoapClient()->get_zone_by_country($params)->get_zone_by_countryResult;

        return json_decode($result, true)[0]['zone'];
    }


}
