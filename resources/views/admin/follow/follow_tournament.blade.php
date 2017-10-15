@extends('admin.base')

@section('content')
<!-- Main Content -->
<section id="main-content" class="no-transition">
    <section class="wrapper">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group" id="teams_follow">
                    <label for="teams_follow">Select a tournament:</label>
                    <select class="form-control" id="select_teams">
                       @foreach($tournaments as $tournament)
                        <option data-id="{{ $tournament->id }}" value="{{ $tournament->id }}">{{ $tournament->name }}</option>
                       @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8" id="result">

            </div>
        </div>
    </section>
</section>
<!-- End of Main Content -->
@endsection