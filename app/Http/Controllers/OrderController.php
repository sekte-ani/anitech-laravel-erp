<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Client;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('client')->orderBy('id', 'asc')->paginate(5);
        $clients = Client::all();
        $status = ['Pending', 'On Progress', 'Completed', 'Cancelled'];

        return view('content.erp.erp-operational-progress', compact([
            'orders',
            'clients',
            'status',
        ]));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'client_id' => 'required',
            'item' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);
        $validatedData['status'] = 'Pending';

        Order::create($validatedData);
        return redirect()->back()->with('success', 'Order created successfully');
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validatedData = $request->validate([
            'client_id' => 'required',
            'item' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'amount' => 'required|numeric',
            'status' => 'required',
        ]);

        $order->update($validatedData);
        return redirect()->back()->with('success', 'Order updated successfully');
    }

    public function destroy($id)
    {
        $deletedOrder = Order::findOrFail($id);
        $deletedOrder->delete();

        return redirect()->back()->with('success', 'Order deleted successfully');
    }

    public function amount(Request $request)
    {
        $amount = $request->quantity * $request->price;
        return response()->json(['amount' => $amount]);
    }
}
