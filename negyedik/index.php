<?php

/**
 * Webfejlesztő programozási feladat
 * 4. Feladat
 * Egy e_index(tomb) {} függvény készítése, mely visszatér egy
 * egyensúlyi index-szel, vagy ha nem létezik ilyen, -1-gyel. A tömb "nagyon nagy" is
 * lehet, hatékony megoldásra van szükség..
 *
 * * PHP version 7.3
 * 
 * @author Demeter Barnabás <birnebarna@gmail.com>
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */


$array = [ -7 , 1 , 5 , 2 , -4 , 3 , 0 ];

$result = e_index($array);
if($result === -1){
    echo "No indexes found!<br>";
}else
{
    echo "The following indexes were found.<br>";
    foreach ($result as $index) {
        echo "".$index."<br>";
    }
}

/*
    Két mutatóval elindulunk a két végpontból, és a tömb közepéig haladunk (páratlan szám esetén pont a középső elemen megállva páros esetén a saját térfél utolsó mezőjén megállva).
    Nevezzük ezt előkészítő fázisnak.
    Mindkét mutatóhoz tartozik 2-2 összegző szám, melyek a tőle balra, illetve jobbra lévő számok összegét tárolják.
    Az előkészítő fázis során csupán az egyik irányban összegzünk (bal index a baloldali elemeket számolja, jobb index a jobboldaliakat.)
    Majd középen "információt cserélnek", és feltöltik a másik számlálót is.

    Ezután egyszerre léptetjük az mutatókat az másik térfél felé. Az elhagyott mezőket mozzáadjuk a megfelelő számlálóhoz. Amire megérkeztünk, azt pedig kivonjuk a másikból.
    Eközben pedig képésenként pontos adatunk van a két irány összegéről, és megnézzük, hogy azonosak-e.

    * @param int[] $array - Input array
    * @return int[] | int - Array of indexes or if there is non, it returns -1
*/
function e_index( $array)
{
    $leftIndex_leftSum = 0;
    $leftIndex_rightSum = 0;
    $rightIndex_leftSum = 0;
    $rightIndex_rightSum = 0;

    $leftIndex = 1;                                 //*Az indexeket a 2. lépcsőről indítom, hogy az elhagyott mező ellenőrzésekor ne mutassunk a tömbön kívülre.
    $rightIndex = count($array) - 2;                    

    $indexArray = [];                                                                                        //megoldás tároló

    for ($i=0; $i < floor( (count($array) - 1 ) / 2 ) ; $i++) {                                              //előkészítő fázis
        $leftIndex_leftSum += $array[$leftIndex - 1];
        $rightIndex_rightSum += $array[$rightIndex + 1];

        $leftIndex++;
        $rightIndex--;
    }

    $leftIndex_rightSum = $rightIndex_rightSum;                                                             //információ csere
    $rightIndex_leftSum = $leftIndex_leftSum;


    for ($i=$leftIndex; $i <= count($array) ; $i++) {                                                       //másik térfélen lépkedés
    
        if($leftIndex_leftSum === $leftIndex_rightSum || $rightIndex_leftSum === $rightIndex_rightSum){     //azonosság ellenőrzés
            array_push($indexArray, $i-1);
        }

        if($i < count($array))
        {
            $leftIndex_leftSum += $array[$leftIndex -1];                                                    //Elhagyott elemek hozzáadása
            $rightIndex_rightSum += $array[$rightIndex + 1];

            $leftIndex_rightSum -= $array[$leftIndex];                                                      //Új mezőre lépés levonása
            $rightIndex_leftSum -= $array[$rightIndex];

            $leftIndex++;
            $rightIndex--;
        }
    }

    return (count($indexArray) == 0)?-1:$indexArray;
}



?>