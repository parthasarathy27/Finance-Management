<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SalesInvoice;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Payment;

class SalesInvoiceController extends Controller
{
    public function index()
    {
        $invoices = SalesInvoice::with('customer', 'payments')->orderBy('invoice_date', 'desc')->get();
        return view('sales.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('sales.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|string|unique:sales_invoices',
            'customer_id'    => 'required|exists:customers,id',
            'invoice_date'   => 'required|date',
            'due_date'       => 'required|date|after_or_equal:invoice_date',
            'notes'          => 'nullable|string',
            'items'                    => 'required|array|min:1',
            'items.*.product_id'       => 'required|exists:products,id',
            'items.*.quantity'         => 'required|integer|min:1',
            'items.*.unit_price'       => 'required|numeric|min:0',
            'items.*.tax_rate'         => 'required|numeric|min:0|max:100',
            'items.*.discount_rate'    => 'required|numeric|min:0|max:100',
        ]);

        DB::transaction(function () use ($request) {
            $subtotal = $taxAmount = $discountAmount = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $qty      = $item['quantity'];
                $price    = $item['unit_price'];
                $taxRate  = $item['tax_rate'];
                $discRate = $item['discount_rate'];
                $itemSub  = $qty * $price;
                $itemTax  = $itemSub * ($taxRate / 100);
                $itemDisc = $itemSub * ($discRate / 100);

                $subtotal       += $itemSub;
                $taxAmount      += $itemTax;
                $discountAmount += $itemDisc;
                $itemsData[]     = [
                    'product_id'    => $item['product_id'],
                    'quantity'      => $qty,
                    'unit_price'    => $price,
                    'tax_rate'      => $taxRate,
                    'discount_rate' => $discRate,
                    'subtotal'      => $itemSub,
                    'total'         => $itemSub + $itemTax - $itemDisc,
                ];
            }

            $invoice = SalesInvoice::create([
                'invoice_number'  => $request->invoice_number,
                'customer_id'     => $request->customer_id,
                'invoice_date'    => $request->invoice_date,
                'due_date'        => $request->due_date,
                'subtotal'        => $subtotal,
                'tax_amount'      => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount'    => $subtotal + $taxAmount - $discountAmount,
                'status'          => 'Pending',
                'notes'           => $request->notes,
            ]);

            foreach ($itemsData as $d) {
                $invoice->items()->create($d);
            }
        });

        return redirect()->route('sales.index')->with('success', 'Sales invoice created successfully.');
    }

    // Route resource uses {sale} as parameter name
    public function show(SalesInvoice $sale)
    {
        $sale->load('customer', 'items.product', 'payments');
        return view('sales.show', ['invoice' => $sale]);
    }

    public function edit(SalesInvoice $sale)
    {
        $sale->load('items');
        $customers = Customer::orderBy('name')->get();
        $products  = Product::orderBy('name')->get();
        return view('sales.edit', ['invoice' => $sale, 'customers' => $customers, 'products' => $products]);
    }

    public function update(Request $request, SalesInvoice $sale)
    {
        $request->validate([
            'invoice_number' => 'required|string|unique:sales_invoices,invoice_number,' . $sale->id,
            'customer_id'    => 'required|exists:customers,id',
            'invoice_date'   => 'required|date',
            'due_date'       => 'required|date|after_or_equal:invoice_date',
            'notes'          => 'nullable|string',
            'items'                    => 'required|array|min:1',
            'items.*.product_id'       => 'required|exists:products,id',
            'items.*.quantity'         => 'required|integer|min:1',
            'items.*.unit_price'       => 'required|numeric|min:0',
            'items.*.tax_rate'         => 'required|numeric|min:0|max:100',
            'items.*.discount_rate'    => 'required|numeric|min:0|max:100',
        ]);

        DB::transaction(function () use ($request, $sale) {
            $sale->items()->delete();
            $subtotal = $taxAmount = $discountAmount = 0;

            foreach ($request->items as $item) {
                $qty      = $item['quantity'];
                $price    = $item['unit_price'];
                $taxRate  = $item['tax_rate'];
                $discRate = $item['discount_rate'];
                $itemSub  = $qty * $price;
                $itemTax  = $itemSub * ($taxRate / 100);
                $itemDisc = $itemSub * ($discRate / 100);

                $subtotal       += $itemSub;
                $taxAmount      += $itemTax;
                $discountAmount += $itemDisc;

                $sale->items()->create([
                    'product_id'    => $item['product_id'],
                    'quantity'      => $qty,
                    'unit_price'    => $price,
                    'tax_rate'      => $taxRate,
                    'discount_rate' => $discRate,
                    'subtotal'      => $itemSub,
                    'total'         => $itemSub + $itemTax - $itemDisc,
                ]);
            }

            $total = $subtotal + $taxAmount - $discountAmount;
            $sale->update([
                'invoice_number'  => $request->invoice_number,
                'customer_id'     => $request->customer_id,
                'invoice_date'    => $request->invoice_date,
                'due_date'        => $request->due_date,
                'subtotal'        => $subtotal,
                'tax_amount'      => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount'    => $total,
                'notes'           => $request->notes,
                'status'          => $sale->payments()->sum('amount') >= $total ? 'Paid' : 'Pending',
            ]);
        });

        return redirect()->route('sales.index')->with('success', 'Sales invoice updated successfully.');
    }

    public function destroy(SalesInvoice $sale)
    {
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Sales invoice deleted successfully.');
    }

    public function storePayment(Request $request, SalesInvoice $sale)
    {
        $outstanding = $sale->outstanding_amount;
        $request->validate([
            'amount'           => "required|numeric|min:0.01|max:{$outstanding}",
            'payment_date'     => 'required|date',
            'payment_method'   => 'required|string',
            'reference_number' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($request, $sale) {
            Payment::create([
                'sales_invoice_id' => $sale->id,
                'amount'           => $request->amount,
                'payment_date'     => $request->payment_date,
                'payment_method'   => $request->payment_method,
                'reference_number' => $request->reference_number,
            ]);

            $paid = $sale->payments()->sum('amount') + $request->amount;
            if ($paid >= $sale->total_amount) {
                $sale->update(['status' => 'Paid']);
            }
        });

        return redirect()->route('sales.show', $sale)->with('success', 'Payment registered successfully.');
    }
}
