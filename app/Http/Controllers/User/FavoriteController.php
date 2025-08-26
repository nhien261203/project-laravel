<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Repositories\Favorite\FavoriteRepositoryInterface;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    protected $favoriteRepo;

    public function __construct(FavoriteRepositoryInterface $favoriteRepo)
    {
        $this->favoriteRepo = $favoriteRepo;
    }

    public function index(Request $request)
    {
        $favorites = $this->favoriteRepo->getUserFavorites(
            $request->user()?->id,
            $request->session()->getId(),
            8
        );

        return view('user.favorites.index', compact('favorites'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
        ]);

        $favorite = $this->favoriteRepo->addToFavorite(
            $request->user()?->id,
            $request->session()->getId(),
            $request->product_id,
            $request->product_variant_id
        );

        return response()->json($favorite, 201);
    }

    public function destroy(Request $request, $id)
    {
        $this->favoriteRepo->removeFromFavorite(
            $request->user()?->id,
            $request->session()->getId(),
            $id
        );
        return response()->json(['message' => 'Đã xoá khỏi danh sách yêu thích']);
    }
    public function destroyByProduct(Request $request, $productId)
    {
        $favorite = Favorite::where('product_id', $productId)
            ->where(function ($q) use ($request) {
                $q->where('user_id', $request->user()?->id)
                    ->orWhere('session_id', $request->session()->getId());
            })->first();

        if ($favorite) {
            $favorite->delete();
        }

        return response()->json(['message' => 'Đã xoá khỏi yêu thích']);
    }
    public function count(Request $request)
    {

        $count = Favorite::where(function ($q) use ($request) {
            $q->where('user_id', $request->user()?->id)
                ->orWhere('session_id', $request->session()->getId());
        })->count();

        return response()->json(['count' => $count]);
    }
    // public function toggle(Request $request, $productId)
    // {
    //     $request->validate([
    //         'product_id' => 'exists:products,id'
    //     ]);

    //     $favorite = Favorite::where('product_id', $productId)
    //         ->where(function ($q) use ($request) {
    //             $q->where('user_id', $request->user()?->id)
    //                 ->orWhere('session_id', $request->session()->getId());
    //         })->first();

    //     if ($favorite) {
    //         $favorite->delete();
    //         return response()->json(['removed' => true]);
    //     } else {
    //         Favorite::create([
    //             'user_id' => $request->user()?->id,
    //             'session_id' => $request->session()->getId(),
    //             'product_id' => $productId
    //         ]);
    //         return response()->json(['added' => true]);
    //     }
    // }
}
