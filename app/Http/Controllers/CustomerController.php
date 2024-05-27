<?php

namespace App\Http\Controllers;

use App\Filters\CustomerFilter;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerCollection;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customer.
     * @param Request $request
     *
     * @return CustomerCollection
     */
    public function index(Request $request): CustomerCollection
    {
        $filter = new CustomerFilter();
        $queryItems = $filter->transform($request);
        $customers = Customer::where($queryItems);

        if ($request->query('includeInvoices')) {
            $customers = $customers->with('invoices');
        }

        return new CustomerCollection($customers->paginate()->appends($request->query()));
    }

    /**
     * Store a newly created customer in storage
     * @param StoreCustomerRequest $request
     *
     * @return JsonResponse|CustomerResource
     */
    public function store(StoreCustomerRequest $request): JsonResponse|CustomerResource
    {
        try {
            $customer = Customer::create($request->all());

        } catch (\Exception $e){
            return response()->json([
                'error' => true,
                'message' => 'Error store a customer',
                'code' => 10100,
                'details' => $e->getMessage()
            ]);
        }

        return new CustomerResource($customer);
    }

    /**
     * Display the specified customer.
     * @param Customer $customer
     *
     * @return CustomerResource
     */
    public function show(Customer $customer): CustomerResource
    {
        if (request()->query('includeInvoices')) {
            return new CustomerResource($customer->loadMissing('invoices'));
        } else {
            return new CustomerResource($customer);
        }
    }

    /**
     * Update the customer resource in storage.
     * @param UpdateCustomerRequest $request
     * @param Customer              $customer
     *
     * @return JsonResponse
     */
    public function update(UpdateCustomerRequest $request, Customer $customer): JsonResponse
    {
       $wasUpdated = $customer->update($request->all());

       if($wasUpdated){
           return response()->json();
       }else{
           return response()->json([
               'error' => true,
               'message' => 'Error update a customer',
               'code' => 10200,
               'details' => null
           ]);
       }
    }

    /**
     * Remove the specified customer from storage.
     * @param Customer $customer
     *
     * @return JsonResponse
     */
    public function destroy(Customer $customer): JsonResponse
    {
        $wasDeleted =  $customer->delete();

        if($wasDeleted){
            return response()->json();
        }else{
            return response()->json([
                'error' => true,
                'message' => 'Error deleted a customer',
                'code' => 10200,
                'details' => null
            ]);
        }
    }
}
