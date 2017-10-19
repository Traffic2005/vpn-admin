<?php namespace App\Managers;

/**
 * Created by PhpStorm.
 * User: traffic2005
 * Date: 13.10.17
 * Time: 12:54
 */


class ReportManager extends BaseManager
{
    /**
     * @param $month
     *
     * @return array
     */
    public function getReports($month)
    {
        list($startDate, $finishDate) = $this->setDate($month);
        $abuseUser    = array();
        $abuseCompany = \Transfers::model()->getAbuseCompany($startDate, $finishDate);

        foreach ($abuseCompany as $company) {
            $abuseUser[] = \Transfers::model()->getAbuseUser($company['id'], $startDate, $finishDate);
        }

        return ['abuseUsers' => $abuseUser, 'abuseCompany' => $abuseCompany];
    }

    /**
     * @return array
     */
    private function setDate($month)
    {
        $dateTime = new \DateTime('now');
        $dateTime->setDate($dateTime->format('Y'), $month, $dateTime->format('d'));
        $dateTime->modify('first day of this month');
        $startDate = $dateTime->format('Y-m-d');
        $dateTime->modify('last day of this month');
        $finishDate = $dateTime->format('Y-m-d');

        return array($startDate, $finishDate);
    }
}
