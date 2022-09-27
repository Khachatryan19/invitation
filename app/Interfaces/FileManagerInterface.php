<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface FileManagerInterface
{
    public function reader();

    public function convertToJson(Collection $data);

}