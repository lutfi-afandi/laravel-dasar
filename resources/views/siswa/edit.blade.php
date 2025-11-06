@extends('layouts.main')

@section('content')
    <div class="container">
        <h4>Edit Siswa</h4>

        @if ($errors->has('error'))
            <div class="alert alert-danger">{{ $errors->first('error') }}</div>
        @endif

        <div class="card">
            <div class="card-header">
                <div class="card-tools">
                    <a class="btn btn-warning" href="{{ route('siswa.index') }}"> Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('siswa.update', $siswa->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Nama --}}
                    <div class="form-group mb-3">
                        <label>Nama</label>
                        <input type="text" name="name" value="{{ old('name', $siswa->user->name) }}"
                            class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Email (readonly) --}}
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="text" name="email" class="form-control" value="{{ $siswa->user->email }}"
                            readonly>
                    </div>

                    {{-- NIS --}}
                    <div class="form-group mb-3">
                        <label>NIS</label>
                        <input type="text" name="nis" value="{{ old('nis', $siswa->nis) }}"
                            class="form-control @error('nis') is-invalid @enderror">
                        @error('nis')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Kelas --}}
                    <div class="form-group mb-3">
                        <label>Kelas</label>
                        <select name="kelas" class="form-control @error('kelas') is-invalid @enderror">
                            <option hidden>Pilih</option>
                            @foreach (['10A', '10B', '11A', '11B', '12A', '12B'] as $kelas)
                                <option value="{{ $kelas }}"
                                    {{ old('kelas', $siswa->kelas) == $kelas ? 'selected' : '' }}>
                                    {{ $kelas }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Foto --}}
                    <div class="form-group mb-3">
                        <label>Foto</label>
                        <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror">
                        @error('foto')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        @if ($siswa->foto)
                            <img src="{{ asset('storage/' . $siswa->foto) }}" width="80" class="mt-2 rounded">
                        @endif
                    </div>

                    <button class="btn btn-primary">Update</button>
                    <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>

    </div>
@endsection
