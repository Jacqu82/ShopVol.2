<?php


class handlingPolishGrammaticalCase
{

    /**
     * @param $sumProducts
     * @param $countUsers
     * @return string
     */
    public static function sumProductsAndCountUsers($sumProducts, $countUsers)
    {
        if (($countUsers == 1) && ($sumProducts == 1)) {
            return $countUsers . ' osoba kupiła ' . $sumProducts . ' sztukę';
        } elseif (($countUsers == 1) && ($sumProducts > 1 && $sumProducts < 5)) {
            return $countUsers . ' osoba kupiła ' . $sumProducts . ' sztuki';
        } elseif (($countUsers == 1) && ($sumProducts >= 5)) {
            return $countUsers . ' osoba kupiła ' . $sumProducts . ' sztuk';
        } elseif (($countUsers > 1 && $countUsers <= 4) && ($sumProducts > 1 && $sumProducts <= 4)) {
            return $countUsers . ' osoby kupiło ' . $sumProducts . ' sztuki';
        } elseif (($countUsers > 1 && $countUsers <= 4) && ($sumProducts >= 5)) {
            return $countUsers . ' osoby kupiło ' . $sumProducts . ' sztuk';
        } elseif (($countUsers >= 5) && ($sumProducts >= 5)) {
            return $countUsers . ' osób kupiło ' . $sumProducts . ' sztuk';
        } else {
            return '0 osób kupiło 0 sztuk';
        }
    }
}
