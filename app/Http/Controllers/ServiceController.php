<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function uploadImages($path, $images, $diskName = 'public')
    {
        $filePaths = [];
        foreach ($images as $image) {
            $filePath = $image->store($path, $diskName);
            if ($filePath) {
                $filePaths[] = $filePath;
            } else {
                Log::warning('Failed to upload image: ' . $image->getClientOriginalName());
            }
        }
        return $filePaths;
    }

    public function deleteImages($images, $diskName = 'public')
    {
        if (!is_array($images)) {
            $images = json_decode($images, true);
        }

        foreach ($images as $image) {
            if ($image && Storage::disk($diskName)->exists($image)) {
                Storage::disk($diskName)->delete($image);
            } else {
                Log::warning('Old service image: ' . $image . ' not found');
            }
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
            'short_description' => ['required', 'string', 'max:225'],
            'long_description' => ['required', 'string'],
            'images.*' => ['required', 'image', 'max:2048'],
        ]);

        $filePaths = $this->uploadImages('services', $request->file('images'));

        if (empty($filePaths)) {
            return redirect()->back()->with('error', 'Image upload failed.');
        }

        $validated['images'] = json_encode($filePaths);
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
            'short_description' => ['required', 'string', 'max:225'],
            'long_description' => ['required', 'string'],
            'images.*' => ['nullable', 'image', 'max:2048'],
        ]);

        $existingImages = json_decode($service->images, true) ?? [];

        if ($request->hasFile('images')) {
            $filePaths = $this->uploadImages('services', $request->file('images'));

            if (!empty($filePaths)) {
                $this->deleteImages($existingImages);
                $validated['images'] = json_encode($filePaths);
            } else {
                return redirect()->back()->with('error', 'Image upload failed.');
            }
        } else {
            $validated['images'] = json_encode($existingImages);
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
        $this->deleteImages($service->images);
        $service->delete();

        return redirect()->route('services.index')->with(
            'success', 'Service deleted successfully'
        );
    }
}
