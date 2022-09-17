<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Member;

class UtilController extends Controller
{
    public function home()
    {
        return view('utils.index');
    }

    public function getTableData(Request $request)
    {
        $data = [];

        $per_page = $request->input('per_page');

        $customers = Member::query();
        if($request->filled('search')) {
            $search = $request->input('search');
            $customers = $customers->where('firstname', 'LIKE', "%$search%")
                                    ->orWhere('lastname', 'LIKE', "%$search%")
                                    ->orWhere('email', 'LIKE', "%$search%")
                                    ->orWhere('mobile', 'LIKE', "%$search%");
        }
    
        $customers = $customers->orderBy('id', 'DESC')->paginate($per_page);
        $page = $request->get('page') ?? 1;
        if($customers->count() > 0) {
            $i = ($per_page * $page) - ($per_page - 1);
            foreach($customers as $customer) {
                $data['data'][] = [
                    $i,
                    $customer->firstname,
                    $customer->lastname,
                    $customer->email,
                    $customer->mobile
                ];
                $i++;
            }
        }
        $data['columns'] = [
            'S.No',
            'First Name',
            'Last Name',
            'Email',
            'Mobile'
        ];
        $data['current_page'] = $customers->currentPage();
        $data['has_pages'] = $customers->hasMorePages();
        $data['last_page'] = $customers->lastPage();
        $data['next_page_url'] = $customers->nextPageUrl();
        $data['prev_page_url'] = $customers->previousPageUrl();
        $data['current_page_url'] = $customers->url($page);
        return response($data);
    }
}
