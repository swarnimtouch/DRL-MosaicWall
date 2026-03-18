<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function dashboard(){
        $totalDoctors  = Doctor::count();
        $recentDoctors = Doctor::latest()->take(5)->get();

        return view('admin.dashboard', compact('totalDoctors', 'recentDoctors'));

    }

    public function index(Request $request){
        $doctors = Doctor::query()
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->paginate(10); // 👈 important
        return view('admin.doctors', compact('doctors'));
    }
    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor deleted successfully');
    }
}
