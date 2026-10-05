  <!-- Overlay Eventmodal -->
  <div id="eventmodal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
      <!-- Box Eventmodal -->
      <div class="bg-white w-[400px] rounded-xl shadow-lg overflow-hidden animate-fadeIn">

          <!-- Header -->
          <div class="bg-[#00509d] p-3 flex justify-center items-center">
              <img src="{{ asset('images/logo_area_kerja_putih.png') }}" alt="Logo" class="h-6">
              <span class="text-white font-semibold ml-2">areakerja.com</span>
              <!-- Ganti dengan logo asli -->
          </div>

          <!-- Isi -->
          <div class="p-6 flex flex-col items-center text-center space-y-6">

              <!-- Ikon tanda seru -->
              <div
                  class="w-12 h-12 rounded-full bg-red-500 flex items-center justify-center text-white text-3xl font-bold">
                  !
              </div>

              <!-- Teks -->
              <p class="text-sm font-medium">
                  Apalah anda yakin untuk<br>mengikuti event ini ?
              </p>

              <!-- Tombol -->
              <div class="flex space-x-6">
                  <a href="{{ url('/perusahaan/berhasilikut') }}"
                      class="bg-green-600 text-white px-8 py-2 rounded-md font-semibold">
                      Ya
                  </a>
                  <button onclick="closeEventmodal()" class="bg-red-600 text-white px-8 py-2 rounded-md font-semibold">
                      Tidak
                  </button>
              </div>
          </div>
      </div>
  </div>

  <script>
      function openEventmodal() {
          document.getElementById('eventmodal').classList.remove('hidden');
          document.getElementById('eventmodal').classList.add('flex');
      }

      function closeEventmodal() {
          document.getElementById('eventmodal').classList.remove('flex');
          document.getElementById('eventmodal').classList.add('hidden');
      }
  </script>

  <style>
      @keyframes fadeIn {
          from {
              opacity: 0;
              transform: translateY(20px);
          }

          to {
              opacity: 1;
              transform: translateY(0);
          }
      }

      .animate-fadeIn {
          animation: fadeIn 0.3s ease-out;
      }
  </style>

