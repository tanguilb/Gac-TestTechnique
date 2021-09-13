<?php

namespace App\Controllers;

use App\Models\CallDataModel;


class Start extends BaseController
{
    public function index()
    {
      helper(['form']);
      if ($this->request->getMethod() == 'post') {
        $rules = [
          'callFile' => [
            'rules' => 'uploaded[callFile]|max_size[callFile, 9000000]',
            'label' => 'The file'
          ]
        ];

        if ($this->validate($rules)) {
          $file = $this->request->getFile('callFile');
          if ($file->isValid() && !$file->hasMoved() && $file->getExtension() == "csv") {
            if (file_exists('../public/tickets.' . $file->getExtension())) {
              unlink('../public/tickets.' . $file->getExtension());
            }
            $file->move('../public', 'tickets.' . $file->getExtension());

            $saveData = new SaveData('../public/tickets.csv');

            //insert data in bdd
            $saveData->uploadFile();

            $analyseData = new AnalyseData();
            //get data to show
            $data = $analyseData->getData();

            $datas = ['data' => $data];
            return view('analysedata', $datas);
          }

          $filePath = $file->getPath();
          $fileName = $file->getName();
        }

      }
        return view('submitdata');
    }


}
