<?php

namespace RuangDeveloper\LivewireTable\Interfaces;

use Closure;

interface BulkActionInterface
{
    public static function make(string $name, string $label): BulkActionInterface;
    public function setHandler(Closure $handler): BulkActionInterface;
    public function setConfirmationMessage(string $confirmationMessage): BulkActionInterface;
    public function getName(): string;
    public function getLabel(): string;
    public function getHandler(): Closure;
    public function getConfirmationMessage(): string;
}
