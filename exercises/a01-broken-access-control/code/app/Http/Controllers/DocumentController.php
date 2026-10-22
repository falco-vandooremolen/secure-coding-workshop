<?php

// INTENTIONALLY INSECURE - workshop training material. Never run or deploy.

namespace App\Http\Controllers;

use App\Models\Document;

class DocumentController extends Controller
{
    public function show(int $id)
    {
        $document = Document::find($id);

        return view('documents.show', ['document' => $document]);
    }

    public function destroy(int $id)
    {
        Document::find($id)->delete();

        return redirect('/documents');
    }
}
