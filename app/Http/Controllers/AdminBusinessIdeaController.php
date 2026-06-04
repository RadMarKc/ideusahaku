<?php

namespace App\Http\Controllers;

use App\Models\MicroBusinessIdea;
use App\Services\BusinessIdeaImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class AdminBusinessIdeaController extends Controller
{
    public function index(): View
    {
        return view('admin.business-ideas.index', [
            'ideas' => MicroBusinessIdea::query()
                ->orderBy('name')
                ->paginate(15),
        ]);
    }

    public function import(Request $request, BusinessIdeaImportService $importer): RedirectResponse
    {
        $validated = $request->validate([
            'business_file' => ['required', 'file', 'mimes:csv,txt,xlsx', 'max:5120'],
        ], [
            'business_file.required' => 'File CSV/Excel wajib diunggah.',
            'business_file.mimes' => 'Format file harus CSV atau Excel .xlsx.',
        ]);

        $file = $validated['business_file'];
        $result = $importer->import(
            $file->getRealPath(),
            $file->getClientOriginalExtension()
        );

        return redirect()
            ->route('admin.business-ideas.index')
            ->with('status', "Import selesai. {$result['imported']} data usaha diproses.");
    }

    public function template(): StreamedResponse
    {
        $rows = [
            ['namausaha', 'modal', 'skormodal', 'kategori', 'waktu', 'deskripsi'],
            ['Laundry Kiloan', '1000000', '3', 'offline', 'sedang', 'Layanan laundry rumahan untuk pelanggan sekitar.'],
            ['Toko Online Aksesoris', '500000', '4', 'online', 'rendah', 'Usaha penjualan aksesoris melalui marketplace dan media sosial.'],
        ];

        return response()->streamDownload(function () use ($rows) {
            $output = fopen('php://output', 'w');

            foreach ($rows as $row) {
                fputcsv($output, $row);
            }

            fclose($output);
        }, 'template-data-usaha.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function update(Request $request, MicroBusinessIdea $businessIdea): RedirectResponse
    {
        $validated = $request->validate([
            'description' => ['nullable', 'string', 'max:5000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $businessIdea->update([
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.business-ideas.index', ['page' => $request->integer('page', 1)])
            ->with('status', 'Deskripsi usaha berhasil diperbarui.');
    }
}
