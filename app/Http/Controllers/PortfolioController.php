<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    public function uploadImage($path, $image, $diskName = 'public')
    {
        $filePath = $image->store($path, $diskName);
        return $filePath ?: false;
    }
    public function deleteImage($image, $diskName = 'public')
    {
        if($image && Storage::disk($diskName)->exists($image)){
            Storage::disk($diskName)->delete($image);
        }else{
            Log::warning('Old portfolio image: ' . $image . ' not found');
        }
    }
    public function index()
    {
        $portfolios = Portfolio::with('services')->latest()->get();
        return view('portfolio.index', compact('portfolios'));
    }

    public function show(Portfolio $portfolio)
    {
        $portfolio->load('services');
        return view('portfolio.show', compact('portfolio'));
    }

    public function create()
    {
        $services = Service::select('id','name','created_at')->latest()->get();
        return view('portfolio.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'services_id' => ['required', 'array'],
            'services_id.*' => ['exists:services,id'],
            'name' => ['required', 'string', 'max:225'],
            'description' => ['required', 'string', 'max:225'],
            'image' => ['required', 'image', 'max:2048'],
            'url' => ['nullable', 'url'],
        ]);

        $filePath = $this->uploadImage('portfolios', $request->file('image'));

        if(!$filePath){
            return redirect()->back()->with('error', 'Image upload failed.');
        }

        $validated['image'] = $filePath;
        $validated['name_slug'] = str()->slug($validated['name']);
        $validated['created_by'] = auth()->id();

        $portfolio = Portfolio::create($validated);
        $portfolio->services()->sync($validated['services_id']);

        return redirect()->route('portfolios.index')->with(
            'success', 'Portfolio created successfully'
        );
    }

    public function edit(Portfolio $portfolio)
    {
        $portfolio->load('services');
        $services = Service::select('id','name','created_at')->latest()->get();
        return view('portfolio.edit', compact('portfolio', 'services'));
    }

    public function update(Portfolio $portfolio, Request $request)
    {
        $validated = $request->validate([
            'services_id' => ['required', 'array'],
            'services_id.*' => ['exists:services,id'],
            'name' => ['required', 'string', 'max:225'],
            'description' => ['required', 'string', 'max:225'],
            'image' => ['nullable', 'image', 'max:2048'],
            'url' => ['nullable', 'url'],
        ]);

        if($request->hasFile('image')){
            $filePath = $this->uploadImage('portfolios', $request->file('image'));

            if($filePath){
                $this->deleteImage($portfolio->image);
                $validated['image'] = $filePath;
            }else{
                return redirect()->back()->with('error', 'Image upload failed.');
            }

        }else{
            $validated['image'] = $portfolio->image;
        }

        $validated['name_slug'] = str()->slug($validated['name']);
        $validated['updated_by'] = auth()->id();

        $portfolio->update($validated);
        $portfolio->services()->sync($validated['services_id']);

        return redirect()->route('portfolios.edit', $portfolio)->with(
            'success', 'Portfolio updated successfully'
        );
    }

    public function destroy(Portfolio $portfolio)
    {
        $this->deleteImage($portfolio->image);
        $portfolio->delete();

        return redirect()->route('portfolios.index')->with(
            'success', 'Portfolio deleted successfully'
        );
    }
}
