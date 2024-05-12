@extends('components/layout')

@section('seccion')
    <div class="container mt-4">
        <form method="GET" action="{{route('getProducts')}}">
            @csrf
            <div class="row">
            <div class="col">
                <input type="text" class="form-control" placeholder="Buscar" name="search">
            </div>
            <div class="col">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
            @if($filter == 1)
                <div class="col-3">
                    <button type="submit" class="btn btn-warning">Limpiar</button>
                </div>
            @else
                <div class="col-3">
                </div>
            @endif
            </div>
        </form>
        </div>

    <div class="container mt-3">
        <table class="table">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Codigo</th>
                <th>Precio</th>
                <th>Descripcion</th>
            </tr>
            </thead>
            <tbody>
                @foreach($products as $item)
                    <tr>
                        <td>{{$item->name}}</td>
                        <td>{{$item->code}}</td>
                        <td>{{$item->price}}</td>
                        <td>{{$item->description}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection