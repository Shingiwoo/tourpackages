@extends('admin.admin_dashboard')
@section('admin')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div
                class="row-gap-4 mb-6 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="py-3 mb-1 fw-bold">Detail Biaya Pengeluaran</h4>
                    <p class="mb-0">Rincian biaya operasional setiap trip berdasarkan code boooking </p>
                </div>
                <div class="flex-wrap gap-4 d-flex align-content-center">
                </div>
            </div>
            <div class="mb-4 card">
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered">
                            <tr>
                                <th rowspan="2" class="text-center align-content-center text-uppercase">
                                    <label class="form-label text-info">Kode Booking</label> <br>
                                    <h5 class="form-control-static"><b>{{ $booking->code_booking }}</b></h5>
                                </th>
                                <td class="text-center align-content-center text-uppercase"><label
                                        class="form-label text-info">Nama Paket</label></td>
                                <td class="text-center align-content-center text-uppercase"><label
                                        class="form-label text-info">Status</label></td>
                            </tr>
                            <tr>
                                <td class="text-center align-content-center text-uppercase">
                                    <p class="align-content-center"><b>{{ $booking->package_name ?? 'N/A' }}</b></p>
                                </td>
                                <td class="text-center align-content-center text-uppercase">
                                    <p class="align-content-center"><b>{{ $booking->status }}</b></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="mt-4">
                        <h5 class="mb-1 align-content-center text-uppercase" style="padding-left: 5px">Rincian Biaya</h5>
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Aksi</th>
                                        <th class="text-center">Tanggal</th>
                                        <th class="text-center">Deskripsi & Akun</th>
                                        <th class="text-center">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($expenses as $expense)
                                        <tr>
                                            <td style="width: 15%" class="text-center">
                                                <a href="{{ route('edit.expense', $expense->id) }}"
                                                    class="badge bg-warning bg-glow">
                                                    <i class="ti ti-edit"></i> Edit
                                                </a>
                                                <a href="javascript:void(0)" class="badge bg-danger bg-glow delete-expense"
                                                    data-id="{{ $expense->id }}"
                                                    data-url="{{ route('delete.expense', $expense->id) }}">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            </td>
                                            <td class="text-center align-content-center text-uppercase">{{ $expense->date }}</td>
                                            <td class="text-start align-content-center text-uppercase">
                                                <b>{{ $expense->description }}</b> <br>
                                                {{ $expense->account->code }} | {{ $expense->account->name }}
                                            </td>
                                            <td class="text-end align-content-center">
                                                <span class="badge bg-primary rounded-pill">Rp
                                                    {{ number_format($expense->amount, 0, ',', '.') }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada data biaya.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 text-end" style="padding-right: 5px">
                            <h5>Total Biaya: Rp {{ number_format($total_cost, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ route('all.expenses') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <!-- Sertakan SweetAlert2 untuk konfirmasi hapus -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Tangani klik tombol hapus
            const deleteButtons = document.querySelectorAll('.delete-expense');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const expenseId = this.getAttribute('data-id');
                    const deleteUrl = this.getAttribute('data-url');

                    // Konfirmasi hapus menggunakan SweetAlert2
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: 'Data biaya ini akan dihapus permanently!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Kirim permintaan DELETE melalui AJAX
                            fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.message) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: data.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        // Refresh halaman atau hapus baris dari tabel
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: data.error || 'Terjadi kesalahan saat menghapus data.'
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan: ' + error.message
                                });
                            });
                        }
                    });
                });
            });
        });
    </script>
@endsection
