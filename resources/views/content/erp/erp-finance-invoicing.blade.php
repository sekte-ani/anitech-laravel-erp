@extends('partial.layout-page', ['title' => 'ERP-Finance-Invoicing'])

@section('content')
<div class="d-flex justify-content-end my-3">
    <button type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#invoiceModal">Add</button>
</div>

 <!-- Bootstrap Table with Header - Light -->
 <div class="card">
    <h5 class="card-header">Invoices List</h5>
    <div class="table-responsive text-nowrap">
        <form action="{{ route('invoicing') }}" method="GET" class="d-flex mb-3">
            <input type="month" name="month" class="form-control me-2" value="{{ request('month') }}">
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
      <table class="table table-hover">
        <thead class="table-light text-center">
            <tr>
                <th>#</th>
                <th>Tipe</th>
                <th>Client</th>
                <th>Item</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @foreach ($invoices as $i)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $i->type }}</td>
            <td>{{ $i->orders->pluck('client.name')->filter()->unique()->implode(', ') }}</td>
            <td>{{ $i->orders->pluck('item')->filter()->implode(', ') }}</td>
            <td>{{ $i->amount_due }}</td>
            <td>{{ $i->status }}</td>
            <td>
            @if (Auth::check() && Auth::user()->employee && Auth::user()->employee->roles->whereIn('name', ['Admin', 'Pimpinan Tim Operasional', 'Staff Finance'])->isNotEmpty())
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editInvoiceModal{{ $i->id }}"
                        ><i class="bx bx-edit-alt me-1"></i> Edit</a
                    >
                    @if (Auth::user()->employee->roles->whereIn('name', ['Admin', 'Pimpinan Tim Operasional'])->isNotEmpty())
                    <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="setDeleteUrl('{{ route('invoicing.delete', $i->id) }}')"
                        ><i class="bx bx-trash me-1"></i> Delete</a
                    >
                    @endif
                    <a class="dropdown-item" href="{{ route('invoice.preview', $i->id) }}" target="_blank">
                        <i class="bx bx-download me-1"></i> Download
                    </a>
                    <a href="{{ route('invoice.download', $i->id) }}" class="btn btn-success">Download PDF</a>
                </div>
            </div>
            @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      {{ $invoices->withQueryString()->links() }}
    </div>
  </div>
  <!-- Bootstrap Table with Header - Light -->

  <!-- Modal -->
  <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="invoiceModalLabel">Add Invoice</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('invoicing.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="date" class="form-label">Tanggal</label>
                        <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                        <label class="form-label">Pilih Order</label>
                        <div class="row" id="order-list">
                            @foreach ($orders as $index => $o)
                                <div class="col-md-6 order-item" 
                                    data-client-id="{{ $o->client_id }}" 
                                    data-status="{{ $o->status }}">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="order_id[]" value="{{ $o->id }}">
                                        <label class="form-check-label">
                                            {{ $o->client->name }}: {{ $o->item }}
                                        </label>
                                    </div>
                                </div>
                    
                                @if (($index + 1) % 5 == 0)
                                    </div><div class="row">
                                @endif
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-primary mx-3" id="internalModalBtn" data-bs-toggle="modal" data-bs-target="#internalModal" style="display: none;">
                            + Internal Invoice Item
                        </button>
                    </div>
                    <div class="mb-3">
                        <label for="discount" class="form-label">Diskon</label>
                        <input type="number" step="0.01" name="discount" id="discount" class="form-control @error('discount') is-invalid @enderror" value="{{ old('discount') }}">
                        @error('discount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Note</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
  
  <div class="modal fade" id="internalModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add Internal Item </h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="{{ route('progress.store') }}" enctype="multipart/form-data">
                @csrf
                    <div class="mb-3">
                        <label for="client_id" class="form-label">Tujuan</label>
                        <input type="text" name="client_id" class="form-control @error('client_id') is-invalid @enderror" id="client_id" value="{{ $client->name }}">
                        @error('client_id')
                            <div class="invalid-feedback">
                            {{ $message }}
                            </div>
                        @enderror
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

  @foreach($invoices as $i)
    <div class="modal fade" id="editInvoiceModal{{ $i->id }}" tabindex="-1" aria-labelledby="editExpenseModalLabel{{ $i->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editInvoiceModalLabel{{ $i->id }}">Edit Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('invoicing.update', $i->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="invoice_number" value="{{ old('invoice_number', $i->invoice_number) }}">
                        <div class="mb-3">
                            <label for="date" class="form-label">Tanggal</label>
                            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" id="date" value="{{ old('date', $i->date) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Tipe</label>
                            <input type="text" name="type" id="type" class="form-control @error('type') is-invalid @enderror" value="{{ old('type', $i->type) }}" readonly/>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Order Item</label>
                            <div class="row">
                                @php
                                    $availableOrders = $i->orders->merge($orders);
                                @endphp
                        
                                @foreach ($availableOrders as $index => $o)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="order_id[]" value="{{ $o->id }}" @if($i->orders->contains('id', $o->id)) checked @endif>
                                            <label class="form-check-label">
                                                {{ $o->client->name }}: {{ $o->item }}
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
                            <label for="discount" class="form-label">Diskon</label>
                            <input type="number" step="0.01" name="discount" id="discount" class="form-control @error('discount') is-invalid @enderror" value="{{ old('discount', $i->discount) }}">
                            @error('discount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="notesEdit{{ $i->id }}" class="form-label">Note</label>
                            @error('notes')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                            <textarea name="notes" id="notesEdit{{ $i->id }}" required>{{ old('notes', $i->notes) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            @foreach ($status as $s)
                            @php
                                $cek = ($s == $i->status) ? 'checked' : '';
                            @endphp
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input name="status" id="status{{ $s }}" type="radio" class="custom-control-input" value="{{ $s }}" {{ $cek }}> 
                                    <label for="status{{ $s }}" class="custom-control-label">{{ $s }}</label>
                                </div>
                            @endforeach
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
                    <h5 class="modal-title" id="deleteModalLabel">Delete Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this invoices?</p>
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

    <script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const typeSelect = document.getElementById("type");
            const internalModalBtn = document.getElementById("internalModalBtn");
            const orderItems = document.querySelectorAll(".order-item");

            function filterOrders() {
                const selectedType = typeSelect.value;

                orderItems.forEach(order => {
                    const clientId = order.getAttribute("data-client-id");
                    const orderStatus = order.getAttribute("data-status");

                    if (selectedType === "Internal") {
                        if (clientId === "1" && orderStatus === "Pending") {
                            order.style.display = "block";
                            hasValidInternalOrder = true;
                        } else {
                            order.style.display = "none";
                        }
                    } else if (selectedType === "Order") {
                        if (clientId !== "1") {
                            order.style.display = "block";
                        } else {
                            order.style.display = "none";
                        }
                    } else {
                        order.style.display = "none";
                    }
                });

                if (selectedType === "Internal") {
                    internalModalBtn.style.display = "inline-block";
                } else {
                    internalModalBtn.style.display = "none";
                }
            }

            typeSelect.addEventListener("change", filterOrders);
            filterOrders();
        });

        CKEDITOR.replace( 'notes' );
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".modal").forEach(modal => {
                modal.addEventListener("shown.bs.modal", function() {
                    this.querySelectorAll("textarea[name='notes']").forEach(textarea => {
                        if (!textarea.classList.contains("ck-initialized")) {
                            CKEDITOR.replace(textarea.id);
                            textarea.classList.add("ck-initialized");
                        }
                    });
                });
            });
        });

        function setDeleteUrl(url) {
            document.getElementById('delete-form').action = url;
        }
    </script>
@endsection