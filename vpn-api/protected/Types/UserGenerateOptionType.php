<?php
/**
 * Created by PhpStorm.
 * User: traffic2005
 * Date: 17.10.17
 * Time: 14:53
 */

namespace App\Types;



class UserGenerateOptionType extends AbstractType
{
    /** @var  \User */
    private $user;


    private $allUserTransfer;


    private $monthsTransfers;

    /**
     * @return \User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \User $user
     *
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAllUserTransfer()
    {
        return $this->allUserTransfer;
    }

    /**
     * @param $allUserTransfer
     *
     * @return $this
     */
    public function setAllUserTransfer($allUserTransfer)
    {
        $this->allUserTransfer = $allUserTransfer;
        return $this;
    }

    /**
     * @return array
     */
    public function getMonthsTransfers()
    {
        return $this->monthsTransfers;
    }

    /**
     * @param $monthsTransfers
     *
     * @return $this
     */
    public function setMonthsTransfers($monthsTransfers)
    {
        $this->monthsTransfers = $monthsTransfers;
        return $this;
    }



}