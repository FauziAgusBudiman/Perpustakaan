<x-guest-layout title="Login">
    <div class="d-flex flex-column justify-content-center align-items-center vh-100 bg-light">

        <!-- Kartu Login -->
        <div class="card shadow-lg p-4" style="max-width: 720px; width: 100%; border-radius: 20px;">

            <!-- Logo -->
          <div class="mb-3">
                <img src="/assets/images/Logo.png" alt="Logo"
                    style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
            </div>
            <!-- Judul -->
            <div class="text-center mb-4">
                <h2 class="fw-bold text-primary mb-1">Perpustakaan MTs Tanwiriyyah</h2>
                <p class="text-muted mb-0">Silakan login untuk mengakses sistem</p>
            </div>

            <!-- Form Login -->
            <form action="{{ route('login') }}" method="POST" class="d-flex flex-column gap-3">
                @csrf
                @method('POST')

                <!-- Nomor Anggota -->
                <div>
                    <label for="number" class="form-label fw-semibold">Nomer Pengguna (NIS,NIP)</label>
                    <input type="number" name="number" id="number" class="form-control form-control-lg"
                        placeholder="Masukkan nomor anggota" value="{{ old('number') }}" required>
                    @error('number')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="form-label fw-semibold">Kata Sandi</label>
                    <input type="password" name="password" id="password" class="form-control form-control-lg"
                        placeholder="Masukkan password" required>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Ingat saya
                    </label>
                </div>

                <!-- Tombol Login -->
                <div class="d-grid mt-3">
                    <button type="submit" class="btn btn-primary py-2 fs-5 fw-semibold shadow-sm">
                        Login
                    </button>
                </div>

                <!-- Link Register -->
                <div class="text-center mt-3">
                    <span class="text-muted">Belum punya akun?
                        <a href="{{ route('register') }}" class="text-decoration-none text-primary fw-semibold">
                            Daftar di sini
                        </a>
                    </span>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="mt-4 text-muted small text-center">
            &copy; {{ date('Y') }} Perpustakaan MTs Tanwiriyyah | Sistem Informasi Berbasis Web
        </div>
    </div>
</x-guest-layout>
