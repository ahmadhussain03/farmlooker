<?php

namespace App\Traits;

trait Searchable {

    public function scopeSearch($query)
    {
        $request = request();

        foreach($request->query() as $key => $param){
            if(in_array(strtolower($key), $this->searchableColumns)){
                $query->where(strtolower($key), 'like', '%' . $param . '%');
            }
        }

        return $query;
    }
}
