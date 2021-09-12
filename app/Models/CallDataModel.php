<?php
namespace App\Models;

use CodeIgniter\Model;

class CallDataModel extends Model
{

  protected $table = "call_data";

  protected $primaryKey = "id";

  protected $useAutoIncrement = true;

  protected $returnType = "array";

  protected $userSoftDeletes = false;

  protected $allowedFields = [
    'billed_account_id',
    'bill_id',
    'user_id',
    'call_date',
    'call_time',
    'real_duration',
    'billed_duration',
    'call_type'
  ];

  protected $useTimeStamps = false;

}
