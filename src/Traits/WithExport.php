<?php

namespace RuangDeveloper\LivewireTable\Traits;

use RuangDeveloper\LivewireTable\Interfaces\ExporterInterface;

trait WithExport
{
    public function exporters(): array
    {
        return [];
    }

    public function exportLabel(): string
    {
        return 'Export';
    }

    public function handleExport(string $exporterName)
    {
        $exporter = collect($this->exporters())
            ->filter(fn ($exporter) => $exporter->getName() === $exporterName)
            ->first();

        if (!$exporter) {
            throw new \Exception('Exporter not found');
        }

        if ($exporter instanceof ExporterInterface === false) {
            throw new \Exception('Exporter must be instance of ' . ExporterInterface::class);
        }

        $columns = collect($this->columns())
            ->filter(fn ($column) => $column->isHidden() === false)
            ->toArray();

        if (in_array(WithColumnSelection::class, class_uses($this))) {
            $columns = $this->getSelectedColumns();
        }

        return $exporter->execute($columns, $this->data());
    }
}
