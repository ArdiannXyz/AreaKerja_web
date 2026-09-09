<!-- Main modal -->
<div id="show-pendidikan" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm">
            <!-- Modal header -->
            <div
                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    Pendidikan
                </h3>
                <button type="button"
                    class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="show-pendidikan">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            @if (isset($pelamar) && $pelamar->riwayat_pendidikan->count() > 0)
                @foreach ($pelamar->riwayat_pendidikan as $pend)
                    <div class="mb-6 mt-4">
                        <div class="flex justify-between ml-4">
                            <h3 class="font-semibold text-gray-800 text-lg">
                                {{ $pend->jurusan }} - {{ $pend->pendidikan }}
                                ({{ $pend->tahun_awal }} - {{ $pend->tahun_akhir }})
                            </h3>
                            {{-- <a href="{{ route('pendidikan.edit', $pend->id) }}">
                                <svg width="14" height="14" viewBox="0 0 10 10" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="mr-16">
                                    <path
                                        d="M9.83752 2.24357C10.0542 2.02693 10.0542 1.66587 9.83752 1.46034L8.5377 0.160524C8.33218 -0.0561123 7.97112 -0.0561123 7.75448 0.160524L6.7324 1.17705L8.81544 3.26009M0 7.915V9.99805H2.08304L8.22664 3.8489L6.14359 1.76586L0 7.915Z"
                                        fill="#00509d" />
                                </svg>
                            </a> --}}
                        </div>
                        <div class="flex gap-2 mt-2 ml-4">
                            <form action="{{ route('superadmin.pendidikan.destroy', $pend->id) }}" method="POST"
                                onsubmit="return confirm('Yakin hapus data pendidikan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-3 py-1 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 font-medium">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endif
            <div class="flex justify-end mr-6">
                <button data-modal-target="create_pendidikanmodal" data-modal-toggle="create_pendidikanmodal"
                    data-modal-hide="show-org" type="button"
                    class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-2 py-2 mb-4 ml-4 text-center">

                    <svg width="12" height="12" viewBox="0 0 45 45" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M42.1875 19.6875H25.3125V2.8125C25.3125 2.06658 25.0162 1.35121 24.4887 0.823763C23.9613 0.296317 23.2459 0 22.5 0C21.7541 0 21.0387 0.296317 20.5113 0.823763C19.9838 1.35121 19.6875 2.06658 19.6875 2.8125V19.6875H2.8125C2.06658 19.6875 1.35121 19.9838 0.823763 20.5113C0.296317 21.0387 0 21.7541 0 22.5C0 23.2459 0.296317 23.9613 0.823763 24.4887C1.35121 25.0162 2.06658 25.3125 2.8125 25.3125H19.6875V42.1875C19.6875 42.9334 19.9838 43.6488 20.5113 44.1762C21.0387 44.7037 21.7541 45 22.5 45C23.2459 45 23.9613 44.7037 24.4887 44.1762C25.0162 43.6488 25.3125 42.9334 25.3125 42.1875V25.3125H42.1875C42.9334 25.3125 43.6488 25.0162 44.1762 24.4887C44.7037 23.9613 45 23.2459 45 22.5C45 21.7541 44.7037 21.0387 44.1762 20.5113C43.6488 19.9838 42.9334 19.6875 42.1875 19.6875Z"
                            fill="white" />
                    </svg>

                </button>
            </div>
        </div>
    </div>
</div>
