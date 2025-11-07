@extends("layout.template")
@section("title", "Data Pelanggan")
@section("breadcrumb")
    <div class="page-info">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route("dashboard") }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Data Pelanggan</li>
            </ol>
        </nav>
    </div>
@endsection
@section("main")
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Pelanggan</h5>
                    <div class="table-responsive">
                        <table id="zero-conf" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>No Telp</th>
                                    <th>Email</th>
                                    <th>Tanggal Bergabung</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pelanggan as $p)
                                    <tr>
                                        <td>{{ $p->nama }}</td>
                                        <td>{{ $p->no_telp }}</td>
                                        <td>{{ $p->email }}</td>
                                        <td>{{ $p->created_at->format('d M Y') }}</td>
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
