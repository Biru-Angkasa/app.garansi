<?php

namespace App\Http\Controllers;

use App\Models\Garansi;
use Illuminate\Http\Request;

class GaransiChatController extends Controller
{
    // dipanggil dari dashboard/detail garansi (teknisi, perlu login)
    public function index(Garansi $garansi)
    {
        $garansi->chats()->where('sender_type', '!=', 'teknisi')->update(['is_read' => true]);

        return response()->json($garansi->chats()->oldest()->get());
    }

    public function store(Request $request, Garansi $garansi)
    {
        $request->validate(['message' => 'required|string|max:2000']);

        $chat = $garansi->chats()->create([
            'user_id'     => auth()->id(),
            'sender_name' => auth()->user()->name,
            'sender_type' => 'teknisi',
            'message'     => $request->message,
            'is_read'     => true,
        ]);

        return response()->json($chat);
    }

    // dipanggil dari halaman tracking publik (customer, tanpa login)
    public function indexPublic(Garansi $garansi)
    {
        return response()->json($garansi->chats()->oldest()->get());
    }

    public function storeFromCustomer(Request $request, Garansi $garansi)
    {
        $request->validate(['message' => 'required|string|max:2000']);

        $chat = $garansi->chats()->create([
            'user_id'     => null,
            'sender_name' => $garansi->nama,
            'sender_type' => 'customer',
            'message'     => $request->message,
            'is_read'     => false,
        ]);

        return response()->json($chat);
    }

    // floating bubble dashboard (teknisi, perlu login)
    public function active()
    {
        $garansis = Garansi::whereHas('chats')
            ->withCount(['chats as unread_count' => fn ($q) => $q->where('is_read', false)])
            ->get();

        return response()->json(
            $garansis->map(function ($g) {
                $last = $g->chats()->latest()->first();
                return [
                    'id'              => $g->id,
                    'nama'            => $g->nama,
                    'unread'          => $g->unread_count,
                    'last_message'    => optional($last)->message,
                    'last_message_at' => optional($last)->created_at,
                ];
            })->sortByDesc('last_message_at')->values()
        );
    }
    
}