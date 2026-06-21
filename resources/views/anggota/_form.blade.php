@php $isEdit = isset($anggota); @endphp
<div class="row g-3">
    {{-- NIM --}}
    <div class="col-md-4">
        <label for="nim" class="form-label fw-semibold">NIM <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('nim') is-invalid @enderror" id="nim" name="nim"
               value="{{ old('nim', $isEdit ? $anggota->nim : '') }}" {{ $isEdit ? 'readonly' : '' }} required>
        @error('nim')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Nama Lengkap --}}
    <div class="col-md-8">
        <label for="nama_lengkap" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" id="nama_lengkap" name="nama_lengkap"
               value="{{ old('nama_lengkap', $isEdit ? $anggota->nama_lengkap : '') }}" required>
        @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Email --}}
    <div class="col-md-6">
        <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
               value="{{ old('email', $isEdit ? $anggota->email : '') }}" required>
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Tanggal Lahir --}}
    <div class="col-md-3">
        <label for="tanggal_lahir" class="form-label fw-semibold">Tanggal Lahir <span class="text-danger">*</span></label>
        <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir" name="tanggal_lahir"
               value="{{ old('tanggal_lahir', $isEdit ? $anggota->tanggal_lahir?->format('Y-m-d') : '') }}" required>
        @error('tanggal_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Jenis Kelamin --}}
    <div class="col-md-3">
        <label for="jenis_kelamin" class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
        <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin" required>
            <option value="">-- Pilih --</option>
            <option value="L" {{ old('jenis_kelamin', $isEdit ? $anggota->jenis_kelamin : '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
            <option value="P" {{ old('jenis_kelamin', $isEdit ? $anggota->jenis_kelamin : '') == 'P' ? 'selected' : '' }}>Perempuan</option>
        </select>
        @error('jenis_kelamin')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Tempat Lahir --}}
    <div class="col-md-4">
        <label for="tempat_lahir" class="form-label fw-semibold">Tempat Lahir</label>
        <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" id="tempat_lahir" name="tempat_lahir"
               value="{{ old('tempat_lahir', $isEdit ? $anggota->tempat_lahir : '') }}">
        @error('tempat_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- No HP --}}
    <div class="col-md-4">
        <label for="no_hp" class="form-label fw-semibold">No. HP</label>
        <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp"
               value="{{ old('no_hp', $isEdit ? $anggota->no_hp : '') }}" placeholder="08xxxxxxxxxx">
        @error('no_hp')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Program Studi --}}
    <div class="col-md-4">
        <label for="program_studi" class="form-label fw-semibold">Program Studi</label>
        <input type="text" class="form-control @error('program_studi') is-invalid @enderror" id="program_studi" name="program_studi"
               value="{{ old('program_studi', $isEdit ? $anggota->program_studi : '') }}">
        @error('program_studi')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Jurusan --}}
    <div class="col-md-6">
        <label for="jurusan" class="form-label fw-semibold">Jurusan</label>
        <input type="text" class="form-control @error('jurusan') is-invalid @enderror" id="jurusan" name="jurusan"
               value="{{ old('jurusan', $isEdit ? $anggota->jurusan : '') }}">
        @error('jurusan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Alamat --}}
    <div class="col-md-6">
        <label for="alamat" class="form-label fw-semibold">Alamat</label>
        <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="2">{{ old('alamat', $isEdit ? $anggota->alamat : '') }}</textarea>
        @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <hr class="my-2">

    {{-- Divisi --}}
    <div class="col-md-4">
        <label for="divisi" class="form-label fw-semibold">Divisi</label>
        <select class="form-select @error('divisi') is-invalid @enderror" id="divisi" name="divisi">
            <option value="">— Belum ditentukan —</option>
            @foreach(['fotografi'=>'Fotografi','pers_penyiaran'=>'Pers & Penyiaran','videografi'=>'Videografi','kominfo'=>'Kominfo','redaksi'=>'Redaksi','inventory'=>'Inventory'] as $v=>$l)
            <option value="{{ $v }}" {{ old('divisi', $isEdit ? $anggota->divisi : '') == $v ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
        </select>
        @error('divisi')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Jabatan --}}
    <div class="col-md-4">
        <label for="jabatan_struktural" class="form-label fw-semibold">Jabatan Struktural <span class="text-danger">*</span></label>
        <select class="form-select @error('jabatan_struktural') is-invalid @enderror" id="jabatan_struktural" name="jabatan_struktural" required>
            @php $jabatanList = [
                'ketua_umum'=>'Ketua Umum','wakil_ketua_umum'=>'Wakil Ketua Umum',
                'sekretaris_umum_1'=>'Sekretaris Umum 1','sekretaris_umum_2'=>'Sekretaris Umum 2',
                'bendahara_umum_1'=>'Bendahara Umum 1','bendahara_umum_2'=>'Bendahara Umum 2',
                'kadiv_fotografi'=>'Kadiv Fotografi','kadiv_pers_penyiaran'=>'Kadiv Pers & Penyiaran','kadiv_videografi'=>'Kadiv Videografi',
                'kanit_kominfo'=>'Kanit Kominfo','kanit_redaksi'=>'Kanit Redaksi','kanit_inventory'=>'Kanit Inventory',
                'staf'=>'Staf','anggota'=>'Anggota',
            ]; @endphp
            @foreach($jabatanList as $v=>$l)
            <option value="{{ $v }}" {{ old('jabatan_struktural', $isEdit ? $anggota->jabatan_struktural : 'anggota') == $v ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
        </select>
        @error('jabatan_struktural')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Status --}}
    <div class="col-md-4">
        <label for="status_keanggotaan" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
        <select class="form-select @error('status_keanggotaan') is-invalid @enderror" id="status_keanggotaan" name="status_keanggotaan" required>
            <option value="aktif" {{ old('status_keanggotaan', $isEdit ? $anggota->status_keanggotaan : 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="pasif" {{ old('status_keanggotaan', $isEdit ? $anggota->status_keanggotaan : '') == 'pasif' ? 'selected' : '' }}>Pasif</option>
            <option value="alumni" {{ old('status_keanggotaan', $isEdit ? $anggota->status_keanggotaan : '') == 'alumni' ? 'selected' : '' }}>Alumni</option>
        </select>
        @error('status_keanggotaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Tanggal Bergabung --}}
    <div class="col-md-4">
        <label for="tanggal_bergabung" class="form-label fw-semibold">Tanggal Bergabung</label>
        <input type="date" class="form-control @error('tanggal_bergabung') is-invalid @enderror" id="tanggal_bergabung" name="tanggal_bergabung"
               value="{{ old('tanggal_bergabung', $isEdit ? $anggota->tanggal_bergabung?->format('Y-m-d') : now()->format('Y-m-d')) }}">
        @error('tanggal_bergabung')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Foto Profil --}}
    <div class="col-md-4">
        <label for="foto_profil" class="form-label fw-semibold">Foto Profil</label>
        <input type="file" class="form-control @error('foto_profil') is-invalid @enderror" id="foto_profil" name="foto_profil" accept="image/jpeg,image/png">
        @error('foto_profil')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @if($isEdit && $anggota->foto_profil)
        <div class="mt-2"><img src="{{ asset('storage/' . $anggota->foto_profil) }}" class="rounded" style="height:60px" alt="Foto"></div>
        @endif
    </div>
</div>
