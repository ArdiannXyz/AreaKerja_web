@extends('admin.sidebar.index')
@section('sidebaradmin')
    <div class="p-4 sm:ml-64" x-data="{
        openModal: false,
        openNotif: false,
        openAllNotif: false
    }">
        <div class="flex-1 p-6 bg-white overflow-y-auto">
            <div class="flex justify-between items-center mb-6 flex-wrap gap-4">

                <h1 class="text-2xl font-medium whitespace-nowrap">
                    Event
                </h1>

                <div class="flex items-center gap-3 flex-wrap justify-end w-full sm:w-auto">

                    {{-- Tombol Notifikasi --}}
                    <button @click="openNotif = true" class="relative shrink-0">
                        <!-- Icon Lonceng -->
                        <svg width="31" height="32" viewBox="0 0 31 32" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_722_7956)">
                                <path
                                    d="M23.076 14.9431L22.6747 12.7383L21.1101 13.0055L21.5756 15.5633C21.6168 15.7894 21.7387 15.9922 21.9146 16.127L24.4524 18.0732L24.6985 19.4255L7.4876 22.3654L7.24147 21.0131L8.93911 18.3434C9.05673 18.1585 9.09972 17.9276 9.05861 17.7015L8.43786 14.2911C8.21777 13.0934 8.29153 11.8668 8.65169 10.7352C9.01186 9.60353 9.64569 8.60691 10.4892 7.84595C11.3326 7.08499 12.3559 6.58665 13.4555 6.40126C14.5552 6.21586 15.6924 6.34997 16.7522 6.79004L16.4051 4.88278C15.595 4.65063 14.7612 4.55689 13.9346 4.605L13.6165 2.85717L12.0518 3.12444L12.37 4.87227C10.4802 5.41568 8.87215 6.70676 7.85685 8.49588C6.84155 10.285 6.49109 12.445 6.87324 14.5583L7.42973 17.6158L5.7321 20.2855C5.61447 20.4704 5.57149 20.7013 5.6126 20.9274L6.07815 23.4852C6.11931 23.7114 6.24121 23.9141 6.41702 24.049C6.59284 24.1838 6.80817 24.2396 7.01565 24.2042L12.4919 23.2688L12.647 24.1214C12.8528 25.252 13.4623 26.2659 14.3414 26.9401C15.2205 27.6142 16.2971 27.8934 17.3345 27.7162C18.3719 27.539 19.2851 26.9199 19.8732 25.9951C20.4612 25.0704 20.676 23.9157 20.4702 22.785L20.315 21.9324L25.7912 20.997C25.9987 20.9616 26.1813 20.8378 26.2989 20.6528C26.4165 20.4679 26.4595 20.2369 26.4183 20.0108L25.9528 17.453C25.9116 17.2269 25.7896 17.0241 25.6138 16.8894L23.076 14.9431ZM18.9055 23.0523C19.029 23.7307 18.9002 24.4235 18.5473 24.9784C18.1945 25.5332 17.6466 25.9047 17.0242 26.011C16.4017 26.1173 15.7557 25.9498 15.2283 25.5453C14.7008 25.1408 14.3351 24.5325 14.2117 23.8541L14.0565 23.0015L18.7504 22.1997L18.9055 23.0523Z"
                                    fill="black" />
                            </g>
                        </svg>

                        @if ($global_notifikasi_unread > 0)
                            <span id="notif-badge"
                                class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                                {{ $global_notifikasi_unread }}
                            </span>
                        @endif
                    </button>

                    {{-- Profil --}}
                    <div
                        class="flex items-center bg-white border border-orange-500 shadow-md rounded-2xl px-3 py-2 w-full sm:w-72 md:w-80 lg:w-90">

                        <div class="flex items-center gap-3 min-w-0 w-full">
                            <a href="#" class="shrink-0">
                                @if (Auth::user()->role == 'admin')
                                    @if (Auth::user()->admin->img_profile)
                                        <img class="w-10 h-10 object-cover rounded-full"
                                            src="{{ asset('storage/' . Auth::user()->admin->img_profile) }}" alt="Profile">
                                    @else
                                        <img class="w-10 h-10 rounded-full"
                                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128">
                                    @endif
                                @else
                                    <img class="w-10 h-10 rounded-full"
                                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128">
                                @endif
                            </a>

                            <div class="text-sm min-w-0 w-full">
                                <span class="font-semibold block truncate">
                                    {{ Auth::user()->username }}
                                </span>

                                <p class="text-gray-500 text-xs truncate">
                                    {{ Auth::user()->email }}
                                </p>
                            </div>
                        </div>

                    </div>

                </div>
            </div>


            <div class="pl-3 mt-5 px-4 sm:px-6 lg:px-8 w-full overflow-x-hidden break-words">

                {{-- header status & tombol --}}
                <div
                    class="flex flex-col sm:flex-row justify-end items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 mb-4">
                    <span class="font-medium">Status</span>

                    @if ($event->status == 'buka')
                        <span class="bg-green-500 text-white px-4 py-2 rounded-lg text-sm">Buka</span>
                    @elseif ($event->status == 'tutup')
                        <span class="bg-red-500 text-white px-4 py-2 rounded text-sm">Tutup</span>
                    @else
                        <span class="bg-gray-500 text-white px-4 py-2 rounded text-sm">Draft</span>
                    @endif

                    <form action="{{ route('admin.event.destroy', $event->id) }}" method="post">
                        @csrf
                        @method('delete')
                        <button
                            class="bg-red-500 hover:bg-red-600 text-white px-14 py-2 rounded-lg text-sm w-full sm:w-auto">
                            Hapus
                        </button>
                    </form>
                </div>

                <div class="flex flex-col sm:flex-row justify-end items-start sm:items-center gap-3 mb-6">
                    <a href="{{ route('admin.edit.event', $event->id) }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-12 py-2 rounded-lg text-sm w-full sm:w-auto text-center">
                        Edit Event
                    </a>

                    {{-- <a href="#"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg text-sm w-full sm:w-auto text-center">
                        Lihat Partisipan
                    </a> --}}
                </div>

                {{-- tanggal --}}
                <p class="mb-2 font-semibold">
                    {{ \Carbon\Carbon::parse($event->tgl_mulai)->format('d M Y') }}
                </p>

                {{-- gambar --}}
                @if (!empty($event->image))
                    <img src="{{ asset('storage/' . $event->image) }}" alt="event image"
                        class="rounded-2xl mb-6 w-full max-w-full h-auto object-cover">
                @else
                    <img src="{{ asset('images/woi.jpg') }}" alt="event image"
                        class="rounded-2xl mb-6 w-full max-w-full h-auto object-cover">
                @endif

                {{-- judul --}}
                <h2 class="font-semibold text-lg mb-2 break-words">
                    {{ $event->title }}
                </h2>

                {{-- deskripsi --}}
                <div class="tinymce-content break-words">
                    {!! $event->content !!}
                </div>

                {{-- detail acara --}}
                <h3 class="font-semibold text-orange-500 mt-6 mb-2">Detail Acara</h3>

                <div class="space-y-3">

                    {{-- waktu --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center space-x-0 sm:space-x-2 break-all">
                        {{-- <svg width="22" height="22" ...></svg> --}}
                        <p>Waktu:
                            {{ \Carbon\Carbon::parse($event->tgl_mulai)->format('d M Y') }}
                            ({{ $event->jam_mulai }} - {{ $event->jam_akhir }}) WIB
                        </p>
                    </div>

                    {{-- lokasi --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center space-x-0 sm:space-x-2 break-all">
                        {{-- <svg width="18" height="22" ...></svg> --}}
                        <p>Lokasi: {{ $event->lokasi ?? '-' }}</p>
                    </div>

                    {{-- link --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center space-x-0 sm:space-x-2 break-all">
                        {{-- <i class="ph ph-link text-2xl"></i> --}}

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

                {{-- daftar kegiatan --}}
                <h3 class="text-base font-semibold mt-3 sm:mt-2 mb-2">
                    Daftar kegiatan :
                </h3>


                <div class="rounded-xl border-2 border-orange-500 overflow-x-auto">
                    <table class="w-full text-sm border-collapse min-w-[360px]">
                        <thead>
                            <tr>
                                <th class="border border-orange-500 px-4 py-2 w-[20%] text-center">
                                    Waktu
                                </th>
                                <th class="border border-orange-500 px-4 py-2 text-center">
                                    Acara
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($event->kegiatan as $k)
                                <tr>
                                    <td class="border border-orange-500 px-4 py-2 text-center">
                                        {{ $k->waktu }}
                                    </td>
                                    <td class="border border-orange-500 px-4 py-2 text-center break-words">
                                        {{ $k->kegiatan }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center py-3">
                                        Belum ada kegiatan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>


            </div>


        </div>
        @include('admin.notif.modal_notif')
        @include('admin.notif.modal_semua')
    </div>
@endsection
