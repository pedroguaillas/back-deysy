<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Archivo;
use App\StaticClasses\DBStatics;

class ReportVentasController extends Controller
{
    public function report(Request $request)
    {
        $datestart = new \DateTime($request->get('datestart'));
        $dateend = new \DateTime($request->get('dateend'));

        $monthstart = $datestart->format('m');
        $monthend = $dateend->format('m');
        $year = $dateend->format('Y');

        $db = null;
        switch ((int)$year) {
            case 2021:
                $db = DBStatics::DB21;
                break;
            case 2022:
                $db = DBStatics::DB22;
                break;
            case 2023:
                $db = DBStatics::DB23;
                break;
            default:
                $db = DBStatics::DB;
                break;
        }

        // Deysy
        $fileventas = Archivo::select('fileventa')
            ->where([
                'cliente_auditwhole_ruc' => $request->get('ruc'),
                'anio' => $year
            ])
            ->whereBetween('mes', [$monthstart, $monthend])
            ->get();

        $newVentas = new \DOMDocument('1.0', 'ISO-8859-1');
        /* create the root element of the xml tree */
        $xmlRoot = $newVentas->createElement("ventas");
        /* append it to the document created */
        $xmlRoot = $newVentas->appendChild($xmlRoot);

        foreach ($fileventas as $fileventa) {
            if ($fileventa->fileventa !== null) {
                $xml = base64_decode($fileventa->fileventa);
                $document = new \DOMDocument();
                $document->loadXML($xml);

                $ventas = $document->getElementsByTagName('venta');

                foreach ($ventas as $venta) {

                    $node = $newVentas->importNode($venta, true);
                    $xmlRoot->appendChild($node);
                }
            }
        }

        return base64_encode($newVentas->saveXML());
    }
}
