<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller;

class HomeController extends Controller
{


	public function index(){

		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.covid19india.org/data.json",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		));

		$response = curl_exec($curl);
		curl_close($curl);
		$arrResponse 	= json_decode($response);

		$STATE 		= [];
		$RECOVERED 	= [];
		$ACTIVE 	= [];
		$DEATH 		= [];
		$CONFIRMED 	= [];
		foreach ($arrResponse->statewise as $key => $value) {
			if($value->state !='Total' && $value->confirmed > 0){
				array_push($STATE, $value->state);
				array_push($CONFIRMED, (int)$value->confirmed);
				array_push($RECOVERED, (int)$value->recovered);
				array_push($ACTIVE, (int)$value->active);
				array_push($DEATH, (int)$value->deaths);
			}
		}
		$arrData[0]['name'] = 'CONFIRMED';
		$arrData[0]['data'] = $CONFIRMED;
		$arrData[1]['name'] = 'ACTIVE';
		$arrData[1]['data'] = $ACTIVE;
		$arrData[2]['name'] = 'RECOVERED';
		$arrData[2]['data'] = $RECOVERED;
		$arrData[3]['name'] = 'DEATH';
		$arrData[3]['data'] = $DEATH;
		// echo '<pre>';print_r(json_encode($arrData));exit();
		// $STATEWISE = array($RECOVERED,$ACTIVE,$DEATH,$CONFIRMED);


		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.covid19india.org/state_district_wise.json",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		));

		$response = curl_exec($curl);
		curl_close($curl);

		$arrStateResponse 		= json_decode($response);
		$arrResult['items'] 	= $arrResponse->statewise;
		$arrResult['statewise'] = json_encode($arrData);
		$arrResult['statename'] = json_encode($STATE);
		$arrResult['dateWise'] 	= $arrResponse->cases_time_series;
		$arrResult['state'] 	= $arrStateResponse;
		// echo '<pre>';print_r(json_encode($arrData));exit;
		// echo $response;exit;		

		return view('Website.index',$arrResult);
	}
}
    
