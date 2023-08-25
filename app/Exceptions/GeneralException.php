<?php

namespace App\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

class GeneralException extends Exception
{
    private int $codeException;
    private string $module;

    #[Pure] public function __construct(string $message, int $code = 400, string $module = "DEFAULT_MODULE", Exception $previous = null) {
        $this->codeException = $code;
        $this->module = $module;
        parent::__construct($message, $code, $previous);
    }

    public function __toString(): string {
        return " -> [{$this->code}]: {$this->message} - {$this->line} - {$this->file}";
    }

    public function getAllInformation(): array {
        return [
            "code" => $this->getCode(),
            "module" => $this->getModule(),
            "message" => $this->getMessage(),
            "line" => $this->getLine(),
            "file" => $this->getFile(),
            "trace" => $this->getTraceAsString(),
        ];
    }

    public function getCodeException(): int
    {
        return $this->codeException;
    }

    public function getModule(): string
    {
        return $this->module;
    }

    public function defaultFunction(): string {
        return "Una función personalizada para este tipo de excepción :v";
    }

}
