<?php


class handlingPolishGrammaticalCase
{
    /**
     * @param $sumProducts
     * @param $sumUsers
     */
    public static function sumProductsAndSumUsers($sumProducts, $sumUsers)
    {
        if (($sumUsers == 1) && ($sumProducts == 1)) {
            echo $sumUsers . ' osoba kupiła ' . $sumProducts . ' sztukę';
        } elseif (($sumUsers == 1) && ($sumProducts > 1 && $sumProducts < 5)) {
            echo $sumUsers . ' osoba kupiła ' . $sumProducts . ' sztuki';
        } elseif (($sumUsers == 1) && ($sumProducts >= 5)) {
            echo $sumUsers . ' osoba kupiła ' . $sumProducts . ' sztuk';
        } elseif (($sumUsers > 1 && $sumUsers <= 4) && ($sumProducts > 1 && $sumProducts <= 4)) {
            echo $sumUsers . ' osoby kupiło ' . $sumProducts . ' sztuki';
        } elseif (($sumUsers > 1 && $sumUsers <= 4) && ($sumProducts >= 5)) {
            echo $sumUsers . ' osoby kupiło ' . $sumProducts . ' sztuk';
        } elseif (($sumUsers >= 5) && ($sumProducts >= 5)) {
            echo $sumUsers . ' osób kupiło ' . $sumProducts . ' sztuk';
        } else {
            echo '0 osób kupiło 0 sztuk';
        }
    }
}
