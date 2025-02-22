<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PromotionController extends Controller
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
            Log::warning('Old promotion image: ' . $image . ' not found');
        }
    }
    public function index()
    {
        $promotions = Promotion::latest()->get();
        return view('promotion.index', compact('promotions'));
    }

    public function show(Promotion $promotion)
    {
        return view('promotion.show', compact('promotion'));
    }

    public function create()
    {
        return view('promotion.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:225'],
            'image' => ['required', 'image', 'max:2048'],
            'url' => ['nullable', 'url'],
        ]);

        $filePath = $this->uploadImage('promotions', $request->file('image'));

        if(!$filePath){
            return redirect()->back()->with('error', 'Image upload failed.');
        }

        $validated['image'] = $filePath;
        $validated['name_slug'] = str()->slug($validated['name']);
        $validated['created_by'] = auth()->id();

        Promotion::create($validated);

        return redirect()->route('promotions.index')->with(
            'success', 'Promotion created successfully'
        );
    }

    public function edit(Promotion $promotion)
    {
        return view('promotion.edit', compact('promotion'));
    }

    public function update(Promotion $promotion, Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:225'],
            'image' => ['required', 'image', 'max:2048'],
            'url' => ['nullable', 'url'],
        ]);

        if($request->hasFile('image')){
            $filePath = $this->uploadImage('promotions', $request->file('image'));

            if($filePath){
                $this->deleteImage($promotion->image);
                $validated['image'] = $filePath;
            }else{
                return redirect()->back()->with('error', 'Image upload failed.');
            }

        }else{
            $validated['image'] = $promotion->image;
        }

        $validated['name_slug'] = str()->slug($validated['name']);
        $validated['updated_by'] = auth()->id();

        $promotion->update($validated);

        return redirect()->route('promotions.edit', $promotion)->with(
            'success', 'Promotion updated successfully'
        );
    }

    public function destroy(Promotion $promotion)
    {
        $this->deleteImage($promotion->image);
        $promotion->delete();

        return redirect()->route('promotions.index')->with(
            'success', 'Promotion deleted successfully'
        );
    }
}
