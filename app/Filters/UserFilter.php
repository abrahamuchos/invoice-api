<?php

namespace App\Filters;

use App\Filters\ApiFilter;

class UserFilter extends ApiFilter
{
    protected array $safeParams = [
        'id' => ['eq'],
        'email' => ['eq'],
        'isAdmin' => ['eq'],
        'lastAccess' => ['eq', 'ne', 'lt', 'lte', 'gt', 'gte'],
        'createdAt' => ['eq', 'ne', 'lt', 'lte', 'gt', 'gte'],
        'updatedAt' => ['eq', 'ne', 'lt', 'lte', 'gt', 'gte'],
    ];

    protected array $columnMap = [
        'isAdmin' => 'is_admin',
        'lastAccess' => 'last_access',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];

    protected array $operatorMap = [
        'eq' => '=',
        'ne' => '!=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>='
    ];
}
