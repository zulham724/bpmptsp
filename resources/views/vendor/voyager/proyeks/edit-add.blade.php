@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    <!-- form start -->
                    <form role="form"
                            class="form-edit-add"
                            action="{{ $edit ? route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) : route('voyager.'.$dataType->slug.'.store') }}"
                            method="POST" enctype="multipart/form-data">
                        <!-- PUT Method if we are editing -->
                        @if($edit)
                            {{ method_field("PUT") }}
                        @endif

                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <div class="panel-body">

                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Adding / Editing -->
                            @php
                                $dataTypeRows = $dataType->{($edit ? 'editRows' : 'addRows' )};
                            @endphp

                        @if(Auth::user()->hasRole('kab'))
                            @foreach($dataTypeRows as $row)
                                <!-- GET THE DISPLAY OPTIONS -->
                                @php
                                    $display_options = $row->details->display ?? NULL;
                                    if ($dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')}) {
                                        $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')};
                                    }
                                @endphp
                                @if (isset($row->details->legend) && isset($row->details->legend->text))
                                    <legend class="text-{{ $row->details->legend->align ?? 'center' }}" style="background-color: {{ $row->details->legend->bgcolor ?? '#f0f0f0' }};padding: 5px;">{{ $row->details->legend->text }}</legend>
                                @endif

                                    <div class="form-group @if($row->type == 'hidden') hidden @endif col-md-{{ $display_options->width ?? 12 }} {{ $errors->has($row->field) ? 'has-error' : '' }}" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                                        {{ $row->slugify }}
                                        @if($row->display_name == 'Kab Kota Id')
                                        @elseif($row->display_name == 'Status')
                                        @else
                                            <label class="control-label" for="name">{{ $row->display_name }}</label>
                                        @endif
                                        @include('voyager::multilingual.input-hidden-bread-edit-add')
                                        @if (isset($row->details->view))
                                            @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => ($edit ? 'edit' : 'add')])
                                        @elseif ($row->type == 'relationship')
                                            @include('voyager::formfields.relationship', ['options' => $row->details])
                                        @elseif ($row->type == 'coordinates')
                                            <a href="javascript:void(0)" onclick="loadthemappicker()" class="btn btn-sm btn-success"><i class="fa fa-map"></i> Pilih Lokasi</a>
                                            @forelse($dataTypeContent->getCoordinates() as $point)
                                                <input type="hidden" name="{{ $row->field }}[lat]" value="{{ $point['lat'] }}" id="lat"/>
                                                <input type="hidden" name="{{ $row->field }}[lng]" value="{{ $point['lng'] }}" id="lng"/>
                                            @empty
                                                <input type="hidden" name="{{ $row->field }}[lat]" value="{{ config('voyager.googlemaps.center.lat') }}" id="lat"/>
                                                <input type="hidden" name="{{ $row->field }}[lng]" value="{{ config('voyager.googlemaps.center.lng') }}" id="lng"/>
                                            @endforelse
                                            <p id="koordinat"></p>
                                            <div id="map_piker" style="height: 300px!important;"></div>

                                        @elseif ($row->field == 'kab_kota_id')
                                            <input type="hidden" value="{{Auth::user()->id}}" name="kab_kota_id">
                                        @elseif ($row->field == 'status')
                                            <input type="hidden" value="0" name="status">
                                        @else
                                            {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                                        @endif

                                        @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                                            {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                                        @endforeach
                                        @if ($errors->has($row->field))
                                            @foreach ($errors->get($row->field) as $error)
                                                <span class="help-block">{{ $error }}</span>
                                            @endforeach
                                        @endif
                                    </div>
                            @endforeach
                        @else
                            @foreach($dataTypeRows as $row)
                                    <!-- GET THE DISPLAY OPTIONS -->
                                        @php
                                            $display_options = $row->details->display ?? NULL;
                                            if ($dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')}) {
                                                $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')};
                                            }
                                        @endphp
                                        @if (isset($row->details->legend) && isset($row->details->legend->text))
                                            <legend class="text-{{ $row->details->legend->align ?? 'center' }}" style="background-color: {{ $row->details->legend->bgcolor ?? '#f0f0f0' }};padding: 5px;">{{ $row->details->legend->text }}</legend>
                                        @endif

                                        <div class="form-group @if($row->type == 'hidden') hidden @endif col-md-{{ $display_options->width ?? 12 }} {{ $errors->has($row->field) ? 'has-error' : '' }}" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                                            {{ $row->slugify }}
                                            <label class="control-label" for="name">{{ $row->display_name }}</label>
                                            @include('voyager::multilingual.input-hidden-bread-edit-add')
                                            @if (isset($row->details->view))
                                                @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => ($edit ? 'edit' : 'add')])
                                            @elseif ($row->type == 'relationship')
                                                @include('voyager::formfields.relationship', ['options' => $row->details])
                                            @elseif ($row->type == 'coordinates')
                                                <a href="javascript:void(0)" onclick="loadthemappicker()" class="btn btn-sm btn-success"><i class="fa fa-map"></i> Pilih Lokasi</a>
                                                @forelse($dataTypeContent->getCoordinates() as $point)
                                                    <input type="hidden" name="{{ $row->field }}[lat]" value="{{ $point['lat'] }}" id="lat"/>
                                                    <input type="hidden" name="{{ $row->field }}[lng]" value="{{ $point['lng'] }}" id="lng"/>
                                                @empty
                                                    <input type="hidden" name="{{ $row->field }}[lat]" value="{{ config('voyager.googlemaps.center.lat') }}" id="lat"/>
                                                    <input type="hidden" name="{{ $row->field }}[lng]" value="{{ config('voyager.googlemaps.center.lng') }}" id="lng"/>
                                                @endforelse
                                                <p id="koordinat"></p>
                                                <div id="map_piker" style="height: 300px!important;"></div>
                                            @elseif ($row->field == 'kab_kota_id')
                                                <select class="form-control select2" name="kab_kota_id">

                                                    @foreach($users as $user)
                                                        @if(is_null($dataTypeContent->kab_kota_id))
                                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                                        @else
                                                            <option value="{{$dataTypeContent->kab_kota_id}}" selected>{{$dataTypeContent->kabkota->name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>

                                            @else
                                                {{--@if(Auth::user()->hasRole('kab'))--}}

                                                {{--@endif--}}
                                                {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                                            @endif

                                            @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                                                {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                                            @endforeach
                                            @if ($errors->has($row->field))
                                                @foreach ($errors->get($row->field) as $error)
                                                    <span class="help-block">{{ $error }}</span>
                                                @endforeach
                                            @endif
                                        </div>
                                    @endforeach
                        @endif
                        </div><!-- panel-body -->

                        <div class="panel-footer">
                            @section('submit-buttons')
                                <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                            @stop
                            @yield('submit-buttons')
                        </div>
                    </form>

                    <iframe id="form_target" name="form_target" style="display:none"></iframe>
                    <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post"
                            enctype="multipart/form-data" style="width:0;height:0;overflow:hidden">
                        <input name="image" id="upload_file" type="file"
                                 onchange="$('#my_form').submit();this.value='';">
                        <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
                        {{ csrf_field() }}
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
                </div>

                <div class="modal-body">
                    <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete File Modal -->
@stop

@section('javascript')
    <script type="text/javascript" src="{{asset('js/map/app.js')}}"></script>
    <script type="text/javascript">
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(setPosition);
            } else {
                console.log('none');
            }
        }

        function setPosition(position) {
            window.my_latitude = position.coords.latitude;
            window.my_longitude = position.coords.longitude;
        }

        getLocation();
        function ubahkoor() {
            var lat 	= $('#koordinat_lokasi_lat').val();
            var long 	= $('#koordinat_lokasi_long').val();
            var koor 	= lat+','+long;

            $('#koordinat_lokasi').val(koor);
        }
        function loadthemappicker(lt, lg) {
            if (lt==null) {
                lt = -6.983306;
            };
            if (lg==null) {
                lg = 110.407650;
            };

            if ($('#pac-input').length<=0) {
                $('<input id="pac-input" class="controls" type="text" placeholder="Search Box">').insertAfter('#koordinat');
            }
            loadScript("https://maps.googleapis.com/maps/api/js?key=AIzaSyBGsawbqVs083lGEe8cilVz0FqO0rHt5ZE&amp;sensor=false&libraries=places",function(){
                function updateMarkerPosition(latLng) {
                    var lat = [latLng.lat()];
                    var lng = [latLng.lng()];
                    $("#lat").val(lat);
                    $("#lng").val(lng);
                    $("#koordinat").html(lat+','+lng);
                }

                var map = new google.maps.Map(document.getElementById('map_piker'), {
                    zoom: 12,
                    center: new google.maps.LatLng(lt, lg),
                    // mapTypeId: google.maps.MapTypeId.ROADMAP
                    mapTypeId: 'satellite'
                });

                // Create the search box and link it to the UI element.
                var input = document.getElementById('pac-input');
                var searchBox = new google.maps.places.Autocomplete(input);
                //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

                // Bias the SearchBox results towards current map's viewport.
                /*map.addListener('bounds_changed', function() {
                  searchBox.setBounds(map.getBounds());
                });*/
                searchBox.bindTo('bounds', map);


                var latLng = new google.maps.LatLng(lt, lg);
                var marker = new google.maps.Marker({
                    position : latLng,
                    title : 'Pilih Lokasi',
                    map : map,
                    draggable : true
                });

                searchBox.addListener('place_changed', function() {
                    var place = window.placenya = searchBox.getPlace();
                    var place_nama = place.name;
                    var place_lat = place.geometry.location.lat();
                    var place_lng = place.geometry.location.lng();
                    // if (!place.geometry) {
                    //   // User entered the name of a Place that was not suggested and
                    //   // pressed the Enter key, or the Place Details request failed.
                    //   window.alert("No details available for input: '" + place.name + "'");
                    //   return;
                    // }

                    // If the place has a geometry, then present it on a map.
                    if (place.geometry.viewport) {
                        map.fitBounds(place.geometry.viewport);
                    } else {
                        map.setCenter(place.geometry.location);
                        map.setZoom(20);  // Why 17? Because it looks good.
                    }

                    console.log(place_lat+','+place_lng);
                    var default_location = new google.maps.LatLng(Number(place_lat),Number(place_lng));
                    var latLng = default_location;
                    updateMarkerPosition(latLng);
                    marker.setPosition(place.geometry.location);
                    //map.fitBounds(bounds);
                });

                updateMarkerPosition(latLng);
                google.maps.event.addListener(marker, 'drag', function() {
                    updateMarkerPosition(marker.getPosition());
                });
            });
            $('#modallokasi').modal('show')

        }
    </script>
    <script>
        var params = {};
        var $file;

        function deleteHandler(tag, isMulti) {
          return function() {
            $file = $(this).siblings(tag);

            params = {
                slug:   '{{ $dataType->slug }}',
                filename:  $file.data('file-name'),
                id:     $file.data('id'),
                field:  $file.parent().data('field-name'),
                multi: isMulti,
                _token: '{{ csrf_token() }}'
            }

            $('.confirm_delete_name').text(params.filename);
            $('#confirm_delete_modal').modal('show');
          };
        }

        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();

            //Init datepicker for date fields if data-datepicker attribute defined
            //or if browser does not handle date inputs
            $('.form-group input[type=date]').each(function (idx, elt) {
                if (elt.type != 'date' || elt.hasAttribute('data-datepicker')) {
                    elt.type = 'text';
                    $(elt).datetimepicker($(elt).data('datepicker'));
                }
            });

            @if ($isModelTranslatable)
                $('.side-body').multilingual({"editing": true});
            @endif

            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });

            $('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
            $('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
            $('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
            $('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));

            $('#confirm_delete').on('click', function(){
                $.post('{{ route('voyager.media.remove') }}', params, function (response) {
                    if ( response
                        && response.data
                        && response.data.status
                        && response.data.status == 200 ) {

                        toastr.success(response.data.message);
                        $file.parent().fadeOut(300, function() { $(this).remove(); })
                    } else {
                        toastr.error("Error removing file.");
                    }
                });

                $('#confirm_delete_modal').modal('hide');
            });
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop
