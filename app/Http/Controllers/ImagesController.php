<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Image;

class ImagesController extends Controller
{
    /**
     * About gallery content manage
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
    	
        $images = Image::all();

        return view('admin.gallery', compact('images'));
    }


    public function destroy($id)
    {

        $image = Image::findOrFail($id);
        Storage::delete('/public/img/thumbs/' . $image->url);
        $image->delete();

        return [
            'success' => true,
            'message' => 'Image deleted!'
        ];
    }
}
