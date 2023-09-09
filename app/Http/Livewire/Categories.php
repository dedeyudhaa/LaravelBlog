<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use App\Models\Post;

class Categories extends Component
{

    public $category_name;
    public $selected_category_id;
    public $updateCategoryMode = false;

    public $subcategory_name;
    public $parent_category = 0;
    public $selected_subcategory_id;
    public $updateSubCategoryMode = false;

    protected $listeners = [
        'resetModalForm',
        'deleteCategoryAction',
        'deleteSubCategoryAction', 
        'updateCategoryOrdering',
        'updateSubCategoryOrdering'
    ];

    public function resetModalForm()
    {
        $this->resetErrorBag();
        $this->category_name = null;
        $this->subcategory_name = null;
        $this->parent_category = null;
    }

    public function addCategory()
    {
        $this->validate([
            'category_name' => 'required|unique:categories,category_name'
        ]);

        $category = new Category();
        $category->category_name = $this->category_name;
        $saved = $category->save();

        if($saved){
            $this->dispatchBrowserEvent('hideCategoriesModal');
            $this->category_name = null;
            $this->showToaster('Category baru berhasil ditambahkan','success');
        } else {
            $this->showToaster('Terjadi Keselahan','error');
        }
    }


    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        $this->selected_category_id = $category->id;
        $this->category_name = $category->category_name;
        $this->updateCategoryMode = true;
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('showCategoriesModal');
    }


    public function updateCategory()
    {
        if($this->selected_category_id){
            $this->validate([
                'category_name' => 'required|unique:categories,category_name,'.$this->selected_category_id
            ]);

            $category = Category::findOrFail($this->selected_category_id);
            $category->category_name = $this->category_name;
            $updated = $category->save();

            if($updated){
                $this->dispatchBrowserEvent('hideCategoriesModal');
                $this->updateCategoryMode = false;
                $this->showToaster('Category berhasil diperbarui','success');
            } else{
                $this->showToaster('Terjadi kesalahan','error');
            }
        }
    }


    public function addSubCategory()
    {
        $this->validate([
            'parent_category' => 'required',
            'subcategory_name' => 'required|unique:sub_categories,subcategory_name'
        ]);

        $subcategory = new SubCategory();
        $subcategory->subcategory_name = $this->subcategory_name;
        $subcategory->slug = Str::slug($this->subcategory_name);
        $subcategory->parent_category = $this->parent_category;
        $saved = $subcategory->save();

        if($saved){
            $this->dispatchBrowserEvent('hideSubCategoriesModal');
            $this->parent_category = null;
            $this->subcategory_name = null;
            $this->showToaster('SubCategory baru berhasil ditambahkan','success');
        }else{
            $this->showToaster('Terjadi kesalahan','error');
        }
    }


    public function editSubCategory($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $this->selected_subcategory_id = $subcategory->id;
        $this->parent_category = $subcategory->parent_category;
        $this->subcategory_name = $subcategory->subcategory_name;
        $this->updateSubCategoryMode = true;
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('showSubCategoriesModal');
    }


    public function updateSubCategory()
    {
        if($this->selected_subcategory_id){
            $this->validate([
                'parent_category' => 'required',
                'subcategory_name' => 'required|unique:sub_categories,subcategory_name,'.$this->selected_subcategory_id
            ]);

            $subcategory = SubCategory::findOrFail($this->selected_subcategory_id);
            $subcategory->subcategory_name = $this->subcategory_name;
            $subcategory->slug = Str::slug($this->subcategory_name);
            $subcategory->parent_category = $this->parent_category;
            $updated = $subcategory->save();

            if($updated){
                $this->dispatchBrowserEvent('hideSubCategoriesModal');
                $this->updateSubCategoryMode = false;
                $this->showToaster('SubCategory berhasil diperbarui','success');
            } else {
                $this->showToaster('Terjadi Kesalahan','error');
            }
        }
    }


    public function deleteCategory($id)
    {
        $category = Category::find($id);
        $this->dispatchBrowserEvent('deleteCategory', [
            'title' => 'Apa anda yakin ?',
            'html' => 'Anda ingin menghapus category <b>'.$category->category_name.'</b> ',
            'id' => $id
        ]);
    }


    public function deleteCategoryAction($id)
    {
        $category = Category::where('id',$id)->first();
        $subcategories = SubCategory::where('parent_category',$category->id)->whereHas('posts')->with('posts')->get();

        if( !empty($subcategories) && count($subcategories) > 0 ){
            $totalPosts = 0;

            foreach($subcategories as $subcat){
                $totalPosts = $totalPosts + Post::where('subcategory_id', $subcat->id)->get()->count();
            }

            $this->showToaster('Kategori ini tidak dapat dihapus, terdapat ('.$totalPosts.') postingan terkait, ','error');

        } else{
            SubCategory::where('parent_category',$category->id)->delete();
            $category->delete();
            $this->showToaster('Kategori berhasil dihapus, ','info');
        }

    }


    public function deleteSubCategory($id)
    {
        $subcategory = SubCategory::find($id);
        $this->dispatchBrowserEvent('deleteSubCategory', [
            'title' => 'Apa anda yakin ?',
            'html' => 'Anda ingin menghapus subcategory <b>'.$subcategory->category_name.'</b> ',
            'id' => $id
        ]);
    }

    public function deleteSubCategoryAction($id)
    {
        $subcategory = SubCategory::where('id', $id)->first();
        $posts = Post::where('subcategory_id',$subcategory->id)->get()->toArray();

        if( !empty($posts) && count($posts) > 0 ){
            
            $this->showToaster('Sub kategori ini tidak dapat dihapus, terdapat ('.count($posts).') postingan terkait, ','error');

        } else{
            $subcategory->delete();
            $this->showToaster('Sub kategori berhasil dihapus, ','info');
        }
    }


    public function updateCategoryOrdering($positions)
    {
        // dd($positions);
        foreach($positions as $position){
            $index = $position[0];
            $newPosition = $position[1];
            Category::where('id', $index)->update([
                'ordering' => $newPosition
            ]);
            $this->showToaster('Urutan kategory berhasil diperbarui','success');
        }
    }


    public function updateSubCategoryOrdering($positions)
    {
         //dd($positions);
        foreach($positions as $position){
            $index = $position[0];
            $newPosition = $position[1];
            SubCategory::where('id', $index)->update([
                'ordering' => $newPosition
            ]);
            $this->showToaster('Urutan sub kategory berhasil diperbarui','success');
        }
    }

    public function showToaster($message, $type){
        return $this->dispatchBrowserEvent('showToaster', [
            'type' => $type,
            'message' => $message
        ]);
    }

    public function render()
    {
        return view('livewire.categories',[
            'categories' => Category::orderBy('ordering','asc')->get(),
            'subcategories' => SubCategory::orderBy('ordering','asc')->get()
        ]);
    }
}
