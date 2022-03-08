#Demeter Barnabás
#birnebarna@gmail.com
#Webfejlesztő programozási feladat
#1. Feladat
#1. Hány lehetséges út van egy N magas háromszögben?

# Lehetséges utak száma: 2^N / 2

#2. Írjon parancssori programot a maximális összegű út összegének megtalálása. A
#programnak "nagyon nagy" (pl. 50 magas) háromszögekre is hamar (1 sec alatt,
#körül) le kell tudnia futni egy mai átlagos gépen. A program a standard
#bemeneten kapja a háromszöget, soronként a sorokat (sorhatároló karakter a \n), az
#elemek egyetlen szóközzel vannak elválasztva. A program kimenete egyetlen szám
#legyen, a maximális összegű út összege.


$graphHeight = 50													#a gráf magassága
$filePath = ".\triangle.txt"										#input file útvonala

[int[]]$leafDistance = @(0) * $graphHeight							#levelek távolsága a gyökértől kezdetben 0, soronként frissítjük, ahogy haladunk a fában

#Ha nem létezik az input fájl, generál egyet
Function GenerateInputFile{

	if(!(Test-Path $filePath))
	{
		New-Item $filePath
	}

}

#Teszt fájl adatokkal való feltöltése
#Random 0-9 közötti számokkal feltölti az input fájlt
Function RandomizeInput{

	$triangleContent = ""
	for($i = 0; $i -lt $graphHeight; $i++){ 

		for($j = 0; $j -le $i; $j++){ 

			$randomNumber = Get-Random -Minimum 0 -Maximum 9
			$separator = ","
			if($i -eq($j)){$separator = ""}
			$triangleContent = -join($triangleContent, $randomNumber, $separator)
		}
		$triangleContent = -join($triangleContent, "`r`n")
	}
	Set-Content $filePath $triangleContent

}

#Kiolvossa az input fájl tartalmát
Function ReadInput{

	$content = Get-Content $filePath 
	return $content

}


#Max függvény
Function Max{

	Param($left,$right)
	$right = [int]$right[0]

	if($left -eq $null){
		return $right		
	}

	$left = [int]$left[0]

	if($left -lt $right) {return $right}
	else {return $left}

}


GenerateInputFile
RandomizeInput
$content = ReadInput								

$rows = $content -split "`r`n"								#felbontjuk a tesztadatokat sorokra

for($i = 0; $i -lt $graphHeight; $i++){						#fentről lefelé feldolgozzuk a sorokat

	$nodes = $rows[$i] -split ","							#az adott sorbeli csomópontok listája

	[int[]]$newDistances = @(0) * $nodes.length				#az adott sorbeli csomópontok távolsága a gyökérhez képest
															#csak ideiglenes tárolásra használjuk, amíg a jelen sort fel nem dolgozzuk
	
	for($j = $nodes.length-1; $j -ge 0; $j--){				#jobbról balra vizsgáljuk a csomópontokat
						
		if($i -eq 0){										#ha i=0 => ez maga gyökér
			$newDistances[$j] = [int]$nodes[$j]

		}else {
			if($leafDistance[$j] -eq 0){					#ezen a ponton $leafDistance-ben még az előző sor (szülők) út költsége található
															#ez egy új levél, amihez eddig még nem nyúltunk. Vizuálisan a fa jobb alul terebélyesedett ki.
				$newDistances[$j] = [int]$leafDistance[$j-1] + [int]$nodes[$j]		#Az egyetlen szülője  (relatívan bal felső) és a csomópont értékéből kapjuk meg az ide vezető utat.
				
			}else{	

				$distanceOnLeftParent = [int]$leafDistance[$j-1] + [int]$nodes[$j]	#bal szülőjétől származó út hossza
				$distanceOnRightParent = [int]$leafDistance[$j] + [int]$nodes[$j]	#jobb szülőjétől származó út hossza

				$maxDistance = Max $distanceOnLeftParent $distanceOnRightParent		#a kettő közül a leghosszab utat kiválasztjuk
				$newDistances[$j] = $maxDistance									#a csomópont költségét beállítjuk
			}
		}
	}	
																				#a $newDistances-ben minden csomóponthoz maximális költséget tárolunk
	for($j = $nodes.length-1; $j -ge 0; $j--){
		$leafDistance[$j] = $newDistances[$j]										#ezt átmásoljuk a gyökérbe vezető tárolóba
																					#így a következő sor innen veszi majd a szülők költségeit
	}
}

$max = 0;
for($i = 0; $i -lt $leafDistance.length; $i++){
		if($leafDistance[$j] -gt $max){
			$max = $leafDistance[$j]
		}
}

Write-Host "The maximum distance is "$max







