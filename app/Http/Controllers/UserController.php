<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        return view("index");
    }

    // Fetch data stored in session and return as JSON response
    public function fetchData()
    {
        $data = Session::get("data", []);
        //dd($data);

        return response()->json(["data" => $data]);
    }

     // Add new user data to session and store uploaded image
    public function add(Request $request)
    {
        $this->validate($request, [
            "name" => "required",
            "image" => "required|image",
            "address" => "required",
            "gender" => "required",
        ]);

        $imagePath = $request->file("image")->store("public/images");

        $imagePath = str_replace("public/", "", $imagePath);

        // Get the current ID from session, or set it to 0 if not found
        $currentId = (int) Session::get('id', 0);
        $newId = $currentId + 1;

        $data = Session::get("data", []);
        $data[] = [
            "id" => $newId,
            "name" => $request->input("name"),
            "image" => $imagePath,
            "address" => $request->input("address"),
            "gender" => $request->input("gender"),
        ];
        Session::put("data", $data);
        Session::put('id', $newId); 

        return response()->json(["success" => "Data added successfully!"]);
    }

    // Edit existing user data in session and update uploaded image if provided
    public function edit(Request $request)
    {
        $this->validate($request, [
            "id" => "required",
            "name" => "required",
            "image" => "image", 
            "address" => "required",
            "gender" => "required",
        ]);

        $data = Session::get("data", []);
        foreach ($data as &$entry) {
            if ($entry["id"] == $request->input("id")) {
                $entry["name"] = $request->input("name");
                if ($request->hasFile("image")) {
                    $imagePath = $request
                        ->file("image")
                        ->store("public/images");
                    $imagePath = str_replace("public/", "", $imagePath);
                    $entry["image"] = $imagePath;
                }
                $entry["address"] = $request->input("address");
                $entry["gender"] = $request->input("gender");
                break;
            }
        }
        Session::put("data", $data);

        return response()->json(["success" => "Data edited successfully!"]);
    }

    // Delete user data from session
    public function delete(Request $request)
    {
        $data = Session::get("data", []);
        $idToDelete = $request->input('id');

        $filteredData = array_filter($data, function ($entry) use ($idToDelete) {
            return $entry['id'] != $idToDelete;
        });

        $filteredData = array_values($filteredData);

        Session::put("data", $filteredData);

        return response()->json(["success" => "Data deleted successfully!"]);
    }


    // View user data from session based on provided ID
    public function view(Request $request)
    {
        $data = Session::get("data", []);
        $entry = array_values(
            array_filter($data, function ($entry) use ($request) {
                return $entry["id"] == $request->input("id");
            })
        );

        // Update image path
        if (!empty($entry)) {
            $entry[0]["image"] = url("storage/" . $entry[0]["image"]);
        }

        return response()->json(["data" => $entry]);
    }
    
    // Sort user data stored in session based on provided criteria
    public function sort(Request $request)
    {
        $data = Session::get("data", []);
        $sort_by = $request->input("sort_by");

        // Check if sort order is stored in session, initialize it if not
        $sort_order = Session::get("sort_order", "asc");

        // Toggle sort order between ascending and descending
        $sort_order = $sort_order == "asc" ? "desc" : "asc";
        $sorted_data = collect($data)
            ->sortBy($sort_by, SORT_NATURAL, $sort_order == "asc")
            ->values()
            ->all();

        // Update session with new sort order
        Session::put("sort_order", $sort_order);
        Session::put("data", $sorted_data);

        return response()->json([
            "data" => $sorted_data,
            "sort_order" => $sort_order,
        ]);
    }
}
