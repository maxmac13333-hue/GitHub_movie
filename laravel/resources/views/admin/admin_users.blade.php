@extends('layouts.app')
@section('title','ผู้ใช้งาน')
@section('header')<h1 class="text-2xl font-bold">ผู้ใช้งาน</h1>@endsection
@section('content')
<div class="mx-auto max-w-5xl px-4 py-10"><div class="cinema-panel overflow-x-auto"><table class="w-full min-w-[560px] text-left"><thead class="bg-white/5 text-sm text-slate-400"><tr><th class="p-4">ชื่อ</th><th class="p-4">อีเมล</th><th class="p-4">สิทธิ์</th><th class="p-4">จำนวนการจอง</th></tr></thead><tbody>@foreach($users as $user)<tr class="border-t border-white/10"><td class="p-4 font-bold">{{ $user->name }}</td><td class="p-4">{{ $user->email }}</td><td class="p-4">{{ $user->is_admin ? 'ผู้ดูแล' : 'สมาชิก' }}</td><td class="p-4">{{ $user->bookings_count }}</td></tr>@endforeach</tbody></table></div><div class="mt-5">{{ $users->links() }}</div></div>
@endsection
