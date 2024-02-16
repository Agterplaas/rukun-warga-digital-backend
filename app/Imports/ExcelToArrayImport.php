<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExcelToArrayImport implements ToArray, WithHeadingRow
{
    protected $data = [];

    public function array(array $row)
    {
        $this->data[] = $row;

        return $this;
    }

    public function getArrayData()
    {
        return $this->data;
    }
}
