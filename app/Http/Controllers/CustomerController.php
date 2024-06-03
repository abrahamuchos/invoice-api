<?php

namespace App\Http\Controllers;

use App\Exceptions\DeleteException;
use App\Exceptions\StoreException;
use App\Exceptions\UpdateException;
use App\Filters\CustomerFilter;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerCollection;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $storeException = new StoreException('Customer could not be saved', 10103);
            return $storeException->render($e->getMessage());
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
           return response()->json([], 201);
       }else{
           $updateException = new UpdateException('Customer could not be updated', 10203);
           return $updateException->render(null, 'Please check ID customer and try again');
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
        if(!Auth::user()->tokenCan('delete')){
            return response()->json([], 401);
        }

        $wasDeleted = $customer->delete();

        if ($wasDeleted) {
            return response()->json();
        } else {
            $deleteException = new DeleteException('Customer could not be deleted', 10103);
            return $deleteException->render();
        }
    }
}
