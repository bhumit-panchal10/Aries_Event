<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Product;
use App\Models\BlogProducts;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Image;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        try {
            $Blog = Blog::orderBy('blogId', 'desc')
                ->where(['iStatus' => 1, 'isDelete' => 0])
                ->paginate(25);

            return view('admin.blog.index', compact('Blog'));
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function createview(Request $request)
    {
        try {

            return view('admin.blog.add');
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'strTitle' => 'required',
                'strDescription' => 'required',
                'strPhoto' => 'required'
            ], [
                'strTitle.required' => 'The title field is required.',
                'strDescription.required' => 'The description field is required.',
                'strPhoto.required' => 'The photo field is required.'
            ]);

            $img = "";
            if ($request->hasFile('strPhoto')) {

                $root = $_SERVER['DOCUMENT_ROOT'];
                $image = $request->file('strPhoto');
                $imgName = time() . '_' . mt_rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
                // $destinationPath = $root . '/Blog/Thumbnail/';
                $destinationPath = FolderPath('/uploads/Blog/Thumbnail/');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $img = Image::make($image->getRealPath());

                $img->resize(540, 720, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $imgName);

                // $destinationpath = $root . '/Blog/';
                $destinationpath = FolderPath('/uploads/Blog/');
                $image->move($destinationpath, $imgName);
            }

            $Data = array(
                'strTitle' => $request->strTitle,
                'strSlug' => Str::slug($request->strTitle),
                'strDescription' => $request->strDescription,
                'strPhoto' => $imgName,
                'metaTitle' => $request->metaTitle,
                'date' => $request->date,
                'metaKeyword' => $request->metaKeyword,
                'metaDescription' => $request->metaDescription,
                'head' => $request->head,
                'body' => $request->body,
                'created_at' => date('Y-m-d H:i:s'),
                'strIP' => $request->ip()
            );
            DB::table('blog')->insert($Data);

            return redirect()->route('blog.index')->with('success', 'Blog Created Successfully.');
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function editview(Request $request, $id)
    {
        try {
            $data = Blog::where(['iStatus' => 1, 'isDelete' => 0, 'blogId' => $id])->first();

            return view('admin.blog.edit', compact('data'));
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'strTitle' => 'required',
                'strDescription' => 'required'
            ], [
                'strTitle.required' => 'The title field is required.',
                'strDescription.required' => 'The description field is required.'
            ]);


            $img = "";
            $root = $_SERVER['DOCUMENT_ROOT'];
            // $destinationPath = $root . '/Blog/Thumbnail/';
            // $fullImagePath = $root . '/Blog/';
            $destinationPath = FolderPath('/uploads/Blog/Thumbnail/');
            $fullImagePath = FolderPath('/uploads/Blog/');

            if ($request->hasFile('strPhoto')) {
                $image = $request->file('strPhoto');
                $imgName = time() . '_' . mt_rand(1000, 9999) . '.' . $image->getClientOriginalExtension();

                // Create directories if not exists
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                // Resize and save thumbnail
                $img = Image::make($image->getRealPath());

                $img->resize(540, 720, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $imgName);

                // Move full image to Product folder
                if (!file_exists($fullImagePath)) {
                    mkdir($fullImagePath, 0755, true);
                }
                $image->move($fullImagePath, $imgName);

                // Unlink old image
                $oldImg = $request->input('hiddenPhoto') ? $request->input('hiddenPhoto') : null;
                if ($oldImg && file_exists($fullImagePath . $oldImg)) {
                    unlink($destinationPath . $oldImg);
                    unlink($fullImagePath . $oldImg);
                }

                // Update the image name to save
                $img = $imgName;
            } else {
                $img = $request->input('hiddenPhoto'); // Keep the old image if no new image is uploaded
            }

            Blog::where(['iStatus' => 1, 'isDelete' => 0, 'blogId' => $id])
                ->update([
                    'strTitle' => $request->strTitle,
                    'strSlug' => Str::slug($request->strTitle),
                    'strDescription' => $request->strDescription,
                    'strPhoto' => $img,
                    'date' => $request->date,
                    'metaTitle' => $request->metaTitle,
                    'metaKeyword' => $request->metaKeyword,
                    'metaDescription' => $request->metaDescription,
                    'head' => $request->head,
                    'body' => $request->body,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            return redirect()->route('blog.index')->with('success', 'Blog Updated Successfully.');
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }


    public function delete(Request $request)
    {
        try {
            $delete = Blog::where(['iStatus' => 1, 'isDelete' => 0, 'blogId' => $request->blogId])->first();

            $root = $_SERVER['DOCUMENT_ROOT'];
            // $destinationpath = $root . '/Blog/';
            // $destinationpath1 = $root . '/Blog/Thumbnail/';
            $destinationpath = FolderPath('/uploads/Blog/');
            $destinationpath1 = FolderPath('/uploads/Blog/Thumbnail/');

            if (file_exists($destinationpath1 . $delete->strPhoto)) {
                unlink($destinationpath1 . $delete->strPhoto);
            }
            if (file_exists($destinationpath . $delete->strPhoto)) {
                unlink($destinationpath . $delete->strPhoto);
            }

            Blog::where(['iStatus' => 1, 'isDelete' => 0, 'blogId' => $request->blogId])->delete();

            return back()->with('success', 'Blog Deleted Successfully!.');
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }
}
