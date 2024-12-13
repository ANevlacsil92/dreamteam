@extends('layouts.app')

@section('content')
    <div id="app" class="container-fluid">
        <div class="row">
            <component is="{{ $component }}" :user="{{ json_encode(auth()->user()) }}" :params="{{ json_encode($params ?? null) }}"></component>
        </div>
          <div class="row nav-row footer">
        <!-- Button trigger modal -->
         <a href="/impressum" style="width:100%; padding:5px;">
           <button
           class="imprint w-100"
           type="button"
           >
           Impressum
          </button>
        </a>
      </div>
    </div>
@endsection