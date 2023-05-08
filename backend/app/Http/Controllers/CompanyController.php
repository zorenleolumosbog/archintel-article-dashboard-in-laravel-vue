<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Http\Resources\CompanyResource;
use Illuminate\Http\Request;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $companies = Company::when(!$request->date_from && !$request->date_to, function ($query) use($request) {
                    $query->when($request->search, function ($query) use($request) {
                        $query->where('name', 'LIKE', '%'.$request->search.'%');
                    });
                })
                ->when($request->date_from && $request->date_to, function ($query) use($request) {
                    $query->whereDate('created_at', '>=', $request->date_from)
                    ->whereDate('created_at', '<=', $request->date_to)
                    ->when($request->search, function ($query) use($request) {
                        $query->where(function ($query) use($request) {
                            $query->where('name', 'LIKE', '%'.$request->search.'%');
                        });
                    });
                })
                ->when($request->status, function ($query) use($request) {
                    $query->where('status', $request->status);
                })
                ->orderBy('created_at', 'desc')
                ->paginate($request->limit ? $request->limit : Company::count());
        
        return CompanyResource::collection($companies);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyRequest $request)
    {
        $file = $request->file('logo');
        $path = Storage::disk('public')->put("upload/" . Carbon::now()->toDateString() . "/" . Auth::user()->id, $file);

        $company = Company::create([
            'logo' => Storage::disk('public')->url($path),
            'path' => $path,
            'name' => $request->name,
            'status' => $request->status
        ]);

        return new CompanyResource($company);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return new CompanyResource($company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyRequest $request, Company $company)
    {
        $data = $request->all();
        
        $file = $request->file('logo');
        if($file) {
            //Remove existing file
            Storage::disk('public')->delete($company->path);

            $path = Storage::disk('public')->put("upload/" . Carbon::now()->toDateString() . "/" . Auth::user()->id, $file);
    
            $company->update([
                'logo' => Storage::disk('public')->url($path),
                'path' => $path
            ]);

           $data = $request->except(['logo', 'path']);
        }
        
        $company->update($data);

        return new CompanyResource($company);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->delete();
        
        return response(null, 204);
    }
}
