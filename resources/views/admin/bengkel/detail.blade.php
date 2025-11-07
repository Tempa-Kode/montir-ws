@extends("layout.template")
@section("title", "Data Bengkel")
@section("breadcrumb")
    <div class="page-info">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ route("bengkel.index") }}">Data Bengkel</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $bengkel->nama }}</li>
            </ol>
        </nav>
    </div>
@endsection
@section("main")
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if (!$bengkel->verifikasi)
        <div class="alert alert-danger" role="alert">
            <h6 class="alert-heading">Permohonan Ditolak</h6>
            <hr>
            <p>{{ $bengkel->alasan_penolakan }}</p>
        </div>
    @endif

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Bengkel</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama Bengkel</th>
                                    <th>Pemilik</th>
                                    <th>Alamat</th>
                                    <th>Verifikasi</th>
                                    <th>Tanggal Bergabung</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $bengkel->nama }}</td>
                                    <td>{{ $bengkel->user->nama }}</td>
                                    <td>{{ $bengkel->alamat }}</td>
                                    <td>
                                        @if($bengkel->verifikasi)
                                            <span class="badge badge-success">Terverifikasi</span>
                                        @else
                                            <span class="badge badge-warning">Belum Terverifikasi</span>
                                        @endif
                                    </td>
                                    <td>{{ $bengkel->created_at->format('d M Y') }}</td>
                                    <td>
                                        @if (!$bengkel->verifikasi)
                                            <form action="{{ route('bengkel.accept', $bengkel->id) }}" method="post" class="d-inline">
                                                @csrf
                                                @method("PATCH")
                                                <button type="submit" class="btn btn-success btn-sm">Verifikasi</button>
                                            </form>
                                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#exampleModalCenter">Tolak</button>
                                        @else
                                            <span class="text-success">Tidak ada aksi</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Layanan Bengkel</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Jenis Layanan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bengkel->layananBengkel as $layanan)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration() }}</th>
                                        <td>{{ $layanan->jenis_layanan }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada layanan untuk bengkel ini.</td>
                                    </tr>
                                @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Montir</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">No Telp</th>
                                    <th scope="col">Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bengkel->montirs as $montir)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration() }}</th>
                                        <td>{{ $montir->nama }}</td>
                                        <td>{{ $montir->no_telp }}</td>
                                        <td>{{ $montir->email }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada montir untuk bengkel ini.</td>
                                    </tr>
                                @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('bengkel.reject', $bengkel->id) }}" method="post">
                    @csrf
                    @method("PATCH")
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Apakah anda yakin ingin menolak permohonan ini?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="material-icons">close</i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="alasan_penolakan">Alasan Penolakan</label>
                            <textarea class="form-control" id="alasan_penolakan" rows="3" name="alasan_penolakan"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
