<?php

namespace App\Http\Controllers\Frontend\Home;

use App\Award;
use App\Berita;
use App\BiayaAir;
use App\BiayaListrik;
use App\CjibfEvent;
use App\CjibfSektor;
use App\Events\FeedAction;
use App\Faq;
use App\Feed;
use App\InfrastrukturPendukung;
use App\JenisFaq;
use App\JenisKatUserAir;
use App\KabkotaUserModel;
use App\LoiInterest;
use App\Pariwisata;
use App\Perikanan;
use App\Perkebunan;
use App\Pertanian;
use App\PertumbuhanEkonomi;
use App\Peternakan;
use App\ProfileInvestor;
use App\ProfilKabupaten;
use App\Proyek;
use App\Umr;
use App\User;
use App\UserInvestor;
use Artesaos\SEOTools\Facades\SEOTools;
use Cornford\Googlmapper\Facades\MapperFacade as Mapper;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function Symfony\Component\Debug\Tests\testHeader;
use TCG\Voyager\Facades\Voyager;

class HomeController extends Controller
{
    public function home(){

        SEOTools::setTitle('Home');
        SEOTools::setDescription(Voyager::setting('site.ket_why'));
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::addImages(Voyager::image(setting('site.bg_why')));
        SEOTools::opengraph()->addProperty('type', 'website')->setSiteName('Central Java Investment Platform')->setDescription(Voyager::setting('site.ket_why'));
        SEOTools::twitter()->setSite('@DPMPTSPJateng')->setImages('https://cjip.jatengprov.go.id/storage/settings/August2019/esr0C8HmQss78AAnlaue.png')->setDescription(Voyager::setting('site.ket_why'));
        SEOTools::jsonLd()->addImage('https://cjip.jatengprov.go.id/storage/settings/August2019/esr0C8HmQss78AAnlaue.png')->setUrl(url()->current())->setDescription(Voyager::setting('site.ket_why'));


        $test = Proyek::with('translations')->get();


        $mapsKey = 'AIzaSyBGsawbqVs083lGEe8cilVz0FqO0rHt5ZE&amp';
        $feeds = Feed::orderByViews()->paginate(8);
        $populers = Feed::orderByViews()->take(5)->get();
        $news = Berita::take(5)->get();

        $ekonomis = PertumbuhanEkonomi::where('status', 1)->get();
        $awards = Award::all();
        $infrastrukturs = InfrastrukturPendukung::all();
        $umks = Umr::all()->groupBy(['kab_kota_id', 'tahun']);
        $min = Umr::min('nilai_umr');
        $max = Umr::max('nilai_umr');
        $min_umk = Umr::where('nilai_umr', $min)->first();
        $max_umk = Umr::where('nilai_umr', $max)->first();
        //dd($min_umk->kab->kota->kabkota->nama);


        $user = User::all();
        //dd($user);
        //dd($umks->toJson());
        foreach ($umks as $key1 => $umk){
            //dd($key1);
            //dd(count($umk));
            $kota = User::where('id', $key1)->first();
            //dd($kota->kota->kabkota->nama);
            //dd($umk);
        }
        $listriks = BiayaListrik::all();
        $airs = JenisKatUserAir::all();
        $alphabet = range('A', 'Z');

       $ch = curl_init();
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_URL, 'http://sijablayv2.dpmptsp.jatengprov.go.id/api/realisasi');
       $result = curl_exec($ch);
       curl_close($ch);
       $obj = json_decode($result);
       //dd(json_encode($obj));


       if (Auth::guard('investor')->check()){
           $registered = ProfileInvestor::where('user_id',Auth::guard('investor')->user()->id)->first();
           $intersts = LoiInterest::where('user_id', Auth::guard('investor')->user()->id)->get();
           //dd($registered);
           if (is_null($registered)){
               return redirect()->route('form.profile', Auth::guard('investor')->user()->id );
           }
           elseif (isset($intersts)){
               return view('front-end.new-home', compact('mapsKey', 'min_umk','max_umk','alphabet','obj','feeds', 'intersts', 'populers', 'news' , 'ekonomis', 'awards', 'infrastrukturs', 'umks', 'listriks', 'airs', 'user'));
           }
           else{
               return view('front-end.new-home', compact('mapsKey', 'min_umk','max_umk','alphabet','feeds','obj',  'populers', 'news' , 'ekonomis', 'awards', 'infrastrukturs', 'umks', 'listriks', 'airs', 'user'));

           }
       }
       else{
           return view('front-end.new-home', compact(
               'mapsKey', 'min_umk','max_umk','feeds', 'populers', 'news', 'ekonomis', 'awards','alphabet','obj', 'infrastrukturs', 'umks', 'listriks', 'airs', 'user'));
       }

            }

    public function likes(Request $request, $id)
    {
        $action = $request->get('action');
        switch ($action) {
            case 'Like':
                Feed::where('id', $id)->increment('likes_count');
                break;
            case 'Unlike':
                Feed::where('id', $id)->decrement('likes_count');
                break;
        }
        event(new FeedAction($id, $action));
        return '';
    }

    public function sidebar(){
        $intersts = LoiInterest::all();
        $populers = Feed::orderByViews()->take(5)->get();
        $news = Berita::take(5)->get();

        return view('front-end.sidebar', compact('intersts', 'populers', 'news'));
    }

    public function readyToOffer(){
        SEOTools::setTitle('Ready to Offered');
        SEOTools::setDescription(Voyager::setting('site.ket_rto'));
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::addImages(Voyager::image(setting('site.bg_rto')));
        SEOTools::opengraph()->addProperty('type', 'website')->setSiteName('Central Java Investment Platform')->setDescription(Voyager::setting('site.ket_rto'));
        SEOTools::twitter()->setSite('@DPMPTSPJateng')->setImages('https://cjip.jatengprov.go.id/storage/settings/August2019/esr0C8HmQss78AAnlaue.png')->setDescription(Voyager::setting('site.ket_rto'));
        SEOTools::jsonLd()->addImage(Voyager::image(setting('site.bg_rto')))->setUrl(url()->current())->setDescription(Voyager::setting('site.ket_rto'));

        $proyeks = Proyek::whereHas('marketplace', function ($query) {
            $query->where('name', '=', 'Ready to Offer');
        })->where('status', 1)->paginate(5);
        //dd($proyeks);
        //dd($proyeks->load('translations'));
        if (($isModelTranslatable = is_bread_translatable($proyeks))) {
            $proyeks->load('translations');
        }

        return view('front-end.marketplace.ready-to-offer', compact('proyeks', 'isModelTranslatable'));
        //$proyeks = Proyek::with('marketplace');
    }

    public function detailRto($id, $slug){

        $proyek = Proyek::findOrFail($id);
        $mapsKey = 'AIzaSyBGsawbqVs083lGEe8cilVz0FqO0rHt5ZE&amp';
       /* $proyek*/
        $images = json_decode($proyek->fotos);
        SEOTools::setTitle('Ready to Offered -'.$proyek->translate('en')->project_name.' - '.$proyek->project_name);
        SEOTools::setDescription('Here is some ready to offered investment project - '.$proyek->translate('en')->latar_belakang.' - '.$proyek->latar_belakang);
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::addImages(Voyager::image($images[0]));
        SEOTools::setCanonical(url()->current());
        SEOTools::opengraph()->addProperty('type', 'articles')->setSiteName('Central Java Investment Platform')->setDescription($proyek->translate('en')->latar_belakang.' - '.$proyek->latar_belakang);
        SEOTools::twitter()->setSite('@DPMPTSPJateng')->setImages(Voyager::image($images[0]))->setDescription($proyek->translate('en')->latar_belakang.' - '.$proyek->latar_belakang);
        SEOTools::jsonLd()->addImage(Voyager::image($images[0]))->setUrl(url()->current())->setDescription($proyek->translate('en')->latar_belakang.' - '.$proyek->latar_belakang);

        return view('front-end.marketplace.detail.rto', compact('proyek', 'mapsKey'));

    }

    public function prospectiveProject(){
        $proyeks = Proyek::whereHas('marketplace', function ($query) {
            $query->where('name', '=', 'Prospective Project');
        })->where('status', 1)->paginate(5);
        SEOTools::setTitle('Prospective Projects');
        SEOTools::setDescription(Voyager::setting('site.ket_pros'));
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::addImages(Voyager::image(setting('site.bg_pros')));
        SEOTools::setCanonical(url()->current());
        SEOTools::opengraph()->addProperty('type', 'articles')->setSiteName('Central Java Investment Platform')->setDescription(Voyager::setting('site.ket_pros'));
        SEOTools::twitter()->setSite('@DPMPTSPJateng')->setImages(Voyager::image(setting('site.bg_pros')))->setDescription(Voyager::setting('site.ket_pros'));
        SEOTools::jsonLd()->addImage(Voyager::image(setting('site.bg_pros')))->setUrl(url()->current())->setDescription(Voyager::setting('site.ket_pros'));


        //dd($proyeks);
        //$proyeks = Proyek::with('marketplace');
        return view('front-end.marketplace.prospective', compact('proyeks'));
    }

    public function detailPros($id, $slug){
        $proyek = Proyek::findOrFail($id);
        $images = json_decode($proyek->fotos);
        $mapsKey = 'AIzaSyBGsawbqVs083lGEe8cilVz0FqO0rHt5ZE&amp';
        SEOTools::setTitle('Prospective Projects -'.$proyek->translate('en')->project_name.' - '.$proyek->project_name);
        SEOTools::setDescription('Here is some prospective investment projects - '.$proyek->translate('en')->latar_belakang.' - '.$proyek->latar_belakang);
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::addImages(Voyager::image($images[0]));
        SEOTools::setCanonical(url()->current());
        SEOTools::opengraph()->addProperty('type', 'articles')->setSiteName('Central Java Investment Platform')->setDescription($proyek->translate('en')->latar_belakang.' - '.$proyek->latar_belakang);
        SEOTools::twitter()->setSite('@DPMPTSPJateng')->setImages(Voyager::image($images[0]))->setDescription($proyek->translate('en')->latar_belakang.' - '.$proyek->latar_belakang);
        SEOTools::jsonLd()->addImage(Voyager::image($images[0]))->setUrl(url()->current())->setDescription($proyek->translate('en')->latar_belakang.' - '.$proyek->latar_belakang);

        return view('front-end.marketplace.detail.pros', compact('proyek', 'mapsKey'));
    }

    public function potentialProject(){
        SEOTools::setTitle('Potential Projects');
        SEOTools::setDescription('Here is some potential investment projects investment project - '.Voyager::setting('site.description'));
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::addImages(Voyager::image(setting('site.bg_pot')));
        SEOTools::setCanonical(url()->current());
        SEOTools::opengraph()->addProperty('type', 'articles')->setSiteName('Central Java Investment Platform')->setDescription(Voyager::setting('site.ket_pot'));
        SEOTools::twitter()->setSite('@DPMPTSPJateng')->setImages(Voyager::image(setting('site.bg_pot')))->setDescription(Voyager::setting('site.ket_pot'));
        SEOTools::jsonLd()->addImage(Voyager::image(setting('site.bg_pros')))->setUrl(url()->current())->setDescription(Voyager::setting('site.ket_pot'));


        $proyeks = Proyek::whereHas('marketplace', function ($query) {
            $query->where('name', '=', 'Potential Project');
        })->where('status', 1)->paginate(5);
        //dd($proyeks);
        //$proyeks = Proyek::with('marketplace');
        return view('front-end.marketplace.potentials', compact('proyeks'));
    }

    public function detailPot($id, $slug){
        $proyek = Proyek::findOrFail($id);
        $mapsKey = 'AIzaSyBGsawbqVs083lGEe8cilVz0FqO0rHt5ZE&amp';
        $images = json_decode($proyek->fotos);

        SEOTools::setTitle('Potential Projects -'.$proyek->translate('en')->project_name.' - '.$proyek->project_name);
        SEOTools::setDescription('Here is some potential investment project - '.$proyek->translate('en')->latar_belakang.' - '.$proyek->latar_belakang);
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::addImages(Voyager::image($images[0]));
        SEOTools::setCanonical(url()->current());
        SEOTools::opengraph()->addProperty('type', 'articles')->setSiteName('Central Java Investment Platform')->setDescription($proyek->translate('en')->latar_belakang.' - '.$proyek->latar_belakang);
        SEOTools::twitter()->setSite('@DPMPTSPJateng')->setImages(Voyager::image($images[0]))->setDescription($proyek->translate('en')->latar_belakang.' - '.$proyek->latar_belakang);
        SEOTools::jsonLd()->addImage(Voyager::image($images[0]))->setUrl(url()->current())->setDescription($proyek->translate('en')->latar_belakang.' - '.$proyek->latar_belakang);

        return view('front-end.marketplace.detail.pot', compact('proyek', 'mapsKey'));
    }

    public function detailProyek($id){
        $proyek = Proyek::findOrFail($id);
        $mapsKey = 'AIzaSyBGsawbqVs083lGEe8cilVz0FqO0rHt5ZE&amp';
        $images = json_decode($proyek->fotos);

        SEOTools::setTitle('Detail Project Investasi -'.$proyek->translate('en')->project_name.' - '.$proyek->project_name);
        SEOTools::setDescription('Here is some potential investment project - '.$proyek->translate('en')->latar_belakang.' - '.$proyek->latar_belakang);
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::opengraph()->addProperty('type', 'articles')->setSiteName('Central Java Investment Platform')->setDescription($proyek->translate('en')->latar_belakang.' - '.$proyek->latar_belakang);
        SEOTools::twitter()->setSite('@DPMPTSPJateng')->setImages(Voyager::image($images[0]))->setDescription($proyek->translate('en')->latar_belakang.' - '.$proyek->latar_belakang);
        SEOTools::jsonLd()->addImage(Voyager::image($images[0]))->setUrl(url()->current())->setDescription($proyek->translate('en')->latar_belakang.' - '.$proyek->latar_belakang);

        return view('front-end.marketplace.detail.detailproyek', compact('proyek', 'mapsKey'));
    }

    public function detailProfile($id, $slug){
        $profil = ProfilKabupaten::findOrFail($id);
        $mapsKey = 'AIzaSyBGsawbqVs083lGEe8cilVz0FqO0rHt5ZE&amp';
        SEOTools::setTitle($profil->profil.' - '.$profil->translate('en')->profil);
        SEOTools::setDescription($profil->translate('en')->profil.' - '.$profil->profil);
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::opengraph()->addProperty('type', 'articles')->setSiteName('Central Java Investment Platform')->setDescription($profil->translate('en')->profil.' - '.$profil->profil);
        SEOTools::twitter()->setSite('@DPMPTSPJateng')->setImages('https://cjip.jatengprov.go.id/storage/settings/August2019/esr0C8HmQss78AAnlaue.png')->setDescription($profil->translate('en')->profil.' - '.$profil->profil);
        SEOTools::jsonLd()->addImage('https://cjip.jatengprov.go.id/storage/settings/August2019/esr0C8HmQss78AAnlaue.png')->setUrl(url()->current())->setDescription($profil->translate('en')->profil.' - '.$profil->profil);

        return view('front-end.marketplace.detail.profil', compact('profil', 'mapsKey'));
    }

    public function faq(){
        SEOTools::setTitle('FAQ');
        SEOTools::setDescription('Frequently Asked Question about investment in Central Java - Pertanyaan yang sering muncul ketika Anda ingin berinvestasi di Provinsi Jawa Tengah');
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::addImages(Voyager::image(setting('site.bg_why')));
        SEOTools::opengraph()->addProperty('type', 'articles')->setSiteName('Central Java Investment Platform')->setDescription('Frequently Asked Question about investment in Central Java - Pertanyaan yang sering muncul ketika Anda ingin berinvestasi di Provinsi Jawa Tengah');
        SEOTools::twitter()->setSite('@DPMPTSPJateng')->setImages(Voyager::image(setting('site.bg_why')))->setDescription('Frequently Asked Question about investment in Central Java - Pertanyaan yang sering muncul ketika Anda ingin berinvestasi di Provinsi Jawa Tengah');
        SEOTools::jsonLd()->addImage(Voyager::image(setting('site.bg_why')))->setUrl(url()->current())->setDescription('Frequently Asked Question about investment in Central Java - Pertanyaan yang sering muncul ketika Anda ingin berinvestasi di Provinsi Jawa Tengah');

        $faqs = Faq::all()->groupBy('jenis_faq');
        //dd($faqs);
        foreach ($faqs as $key => $faq){
            //dd($key);
        }
        $jns_faq = JenisFaq::all();
        //dd($proyeks);
        //$proyeks = Proyek::with('marketplace');
        return view('front-end.faq', compact('faqs', 'jns_faq'));
    }

    public function checkEmail(Request $request){
        if($request->get('email'))
        {
            $email = $request->get('email');
            $data = DB::table("user_investors")
                ->where('email', $email)
                ->count();
            if($data > 0)
            {
                echo 'not_unique';
            }
            else
            {
                echo 'unique';
            }
        }
    }

    public function bySector($slug){
        //dd($slug);

        $proyeks = Proyek::whereHas('bySector', function ($query) use ($slug) {
            //dd($slug);
            $query->where('name', '=', $slug);
        })->where('status', 1)->paginate(5);


        SEOTools::setTitle($slug);
        SEOTools::setDescription('Central Java Investment on Sector '.$slug);
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::addImages(Voyager::image(setting('site.bg_why')));
        SEOTools::opengraph()->addProperty('type', 'articles')->setSiteName('Central Java Investment Platform')->setDescription('Central Java Investment on Sector '.$slug);
        SEOTools::twitter()->setSite('@DPMPTSPJateng')->setImages(Voyager::image(setting('site.bg_why')))->setDescription('Central Java Investment on Sector '.$slug);
        SEOTools::jsonLd()->addImage(Voyager::image(setting('site.bg_why')))->setUrl(url()->current())->setDescription('Central Java Investment on Sector '.$slug);

        //dd($proyeks);
        return view('front-end.marketplace.bysector', compact('proyeks', 'slug'));
    }
    public function byCity($id){
        //dd($slug);

        $kabId = KabkotaUserModel::where('kab_kota_id', $id)->first();
        //dd($kabId);
        $userId = $kabId->user_id;
        //dd($userId);
        $user = User::findOrFail($userId);
        $proyeks = Proyek::whereHas('byUser', function ($query) use ($userId) {
            //dd($slug);
            $query->where('id', '=', $userId);
        })->where('status', 1)->get();
        //dd($proyeks);

        foreach ($proyeks as $proyek){
            $image = json_decode($proyek->fotos);
            //dd($proyek->bySector);
        }
        SEOTools::setTitle($user->name);
        SEOTools::setDescription('Central Java Investment on City/Regency '.$user->name);
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::addImages(Voyager::image(setting('site.bg_why')));
        SEOTools::opengraph()->addProperty('type', 'articles')->setSiteName('Central Java Investment Platform')->setDescription('Central Java Investment on  City/Regency'.$user->name);
        SEOTools::twitter()->setSite('@DPMPTSPJateng')->setImages(Voyager::image(setting('site.bg_why')))->setDescription('Central Java Investment on  City/Regency'.$user->name);
        SEOTools::jsonLd()->addImage(Voyager::image(setting('site.bg_why')))->setUrl(url()->current())->setDescription('Central Java Investment on City/Regency '.$user->name);


        return view('front-end.marketplace.detail.proyek', compact('proyeks'));
    }
    public function findBySector($id){
        $user = Auth::guard('investor')->user()->id;
        $profile = ProfileInvestor::where('user_id', $user)->first();
        $proyeks = Proyek::where('sektor_id', $id)->where('status', 1)->get();
        //dd($proyeks);
        foreach ($proyeks as $proyek){
            //dd($proyek->bySector);
        }
        //dd($proyeks[0]->byUser->namakota);

        return view('front-end.marketplace.detail.proyek', compact('proyeks', 'profile'));
    }
    public function findInterestBySector($id){
        $user = Auth::guard('investor')->user()->id;
        $profile = ProfileInvestor::where('user_id', $user)->first();
        $proyeks = Proyek::where('sektor_id', $id)->where('status', 1)->get();
        //dd($proyeks);
        foreach ($proyeks as $proyek){
            //dd($proyek->bySector);
        }
        //dd($proyeks[0]->byUser->namakota);

        return view('front-end.investor.content.interest', compact('proyeks', 'profile'));
    }

    public function maps(){
        $proyeks = Proyek::where('status', 1)->get();

        foreach ($proyeks as $proyek){
            $images = json_decode($proyek->fotos);
            //dd($images[0]);
        }
        SEOTools::setTitle('Central Java Investment by Location');
        SEOTools::setDescription('Discover Central Java Investment on Maps');
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::setCanonical(url()->current());
        SEOTools::addImages(Voyager::image(setting('site.bg_why')));
        SEOTools::opengraph()->addProperty('type', 'website')->setSiteName('Central Java Investment Platform')->setDescription('Discover all Central Java Investment Projects using Maps, Temukan semua projek investasi Jawa Tengah melalui peta');
        SEOTools::twitter()->setSite('@DPMPTSPJateng')->setImages(Voyager::image(setting('site.bg_why')))->setDescription('Discover all Central Java Investment Projects using Maps, Temukan semua projek investasi Jawa Tengah melalui peta');
        SEOTools::jsonLd()->addImage(Voyager::image(setting('site.bg_why')))->setUrl(url()->current())->setDescription('Discover all Central Java Investment Projects using Maps, Temukan semua projek investasi Jawa Tengah melalui peta');


        //dd(asText($proyek->location));

        Mapper::location('Central Java')->map(['zoom' => 8, 'center' => true, 'marker' => false, 'type' => 'ROAD']);

        foreach ($proyeks as $proyek){
            $images = json_decode($proyek->fotos);
            //dd($images[0]);
            //dd(json_encode($proyek->getCoordinates()));
            //dump($images);
            $array = $proyek->getCoordinates();
            //dd((float)$array[0]['lat']);

            if ($proyek->marketplace->name == 'Ready to Offer'){
                Mapper::informationWindow((float)$array[0]['lat'], (float)$array[0]['lng'], '<div id="iw-container">'.
                    '<div class="iw-title">'.$proyek->marketplace->name.'</div>'.
                    '<div class="iw-content">'.
                    '<div class="iw-subTitle">'.$proyek->translate('en')->project_name.'</div>' .
                    '<img src='.'"'.Voyager::image($images[0]).'"'.' alt='.'"'.$proyek->translate('en')->project_name.'"'.' height="115" width="83">' .
                    '<p>'.$proyek->translate('en')->latar_belakang.'</p>'.
                    '<div class="iw-subTitle"><a style="font-weight: 300;color: #c82333" href='.'"'.route('detail.proyek', $proyek->id).'"'.'>Detail</a></div>' .
                    '</div>' .
                    '<div class="iw-bottom-gradient"></div>' .
                    '</div>',
                    ['icon' => 'http://cjip.jatengprov.go.id/storage/additional/ICON/readys.png']
                );
            }
            elseif ($proyek->marketplace->name == 'Prospective Project'){
                Mapper::informationWindow((float)$array[0]['lat'], (float)$array[0]['lng'], '<div id="iw-container">'.
                    '<div class="iw-title">'.$proyek->marketplace->name.'</div>'.
                    '<div class="iw-content">'.
                    '<div class="iw-subTitle">'.$proyek->translate('en')->project_name.'</div>' .
                    '<img src='.'"'.Voyager::image($images[0]).'"'.' alt='.'"'.$proyek->translate('en')->project_name.'"'.' height="115" width="83">' .
                    '<p>'.$proyek->translate('en')->latar_belakang.'</p>'.
                    '<div class="iw-subTitle"><a style="font-weight: 300;color: #c82333" href='.'"'.route('detail.proyek', $proyek->id).'"'.'>Detail</a></div>' .
                    '</div>' .
                    '<div class="iw-bottom-gradient"></div>' .
                    '</div>',
                    ['icon' => 'http://cjip.jatengprov.go.id/storage/additional/ICON/prospectives.png']
                );
            }
            else{
                Mapper::informationWindow((float)$array[0]['lat'], (float)$array[0]['lng'], '<div id="iw-container">'.
                    '<div class="iw-title">'.$proyek->marketplace->name.'</div>'.
                    '<div class="iw-content">'.
                    '<div class="iw-subTitle">'.$proyek->translate('en')->project_name.'</div>' .
                    '<img src='.'"'.Voyager::image($images[0]).'"'.' alt='.'"'.$proyek->translate('en')->project_name.'"'.' height="115" width="83">' .
                    '<p>'.$proyek->translate('en')->latar_belakang.'</p>'.
                    '<div class="iw-subTitle"><a style="font-weight: 300;color: #c82333" href='.'"'.route('detail.proyek', $proyek->id).'"'.'>Detail</a></div>' .
                    '</div>' .
                    '<div class="iw-bottom-gradient"></div>' .
                    '</div>',
                    ['icon' => 'http://cjip.jatengprov.go.id/storage/additional/ICON/potentials.png']
                );
            }


        }
        //die();

        //dd($array);
        $mapsKey = 'AIzaSyBGsawbqVs083lGEe8cilVz0FqO0rHt5ZE&amp';

        return view('front-end.marketplace.bylocation', compact('array', 'mapsKey'));
    }

    public function investmentOpportunities(){
        $proyeks = Proyek::where('status', 1)->paginate(10);
        $mapsKey = 'AIzaSyBGsawbqVs083lGEe8cilVz0FqO0rHt5ZE&amp';

        SEOTools::setTitle('Potential Projects');
        SEOTools::setDescription('Here is some potential investment projects investment project - '.Voyager::setting('site.ket_why'));
        SEOTools::opengraph()->setUrl(url()->current());
        SEOTools::addImages(Voyager::image(setting('site.bg_why')));
        SEOTools::setCanonical(url()->current());
        SEOTools::opengraph()->addProperty('type', 'website')->setSiteName('Central Java Investment Platform')->setDescription('Discover all Central Java Investment Projects, Temukan semua projek investasi Jawa Tengah');
        SEOTools::twitter()->setSite('@DPMPTSPJateng')->setImages(Voyager::image(setting('site.bg_why')))->setDescription('Discover all Central Java Investment Projects, Temukan semua projek investasi Jawa Tengah');
        SEOTools::jsonLd()->addImage(Voyager::image(setting('site.bg_why')))->setUrl(url()->current())->setDescription('Discover all Central Java Investment Projects, Temukan semua projek investasi Jawa Tengah');

        return view('front-end.investment-opportunities', compact('proyeks', 'mapsKey'));

    }
}
