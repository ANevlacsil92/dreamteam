@extends('layouts.app')

@section('content')

            <div id="app" class="container-fluid">

                <div class="row">
                    <component is="{{ $component }}" :user="{{ json_encode(auth()->user()) }}" :params="{{ json_encode($params ?? null) }}"></component>
                </div>

            </div>
@endsection