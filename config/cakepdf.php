<?php
return [
    'CakePdf' => [
        'engine' => 'App.ReporteTcpdf',
        'margin' => [
            'top' => 20,
            'right' => 15,
            'bottom' => 25,
            'left' => 15,
        ],
        'orientation' => 'P',
        'pageSize' => 'LETTER',
        'download' => true,
    ]
];
