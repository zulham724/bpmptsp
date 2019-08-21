<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/



Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

    Route::get('/chatembed', function (){
        return view('chat.chat-embed');
    })->name('cp-provsd');

    Route::get('/reset', 'CJIBF\LayoutController@reset')->name('reset');
    Route::get('/event', 'CJIBF\LayoutController@eventHome')->name('event.home');
    Route::get('/cjibf', 'CJIBF\LayoutController@eventSetting')->name('event.form');
    Route::post('/cjibf', 'CJIBF\LayoutController@eventStore')->name('event.post');


    Route::get('/layout', 'CJIBF\LayoutController@layoutForm')->name('layout.form');
    Route::post('/layout', 'CJIBF\LayoutController@layoutPost')->name('layout.post');

    Route::get('/cjibf/layout/table', 'CJIBF\LayoutController@mejaShow')->name('meja.show');
    Route::post('/cjibf/layout/table', 'CJIBF\LayoutController@mejaPost')->name('meja.post');
    Route::get('/cjibf/layout/table-filter', 'CJIBF\LayoutController@filterShow')->name('mejafilter.show');
    Route::patch('/cjibf/layout/table-filter', 'CJIBF\LayoutController@filterPost')->name('mejafilter.post');
    Route::get('/cjibf/layout/table-kabkota', 'CJIBF\LayoutController@kabkotaShow')->name('kabkota.show');
    Route::patch('/cjibf/layout/table-kabkota', 'CJIBF\LayoutController@kabkotaPost')->name('kabkota.post');


    Route::get('/cjibf/loi/{profil_id}/{id}', 'CJIBF\LayoutController@Loi')->name('loi.cjibf');
    Route::post('/cjibf/loi/{profil_id}/{id}', 'CJIBF\LayoutController@addLoi')->name('loi-cjibf.post');

    Route::get('/cjibf/loimanual/', 'CJIBF\LayoutController@LoiManual')->name('loi.manual');
    Route::post('/cjibf/loimanual/', 'CJIBF\LayoutController@addLoiManual')->name('loi-cjibf.manual');

    Route::get('/daftar-hadir', 'CJIBF\DaftarHadirController@index')->name('daftar.hadir');
    Route::get('/cetak-daftar-hadir', 'CJIBF\DaftarHadirController@cetak')->name('cetak.daftar-hadir');
    Route::get('/cetakmeja-daftar-hadir', 'CJIBF\DaftarHadirController@cetakMeja')->name('cetakpermeja.daftar-hadir');

});

        Route::get('/x', 'FrontEnd\Provinsi\VideoPlayerController@home')->name('homey');
        Route::get('/', 'FrontEnd\Home\HomeController@home')->name('homey2');
        Route::get('/sidebar', 'FrontEnd\Home\HomeController@sidebar')->name('sidebar');
        Route::get('/menu', 'FrontEnd\Provinsi\VideoPlayerController@menu')->name('menu');
        Route::get('/search', 'FrontEnd\Home\SearchController@search')->name('search');
        Route::get('/live-count', function (){
            return view('front-end.live-count.live-count');
        })->name('live.count');


        Route::post('/feed/{id}/like', 'FrontEnd\Home\HomeController@likes')->name('likes.count');

        Route::get('pariwisata/', 'FrontEnd\Potensi\PariwisataController@pariwisata')->name('only.pariwisata');
        Route::get('perikanan/', 'FrontEnd\Potensi\PerikananController@perikanan')->name('only.perikanan');
        Route::get('pertanian/', 'FrontEnd\Potensi\PertanianController@pertanian')->name('only.pertanian');
        Route::get('perkebunan/', 'FrontEnd\Potensi\PerkebunanController@perkebunan')->name('only.perkebunan');
        Route::get('peternakan/', 'FrontEnd\Potensi\PeternakanController@peternakan')->name('only.peternakan');

        /*Route::get('/pariwisata', 'FrontEnd\Provinsi\VideoPlayerController@pariwisata')->name('pariwisata');*/
        Route::get('/pariwisata/{id}', 'FrontEnd\Provinsi\VideoPlayerController@readPariwisata')->name('pariwisata.detail');
        Route::get('/pariwisata/maps/{id}', 'FrontEnd\Provinsi\VideoPlayerController@mapsPariwisata')->name('pariwisata.maps');

        /*Route::get('/perikanan', 'FrontEnd\Provinsi\VideoPlayerController@perikanan')->name('perikanan');*/
        Route::get('/perikanan/{id}', 'FrontEnd\Provinsi\VideoPlayerController@readPerikanan')->name('perikanan.detail');

        /*Route::get('/pertanian', 'FrontEnd\Provinsi\VideoPlayerController@pertanian')->name('pertanian');*/
        Route::get('/pertanian/{id}', 'FrontEnd\Provinsi\VideoPlayerController@readPertanian')->name('pertanian.detail');

        /*Route::get('/perkebunan', 'FrontEnd\Provinsi\VideoPlayerController@perkebunan')->name('perkebunan');*/
        Route::get('/perkebunan/{id}', 'FrontEnd\Provinsi\VideoPlayerController@readPerkebunan')->name('perkebunan.detail');

        /*Route::get('/peternakan', 'FrontEnd\Provinsi\VideoPlayerController@peternakan')->name('peternakan');*/
        Route::get('/peternakan/{id}', 'FrontEnd\Provinsi\VideoPlayerController@readPeternakan')->name('peternakan.detail');

        Route::get('/sarpras', 'FrontEnd\SaranaPrasarana\SarprasController@sarpras')->name('sarpras');

        Route::get('/kajian', 'FrontEnd\Kajian\KajianController@kajian')->name('kajian');

        Route::get('/regulasi', 'FrontEnd\Regulasi\RegulasiController@regulasi')->name('regulasi');

        Route::get('/news', 'FrontEnd\News\NewsController@news')->name('news');
        Route::get('/news/{id}', 'FrontEnd\News\NewsController@readBerita')->name('berita.detail');

        Route::get('/profile_jateng', 'FrontEnd\Profile\ProfileController@profile')->name('profile_jateng');

        Route::get('/profile_jateng/{id}', 'FrontEnd\Profile\ProfileController@profileDetail')->name('detail.profile_jateng');


Route::get('/admin/perikanans/{id}/edit', 'Admin\Kabkota\Potensi\PerikananController@edit')->name('edit.perikanan');
Route::delete('/admin/perikanans/delete/{id}', 'Admin\Kabkota\Potensi\PerikananController@destroy')->name('delete.perikanan');
Route::get('/admin/perkebunans/{id}/edit', 'Admin\Kabkota\Potensi\PerkebunanController@edit')->name('edit.perkebunan');
Route::delete('/admin/perkebunans/delete/{id}', 'Admin\Kabkota\Potensi\PerkebunanController@destroy')->name('delete.perkebunan');
Route::get('/admin/pertanians/{id}/edit', 'Admin\Kabkota\Potensi\PertanianController@edit')->name('edit.pertanian');
Route::delete('/admin/pertanians/delete/{id}', 'Admin\Kabkota\Potensi\PertanianController@destroy')->name('delete.pertanian');
Route::get('/admin/peternakans/{id}/edit', 'Admin\Kabkota\Potensi\PeternakanController@edit')->name('edit.peternakan');
Route::delete('/admin/peternakans/delete/{id}', 'Admin\Kabkota\Potensi\PeternakanController@destroy')->name('delete.peternakan');


Route::post('/admin/post-loi', 'Loi\LoiController@postloi')->name('post.loi');
Route::patch('/admin/post-loi/{id}', 'Loi\LoiController@updateloi')->name('update.loi');

Route::get('/admin/lois/pdf/{id}', 'Loi\LoiController@cetakPdf')->name('cetak.loi');



Auth::routes();
Route::get('/chats', 'Chat\ChatController@index')->name('home');
Route::get('/contacts', 'Chat\ContactsController@get');
Route::get('/conversation/{id}', 'Chat\ContactsController@getMessagesFor');
Route::post('/conversation/send', 'Chat\ContactsController@send');
/*Route::get('home', function () {

    return view('home');

})->name('home');*/


Route::get('login/', 'Auth\LoginController@showInvestorLoginForm')->name('show.login');
Route::post('login/', 'Auth\LoginController@investorLogin')->name('investor.login');
Route::get('register/', 'Auth\RegisterController@showInvestorRegisterForm')->name('show.register');
Route::post('register/', 'Auth\RegisterController@createInvestor')->name('investor.register');
Route::get('login/{provider}', 'Auth\SocialController@redirect');
Route::get('login/{provider}/callback','Auth\SocialController@Callback');

Route::get('sarpras/{sarpras}', 'Frontend\SaranaPrasarana\SarprasController@detail')->name('sarpras2');
Route::get('sarpras/bandara/{id}', 'Frontend\SaranaPrasarana\SarprasController@bandara')->name('maps.bandara');
Route::get('sarpras/pelabuhan/{id}', 'Frontend\SaranaPrasarana\SarprasController@pelabuhan')->name('maps.pelabuhan');
Route::get('sarpras/kereta/{id}', 'Frontend\SaranaPrasarana\SarprasController@kereta')->name('maps.kereta');
Route::get('sarpras/listrik/{id}', 'Frontend\SaranaPrasarana\SarprasController@listrik')->name('maps.listrik');
Route::get('sarpras/gas/{id}', 'Frontend\SaranaPrasarana\SarprasController@gas')->name('maps.gas');
Route::get('sarpras/waduk/{id}', 'Frontend\SaranaPrasarana\SarprasController@waduk')->name('maps.waduk');
Route::get('sarpras/tol/{id}', 'Frontend\SaranaPrasarana\SarprasController@tol')->name('maps.tol');
Route::get('sarpras/smk/{id}', 'Frontend\SaranaPrasarana\SarprasController@smk')->name('maps.smk');
Route::get('sarpras/bpk/{id}', 'Frontend\SaranaPrasarana\SarprasController@bpk')->name('maps.bpk');
Route::get('sarpras/lpk/{id}', 'Frontend\SaranaPrasarana\SarprasController@lpk')->name('maps.lpk');
Route::get('sarpras/bank/{id}', 'Frontend\SaranaPrasarana\SarprasController@bank')->name('maps.bank');
Route::get('sarpras/ki/{id}', 'Frontend\SaranaPrasarana\SarprasController@mapsKi')->name('maps.ki');


Route::middleware('auth:investor')->group(function () {
    Route::get('profile/{id}','FrontEnd\Investor\ProfilController@showProfileForm')->name('form.profile');
    Route::get('dashboard/{id}','FrontEnd\Investor\ProfilController@dashboard')->name('dashboard.investor');
    Route::patch('dashboard/{id}','FrontEnd\Investor\ProfilController@updateProfile')->name('update.investor');
    Route::post('profile/','FrontEnd\Investor\ProfilController@storeProfile')->name('store.profile');

    Route::get('investment/','FrontEnd\Investor\ProfilController@investment')->name('investment.investor');
    Route::post('investment/','FrontEnd\Investor\ProfilController@investmentPost')->name('investment.post');
    Route::get('investment/{id}','FrontEnd\Investor\ProfilController@investmentEdit')->name('edit.investment');
    Route::patch('investment/{id}','FrontEnd\Investor\ProfilController@updateInvestment')->name('update.investment');


    Route::get('cjibf', 'CJIBF\FrontEndController@front')->name('frontend.cjibf');
    Route::post('cjibf', 'CJIBF\FrontEndController@join')->name('join.cjibf');


    Route::get('company_profile/','FrontEnd\Investor\ProfilController@daftar')->name('daftar');

    Route::get('loi/','FrontEnd\Investor\InterestController@showInterestForm')->name('form.interest');
    Route::post('loi/','FrontEnd\Investor\InterestController@storeInterest')->name('store.interest');

    Route::get('mail/daftar-cjibf','Mail\CJIBF\DaftarCjibfController@send')->name('daftar.cjibf');

});

Route::post('feedback', 'Feedback\FeedbackController@feedback')->name('feedback');

/*Route::get('tes/mail', function (){
    return view('vendor.maileclipse.templates.attach');
});
Route::get('testchart', function (){
    return view('front-end.content.profile.kabkota.luaswilayah');
});*/


