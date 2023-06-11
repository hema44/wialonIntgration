@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @foreach( \Illuminate\Support\Facades\Cache::get("data") as $key =>$value)
                        @if(is_array($value))
                            @foreach($value as $key1 =>$value1)
                                @if(is_array($value))
                                @else
                                        {{$key1}} : {{$value1}} <br>
                                @endif
                            @endforeach
                        @else
                                {{$key}} : {{$value}} <br>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
