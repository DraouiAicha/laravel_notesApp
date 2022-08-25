@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <a href="{{route('notes.create')}}">Create New</a>
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notes as $item)
                        <tr>
                            <td>{{$item->title}}</td>
                            <td>{{$item->created_at}}</td>
                            <td>
                                <a href="{{route('notes.show',$item->id)}}">Show</a>
                                <a href="{{route('notes.edit',$item->id)}}">Edit</a>
                                <form method="POST" action="{{route('notes.destroy',$item->id)}}">
                                    @csrf
                                    @method('DELETE')
                                    <button>Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
