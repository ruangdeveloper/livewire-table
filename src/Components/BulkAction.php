<?php

namespace RuangDeveloper\LivewireTable\Components;

use Closure;

class BulkAction
{
    protected string $name;
    protected string $label;
    protected Closure $handler;
    protected string $confirmationMessage = 'Are you sure?';

    private function __construct(string $name, string $label)
    {
        $this->name = $name;
        $this->label = $label;
    }

    public static function make(string $name, string $label): BulkAction
    {
        return new static($name, $label);
    }

    public function handler(Closure $handler): BulkAction
    {
        $this->handler = $handler;

        return $this;
    }

    public function confirmationMessage(string $confirmationMessage): BulkAction
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
