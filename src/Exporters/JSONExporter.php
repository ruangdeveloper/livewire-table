<?php

namespace RuangDeveloper\LivewireTable\Exporters;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use RuangDeveloper\LivewireTable\Interfaces\ExporterInterface;
use stdClass;

class JSONExporter implements ExporterInterface
{
    protected string $fileName = 'export.json';
    protected string $label = 'JSON';

    public function __construct(?string $fileName = null)
    {
        $this->fileName = $fileName;
    }

    public static function make(?string $fileName = null): JSONExporter
    {
        return new static($fileName);
    }

    public function fileName(string $fileName): JSONExporter
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function label(string $label): JSONExporter
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
        return 'json';
    }

    public function execute(array $columns, LengthAwarePaginator|Paginator|Collection|array $data): mixed
    {
        $filename = $this->fileName ?? 'export.json';
        $handle = fopen($filename, 'w+');
        $jsonData = [];

        foreach ($data as $index => $item) {
            $row = new stdClass();
            foreach ($columns as $column) {
                $renderer = $column->getExportRenderer();
                $row->{$column->getName()} = call_user_func($renderer, $item, $index);
            }
            $jsonData[$index] = $row;
        }

        fwrite($handle, json_encode($jsonData));

        fclose($handle);

        return response()->download($filename)->deleteFileAfterSend(true);
    }
}
