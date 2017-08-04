@extends('layouts.app')

@section('content')
<h1>{{$title}}</h1>
<p>Welcome to our Services and development</p>
 @if (count($Services)>0)
    <ul class="list-group">
        @foreach($Services as $service)
            <li class="list-group-item">{{$service}}</li>
        @endforeach
    </ul>
    
 @endif
@endsection