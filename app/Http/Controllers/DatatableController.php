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

        $pageSize = $req->input('length');
        $start = $req->input('start');

        $result = $query->skip($start)->take($pageSize)->get()->toArray();
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

        return response()->json(['draw' => $req->input('draw'), 'data' => $result, 'recordsTotal' => $recordsTotal, 'recordsFiltered' => $recordsTotal]);
    }
}
