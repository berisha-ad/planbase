<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Company::class, 'company');
    }

    public function index()
    {
        $companies = Company::latest()->paginate(15);
        return response()->json($companies, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:50',
            'email'       => 'nullable|email|unique:companies,email',
            'phone'       => 'nullable|string|max:15',
            'website'     => 'nullable|url',
            'description' => 'required|string',
            'address'     => 'required|string',
            'city'        => 'required|string',
            'zip_code'    => 'required|string|max:10',
            'upload_id'   => 'nullable|exists:uploads,id',
        ]);

        $validated['user_id'] = Auth::id();
        $company = Company::create($validated);

        return response()->json($company, Response::HTTP_CREATED);
    }

    public function show(Company $company)
    {
        return response()->json($company, Response::HTTP_OK);
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:50',
            'email'       => 'sometimes|nullable|email|unique:companies,email,' . $company->id,
            'phone'       => 'sometimes|nullable|string|max:15',
            'website'     => 'sometimes|nullable|url',
            'description' => 'sometimes|required|string',
            'address'     => 'sometimes|required|string',
            'city'        => 'sometimes|required|string',
            'zip_code'    => 'sometimes|required|string|max:10',
            'upload_id'   => 'sometimes|nullable|exists:uploads,id',
        ]);

        $company->update($validated);

        return response()->json($company, Response::HTTP_OK);
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
