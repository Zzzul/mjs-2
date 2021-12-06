<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\StoreSupplierRequest;
use App\Http\Requests\Contact\UpdateSupplierRequest;
use App\Models\Contact\Supplier;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return Datatables::of(Supplier::orderByDesc('updated_at'))
                ->addIndexColumn()
                ->addColumn('action', 'contact.supplier._action')
                ->addColumn('alamat', function ($row) {
                    return Str::limit($row->alamat, 40);
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d m Y H:i');
                })
                ->addColumn('updated_at', function ($row) {
                    return $row->updated_at->format('d m Y H:i');
                })
                ->toJson();
        }

        return view('contact.supplier.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contact.supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSupplierRequest $request)
    {
        Supplier::create($request->validated());

        Alert::success('Tambah Data', 'Berhasil');

        return redirect()->route('supplier.index');
    }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  \App\Models\Contact\Supplier  $supplier
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show(Supplier $supplier)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contact\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        return view('contact.supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contact\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->validated());

        Alert::success('Update Data', 'Berhasil');

        return redirect()->route('supplier.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contact\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        Alert::success('Hapus Data', 'Berhasil');

        return redirect()->route('supplier.index');
    }
}