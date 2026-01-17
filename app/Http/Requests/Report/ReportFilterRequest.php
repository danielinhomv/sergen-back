<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;

class ReportFilterRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Fecha
            'today' => ['nullable', 'boolean'],
            'date_start' => ['nullable', 'date'],
            'date_end' => ['nullable', 'date'],

            // Identificador explícito del filtro de fecha (opcional)
            'filter_type' => ['nullable', 'in:none,today,from,until,range'],

            'property_id' => ['nullable', 'integer', 'min:1'],
            'control_id'  => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'filter_type.in' => 'filter_type debe ser: none, today, from, until o range.',
        ];
    }

    /**
     * Normaliza filtros.
     * - Si viene filter_type se respeta.
     * - Si no, se deduce.
     */
    public function filters(): array
    {
        $today = (bool) $this->input('today', false);
        $start = $this->input('date_start');
        $end   = $this->input('date_end');

        $filterType = $this->input('filter_type');

        if (!$filterType) {
            $filterType = match (true) {
                $today => 'today',
                ($start && $end) => 'range',
                (bool)$start => 'from',
                (bool)$end => 'until',
                default => 'none',
            };
        }

        // Si filter_type=today, ignoramos fechas
        if ($filterType === 'today') {
            $today = true;
            $start = null;
            $end = null;
        }

        return [
            'filter_type' => $filterType,
            'today' => $today,
            'date_start' => $start,
            'date_end' => $end,
            'property_id' => $this->input('property_id'),
            'control_id'  => $this->input('control_id'),
        ];
    }
}
