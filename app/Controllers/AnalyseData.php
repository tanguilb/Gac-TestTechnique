<?php

namespace App\Controllers;


class analyseData extends BaseController
{
    protected $db;

    public function __construct()
    {
      $this->db = \Config\Database::connect();
    }

    public function getData()
    {
      $totalRealCallFromDate = $this->getTotalRealCallFromDate();
      $topTen = $this->getTopTenDataBilled();
      $totalSms = $this->totalSmsSend();
      return [
        "totalCall" => $totalRealCallFromDate ?? null,
        "topTen"    => $topTen ?? null,
        "totalSms"  => $totalSms ?? null
      ];
    }

    private function getTotalRealCallFromDate()
    {
      $query = $this->db->query('
        SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(real_duration))) AS TotalTime
        FROM call_data where call_date >= "2012-02-15"
        and call_type like "appel%"');
      $result = $query->getResultArray()[0] ?? null;
      return $result['TotalTime'];

    }

    private function getTopTenDataBilled()
    {
      $query = $this->db->query('
        SELECT user_id,ANY_VALUE(call_time) as callTime,
        MAX(billed_duration) as billedDuration, ANY_VALUE(call_type) as callType
        FROM call_data WHERE call_type like "connexion%"
        AND (call_time < "08:00:00" or call_time > "18:00:00")
        GROUP BY user_id ORDER BY MAX(billed_duration) DESC
        LIMIT 10;
      ');
      $result = $query->getResultArray();
      return $result;
    }

    private function totalSmsSend()
    {
      $query = $this->db->query('
        SELECT count(id) as totalSms FROM call_data
        WHERE call_type LIKE "%envoi de sms%";
      ');
      $result = $query->getResultArray()[0] ?? null;
      return $result['totalSms'];
    }
}
