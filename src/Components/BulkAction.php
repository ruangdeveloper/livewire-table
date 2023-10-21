<?php

namespace RuangDeveloper\LivewireTable\Components;

use Closure;
use RuangDeveloper\LivewireTable\Interfaces\BulkActionInterface;

class BulkAction implements BulkActionInterface
{
    private string $name;
    private string $label;
    private Closure $handler;
    private string $confirmationMessage = 'Are you sure?';

    private function __construct(string $name, string $label)
    {
        $this->name = $name;
        $this->label = $label;
    }

    public static function make(string $name, string $label): self
    {
        return new static($name, $label);
    }

    public function setHandler(Closure $handler): self
    {
        $this->handler = $handler;

        return $this;
    }

    public function setConfirmationMessage(string $confirmationMessage): self
    {
        $this->confirmationMessage = $confirmationMessage;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getHandler(): Closure
    {
        return $this->handler;
    }

    public function getConfirmationMessage(): string
    {
        return $this->confirmationMessage;
    }
}
