<?php
namespace mhndev\asmari;

use DateTime;

/**
 * Interface iAsmariClient
 * @package mhndev\asmari
 */
interface iAsmariClient
{


    /**
     * @param GetPriceDataObject $get_price_data_object
     * @return mixed
     */
    function getPrice(GetPriceDataObject $get_price_data_object);


    /**
     * @param IssueDataObject $issue_data_object
     * @return mixed
     */
    function issue(IssueDataObject $issue_data_object);


    /**
     * @param int $country_id
     * @return array
     */
    function getZoneByCountry(int $country_id);

}
