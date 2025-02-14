<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
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
            Log::warning('Old package image: ' . $image . ' not found');
        }
    }
    public function index()
    {
        $packages = Package::with('service')->latest()->get();

        return view('package.index', compact('packages'));
    }

    public function show(Package $package)
    {
        $package->load('service');
        return view('package.show', compact('package'));
    }

    public function create()
    {
        $services = Service::select('id','name','created_at')->latest()->get();
        return view('package.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'name' => ['required', 'string', 'max:225'],
            'price' => ['required', 'integer', 'min:1'],
            'features' => ['required', 'string', 'max:225'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if($request->hasFile('image')){
            $filePath = $this->uploadImage('packages', $request->file('image'));

            if(!$filePath){
                return redirect()->back()->with('error', 'Image upload failed.');
            }

            $validated['image'] = $filePath;
        }

        $validated['name_slug'] = str()->slug($validated['name']);
        $validated['created_by'] = auth()->id();

        Package::create($validated);

        return redirect()->route('packages.index')->with(
            'success', 'Package created successfully'
        );
    }

    public function edit(Package $package)
    {
        $services = Service::select('id','name','created_at')->latest()->get();
        return view('package.edit', compact('package','services'));
    }

    public function update(Package $package, Request $request)
    {
       $validated = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'name' => ['required', 'string', 'max:225'],
            'price' => ['required', 'integer', 'min:1'],
            'features' => ['required', 'string', 'max:225'],
            'discount_price' => ['nullable', 'integer', 'min:1'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if($request->hasFile('image')){
            $filePath = $this->uploadImage('packages', $request->file('image'));

            if($filePath && $package->image){
                $this->deleteImage($package->image);
                $validated['image'] = $filePath;
            }else{
                return redirect()->back()->with('error', 'Image upload failed.');
            }

        }else{
            $validated['image'] = $package->image;
        }

        $validated['name_slug'] = str()->slug($validated['name']);
        $validated['updated_by'] = auth()->id();

        $package->update($validated);

        return redirect()->route('packages.edit', $package)->with(
            'success', 'Package updated successfully'
        );
    }

    public function destroy(Package $package)
    {
        if($package->image){
            $this->deleteImage($package->image);
        }
        $package->delete();

        return redirect()->route('packages.index')->with(
            'success', 'Package deleted successfully'
        );
    }
}
