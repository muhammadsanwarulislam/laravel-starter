@extends('layouts.backend.app')

@section('title','Users')

@push('css')
<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-users icon-gradient bg-mean-fruit">
                </i>
            </div>
            <div>{{ __('All Users') }}</div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block dropdown">
                @canany('backend.admin.create')
                    <a href="{{ route('backend.admin.create') }}" class="btn-shadow btn btn-info">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fas fa-plus-circle fa-w-20"></i>
                        </span>
                        {{ __('Create User') }}
                    </a>
                @endcanany
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="main-card mb-3 card">
            <div class="table-responsive">
                <table id="datatableUser" class="align-middle mb-0 table table-borderless table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Joined At</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $key=>$user)
                        <tr>
                            <td class="text-center text-muted">#{{ $key + 1 }}</td>
                            <td>
                                <div class="widget-content p-0">
                                    <div class="widget-content-wrapper">
                                        <div class="widget-content-left flex2">
                                            <div class="widget-heading">{{ $user->name }}</div>
                                            <div class="widget-subheading opacity-7">
                                                @if ($user->role)
                                                <span class="badge badge-info">{{ $user->role->name }}</span>
                                                @else
                                                <span class="badge badge-danger">No role found :(</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">{{ $user->email }}</td>
                            <td class="text-center">{{ $user->created_at->diffForHumans() }}</td>
                            <td class="text-center">
                                <a class="fa-eye-style" href="{{ route('backend.admin.show',$user->id) }}"><i
                                        class="fas fa-eye"></i>
                                </a> |
                                <a class="fa-edit-style" href="{{ route('backend.admin.edit',$user->id) }}"><i
                                        class="fas fa-edit"></i>
                                </a> |
                                <button type="button" class="delete-btn-style" onclick="deleteData({{ $user->id }})">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <form id="delete-form-{{ $user->id }}"
                                    action="{{ route('backend.admin.destroy',$user->id) }}" method="POST"
                                    style="display: none;">
                                    @csrf()
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Datatable
    $("#datatableUser").DataTable();
});
</script>
@endpush