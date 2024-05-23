<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ApiFilter
{

    protected array $safeParams = [];
    protected array $columnMap = [];
    protected array $operatorMap = [];

    /**
     * Transform request to where clausules
     * @param Request $request
     *
     * @return array
     */
    public function transform(Request $request): array
    {
        $eloQuery = [];
        foreach ($this->safeParams as $param => $operators){
            $query = $request->query($param);
            if(!isset($query)){
                continue;
            }
            $column = $this->columnMap[$param] ?? $param;
            foreach ($operators as $operator){
                if(isset($query[$operator])){
                    $eloQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }

        return $eloQuery;
    }
}
