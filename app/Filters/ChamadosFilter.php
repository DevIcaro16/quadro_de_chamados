<?php

namespace App\Filters;

use DeepCopy\Exception\PropertyException;
use Illuminate\Http\Request;

class ChamadosFilter extends Filter
{
    protected array $allowedOperatorsFields = [
        'tipo' => ['eq', 'ne', 'in'],
        'local' => ['eq', 'ne', 'in'],
        'prioridade' => ['eq', 'ne', 'in'],
        'status' => ['eq', 'ne'],
        'chamados_data' => ['gt', 'eq', 'lt', 'gte', 'lte', 'ne'],
    ];

}
