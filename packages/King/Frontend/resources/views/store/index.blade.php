@extends('frontend::layouts._frontend')

@section('title')
Setting > My store
@stop

@section('content')
<div class="_mw970 _ma">
    <div class="_fwfl store-container">
        <div class="_fwfl store-header">
            <div class="_fwfl store-cover">
                <div class="_fwfl _fh store-cover-img cover-big" style="background-image:url('{{ get_cover('big') }}')">
                    <button class="_fr _m10 btn _btn-sm _btn-black-opacity choose-cover-btn" data-event-trigger="#cover-file" data-event="click|click">
                        <img class="loading-in-btn-sm" src="{{ asset('packages/king/frontend/images/loading-black-opacity-24x24.gif') }}" />
                        <b>{{ _t('store_change_cover') }}</b>
                        <i class="fa fa-check _dn"></i>
                    </button>
                    <div class="_fwfl _dn">
                        {!! Form::open(['route' => 'front_setting_change_cover', 'files' => true, 'method' => 'POST', 'id' => 'upload-cover-form', 'data-upload-cover']) !!}
                        {!! Form::file('__file', ['class' => 'field-file-hidden', 'id' => 'cover-file', 'accept' => 'image/*', 'data-event-trigger' => '#upload-cover-form', 'data-event' => 'change|submit']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <div class="_fwfl store-nav-bar">
                <ul class="_fwfl _fh _ls store-nav-list">
                    <li><a href="#"><b>{{ _t('store_product') }} <span class="_fs12">(12)</span></b></a></li>
                    <li><a href="#"><b>{{ _t('store_contact') }}</b></a></li>
                    <li><a href="#"><b>{{ _t('store_rating') }} <span class="_fs12">(17)</span></b></a></li>
                    <li><a href="#"><b>{{ _t('store_follow') }} <span class="_fs12">(22)</span></b></a></li>
                    <li data-toggle="modal" data-target="#myModal">
                        <a href="#" id="add-product-tooltip" data-toggle="tooltip" data-placement="bottom" data-original-title="{{ _t('add_product') }}">
                            <b><i class="_fs14 fa fa-plus"></i></b>
                        </a>
                    </li>
                    <li>
                        <a href="#" id="search-product-tooltip" data-toggle="tooltip" data-placement="bottom" data-original-title="{{ _t('search_in_store') }}">
                            <b><i class="_fs14 fa fa-search"></i></b>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@include('frontend::inc.save-product-popup')

@stop

@section('js')
<script>

    $('#add-product-tooltip').tooltip();
    $('#search-product-tooltip').tooltip();

</script>
@stop