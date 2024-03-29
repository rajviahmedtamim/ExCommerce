<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Products;
use Illuminate\Http\Request;
//use DB;
use Illuminate\Support\Facades\DB;
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

            $products="";
              $query=DB::table('products')->leftJoin('categories','products.category_id','categories.id')
                  ->leftJoin('subcategories','products.subcategory_id','subcategories.id')
                  ->leftJoin('brands','products.brand_id','brands.id');
              if($request->category_id){
                  $query->where('products.category_id',$request->category_id);
              }
              if($request->subcategory_id){
                $query->where('products.subcategory_id',$request->subcategory_id);
              }
              if($request->brand_id){
                $query->where('products.brand_id',$request->brand_id);
              }
              if($request->status==1){
                $query->where('products.status',1);
              }
              if($request->status==0){
                $query->where('products.status',0);
              }

            $product=$query->select('products.*','categories.category_name','subcategories.subcategory_name','brands.brand_name')
                ->get();

            return DataTables::of($product)
                ->addIndexColumn()
                ->editColumn('thumbnail',function ($row) use($imgurl){
                    return '<img src="'.$imgurl.'/'.$row->thumbnail.'" height="30" width="30" >';
                })
                ->editColumn('featured',function ($row){
                    if($row->featured==1){
                        return '<a href="#" data-id="'.$row->id.'" class="deactive_featurd"><i class="fas fa-thumbs-down text-danger"></i> <span class="badge badge-success">active</span> </a>';
                    }
                    else{
                        return '<a href="#" data-id="'.$row->id.'" class="active_featurd"> <i class="fas fa-thumbs-up text-success"></i> <span class="badge badge-danger">deactive</span> </a>';
                    }
                })
                ->editColumn('today_deal',function ($row){
                    if($row->today_deal==1) {
                        return '<a href="#" data-id="'.$row->id.'" class="deactive_deal"><i class="fas fa-thumbs-down text-danger"></i> <span class="badge badge-success">active</span> </a>';
                    }
                    else{
                        return '<a href="#" data-id="'.$row->id.'" class="active_deal"><i class="fas fa-thumbs-up text-success"></i> <span class="badge badge-danger">deactive</span> </a>';
                    }
                })
                ->editColumn('status',function ($row){
                    if($row->status==1){
                        return '<a href="#" data-id="'.$row->id.'" class="deactive_status"><i class="fas fa-thumbs-down text-danger"></i> <span class="badge badge-success">active</span> </a>';
                    }
                    else{
                        return '<a href="#" data-id="'.$row->id.'" class="active_status"><i class="fas fa-thumbs-up text-danger"></i> <span class="badge badge-danger">deactive</span> </a>';
                    }
                })

                ->addColumn('action', function($row){
                    $actionbtn='
                        <a href="#" class="btn btn-info btn-sm edit"><i class="fas fa-eye"></i></a>
                        <a href="'.route('product.edit',[$row->id]).'" class="btn btn-primary btn-sm edit"><i class="fas fa-edit"></i></a>
                      	<a href="'.route('product.delete',[$row->id]).'" class="btn btn-danger btn-sm" id="delete"><i class="fas fa-trash"></i></a>';
                    return $actionbtn;
                })
                ->rawColumns(['action','category_name','subcategory_name','brand_name','thumbnail','featured','today_deal','status'])
                ->make(true);
        }
//        $category=Category::all(); --Model
        $category=DB::table('categories')->get(); //query builder
        $subcategory=DB::table('subcategories')->get();
        $brand=DB::table('brands')->get();
        return view('admin.product.index',compact('category','subcategory','brand'));
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
            'subcategory_id ' => 'required',
            'brand_id' => 'required',
            'unit' => 'required',
            'selling_price' => 'required',
            'color'=>'required',
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
        $data['product_slider']=$request->product_slider;
        $data['status']=$request->status;
        $data['admin_id']=Auth::id();
        $data['date']=date('d-m-y');
        $data['month']=date('F');
        //single image
        if($request->thumbnail){
            $thumbnail=$request->thumbnail;
            $photoname=$slug.'.'.$thumbnail->getClientOriginalExtension();
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

//    Product Edit
    public function edit($id){
        $product=DB::table('products')->where('id',$id)->first();
        //$product=Product::findorfail($id);
        $category=Category::all();
        $brand=Brand::all();
        $warehosue=DB::table('warehouses')->get();
        $pickup_point=DB::table('pickup_point')->get();
        //childcategory get_
        $childcategory=DB::table('childcategories')->where('category_id',$product->category_id)->get();
        // dd($childcategory);
        return view('admin.product.edit',compact('product','category','brand','warehosue','pickup_point','childcategory'));
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
    //Deactivate Today deals
    public function notdeal($id){
        DB::table('products')->where('id',$id)->update(['today_deal'=>0]);
        return response()->json('Product Today Deal Deactivated');
    }
    //active Today deals
    public function activedeal($id){
        DB::table('products')->where('id',$id)->update(['today_deal'=>1]);
        return response()->json('Product Today Deal is Activated');
    }
    //Deactive Status
    public function notstatus($id){
        DB::table('products')->where('id',$id)->update(['status'=>0]);
        return response()->json('Product Status Deactivated');
    }
    //Active Status
    public function activestatus($id){
        DB::table('products')->where('id',$id)->update(['status'=>1]);
        return response()->json('Product Status Activated');
    }

    //product delete
    public function destroy($id){
        DB::table('products')->where('id',$id)->delete();
        return response()->json('Successfully Deleted!');
    }

}
