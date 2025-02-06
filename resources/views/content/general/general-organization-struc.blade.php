@extends('partial.layout-page', ['title' => 'General-Organization-Structure'])

@section('content')
<div class="card">
    <h5 class="card-header">Organization Structure</h5>
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Role</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @foreach($role_user as $ru)
            @if ($ru->role->name != 'Admin')
                <tr>
                    <td>{{ $loop->iteration - 1 }}</td>
                    <td>{{ $ru->user->name }}</td>
                    <td>{{ $ru->role->name }}</td>
                    <td>
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                        <a class="dropdown-item" href="javascript:void(0);"
                            ><i class="bx bx-edit-alt me-1"></i> Edit</a
                        >
                        <a class="dropdown-item" href="javascript:void(0);"
                            ><i class="bx bx-trash me-1"></i> Delete</a
                        >
                        </div>
                    </div>
                    </td>
                </tr>
            @endif
            @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection