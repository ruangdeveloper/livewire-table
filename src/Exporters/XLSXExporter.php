<?php

namespace RuangDeveloper\LivewireTable\Exporters;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use RuangDeveloper\LivewireTable\Interfaces\ExporterInterface;

class XLSXExporter implements ExporterInterface
{
    protected string $fileName = 'export.xlsx';
    protected string $sheetName = 'data';
    protected bool $withHeader = true;
    protected string $label = 'XLSX';

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

    public function sheetName(string $sheetName): XLSXExporter
    {
        $this->sheetName = $sheetName;

        return $this;
    }

    public function withHeader(bool $withHeader): XLSXExporter
    {
        $this->withHeader = $withHeader;

        return $this;
    }

    public function label(string $label): XLSXExporter
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
        return 'xlsx';
    }

    public function execute(array $columns, \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Contracts\Pagination\Paginator|\Illuminate\Database\Eloquent\Collection|array $data): mixed
    {
        $filename = $this->fileName ?? 'export.xlsx';

        $spreadsheet = new Spreadsheet();
        $activeSheet = $spreadsheet->getActiveSheet();
        $activeSheet->setTitle($this->sheetName ?? 'data');

        if ($this->withHeader) {
            $activeSheet->fromArray(array_map(function ($column) {
                return $column->getLabel();
            }, $columns));
        }

        foreach ($data as $index => $item) {
            $row = [];
            foreach ($columns as $column) {
                $renderer = $column->getExportRenderer();
                $row[] = call_user_func($renderer, $item, $index);
            }
            $activeSheet->fromArray($row, null, 'A' . ($index + ($this->withHeader ? 2 : 1)));
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);

        return response()->download($filename)->deleteFileAfterSend(true);
    }
}
