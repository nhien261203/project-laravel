<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Category\CategoryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    protected $repo;

    public function __construct(CategoryRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request)
    {
        $categories = $this->repo->all($request);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = $this->repo->getParentOptions();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);

        try {
            $this->repo->create($request->all());

            return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công');
        } catch (\Illuminate\Database\QueryException $e) {
            // Bắt lỗi duplicate slug
            if ($e->getCode() === '23000') {
                return back()
                    ->withInput()
                    ->with('error', 'Slug đã tồn tại. Vui lòng chọn tên khác hoặc nhập slug thủ công.');
            }

            return back()
                ->withInput()
                ->with('error', 'Đã có lỗi xảy ra: ' . $e->getMessage());
        }
    }


    public function edit($id)
    {
        $category = $this->repo->find($id);
        $parents = $this->repo->getParentOptions($id); // loại chính nó khỏi danh sách
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, $id)
    {
        $this->validateRequest($request, $id);

        try {
            $this->repo->update($id, $request->all());

            return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                return back()
                    ->withInput()
                    ->with('error', 'Slug đã tồn tại. Vui lòng đổi tên hoặc nhập slug khác.');
            }

            return back()
                ->withInput()
                ->with('error', 'Đã có lỗi xảy ra: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        $this->repo->delete($id);
        return redirect()->route('admin.categories.index')->with('success', 'Xoá danh mục thành công');
    }

    protected function validateRequest($request, $id = null)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:categories,slug' . ($id ? ',' . $id : '')],
            'status' => ['required', 'in:0,1'],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
        ];

        // Nếu đang update và người dùng chọn chính mình làm parent
        if ($id && $request->input('parent_id') == $id) {
            // thêm lỗi thủ công
            Validator::make([], [])->after(function ($validator) {
                $validator->errors()->add('parent_id', 'Không được chọn chính mình làm danh mục cha.');
            })->validate();
        }

        Validator::make($request->all(), $rules)->validate();
    }

    public function show($id)
    {
        $category = $this->repo->find($id);
        return view('admin.categories.show', compact('category'));
    }
}
