<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'vendor/autoload.php';
class CustomLanguageToFontImplementation extends \Mpdf\Language\LanguageToFont
{

    public function getLanguageOptions($llcc, $adobeCJK)
    {
        if ($llcc === 'th') {
            return [false, 'frutiger']; // for thai language, font is not core suitable and the font is Frutiger
        }

        return parent::getLanguageOptions($llcc, $adobeCJK);
    }

}
class PdfMaker extends CI_Controller {
 
	public function index(){
		$html = '<p style="font-family: solaimanlipi;"> সিকিউরিটি ভেরিফিকেশন ফরম </p>';
		
		$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
		$fontDirs = $defaultConfig['fontDir'];

		$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
		$fontData = $defaultFontConfig['fontdata'];

		
		$mpdf = new \Mpdf\Mpdf([
			'format'=>'A4',
			'orientation'=>'P',
			'languageToFont' => new CustomLanguageToFontImplementation(),
			'fontDir' => array_merge($fontDirs, ['/fonts']),
			'fontdata' => $fontData + [
				'solaimanlipi' => [
					'R' => 'SolaimanLipi.ttf',
					'useOTL' => 0xFF,
				]
			],
			'default_font' => 'solaimanlipi'
		]);
		$fileName = uniqid().'.pdf';
		//$mpdf->defaultheaderline = 0;
		//$mpdf->defaultfooterline = 0;
		$mpdf->SetHeader('Document Title|Center Text|{PAGENO}');
		$mpdf->SetFooter('Document Title');
		$stylesheet = file_get_contents(FCPATH.'css/example.css'); // external css
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->WriteHTML($html,2);
		$mpdf->Output($fileName,'D'); 
	}
   
	
}