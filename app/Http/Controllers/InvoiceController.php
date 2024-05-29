<?php

namespace App\Http\Controllers;

use App\Filters\InvoiceFilter;
use App\Http\Requests\BulkStoreRequest;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Http\Resources\InvoiceCollection;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoice
     *
     * @param Request $request
     *
     * @return InvoiceCollection
     */
    public function index(Request $request): InvoiceCollection
    {
        $filters = new InvoiceFilter();
        $queryItems = $filters->transform($request);
        $invoices = Invoice::where($queryItems);

        if ($request->query('includeCustomer')) {
            $invoices->with('customer');
        }


        return new InvoiceCollection($invoices->paginate()->appends($request->query()));
    }

    /**
     * Store a newly created invoice in storage.
     *
     * @param StoreInvoiceRequest $request
     *
     * @return JsonResponse|InvoiceResource
     */
    public function store(StoreInvoiceRequest $request): JsonResponse|InvoiceResource
    {
        try {
            $invoiceNumber = $this->_nextInvoiceNumber();
            $invoice = Invoice::create([
                'customer_id' => $request->input('customerId'),
                'invoice_number' => $invoiceNumber,
                'uuid' => (string)\Str::uuid(),
                'amount' => $request->input('amount'),
                'status' => $request->input('status'),
                'billed_dated' => $request->input('billedDated'),
                'paid_dated' => $request->input('paidDated'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Error store a invoice',
                'code' => 10101,
                'details' => $e->getMessage()
            ]);
        }

        return new InvoiceResource($invoice);
    }

    /**
     * @param BulkStoreRequest $request
     *
     * @return JsonResponse
     */
    public function bulkStore(BulkStoreRequest $request): JsonResponse
    {
        $bulk = collect($request->all())->map(function ($arr, $key) {
            $arr['invoice_number'] = $this->_nextInvoiceNumber();
            $arr['uuid'] = (string)\Str::uuid();
            return Arr::except($arr, ['customerId', 'billedDated', 'paidDated']);
        });

        try {
            $wasInserted = Invoice::insert($bulk->toArray());

        }catch (QueryException $e){
            return response()->json([
                'error' => true,
                'message' => 'Query Error insert a massive data (invoice)',
                'code' => 10104,
                'details' => $e->getMessage()
            ]);
        }

        if ($wasInserted) {
            return response()->json();
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Error insert a massive data (invoice)',
                'code' => 10103,
                'details' => null
            ]);
        }
    }

    /**
     * Display the specified invoice.
     *
     * @param Invoice $invoice
     *
     * @return InvoiceResource
     */
    public function show(Invoice $invoice): InvoiceResource
    {
        if (request()->query('includeCustomer')) {
            return new InvoiceResource($invoice->loadMissing('customer'));
        } else {
            return new InvoiceResource($invoice);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateInvoiceRequest $request
     * @param Invoice              $invoice
     *
     * @return JsonResponse
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice): JsonResponse
    {
        $wasUpdated = $invoice->update($request->all());

        if ($wasUpdated) {
            return response()->json();
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Error update a invoice',
                'code' => 10201,
                'details' => null
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Invoice $invoice
     *
     * @return JsonResponse
     */
    public function destroy(Invoice $invoice): JsonResponse
    {
        if(!Auth::user()->tokenCan('delete')){
            return response()->json([], 401);
        }

        $wasDeleted = $invoice->delete();

        if ($wasDeleted) {
            return response()->json();
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Error deleted a invoice',
                'code' => 10201,
                'details' => null
            ]);
        }
    }

    /**
     * Retrieve next value on invoice number
     * @return int
     */
    private function _nextInvoiceNumber(): int
    {
        return DB::select("select nextval('invoice_number_seq')")[0]->nextval;
    }
}



