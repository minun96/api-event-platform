<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // definisco $search e $category per la ricerca
    public function index(Request $request) {

        // return Event::orderBy('id', 'asc')->paginate(20);
        
        $search = $request->input('q');
        $categoryId = $request->input('categoryId');
        $query = Event::query();

        // controllo se ci sono i parametri
        if ($search) {
            // posso cercare sia nome che città che artista
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('artist', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        
        // restituisco la query
        return $query->paginate(20);

    }
}
