<?php

namespace App\Filters;

class InvoiceFilter extends ApiFilter
{
    protected array $safeParams = [
        'customerId' => ['eq'],
        'invoiceNumber' => ['eq'],
        'uuid' => ['eq'],
        'amount' => ['eq', 'gt', 'lt', 'lte', 'gte'],
        'status' => ['eq', 'ne'],
        'billedDated' => ['eq', 'gt', 'lt', 'lte', 'gte'],
        'paidDated' => ['eq', 'gt', 'lt', 'lte', 'gte'],
    ];

    protected array $columnMap = [
        'customerId' => 'customer_id',
        'invoiceNumber' => 'invoice_number',
        'billedDated' => 'billed_dated',
        'paidDated' => 'paid_dated',
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
