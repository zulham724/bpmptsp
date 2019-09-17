<?php

namespace App\Http\Controllers\CJIBF;

use App\CjibfChair;
use App\CjibfCp;
use App\CjibfEvent;
use App\CjibfInvestor;
use App\CjibfSektor;
use App\CjibfTable;
use App\KabKota;
use App\KabkotaUserModel;
use App\LayoutCol;
use App\LayoutRow;
use App\Mail\DaftarCJIBF;
use App\Pengumuman;
use App\ProfileInvestor;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class FrontEndController extends Controller
{
    public function front(){
        $user = Auth::guard('investor')->user()->id;
        $events = CjibfEvent::where('is_open', 1)->first();
        $profile = ProfileInvestor::where('user_id', $user)->first();
        $cities = KabKota::all();
        $sektors = CjibfSektor::all();
        $pengumuman = Pengumuman::all();
        $registered = CjibfInvestor::where('profile_id', $profile->id)->first();

//        /dd($cities);
        //dd($registered->userId->kabkota->nama);

        //dd($profile);
        //dd($registered);
        if (isset($events)){
            $cps = CjibfCp::where('event_id', $events->id)->get();
            $buka = Carbon::parse($events->tgl_buka)->format('d/m/Y');
            $now = Carbon::now()->format('d/m/Y');
            return view('front-end.investor.content.cjibf', compact('events', 'profile', 'cities', 'sektors', 'pengumuman', 'registered', 'cps'));
        }

        //dd(Carbon::parse($events->tgl_buka)->lte(Carbon::now()));

        //dd(Carbon::parse($events->tgl_buka)->format('d/m/Y'));
        //dd(Carbon::now()->format('d/m/Y'));
        return view('front-end.investor.content.cjibf', compact('events', 'profile', 'cities', 'sektors', 'pengumuman', 'registered'));
    }

    public function join(Request $request){
        //dd($request->all());
        try {
            $this->validate($request, [
                'kab_kota' => 'required',
                'profil' => 'required',
                'why' => 'required',
            ]);
        } catch (ValidationException $e) {
        }
        $user_id = Auth::guard('investor')->user()->id;
        $user_name = Auth::guard('investor')->user()->name;
        $layout_col = LayoutCol::all();
        $layout_row = LayoutRow::all();
        $event = CjibfEvent::first();
        $mejas = CjibfTable::all();
        $pengumuman = Pengumuman::all();
        $user_kab_kota = KabkotaUserModel::where('kab_kota_id', $request->kab_kota)->first();

        //dd($user_kab_kota->user_id);
        //dd($request->all());
        $join = new CjibfInvestor;
        $join->kab_kota_id = $user_kab_kota->user_id;
        $join->profile_id = $request->profil;
        $join->sektor_interest = $request->why;

        //dd($join);
        $join->save();

        //dd($join->kota->user->user_id);
        $sisakursi = CjibfTable::where('kabkota_id', $join->kab_kota_id)->first();
        //dd($sisakursi);

        if ($sisakursi->sisa <= 0){
            /* $cadangans = CjibfTable::with('jenis')->where('jenis_meja', 8)->where('sisa', '>', 0)->first();*/
            $cadangans = CjibfTable::whereHas('jenis', function ($query){
                $query->where('nama', 'Cadangan')->where('sisa', '>', 0);
            })->first();


            if (isset($cadangans)){
                $cadangans->sisa = ($cadangans->sisa)-1;
                $cadangans->update();

                $updateInvestor = CjibfInvestor::where('id', $join->id)->first();
                $updateInvestor->meja_id = $cadangans->kode_meja;
                //dd($updateInvestor);
                $updateInvestor->update();


                //dd($daftar);
                $sendObj = new \stdClass();
                $sendObj->nama_investor = $user_name;
                $sendObj->minat_kabkota = $request->kab_kota;
                $sendObj->minat_sektor = $updateInvestor->sektor_interest;
                $sendObj->meja = $updateInvestor->meja_id;
                $sendObj->col = $layout_col;
                $sendObj->row = $layout_row;
                $sendObj->event = $event;
                $sendObj->mejas = $mejas;
                $sendObj->perusahaan = $updateInvestor->profil->nama_perusahaan;
                $sendObj->qr = QrCode::format('png')
                    ->errorCorrection('H')
                    ->size(200)
                    ->merge('http://cjip.jatengprov.go.id/storage/additional/cjip-2.png', .3, true)
                    ->generate($sendObj->event->nama_kegiatan.','.$sendObj->nama_investor.','.$sendObj->perusahaan.','.$sendObj->meja);
                //dd($sendObj);

                /*$pdf = PDF::loadView('attach', ['send'=>$sendObj])->save($sendObj->perusahaan .'-'.'CJIBF2019-registered-detail.pdf');
                $attach = Storage::put('public/register/'.$sendObj->perusahaan .'-'.'CJIBF2019-registered-detail.pdf' ,$pdf->output());*/
                //dd($attach);
                $filename = $sendObj->perusahaan;
                //dd($sendObj);
                //$pdf = PDF::loadView('attach', ['send' => $sendObj])->setPaper('letter','portrait')->save(public_path('CJIBF2019/'.'CJIBF_'.$filename.'.pdf'));

                Mail::to(Auth::guard('investor')->user()->email)->send(new DaftarCJIBF($sendObj));
                //return PDF::loadView('attach', ['send' => $sendObj])->setPaper($customPaper, $paper_orientation)->stream();
                //dd($attach);
                //return $pdf->stream('CJIBF_'.$filename.'.pdf');
                return view('front-end.investor.content.cjibf-registered', compact('pengumuman'));
            }
            else{
                $pengumuman = Pengumuman::all();
                return view('front-end.investor.content.full', compact('pengumuman'));
            }

        }
        else{
            $sisakursi->sisa = ($sisakursi->sisa)-1;
            $sisakursi->update();
            //dd($sisakursi);

            $updateInvestor = CjibfInvestor::where('id', $join->id)->first();
            $updateInvestor->meja_id = $sisakursi->kode_meja;

            $updateInvestor->update();
            //dd($updateInvestor);
            //dd($updateInvestor->userId->);
            $sendObj = new \stdClass();
            $sendObj->nama_investor = $user_name;
            $sendObj->minat_kabkota = $request->kab_kota;
            $sendObj->minat_sektor = $updateInvestor->sektor_interest;
            $sendObj->meja = $updateInvestor->meja_id;
            $sendObj->col = $layout_col;
            $sendObj->row = $layout_row;
            $sendObj->event = $event;
            $sendObj->mejas = $mejas;
            $sendObj->perusahaan = $updateInvestor->profil->nama_perusahaan;
            $sendObj->qr = QrCode::format('png')
                ->errorCorrection('H')
                ->size(200)
                ->merge('http://cjip.jatengprov.go.id/storage/additional/cjip-2.png', .3, true)
                ->generate($sendObj->event->nama_kegiatan.','.$sendObj->nama_investor.','.$sendObj->perusahaan.','.$sendObj->meja);
            $filename = $sendObj->perusahaan;
            //dd($sendObj);
            //$pdf = PDF::loadView('attach', ['send' => $sendObj])->setPaper('letter','portrait')->save(public_path('CJIBF2019/'.'CJIBF_'.$filename.'.pdf'));

            Mail::to(Auth::guard('investor')->user()->email)->send(new DaftarCJIBF($sendObj));

            return view('front-end.investor.content.cjibf-registered', compact('pengumuman'));
            //return $pdf->stream('CJIBF_'.$filename.'.pdf');
        }

    }

    public function pdf(){

    }

    public function liveCount(){

    }
}
