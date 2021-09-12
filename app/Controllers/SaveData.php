<?php

namespace App\Controllers;

class SaveData extends BaseController
{
  const UNWANTED = ['Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C',
  'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O',
  'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a',
  'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
  'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y','Ğ'=>'G', 'İ'=>'I', 'Ş'=>'S', 'ğ'=>'g',
  'ı'=>'i', 'ş'=>'s', 'ü'=>'u','ă'=>'a', 'Ă'=>'A', 'ș'=>'s', 'Ș'=>'S', 'ț'=>'t', 'Ț'=>'T'];
  protected string $fileName;

  public function __construct(string $fileName = null)
  {
    $this->fileName = $fileName;
  }


  public function uploadFile()
  {
    set_time_limit(0);
    $db      = \Config\Database::connect();
    $builder = $db->table('call_data');
    // we truncate table before inderting
    $builder->truncate();
    if (!is_file($this->fileName)) {
      echo $this->fileName . " doesNot exists.";
      return false;

    }

    if (($handle = fopen($this->fileName, "r")) ===  false) {
      throw new OpeningFileFailedException('error: we can not open file : {$fileName}');
      return false;
    }

    $cpt = 0;
    $value = "";
    $cptfail= 0;
    while (($line =fgetcsv($handle, 0, ";")) !== false) {
      $values = $this->checkLine($line);
      if (!empty($values)) {
        $data[] = [
         'billed_account_id'  => $values[0],
         'bill_id'            => $values[1],
         'user_id'            => $values[2],
         'call_date'          => $values[3],
         'call_time'          => $values[4],
         'real_duration'      => $values[5],
         'billed_duration'    => $values[6],
         'call_type'          => $values[7]
       ];

       $cpt++;
     } else {

       $cptfail++;
     }

     if ($cpt >= 10000) {
       $builder->insertBatch($data);
       var_dump($cpt);
       var_dump($cptfail);
       $data = [];
       $cpt = 0;
     }
    }

    // the last lines to insert
    if (!empty($data)) {
      $builder->insertBatch($data);
    }

  }


  private function checkLine(array $line)
  {
    if (count($line) != 8) {
      return [];
    }

    $line = $this->cleanLine($line);
    $values = [];
    foreach ($line as $key => $value) {
      $value = utf8_encode($value);
        switch ($key) {
          case 0:
          case 1:
          case 2:
            if (!is_numeric($value)) {
              return [];
            }
            $values[] = $value ;
          break;
          case 3:
            if (!preg_match("/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/" , $value )) {
              return [];
            }
            $value = str_replace("/", "-", $value);
            $value = date("Y-m-d", strtotime($value));
            $values[] = $value;
          break;
          case 4:
            if (!preg_match("/^[0-9]{2}:[0-9]{2}:[0-9]{2}$/" , $value )) {
              return [];
            }
            $values[] = $value;
          break;
          case 5:
          case 6:
            $values[] = $value;
          break;
          case 7:
            if (!is_string($value)) {
              return [];
            }
            $values[] = $value;
          break;
          default:
          return [];
       }
    }
   return $values;
 }

 private function cleanLine(array $line)
 {
     $line = implode('|', $line);
     $line = utf8_encode($line);
     $line = htmlentities($line, ENT_QUOTES | ENT_HTML5, "UTF-8");
     $line = str_ireplace('&rsquo;', "'", $line);
     $line = str_ireplace('&lsquo;', "'", $line);
     $line = str_ireplace('&ldquo;', '"', $line);
     $line = str_ireplace('&rdquo;', '"', $line);
     $line = html_entity_decode($line, ENT_QUOTES | ENT_HTML5);
     $line = str_ireplace('"', '', $line);
     $line = str_ireplace("'", '', $line);
     $line = strtolower($line);
     $line = strtr($line, static::UNWANTED);
     $line = explode('|', $line);

     return $line;
 }

}
