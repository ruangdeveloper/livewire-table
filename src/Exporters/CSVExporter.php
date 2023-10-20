<?php

namespace RuangDeveloper\LivewireTable\Exporters;

use RuangDeveloper\LivewireTable\Interfaces\ExporterInterface;

class CSVExporter implements ExporterInterface
{
    protected string $fileName = 'export.csv';
    protected string $delimiter = ',';
    protected bool $withHeader = true;
    protected string $label = 'CSV';

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

    public function delimiter(string $delimiter): CSVExporter
    {
        $this->delimiter = $delimiter;

        return $this;
    }

    public function withHeader(bool $withHeader): CSVExporter
    {
        $this->withHeader = $withHeader;

        return $this;
    }

    public function label(string $label): CSVExporter
    {
        $this->label = $label;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getName(): string
    {
        return 'csv';
    }

    public function execute(array $columns, \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Contracts\Pagination\Paginator|\Illuminate\Database\Eloquent\Collection|array $data): mixed
    {
        $filename = $this->fileName ?? 'export.csv';
        $handle = fopen($filename, 'w+');

        if ($this->withHeader) {
            $headers = collect($columns)->map(function ($column) {
                return $column->getLabel();
            })->toArray();
            fputcsv($handle, $headers, $this->delimiter ?? ',');
        }

        foreach ($data as $index => $item) {
            $row = [];
            foreach ($columns as $column) {
                $renderer = $column->getExportRenderer();
                $row[] = call_user_func($renderer, $item, $index);
            }
            fputcsv($handle, $row, $this->delimiter ?? ',');
        }

        fclose($handle);

        return response()->download($filename)->deleteFileAfterSend(true);
    }
}
