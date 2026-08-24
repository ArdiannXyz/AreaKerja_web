@extends('admin.sidebar.index')
@section('sidebaradmin')
    <div class="p-4 sm:ml-64">
        <div class="mx-auto p-6 max-w-3xl w-full">
            <h1 class="text-2xl font-bold mb-6 text-gray-800">Edit Tips Kerja</h1>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/></svg>
                        <span class="font-bold">Mohon perbaiki kesalahan berikut:</span>
                    </div>
                    <ul class="list-disc list-inside text-sm pl-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.tips-kerja.update', $tips->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Judul -->
                <div>
                    <label class="block mb-2 text-lg font-medium break-words">Judul Artikel <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $tips->title) }}" required placeholder="Tulis judul artikel..."
                        class="w-full border-2 @error('title') border-red-500 @else border-gray-400 @enderror rounded-lg px-3 py-2 text-base break-words">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cover -->
                <div>
                    <label class="block mb-2 text-lg font-medium break-words">Cover File / Image <span class="text-xs text-gray-500 font-normal">(Opsional)</span></label>
                    @if ($tips->image)
                        <div class="mb-2">
                            <span class="text-xs text-gray-600 font-semibold">File Saat Ini:</span>
                            <a href="{{ asset('storage/' . $tips->image) }}" target="_blank" class="text-blue-600 hover:underline text-xs ml-1">{{ $tips->image }}</a>
                        </div>
                    @endif
                    <input type="file" name="image" accept=".png,.jpg,.jpeg,.webp,.pdf"
                        class="w-full border-2 @error('image') border-red-500 @else border-gray-400 @enderror rounded-lg px-3 py-2 text-base">
                    @error('image')
                        <p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block mb-2 text-lg font-medium break-words">Status Publikasi</label>
                    <select name="status" class="w-full border-2 border-gray-400 rounded-lg px-3 py-2 text-base">
                        <option value="terbit" {{ old('status', $tips->status) == 'terbit' ? 'selected' : '' }}>Terbit</option>
                        <option value="belum terbit" {{ old('status', $tips->status) == 'belum terbit' ? 'selected' : '' }}>Draf (Belum Terbit)</option>
                    </select>
                </div>

                <!-- Content -->
                <div>
                    <label class="block mb-2 text-lg font-medium break-words">Isi Artikel <span class="text-red-500">*</span></label>
                    <textarea id="editor" name="content" class="w-full h-48 border @error('content') border-red-500 @else border-gray-400 @enderror rounded-lg break-words">{{ old('content', $tips->content) }}</textarea>
                    @error('content')
                        <p class="text-red-500 text-sm mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- TinyMCE -->
                <script src="https://cdn.tiny.cloud/1/oqx873eo8a4800gwchmdyn357lbg0rvj9bxkryttzmw9uf7q/tinymce/8/tinymce.min.js"
                    referrerpolicy="origin"></script>

                <script>
                    tinymce.init({
                        selector: '#editor',
                        height: 500,
                        menubar: false,
                        plugins: 'lists link image media code fullscreen mentions',
                        toolbar: 'undo redo | bold italic underline | bullist numlist | link image media | code fullscreen',
                    });
                </script>

                <!-- Button -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg shadow w-full sm:w-auto font-semibold">
                        Simpan Perubahan
                    </button>

                    <a href="{{ route('admin.tips-kerja') }}" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg shadow w-full sm:w-auto text-center font-semibold">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
