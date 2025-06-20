<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class ManageCategories extends Component
{
    use WithPagination;

    public $perPage = 5;
    public $sortBy = 'created_at';
    public $sortDir = 'DESC';
    public $search = '';
    public $currentUrl;

    public function setSortBy($sortColum)
    {
        if ($this->sortBy == $sortColum) {
            $this->sortDir = ($this->sortDir == 'ASC') ? 'DESC' : 'ASC';
            return;
        }

        $this->sortBy = $sortColum;
        $this->sortDir = 'ASC';
    }
    public function delete($id)
    {
        $category = Category::find($id);

        if ($category) {
            $category->delete();
            session()->flash('message', 'Category deleted successfully.');
        } else {
            session()->flash('error', 'Category not found.');
        }

        $this->resetPage();
    }
    public function render()
    {
        $current_url = url()->current();
        $explode_url = explode('/', $current_url);

        $this->currentUrl = $explode_url[3] . ' ' . $explode_url[4];

        return view('livewire.manage-categories', [
            'categories' => Category::when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
                ->orderBy($this->sortBy, $this->sortDir)
                ->paginate($this->perPage)
        ])
            ->layout('admin-layout');
    }
}
