@extends('frontend::layouts._frontend')

@section('title')
Setting > My store
@stop

@section('content')
<div class="_mw970 _ma">
    <div class="_fwfl store-container">
        <div class="_fwfl store-header">
            <div class="_fwfl store-cover">
                <div class="_fwfl _fh store-cover-img" style="background-image:url('{{ get_cover('big') }}')"></div>
            </div>
            <div class="_fwfl store-nav-bar">
                <ul class="_fwfl _fh _ls store-nav-list">
                    <li><a href="#"><b>Product <span class="_fs12">(12)</span></b></a></li>
                    <li><a href="#"><b>Contact</b></a></li>
                    <li><a href="#"><b>Rating <span class="_fs12">(17)</span></b></a></li>
                    <li><a href="#"><b>Follow <span class="_fs12">(22)</span></b></a></li>
                    <li><a href="#"><b><i class="_fs14 fa fa-plus"></i></b></a></li>
                    <li><a href="#"><b><i class="_fs14 fa fa-search"></i></b></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
@stop