<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Repositories\Category\CategoryRepositoryInterface;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $categoryRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function index()
    {
        // lay cate cho header 
        $categoriesWithChildren = $this->categoryRepo->getWithChildren();

        // lay banner cho home
        $banners = Banner::where('status', 1)
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        return view('layout.user', compact('categoriesWithChildren', 'banners'));
    }
}
