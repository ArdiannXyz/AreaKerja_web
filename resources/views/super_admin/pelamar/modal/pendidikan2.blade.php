<!-- Main modal -->
<div id="create_pendidikanmodal2" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <!-- Modal header -->
            <div
                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-lg font-semibold">Tambah Pengalaman Organisasi</h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="create_pendidikanmodal2">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="max-w-lg mx-auto bg-white shadow-lg rounded-xl p-6">
                <form
                    action="{{ isset($riwayatpendidikan)
                        ? route('superadmin.pendidikan.update', $riwayatpendidikan->id)
                        : route('superadmin.pendidikan.update') }}"
                    method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="pelamar_id" value="{{ $pelamar->id }}">


                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-1">Pendidikan</label>
                        <input type="text" name="pendidikan"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-1">Jurusan</label>
                        <input type="text" name="jurusan"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-900 mb-1">Asal Pendidikan</label>
                        <input name="asal_pendidikan" type="text"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"></input>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-1">Tahun Awal</label>
                            <input type="number" name="tahun_awal"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-1">Tahun Akhir</label>
                            <input type="number" name="tahun_akhir"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600">
                        </div>
                    </div>


                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-blue-700 font-medium hover:bg-blue-800 text-white px-5 py-2 rounded-lg shadow-md transition">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
