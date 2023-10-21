<?php

namespace RuangDeveloper\LivewireTable\Exporters;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use RuangDeveloper\LivewireTable\Interfaces\ExporterInterface;

class CSVExporter implements ExporterInterface
{
    private string $fileName = 'export.csv';
    private string $delimiter = ',';
    private bool $withHeader = true;
    private string $label = 'CSV';
    private string $name = 'csv';


    public function __construct(?string $fileName = null)
    {
        $this->fileName = $fileName;
    }

    public static function make(?string $fileName = null): self
    {
        return new static($fileName);
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function setDelimiter(string $delimiter): self
    {
        $this->delimiter = $delimiter;

        return $this;
    }

    public function setWithHeader(bool $withHeader): self
    {
        $this->withHeader = $withHeader;

        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function execute(array $columns, LengthAwarePaginator|Paginator|Collection|array $data): mixed
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
