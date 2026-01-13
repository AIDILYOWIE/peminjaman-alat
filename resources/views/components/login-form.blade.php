<div>
    <form action="{{ route('login') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="role" x-model="role">

        <!-- Dynamic Field (NISN or NIP) -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <span x-show="role === 'siswa'">NISN</span>
                <span x-show="role === 'guru'" x-cloak>NIP</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <x-heroicon-o-identification class="h-5 w-5 text-gray-400" />
                </div>
                <input
                    type="text"
                    :name="role === 'siswa' ? 'nisn' : 'nip'"
                    :id="role === 'siswa' ? 'nisn' : 'nip'"
                    :placeholder="role === 'siswa' ? 'Masukkan NISN' : 'Masukkan NIP'"
                    required
                    class="block w-full pl-12 pr-4 py-3.5 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all bg-gray-50 hover:bg-white">
            </div>
        </div>

        <!-- Password Field -->
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                Password
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex                                                                                                                     items-center pointer-events-none">
                    <x-heroicon-o-lock-closed class="h-5 w-5 text-gray-400" />
                </div>
                <input
                    type="password"
                    name="password"
                    id="password"
                    placeholder="Masukkan Password"
                    required
                    class="block w-full pl-12 pr-4 py-3.5 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all bg-gray-50 hover:bg-white">
            </div>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input
                    type="checkbox"
                    name="remember"
                    id="remember"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded transition-colors">
                <label for="remember" class="ml-2 block text-sm text-gray-700">
                    Ingat saya
                </label>
            </div>
            <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors">
                Lupa password?
            </a>
        </div>

        <!-- Submit Button -->
        <button
            type="submit"
            class="w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-indigo-200 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:scale-[1.02] active:scale-[0.98]">
            <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5" />
            Masuk
        </button>
    </form>
</div>