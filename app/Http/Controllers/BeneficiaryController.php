<?php

// In app/Http/Controllers/BeneficiaryController.php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\Partner;
use Illuminate\Http\Request;

class BeneficiaryController extends Controller
{
    // Show the beneficiary registration form
    public function create()
    {
        $beneficiaries = Beneficiary::all();
        $partners = Partner::all();
        return view('admin.createBeneficiary', compact('beneficiaries', 'partners'));
    }

    // Handle the form submission
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'purok' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
        ]);

        // Create the new beneficiary record
        Beneficiary::create($request->all());

        // Redirect back with a success message
        return redirect()->route('admin.beneficiaries')->with('success', 'Beneficiary registered successfully.');
    }
}

