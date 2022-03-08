<?php

/**
 * Webfejlesztő programozási feladat
 * 3. Feladat
 * Hány kerek prím van 1 millió alatt?
 *
 * * PHP version 7.3
 * 
 * @author Demeter Barnabás <birnebarna@gmail.com>
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */


$foundNumbers = array();             //Ebben a tömbben tároljuk a megoldásokat.

getAllNumbers(); 

if(count($foundNumbers) === 0){
    echo "No prime numbers found!<br>";
}else
{
    echo "The following numbers were found.<br>";
    foreach ($foundNumbers as $number) {
        echo "".$number."<br>";
    }
}

function getAllNumbers()
{
    global $foundNumbers;

    array_push($foundNumbers, "2");                                     //A páros számok nem prímek, ezért a párosával való léptetés érdekében a kivétel 2-est külön eltároljuk.

    for ($i=3; $i < 1000000; $i+=2) { 
        if(isPrime($i) && !in_array( $i, $foundNumbers) ) {             //Ha a szám prím és még nem tároltuk el
            mixDigits($i."");                                           //akkor nézzük csak meg a kerek prím tulajdonságát
        }
    }
}


/*
    Kerek prím tulajdonság vizsgálata.
    Elkészíti a szám összes forgatását és megvizsgálja, hogy mindegyik prím-e.
    Ha igen, akkor eltárolja őket a $foundNumbers tömbbe.

    * @param string $string - Number string
*/
function mixDigits(string $string):void
{
    global $foundNumbers;

    $isAllPrime = true;
    $mixedNumbers = [];                                                 //Ideiglenes tároló, melyben az egyes forgatásokat tároljuk.

    array_push($mixedNumbers, $string);
    $shiftedString = shift($string);

    while($shiftedString != $string && $isAllPrime){                    //Addig forgatjuk, amíg önmagát nem kapjuk vissza vagy kiderül, hogy találtunk nem prímet.
        array_push($mixedNumbers, $shiftedString);
        if(!isPrime($shiftedString)) {
            $isAllPrime = false;
        }
        $shiftedString = shift($shiftedString);
    }

    if($isAllPrime){                                                   //Minden forgatás prím, ezért az ideiglenes tárolóból átmásoljuk a végleges megoldások közé.
        foreach ($mixedNumbers as $number) {
            array_push($foundNumbers, $number);
        }
    }
}

/*
    Számjegyek forgatása.
    Az szám első számjegyét a szám végére tolja.

    * @param string $string - Number string
    * @return string $string - 
*/
function shift(string $string):string
{
    if(strlen($string) > 1){
        $firstDigit = substr($string, 0, 1);
        $rest = substr($string, 1);
        return $rest.$firstDigit;
    }else{
        return $string;
    }
}


/*
    Megmondja egy számról, hogy prím szám-e.

    * @param string $string - Number string
    * @return bool 
*/
function isPrime(string $string):bool
{
    $number = intval( $string );
    if($number < 2) {
        return false;
    }

    for ($i = 2; $i <= sqrt($number); $i++){        
        if($number % $i == 0){
            return false;
        }
    }

    return true;
}




?>