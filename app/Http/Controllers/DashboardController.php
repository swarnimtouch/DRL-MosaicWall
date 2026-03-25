<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Exports\DoctorExport;
use Maatwebsite\Excel\Facades\Excel;
use Aws\S3\S3Client;
use ZipArchive;

class DashboardController extends Controller
{
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

    public function downloadFolderZip()
{
    $s3 = new S3Client([
        'region'      => 'ap-south-1',
        'version'     => 'latest',
        'credentials' => [
            'key'    => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
        ],
    ]);

    $bucket = 'swarnimpolling';

    // 1. Get all photo URLs from DB (skip nulls)
    $photoUrls = Doctor::whereNotNull('photo')->pluck('photo');

    if ($photoUrls->isEmpty()) {
        return response()->json(['message' => 'No photos found in database'], 404);
    }

    // 2. Create ZIP
    $zipFileName = storage_path('app/drl_mosaicwall_doctors.zip');
    $zip = new ZipArchive;

    if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        return response()->json(['message' => 'Could not create ZIP file'], 500);
    }

    $successCount = 0;

    foreach ($photoUrls as $url) {
        // 3. Convert full S3 URL → S3 key
        // e.g. https://swarnimpolling.s3.ap-south-1.amazonaws.com/drl_mosaicwall/doctors/john-doe-1234567890.jpg
        //   →  drl_mosaicwall/doctors/john-doe-1234567890.jpg
        $parsed = parse_url($url);
        $s3Key  = ltrim($parsed['path'], '/');

        try {
            $fileContent = $s3->getObject([
                'Bucket' => $bucket,
                'Key'    => $s3Key,
            ])['Body']->getContents();

            $fileName = basename($s3Key); // e.g. john-doe-1234567890.jpg
            $zip->addFromString($fileName, $fileContent);
            $successCount++;

        } catch (\Exception $e) {
            // Skip missing/broken files, continue with rest
            \Log::warning("S3 photo not found: {$s3Key} | Error: " . $e->getMessage());
            continue;
        }
    }

    $zip->close();

    if ($successCount === 0) {
        @unlink($zipFileName);
        return response()->json(['message' => 'No photos could be fetched from S3'], 404);
    }

    return response()->download($zipFileName)->deleteFileAfterSend(true);
}
}