<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubCategoryRequest;
use App\Models\Admin\SubCategory;
use App\Models\Admin\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class SubCategoryController extends Controller
{
    public function sub_category(Request $request)
    {
        if ($request->ajax()) {
            $data = SubCategory::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = '<div class="action__buttons">';
                    $btn = $btn . '<a href="' . route('admin.sub_category.edit', $data->id) . '" class="btn-action" title="Edit"><i class="fas fa-pen-to-square"></i></a>';

                    if ($data->Status == 1) {
                        $btn = $btn . '<a href="' . route('admin.sub_category.inactive', $data->id) . '" class="btn-action" title="Inactive"><i class="fas fa-toggle-on"></i></a>';
                    } else {
                        $btn = $btn . '<a href="' . route('admin.sub_category.active', $data->id) . '" class="btn-action" title="Active"><i class="fas fa-toggle-off"></i></a>';
                    }

                    $btn = $btn . '<a href="' . route('admin.sub_category.delete', $data->id) . '" class="btn-action delete" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                    $btn = $btn . '</div>';
                    return $btn;
                })
                ->editColumn('category', function ($data) {
                    return $data->category->en_Category_Name;
                })
                ->editColumn('SubCategory_Name', function ($data) {
                    return $data->en_SubCategory_Name;
                })
                ->editColumn('SubCategory_Slug', function ($data) {
                    return $data->en_SubCategory_Slug;
                })
                ->editColumn('Status', function ($data) {
                    if ($data->Status == 1) {
                        $active = "Active";
                        return '<span class="status active">' . $active . '</span>';
                    } else {
                        $active = "Inactive";
                        return '<span class="status blocked">' . $active . '</span>';
                    }
                })
                ->editColumn('Description', function ($data) {
                    return Str::limit($data->en_Description, 10);
                })
                ->editColumn('SubCategory_Icon', function ($data) {
                    return $data->SubCategory_Icon;
                })
                ->rawColumns(['action', 'SubCategory_Name', 'SubCategory_Slug', 'Status', 'Description'])
                ->make(true);
        }
        $data['title'] = __('SubCategory List');
        return view('admin.pages.sub_category.index', $data);
    }

    public function sub_categoryCreate()
    {
        $data["category"] = Category::get();
        $data['title'] = __('SubCategory Create');
        return view('admin.pages.sub_category.create', $data);
    }

    public function sub_categoryStore(SubCategoryRequest $request)
    {
        $sub_category = SubCategory::create([
            'category_id' => $request->category_id,
            'en_SubCategory_Name' => $request->en_sub_category_name,
            'en_Description' => $request->en_description,
            'en_SubCategory_Slug' => $this->slugify($request->en_sub_category_name),
            'fr_SubCategory_Name' => $request->fr_sub_category_name,
            'fr_Description' => $request->fr_description,
            'fr_SubCategory_Slug' => $this->slugify($request->fr_sub_category_name),

            'SubCategory_Icon' => $request->icon_class,
        ]);
        if ($sub_category) {
            return redirect()->route('admin.sub_category')->with('success', __('Successfully Stored !'));
        }
        return redirect()->route('admin.sub_category')->with('error', __('Does not Stored !'));
    }
    public function sub_categoryEdit($id)
    {

        $data["category"] = Category::get();
        $data['title'] = __('SubCategory Create');
        $data['edit'] = SubCategory::where('id', $id)->first();
        return view('admin.pages.sub_category.edit', $data);
    }
    public function sub_categoryUpdate(Request $request)
    {
        $id = $request->id;
        $cat = SubCategory::whereid($id)->first();
        $update = $cat->update([
            'category_id' => $request->category_id,
            'en_SubCategory_Name' => is_null($request->en_sub_category_name) ? $cat->en_SubCategory_Name : $request->en_sub_category_name,
            'en_Description' => is_null($request->en_description) ? $cat->en_Description : $request->en_description,
            'en_SubCategory_Slug' => is_null($request->en_sub_category_name) ? $cat->en_SubCategory_Slug : $this->slugify($request->en_sub_category_name),
            'fr_SubCategory_Name' => is_null($request->fr_sub_category_name) ? $cat->fr_SubCategory_Name : $request->fr_sub_category_name,
            'fr_Description' => is_null($request->fr_description) ? $cat->fr_Description : $request->fr_description,
            'fr_SubCategory_Slug' => is_null($request->fr_sub_category_name) ? $cat->fr_SubCategory_Slug : $this->slugify($request->fr_sub_category_name),

            'SubCategory_Icon' => is_null($request->icon_class) ? null : $request->icon_class,
        ]);
        if ($update) {
            return redirect()->route('admin.sub_category')->with('success', __('Successfully Updated!'));
        }
        return redirect()->back()->with('error', __('Does not Update  !'));
    }
    public function sub_categoryActive($id)
    {
        $inactive = SubCategory::find($id)->update(['Status' => 1]);
        if ($inactive) {
            return redirect()->route('admin.sub_category')->with('success', __('Successfully Active !'));
        }
        return redirect()->route('admin.sub_category')->with('success', __('Does not Updated!'));
    }
    public function sub_categoryInactive($id)
    {
        $inactive = SubCategory::find($id)->update(['Status' => 0]);
        if ($inactive) {
            return redirect()->route('admin.sub_category')->with('success', __('Successfully Inactive !'));
        }
        return redirect()->route('admin.sub_category')->with('success', __('Does not Updated !'));
    }

    public function sub_categoryDelete($id)
    {
        $delete = SubCategory::Where('id', $id)->delete();
        if ($delete) {
            return redirect()->route('admin.sub_category')->with('success', __('Successfully Deleted !'));
        }
        return redirect()->route('admin.sub_category')->with('error', __('Does Not Delete!'));
    }

    public function slugify($text)
    {
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate divider
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }
}
