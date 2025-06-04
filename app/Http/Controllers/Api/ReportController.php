<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Jobs\CreateReport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    
    public function getAll()
    {
        $reports = Report::all();
        return response()->json($reports);
    }

    public function getById($id)
    {
        $report = Report::find($id);

        if (!$report) {
            return response()->json(['message' => 'Report not found'], 404);
        }

        return response()->json($report);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'alarm_id' => 'required|integer',
            'filepath' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'client' => 'nullable|string|max:255',
        ]);

        $report = Report::create([
            'title' => $validated['title'] ?? 'No title',
            'description' => $validated['description'] ?? null,
            'filepath' => $validated['filepath'],
            'alarm_id' => $validated['alarm_id'],
            'alarm_type' => 'Alarm',
            'client' => $validated['client'] ?? null,
            'status' => 'In process',
        ]);

        CreateReport::dispatch($report);

        return response()->json(['message' => 'Your file is being processed.'], 200);
    }

    public function downloadFile($id)
    {
        $report = Report::find($id);

        if (!$report || !$report->filepath) {
            return response()->json(['message' => 'File not found.'], 404);
        }

        if (!Storage::exists($report->filepath)) {
            return response()->json(['message' => 'File doesnt exists.'], 404);
        }

        return Storage::download($report->filepath);
    }

    public function getPdfAsImage($id)
    {
        $report = Report::find($id);

        if (!$report || !$report->filepath) {
            return response()->json(['message' => 'File not found.'], 404);
        }

        if (!Storage::exists($report->filepath)) {
            return response()->json(['message' => 'File does not exist.'], 404);
        }

        $pdfPath = Storage::path($report->filepath);

        try {
            $imagick = new \Imagick();
            $imagick->setResolution(200, 200); 
            $imagick->readImage($pdfPath . '[0]'); 
            $imagick->setImageFormat('jpeg');

            $imageBlob = $imagick->getImageBlob();

            return Response::make($imageBlob, 200, [
                'Content-Type' => 'image/jpeg',
                'Content-Disposition' => 'inline; filename="report_preview.jpg"',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to convert PDF: ' . $e->getMessage()], 500);
        }
    }
}
