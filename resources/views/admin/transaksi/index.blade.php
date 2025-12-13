@extends("layout.template")
@section("title", "Data Transaksi")
@section("breadcrumb")
    <div class="page-info">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Data Transaksi</li>
            </ol>
        </nav>
    </div>
@endsection
@section("main")
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Data Transaksi</h5>
                        <a href="{{ route('laporan.transaksi') }}" class="btn btn-primary mb-3">
                            <span class="material-icons-outlined mr-3" style="font-size: 18px">
                                download
                            </span>
                            Laporan
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table id="zero-conf" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Bengkel</th>
                                    <th>Layanan</th>
                                    <th>Pelanggan</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaksi as $t)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $t->layananBengkel->bengkel->nama }}</td>
                                        <td>{{ $t->layananBengkel->jenis_layanan }}</td>
                                        <td>{{ $t->pelanggan->nama }}</td>
                                        <td>
                                            <span class="badge badge-{{ $t->status === 'selesai' ? 'success' : 'warning' }}">{{ ucfirst($t->status) }}</span>
                                        </td>
                                        <td>{{ $t->created_at->diffForHumans() }}</td>
                                        <td>
                                            <a href="{{ route('transaksi.show', $t->id) }}" class="btn btn-info btn-sm">Detail</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Nama</th>
                                    <th>No Telp</th>
                                    <th>Email</th>
                                    <th>Tanggal Bergabung</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
