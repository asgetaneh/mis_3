<?php
  use App\Models\OwnershipTranslation;
  use App\Models\SuppervisionTranslation;


/**
 * Write code on Method
 *
 * @return response()
 */
if (! function_exists('gettrans')) {
    function gettransOwn($locale, $idd)
    {
    	$reservations = OwnershipTranslation::where('ownership_id', '=', $idd)
                           ->where('locale', '=', $locale)
                           ->get();
 
        return $reservations;
    }
    function gettransSuppervision($locale, $idd)
    {
      $reservations = SuppervisionTranslation::where('suppervision_id', '=', $idd)
                           ->where('locale', '=', $locale)
                           ->get();
 
        return $reservations;
    }
}