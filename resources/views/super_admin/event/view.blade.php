@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-white overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">

            <h1 class="text-2xl font-medium break-words">Event</h1>

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full sm:w-auto">

                {{-- Tombol Notifikasi --}}
                <button @click="openNotif = true" class="relative">
                    <svg width="31" height="32" viewBox="0 0 31 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- SVG original -->
                        <g clip-path="url(#clip0_722_7956)">
                            <path d="M23.076 14.9431L22.6747 12.7383L21.1101 13.0055L21.5756 15.5633C21.6168 15.7894 21.7387 15.9922
                                                21.9146 16.127L24.4524 18.0732L24.6985 19.4255L7.4876 22.3654L7.24147 21.0131L8.93911 18.3434C9.05673
                                                18.1585 9.09972 17.9276 9.05861 17.7015L8.43786 14.2911C8.21777 13.0934 8.29153 11.8668 8.65169 10.7352C9.01186
                                                9.60353 9.64569 8.60691 10.4892 7.84595C11.3326 7.08499 12.3559 6.58665 13.4555 6.40126C14.5552 6.21586
                                                15.6924 6.34997 16.7522 6.79004L16.4051 4.88278C15.595 4.65063 14.7612 4.55689 13.9346 4.605L13.6165 2.85717L12.0518
                                                3.12444L12.37 4.87227C10.4802 5.41568 8.87215 6.70676 7.85685 8.49588C6.84155 10.285 6.49109 12.445
                                                6.87324 14.5583L7.42973 17.6158L5.7321 20.2855C5.61447 20.4704 5.57149 20.7013 5.6126 20.9274L6.07815
                                                23.4852C6.11931 23.7114 6.24121 23.9141 6.41702 24.049C6.59284 24.1838 6.80817 24.2396 7.01565 24.2042L12.4919
                                                23.2688L12.647 24.1214C12.8528 25.252 13.4623 26.2659 14.3414 26.9401C15.2205 27.6142 16.2971 27.8934
                                                17.3345 27.7162C18.3719 27.539 19.2851 26.9199 19.8732 25.9951C20.4612 25.0704 20.676 23.9157 20.4702
                                                22.785L20.315 21.9324L25.7912 20.997C25.9987 20.9616 26.1813 20.8378 26.2989 20.6528C26.4165 20.4679
                                                26.4595 20.2369 26.4183 20.0108L25.9528 17.453C25.9116 17.2269 25.7896 17.0241 25.6138 16.8894L23.076
                                                14.9431ZM18.9055 23.0523C19.029 23.7307 18.9002 24.4235 18.5473 24.9784C18.1945 25.5332 17.6466 25.9047
                                                17.0242 26.011C16.4017 26.1173 15.7557 25.9498 15.2283 25.5453C14.7008 25.1408 14.3351 24.5325
                                                14.2117 23.8541L14.0565 23.0015L18.7504 22.1997L18.9055 23.0523Z"
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

                <!-- Card Profil -->
                <div
                    class="flex items-center justify-between w-full sm:w-90 h-auto sm:h-14 bg-white border border-orange-500 shadow-md rounded-2xl px-3 py-2">

                    <!-- Logo + Info -->
                    <div class="flex items-center gap-3">
                        <a href="#">
                            @if (Auth::user()->role == 'super_admin')
                                @if (Auth::user()->superadmin->img_profile)
                                    <img id="pu" class="w-10 h-10 object-cover rounded-full profile-img"
                                        src="{{ asset('storage/' . Auth::user()->superadmin->img_profile) }}"
                                        alt="Profile">
                                @else
                                    <img id="pu" class="w-10 h-10 rounded-full"
                                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                                        alt="Profile">
                                @endif
                            @else
                                <img class="w-10 h-10 rounded-full"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=00509d&color=fff&size=128"
                                    alt="Profile">
                            @endif
                        </a>

                        <div class="text-sm leading-tight">
                            <span class="font-semibold block truncate max-w-[120px] sm:max-w-none">
                                {{ Auth::user()->username }}
                            </span>
                            <p class="text-gray-500 text-xs sm:text-sm break-words max-w-[150px] sm:max-w-none">
                                {{ Auth::user()->email }}
                            </p>
                        </div>
                    </div>

                </div>

            </div>
        </div>


        {{-- content --}}
        <div class="pl-3 mt-5">

            {{-- header status & tombol --}}
            <div class="flex justify-end items-center gap-3 mb-4 flex-wrap sm:flex-nowrap">

                <span class="font-medium whitespace-nowrap break-words">
                    Status
                </span>

                @if ($event->status == 'buka')
                    <span
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm whitespace-nowrap break-words">
                        Buka
                    </span>
                @elseif ($event->status == 'tutup')
                    <span
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm whitespace-nowrap break-words">
                        Tutup
                    </span>
                @else
                    <span class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm whitespace-nowrap break-words">
                        Draft
                    </span>
                @endif

                <form action="{{ route('superadmin.event.destroy', $event->id) }}" method="post" class="w-full sm:w-auto">
                    @csrf
                    @method('delete')
                    <button
                        class="bg-red-500 hover:bg-red-600 text-white px-14 py-2 rounded-lg text-sm w-full sm:w-auto text-center break-words">
                        Hapus
                    </button>
                </form>
            </div>

            {{-- Edit & lihat partisipan --}}
            <div class="flex justify-end items-center gap-3 mb-6 flex-wrap sm:flex-nowrap">

                <a href="{{ route('superadmin.edit.event', $event->id) }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-12 py-2 rounded-lg text-sm 
                   w-full sm:w-auto text-center break-words">
                    Edit Event
                </a>

                {{-- <button
                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg text-sm 
                   w-full sm:w-auto text-center break-words">
                    Lihat Partisipan
                </button> --}}
            </div>



            {{-- tanggal --}}
            <p class="mb-2 font-semibold text-base sm:text-lg break-words">
                {{ \Carbon\Carbon::parse($event->tgl_mulai)->format('d M Y') }}
            </p>

            {{-- gambar --}}
            @if ($event->image)
                <img src="{{ asset('storage/' . $event->image) }}" alt="event image"
                    class="rounded-2xl mb-6 w-full h-auto object-cover">
            @else
                <img src="{{ asset('images/rang nulis.jpg') }}" alt="event image"
                    class="rounded-2xl mb-6 w-full h-auto object-cover">
            @endif

            {{-- deskripsi --}}
            <h2 class="font-semibold text-lg sm:text-xl mb-2 break-words">
                {{ $event->title }}
            </h2>

            <div class="tinymce-content prose prose-sm sm:prose-base max-w-full break-words">
                {!! $event->content !!}
            </div>




            {{-- detail acara --}}
            <h3 class="font-semibold text-orange-600 mt-6 mb-2 text-lg sm:text-xl break-words">
                Detail Acara
            </h3>

            <div class="space-y-3">

                {{-- Waktu --}}
                <div class="flex items-start sm:items-center gap-2 flex-wrap">
                    <svg width="22" height="22" viewBox="0 0 22 22" class="flex-shrink-0">
                        <path
                            d="M11.0277 21.8855C5.11833 21.8855 0.328125 17.0953 0.328125 11.1859C0.328125 5.27653 5.11833 0.486328 11.0277 0.486328C16.9371 0.486328 21.7273 5.27653 21.7273 11.1859C21.7273 17.0953 16.9371 21.8855 11.0277 21.8855ZM11.0277 19.7456C13.2979 19.7456 15.4751 18.8438 17.0803 17.2385C18.6856 15.6333 19.5874 13.4561 19.5874 11.1859C19.5874 8.91575 18.6856 6.73856 17.0803 5.13331C15.4751 3.52806 13.2979 2.62625 11.0277 2.62625C8.75755 2.62625 6.58036 3.52806 4.97511 5.13331C3.36986 6.73856 2.46804 8.91575 2.46804 11.1859C2.46804 13.4561 3.36986 15.6333 4.97511 17.2385C6.58036 18.8438 8.75755 19.7456 11.0277 19.7456ZM12.0977 11.1859H16.3775V13.3258H9.95775V5.83612H12.0977V11.1859Z"
                            fill="black" />
                    </svg>

                    <p class="text-sm sm:text-base break-words">
                        Waktu:
                        {{ \Carbon\Carbon::parse($event->tgl_mulai)->format('d M Y') }}
                        ({{ $event->jam_mulai }} - {{ $event->jam_akhir }}) WIB
                    </p>
                </div>

                {{-- Lokasi --}}
                <div class="flex items-start sm:items-center gap-2 flex-wrap">
                    <svg width="18" height="22" viewBox="0 0 18 22" class="flex-shrink-0">
                        <path
                            d="M8.8885 13.4301C11.2488 13.4301 13.1683 11.5105 13.1683 9.15022C13.1683 6.78989 11.2488 4.87039 8.8885 4.87039C6.52817 4.87039 4.60867 6.78989 4.60867 9.15022C4.60867 11.5105 6.52817 13.4301 8.8885 13.4301ZM8.8885 7.0103C10.0687 7.0103 11.0284 7.97006 11.0284 9.15022C11.0284 10.3304 10.0687 11.2901 8.8885 11.2901C7.70834 11.2901 6.74858 10.3304 6.74858 9.15022C6.74858 7.97006 7.70834 7.0103 8.8885 7.0103Z"
                            fill="black" />
                        <path
                            d="M8.26731 21.79C8.4484 21.9193 8.66537 21.9888 8.88789 21.9888C9.11041 21.9888 9.32738 21.9193 9.50846 21.79C9.83373 21.56 17.4786 16.04 17.4476 9.14951C17.4476 4.42993 13.6075 0.589844 8.88789 0.589844C4.1683 0.589844 0.328219 4.42993 0.328219 9.14416C0.29719 16.04 7.94205 21.56 8.26731 21.79ZM8.88789 2.72976C12.4284 2.72976 15.3076 5.60902 15.3076 9.15486C15.3301 13.9033 10.6127 18.1671 8.88789 19.5656C7.16419 18.1661 2.44567 13.9012 2.46814 9.14951C2.46814 5.60902 5.3474 2.72976 8.88789 2.72976Z"
                            fill="black" />
                    </svg>

                    <p class="text-sm sm:text-base break-words whitespace-normal">
                        Lokasi: {{ $event->lokasi ?? '-' }}
                    </p>
                </div>

                {{-- Link Form --}}
                <div class="flex gap-2 items-start flex-wrap">
                    <i class="ph ph-link text-2xl w-[35px] flex-shrink-0"></i>

                    @if ($event->link_form)
                        <a href="{{ $event->link_form }}" target="_blank"
                            class="text-blue-600 underline hover:text-blue-800 break-words max-w-full">
                            {{ $event->link_form }}
                        </a>
                    @else
                        <p class="text-sm">Belum ditentukan</p>
                    @endif
                </div>

            </div>


            <!-- Daftar kegiatan -->
            <h3 class="text-base font-semibold mb-2 mt-2">Daftar kegiatan :</h3>

            <div class="rounded-xl border-2 border-orange-500 overflow-hidden">
                <div class="overflow-x-auto"> <!-- Tambah scroll mobile -->
                    <table class="w-full text-sm border-collapse min-w-[350px]">
                        <thead>
                            <tr>
                                <th class="border border-orange-500 px-4 py-2 text-center w-[20%] break-words">
                                    Waktu
                                </th>
                                <th class="border border-orange-500 px-4 py-2 text-center break-words">
                                    Acara
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($event->kegiatan as $k)
                                <tr>
                                    <td class="border border-orange-500 px-4 py-2 text-center break-words">
                                        {{ $k->waktu }}
                                    </td>
                                    <td class="border border-orange-500 px-4 py-2 text-center break-words">
                                        {{ $k->kegiatan }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center py-3 break-words">
                                        Belum ada kegiatan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- tombol daftar --}}
            <!-- <div class="flex justify-center mt-6">
                                                                                                <button class="bg-orange-500 text-white px-8 py-2 rounded">Mendaftar</button>
                                                                                            </div> -->
        </div>

        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')
    </main>
@endsection
