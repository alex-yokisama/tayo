<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BaseListRequest extends FormRequest
{
    protected function allowedSorts() : \Illuminate\Support\Collection
    {
        return collect(['id']);
    }

    protected function defaultSort() : string
    {
        return 'id';
    }

    public function allowedPerPages() : \Illuminate\Support\Collection
    {
        return collect([2, 3, 4, 5, 20, 50, 100, 200]);
    }

    protected function defaultPerPage() : int
    {
        return 50;
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    protected function prepareSorts()
    {
        if (!$this->allowedSorts()->contains($this->sort)) {
            $this->sort = $this->defaultSort();
        }
    }

    protected function prepareSortOrder()
    {
        if (strtoupper($this->order) != 'DESC') {
            $this->order = 'ASC';
        } else {
            $this->order = 'DESC';
        }
        if ($this->sort == 'id') {
            $this->order = 'DESC';
        }
    }

    protected function preparePerPage()
    {
        if (!$this->allowedPerPages()->contains($this->perPage)) {
            $this->perPage = $this->defaultPerPage();
        }
    }

    protected function prepareForValidation()
    {
        $this->prepareSorts();
        $this->prepareSortOrder();
        $this->preparePerPage();
    }
}
