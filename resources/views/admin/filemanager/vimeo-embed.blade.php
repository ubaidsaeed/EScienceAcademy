@extends('layouts.admin.app')
@push('style')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body, html {
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
        .vimeo-container {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .vimeo-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
    @endpush
    @section('content')
    <div class="vimeo-container">
        {!! $embedHtml !!}
    </div>
    @endsection

