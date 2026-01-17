<?php

namespace App\Services\Report\Contracts;

interface ReportStrategy
{
    public function name(): string;

    /**
     * Validación específica de la etapa (además de la validación común).
     * Retorna null si ok, o array con status/code/message si error.
     */
    public function validate(array $filters): ?array;

    /**
     * Obtiene datos base (puede devolver rows + otros datos).
     */
    public function fetch(array $filters);

    /**
     * Construye el payload final (summary, distributions, details, etc.)
     */
    public function build(array $filters, $fetched): array;
}
