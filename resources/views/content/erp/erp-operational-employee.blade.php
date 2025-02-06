@extends('partial.layout-page', ['title' => 'ERP-Operational-Client'])

@section('content')
<div class="d-flex justify-content-end my-3">
    <button type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#employeeModal">Add Employee</button>
</div>

 <!-- Bootstrap Table with Header - Light -->
 <div class="card">
    <h5 class="card-header">Employee List</h5>
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            @if (Auth::user()->roles[0]->name == 'Admin')
            <th>Password</th>
            @endif
            <th>Role</th>
            <th>Images</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @foreach ($users as $u)
          @if ($u->roles->contains('name', 'Admin') == false)
          <tr>
            <td>{{ $loop->iteration - 1 }}</td>
            <td>{{ $u->name }}</td>
            <td>{{ $u->email }}</td>
            <td>{{ $u->confirm_password }}</td>
            <td>
                @foreach ($u->roles as $role)
                    <span class="badge bg-primary">{{ $role->name }}</span>
                @endforeach
            </td>
            <td>
                @if ($u->images)
                    <img src="{{ env('STORAGE_URL') . $u->images }}" style="object-fit: cover; width: 100%; height: 100px; max-width: 200px;" class="card-img" alt="...">
                @else
                    <img src="https://picsum.photos/seed/nophoto" style="object-fit: cover; width: 100%; height: 100px; max-width: 200px;" class="card-img" alt="...">
                @endif
            </td>
            <td>
                @if ($u->phone != null)
                    {{ $u->phone }}
                @else
                    -
                @endif
            </td>
            <td>
                @if ($u->address != null)
                    {{ $u->address }}
                @else
                    -
                @endif
            </td>
            <td>
                @if ($u->status == 'Active')
                    <span class="badge bg-label-success me-1">Active</span>
                @else
                    <span class="badge bg-label-danger me-1">Non-Active</span>
                @endif
            </td>
            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="javascript:void(0);"
                        ><i class="bx bx-edit-alt me-1"></i> Edit</a
                    >
                    <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="setDeleteUrl('{{ route('emp.delete', $u->id) }}')"
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
      {{ $users->withQueryString()->links() }}
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