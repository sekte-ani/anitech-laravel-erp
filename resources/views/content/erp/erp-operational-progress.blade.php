@extends('partial.layout-page', ['title' => 'ERP-Operational-Progress'])

@section('content')
<div class="d-flex justify-content-end my-3">
    <button type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#orderModal">Add Order</button>
</div>

 <!-- Bootstrap Table with Header - Light -->
 <div class="card">
    <h5 class="card-header">Order List</h5>
    <div class="table-responsive text-nowrap">
      <table class="table table-hover">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Item</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @foreach ($orders as $o)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $o->client->name }}</td>
            <td>{{ $o->item }}</td>
            <td>{{ $o->quantity }}</td>
            <td>{{ $o->price }}</td>
            <td>{{ $o->amount }}</td>
            <td>
                @if ($o->status == 'Pending')
                    <span class="badge bg-label-warning me-1">Pending</span>
                @elseif ($o->status == 'On Progress')
                    <span class="badge bg-label-primary me-1">On Progress</span>
                @elseif ($o->status == 'Completed')
                    <span class="badge bg-label-success me-1">Completed</span>
                @else
                    <span class="badge bg-label-danger me-1">Cancelled</span>
                @endif
            </td>
            <td>
            @if (Auth::check() && (Auth::user()->employee->roles->contains('name', 'Admin')))
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editOrderModal{{ $o->id }}"
                        ><i class="bx bx-edit-alt me-1"></i> Edit</a
                    >
                    @if (Auth::user()->employee->roles[0]->name == 'Admin')
                    <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="setDeleteUrl('{{ route('progress.delete', $o->id) }}')"
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
      {{ $orders->withQueryString()->links() }}
    </div>
  </div>
  <!-- Bootstrap Table with Header - Light -->

  <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add Order </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="{{ route('progress.store') }}" enctype="multipart/form-data">
            @csrf
                <div class="mb-3">
                    <label for="client_id" class="form-label">Client</label>
                    <select class="form-select" name="client_id" required>
                    <option value="" disabled selected>--- Pilih Client ---</option>
                    @foreach ($clients as $c)
                    @if (old('client_id') == $c->id)
                        <option value="{{ $c->id }}" selected>{{ $c->name }}</option>
                    @else
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endif
                    @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="item" class="form-label">Item</label>
                    <input type="text" name="item" class="form-control @error('item') is-invalid @enderror" id="item" placeholder="Masukkan item">
                    @error('item')
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" id="quantity" placeholder="Masukkan quanity" required>
                    @error('quantity')
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" id="price" placeholder="Masukkan harga" required>
                    @error('price')
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" id="amount" readonly>
                    @error('amount')
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

  @foreach($orders as $o)
  <div class="modal fade" id="editOrderModal{{ $o->id }}" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Order </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="{{ route('progress.update', $o->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
                <div class="mb-3">
                    <label for="client_id" class="form-label">Client</label>
                    <select class="form-select" name="client_id" required>
                    @foreach ($clients as $c)
                    @if (old('client_id') == $c->id)
                        <option value="{{ $c->id }}" selected>{{ $c->name }}</option>
                    @else
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endif
                    @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="item" class="form-label">Item</label>
                    <input type="text" name="item" value="{{ old('item', $o->item) }}" class="form-control @error('item') is-invalid @enderror" id="item">
                    @error('item')
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantity" value="{{ old('quantity', $o->quantity) }}" class="form-control @error('quantity') is-invalid @enderror" id="quantity">
                    @error('quantity')
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Harga</label>
                    <input type="number" name="price" value="{{ old('price', $o->price) }}" class="form-control @error('price') is-invalid @enderror" id="price">
                    @error('price')
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" name="amount" value="{{ old('amount', $o->amount) }}" class="form-control @error('amount') is-invalid @enderror" id="amount" readonly>
                    @error('amount')
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    @foreach ($status as $s)
                      @php
                          $cek = ($s == $o->status) ? 'checked' : '';
                      @endphp
                          <div class="custom-control custom-radio custom-control-inline">
                              <input name="status" id="status{{ $s }}" type="radio" class="custom-control-input" value="{{ $s }}" {{ $cek }}> 
                              <label for="status{{ $s }}" class="custom-control-label">{{ $s }}</label>
                          </div>
                    @endforeach
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

  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this order?</p>
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
    const quantity = document.querySelector('#quantity');
    const price = document.querySelector('#price');
    const amount = document.querySelector('#amount');

    function updateAmount() {
        fetch(`/amount?quantity=${quantity.value}&price=${price.value}`)
            .then(response => response.json())
            .then(data => amount.value = data.amount);
    }

    quantity.addEventListener('input', updateAmount);
    price.addEventListener('input', updateAmount);

    function setDeleteUrl(url) {
        document.getElementById('delete-form').action = url;
    }
</script>
@endsection