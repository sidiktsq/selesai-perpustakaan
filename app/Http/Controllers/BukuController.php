<?php
namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Pengarang;
use Illuminate\Http\Request;

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
        $validated = $request->validate([
            'judul'        => 'required',
            'kategori_id'  => 'required|exists:kategoris,id',
            'pengarang_id' => 'required|array',
            'stok'         => 'required|integer',
            'tahun'        => 'required|integer',
        ]);

        $buku = Buku::create([
            'judul'       => $request->judul,
            'kategori_id' => $request->kategori_id,
            'stok'        => $request->stok,
            'tahun'       => $request->tahun,
        ]);

        $buku->pengarangs()->attach($request->pengarang_id);

        return redirect()->route('buku.index')->with('success', 'Data berhasil ditambahkan.');
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
