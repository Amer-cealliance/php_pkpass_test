<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PKPass\PKPass;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function pkpass(Request $r)
    {
        // storage\certificates\pass_p12_Certificates.p12
        $pass = new PKPass(storage_path('certificates\pass_p12_certificates_with_password.p12'), '#passfile1234');

        // Pass content
        $data = [
            'description' => 'Demo pass',
            'formatVersion' => 1,
            'organizationName' => 'Flight Express',
            'passTypeIdentifier' => 'pass.com.scholica.flights', // Change this!
            'serialNumber' => '12345678',
            'teamIdentifier' => 'KN44X8ZLNC', // Change this!
            'boardingPass' => [
                'primaryFields' => [
                    [
                        'key' => 'origin',
                        'label' => 'San Francisco',
                        'value' => 'SFO',
                    ],
                    [
                        'key' => 'destination',
                        'label' => 'London',
                        'value' => 'LHR',
                    ],
                ],
                'secondaryFields' => [
                    [
                        'key' => 'gate',
                        'label' => 'Gate',
                        'value' => 'F12',
                    ],
                    [
                        'key' => 'date',
                        'label' => 'Departure date',
                        'value' => '07/11/2012 10:22',
                    ],
                ],
                'backFields' => [
                    [
                        'key' => 'passenger-name',
                        'label' => 'Passenger',
                        'value' => 'John Appleseed',
                    ],
                ],
                'transitType' => 'PKTransitTypeAir',
            ],
            'barcode' => [
                'format' => 'PKBarcodeFormatQR',
                'message' => 'Flight-GateF12-ID6643679AH7B',
                'messageEncoding' => 'iso-8859-1',
            ],
            'backgroundColor' => 'rgb(32,110,247)',
            'logoText' => 'Flight info',
            'relevantDate' => date('Y-m-d\TH:i:sP')
        ];
        $pass->setData($data);

        // Add files to the pass package
        $pass->addFile('images/icon.png');
        $pass->addFile('images/icon@2x.png');
        $pass->addFile('images/logo.png');

        // Create and output the pass

        $uuid = Str::uuid();
        $fileName =  $uuid . '.pkpass';
        $pkpassFile = $pass->create();
        Storage::put('pkpass/' .$fileName, $pkpassFile);
        return storage_path('app/pkpass/'.$fileName);
    }
}
