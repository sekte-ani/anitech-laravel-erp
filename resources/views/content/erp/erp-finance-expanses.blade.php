@extends('partial.layout-page', ['title' => 'ERP-Finance-Expenses'])

@section('content')
<div class="d-flex justify-content-end my-3">
    <button type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#expenseModal">+</button>
</div>

 <!-- Bootstrap Table with Header - Light -->
 <div class="card">
    <h5 class="card-header">Expenses Tracker</h5>
    <div class="table-responsive text-nowrap">
        <form action="{{ route('expanses') }}" method="GET" class="d-flex mb-3">
            <input type="month" name="month" class="form-control me-2" value="{{ request('month') }}">
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
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
          @foreach ($expenses as $e)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $e->date }}</td>
            <td>{{ $e->item }}</td>
            <td>{{ $e->category->name }}</td>
            <td>{{ $e->type }}</td>
            <td>{{ $e->frequency }}</td>
            <td class="text-success fw-bold">
                @if ($e->type === 'Pemasukan' || $e->category->name === 'Sisa Saldo')
                    {{ number_format($e->amount, 0, ',', '.') }}
                @endif
            </td>
            <td class="text-danger fw-bold">
                @if ($e->type === 'Pengeluaran' && $e->category->name !== 'Sisa Saldo')
                    {{ number_format($e->amount, 0, ',', '.') }}
                @endif
            </td>
            <td>{{ number_format($e->balance, 0, ',', '.') }}</td>
            <td>
            @if (Auth::check() && Auth::user()->employee && Auth::user()->employee->roles->whereIn('name', ['Admin', 'Pimpinan Tim Operasional', 'Staff Finance'])->isNotEmpty())
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editExpenseModal{{ $e->id }}"
                        ><i class="bx bx-edit-alt me-1"></i> Edit</a
                    >
                    @if (Auth::user()->employee->roles->whereIn('name', ['Admin', 'Pimpinan Tim Operasional'])->isNotEmpty())
                    <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="setDeleteUrl('{{ route('expenses.delete', $e->id) }}')"
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
    </div>
  </div>
  <!-- Bootstrap Table with Header - Light -->

  <!-- Modal -->
<div class="modal fade" id="expenseModal" tabindex="-1" aria-labelledby="expenseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add Employee </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="{{ route('expenses.store') }}" enctype="multipart/form-data">
            @csrf
                <div class="mb-3">
                    <label for="date" class="form-label">Tanggal</label>
                    <input type="date" autofocus value="{{ old('date') }}" name="date" id="date" class="form-control @error('date') is-invalid @enderror" required>
                    @error('date')
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="item" class="form-label">Item</label>
                    <input type="text" name="item" id="item" placeholder="Masukkan Data" value="{{ old('item') }}" class="form-control @error('item') is-invalid @enderror" required>
                    @error('item')
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="category_id" class="form-label">Kategori</label>
                    <select class="form-select" name="category_id" required>
                    <option value="" disabled selected>--- Pilih Kategori ---</option>
                    @foreach ($category as $c)
                        <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                    </select>
                    <button type="button" class="btn btn-primary mx-3" data-bs-toggle="modal" data-bs-target="#categoryModal">Add Category</button>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Tipe</label>
                    <select class="form-select" name="type" id="type" required>
                        <option value="" disabled selected>--- Pilih Tipe ---</option>
                        @foreach ($type as $t)
                            <option value="{{ $t }}" {{ old('type') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="frequency" class="form-label">Sumber</label>
                    <select class="form-select" name="frequency" id="frequency" required>
                        <option value="" disabled selected>--- Pilih Sumber ---</option>
                        @foreach ($frequency as $f)
                            <option value="{{ $f }}" {{ old('frequency') == $f ? 'selected' : '' }}>{{ $f }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="amount" class="form-label">Jumlah</label>
                    <input class="form-control @error('amount') is-invalid @enderror" type="number" name="amount" id="amount" placeholder="Masukkan jumlah" value="{{ old('amount') }}">
                    @error('amount')
                    {{ $message }}
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

  @foreach($expenses as $e)
    <div class="modal fade" id="editExpenseModal{{ $e->id }}" tabindex="-1" aria-labelledby="editExpenseModalLabel{{ $e->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editExpenseModalLabel{{ $e->id }}">Edit Expense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('expenses.update', $e->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="date" class="form-label">Tanggal</label>
                            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" id="date" value="{{ old('date', $e->date) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="item" class="form-label">Item</label>
                            <input type="text" name="item" class="form-control @error('item') is-invalid @enderror" id="item" value="{{ old('item', $e->item) }}" required>
                            @error('item')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                @foreach($category as $c)
                                    <option value="{{ $c->id }}" {{ $e->category_id == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Tipe</label>
                            <select name="type" id="type" class="form-control">
                                @foreach ($type as $t)
                                    <option value="{{ $t }}" {{ $t == $e->type ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="frequency" class="form-label">Sumber</label>
                            <select name="frequency" id="frequency" class="form-control">
                                @foreach ($frequency as $f)
                                    <option value="{{ $f }}" {{ $f == $e->frequency ? 'selected' : '' }}>{{ $f }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Jumlah</label>
                            <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" id="amount" value="{{ old('amount', $e->amount ?? 0) }}" step="0.01" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
                    <h5 class="modal-title" id="deleteModalLabel">Delete Expenses</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this expenses?</p>
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
        function setDeleteUrl(url) {
            document.getElementById('delete-form').action = url;
        }
    </script>
@endsection