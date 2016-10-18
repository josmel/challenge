@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Acortador de URL sencillo</h1>
</div>

<div class="row">

    <div class="col-md-6 col-md-offset-3">

        @if (count($errors) > 0)
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
            {{ $error }}
            @endforeach
        </div>
        @endif
        @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <form class="form-horizontal" method="POST" action="/save">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="input-group form-group-lg">
                <input type="text" name="url" id="url" class="form-control"
                       placeholder="http://tu-website.com"
                       value="@if(session()->has('link')) {{ config('app.domain') . session()->get('link') }} @endif">
                <span class="input-group-btn">
                    @if (session()->has('link'))
                    <button class="btn btn-warning btn-lg clipboard" data-clipboard-target="#url" type="button">
                        COPIAR URL
                    </button>
                    @else
                    <button  type="submit" class="btn btn-success btn-lg">ACORTAR!</button>
                    @endif
                </span>
            </div><!-- /input-group -->
        </form>
        <br><br>
        <table>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>URL</th>
                        <th>URL ACORTADA</th>
                        <th>FECHA</th>
                        <th>TOTAL DE REFERENCIAS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($links as $link)
                    <tr>
                        <td>{{$link->url}}</td>
                        <td><a href="{{ config('app.domain'). $link->hash}}" target="_black">{{ config('app.domain'). $link->hash}}</a></td>
                        <td>{{$link->created_at}}</td>
                        <td>{{$link->total}}</td>
                    </tr>

                    @endforeach
                </tbody>
            </table>

    </div>
</div>
@stop

@section('scripts')
<script>
    new Clipboard('.clipboard');
</script>
@endsection
