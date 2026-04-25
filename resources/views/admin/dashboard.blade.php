<x-admin.layout.app title="Dashboard" breadcrumb="Tổng quan hệ thống">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

        {{-- Welcome card --}}
        <div class="col-span-full bg-white rounded-lg border border-gray-200 p-6 flex items-center gap-5 shadow-sm">
            <div class="w-12 h-12 rounded flex items-center justify-center flex-shrink-0" style="background:#A31D1D;">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Chào mừng</p>
                <p class="text-gray-800 font-semibold text-base mt-0.5">
                    {{ auth()->user()->name }}
                </p>
                <p class="text-xs text-gray-400 mt-0.5 capitalize">
                    {{ auth()->user()->role }} — Đăng nhập lần cuối: {{ now()->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>

        {{-- Stat: Total Admins (superadmin only) --}}
        @if (auth()->user()->isSuperAdmin())
            <div class="bg-white rounded-lg border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Tài khoản Admin</p>
                    <div class="w-8 h-8 rounded flex items-center justify-center" style="background:#FEF2F2;">
                        <svg class="w-4 h-4" style="color:#A31D1D;" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-800">
                    {{ \App\Models\User::allAdmins()->count() }}
                </p>
                <a href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center gap-1 text-xs mt-2 transition-colors duration-150"
                    style="color:#A31D1D;">
                    Xem danh sách
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        @endif

    </div>

</x-admin.layout.app>
