<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month');
        
        $query = Invoice::orderBy('created_at', 'desc');

        if ($month) {
            $query->whereMonth('created_at', Carbon::parse($month)->month)->whereYear('created_at', Carbon::parse($month)->year);
        }

        $invoices = $query->with('orders.client')->orderBy('id', 'asc')->paginate(10);
        $orders = Order::whereNull('invoice_id')->with('client')->get();
        $client = Client::find(1);
        $type = ['Internal', 'Order'];
        $status = ['Pending', 'Paid', 'Cancelled'];

        return view('content.erp.erp-finance-invoicing', compact([
            'invoices',
            'orders',
            'client',
            'type',
            'status',
        ]));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:Internal,Order',
            'date' => 'required|date',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'order_id' => 'required|array',
            'order_id.*' => 'exists:orders,id',
        ]);

        $orders = Order::whereIn('id', $request->order_id)->get();
        $subtotal = $orders->sum('amount');
        $discount = $request->discount ?? 0;
        $total = max(0, $subtotal - $discount);

        $invoice = Invoice::create([
            'type' => $request->type,
            'date' => $request->date,
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'amount_due' => $total,
            'notes' => $request->notes,
        ]);

        foreach ($orders as $order) {
            $order->update(['invoice_id' => $invoice->id]);
        }

        return redirect()->back()->with('success', 'Invoice berhasil dibuat dengan ' . count($orders) . ' order(s).');
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'type' => 'required|in:Internal,Order',
            'date' => 'required|date',
            'invoice_number' => 'required|unique:invoices,invoice_number,' . $invoice->id,
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'required|in:Pending,Paid,Cancelled',
            'order_id' => 'required|array',
            'order_id.*' => 'exists:orders,id',
        ]);

        Order::where('invoice_id', $invoice->id)->update(['invoice_id' => null]);

        $orders = Order::whereIn('id', $request->order_id)->get();
        $subtotal = $orders->sum('amount');
        $discount = $request->discount ?? 0;
        $total = max(0, $subtotal - $discount);

        $invoice->update([
            'type' => $request->type,
            'date' => $request->date,
            'invoice_number' => $request->invoice_number,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'amount_due' => $total,
            'notes' => $request->notes,
            'status' => $request->status,
        ]);

        foreach ($orders as $order) {
            $order->update(['invoice_id' => $invoice->id]);
        }

        return redirect()->back()->with('success', 'Invoice berhasil diperbarui.');
    }

    public function destroy(Invoice $invoice)
    {
        Order::where('invoice_id', $invoice->id)->update(['invoice_id' => null]);

        $invoice->delete();

        return redirect()->back()->with('success', 'Invoice berhasil dihapus.');
    }

    public function addOrders(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'order_id' => 'required|array',
            'order_id.*' => 'exists:orders,id',
        ]);

        $invoice = Invoice::findOrFail($request->invoice_id);
        $orders = Order::whereIn('id', $request->order_ids)->get();

        foreach ($orders as $order) {
            $order->update(['invoice_id' => $invoice->id]);
        }

        $subtotal = $invoice->orders()->sum('amount');
        $discount = $invoice->discount;
        $total = max(0, $subtotal - $discount);

        $invoice->update([
            'subtotal' => $subtotal,
            'total' => $total,
            'amount_due' => $total,
        ]);

        return redirect()->back()->with('success', 'Order berhasil ditambahkan ke invoice.');
    }

    public function preview($id)
    {
        $invoice = Invoice::with('orders.client')->findOrFail($id);

        return view('invoice.invoice-temp-baru', compact('invoice'));
    }

    public function download($id)
    {
        $invoice = Invoice::with('orders.client')->findOrFail($id);
        $client = $invoice->orders->first()?->client->name ?? 'Unknown';

        $pdf = Pdf::loadView('invoice.invoice-temp-baru', compact('invoice'));

        return $pdf->download("Invoice {$client}.pdf");
    }
}
