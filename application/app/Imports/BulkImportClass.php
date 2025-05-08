<?php

namespace App\Imports;

use App\Models\Company;
use App\Models\Services;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BulkImportClass implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // dd($row);

        return new Services([
            'company' => $row['company_id'],
            'service_name' => $row['service_name'],
            'description' => $row['description'],
            'product_code' => $row['product_code'],
            'price' => $row['price'],
            'hour_session' => $row['hour_session'],
            'weekly_freq' => $row['weekly_freq'],
            'total_session' => $row['total_session'],
            'man_power' => $row['man_power'],
            'created_by' => Auth::user()->id,
            'created_by_name' => Auth::user()->first_name . " " . Auth::user()->last_name
        ]);
    }
}
