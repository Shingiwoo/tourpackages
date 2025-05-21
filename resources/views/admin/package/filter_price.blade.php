@extends('admin.admin_dashboard')
@section('admin')
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="mb-4 row">
                <h5 class="mb-0 font-semibold">Search Price</h5>
                <p class="text-sm text-gray-600">Cari harga paket wisata berdasakan kriteria yang dipilih.</p>
                <hr class="my-2" />
            </div>

            <div class="mb-4 row">
                <form method="GET" action="{{ route('price.search') }}">
                    <div
                        class="row-gap-4 mb-6 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-1">Filter Paket Wisata</h6>
                            <p class="text-sm text-gray-600">Silakan isi kolom sesuai dengan yang di butuhkan</p>
                        </div>
                        <div class="flex-wrap gap-4 d-flex align-content-center">
                            <label for="submit" class="form-label">&nbsp;</label>
                            <button type="submit" id="submit" class="btn btn-primary w-100">Tampilkan</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-2 col-sm-6 col-lg-3">
                            <div class="pb-4 d-flex justify-content-between align-items-start card-widget-1 pb-sm-0">
                                <label for="min_peserta" class="form-label">Min Peserta</label>
                                <input type="number" min="0" name="min_peserta" id="min_peserta"
                                    class="form-control" value="{{ request('min_peserta') }}">
                            </div>
                        </div>
                        <div class="mb-2 col-sm-6 col-lg-3">
                            <div class="pb-4 d-flex justify-content-between align-items-start card-widget-1 pb-sm-0">
                                <label for="max_peserta" class="form-label">Max Peserta</label>
                                <input type="number" min="0" name="max_peserta" id="max_peserta"
                                    class="form-control" value="{{ request('max_peserta') }}">
                            </div>
                        </div>
                        <div class="mb-2 col-sm-6 col-lg-3">
                            <div class="pb-4 d-flex justify-content-between align-items-start card-widget-1 pb-sm-0">
                                <label for="durasi_paket" class="form-label">Durasi Paket</label>
                                <select name="durasi_paket" id="durasi_paket" class="form-select" required>
                                    <option value="">-- Semua Durasi --</option>
                                    <option value="1" {{ request('durasi_paket') == '1' ? 'selected' : '' }}>1 Hari
                                    </option>
                                    <option value="2" {{ request('durasi_paket') == '2' ? 'selected' : '' }}>2 Hari
                                    </option>
                                    <option value="3" {{ request('durasi_paket') == '3' ? 'selected' : '' }}>3 Hari
                                    </option>
                                    <option value="4" {{ request('durasi_paket') == '4' ? 'selected' : '' }}>4 Hari
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="pb-4 d-flex justify-content-between align-items-start card-widget-1 pb-sm-0">
                                <label for="agen_name" class="form-label">Agen Name</label>
                                <select name="agen_name" id="agen_name" class="form-select" required>
                                    <option value="">-- Semua Agen --</option>
                                    @foreach ($agens as $agen)
                                        <option value="{{ $agen->id }}"
                                            {{ request('agen_name') == $agen->id ? 'selected' : '' }}>
                                            {{ $agen->username }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @if (isset($packages) && $packages->count() > 0)
                <div class="p-3 row">
                    @foreach ($packages as $package)
                        <div class="mb-4 card">
                            <div class="mt-3 row">
                                <div class="text-center col-sm-4 align-content-center">
                                    <h5><b>Nama Paket</b></h5>
                                </div>
                                <div class="mb-0 text-center col-sm-8 align-content-center">
                                    <h4 class="text-info">{{ $package['name_package'] }}</h4>
                                </div>
                            </div>

                            <!-- Prices -->
                            @if (isset($package['grouped_prices']) && !empty($package['grouped_prices']))
                                <!-- Multi-day package prices -->
                                @foreach ($package['grouped_prices'] as $type => $pricesByParticipant)
                                    @if (!empty($pricesByParticipant))
                                        <div class="mt-3 row">
                                            <div class="text-center col-sm-12 align-content-center">
                                                <h5><b>{{ strtoupper(str_replace(' ', ' - ', $type)) }}</b></h5>
                                            </div>
                                        </div>

                                        @foreach ($pricesByParticipant as $participantCount => $price)
                                            <div class="row mb-4">
                                                <div class="my-1 col-sm-3 align-content-center"><b>Kendaraan</b></div>
                                                <div class="my-1 col-sm-3 align-content-center">{{ $price['vehicle'] }}
                                                </div>
                                                <div class="my-1 col-sm-3 align-content-center"><b>Peserta</b></div>
                                                <div class="my-1 col-sm-3 align-content-center">{{ $participantCount }} Pax
                                                </div>
                                            </div>

                                            @php
                                                $accommodations = [
                                                    'WithoutAccomodation' => 'Tanpa Penginapan',
                                                    'Cottage' => 'Cottage',
                                                    'Homestay' => 'Homestay',
                                                    'Villa' => 'Villa',
                                                    'Guesthouse' => 'Guesthouse',
                                                    'ThreeStar' => 'Hotel Bintang 3',
                                                    'FourStar' => 'Hotel Bintang 4',
                                                ];

                                                $availableAccommodations = array_filter(
                                                    $accommodations,
                                                    fn($key) => isset($price[$key]),
                                                    ARRAY_FILTER_USE_KEY,
                                                );

                                                $chunks = array_chunk($availableAccommodations, 2, true);
                                            @endphp

                                            @foreach ($chunks as $chunk)
                                                <div class="row mb-4">
                                                    @foreach ($chunk as $accKey => $accName)
                                                        @if (isset($price[$accKey]))
                                                            <div class="my-1 col-sm-3 align-content-center">
                                                                <b>{{ $accName }}</b></div>
                                                            <div class="my-1 col-sm-3 align-content-center">
                                                                <span class="price-value"
                                                                      data-label="{{ $accName }}"
                                                                      data-price="{{ $price[$accKey] }}">
                                                                    Rp {{ number_format($price[$accKey], 0, ',', '.') }} /orang
                                                                </span>
                                                                <button class="btn btn-sm btn-outline-secondary copy-btn"
                                                                        data-label="{{ $accName }}"
                                                                        data-price="{{ $price[$accKey] }}"
                                                                        title="Salin ke clipboard">
                                                                    <i class="ti ti-file-check"></i>
                                                                </button>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        @endforeach

                                        @if (!$loop->last)
                                            <hr>
                                        @endif
                                    @endif
                                @endforeach
                                <!-- One-day package prices -->
                            @elseif(isset($package['prices']) && !empty($package['prices']))
                                <div class="mt-3 row">
                                    <div class="text-center col-sm-12 align-content-center">
                                        <h5><b>HARGA PAKET</b></h5>
                                    </div>
                                </div>

                                @foreach ($package['prices'] as $price)
                                    <div class="row mb-4">
                                        <div class="my-1 col-sm-3 align-content-center"><b>Kendaraan</b></div>
                                        <div class="my-1 col-sm-3 align-content-center">{{ $price['vehicle'] }}</div>
                                        <div class="my-1 col-sm-3 align-content-center"><b>Peserta</b></div>
                                        <div class="my-1 col-sm-3 align-content-center">{{ $price['user'] }} Pax</div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="my-1 col-sm-3 align-content-center"><b>Harga Normal</b></div>
                                        <div class="my-1 col-sm-3 align-content-center">
                                            <span class="price-value"
                                                  data-label="Harga Normal"
                                                  data-price="{{ $price['price'] }}">
                                                Rp {{ number_format($price['price'], 0, ',', '.') }} /orang
                                            </span>
                                            <button class="btn btn-sm btn-outline-secondary copy-btn"
                                                    data-label="Harga Normal"
                                                    data-price="{{ $price['price'] }}"
                                                    title="Salin ke clipboard">
                                                <i class="ti ti-file-check"></i>
                                            </button>
                                        </div>
                                        <div class="my-1 col-sm-3 align-content-center"><b>Tanpa Makan</b></div>
                                        <div class="my-1 col-sm-3 align-content-center">
                                            <span class="price-value"
                                                  data-label="Tanpa Makan"
                                                  data-price="{{ $price['nomeal'] }}">
                                                Rp {{ number_format($price['nomeal'], 0, ',', '.') }} /orang
                                            </span>
                                            <button class="btn btn-sm btn-outline-secondary copy-btn"
                                                    data-label="Tanpa Makan"
                                                    data-price="{{ $price['nomeal'] }}"
                                                    title="Salin ke clipboard">
                                                <i class="ti ti-file-check"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @if (isset($price['wnaCost']))
                                        <div class="row mb-4">
                                            <div class="my-1 col-sm-3 align-content-center"><b>Biaya WNA</b></div>
                                            <div class="my-1 col-sm-3 align-content-center">
                                                <span class="price-value"
                                                      data-label="Biaya WNA"
                                                      data-price="{{ $price['wnaCost'] }}">
                                                    Rp {{ number_format($price['wnaCost'], 0, ',', '.') }} /orang
                                                </span>
                                                <button class="btn btn-sm btn-outline-secondary copy-btn"
                                                        data-label="Biaya WNA"
                                                        data-price="{{ $price['wnaCost'] }}"
                                                        title="Salin ke clipboard">
                                                    <i class="ti ti-file-check"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                    @if (!$loop->last)
                                        <hr>
                                    @endif
                                @endforeach
                            @else
                                <div class="mt-3 row">
                                    <div class="text-center col-12">
                                        <p class="text-danger">Harga tidak tersedia untuk kriteria yang dipilih</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @elseif(request()->hasAny(['min_peserta', 'max_peserta', 'durasi_paket', 'agen_name']))
                <div class="alert alert-warning">
                    Tidak ada paket yang ditemukan dengan kriteria yang dipilih.
                </div>
            @endif

        </div>
        <!-- End content -->
    </div>
    <!-- End content wrapper -->
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Existing form validation code
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const durasi = document.getElementById('durasi_paket').value;
                const agen = document.getElementById('agen_name').value;
                const minPeserta = document.getElementById('min_peserta').value;
                const maxPeserta = document.getElementById('max_peserta').value;

                if (!durasi) {
                    alert('Silakan pilih durasi paket');
                    e.preventDefault();
                    return;
                }

                if (!agen) {
                    alert('Silakan pilih agen');
                    e.preventDefault();
                    return;
                }

                if (!minPeserta && !maxPeserta) {
                    alert('Silakan isi minimal peserta atau maksimal peserta');
                    e.preventDefault();
                    return;
                }

                if (minPeserta && maxPeserta && parseInt(minPeserta) > parseInt(maxPeserta)) {
                    alert('Minimal peserta tidak boleh lebih besar dari maksimal peserta');
                    e.preventDefault();
                    return;
                }
            });

            // Enhanced copy to clipboard functionality
            document.querySelectorAll('.copy-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const label = this.getAttribute('data-label');
                    const price = this.getAttribute('data-price');
                    const formattedPrice = 'Rp ' + parseInt(price).toLocaleString('id-ID');
                    const textToCopy = `${label}\n${formattedPrice} /orang`;

                    navigator.clipboard.writeText(textToCopy).then(() => {
                        // Change button appearance temporarily
                        const originalInnerHTML = this.innerHTML;
                        this.innerHTML = '<i class="ti ti-file-check"></i>';
                        this.classList.remove('btn-outline-secondary');
                        this.classList.add('btn-outline-success');

                        // Revert after 2 seconds
                        setTimeout(() => {
                            this.innerHTML = originalInnerHTML;
                            this.classList.remove('btn-outline-success');
                            this.classList.add('btn-outline-secondary');
                        }, 2000);
                    }).catch(err => {
                        console.error('Failed to copy: ', err);
                        alert('Gagal menyalin ke clipboard');
                    });
                });
            });
        });
    </script>
@endsection
