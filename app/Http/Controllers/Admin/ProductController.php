<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;
use DB;
use Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;


class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //index method
    public function index(Request $request){
        if ($request->ajax()) {
            $imgurl='public/files/product';
            $data= Products::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('thumbnail',function ($row) use($imgurl){
                    return '<img src="'.$imgurl.'/'.$row->thumbnail.'" height="30" width="30" >';
                })
                ->editColumn('category_name',function ($row){
                    return $row->category->category_name;
                })
                ->editColumn('subcategory_name',function ($row){
                    return $row->subcategory->subcategory_name;
                })
                ->editColumn('brand_name',function ($row){
                    return $row->brand->brand_name;
                })
                ->editColumn('featured',function ($row){
                    if($row->featured==1){
                        return '<a href="#" data-id="'.$row->id.'" class="deactive_featured"><i class="fa fa-thumbs-down text-danger"> <span class="badge-success">deactive</span> </i></a>';
                    }
                    else{
                        return '<a href="#" data-id="'.$row->id.'" class="active_featured"><i class="fa fa-thumbs-up text-success"> <span class="badge-danger">active</span></i></a>';
                    }
                })
                ->editColumn('today_deal',function ($row){
                    if($row->today_deal==1){
                        return '<a href=""><i class="fa fa-thumbs-down text-danger"> <span class="badge-success">active</span> </i></a>';
                    }
                    else{
                        return '<a href=""><i class="fa fa-thumbs-up text-success"> <span class="badge-danger">deactive</span></i></a>';
                    }
                })
                ->editColumn('status',function ($row){
                    if($row->status==1){
                        return '<a href=""><i class="fa fa-thumbs-down text-danger"> <span class="badge-success">active</span> </i></a>';
                    }
                    else{
                        return '<a href=""><i class="fa fa-thumbs-up text-success"> <span class="badge-danger">deactive</span></i></a>';
                    }
                })

                ->addColumn('action', function($row){
                    $actionbtn='
                        <a href="#" class="btn btn-info btn-sm edit"><i class="fas fa-edit"></i></a>
                        <a href="#" class="btn btn-primary btn-sm edit"><i class="fas fa-eye"></i></a>
                      	<a href="'.route('brand.delete',[$row->id]).'" class="btn btn-danger btn-sm"
                      	 id="delete"><i class="fas fa-trash"></i></a>';
                    return $actionbtn;
                })
                ->rawColumns(['action','category_name','subcategory_name','brand_name','thumbnail','featured','today_deal','status'])
                ->make(true);
        }
        return view('admin.product.index');
    }

    //product create page
    public function create()
    {
        $category=DB::table('categories')->get();  //Category::all();
        $brand=DB::table('brands')->get();          //Brand::all();
        $pickup_point=DB::table('pickup_point')->get();  //Pickuppoint::all();
        $warehosue=DB::table('warehouses')->get(); //Warehouse::all()
        return view('admin.product.create',compact('category','brand','pickup_point','warehosue'));
    }

    //product store method
    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required',
            'code' => 'required|unique:products|max:55',
//            'subcategory_id ' => 'required',
            'brand_id' => 'required',
            'unit' => 'required',
            'selling_price' => 'required',
            'description' => 'required',
        ]);

        //subcategory cal for category id
        $subcategory=DB::table('subcategories')->where('id',$request->subcategory_id )->first();
        $slug= Str::slug($request->name, '-');

        $data=array();
        $data['name']=$request->name;
        $data['slug']= $slug;
        $data['code']=$request->code;
        $data['category_id']=$subcategory->category_id;
        $data['subcategory_id']=$request->subcategory_id;
        $data['childcategory_id']=$request->childcategory_id;
        $data['brand_id']=$request->brand_id;
        $data['pickup_point_id']=$request->pickup_point_id;
        $data['unit']=$request->unit;
        $data['tags']=$request->tags;
        $data['purchase_price']=$request->purchase_price;
        $data['selling_price']=$request->selling_price;
        $data['discount_price']=$request->discount_price;
        $data['warehouse']=$request->warehouse;
        $data['stock_quantity']=$request->stock_quantity;
        $data['color']=$request->color;
        $data['size']=$request->size;
        $data['description']=$request->description;
        $data['video']=$request->video;
        $data['featured']=$request->featured;
        $data['today_deal']=$request->today_deal;
        $data['status']=$request->status;
        $data['admin_id']=Auth::id();
        $data['date']=date('d-m-y');
        $data['month']=date('F');
        //single image
        if($request->thumbnail){
            $thumbnail=$request->thumbnail;
            $photoname='slug'.'.'.$thumbnail->getClientOriginalExtension();
            //$photo->move('public/files/brand/',$photoname);  //Without image intervention
            Image::make($thumbnail)->resize(600,600)->save('public/files/product/'.$photoname); //image intervention
            $data['thumbnail']=$photoname;
        }
        //multiple Image
        $images = array();
        if($request->hasFile('images')){
            foreach ($request->File('images') as $key => $image){
                $imageName= hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
                Image::make($image)->resize(600,600)->save('public/files/product/'.$imageName);
                array_push($images, $imageName);
            }
            $data['images']=json_encode($images);
        }
        DB::table('products')->insert($data);
        $notification=array('messege' => 'Product Inserted!', 'alert-type' => 'success');
        return redirect()->back()->with($notification);
    }

    //not featured
    public function notfeatured($id){
        DB::table('products')->where('id',$id)->update(['featured'=>0]);
        return response()->json('Product Not Featured');
    }
    //active featured
    public function activefeatured($id){
        DB::table('products')->where('id',$id)->update(['featured'=>1]);
        return response()->json('Product Featured Activated');
    }
}
