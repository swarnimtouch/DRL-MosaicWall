<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('doctor.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'emp_id' => 'required',
            'hq' => 'required',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $doctor = new Doctor();
        $doctor->name = $request->name;
        $doctor->emp_id = $request->emp_id;
        $doctor->hq = $request->hq;

        if ($request->hasFile('photo')) {

            $file = $request->file('photo');

            $doctorName = Str::slug($request->name);

            // unique filename
            $fileName = $doctorName . '-' . time() . '.' . $file->getClientOriginalExtension();

            // S3 path
            $path = 'drl_mosaicwall/doctors/' . $fileName;

            // upload to S3
            Storage::disk('s3')->put($path, file_get_contents($file), 'public');

            // save URL in DB
            $doctor->photo = Storage::disk('s3')->url($path);
        }


        $doctor->save();

        return redirect()->back()->with('success', 'Doctor created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        //
    }
}
