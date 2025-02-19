@extends('partial.layout-page', ['title' => 'ERP-Finance-Expenses'])

@section('content')
<div class="d-flex justify-content-end my-3">
    <button type="button" class="btn btn-primary mx-3" data-bs-toggle="modal" data-bs-target="#categoryModal">Add Category</button>
    <button type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#expenseModal">+</button>
</div>

 <!-- Bootstrap Table with Header - Light -->
 <div class="card">
    <h5 class="card-header">Expenses Tracker</h5>
    <div class="table-responsive text-nowrap">
      <table class="table table-hover">
        <thead class="table-light text-center">
            <tr>
                <th rowspan="2" class="align-middle">#</th>
                <th rowspan="2" class="align-middle">Tanggal</th>
                <th rowspan="2" class="align-middle">Item</th>
                <th rowspan="2" class="align-middle">Kategori</th>
                <th rowspan="2" class="align-middle">Tipe</th>
                <th rowspan="2" class="align-middle">Sumber</th>
                <th colspan="2">Jumlah</th>
                <th rowspan="2" class="align-middle">Saldo</th>
                <th rowspan="2" class="align-middle">Actions</th>
            </tr>
            <tr>
                <th>Income</th>
                <th>Expense</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          {{-- @foreach ($expenses as $e)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $e->date }}</td>
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
          @endforeach --}}
        </tbody>
      </table>
      {{-- {{ $employees->withQueryString()->links() }} --}}
    </div>
  </div>
  <!-- Bootstrap Table with Header - Light -->

  <!-- Modal -->
{{-- <div class="modal fade" id="expenseModal" tabindex="-1" aria-labelledby="expenseModalLabel" aria-hidden="true">
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
  </div> --}}
  
  <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add Category </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="{{ route('category.store') }}" enctype="multipart/form-data">
            @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Kategori</label>
                    <input type="text" autofocus value="{{ old('name') }}" name="name" id="name" placeholder="Masukkan kategori" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')
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
@endsection