<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                {{-- Page title --}}
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Change Password</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">SandLab</a></li>
                            <li class="breadcrumb-item active">Change Password</li>
                        </ol>
                    </div>
                </div>

                {{-- Card putih --}}
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Form Change Password</h5>

                        {{-- Alert sukses --}}
                        @if (session('success'))
                            <div class="alert alert-success mb-3">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form wire:submit.prevent="save" autocomplete="off">
                            {{-- Current Password --}}
                            <div class="form-group">
                                <label for="current_password" class="mb-1">Password Saat Ini</label>
                                <input type="password"
                                       id="current_password"
                                       class="form-control @error('current_password') is-invalid @enderror"
                                       wire:model.defer="current_password"
                                       placeholder="Masukkan password saat ini">
                                @error('current_password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- New Password --}}
                            <div class="form-group">
                                <label for="password" class="mb-1">Password Baru</label>
                                <input type="password"
                                       id="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       wire:model.defer="password"
                                       placeholder="Minimal 8 karakter">
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Confirm --}}
                            <div class="form-group">
                                <label for="password_confirmation" class="mb-1">Konfirmasi Password Baru</label>
                                <input type="password"
                                       id="password_confirmation"
                                       class="form-control @error('password_confirmation') is-invalid @enderror"
                                       wire:model.defer="password_confirmation"
                                       placeholder="Ulangi password baru">
                                @error('password_confirmation')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Logout other devices --}}
                            <div class="form-group form-check mb-4">
                                <input type="checkbox"
                                       class="form-check-input"
                                       id="logout_others"
                                       wire:model="logout_others">
                                <label class="form-check-label" for="logout_others">
                                    Logout dari device lain setelah ganti password
                                </label>
                            </div>

                            {{-- Tombol aksi --}}
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary"><i class="ri-close-line"></i>
                                    Cancel
                                </a>
                                <button type="submit"
                                        class="btn btn-success ml-2"
                                        wire:loading.attr="disabled">
                                    <span wire:loading.remove><i class="ri-checkbox-circle-line"></i>Submit</span>
                                    <span wire:loading>Saving...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
