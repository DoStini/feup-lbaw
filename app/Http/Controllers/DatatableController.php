<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DatatableController extends Controller {

    protected string $name;

    protected function filter() {
        return DB::table('user_shopper');
    }

    public function get(Request $req) {
        $recordsTotal = $this->filter()->count();

        $query = $this->filter();
        foreach ($req->input("order") as $value) {
            $columnName = null;
            $orderable = null;
            foreach ($req->input("columns") as $col) {
                if ($col["data"] === $value["column"]) {
                    $columnName = $col["name"];
                    $orderable = $col["orderable"];
                    break;
                }
            }

            if ($columnName === null || !$orderable) {
                continue;
            }

            switch ($value["dir"]) {
                case "asc":
                    $query->orderBy($columnName); // SQL INJECTION
                    break;
                case "desc":
                    $query->orderByDesc($columnName); // SQL INJECTION
                    break;
            }
        }

        $i = 0;
        $searchTerm = $req->input("search")["value"];
        if ($searchTerm !== null) {
            foreach ($req->input("columns") as $col) {
                $colName = $col["name"];
                if ($col["searchable"] && $colName !== null) {
                    if ($i === 0) {
                        $query->whereRaw('"' . $colName . '"::text ILIKE ?', ["%" . $searchTerm . "%"]);
                    } else {
                        $query->orWhereRaw('"' . $colName . '"::text ILIKE ?', ["%" . $searchTerm . "%"]);
                    }
                }

                $i++;
            }
        }
        $recordsFiltered = $query->count();

        $pageSize = $req->input('length');
        $start = $req->input('start');

        DB::enableQueryLog();

        $result = $query->skip($start)->take($pageSize)->get()->toArray();

        // dd(DB::getQueryLog());
        $result = array_map(function ($entry) use ($req) {
            $arr = [];

            foreach ($req->input("columns") as $col) {
                $colName = $col["name"];

                if (property_exists($entry, $col["name"])) {
                    $arr[$col["data"]] = $entry->$colName;
                } else {
                    $arr[$col["data"]] = "N/A";
                }
            }

            return $arr;
        }, $result);

        return response()->json(['draw' => intval($req->input('draw')), 'data' => $result, 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsFiltered]);
    }
}
