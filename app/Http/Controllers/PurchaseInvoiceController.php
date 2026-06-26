<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseInvoice;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Payment;

class PurchaseInvoiceController extends Controller
{
    public function index()
    {
        $invoices = PurchaseInvoice::with('supplier', 'payments')->orderBy('invoice_date', 'desc')->get();
        return view('purchase.index', compact('invoices'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products  = Product::orderBy('name')->get();
        return view('purchase.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|string|unique:purchase_invoices',
            'supplier_id'    => 'required|exists:suppliers,id',
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

            $invoice = PurchaseInvoice::create([
                'invoice_number'  => $request->invoice_number,
                'supplier_id'     => $request->supplier_id,
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

        return redirect()->route('purchase.index')->with('success', 'Purchase invoice created successfully.');
    }

    // Route resource uses {purchase} as parameter name
    public function show(PurchaseInvoice $purchase)
    {
        $purchase->load('supplier', 'items.product', 'payments');
        return view('purchase.show', ['invoice' => $purchase]);
    }

    public function edit(PurchaseInvoice $purchase)
    {
        $purchase->load('items');
        $suppliers = Supplier::orderBy('name')->get();
        $products  = Product::orderBy('name')->get();
        return view('purchase.edit', ['invoice' => $purchase, 'suppliers' => $suppliers, 'products' => $products]);
    }

    public function update(Request $request, PurchaseInvoice $purchase)
    {
        $request->validate([
            'invoice_number' => 'required|string|unique:purchase_invoices,invoice_number,' . $purchase->id,
            'supplier_id'    => 'required|exists:suppliers,id',
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

        DB::transaction(function () use ($request, $purchase) {
            $purchase->items()->delete();
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

                $purchase->items()->create([
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
            $purchase->update([
                'invoice_number'  => $request->invoice_number,
                'supplier_id'     => $request->supplier_id,
                'invoice_date'    => $request->invoice_date,
                'due_date'        => $request->due_date,
                'subtotal'        => $subtotal,
                'tax_amount'      => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount'    => $total,
                'notes'           => $request->notes,
                'status'          => $purchase->payments()->sum('amount') >= $total ? 'Paid' : 'Pending',
            ]);
        });

        return redirect()->route('purchase.index')->with('success', 'Purchase invoice updated successfully.');
    }

    public function destroy(PurchaseInvoice $purchase)
    {
        $purchase->delete();
        return redirect()->route('purchase.index')->with('success', 'Purchase invoice deleted successfully.');
    }

    public function storePayment(Request $request, PurchaseInvoice $purchase)
    {
        $outstanding = $purchase->outstanding_amount;
        $request->validate([
            'amount'           => "required|numeric|min:0.01|max:{$outstanding}",
            'payment_date'     => 'required|date',
            'payment_method'   => 'required|string',
            'reference_number' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($request, $purchase) {
            Payment::create([
                'purchase_invoice_id' => $purchase->id,
                'amount'              => $request->amount,
                'payment_date'        => $request->payment_date,
                'payment_method'      => $request->payment_method,
                'reference_number'    => $request->reference_number,
            ]);

            $paid = $purchase->payments()->sum('amount') + $request->amount;
            if ($paid >= $purchase->total_amount) {
                $purchase->update(['status' => 'Paid']);
            }
        });

        return redirect()->route('purchase.show', $purchase)->with('success', 'Payment registered successfully.');
    }
}
