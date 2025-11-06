@extends('layouts.main')

@section('content')
    <h1>Tampilan Siswa</h1>

    <div class="card">
        <div class="card-header">
            <div class="card-tools">
                <a class="btn btn-success" href="{{ route('siswa.create') }}">Tambah Data</a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="row">
                <div class="col"> </div>
                <div class="col"> </div>
                <div class="col mb-3">
                    <form action="{{ route('siswa.index') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit"> Cari</button>
                            <a href="{{ route('siswa.index') }}" class="btn btn-success">reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="bg-primary">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>E-Mail</th>
                            <th>Kelas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswas as $siswa)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $siswa->nis }}</td>
                                <td>{{ $siswa->user->name }}</td>
                                <td>{{ $siswa->user->email }}</td>
                                <td>{{ $siswa->kelas }}</td>
                                <td>
                                    <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus siswa ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">Lom Ada</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $siswas->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
