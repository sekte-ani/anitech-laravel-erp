<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Event\Code\Test;

class TestimonialController extends Controller
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
            Log::warning('Old testimonial image: ' . $image . ' not found');
        }
    }
    public function index()
    {
        $testimonials = Testimonial::latest()->get();

        return view('testimonial.index', compact('testimonials'));
    }

    public function show(Testimonial $testimonial)
    {
        return view('testimonial.show', compact('testimonial'));
    }

    public function create()
    {
        return view('testimonial.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:225'],
            'job' => ['required', 'string', 'max:225'],
            'message' => ['required', 'string', 'max:225'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if($request->hasFile('image')){
            $filePath = $this->uploadImage('testimonials', $request->file('image'));

            if(!$filePath){
                return redirect()->back()->with('error', 'Image upload failed.');
            }

            $validated['image'] = $filePath;
        }

        $validated['created_by'] = auth()->id();

        Testimonial::create($validated);

        return redirect()->route('testimonials.index')->with(
            'success', 'Testimonial created successfully'
        );
    }

    public function edit(Testimonial $testimonial)
    {
        return view('testimonial.edit', compact('testimonial'));
    }

    public function update(Testimonial $testimonial, Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:225'],
            'job' => ['required', 'string', 'max:225'],
            'message' => ['required', 'string', 'max:225'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if($request->hasFile('image')){
            $filePath = $this->uploadImage('testimonials', $request->file('image'));

            if(!$filePath){
                return redirect()->back()->with('error', 'Image upload failed.');
            }

            if($testimonial->image){
                $this->deleteImage($testimonial->image);
            }

            $validated['image'] = $filePath;

        }else{
            $validated['image'] = $testimonial->image;
        }

        $validated['updated_by'] = auth()->id();

        $testimonial->update($validated);

        return redirect()->route('testimonials.edit', $testimonial)->with(
            'success', 'Testimonial updated successfully'
        );
    }

    public function destroy(Testimonial $testimonial)
    {
        if($testimonial->image){
            $this->deleteImage($testimonial->image);
        }
        $testimonial->delete();

        return redirect()->route('testimonials.index')->with(
            'success', 'Testimonial deleted successfully'
        );
    }
}
