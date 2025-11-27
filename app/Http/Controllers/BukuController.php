<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Pengarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BukuController extends Controller
{
    public function index()
    {
        $bukus = Buku::with(['kategori', 'pengarangs'])->latest()->get();
        return view('buku.index', compact('bukus'));
    }

    public function create()
    {
        $kategoris  = Kategori::all();
        $pengarangs = Pengarang::all();

        return view('buku.create', compact('kategoris', 'pengarangs'));
    }

   public function store(Request $request)
{
    // Validasi input
    $validated = $request->validate([
        'judul'         => 'required|string|max:255',
        'kategori_id'   => 'required|exists:kategoris,id',
        'pengarang_id'  => 'required|array',
        'pengarang_id.*'=> 'exists:pengarangs,id',
        'stok'          => 'required|integer|min:0',
        'tahun'         => 'required|integer|min:1900|max:' . (date('Y') + 1),
    ]);

    try {
        DB::beginTransaction();

        // Buat data buku dengan nilai default untuk field yang required
        $buku = Buku::create([
            'judul'         => $request->judul,
            'kategori_id'   => $request->kategori_id,
            'stok'          => $request->stok,
            'tahun_terbit'  => $request->tahun,
            'isbn'          => 'TEMP-' . time(), // Nilai sementara untuk ISBN
            'jumlah_halaman'=> 0, // Nilai default
        ]);

        // Hubungkan dengan pengarang
        $buku->pengarangs()->sync($request->pengarang_id);

        DB::commit();

        return redirect()->route('buku.index')
            ->with('success', 'Buku berhasil ditambahkan');
            
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    public function show($id)
    {
        $buku = Buku::with(['kategori', 'pengarangs'])->findOrFail($id);
        return view('buku.show', compact('buku'));
    }

    public function edit($id)
    {
        $buku       = Buku::with(['pengarangs'])->findOrFail($id);
        $kategoris  = Kategori::all();
        $pengarangs = Pengarang::all();
        return view('buku.edit', compact('buku', 'kategoris', 'pengarangs'));
    }

    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'judul'        => 'required',
            'kategori_id'  => 'required|exists:kategoris,id',
            'stok'         => 'required|integer',
            'tahun'        => 'required|integer',
            'pengarang_id' => 'required|array',
        ]);

        $buku->update([
            'judul'       => $request->judul,
            'kategori_id' => $request->kategori_id,
            'stok'        => $request->stok,
            'tahun'       => $request->tahun,
        ]);

        $buku->pengarangs()->sync($request->pengarang_id);

        return redirect()->route('buku.index')->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return redirect()->route('buku.index');
    }
}
