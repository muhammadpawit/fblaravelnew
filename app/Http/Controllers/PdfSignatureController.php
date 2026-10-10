<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfSignatureController extends Controller
{
    public function index()
    {
        return view('pdf-signature.index');
    }

    public function generatePdf(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'signature' => 'required|string', // base64 encoded image
        ]);

        $data = [
            'name' => $request->input('name'),
            'signature' => $request->input('signature'),
            'date' => now()->translatedFormat('d F Y')
        ];

        $pdf = Pdf::loadView('pdf-signature.pdf', $data);
        
        return $pdf->stream('dokumen-bertanda-tangan.pdf');
    }
}
