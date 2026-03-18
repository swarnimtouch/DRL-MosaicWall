<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Exports\DoctorExport;
use Maatwebsite\Excel\Facades\Excel;

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
            ->paginate(10);
        return view('admin.doctors', compact('doctors'));
    }
    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor deleted successfully');
    }
    public function export(Request $request)
    {
        return Excel::download(
            new DoctorExport($request->search),
            'doctors_' . now()->format('Ymd_His') . '.xlsx'
        );
    }
}
