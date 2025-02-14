<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
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
            Log::warning('Old service image: ' . $image . ' not found');
        }
    }
    public function index()
    {
        $services = Service::latest()->get();

        return view('service.index', compact('services'));
    }

    public function show(Service $service)
    {
        return view('service.show', compact('service'));
    }

    public function create()
    {
        return view('service.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:225'],
            'description' => ['required', 'string', 'max:225'],
            'image' => ['required', 'image', 'max:2048'],
        ]);

        $filePath = $this->uploadImage('services', $request->file('image'));

        if(!$filePath){
            return redirect()->back()->with('error', 'Image upload failed.');
        }

        $validated['image'] = $filePath;
        $validated['name_slug'] = str()->slug($validated['name']);
        $validated['created_by'] = auth()->id();

        Service::create($validated);

        return redirect()->route('services.index')->with(
            'success', 'Service created successfully'
        );
    }

    public function edit(Service $service)
    {
        return view('service.edit', compact('service'));
    }

    public function update(Service $service, Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:225'],
            'description' => ['required', 'string', 'max:225'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if($request->hasFile('image')){
            $filePath = $this->uploadImage('services', $request->file('image'));

            if($filePath){
                $this->deleteImage($service->image);
                $validated['image'] = $filePath;
            }else{
                return redirect()->back()->with('error', 'Image upload failed.');
            }

        }else{
            $validated['image'] = $service->image;
        }

        $validated['name_slug'] = str()->slug($validated['name']);
        $validated['updated_by'] = auth()->id();

        $service->update($validated);

        return redirect()->route('services.edit', $service)->with(
            'success', 'Service updated successfully'
        );
    }

    public function destroy(Service $service)
    {
        $this->deleteImage($service->image);
        $service->delete();

        return redirect()->route('services.index')->with(
            'success', 'Service deleted successfully'
        );
    }
}
