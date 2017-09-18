<?php


namespace App\Filters;


use Illuminate\Http\Request;

abstract class Filters
{
    /**
     * @var Request
     */
    private $request;
    protected $builder;
    protected $filters = [];

    /**
     * ThreadFilters constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;
//        if(! $username = $this->request->by) {
//            return $builder;
//        }
//
//        return $this->by($username);

        foreach ($this->request->intersect($this->filters) as $filter => $value) {
            if(method_exists($this, $filter)) {
                $this->$filter($value);
                break;
            }
        }

        return $this->builder;
    }
}