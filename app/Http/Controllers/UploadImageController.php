<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UploadImageService;

class UploadImageController extends Controller
{
    protected $imageService;

    public function __construct(UploadImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function upload(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $path = $this->imageService->upload($request->file('image'));

            // Simpan ke database kalau perlu
            // $candidate = Candidate::find($request->candidate_id);
            // $candidate->image = $path;
            // $candidate->save();

            return redirect()->back()->with('success', 'Gambar kandidat berhasil diupload!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal upload gambar: ' . $e->getMessage()]);
        }
    }

    /**
     * Hapus image kandidat
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        $deleted = $this->imageService->delete($request->path);

        if ($deleted) {
            return redirect()->back()->with('success', 'Gambar berhasil dihapus!');
        }

        return redirect()->back()->withErrors(['error' => 'Gambar tidak ditemukan.']);
    }
}