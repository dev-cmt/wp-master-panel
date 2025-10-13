<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Helpers\ImageHelper;

class PageSeoController extends Controller
{
    // Show all pages with their SEO info
    public function index()
    {
        $pages = Page::with('seo')->get();
        return view('backend.seo', compact('pages'));
    }

    // Update SEO for a single page
    public function update(Request $request, Page $page)
    {
        $data = $request->validate([
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords'    => 'nullable|string|max:255',
            'og_image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('og_image')) {
            $data['og_image'] = ImageHelper::uploadImage($request->file('og_image'), 'uploads/seo', optional($page->seo)->og_image);
        }

        // create or update seo row
        $page->seo()->updateOrCreate([], $data);

        return back()->with('success','SEO updated successfully!');
    }
}
