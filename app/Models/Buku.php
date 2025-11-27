<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';
    
    protected $fillable = [
        'judul',
        'stok',
        'tahun_terbit',
        'kategori_id',
        'isbn',
        'jumlah_halaman',
        'deskripsi',
        'gambar'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function pengarangs()
    {
        return $this->belongsToMany(Pengarang::class);
    }

    public function peminjamans()
    {
        return $this->belongsToMany(Peminjaman::class, 'peminjaman_detail')
            ->withPivot('jumlah');
    }

    public function peminjamanDetails()
    {
        return $this->hasMany(PeminjamanDetail::class);
    }

}
