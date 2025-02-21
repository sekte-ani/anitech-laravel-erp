
@extends('partial.layout-page', ['title' => 'ERP-Operational-Client'])

@section('content')
<div class="card">
    <h5 class="card-header">User List</h5>
    <div class="table-responsive text-nowrap">
      <table class="table table-hover">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            @if (Auth::user()->employee->roles[0]->name == 'Admin')
            <th>Password</th>
            @endif
            <th>Role</th>
            <th>Status</th>
            @if (Auth::user()->employee->roles[0]->name == 'Admin')
            <th>Actions</th>
            @endif
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @foreach ($users as $u)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $u->employee->name }}</td>
            <td>{{ $u->email }}</td>
            @if (Auth::user()->employee->roles[0]->name == 'Admin')
            <td>{{ $u->confirm_password }}</td>
            @endif
            <td>
                @foreach ($u->employee->roles as $role)
                    <span class="badge bg-primary">{{ $role->name }}</span>
                @endforeach
            </td>
            <td>
                @if ($u->status == 'Active')
                    <span class="badge bg-label-success me-1">Active</span>
                @else
                    <span class="badge bg-label-danger me-1">Non-Active</span>
                @endif
            </td>
            @if (Auth::user()->employee->roles[0]->name == 'Admin')
            <td>
                <div>
                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editRoleModal{{ $u->employee->id }}"
                        ><i class="bx bx-edit-alt me-1"></i> Edit</button
                    >
                </div>
            </td>
            @endif
          </tr>
          @endforeach
        </tbody>
      </table>
      {{ $users->withQueryString()->links() }}
    </div>
  </div>

<div class="d-flex justify-content-end my-3">
    <button type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#employeeModal">Add Employee</button>
</div>

 <!-- Bootstrap Table with Header - Light -->
 <div class="card">
    <h5 class="card-header">Employee List</h5>
    <div class="table-responsive text-nowrap">
      <table class="table table-hover">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Division</th>
            <th>Images</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Bank Name</th>
            <th>Bank Account</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @foreach ($employees as $e)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $e->name }}</td>
            <td>
                @foreach ($e->divisions as $division)
                    <span class="badge bg-primary">{{ $division->name }}</span>
                @endforeach
            </td>
            <td>
                @if ($e->images)
                    <img src="{{ asset('storage/' . $e->images) }}" style="object-fit: cover; width: 100%; height: 100px; max-width: 200px;" class="card-img" alt="...">
                @else
                    <img src="https://picsum.photos/seed/nophoto" style="object-fit: cover; width: 100%; height: 100px; max-width: 200px;" class="card-img" alt="...">
                @endif
            </td>
            <td>
                @if ($e->phone != null)
                    {{ $e->phone }}
                @else
                    -
                @endif
            </td>
            <td>
                @if ($e->address != null)
                    {{ $e->address }}
                @else
                    -
                @endif
            </td>
            <td>
                @if ($e->bank_name != null)
                    {{ $e->bank_name }}
                @else
                    -
                @endif
            </td>
            <td>
                @if ($e->bank_account != null)
                    {{ $e->bank_account }}
                @else
                    -
                @endif
            </td>
            <td>
            @if (Auth::check() && (Auth::user()->employee->roles->contains('name', 'Admin') || Auth::user()->employee->id == $e->id))
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editEmployeeModal{{ $e->id }}"
                        ><i class="bx bx-edit-alt me-1"></i> Edit</a
                    >
                    @if (Auth::user()->employee->roles[0]->name == 'Admin')
                    <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="setDeleteUrl('{{ route('emp.delete', $e->id) }}')"
                        ><i class="bx bx-trash me-1"></i> Delete</a
                    >
                    @endif
                </div>
            </div>
            @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      {{ $employees->withQueryString()->links() }}
    </div>
  </div>
  <!-- Bootstrap Table with Header - Light -->

  <!-- Modal -->
<div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add Employee </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="{{ route('emp.store') }}" enctype="multipart/form-data">
            @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" autofocus value="{{ old('name') }}" name="name" id="name" placeholder="Masukkan nama karyawan" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" name="slug" id="slug" placeholder="Slug akan digenerate.." value="{{ old('slug') }}" readonly class="form-control @error('slug') is-invalid @enderror" id="slug" required>
                    @error('slug')
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Masukkan email karyawan" required>
                    @error('email')
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="division_id" class="form-label">Divisi</label>
                    <select class="form-select" name="division_id" required>
                    <option value="" disabled selected>--- Pilih Divisi ---</option>
                    @foreach ($divisions as $d)
                    @if (old('division_id') == $d->id)
                        <option value="{{ $d->id }}" selected>{{ $d->name }}</option>
                    @else
                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                    @endif
                    @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="role_id" class="form-label">Role</label>
                    <select class="form-select" name="role_id" required>
                    <option value="" disabled selected>--- Pilih Role ---</option>
                    @foreach ($roles as $r)
                    @if (old('role_id') == $r->id)
                        <option value="{{ $r->id }}" selected>{{ $r->name }}</option>
                    @else
                    <option value="{{ $r->id }}">{{ $r->name }}</option>
                    @endif
                    @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="images" class="form-label">Foto Karyawan</label>
                    <img class="img-preview img-fluid mb-3 col-sm-5">
                    <input class="form-control @error('images') is-invalid @enderror" onchange="previewImage()" type="file" name="images" id="images" accept="image/png, image/jpg, image/jpeg">
                    @error('img')
                    {{ $message }}
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">No. HP</label>
                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" id="phone" placeholder="Masukkan nomor telepon">
                    @error('phone')
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add</button>
            </div>
        </form>
      </div>
    </div>
  </div>

  @foreach($employees as $e)
  <div class="modal fade" id="editEmployeeModal{{ $e->id }}" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Employee </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="{{ route('emp.update', $e->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" autofocus value="{{ old('name', $e->name) }}" name="name" id="name" placeholder="Masukkan nama karyawan" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" name="slug" id="slug" placeholder="Slug akan digenerate.." value="{{ old('slug', $e->slug) }}" readonly class="form-control @error('slug') is-invalid @enderror" id="slug" required>
                    @error('slug')
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <input type="hidden" name="role_id" value="{{ $e->role_id }}">
                <input type="hidden" name="division_id" value="{{ $e->division_id }}">
                <div class="mb-3">
                    <label for="images" class="form-label">Foto Karyawan</label>
                    @if (!empty($e->images))
                    <input type="hidden" name="oldImage" value="{{ $e->images }}">
                    <img src="{{ asset('storage/') .$e->images }}" class="img-preview img-fluid mb-3 col-sm-5 d-block">
                    @else
                    <img class="img-preview img-fluid mb-3 col-sm-5">
                    @endif
                    <input class="form-control @error('images') is-invalid @enderror" onchange="previewImage()" type="file" id="images" name="images" accept="image/png, image/jpeg, image/jpg" >
                    @error('images')
                        {{ $message }}
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">No. HP</label>
                    <input type="tel" name="phone" value="{{ old('phone', $e->phone) }}" class="form-control @error('phone') is-invalid @enderror" id="phone" placeholder="Masukkan nomor telepon">
                    @error('phone')
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Alamat</label>
                    <input type="text" name="address" value="{{ old('address', $e->address) }}" class="form-control @error('address') is-invalid @enderror" id="address" placeholder="Masukkan alamat">
                    @error('address')
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="bank_name" class="form-label">Nama Bank</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name', $e->bank_name) }}" class="form-control @error('bank_name') is-invalid @enderror" id="bank_name" placeholder="Masukkan nama bank">
                    @error('bank_name')
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="bank_account" class="form-label">No. Rekening</label>
                    <input type="text" name="bank_account" value="{{ old('bank_account', $e->bank_account) }}" class="form-control @error('bank_account') is-invalid @enderror" id="bank_account" placeholder="Masukkan nomor rekening">
                    @error('bank_account')
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Edit</button>
            </div>
        </form>
      </div>
    </div>
  </div>
  @endforeach

  @foreach ($users as $u)
    <div class="modal fade" id="editRoleModal{{ $u->employee->id }}" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoleModalLabel">Edit for {{ $u->employee->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('role.update', $u->employee->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" autofocus value="{{ old('password', $u->password) }}" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">
                                {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="confirm_password" autofocus value="{{ old('confirm_password', $u->confirm_password) }}" name="confirm_password" id="confirm_password" class="form-control @error('confirm_password') is-invalid @enderror">
                            @error('confirm_password')
                                <div class="invalid-feedback">
                                {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Divisi</label>
                            <div class="row">
                                @foreach ($divisions as $index => $d)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="division_id[]" value="{{ $d->id }}" 
                                                @if(in_array($d->id, $u->employee->divisions->pluck('id')->toArray())) checked @endif>
                                            <label class="form-check-label">
                                                {{ $d->name }}
                                            </label>
                                        </div>
                                    </div>
                        
                                    @if (($index + 1) % 5 == 0)
                                        </div><div class="row">
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <div class="row">
                                @foreach ($roles as $index => $r)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="role_id[]" value="{{ $r->id }}" 
                                                @if(in_array($r->id, $u->employee->roles->pluck('id')->toArray())) checked @endif>
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
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                              @foreach ($status as $s)
                              @php
                                  $cek = ($s == $u->status) ? 'checked' : '';
                              @endphp
                                  <div class="custom-control custom-radio custom-control-inline">
                                      <input name="status" id="status{{ $s }}" type="radio" class="custom-control-input" value="{{ $s }}" {{ $cek }}> 
                                      <label for="status{{ $s }}" class="custom-control-label">{{ $s }}</label>
                                  </div>
                              @endforeach
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

  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this user?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="delete-form" method="POST" action="" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

  <script>
    const name = document.querySelector('#name');
    const slug = document.querySelector('#slug');

    name.addEventListener('change', function(){
        fetch('/checkSlugName?name=' + name.value)
        .then(response => response.json())
        .then(data => slug.value = data.slug)
    });

    function previewImage(){
        const image = document.querySelector('#img');
        const imgPreview = document.querySelector('.img-preview');

        imgPreview.style.display = 'block';

        const oFReader = new FileReader();
        oFReader.readAsDataURL(image.files[0]);

        oFReader.onload = function(oFREvent){
          imgPreview.src = oFREvent.target.result;
        }
      }

    function setDeleteUrl(url) {
        document.getElementById('delete-form').action = url;
    }
</script>
@endsection
