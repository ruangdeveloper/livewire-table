<?php

namespace RuangDeveloper\LivewireTable\Exporters;

use RuangDeveloper\LivewireTable\Interfaces\ExporterInterface;

class XLSXExporter implements ExporterInterface
{
    protected string $fileName = 'export.xlsx';

    public function __construct(?string $fileName = null)
    {
        $this->fileName = $fileName;
    }

    public static function make(?string $fileName = null): XLSXExporter
    {
        return new static($fileName);
    }

    public function fileName(string $fileName): XLSXExporter
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getLabel(): string
    {
        return 'XLSX';
    }

    public function getName(): string
    {
        return 'xlsx';
    }

    public function execute(array $columns, \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Contracts\Pagination\Paginator|\Illuminate\Database\Eloquent\Collection|array $data): mixed
    {
        $filename = $this->fileName ?? 'export.xlsx';

        // TODO: Implement XLSX Exporter
    }
}