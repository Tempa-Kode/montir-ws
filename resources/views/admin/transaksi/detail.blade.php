@extends("layout.template")
@section("title", "Detail Transaksi #{{ $transaksi->id }}")
@section("breadcrumb")
    <div class="page-info">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route("transaksi.index") }}">Data Transaksi</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Transaksi #{{ $transaksi->id }}</li>
            </ol>
        </nav>
    </div>
@endsection
@section("main")
    {{-- Informasi Order Layanan --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informasi Order</h5>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th width="40%">ID Order</th>
                                <td>: #{{ $transaksi->id }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Order</th>
                                <td>: {{ $transaksi->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>: 
                                    @if($transaksi->status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($transaksi->status == 'kelokasi')
                                        <span class="badge badge-info">Menuju Lokasi</span>
                                    @elseif($transaksi->status == 'kerjakan')
                                        <span class="badge badge-primary">Sedang Dikerjakan</span>
                                    @elseif($transaksi->status == 'selesai')
                                        <span class="badge badge-success">Selesai</span>
                                    @else
                                        <span class="badge badge-secondary">{{ $transaksi->status }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Status Pembayaran</th>
                                <td>: 
                                    @if($transaksi->status_pembayaran == 'lunas')
                                        <span class="badge badge-success">Lunas</span>
                                    @elseif($transaksi->status_pembayaran == 'belum-lunas')
                                        <span class="badge badge-warning">Belum Lunas</span>
                                    @else
                                        <span class="badge badge-secondary">Belum Ada</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Harga Layanan</th>
                                <td>: {{ $transaksi->harga_layanan ? 'Rp ' . number_format($transaksi->harga_layanan, 0, ',', '.') : 'Belum ditentukan' }}</td>
                            </tr>
                            <tr>
                                <th>Lokasi Pelanggan</th>
                                <td>: {{ $transaksi->latitude }}, {{ $transaksi->longitude }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Pelanggan</h5>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th width="40%">Nama</th>
                                <td>: {{ $transaksi->pelanggan->nama }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>: {{ $transaksi->pelanggan->email }}</td>
                            </tr>
                            <tr>
                                <th>No. Telepon</th>
                                <td>: {{ $transaksi->pelanggan->no_telp }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>: {{ $transaksi->pelanggan->alamat }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Data Bengkel --}}
    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Bengkel</h5>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th width="40%">Nama Bengkel</th>
                                <td>: {{ $transaksi->layananBengkel->bengkel->nama }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>: {{ $transaksi->layananBengkel->bengkel->alamat }}</td>
                            </tr>
                            <tr>
                                <th>Lokasi</th>
                                <td>: {{ $transaksi->layananBengkel->bengkel->latitude }}, {{ $transaksi->layananBengkel->bengkel->longitude }}</td>
                            </tr>
                            <tr>
                                <th>Verifikasi</th>
                                <td>: 
                                    @if($transaksi->layananBengkel->bengkel->verifikasi)
                                        <span class="badge badge-success">Terverifikasi</span>
                                    @else
                                        <span class="badge badge-danger">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Jenis Layanan</th>
                                <td>: {{ $transaksi->layananBengkel->jenis_layanan }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Montir</h5>
                    @if($transaksi->montir)
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th width="40%">Nama Montir</th>
                                    <td>: {{ $transaksi->montir->user->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>: {{ $transaksi->montir->user->email }}</td>
                                </tr>
                                <tr>
                                    <th>No. Telepon</th>
                                    <td>: {{ $transaksi->montir->user->no_telp }}</td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">Montir belum ditugaskan</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Item Service --}}
    @if($transaksi->itemService && $transaksi->itemService->count() > 0)
    <div class="row mt-3">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Item Service</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Nama Item</th>
                                    <th width="20%">Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach($transaksi->itemService as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->nama_item }}</td>
                                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                </tr>
                                @php $total += $item->harga; @endphp
                                @endforeach
                                <tr class="font-weight-bold">
                                    <td colspan="2" class="text-right">Total Item:</td>
                                    <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <td colspan="2" class="text-right">Biaya Layanan:</td>
                                    <td>Rp {{ number_format($transaksi->harga_layanan ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="font-weight-bold bg-light">
                                    <td colspan="2" class="text-right">Total Keseluruhan:</td>
                                    <td>Rp {{ number_format($total + ($transaksi->harga_layanan ?? 0), 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Bukti Pembayaran --}}
    @if($transaksi->bukti_bayar)
    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Bukti Pembayaran</h5>
                    <img src="{{ asset($transaksi->bukti_bayar) }}" alt="Bukti Pembayaran" class="img-fluid rounded" style="max-height: 400px;">
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Ulasan & Rating --}}
    @if($transaksi->ulasanRating)
    <div class="row mt-3">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ulasan & Rating</h5>
                    <div class="mb-2">
                        <strong>Rating:</strong>
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $transaksi->ulasanRating->rating)
                                <i class="material-icons text-warning" style="font-size: 20px;">star</i>
                            @else
                                <i class="material-icons text-muted" style="font-size: 20px;">star_border</i>
                            @endif
                        @endfor
                        <span class="ml-2">({{ $transaksi->ulasanRating->rating }}/5)</span>
                    </div>
                    <div>
                        <strong>Ulasan:</strong>
                        <p class="mt-2">{{ $transaksi->ulasanRating->ulasan ?? 'Tidak ada ulasan' }}</p>
                    </div>
                    <small class="text-muted">Diberikan pada: {{ $transaksi->ulasanRating->created_at->format('d M Y, H:i') }}</small>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Tombol Kembali --}}
    <div class="row mt-3">
        <div class="col">
            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
                <i class="material-icons">arrow_back</i> Kembali
            </a>
        </div>
    </div>

@endsection
