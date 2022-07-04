<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubcategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
//    index method for read data
    public function index(){

//        Query Builder with one to one join
        $data=DB::table('subcategories')->leftJoin('categories','subcategories.category_id','categories.id')
            ->select('subcategories.*','categories.category_name')->get();

        $category=Category::all();//       Eloquent ORM

//        $category=DB::table('categories')->get();//        Query Builder
        return view('admin.category.subcategory.index',compact('data','category'));
    }

    //store method
    public function store(Request $request){
        $validated = $request->validate([
            'subcategory_name' => 'required|max:55',
        ]);
        //query builder
        $data=array();
        // query Builder
        // $data['category_id']=$request->category_id;
        // $data['subcategory_name']=$request->subcategory_name;
        // $data['subcat_slug']=Str::slug($request->subcategory_name, '-');

        // DB::table('subcategories')->insert($data);

        // Eloquent ORM
        Subcategory::insert([
            'category_id'=>$request->category_id,
            'subcategory_name'=>$request->subcategory_name,
            'subcat_slug'=>Str::slug($request->subcategory_name, '-')
        ]);
        $notification=array('messege' => 'Subcategory Inserted!', 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    //destroy category
    public function destroy($id){
        //query builder
        //DB::table('subcategories')->where('id',$id)->delete();

        //eloquent ORM
        $subcat=Subcategory::find($id);
        $subcat->delete();

        $notification=array('messege' => 'Subcategory Deleted!', 'alert-type' => 'success');
        return redirect()->back()->with($notification);

    }

    //edit subcategory
    public function edit($id){
        //Eloquent ORM
//        $data=Subcategory::find($id);
//        $category=DB::Category::all();

        //Query Builder
        $data=Subcategory::find($id);
        $category=DB::table('categories')->get();
        return view('admin.category.subcategory.edit',compact('data','category'));
    }

    //Update method
    public function update(Request $request){
        //Query Builder
//        $data=array();
//        $data['category_id']=$request->category_id;
//        $data['subcategory_name']=$request->subcategory_name;
//        $data['subcat_slug']=Str::slug($request->subcategory_name, '-');
//        DB::table('subcategories')->where('id',$request->id)->update($data);

        //Eloquent ORM
        $subcategory=Subcategory::where('id',$request->id)->first();
        $subcategory->update([
            'category_id'=>$request->category_id,
            'subcategory_name'=>$request->subcategory_name,
            'subcat_slug'=>Str::slug($request->subcategory_name, '-')
        ]);
        $notification=array('messege' => 'Sub-category Updated!', 'alert-type' => 'success');
        return redirect()->back()->with($notification);

    }
}
