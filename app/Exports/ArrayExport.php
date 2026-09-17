<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

class ArrayExport implements FromArray, WithTitle
{
    public function __construct(protected array $data, protected string $title)
    {
    }

    public function array(): array
    {
        return $this->data;
    }

    public function title(): string
    {
        return $this->title;
    }
}
