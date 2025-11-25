<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengarang extends Model
{
    protected $fillable = ['nama_pengarang'];

    public function bukus()
    {
        return $this->belongsToMany(Buku::class, 'buku_pengarang', 'pengarang_id', 'buku_id');
    }
}
