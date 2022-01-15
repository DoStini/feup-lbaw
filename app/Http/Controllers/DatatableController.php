<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DatatableController extends Controller {

    protected string $name;

    private function escapeColumnName($columnName) {
        return preg_replace('/[^A-Za-z0-9_]+/', '', $columnName);
    }

    public function get(Request $req, $filter) {
        $recordsTotal = $filter->count();

        $query = $filter;
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

            $columnName = $this->escapeColumnName($columnName);

            switch ($value["dir"]) {
                case "asc":
                    $query->orderBy($columnName); // SQL INJECTION
                    break;
                case "desc":
                    $query->orderByDesc($columnName); // SQL INJECTION
                    break;
            }
        }

        $searchTerm = $req->input("search")["value"];
        foreach ($req->input("columns") as $col) {
            $colName = $col["name"];
            if ($col["searchable"] && $colName !== null) {
                $colName = $this->escapeColumnName($colName);

                if($searchTerm !== null) {
                    $query->orWhereRaw('"' . $colName . '"::text ILIKE ?', ["%" . $searchTerm . "%"]);
                }

                if($col["search"] !== null && $col["search"]["value"] != null) {
                    $query->orWhereRaw('"' . $colName . '"::text ILIKE ?', ["%" . $col["search"]["value"] . "%"]);
                }
            }
        }
        $recordsFiltered = $query->count();

        $pageSize = $req->input('length');
        $start = $req->input('start');

        $result;
        try {
            $result = $query->skip($start)->take($pageSize)->get()->toArray();
        } catch(\Exception $e) {
            return response()->json(['draw' => intval($req->input('draw')),'error' => "Couldn't retrieve data"],422);
        }

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
