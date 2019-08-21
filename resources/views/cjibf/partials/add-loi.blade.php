@extends('voyager::master')
@section('css')
    <link rel="stylesheet" href="{{asset('css/front-end/cjibf.css')}}">
    <link rel="stylesheet" href="{{asset('css/cjibf.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{asset('css/front-end/main.css')}}" id="main_style">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,800" rel="stylesheet">
    <link href="https://cdn.materialdesignicons.com/2.0.46/css/materialdesignicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('sass')}}">
@endsection
@section('page_header')
    <h1 class="page-title">
        {{ 'Input Rencana Realisasi' }}
    </h1>
    @include('voyager::multilingual.language-selector')
@stop
@section('content')
    <div class="col-lg-12">
        <div class="col-lg-6 mb-4">
            <div class="card card-small mb-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">Company Details</h6>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                        <div class="row">
                            <div class="col">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="feFirstName">Name</label>
                                            <input type="text" class="form-control" id="feFirstName" name="name"
                                                   placeholder="Full Name" value="{{$profile->investor_name}}" disabled>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="position">Position</label>
                                            <input type="text" class="form-control" id="position" name="jabatan"
                                                   placeholder="Position" value="{{$profile->jabatan}}" disabled>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="phone">Phone</label>
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                   placeholder="Phone" value="{{$profile->phone}}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="companyname">Company Name</label>
                                            <input type="text" class="form-control" id="companyname" name="company_name"
                                                   placeholder="Email" value="{{$profile->nama_perusahaan}}" disabled>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="badan_hukum">Corporation</label>
                                            <input type="text" class="form-control" id="badan_hukum" name="badan_hukum"
                                                   value="{{$profile->badan_hukum}}" disabled>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="bidang_usaha">Business Field</label>
                                            <input type="text" class="form-control" id="bidang_usaha"
                                                   name="bidang_usaha" value="{{$profile->bidang_usaha}}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="feInputAddress">Address</label>
                                        <input type="text" class="form-control" id="feInputAddress" name="address"
                                               placeholder="1234 Main St" value="{{$profile->alamat}}" disabled>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" name="email" id="email"
                                                   value="{{$profile->userInv->email}}" disabled>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="country">Country</label>
                                            <input type="text" class="form-control" id="country" name="country"
                                                   value="{{$profile->country}}" disabled>
                                        </div>
                                    </div>

                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header border-bottom">
                    <h6 class="m-0">LoI</h6>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                        <div class="row">
                            <div class="col-12">
                                <form action="{{route('loi-cjibf.post', [$profile->id , $peserta->id])}}" method="post">
                                    @csrf
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="sektor">Sector</label>
                                            <input type="text" class="form-control" id="sektor" name="sektor"
                                                   placeholder="Business Field (Industry, Tourism, etc)"
                                                   value="{{$profile->sektors->sektor_interest}}" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="lokasi">Location</label>
                                            <input type="text" class="form-control" id="lokasi" name="lokasi"
                                                   placeholder="Detail Location" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="inv">Investment Value</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <select id="alarm_action" class="form-control" required>
                                                        <option selected>Choose...</option>
                                                        <option value='rupiah'>Rupiah</option>
                                                        <option value='dollar'>US Dollar</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <input class="form-control" name="rp" placeholder="Rupiah" id="rp"
                                                   style="display: none"/>
                                            <input class="form-control" name="usd" id="usd" placeholder="USD"
                                                   style="display: none"/>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block">Invest Now !!!</button>
                                </form>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
    <script>
        $('#rp').inputmask("numeric", {
            radixPoint: ".",
            groupSeparator: ".",
            digits: 3,
            autoGroup: true,
            /* prefix: 'Rp. ',*/ //Space after $, this will not truncate the first character.
            rightAlign: false,
            oncleared: function () {
                self.Value('');
            }
        });
    </script>
    <script>
        $('#usd').inputmask("numeric", {
            radixPoint: ".",
            groupSeparator: ".",
            digits: 3,
            autoGroup: true,
            /* prefix: 'Rp. ',*/ //Space after $, this will not truncate the first character.
            rightAlign: false,
            oncleared: function () {
                self.Value('');
            }
        });
    </script>
    <script>
        var alarmInput = $('#alarm_action');
        alarmInput.on('change', function () {
            var rp = $('#rp');
            var usd = $('#usd');
            //this == alarmInput within this change handler
            switch ($(this).val()) {
                case 'rupiah':
                    rp.show();
                    usd.hide();
                    break;
                case 'dollar':
                    rp.hide();
                    usd.show();
                    break;
            }
        });
    </script>
@endsection