<?php namespace App\Managers;

/**
 * Created by PhpStorm.
 * User: traffic2005
 * Date: 13.10.17
 * Time: 12:54
 */

use App\Types\UserGenerateOptionType;
use Faker\Factory;

class GeneratorManager extends BaseManager
{
    const TRANSFER_MIN = 100;
    const TRANSFER_MAX = 10995116277760;
    const PERIOD_MONTH = 6;
    const MIN_RECORD = 50;
    const MAX_RECORD = 500;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * UserManager constructor.
     *
     */
    public function __construct()
    {
        $this->faker = Factory::create();
    }


    /**
     * @return array
     */
    public function fillUser()
    {
        for ($i = 0; $i < 25; $i++) {
            $userModel             = new \User;
            $userModel->name       = $this->faker->name;
            $userModel->email      = $this->faker->email;
            $userModel->company_id = rand(1, 6);
            $userModel->created_at = $this->faker->dateTimeThisYear->format('Y-m-d H:i');
            $userModel->save();
        }
    }

    public function fillTransfers()
    {

        //cleat exist data
        \Transfers::model()->deleteAll();

        //divide the total periodMonth in months from the current date
        $months = [];

        //get current date object
        $date = new \DateTime();

        for ($i = 0; $i <= self::PERIOD_MONTH; $i++) {
            $monthClass             = new \stdClass();
            $monthClass->finishDate = new \DateTime('now');
            $monthClass->startDate  = $date->modify('-1 month');
            $months[]               = $monthClass;
        }

        /**
         * @var \User[] $users
         */
        $users          = \User::model()->findAll();
        $users2Generate = array();
        foreach ($users as $user) {
            $transferAmount         = $this->faker->numberBetween(self::MIN_RECORD, self::MAX_RECORD);
            $userGenerateOptionType = new UserGenerateOptionType();
            $users2Generate[]       = $userGenerateOptionType
                ->setUser($user)
                ->setAllUserTransfer($transferAmount)
                ->setMonthsTransfers($this->explodeTransfers($transferAmount,
                    self::PERIOD_MONTH
                )
                );


        }

        /** @var UserGenerateOptionType $userGenerateOption */
        foreach ($users2Generate as $userGenerateOption) {

            foreach ($userGenerateOption->getMonthsTransfers() as $key => $periodMonth) {
                for ($i = 1; $i <= $periodMonth; $i++) {
                    $transferModel              = new \Transfers();
                    $transferModel->user_id     = $userGenerateOption->getUser()->id;
                    $transferModel->created_at  = $this->faker->dateTimeBetween($months[$key]->startDate,
                        $months[$key]->finishDate
                    )->format('Y-m-d H:i');
                    $transferModel->amountBytes = $this->faker->numberBetween(self::TRANSFER_MIN, self::TRANSFER_MAX);
                    $transferModel->resource    = $this->faker->url;
                    $transferModel->save();
                }
            }
        }
        $count = \Transfers::model()->getTransferCount();
        return ($count['count'] < self::MIN_RECORD) ? false : true;
    }

    /**
     * @param $number
     * @param $parts
     *
     * @return array
     */
    private function explodeTransfers($number, $parts)
    {     
        for ($i = 1; $i < $parts; $i++)
        {
            $temp[$i] = $number - rand(1, $number);
            $number = $number - $temp[$i];
        }
        $temp[$parts] = $number;
        return $temp;
    }

    /**
     * @param $num
     *
     * @return float
     */
    private function reduce($num)
    {
        return round($num * 0.95, 0);
    }
}