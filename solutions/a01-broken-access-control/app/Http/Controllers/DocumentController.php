<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Support\Facades\Gate;

class DocumentController extends Controller
{
    public function show(Document $document)
    {
        Gate::authorize('view', $document);

        return view('documents.show', ['document' => $document]);
    }

    public function destroy(Document $document)
    {
        Gate::authorize('delete', $document);
        $document->delete();

        return redirect('/documents');
    }
}
