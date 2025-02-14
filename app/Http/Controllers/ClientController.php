<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('id', 'asc')->paginate(5);

        return view('content.erp.erp-operational-client', compact([
            'clients',
        ]));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email|string|unique:clients',
        ]);

        Client::create($validatedData);
        return redirect()->back()->with('success', 'Client created successfully');
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email|string|unique:clients,email,' . $client->id,
        ]);

        $client->update($validatedData);
        return redirect()->back()->with('success', 'Client updated successfully');
    }

    public function destroy($id)
    {
        $deletedClient = Client::findOrFail($id);
        $deletedClient->delete();

        return redirect()->back()->with('success', 'Client deleted successfully');
    }
}
