@extends('layouts.app')
@section('main')
<p>Welcome {{Auth::user()->name}}</p>
@endsection