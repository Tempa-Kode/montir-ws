@extends("layout.template")

@section("title", "Dashboard")

@section("breadcrumb")
    <div class="page-info">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </div>
@endsection

@section("main")
    <div>
        <h1>Welcome, {{ Auth::user()->nama }}</h1>
        <p>Here's a summary of your platform's performance today:</p>

        <div class="row stats-row">
            <div class="col-lg-4 col-md-12">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-icon change-success">
                            <i class="material-icons">text_snippet</i>
                        </div>
                        <div class="stats-info">
                            <h5 class="card-title">{{ number_format($activeRequests) }}</h5>
                            <p class="stats-text">Active Request</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-info">
                            <h5 class="card-title">{{ number_format($registeredBengkel) }}</h5>
                            <p class="stats-text">Registered Bengkel</p>
                        </div>
                        <div class="stats-icon change-success">
                            <i class="material-icons">person_add</i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="stats-info">
                            <h5 class="card-title">Rp {{ number_format($totalEarning, 0, ",", ".") }}</h5>
                            <p class="stats-text">Total Earning</p>
                        </div>
                        <div class="stats-icon change-success">
                            <i class="material-icons">attach_money</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Service Requests --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Recent Service Requests</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Pelanggan</th>
                                        <th>Bengkel</th>
                                        <th>Layanan</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentRequests as $request)
                                        <tr>
                                            <td>#{{ $request->id }}</td>
                                            <td>{{ $request->pelanggan->nama }}</td>
                                            <td>{{ $request->layananBengkel->bengkel->nama }}</td>
                                            <td>{{ $request->layananBengkel->jenis_layanan }}</td>
                                            <td>
                                                @if ($request->status == "pending")
                                                    <span class="badge badge-warning">Pending</span>
                                                @elseif($request->status == "kelokasi")
                                                    <span class="badge badge-info">Menuju Lokasi</span>
                                                @elseif($request->status == "kerjakan")
                                                    <span class="badge badge-primary">Dikerjakan</span>
                                                @elseif($request->status == "selesai")
                                                    <span class="badge badge-success">Selesai</span>
                                                @endif
                                            </td>
                                            <td>{{ $request->created_at->format("d M Y, H:i") }}</td>
                                            <td>
                                                <a href="{{ route("transaksi.show", $request->id) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="material-icons" style="font-size: 16px;">visibility</i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Belum ada service request</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="text-right">
                            <a href="{{ route("transaksi.index") }}" class="btn btn-outline-primary btn-sm">
                                Lihat Semua <i class="material-icons" style="font-size: 16px;">arrow_forward</i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Registered Bengkel --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Recent Registered Bengkel</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama Bengkel</th>
                                        <th>Pemilik</th>
                                        <th>Alamat</th>
                                        <th>Status</th>
                                        <th>Tanggal Bergabung</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentBengkel as $bengkel)
                                        <tr>
                                            <td>{{ $bengkel->nama }}</td>
                                            <td>{{ $bengkel->user->nama }}</td>
                                            <td>{{ Str::limit($bengkel->alamat, 40) }}</td>
                                            <td>
                                                @if ($bengkel->verifikasi)
                                                    <span class="badge badge-success">Terverifikasi</span>
                                                @else
                                                    <span class="badge badge-danger">Ditolak</span>
                                                @endif
                                            </td>
                                            <td>{{ $bengkel->created_at->format("d M Y") }}</td>
                                            <td>
                                                <a href="{{ route("bengkel.show", $bengkel->id) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="material-icons" style="font-size: 16px;">visibility</i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Belum ada bengkel terdaftar
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="text-right">
                            <a href="{{ route("bengkel.index") }}" class="btn btn-outline-primary btn-sm">
                                Lihat Semua <i class="material-icons" style="font-size: 16px;">arrow_forward</i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
