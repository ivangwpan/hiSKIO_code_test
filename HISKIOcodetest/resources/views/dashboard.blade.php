<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 border">
                    帳戶狀態
                    <form action="{{ route('deposit') }}" method="POST">
                        @csrf
                        <input type="number" name="money" class="form-control text-black" placeholder="輸入存款金額" required
                            min=0>
                        <button type="submit" class="bg-red">存款</button>
                    </form>
                    <form action="{{ route('withdraw') }}" method="POST">
                        @csrf
                        <input type="number" name="money" class="form-control text-black" placeholder="輸入提款金額"
                            required min=0>
                        <button type="submit" class="bg-red">提款</button>
                    </form>
                    {{-- {{ __("You're logged in!") }} --}}
                    <table class="table w-100 border m-auto">
                        <thead class="border">
                            <tr>
                                <th class="border">用戶 ID</th>
                                <th class="border">帳號</th>
                                <th class="border">存款餘額</th>
                                <th class="border">詳細資料</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th class="border">{{ Auth::user()->id }}</th>
                                <td class="border">{{ Auth::user()->email }}</td>
                                <td class="border">${{ Auth::user()->accounts }}</td>
                                <td class="border">詳情查看</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @php
                $sortedBalances = Auth::user()->balance->sortByDesc('created_at');
            @endphp
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 border">
                    {{-- {{ __("You're logged in!") }} --}}
                    <table class="table w-100 border m-auto">
                        <thead class="border">
                            <tr>
                                <th class="border">金額</th>
                                <th class="border">存款餘額</th>
                                <th class="border">時間</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sortedBalances as $balance)
                                <tr>
                                    <th class="border">
                                        @if ($balance->current_balance - $balance->passed_balance < 0)
                                            提款
                                        @else
                                            存款
                                        @endif
                                        ${{ abs($balance->current_balance - $balance->passed_balance) }}
                                    </th>
                                    <td class="border">${{ $balance->current_balance }}</td>
                                    <td class="border">{{ $balance->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
