<?php

namespace RuangDeveloper\LivewireTable\Exporters;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use RuangDeveloper\LivewireTable\Interfaces\ExporterInterface;

class XLSXExporter implements ExporterInterface
{
    private string $fileName = 'export.xlsx';
    private string $sheetName = 'data';
    private bool $withHeader = true;
    private string $label = 'XLSX';
    private string $name = 'xlsx';

    public function __construct(?string $fileName = null)
    {
        $this->fileName = $fileName;
    }

    public static function make(?string $fileName = null): self
    {
        return new static($fileName);
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function setSheetName(string $sheetName): self
    {
        $this->sheetName = $sheetName;

        return $this;
    }

    public function setWithHeader(bool $withHeader): self
    {
        $this->withHeader = $withHeader;

        return $this;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
