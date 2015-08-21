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
                    <li><a href="#"><b>{{ _t('store_product') }} <span class="_fs12">({{ $productCount }})</span></b></a></li>
                    <li><a href="#"><b>{{ _t('store_contact') }}</b></a></li>
                    <li><a href="#"><b>{{ _t('store_rating') }} <span class="_fs12">(17)</span></b></a></li>
                    <li><a href="#"><b>{{ _t('store_follow') }} <span class="_fs12">(22)</span></b></a></li>
                    <li data-toggle="modal" data-target="#add-product-modal">
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

        <div class="_fwfl store-body">
            <ol class="_fwfl _ls product-tree">
                @set $i = 1
                @foreach( $products as $product )
                @set $image = $product->toImage()
                <li class="{{ (($i++)%3 === 0) ? 'the-3th-product' : '' }}">
                    <div class="product">
                        <div class="product-head">
                            <ul class="product-handle">
                                <li>
                                    <span class="product-pin">
                                        <i class="fa fa-thumb-tack"></i>
                                        <b>300</b>
                                    </span>
                                </li>
                                <li>
                                    <span class="product-share">
                                        <i class="fa fa-share-alt"></i>
                                        <b>42</b>
                                    </span>
                                </li>
                                <li>
                                    <span class="product-comment">
                                        <i class="fa fa-comments-o"></i>
                                        <b>100</b>
                                    </span>
                                </li>
                            </ul>

                            <div class="product-control">
                                <div class="btn-group">
                                    <i class="fa fa-gear product-config-btn _r2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                    <ul class="dropdown-menu product-control-drop">
                                        <li>
                                            <a href="{{ route('front_find_product_by_id', $product->id) }}" data-edit-product-form class="product-edit" data-toggle="tooltip" data-placement="left" data-original-title="Edit">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </li>
                                        <li><a href="#" class="product-hide" data-toggle="tooltip" data-placement="left" data-original-title="Hide"><i class="fa fa-genderless"></i></a></li>
                                        <li><a href="#" class="product-remove" data-toggle="tooltip" data-placement="left" data-original-title="Remove"><i class="fa fa-close"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="product-body">
                            <div class="product-image">
                                <img src="{{ ($product->image_1 !== null) ? product_image($product->image_1->big) : '' }}" alt="{{ $product->name }}"/>
                            </div>
                            <div class="product-info">
                                <span class="product-name-box">
                                    <a href="#" class="product-name" title="{{ $product->name }}">{{ str_limit($product->name, 70) }}</a>
                                </span>
                                <div class="_fwfl _mt5">
                                    <span class="product-price">{{ product_price($product->price) }} <sup>đ</sup></span>
                                    @if( $product->old_price !== null )
                                    <span class="product-old-price">{{ product_price($product->old_price) }} <sup>đ</sup></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ol>
        </div>
    </div>
</div>

@include('frontend::inc.save-product-popup')

@stop

@section('js')
<script>

    $('#add-product-tooltip').tooltip();
    $('#search-product-tooltip').tooltip();
    $('.product-edit').tooltip();
    $('.product-hide').tooltip();
    $('.product-remove').tooltip();

</script>
@stop
