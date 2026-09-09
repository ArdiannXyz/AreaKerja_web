@extends('super_admin.sidebar.index')
@section('sidebarsuperadmin')
    <main class="flex-1 p-6 sm:ml-64 bg-white overflow-y-auto" x-data="{ openNotif: false, openAllNotif: false }">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">

            <h1 class="text-2xl font-medium break-words">Event</h1>

            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full sm:w-auto">

                {{-- Tombol Notifikasi --}}
                @include('super_admin.components.notif_button')

                {{-- User Badge Dropdown --}}
                @include('super_admin.components.user_badge_dropdown')

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
            <h3 class="font-semibold text-blue-800 mt-6 mb-2 text-lg sm:text-xl break-words">
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

            <div class="rounded-xl border-2 border-blue-700 overflow-hidden">
                <div class="overflow-x-auto"> <!-- Tambah scroll mobile -->
                    <table class="w-full text-sm border-collapse min-w-[350px]">
                        <thead>
                            <tr>
                                <th class="border border-blue-700 px-4 py-2 text-center w-[20%] break-words">
                                    Waktu
                                </th>
                                <th class="border border-blue-700 px-4 py-2 text-center break-words">
                                    Acara
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($event->kegiatan as $k)
                                <tr>
                                    <td class="border border-blue-700 px-4 py-2 text-center break-words">
                                        {{ $k->waktu }}
                                    </td>
                                    <td class="border border-blue-700 px-4 py-2 text-center break-words">
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
                                                                                                <button class="bg-blue-700 text-white px-8 py-2 rounded">Mendaftar</button>
                                                                                            </div> -->
        </div>

        @include('super_admin.notif.modal_notif')
        @include('super_admin.notif.modal_semua')
    </main>
@endsection
