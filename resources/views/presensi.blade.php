@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6"
     x-data="{ open: false, detail: null }"
     x-on:presensi-detail.window="
        open = true;
        detail = $event.detail;
     ">

    <!-- Header -->
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-semibold text-gray-900">Presensi Kantor</h1>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Nav -->
            <button id="btnToday" class="h-10 px-4 rounded-xl bg-[#faa938] text-black text-sm font-medium">
                Hari ini
            </button>

            <button id="btnPrev" class="h-10 w-10 rounded-xl border border-gray-200 bg-white hover:bg-gray-50">
                <span class="sr-only">Prev</span>
                ‹
            </button>

            <button id="btnNext" class="h-10 w-10 rounded-xl border border-gray-200 bg-white hover:bg-gray-50">
                <span class="sr-only">Next</span>
                ›
            </button>

            <div id="rangeText" class="ml-2 text-sm font-medium text-gray-700"></div>
        </div>
    </div>

    <!-- Legend -->
    <div class="mt-4 flex flex-wrap items-center gap-2 text-sm">
        <span class="ml-auto text-xs text-gray-500">Klik tanggal/event untuk lihat detail</span>
    </div>

    <!-- Calendar card -->
    <div class="mt-4 bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-3 sm:p-4">
        <div class="mt-4 bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-3 sm:p-4">
            <div id="presensiGrid"></div>
        </div>
    </div>

    <!-- Modal detail -->
    <div x-show="open" x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display:none;">
        <div class="absolute inset-0 bg-black/40" @click="open=false"></div>

        <div class="relative w-full max-w-md rounded-2xl bg-white shadow-xl ring-1 ring-gray-200">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-500" x-text="detail?.date ?? '-'"></div>
                    <div class="text-lg font-semibold text-gray-900">Detail Presensi</div>
                </div>
                <button class="h-9 w-9 rounded-xl hover:bg-gray-50" @click="open=false">✕</button>
            </div>

            <div class="p-5 space-y-4">
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded-xl bg-gray-50 p-3">
                        <div class="text-gray-500">Status</div>
                        <div class="font-semibold text-gray-900" x-text="detail?.status ?? '-'"></div>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-3">
                        <div class="text-gray-500">Sumber</div>
                        <div class="font-semibold text-gray-900" x-text="detail?.source ?? 'Upload Admin'"></div>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-3">
                        <div class="text-gray-500">Masuk</div>
                        <div class="font-semibold text-gray-900" x-text="detail?.checkIn ?? '-'"></div>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-3">
                        <div class="text-gray-500">Pulang</div>
                        <div class="font-semibold text-gray-900" x-text="detail?.checkOut ?? '-'"></div>
                    </div>
                </div>
            </div>

            <div class="p-5 border-t border-gray-100 flex justify-end gap-2">
                <button class="h-10 px-4 rounded-xl border border-gray-200 hover:bg-gray-50 text-sm"
                        @click="open=false">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const gridEl = document.getElementById('presensiGrid');
  const rangeEl = document.getElementById('rangeText');

  // state bulan aktif
  const now = new Date ();
  let current = new Date(now.getFullYear(), now.getMonth(), 1);

  // dummy data (key: YYYY-MM-DD)
  const data = {
    '2026-02-01': { in:'07:10:21', out:'17:16:45', status:'WFO', note:'' },
    '2026-02-02': { in:'07:01:40', out:'16:10:28', status:'WFO', note:'' },
    '2026-02-03': { in:'07:12:23', out:'17:23:41', status:'WFOL', note:'' },
    '2026-02-08': { in:'-', out:'-', status:'CUTI', note:'' },
    '2026-02-14': { in:'-', out:'-', status:'KN', note:'Tidak ada presensi' },
  };

  const statusColor = (st) => ({
    WFO: 'text-green-600',
    WFA: 'text-blue-600',
    WFOL: 'text-blue-600',
    DL: 'text-amber-600',
    CUTI: 'text-rose-600',
    KN: 'text-gray-500',
  }[st] || 'text-gray-700');

  const fmt = (d) => {
    const y = d.getFullYear();
    const m = String(d.getMonth()+1).padStart(2,'0');
    const day = String(d.getDate()).padStart(2,'0');
    return `${y}-${m}-${day}`;
  };

  function render() {
    // range text
    rangeEl.textContent = `${current.getFullYear()}-${String(current.getMonth()+1).padStart(2,'0')}`;

    const year = current.getFullYear();
    const month = current.getMonth(); // 0-11

    const first = new Date(year, month, 1);
    const last = new Date(year, month + 1, 0);

    // start Monday
    const start = new Date(first);
    const day = (start.getDay() + 6) % 7; // Sun->6, Mon->0
    start.setDate(start.getDate() - day);

    // end to complete weeks (up to 6 rows)
    const end = new Date(last);
    const endDay = (end.getDay() + 6) % 7;
    end.setDate(end.getDate() + (6 - endDay));

    const today = new Date();
    const todayKey = fmt(today);

    const days = [];
    for (let d = new Date(start); d <= end; d.setDate(d.getDate()+1)) {
      days.push(new Date(d));
    }

    const dayNames = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];

    gridEl.innerHTML = `
      <div class="grid grid-cols-7 gap-px rounded-2xl overflow-hidden ring-1 ring-gray-200 bg-gray-200">
        ${dayNames.map((n,i)=>`
          <div class="bg-white px-4 py-3 text-xs font-semibold ${i===6?'text-rose-600':'text-gray-600'}">${n}</div>
        `).join('')}


        ${days.map(d=>{
          const key = fmt(d);
          const inMonth = d.getMonth() === month;
          const isSun = ((d.getDay()+6)%7)===6;
          const cell = data[key];

          const inT = cell?.in ?? '';
          const outT = cell?.out ?? '';
          const st = cell?.status ?? '';
          const stClass = statusColor(st);

          return `
            <button
              type="button"
              data-date="${key}"
              class="bg-white px-4 py-3 text-left min-h-[108px] hover:bg-gray-50 transition flex flex-col
                     ${inMonth ? '' : 'opacity-40'}
                     focus:outline-none"
            >
              <div class="flex items-start justify-between">
                <div class="text-sm font-semibold">
                    ${key === todayKey ? `
                        <span class="inline-flex items-center justify-center
                                    w-7 h-7 rounded-full bg-[#faa938] text-white">
                        ${d.getDate()}
                        </span>
                    ` : `
                        <span class="${isSun ? 'text-rose-600' : 'text-gray-900'}">
                        ${d.getDate()}
                        </span>
                    `}
                </div>
              </div>

              <div class="flex-1 flex items-center justify-center text-xs leading-5 text-gray-800">
                ${cell ? `
                    <div class="text-center">
                    <div>${inT}</div>
                    <div>${outT}</div>
                    <div class="font-semibold ${stClass}">${st}</div>
                    </div>
                ` : `<div class="text-gray-300 select-none">—</div>`}
              </div>
            </button>
          `;
        }).join('')}
      </div>
    `;

    gridEl.querySelectorAll('button[data-date]').forEach(btn => {
      btn.addEventListener('click', () => {
        const date = btn.getAttribute('data-date');
        const cell = data[date];

        window.dispatchEvent(new CustomEvent('presensi-detail', {
          detail: {
            date,
            status: cell?.status ?? '-',
            checkIn: cell?.in ?? '-',
            checkOut: cell?.out ?? '-',
            note: cell?.note ?? 'Tidak ada data',
            source: 'Upload Admin',
          }
        }));
      });
    });
  }

  // nav
  document.getElementById('btnToday').addEventListener('click', () => {
    const now = new Date();
    current = new Date(now.getFullYear(), now.getMonth(), 1);
    render();
  });
  document.getElementById('btnPrev').addEventListener('click', () => {
    current = new Date(current.getFullYear(), current.getMonth()-1, 1);
    render();
  });
  document.getElementById('btnNext').addEventListener('click', () => {
    current = new Date(current.getFullYear(), current.getMonth()+1, 1);
    render();
  });

  render();
});
</script>
@endpush
