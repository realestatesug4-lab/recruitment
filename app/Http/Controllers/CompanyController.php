<?php

namespace App\Http\Controllers;

use App\Domain\Companies\Models\Company;
use App\ViewModels\CompanyIndexViewModel;
use App\ViewModels\CompanyProfileViewModel;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function index()
    {
        $paginator = Company::withCount(['jobs as jobs_count' => fn ($query) => $query->published()])
            ->orderByDesc('jobs_count')
            ->orderBy('name')
            ->paginate(20);

        return view('companies.index', [
            'viewModel' => new CompanyIndexViewModel($paginator),
            'paginator' => $paginator,
        ]);
    }

    public function show(Company $company)
    {
        $company->loadCount(['jobs as published_jobs_count' => fn ($query) => $query->published()]);

        return view('companies.show', [
            'viewModel' => new CompanyProfileViewModel($company),
        ]);
    }

    public function store(StoreCompanyRequest $request): RedirectResponse
    {
        $company = Company::create(array_merge($request->validated(), ['owner_id' => Auth::id()]));

        return redirect()->route('companies.show', $company)->with('success', 'Company created.');
    }

    public function update(Company $company, UpdateCompanyRequest $request): RedirectResponse
    {
        $company->update($request->validated());
        return redirect()->route('companies.show', $company)->with('success', 'Company updated.');
    }

    public function destroy(Company $company): RedirectResponse
    {
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company removed.');
    }
}
