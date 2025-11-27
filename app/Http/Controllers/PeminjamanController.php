<?php
namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $title  = 'Delete Data!';
        $text   = "Are you sure you want to delete?";
        $peminjaman = Peminjaman::with(['bukus'])
            ->when($search, function ($query, $search) {
                return $query->where('kode_peminjaman', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        return view('peminjaman.index', compact('peminjaman', 'search'));
    }

    public function create()
    {
        $buku = Buku::with('kategori')->get();
        return view('peminjaman.create', compact('buku'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'buku_id'       => 'required|array',
            'buku_id.*'     => 'exists:buku,id',
            'jumlah'        => 'required|array',
            'jumlah.*'      => 'required|integer|min:1',
        ]);

        // Buat header peminjaman
        $peminjaman = Peminjaman::create([
            'kode_peminjaman'    => 'PJ-' . strtoupper(uniqid()),
            'nama_peminjam'      => $request->nama_peminjam,
            'tanggal_peminjaman' => now(),
            'tanggal_kembali'    => now()->addDays(7),
        ]);

        $pivot = [];

        foreach ($request->buku_id as $i => $bukuid) {

            $buku   = Buku::findOrFail($bukuid);
            $jumlah = $request->jumlah[$i];

            // Data di pivot
            $pivot[$bukuid] = [
                'jumlah' => $jumlah,
            ];

            // Kurangi stok
            $buku->stok -= $jumlah;
            $buku->save();
        }

        // Simpan ke pivot (peminjaman_detail)
        $peminjaman->bukus()->attach($pivot);

        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil disimpan!');
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with('bukus')->findOrFail($id);
        return view('peminjaman.show', compact('peminjaman'));
    }

    public function edit($id)
    {
        $peminjaman = Peminjaman::with('bukus')->findOrFail($id);
        $buku       = Buku::all();
        return view('peminjaman.edit', compact('peminjaman', 'buku'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'buku_id'   => 'required|array',
            'buku_id.*' => 'exists:bukus,id',
            'jumlah'    => 'required|array',
            'jumlah.*'  => 'required|integer|min:1',
        ]);

        $peminjaman = Peminjaman::with('bukus')->findOrFail($id);

        // Kembalikan stok sebelumnya
        foreach ($peminjaman->bukus as $b) {
            $bk = Buku::find($b->id);
            if ($bk) {
                $bk->stok += $b->pivot->jumlah;
                $bk->save();
            }
        }

        // Hapus pivot lama
        $peminjaman->bukus()->detach();

        // Update data peminjaman
        $peminjaman->update([
            'nama_peminjaman'    => $request->nama_peminjaman,
            'tanggal_peminjaman' => now(),
            'tanggal_kembali'    => now(),
        ]);

        $pivot = [];

        foreach ($request->buku_id as $i => $bukuid) {

            $buku   = Buku::findOrFail($bukuid);
            $jumlah = $request->jumlah[$i];

            // Data di pivot
            $pivot[$bukuid] = [
                'jumlah' => $jumlah,
            ];

            // Kurangi stok
            $buku->stok -= $jumlah;
            $buku->save();
        }

        // Simpan ke pivot (peminjaman_detail)
        $peminjaman->bukus()->attach($pivot);

        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil disimpan!');
    }

    public function destroy($id)
    {
        $peminjaman = Peminjaman::with('bukus')->findOrFail($id);

        // Kembalikan stok
        foreach ($peminjaman->bukus as $buku) {
            $bk = Buku::find($buku->id);
            if ($bk) {
                $bk->stok += $buku->pivot->jumlah;
                $bk->save();
            }
        }

        // Hapus pivot
        $peminjaman->bukus()->detach();

        // Hapus data utama
        $peminjaman->delete();
        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil dihapus & stok kembali!');
    }

    public function search(Request $request)
    {
        $query = $request->query('query');

        $peminjaman = Peminjaman::with('bukus')
            ->where('kode_peminjaman', 'like', "%$query%")
            ->get();

        return response()->json($peminjaman);
    }
}
