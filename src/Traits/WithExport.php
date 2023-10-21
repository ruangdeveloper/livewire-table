<?php

namespace RuangDeveloper\LivewireTable\Traits;

use RuangDeveloper\LivewireTable\Interfaces\ExporterInterface;

trait WithExport
{
    public function getExporters(): array
    {
        return [];
    }

    public function getExportLabel(): ?string
    {
        return 'Export';
    }

    public function handleExport(string $exporterName)
    {
        $exporter = collect($this->getExporters())
            ->filter(fn ($exporter) => $exporter->getName() === $exporterName)
            ->first();

        if (!$exporter) {
            throw new \Exception('Exporter not found');
        }

        if ($exporter instanceof ExporterInterface === false) {
            throw new \Exception('Exporter must be instance of ' . ExporterInterface::class);
        }

        $columns = collect($this->getColumns())
            ->filter(fn ($column) => $column->isHidden() === false)
            ->toArray();

        if (in_array(WithColumnSelection::class, class_uses($this))) {
            $columns = $this->getSelectedColumns();
        }

        return $exporter->execute($columns, $this->getData());
    }
}
