@extends('layouts.admin-layout')

@section('page_title', 'Money Savings | List Tabungan')

@section('title', 'List Tabungan')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Tabungan</h3>
        </div>
        <div class="table-responsive">
            <table class="table card-table table-vcenter text-nowrap">
                <thead>
                <tr>
                    <th class="w-1">No.</th>
                    <th>Nama</th>
                    <th>Kredit</th>
                    <th>Debit</th>
                    <th>Persentase</th>
                    <th>Performa</th>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($savingHistory as $key => $item)
                    <tr>
                        <td class="text-muted">{{ $key + 1 }}</td>
                        <td>{{ $item->savings->user->name }}</td>
                        <td>{{ $item->type == 'revenue' ? 'IDR ' . number_format($item->amount) : '-' }}</td>
                        <td>{{ $item->type == 'expense' ? 'IDR ' . number_format($item->amount) : '-' }}</td>
                        <td>{{ $item->saving_rate }}%</td>
                        <td>
                            @if ($item->is_increase)
                                <p class="text-success">
                                    <i class="fe fe-trending-up h-4"></i>
                                </p>
                            @else
                                <p class="text-warning">
                                    <i class="fe fe-trending-down h-4"></i>
                                </p>
                            @endif
                        </td>
                        <td>{{ date('l, d F Y') }}</td>
                        <td>{{ $item->note ?: '-' }}</td>
                        <td>
                            <form action="{{ '' }}" method="POST" onsubmit="return isConfirm()">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-link text-danger" title="Delete">
                                    <i class="fe fe-trash-2"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                <tr class="table-primary font-weight-bold">
                    <td colspan="2">Total</td>
                    <td>IDR {{ number_format(\App\Models\SavingHistory::where('type', 'revenue')->sum('amount') - \App\Models\SavingHistory::where('type', 'expense')->sum('amount')) }}</td>
                    <td>IDR {{ number_format(\App\Models\SavingHistory::where('type', 'expense')->sum('amount')) }}</td>
                    <td colspan="5"></td>
            </table>

            <div class="card-body row">
                <div class="col-lg-12 d-flex justify-content-center">
                    {{ $savingHistory->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
        </div>

        <script>
            function isConfirm() {
                var response = confirm("Beneran mau hapus data ini ?")

                if (response) {
                    return true
                } else {
                    return false
                }
            }
        </script>
@endsection
