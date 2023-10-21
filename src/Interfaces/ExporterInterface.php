<?php

namespace RuangDeveloper\LivewireTable\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

interface ExporterInterface
{
    public function setLabel(string $label): ExporterInterface;
    public function setName(string $name): ExporterInterface;
    public function getLabel(): string;
    public function getName(): string;
    public function execute(array $columns, LengthAwarePaginator|Paginator|Collection|array $data): mixed;
}
