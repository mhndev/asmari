<?php
namespace mhndev\asmari;

use DateTime;
use Exception;
use farhadi\IntlDateTime;

/**
 * Class GetPriceDataObject
 * @package mhndev\asmari
 */
class GetPriceDataObject
{


    /**
     * @var DateTime
     */
    protected $birth_date;

    /**
     * 1392/01/04
     * @var string
     */
    protected $birth_date_string;

    /**
     * @var int
     */
    protected $zone_id;

    /**
     * @var int
     */
    protected $duration_id;


    /**
     * @return DateTime
     */
    public function getBirthDate()
    {
        return $this->birth_date;
    }

    /**
     * @param DateTime $birth_date
     * @return GetPriceDataObject
     * @throws Exception
     */
    public function setBirthDate(DateTime $birth_date)
    {
        if(!class_exists(IntlDateTime::class)) {
            throw new Exception(
                'This package uses the farhadi/intl-datetime package,
                 if you want to call setBirthDate method install the mentioned packages,
                  or you can just simply call setBirthDateJalaliString method and just give it jalali date method'
            );
        }

        $this->birth_date = $birth_date;

        $date = new IntlDateTime(
            $time = $birth_date->getTimestamp(),
            $timezone = 'Asia/Tehran',
            $calendar = 'persian',
            $locale = 'fa_IR'
        );

        $this->birth_date_string = $date->format('y/MM/dd');

        return $this;
    }

    /**
     * @example 1390/02/01
     *
     * @param string $birth_date_jalali_string
     * @return $this
     */
    public function setBirthDateJalaliString(string $birth_date_jalali_string)
    {
        $this->birth_date_string = $birth_date_jalali_string;

        return $this;
    }

    /**
     * @param string $birth_date_miladi_string
     * @return GetPriceDataObject
     * @throws Exception
     * @example 2012-02-5
     *
     */
    public function setBirthDateMiladiString(string $birth_date_miladi_string)
    {
        $this->birth_date = DateTime::createFromFormat('Y-m-d', $birth_date_miladi_string);

        if(!class_exists(IntlDateTime::class)) {
            throw new Exception(
                'This package uses the farhadi/intl-datetime package,
                 if you want to call setBirthDateMiladiString method install the mentioned packages,
                  or you can just simply call setBirthDateJalaliString method and just give it jalali date method'
            );
        }

        $date = new IntlDateTime(
            $time = $this->birth_date->getTimestamp(),
            $timezone = 'Asia/Tehran',
            $calendar = 'persian',
            $locale = 'fa_IR'
        );

        $this->birth_date_string = $date->format('y/MM/dd');

        return $this;
    }


    /**
     * @return int
     */
    public function getZoneId()
    {
        return $this->zone_id;
    }

    /**
     * @param int $zone_id
     * @return GetPriceDataObject
     */
    public function setZoneId($zone_id)
    {
        $this->zone_id = $zone_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getDurationId()
    {
        return $this->duration_id;
    }

    /**
     * @param int $duration_id
     * @return GetPriceDataObject
     */
    public function setDurationId($duration_id)
    {
        $this->duration_id = $duration_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getBirthDateString()
    {
        return $this->birth_date_string;
    }

    /**
     * @param string $birth_date_string
     * @return GetPriceDataObject
     */
    public function setBirthDateString($birth_date_string)
    {
        $this->birth_date_string = $birth_date_string;
        return $this;
    }



}
