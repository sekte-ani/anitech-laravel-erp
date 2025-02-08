@extends('partial.layout-page', ['title' => 'General-Product'])

@section('content')
<div class="d-flex justify-content-end my-3">
    <button type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#productModal">Add Item</button>
</div>

 <!-- Bootstrap Table with Header - Light -->
 <div class="card">
  <h5 class="card-header">Product List</h5>
  <div class="table-responsive text-nowrap">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Description</th>
          <th>Images</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @foreach($products as $p)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $p->name }}</td>
          <td>{!! html_entity_decode($p->description) !!}</td>
          <td>
            @if ($p->images)
                {{-- <img src="{{ env('STORAGE_URL') . $p->images }}" style="object-fit: cover; width: 100%; height: 100px; max-width: 200px;" class="card-img" alt="..."> --}}
                <img src="{{ asset('storage/' . $p->images) }}" class="card-img" style="object-fit: cover; width: 100%; height: 100px; max-width: 200px;" alt="...">
            @else
                <img src="https://picsum.photos/seed/nophoto" style="object-fit: cover; width: 100%; height: 100px; max-width: 200px;" class="card-img" alt="...">
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
                <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="setDeleteUrl('{{ route('product.delete', $p->id) }}')"
                  ><i class="bx bx-trash me-1"></i> Delete</a
              >
              </div>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {{ $products->withQueryString()->links() }}
  </div>
</div>
  <!-- Bootstrap Table with Header - Light -->

  <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add Product</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" action="{{ route('product.store') }}" enctype="multipart/form-data">
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
                  <label for="decription" class="form-label">Deskripsi Produk</label>
                  @error('description')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                  <textarea name="description" id="description" required>{{ old("description") }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="images" class="form-label">Foto Produk</label>
                    <img class="img-preview img-fluid mb-3 col-sm-5">
                    <input class="form-control @error('images') is-invalid @enderror" onchange="previewImage()" type="file" name="images" id="images" accept="image/png, image/jpg, image/jpeg" required>
                    @error('img')
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

  <script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
  <script>
    const name = document.querySelector('#name');
    const slug = document.querySelector('#slug');

    name.addEventListener('change', function(){
        fetch('/checkSlugName?name=' + name.value)
        .then(response => response.json())
        .then(data => slug.value = data.slug)
    });

    CKEDITOR.replace( 'description' );

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