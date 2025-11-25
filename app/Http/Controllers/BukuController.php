<?php
namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Pengarang;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class BukuController extends Controller
{
    public function index()
    {
        $bukus = Buku::with(['kategoris', 'pengarangs'])->latest()->get();
        $title = 'Delete Data!';
        $text  = "Are you sure you want to delete?";
        
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
            'judul'            => 'required',
            'kategori_id' => 'required',
            'pengarang_id'     => 'required|array',
            'stok'             => 'required|integer',
            'tahun'            => 'required|integer',
        ]);

        // 1. Simpan data buku
        $buku = Buku::create([
            'judul'            => $request->judul,
            'kategori_id'      => $request->kategori_id,
            'stok'             => $request->stok,
            'tahun'            => $request->tahun,
        ]);

        // 2. Attach pengarang ke pivot buku_pengarang
        $buku->pengarangs()->attach($request->pengarang_id);
        Alert::success('Success Title', 'Success Message');
        return redirect()->route('buku.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function show($id)
    {
        $buku = Buku::with(['kategoriBuku', 'pengarangs'])->findOrFail($id);
        return view('project.buku.show', compact('buku'));
    }

    public function edit($id)
    {
        $buku       = Buku::with(['pengarangs'])->findOrFail($id);
        $kategoris  = KategoriBuku::all();
        $pengarangs = Pengarang::all();
        return view('buku.edit', compact('buku', 'kategoris', 'pengarangs'));

    }

    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'judul'        => 'required',
            'kategori_id'  => 'required|exists:kategori_id',
            'stok'         => 'required|integer',
            'tahun'        => 'required|integer',
            'pengarang_id' => 'required|array',
        ]);

        // Update data buku
        $buku->update([
            'judul'       => $request->judul,
            'kategori_id' => $request->kategori_id,
            'stok'        => $request->stok,
            'tahun'       => $request->tahun,
        ]);

        // Sinkron pengarang many-to-many
        $buku->pengarangs()->sync($request->pengarang_id);
        Alert::success('Updated!', 'Your file has been updated.');
        return redirect()->route('buku.index')->with('success', 'Data buku berhasil diperbarui');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);+
        $buku->delete();
        Alert::success('Deleted!', 'Your file has been deleted.');
        return redirect()->route('buku.index');

    }
}
