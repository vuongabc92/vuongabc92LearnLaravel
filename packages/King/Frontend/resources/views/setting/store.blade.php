@extends('frontend::layouts._frontend')

@section('title')
Setting > Store
@stop

@section('content')
<div class="_mw970 _ma">
    <div class="_fwfl setting-container">
        <div class="_fl _bgw _r3 setting-left-col">
            <div class="_fwfl setting-header">
                <h1 class="_fwfl _p0 _m0 _fs20 _tg5 _fs20">
                    @if(user()->has_store)
                        {{ _t('change_store_info') }}
                    @else
                        {{ _t('store') }}
                    @endif
                </h1>
            </div>
            <div class="_fwfl">
                @if(user()->has_store)
                <div class="_fwfl setting-group">
                    <div class="_fl setting-field-left">
                        <b class="_fwfl _fs13 _tg5">{{ _t('cover_img') }}</b>
                    </div>
                    <div class="_fr setting-field-right">
                        <b class="_fr _tr7 _fs13 _dn upload-cover-messages"></b>
                    </div>
                    <div class="_fwfl _mt10 setting-cover-box">
                        <img class="_fwfl _r2 setting-cover-img cover-medium" src="{{ get_cover('medium') }}" />
                        <div class="_fwfl _mt10">
                            <button class="_fr btn _btn _btn-white choose-cover-btn" data-event-trigger="#cover-file" data-event="click|click">
                                <img class="loading-in-btn" src="{{ asset('packages/king/frontend/images/loading-gray-white-24x24.gif') }}" />
                                <b class="btn-text">{{ _t('choose_cover') }}</b>
                                <i class="fa fa-check _dn"></i>
                            </button>
                        </div>
                    </div>
                    <div class="_fwfl _dn">
                        {!! Form::open(['route' => 'front_setting_change_cover', 'files' => true, 'method' => 'POST', 'id' => 'upload-cover-form', 'data-upload-cover']) !!}
                        {!! Form::file('__file', ['class' => 'field-file-hidden', 'id' => 'cover-file', 'accept' => 'image/*', 'data-event-trigger' => '#upload-cover-form', 'data-event' => 'change|submit']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
                @endif
                {!! Form::model($store, ['route' => 'front_setting_store_change','method' => 'POST', 'id' => 'save-store-form', 'data-ajax-form' => 'name|category_id|street|city_id|district_id|ward_id|phone_number']) !!}
                    <div class="_fwfl setting-group">
                        <div class="_fl setting-field-left">
                            <b class="_fwfl _fs13 _tg5">{{ _t('basic_info') }}</b>
                        </div>
                        <div class="_fr setting-field-right">
                            <div class="_fl setting-form-group setting-form-group-store">
                                <label class="_fwfl setting-form-label" for="name" data-title="{{ _t('store_name') }}">{{ _t('store_name') }}</label>
                                {!! Form::text('name', null, ['class' => '_fwfl setting-form-field', 'id' => 'name', 'maxlength' => '250']) !!}
                            </div>
                            <div class="_fl setting-form-group setting-form-group-store">
                                <label class="_fwfl setting-form-label" for="category" data-title="{{ _t('category') }}">{{ _t('category') }}</label>
                                {!!
                                    Form::select('category_id', $categories , null, ['id' => 'category', 'class' => 'setting-form-field selectbox-field'])
                                !!}
                                <div class="_fwfl _mt17">
                                    <p class="_fs12 _tg7 _m0">(*) {{ _t('store_name_note1') }}</p>
                                    <p class="_fs12 _tg7 _m0 _mt3">(*) {{ _t('store_name_note2') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="_fwfl setting-group">
                        <div class="_fl setting-field-left">
                            <b class="_fwfl _fs13 _tg5">{{ _t('contact_info') }}</b>
                        </div>
                        <div class="_fr setting-field-right">
                            <div class="_fl setting-form-group setting-form-group-store">
                                <label class="_fwfl setting-form-label" for="street" data-title="{{ _t('street_name') }}">{{ _t('street_name') }}</label>
                                {!! Form::text('street', null, ['class' => 'setting-form-field', 'id' => 'street', 'maxlength' => '250']) !!}
                            </div>
                            <div class="_fl setting-form-group setting-form-group-store">
                                <label class="_fwfl setting-form-label" for="city_id" data-title="{{ _t('province') }}">{{ _t('province') }}</label>
                                {!!
                                    Form::select('city_id', $cities , null, [
                                        'id'            => 'city_id',
                                        'class'         => 'setting-form-field select-box-field',
                                        'data-get-area' => route('front_setting_get_district', 0),
                                        'data-target'   => '#district_id',
                                        'data-text'     => _t('select_district')])
                                !!}
                            </div>
                            <div class="_fl setting-form-group setting-form-group-store">
                                <div class="_w50 _fl _pr3">
                                    <label class="_fwfl setting-form-label" for="district_id" data-title="{{ _t('district') }}">{{ _t('district') }}</label>
                                    {!!
                                        Form::select('district_id', $districts , null, [
                                            'id'            => 'district_id',
                                            'class'         => 'setting-form-field select-box-field',
                                            'data-get-area' => route('front_setting_get_ward', 0),
                                            'data-target'   => '#ward_id',
                                            'data-text'     => _t('select_ward')])
                                    !!}
                                </div>
                                <div class="_w50 _fl _pl3">
                                    <label class="_fwfl setting-form-label" for="ward_id" data-title="{{ _t('ward') }}">{{ _t('ward') }}</label>
                                    {!!
                                        Form::select('ward_id', $wards , null, ['id' => 'ward_id', 'class' => 'setting-form-field select-box-field'])
                                    !!}
                                </div>
                            </div>
                            <div class="_fl setting-form-group setting-form-group-store">
                                <label class="_fwfl setting-form-label" for="phone" data-title="{{ _t('fone_num') }}">{{ _t('fone_num') }}</label>
                                {!! Form::text('phone_number', null, ['class' => 'setting-form-field', 'id' => 'phone', 'maxlength' => '250']) !!}
                                <div class="_fwfl _mt10">
                                    <p class="_fs12 _tg7 _m0">(*) {{ _t('contact_info_note1') }}</p>
                                </div>
                            </div>
                            <div class="_fwfl setting-form-group">
                                <button type="submit" class="_fl _mr10 btn _btn _btn-blue1 _save-btn">
                                    <img class="loading-in-btn" src="{{ asset('packages/king/frontend/images/loading-white-blue-24x24.gif') }}" />
                                    <b class="btn-text">{{ _t('save') }}</b>
                                    <i class="fa fa-check _dn"></i>
                                </button>
                                <button type="reset" class="_fl btn _btn _btn-gray" data-reset-form="#save-store-form">{{ _t('cancel') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
                {!! Form::close() !!}
            </div>
            <div class="_fwfl setting-group last-setting-group">
                <button class="_fr btn _btn _btn-red">{{ _t('del_store') }}</button>
            </div>
        </div>
    </div>
</div>
@stop