<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Log, Validator, Exception, DB;

use App\User, App\Provider;

use App\Category, App\SubCategory;

use App\StaticPage;

class ApplicationController extends Controller {

	/**
     * @method static_pages()
     *
     * @uses used to display the static page for mobile devices
     *
     * @created Vidhya R
     *
     * @edited Vidhya R
     *
     * @param string $page_type 
     *
     * @return reidrect to the view page
     */

    public function static_pages($page_type = 'terms') {

        $page_details = StaticPage::where('type' , $page_type)->first();

        return view('static_pages.view')->with('page_details', $page_details);

    }  


    /**
     * @method static_pages_api()
     *
     * @uses used to get the pages
     *
     * @created Vidhya R 
     *
     * @edited Vidhya R
     *
     * @param - 
     *
     * @return JSON Response
     */

    public function static_pages_api(Request $request) {

        if($request->page_type) {

            $static_page = StaticPage::where('type' , $request->page_type)
                                ->where('status' , APPROVED)
                                ->select('id as page_id' , 'title' , 'description','type as page_type', 'status' , 'created_at' , 'updated_at')
                                ->first();

            $response_array = ['success' => true , 'data' => $static_page];

        } else {

            $static_pages = StaticPage::where('status' , APPROVED)->orderBy('id' , 'asc')
                                ->select('id as page_id' , 'title' , 'description','type as page_type', 'status' , 'created_at' , 'updated_at')
                                ->orderBy('title', 'asc')
                                ->get();

            $response_array = ['success' => true , 'data' => $static_pages ? $static_pages->toArray(): []];

        }

        return response()->json($response_array , 200);

    }

    /**
     * @method get_sub_categories()
     * 
     * @uses - Used to get subcategory list based on the selected category
     *
     * @created vidhya R
     *
     * @updated vidhya R
     * 
     * @param 
     *
     * @return JSON Response
     *
     */

    public function get_sub_categories(Request $request) {
        
        $category_id = $request->category_id;

        $sub_categories = SubCategory::where('category_id', '=', $category_id)
                            ->where('status' , APPROVED)
                            ->orderBy('name', 'asc')
                            ->get();

        $view_page = view('admin.others._sub_categories_list')->with('sub_categories' , $sub_categories)->render();

        $response_array = ['success' =>  true , 'view' => $view_page];

        return response()->json($response_array , 200);
    
    }

    /**
     * @method categories()
     *
     * @uses used get the categories lists
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param 
     *
     * @return response of details
     */

    public function categories(Request $request) {

        try {

            $categories = Category::CommonResponse()->where('categories.status' , APPROVED)->orderBy('name' , 'asc')->get();

            foreach ($categories as $key => $category_details) {

                $category_details->api_page_type = API_PAGE_TYPE_CATEGORY;

                $category_details->api_page_type_id = $category_details->category_id;
            }

            $response_array = ['success' => true, 'data' => $categories];

            return response()->json($response_array , 200);

        } catch(Exception $e) {

            $error = $e->getMessage();

            $response_array = ['success' =>false, 'error' => $error , 'error_code' => 101];
        }

    }

    /**
     * @method sub_categories()
     *
     * @uses used get the sub_categories lists
     *
     * @created Vithya R
     *
     * @updated Vithya R
     *
     * @param 
     *
     * @return response of details
     */

    public function sub_categories(Request $request) {

        try {

            $sub_categories = SubCategory::where('category_id', $request->category_id)->CommonResponse()->where('sub_categories.status' , APPROVED)->orderBy('sub_categories.name' , 'asc')->get();

            $response_array = ['success' => true, 'data' => $sub_categories];

            return $this->sendResponse("", "", $sub_categories);

        } catch(Exception $e) {

            return $this->sendError($e->getMessage(), $e->getCode());
        }

    }

}
