<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class CjibfInvestor extends Model
{
    protected $table = 'cjibf_investor';

    public function userId(){
        return $this->belongsTo(KabkotaUserModel::class, 'kab_kota_id', 'kab_kota_id');
    }

    public function kota(){
        return $this->belongsTo(KabKota::class, 'kab_kota_id');
    }

    public function profil(){
        return $this->belongsTo(ProfileInvestor::class, 'profile_id');
    }

    public function loi(){
        return $this->belongsTo(Lois::class, 'loi_id');
    }
}