<?php

namespace RuangDeveloper\LivewireTable\Exporters;

use RuangDeveloper\LivewireTable\Interfaces\ExporterInterface;

class CSVExporter implements ExporterInterface
{
    protected string $fileName = 'export.csv';

    public function __construct(?string $fileName = null)
    {
        $this->fileName = $fileName;
    }

    public static function make(?string $fileName = null): CSVExporter
    {
        return new static($fileName);
    }

    public function fileName(string $fileName): CSVExporter
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getLabel(): string
    {
        return 'CSV';
    }

    public function getName(): string
    {
        return 'csv';
    }

    public function execute(array $columns, \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Contracts\Pagination\Paginator|\Illuminate\Database\Eloquent\Collection|array $data): mixed
    {
        $filename = $this->fileName ?? 'export.csv';
        $handle = fopen($filename, 'w+');
        $headers = collect($columns)->map(function ($column) {
            return $column->getLabel();
        })->toArray();

        fputcsv($handle, $headers);

        foreach ($data as $index => $item) {
            $row = [];
            foreach ($columns as $column) {
                $renderer = $column->getExportRenderer();
                $row[] = call_user_func($renderer, $item, $index);
            }
            fputcsv($handle, $row);
        }

        fclose($handle);

        return response()->download($filename)->deleteFileAfterSend(true);
    }
}
