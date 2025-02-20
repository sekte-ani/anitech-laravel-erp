@extends('partial.layout-page', ['title' => 'General-Organization-Structure'])

@section('content')
<div class="card">
    <h5 class="card-header bg-primary text-white">Organization Structure</h5>
    <div class="table-responsive text-nowrap">
      <table class="table table-hover">
        <thead class="table-primary">
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Divisi</th>
            <th>Role</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @foreach($role_user->unique('employee_id') as $ru)
            @if ($ru->role->name != 'Admin' && $ru->employee->divisions->contains('name', 'Manajemen Eksekutif'))
                <tr>
                    <td>{{ $loop->iteration - 1 }}</td>
                    <td>{{ $ru->employee->name }}</td>
                    <td>
                        @foreach ($ru->employee->divisions as $division)
                            <span class="badge bg-primary">{{ $division->name }}</span>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($ru->employee->roles as $role)
                            <span class="badge bg-primary">{{ $role->name }}</span>
                        @endforeach
                    </td>
                </tr>
            @endif
            @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection