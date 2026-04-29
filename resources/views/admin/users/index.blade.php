<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b text-left">
                            <th class="py-2">ID</th>
                            <th class="py-2">Name</th>
                            <th class="py-2">Phone</th>
                            <th class="py-2">Role</th>
                            <th class="py-2">Created</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($users as $user)
                            <tr class="border-b">
                                <td class="py-2">{{ $user->id }}</td>
                                <td class="py-2">{{ $user->name }}</td>
                                <td class="py-2">{{ $user->phone }}</td>
                                <td class="py-2">
                                    <span class="px-2 py-1 text-xs rounded 
                                        {{ $user->role === 'admin' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="py-2">{{ $user->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

        </div>
    </div>
</x-app-layout>