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
            @foreach($role_user->unique('user_id') as $ru)
            @if ($ru->role->name != 'Admin')
                <tr>
                    <td>{{ $loop->iteration - 1 }}</td>
                    <td>{{ $ru->user->name }}</td>
                    <td>
                        @foreach ($ru->user->roles as $role)
                            <span class="badge bg-primary">{{ $role->name }}</span>
                        @endforeach
                    </td>
                    <td>
                        <div>
                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editRoleModal{{ $ru->user->id }}"
                                ><i class="bx bx-edit-alt me-1"></i> Edit</button
                            >
                        </div>
                    </td>
                </tr>
            @endif
            @endforeach
        </tbody>
      </table>
      {{ $role_user->withQueryString()->links() }}
    </div>
  </div>

  @foreach ($role_user as $ru)
    <div class="modal fade" id="editRoleModal{{ $ru->user->id }}" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Role for {{ $ru->user->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('role.update', $ru->user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <div class="row">
                                @foreach ($roles as $index => $r)
                                    <div class="col-md-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="role_id[]" value="{{ $r->id }}" 
                                                @if(in_array($r->id, $ru->user->roles->pluck('id')->toArray())) checked @endif>
                                            <label class="form-check-label">
                                                {{ $r->name }}
                                            </label>
                                        </div>
                                    </div>
                        
                                    @if (($index + 1) % 5 == 0)
                                        </div><div class="row">
                                    @endif
                                @endforeach
                            </div>
                        </div>                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
  @endforeach
@endsection