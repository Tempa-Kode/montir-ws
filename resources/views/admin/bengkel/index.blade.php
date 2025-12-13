@extends("layout.template")
@section("title", "Data Bengkel")
@section("breadcrumb")
    <div class="page-info">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Data Bengkel</li>
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
                        <h5 class="card-title">Data Bengkel</h5>
                        <a href="{{ route('laporan.bengkel') }}" class="btn btn-primary mb-3">
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
                                    <th>Nama Bengkel</th>
                                    <th>Pemilik</th>
                                    <th>Alamat</th>
                                    <th>Verifikasi</th>
                                    <th>Rating</th>
                                    <th>Tanggal Bergabung</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bengkel as $b)
                                    <tr>
                                        <td>{{ $b->nama }}</td>
                                        <td>{{ $b->user->nama }}</td>
                                        <td>{{ $b->alamat }}</td>
                                        <td>
                                            @if ($b->verifikasi)
                                                <span class="badge badge-success">Terverifikasi</span>
                                            @else
                                                <span class="badge badge-warning">Belum Terverifikasi</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($b->ulasan_ratings_avg_rating)
                                                {{ number_format($b->ulasan_ratings_avg_rating, 1) }} / 5.0
                                            @else
                                                <span class="text-muted">Belum ada rating</span>
                                            @endif
                                        </td>
                                        <td>{{ $b->created_at->format("d M Y") }}</td>
                                        <td>
                                            <a href="{{ route("bengkel.show", $b->id) }}"
                                                class="btn btn-info btn-sm">Detail</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Nama Bengkel</th>
                                    <th>Pemilik</th>
                                    <th>Alamat</th>
                                    <th>Verifikasi</th>
                                    <th>Rating</th>
                                    <th>Tanggal Bergabung</th>
                                    <th>Aksi</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
