@extends('partial.layout-page', ['title' => 'ERP-Finance-Audit'])

@section('content')
<!-- Bootstrap Table with Header - Light -->
<div class="card">
    <h5 class="card-header">Audit Expenses Tracker</h5>
    <div class="table-responsive text-nowrap">
        <form action="{{ route('audit') }}" method="GET" class="d-flex mb-3">
            <input type="month" name="month" class="form-control me-2" value="{{ request('month') }}">
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
        <table class="table table-hover">
            <thead class="table-light text-center">
                <tr>
                    <th rowspan="2" class="align-middle">#</th>
                    <th rowspan="2" class="align-middle">Editor</th>
                    <th rowspan="2" class="align-middle">Table</th>
                    <th rowspan="2" class="align-middle">Kegiatan</th>
                    <th rowspan="2" class="align-middle">Kategori</th>
                    <th rowspan="2" class="align-middle">Tipe</th>
                    <th rowspan="2" class="align-middle">Sumber</th>
                    <th colspan="2">Jumlah</th>
                    <th rowspan="2" class="align-middle">Saldo</th>
                </tr>
                <tr>
                    <th>Income</th>
                    <th>Expense</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($audit as $a)
                    @php
                        $oldData = json_decode($a->old_data, true);
                        $newData = json_decode($a->new_data, true);
                        $categoryId = $newData['category_id'] ?? $oldData['category_id'] ?? null;
                        $categoryName = $categoryId ? ($categories[$categoryId] ?? '-') : '-';
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $a->user->employee->name }}</td>
                        <td>{{ $a->table_name }}</td>
                        <td>{{ $a->action }}</td>
                        <td>{{ $categoryName }}</td>
                        <td>{{ $newData['type'] ?? $oldData['type'] ?? '-' }}</td>
                        <td>{{ $newData['frequency'] ?? $oldData['frequency'] ?? '-' }}</td>
                        <td class="text-success fw-bold">
                            @if (($newData['type'] ?? $oldData['type']) === 'Pemasukan')
                                {{ number_format($newData['amount'] ?? $oldData['amount'], 0, ',', '.') }}
                            @endif
                        </td>
                        <td class="text-danger fw-bold">
                            @if (($newData['type'] ?? $oldData['type']) === 'Pengeluaran')
                                {{ number_format($newData['amount'] ?? $oldData['amount'], 0, ',', '.') }}
                            @endif
                        </td>
                        <td>{{ number_format($newData['balance'] ?? $oldData['balance'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
  <!-- Bootstrap Table with Header - Light -->
@endsection