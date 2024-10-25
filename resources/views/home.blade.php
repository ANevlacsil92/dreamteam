@extends('layouts.app')

@section('content')
    <div id="app" class="container-fluid">
        <div class="row">
            <component is="{{ $component }}" :user="{{ json_encode(auth()->user()) }}" :params="{{ json_encode($params ?? null) }}"></component>
        </div>
          <div class="row nav-row footer">
        <!-- Button trigger modal -->
          <button
            class="imprint"
            type="button"
            class="btn btn-link"
            data-bs-toggle="modal"
            data-bs-target="#siteNoteModal"
          >
            Impressum
          </button>
      </div>
    </div>
@endsection