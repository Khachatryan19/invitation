<?php

namespace App\Http\Controllers;

use App\Visitors\FileConverterVisitor;
use App\Services\FileManager\ExcelFileManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;
use Throwable;

class ReaderController extends Controller
{
    public function index(Request $request)
    {
        $path = Storage::path($request->file->getClientOriginalName());
        $request->file->storeAs(null, $request->file->getClientOriginalName());
        $file = new FileConverterVisitor(new ExcelFileManager($path));
        $model = $file->fileReader;
        $data = $model->reader();
        $model->convertToJson($data);
    }

    public function reader(Request $request)
    {
        $readerName = "{$request->file->extension()}Reader";

        try {
            $this->$readerName($request);
        } catch (Throwable $exception) {
        }
    }

    public function odsReader(Request $request)
    {
        $path = Storage::path('users.ods');
        $request->file->storeAs(null, 'users.ods');

        $users = (new FastExcel)->import($path)->toJson();

        Storage::disk()->put('users.json', $users);

        Storage::delete('users.ods');
    }
}
