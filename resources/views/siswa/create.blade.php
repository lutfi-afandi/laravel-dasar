@extends('layouts.main')

@section('content')
    <h1>Tampilan Siswa</h1>

    <div class="card">
        <div class="card-header">
            <div class="card-tools">
                <a class="btn btn-warning" href="{{ route('siswa.index') }}"> Kembali</a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('siswa.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="">Nama</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                        value="{{ old('name') }}">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="">Email</label>
                    <input type="text" class="form-control @error('email') is-invalid @enderror" name="email"
                        value="{{ old('email') }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="">NIS</label>
                    <input type="text" class="form-control @error('nis') is-invalid @enderror" name="nis"
                        value="{{ old('nis') }}">
                    @error('nis')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    {{-- ['10A', '10B', '11A', '11B', '12A', '12B'] --}}
                    <label class="form-label" for="">Kelas</label>
                    <select name="kelas" class="form-control @error('kelas') is-invalid @enderror" id="">
                        <option hidden>Pilih</option>
                        <option {{ old('kelas') == '10A' ? 'selected' : '' }}>10A</option>
                        <option {{ old('kelas') == '10B' ? 'selected' : '' }}>10B</option>
                        <option {{ old('kelas') == '11A' ? 'selected' : '' }}>11A</option>
                        <option {{ old('kelas') == '11B' ? 'selected' : '' }}>11B</option>
                        <option {{ old('kelas') == '12A' ? 'selected' : '' }}>12A</option>
                        <option {{ old('kelas') == '12B' ? 'selected' : '' }}>12B</option>
                    </select>
                    @error('kelas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="">Foto</label>
                    <input type="file" class="form-control @error('foto') is-invalid @enderror" name="foto">
                </div>
                @error('foto')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <button class="btn btn-primary mt-3">Simpan</button>
            </form>
        </div>
    </div>
@endsection
