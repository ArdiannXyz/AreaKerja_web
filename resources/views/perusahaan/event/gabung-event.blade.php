@extends('layouts.index-perusahaan')
@section('content')
    <div class="bg-white text-gray-900 mt-20">
        <div class="max-w-3xl mx-auto p-6">

            {{-- header status & tombol --}}
            <div class="flex justify-end items-center space-x-4 mb-4">
                <span class="font-medium">Status</span>
                @if ($event->status == 'buka')
                    <span class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm">Buka</span>
                @elseif ($event->status == 'tutup')
                    <span class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm">Tutup</span>
                @else
                    <span class="bg-gray-500 text-white px-4 py-2 rounded text-sm">Draft</span>
                @endif
            </div>

            {{-- <div class="flex justify-end items-center mb-6">
                <button class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg text-sm">
                    Lihat Partisipan
                </button>
            </div> --}}

            <!-- Tanggal -->
            <div class="text-sm font-medium text-gray-800 mb-2">
                {{ \Carbon\Carbon::parse($event->tgl_mulai)->translatedFormat('d F Y') }}
            </div>

            <!-- Gambar -->
            <img src="{{ !empty($event->image) ? asset('storage/' . $event->image) : asset('images/woi.jpg') }}"
                alt="{{ $event->title ?? 'Event' }}" class="w-full rounded-xl mb-4 object-cover max-h-[300px]">

            <!-- Judul & Deskripsi -->
            <h2 class="text-lg font-bold mb-2 break-words">{{ $event->title }}</h2>

            <div class="tinymce-content text-sm text-gray-700 mb-4 break-words">
                {!! $event->content !!}
            </div>

            <hr class="my-4">

            <!-- Detail acara -->
            <h3 class="text-base font-semibold text-orange-600 mb-3">Detail Acara</h3>

            <div class="flex flex-col sm:flex-row sm:flex-wrap gap-6 mb-6">

                <!-- Waktu -->
                <div class="flex flex-col space-y-4 w-full sm:w-auto">

                    <div class="flex items-start">
                        <span class="mr-4 mt-[2px]">🕒</span>
                        <div class="break-words">
                            <p class="font-semibold">Waktu :</p>
                            <p class="break-words">
                                {{ \Carbon\Carbon::parse($event->tgl_mulai)->translatedFormat('d F Y') }}
                                ({{ $event->jam_mulai }} - {{ $event->jam_akhir }}) WIB
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <span class="mr-6 mt-[2px]">📍</span>
                        <div class="break-words">
                            <p class="font-semibold">Lokasi :</p>
                            <p class="break-words">
                                {{ $event->lokasi ?? 'Belum ditentukan' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <span class="mr-3 mt-[2px]"><i class="ph ph-link text-2xl"></i></span>
                        <div class="break-words">
                            <p class="font-semibold">Link Form :</p>
                            @if ($event->link_form)
                                <a href="{{ $event->link_form }}" target="_blank"
                                    class="text-blue-600 underline hover:text-blue-800 break-all">
                                    {{ $event->link_form }}
                                </a>
                            @else
                                <p>Belum ditentukan</p>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            <!-- Daftar kegiatan -->
            <h3 class="text-base font-semibold mb-2">Daftar Kegiatan:</h3>

            @if ($event->kegiatan->count() > 0)
                <div class="border-2 border-orange-500 rounded-xl overflow-x-auto">
                    <table class="w-full border-orange-500 text-sm min-w-[400px]">
                        <thead>
                            <tr>
                                <th class="border border-orange-500 px-4 py-2 w-[20%] text-center">Waktu</th>
                                <th class="border border-orange-500 px-4 py-2 text-center">Acara</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($event->kegiatan as $kegiatan)
                                <tr>
                                    <td class="border border-orange-500 px-4 py-2 text-center break-words">
                                        {{ $kegiatan->waktu }}
                                    </td>
                                    <td class="border border-orange-500 px-4 py-2 text-center break-words">
                                        {{ $kegiatan->kegiatan }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 italic">Belum ada daftar kegiatan.</p>
            @endif

        </div>

        @include('layouts.footer')
    </div>
@endsection
