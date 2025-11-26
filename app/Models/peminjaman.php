<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $guarded  = [];
    protected $fillable = ['kode_peminjaman', 'nama_peminjam', 'tanggal_peminjaman', 'tanggal_kembali'];

    public function bukus()
    {
        return $this->belongsToMany(Buku::class, 'peminjaman_details')
            ->withPivot(['jumlah'])
            ->withTimestamps();
    }
}
