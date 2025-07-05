<?php
namespace App\Repositories\Category;

interface CategoryRepositoryInterface
{
    public function all($request);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function getParentOptions($excludeId = null);

    // lay cate ra ben header User/HomeController 
    public function getWithChildren();
}
