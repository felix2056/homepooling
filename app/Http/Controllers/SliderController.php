<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Slider;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = Slider::orderBy('order', 'asc')->paginate(25);
        if(count($sliders)>10){
            Session::flash('warning','Don\'t add more than 10 slider for better site performance!');
        }
        return view('admin.sliderList', compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.sliderCreate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate form
        $messages = [
            'image.max' => 'The :attribute size must be under 2MB.',
            'image.dimensions' => 'The :attribute dimensions must be minimum 1900 X 1200.',
        ];
        $this->validate($request, [
            'title' => 'required|min:5|max:255',
            'subtitle' => 'required|min:5|max:255',
            'image' => 'required|mimes:jpeg,jpg,png|max:2048|dimensions:min_width=1900,min_height=1200',

        ], $messages);

        $storagepath = $request->file('image')->store('public/img/home');
        $fileName = basename($storagepath);

        $data = $request->all();
        $data['image'] = $fileName;

        Slider::create($data);

        return redirect()->route('slider.index')->with('success', 'New slider item created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $slider = Slider::findOrFail($id);
        return view('admin.sliderEdit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //validate form
        $messages = [
            'image.max' => 'The :attribute size must be under 2MB.',
            'image.dimensions' => 'The :attribute dimensions must be minimum 1900 X 1200.',
        ];
        $this->validate($request, [
            'title' => 'required|min:5|max:255',
            'subtitle' => 'required|min:5|max:255',
            'image' => 'mimes:jpeg,jpg,png|max:2048|dimensions:min_width=1900,min_height=1200',

        ], $messages);

        $slider = Slider::findOrFail($id);
        $data = $request->all();

        if($request->hasFile('image')){
            $file_path = "public/img/home/".$slider->image;
            Storage::delete($file_path);

            $storagepath = $request->file('image')->store('public/img/home');
            $fileName = basename($storagepath);
            $data['image'] = $fileName;

        }

        $slider->fill($data);
        $slider->save();

        return redirect()->route('slider.index')->with('success', 'Slider item updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        $slider->delete();

        $file_path = "public/img/home/".$slider->image;
        Storage::delete($file_path);
        
        return redirect()->route('slider.index')->with('success', 'Slider item deleted.');
    }
}
