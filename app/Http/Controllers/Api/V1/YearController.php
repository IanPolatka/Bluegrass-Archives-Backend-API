<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\YearResource;

class YearController extends Controller
{
    public function index()
    {
        $years = Year::orderBy('year', 'DESC')->get();
        
        return YearResource::collection($years);
    }

    public function show(Year $year)
    {
        return new YearResource($year);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate($this->rules());

        $year = Year::create($validatedData);

        return new YearResource($year);
    }

    public function update(Request $request, Year $year)
    {
        $validatedData = $request->validate($this->rules());

        $year->update($validatedData); 

        return new YearResource($year);
    }

    public function destroy(Year $year)
    {
        return $year->delete();
    }

    public function current()
    {
        $year = Year::where('current_year', 1)->first();
        return new YearResource($year);
    }

    protected function rules()
    {
        return [
            'year' => 'required|unique:years',
            'current_year'  =>  'required'
        ];
    }
}
