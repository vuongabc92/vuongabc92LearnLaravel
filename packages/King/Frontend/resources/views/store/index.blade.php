@extends('frontend::layouts._frontend')

@section('title')
My store
@stop

@section('content')
<div class="_mw970 _ma">
    <div class="_fwfl store-container">
        <div class="_fwfl store-header">
            <div class="_fwfl store-cover-block">
                <div class="_fwfl cover-backgroud" style="background-image:url('{{ get_cover('big') }}');"></div>
            </div>
            <div class="_fwfl store-nav-bar">
                <ul class="_fwfl _ls store-nav-list">
                    <li><a href="#">Products <span class="_fs12">(12)</span></a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">Rating <span class="_fs12">(2)</span></a></li>
                    <li><a href="#">Follow <span class="_fs12">(22)</span></a></li>
                    <li><a href="#"><i class="_fs14 fa fa-plus"></i></a></li>
                    <li><a href="#"><i class="_fs14 fa fa-search"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
@stop