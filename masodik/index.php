<?php

set_time_limit(500000);

/**
 * Webfejlesztő programozási feladat
 * 2. Feladat
 * Írj PHP nyelven egy függvényt, mely a paraméterben kapott stringből felismeri a
 * leghosszabb ismétlődő stringrészletet és ezzel tér vissza.
 *
 * * PHP version 7.3
 * 
 * @author Demeter Barnabás <birnebarna@gmail.com>
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

    $text = "Lorem 123456789012 ipsum dolor sit amet, consectetur adipiscing elit. Sed imperdiet orci ut erat elementum mollis.
    Morbi quis dolor ipsum, sit amet sagittis urna. Sed ullamcorper lacinia elit nec mollis. Vestibulum nec
    magna velit, ac porttitor nisl. Nulla in venenatis risus. Phasellus a nisl nulla, id condimentum nibh.
    Donec eget eros id eros cursus viverra. Maecenas et adipiscing velit. Proin porta placerat vulputate.
    Duis varius erat eget lorem pellentesque rhoncus. Mauris vehicula interdum mauris, in sodales metus
    aliquam ac. Etiam ac porttitor quam. 123456789012 Vivamus venenatis quam molestie mi ultrices a laoreet enim
    faucibus.";

    
    $lowerCaseText = strtolower($text);

    $result = recurrenceCheck($lowerCaseText);
    if(!$result){
        echo "The text is empty!<br>";
    }else
    {
        if(count($result)===0){
            echo "There are no repeating parts!<br>";
        }else{
            echo "The longest snippet is <bold>".strlen($result[0]["snippet"])."<bold> characters long.<br><br>";
            foreach ($result as $snippet) {
                echo "\"".$snippet["snippet"]."\"<br>";
            }
        }
    }

    /*
        Az alapötlet az, hogy a teljes szövegméret feléig minden lehetséges méretű szövegrész megvizsgálunk, hogy tartozik-e hozzá pár.
        Azonos szövegrészletek kereséséhez két index mutatót használunk. Az első előfordulás kezdetét $i jelzi, a másodikét pedig $j jelöli.
        Ez önmagában egy hármas egymásba ágyazott ciklus lenne, valamit még létszükséges trükközni.

        - Egyezéskor megállunk és lokálisan tovább vizsgáljuk, hogy esetleg nagyobb méret esetén is fennáll-e az egyezés. 
        Pl $i + $mostaniméret + 1...2...3... = $j + $mostaniméret + 1...2...3... stb.
        Egy-egy összehasonlításnál megkapjuk maximális egyezés méretét, azaz, ha hozzávesszük még a következő betűt, akkor már nem egyezik.
        Ezáltal a méretnövelés felgyorsul, főleg a kezdeti szakaszban.

        - Az előző lépés garantálja, hogy $i léptetését (új részlet választás) csak egyszer kell végig futtatni, 
        hiszen a szöveg eleji részeket kimaxoltuk, és már nem lesz egyezés hiába bővítjük.
        Pl "aaa"-nak nincs párja, akkor "aaab"-nek végképp nem lesz.
        Ezért ezeket a részeket elég egyszer vizsgálni.

        * @param string $text - Input text
        * @return array - The longest snippets that appear at least twice in the text
        *     @type int firstIndex - Index of the first occurance
        *     @type int secondIndex - Index of the second occurance
        *     @type string snippet - Text of the snippet
    */
    function recurrenceCheck(string $text)
    {
        $longestLengthChecked = 1;  //A keresési szöveghossz, ilyen hosszúságú szövegrészleteknek keressük a párját
        $foundLength = -1;          //Ez a leghosszabb ismétlődő szó hossza, amit eddig találtunk
        $currentSnippet = "";       //Ez a szövegrészlet, amit kiválasztottunk és épp a párját kutatjuk
        $longestFound = [];         //Ez megoldást tartalmazó szövegrész tömb (lehet, hogy több azonos hosszú ismétlő rész lesz)

        if(strlen($text) == 0){
            return false;
        }

        $i = 0; 
        while ( $longestLengthChecked <= floor(strlen($text) / 2)) {                                                //Csak a totál szöveghossz fele lehet a keresendő hossz, hogy 2 részt még össze tudjunk hasonlítani.
                                                        
            while($i < strlen($text))                                                                               //$i a keresett szövegrészlet első előfordulásának indexe
            {      
                $currentSnippet = substr($text, $i, $longestLengthChecked);                                         //Keresett szövegrészlet kiválasztása

                for ($j = $i + strlen($currentSnippet); $j < strlen($text) - strlen($currentSnippet) ; $j++) {      //$j a potenciális második előfordulás indexe

                    $isMatch = true;                                                                                //Egyezik-e az első és második szövegrészlet az indexeken

                    for ($snippetIterator=0; $snippetIterator < strlen($currentSnippet) && $isMatch; $snippetIterator++) { 
                        if($currentSnippet[$snippetIterator] != $text[$j + $snippetIterator]) {                     //Karakterenként vizsgáljuk az egyezést. 
                            $isMatch = false;                                                                       //Különbözőség esetén megúszunk pár iterációt.
                        }
                    }

                    if($isMatch){
                        for ($k = strlen($currentSnippet); $k < (strlen($text) - $j -1 - strlen($currentSnippet)); $k++) { 
                            if($text[$i + $k] === $text[$j + $k]){                                                  //A szövegrész utáni karakterek megegyeznek mindkét előfordulásnál
                                $longestLengthChecked++;                                                            //Nagyobb méretű az egyezés, mint az eredetileg válaszott rész, ennek megfelelően növeljük a méretet.
                            }else{
                                $k = strlen($text);                                                                 //Egyezés ennél a karakternél már nem áll fenn. Kiugrunk a vizsgálatból.
                            }
                        }

                        if($foundLength < $longestLengthChecked){                                                   //Ha a vizsgált méret nőtt
                            $foundLength = $longestLengthChecked;                                                   //akkor ezt elkönyveljük, mint valós találati méret
                            $longestFound = [];                                                                     //Ürítjük a megoldás tarolót, mert kiderült, hogy a régi elemeknél van nagyobb.
                        }

                        array_push($longestFound, array(                                                            //Az új méretű ismétlődő elemet tároljuk
                            "firstIndex" => $i,
                            "secondIndex" => $j,
                            "snippet" => substr($text, $i, $foundLength)
                        ));

                        $j = strlen($text);                                                                         //Második index keresésből kilépés
                    }

                }

                $i++;                                                                                               //Első index léptetése (hogy új szövegrészt válasszunk)
            }
            $longestLengthChecked++;                                                                                //Keresési méret növelése
        }

        return $longestFound;
    }

?>