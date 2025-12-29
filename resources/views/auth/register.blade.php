@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl w-full grid md:grid-cols-2 gap-8 items-center">
        <!-- Left Side - Registration Form -->
        <div class="w-full order-2 md:order-1">
            <div class="bg-white p-8 sm:p-12 rounded-2xl shadow-2xl transform transition-all duration-300 hover:shadow-3xl">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-[#F53003] to-[#ff6b3d] rounded-full mb-4 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Daftar</h2>
                    <p class="text-gray-600">Buat akun baru dan mulai petualangan Anda</p>
                </div>

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg animate-shake">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="flex-1">
                                @foreach($errors->all() as $error)
                                    <p class="text-sm text-red-700">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="/register" class="space-y-5">
                    @csrf

                    <div class="relative">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input 
                                name="name" 
                                type="text"
                                value="{{ old('name') }}" 
                                class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition duration-200" 
                                placeholder="Masukkan nama lengkap"
                                required
                            />
                        </div>
                    </div>

                    <div class="relative">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                            <input 
                                name="email" 
                                type="email"
                                value="{{ old('email') }}" 
                                class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition duration-200" 
                                placeholder="nama@email.com"
                                required
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="relative">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input 
                                    name="password" 
                                    type="password" 
                                    class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition duration-200" 
                                    placeholder="Min. 8 karakter"
                                    required
                                />
                            </div>
                        </div>

                        <div class="relative">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <input 
                                    name="password_confirmation" 
                                    type="password" 
                                    class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent transition duration-200" 
                                    placeholder="Ulangi password"
                                    required
                                />
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Daftar Sebagai</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#F53003] transition duration-200 {{ old('role') == 'customer' || !old('role') ? 'border-[#F53003] bg-red-50' : '' }}">
                                <input type="radio" name="role" value="customer" class="sr-only" {{ old('role') == 'customer' || !old('role') ? 'checked' : '' }} required>
                                <div class="flex flex-col items-center w-full">
                                    <svg class="w-8 h-8 text-[#F53003] mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-800">Customer</span>
                                    <span class="text-xs text-gray-500 text-center mt-1">Pembeli Tiket</span>
                                </div>
                            </label>
                            <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#F53003] transition duration-200 {{ old('role') == 'organizer' ? 'border-[#F53003] bg-red-50' : '' }}">
                                <input type="radio" name="role" value="organizer" class="sr-only" {{ old('role') == 'organizer' ? 'checked' : '' }}>
                                <div class="flex flex-col items-center w-full">
                                    <svg class="w-8 h-8 text-[#F53003] mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-800">Organizer</span>
                                    <span class="text-xs text-gray-500 text-center mt-1">Penyelenggara</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Optional Profile Fields -->
                    <div class="border-t pt-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Informasi Tambahan (Opsional)</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Nomor Telepon</label>
                                <input name="phone" value="{{ old('phone') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent" placeholder="+62" />
                            </div>

                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Website</label>
                                <input name="website" value="{{ old('website') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent" placeholder="https://example.com" />
                            </div>

                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Alamat</label>
                                <input name="address" value="{{ old('address') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent" />
                            </div>

                            <div id="company_field" style="display: none;">
                                <label class="block text-sm text-gray-600 mb-1">Nama Perusahaan</label>
                                <input name="company_name" value="{{ old('company_name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent" />
                            </div>

                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Bio Singkat</label>
                                <textarea name="bio" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F53003] focus:border-transparent" rows="3">{{ old('bio') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-[#F53003] to-[#ff6b3d] text-white rounded-lg font-semibold hover:from-[#d42902] hover:to-[#F53003] transform hover:scale-[1.02] transition duration-200 shadow-lg hover:shadow-xl">
                        Daftar Sekarang
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <div class="relative mb-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500">Atau</span>
                        </div>
                    </div>
                    
                    <p class="text-sm text-gray-600">
                        Sudah punya akun? 
                        <a href="/login" class="text-[#F53003] font-semibold hover:text-[#d42902] transition duration-200">Login disini</a>
                    </p>
                </div>
            </div>

            <div class="mt-6 text-center text-sm text-gray-500">
                <p>&copy; 2025 Sistem Pemesanan Tiket Event Online</p>
            </div>
        </div>

        <!-- Right Side - Illustration -->
        <div class="hidden md:block order-1 md:order-2">
            <div class="bg-gradient-to-br from-[#F53003] to-[#ff6b3d] p-12 rounded-2xl shadow-2xl">
                <div class="text-white">
                    <div class="mb-8">
                        <svg class="w-20 h-20 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <h2 class="text-4xl font-bold mb-4">Bergabunglah!</h2>
                        <p class="text-lg text-white/90">Daftar sekarang dan nikmati berbagai keuntungan menjadi member kami</p>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Akses ke ribuan event menarik</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Notifikasi event terbaru</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Penawaran spesial & diskon</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Riwayat pembelian tersimpan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

.animate-shake {
    animation: shake 0.5s;
}

/* Radio button styling */
input[type="radio"]:checked + div svg {
    color: #F53003;
}

input[type="radio"]:checked + div span {
    color: #F53003;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleInputs = document.querySelectorAll('input[name="role"]');
    const companyField = document.getElementById('company_field');
    
    function toggleCompanyField() {
        const selectedRole = document.querySelector('input[name="role"]:checked')?.value;
        if (companyField) {
            companyField.style.display = selectedRole === 'organizer' ? 'block' : 'none';
        }
    }
    
    roleInputs.forEach(input => {
        input.addEventListener('change', toggleCompanyField);
    });
    
    toggleCompanyField();
});
</script>
@endsection
