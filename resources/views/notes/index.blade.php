@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <a href="{{route('notes.create')}}">Create New</a>
            <table id="notes_table" class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Created By</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    $('#notes_table').dataTable({
        "serverSide": true,
        "responsive": true,
        "ajax": "{{route('notes.data')}}"
    });
});
</script>
@endsection
