var indexPuzzle;
var velicinaPuzzle;
var stanje_polja=[]; // stanje na ploci
var boja_polja=[]; // ako su dobro spojene pobojamo otoke
var mostovi = [];
var koordx, koordy;

$( document ).ready( function() {
	$( "#btn" ).on( "click", crtaj_polje );
	$("body").on( "contextmenu", function() { return false; } );

  $( "body" ).on( "mousedown", "td", "p", function(event){
		var trenutniMost = $("input[type='radio'][name='most']:checked").val();
		var smjerMosta = trenutniMost[1];
		var brojMostova = trenutniMost[0];
		var id = Number($(this).attr("id"));
		koordx = Math.floor(id/10);
		koordy = id%10;

		if( event.button === 0  ){
			crtaj_most( koordx, koordy, brojMostova, smjerMosta); //lijevi klik
			console.log("crtaj" + koordx+" " +koordy);
		}
		if( event.button === 2 ) {
			obrisi_most( koordx, koordy); //desni klik
			console.log("brisi"+koordx+" " +koordy);
		}

		if(detektiraj_pobjedu())
		{
			ispisi_cestitku();
		}
		console.log(mostovi);
		console.log(stanje_polja);
	});
});

function odabir_tezine(){
	var tezinaSelect = $("#tezina").val();
	var indexPuzzle = null;
  var n = puzzle.length; 
	for( let i = 0; i < n; i ++ ){
		if( puzzle[i].name === tezinaSelect ) {
			indexPuzzle = i;
			break;
		}
	}
	return indexPuzzle;
}

function crtaj_polje(){
	indexPuzzle = odabir_tezine();
	velicinaPuzzle = puzzle[indexPuzzle].size;
	var n = velicinaPuzzle;
	matrica_mostova();
	ispisi_mostove();

	$("#poljeIgre").html("");
	sessionStorage.clear();

	//definiramo novu tablicu igre
	var table = $("<table>");
	for( var r=0; r<n; r++ ){
		table.append("<tr>");
		for( var c=0; c<n; c++ )
		{
			table.append("<td id=" + (r).toString() + (c).toString() + ">" + stanje_polja[r][c]  + "</td>");
		}
		table.append("</tr>");
	}
	table.append("</table>");
    $("#poljeIgre").append(table);
	$("table").css("width","500px");
	var d = Math.floor( 500/ n);
	$("td").css("height",d + "px").css("width", d + "px");

	for( var r=0; r<n; r++ ){
		for( var c=0; c<n; c++ )
		{
			$("#"+(r).toString() + (c).toString()).css("background-color", boja_polja[r][c]);
		}
	}
	$("#cestitka").html("");
	sessionStorage.clear();
}

function matrica_mostova()
{
    var n = velicinaPuzzle;
	stanje_polja= [];
    for( var i=0; i<n; i++ ){
		stanje_polja[i] = [];
		boja_polja[i] = [];
        for ( j=0; j<n; j++)
        {
            stanje_polja[i][j] = " ";
			boja_polja[i][j] = "rgb(255,255,255)";
        }
    }
	var m = puzzle[indexPuzzle].island_row.length;
	var object;
	mostovi= [];
    for( var i=0; i<m; i++ ){
       	var r = puzzle[indexPuzzle].island_row[i];
		var c = puzzle[indexPuzzle].island_col[i];
        var brojPolja = puzzle[indexPuzzle].island_num[i];
		stanje_polja[r-1][c-1] = brojPolja; 
		object = {
			x : r-1,
			y : c-1,
			broj : 0
		};
		mostovi.push(object);
	}
	mostovi.sort(function(a, b) {
		return a.x - b.x;
	  });
}

function ispisi_mostove()
{
	$("#odabirMosta").html(""); 

	var mostovi = $("<p>");
	mostovi.append("Odaberi vrstu mosta:<br>");

	mostovi.append("<input type='radio' name='most' value = '1H'checked>" + "-" + "</input><br>" );
	mostovi.append("<input type='radio' name='most' value = '2H'>" + "=" + "</input><br>" );
	mostovi.append("<input type='radio' name='most' value = '1V'>"  + "|" + "</input><br>" );
	mostovi.append("<input type='radio' name='most' value = '2V'>" + "||" + "</input><br>" );

	mostovi.append("</p>");

	$("#odabirMosta").append(mostovi);
}

//--------------------------------------------------------------

function crtaj_most( a, b, brojMostova, smjerMosta)
{
	if( postoji_put( a, b, brojMostova, smjerMosta))
	{
		refresh_polje();	
	}
}

function obrisi_most( a, b)
{
	if( postoji_most( a, b))
	{
		refresh_polje();	
	}
}

function postoji_put( a, b, broj, smjerMosta)
{
	var n = velicinaPuzzle;
	var prvi=undefined, drugi=undefined;
	if ( smjerMosta === "H" && stanje_polja[koordx][koordy]===" ")
 	{
		for (let i=0; i<=b; i++)
		{
			if ( stanje_polja[a][b-i]!== " " && znak(stanje_polja[a][b-i])===0 )
			{
				prvi = b-i; 
				break;
			}
		}
		for (let i=0; i<n-b; i++)
		{
			if ( stanje_polja[a][b+i] !== " " && znak( stanje_polja[a][b+i] )=== 0)
			{
				drugi = b+i;
				break;
			}
		}
		if( prvi!== undefined && drugi!== undefined)
		{
			if( spoji_otoke(a, prvi, drugi, broj, smjerMosta)) 
			{ 
				obojaj_otoke(a, prvi, drugi, smjerMosta);
			}
			return 1;
		}
	}
	if ( smjerMosta ==="V" && stanje_polja[koordx][koordy]===" ")
	{
		for (let i=0; i<=a; i++)
		{
			if ( stanje_polja[a-i][b] !== " " && znak( stanje_polja[a-i][b] )=== 0 )
			{
				prvi = a-i; 
				break;
			}
		}
		for (let i=0; i<n-a; i++)
		{
			if ( stanje_polja[a+i][b] !== " " && znak( stanje_polja[a+i][b] )=== 0) 
			{
				drugi = a+i; 
				break;
			}
		}
		if( prvi!== undefined && drugi!== undefined)
		{
			if (spoji_otoke(b, prvi, drugi, broj, smjerMosta))
			{ 
				obojaj_otoke(b, prvi, drugi, smjerMosta);
			}
			return 1;
		}
	}
	return 0;
}

function postoji_most( a, b)
{
	var n = velicinaPuzzle;
	var prvi=undefined, drugi=undefined;
	if ( stanje_polja[a][b] === "-" || stanje_polja[a][b] === "=" )
 	{
		for (let i=0; i<=b; i++)
		{
			if ( stanje_polja[a][b-i]!== " " && znak(stanje_polja[a][b-i])===0 )
			{
				prvi = b-i; 
				break;
			}
		}
		for (let i=0; i<n-b; i++)
		{
			if ( stanje_polja[a][b+i] !== " " && znak( stanje_polja[a][b+i] )=== 0)
			{
				drugi = b+i;
				break;
			}
		}
		if( prvi!== undefined && drugi!== undefined )
		{
			if(ponisti_bojanje(a, prvi, drugi)) { return 1;}
		}
	}
	if (stanje_polja[a][b] === "|" || stanje_polja[a][b] === "||" )
	{
		for (let i=0; i<=a; i++)
		{
			if ( stanje_polja[a-i][b] !== " " && znak( stanje_polja[a-i][b] )=== 0 )
			{
				prvi = a-i; 
				break;
			}
		}
		for (let i=0; i<n-a; i++)
		{
			if ( stanje_polja[a+i][b] !== " " && znak( stanje_polja[a+i][b] )=== 0) 
			{
				drugi = a+i; 
				break;
			}
		}
		if( prvi!== undefined && drugi!== undefined )
		{
			if (ponisti_bojanje(b, prvi, drugi)){ return 1;}
			
		}
	}
return 0;
}

function spoji_otoke(b, prvi, drugi, broj, smjerMosta)
{
	var n = mostovi.length;

	if ( smjerMosta === "H" && broj=== "1" && stanje_polja[b][prvi+1] === " ")
	{
		for (let i = prvi+1; i < drugi; i++)
		{
			if(stanje_polja[b][i] !== " " ) {return 0;}
		}
		for (let i = prvi+1; i < drugi; i++) { stanje_polja[b][i] = "-" ;}
		for (let ind = 0; ind < n; ind++ )
		{
			if ( mostovi[ind].x === b && mostovi[ind].y === prvi){ mostovi[ind].broj +=1;}
			if ( mostovi[ind].x === b && mostovi[ind].y === drugi){ mostovi[ind].broj +=1;}
		}
		return 1;
	}
	else if(  smjerMosta === "H" && broj=== "2" && stanje_polja[b][prvi+1] === " ")
	{
		for (let i = prvi+1; i< drugi; i++)
		{ 
			if(stanje_polja[b][i] !== " " ){ return 0;}
		}
		for (let i = prvi+1; i< drugi; i++){ stanje_polja[b][i] = "=" ;}
		for ( let ind = 0; ind < n; ind++ )
		{
			if ( mostovi[ind].x === b && mostovi[ind].y === prvi){ mostovi[ind].broj +=2; }
			if ( mostovi[ind].x === b && mostovi[ind].y === drugi){ mostovi[ind].broj +=2;}
		}
		return 1;
	}
	else if (smjerMosta === "V" && broj === "1" && stanje_polja[prvi+1][b] === " ")
	{
		for (let i = prvi+1; i< drugi; i++)
		{ 
			if(stanje_polja[i][b] !== " " ){ return 0;}
		}
		for (let i = prvi+1; i< drugi; i++){ stanje_polja[i][b] = "|" ;}
		for ( let ind = 0; ind < n; ind++ )
		{
			if ( mostovi[ind].x === prvi && mostovi[ind].y === b){ mostovi[ind].broj +=1; }
			if ( mostovi[ind].x === drugi && mostovi[ind].y === b){ mostovi[ind].broj +=1;}
		}
		return 1;
	}
	else if (smjerMosta === "V" && broj === "2" && stanje_polja[prvi+1][b] === " ")
	{
		for (let i = prvi+1; i< drugi; i++)
		{ 
			if(stanje_polja[i][b] !== " " ){ return 0;}
		}
		for (let i = prvi+1; i< drugi; i++){ stanje_polja[i][b] = "||" ;}
		for ( let ind = 0; ind < n; ind++ )
		{
			if ( mostovi[ind].x === prvi && mostovi[ind].y === b){ mostovi[ind].broj +=2; }
			if ( mostovi[ind].x === drugi && mostovi[ind].y === b){ mostovi[ind].broj +=2;}
		}
		return 1;
	}
return 0;
}

function obrisi_put(b, prvi, drugi )
{
	var n = mostovi.length;
	if ( stanje_polja[koordx][koordy] === "-" ) 
	{
		for (let i = prvi+1; i < drugi; i++){ stanje_polja[b][i] = " " ;}
		for (let ind = 0; ind < n; ind++ )
		{
			if ( mostovi[ind].x === b && mostovi[ind].y === prvi){ mostovi[ind].broj -=1;}
			if ( mostovi[ind].x === b && mostovi[ind].y === drugi){ mostovi[ind].broj -=1;}
		}
	}
	else if(stanje_polja[koordx][koordy] === "=")
	{
		for (let i = prvi+1; i< drugi; i++){ stanje_polja[b][i] = " " ;}
		for ( let ind = 0; ind < n; ind++ )
		{

			if ( mostovi[ind].x === b && mostovi[ind].y === prvi){ mostovi[ind].broj -=2; }
			if ( mostovi[ind].x === b && mostovi[ind].y === drugi){ mostovi[ind].broj -=2;}
		}
	}
	else if (stanje_polja[koordx][koordy] === "|")
	{
		for ( let i = prvi+1; i< drugi; i++){ stanje_polja[i][b] = " " ;}
		for ( let ind = 0; ind < n; ind++ )
		{
			if ( mostovi[ind].x === prvi && mostovi[ind].y === b){ mostovi[ind].broj -=1; }
			if ( mostovi[ind].x === drugi && mostovi[ind].y === b){ mostovi[ind].broj -=1;}
		}
	}
	else if (stanje_polja[koordx][koordy] === "||")
	{
		for (let i = prvi+1; i< drugi; i++){ stanje_polja[i][b] = " " ;}
		for ( let ind = 0; ind < n; ind++ )
		{
			if ( mostovi[ind].x === prvi && mostovi[ind].y === b){ mostovi[ind].broj -=2; }
			if ( mostovi[ind].x === drugi && mostovi[ind].y === b){ mostovi[ind].broj -=2;}
		}
	}
}

function obojaj_otoke(b, prvi, drugi, smjerMosta)
{
	if(  smjerMosta === "H" )
	{
		if ( dobar_broj_mostova( stanje_polja[b][prvi], b, prvi)===1) { boja_polja[b][prvi] = "rgb(144,238,144)";}
		else { boja_polja[b][prvi] = "rgb(255,255,255)";}
		if ( dobar_broj_mostova( stanje_polja[b][drugi], b, drugi)===1) { boja_polja[b][drugi] = "rgb(144,238,144)";}
		else { boja_polja[b][drugi] = "rgb(255,255,255)";}
	}
	else if( smjerMosta === "V")
	{
		if ( dobar_broj_mostova( stanje_polja[prvi][b], prvi, b)===1){ boja_polja[prvi][b] = "rgb(144,238,144)";}	
		else { boja_polja[prvi][b] = "rgb(255,255,255)";}
		if ( dobar_broj_mostova( stanje_polja[drugi][b], drugi, b)===1){ boja_polja[drugi][b] = "rgb(144,238,144)";}			
		else { boja_polja[drugi][b] = "rgb(255,255,255)";}
	}
}

function ponisti_bojanje(b, prvi, drugi)
{
	if( stanje_polja[koordx][koordy] === "-" || stanje_polja[koordx][koordy] === "=" ) 
	{
		obrisi_put(b, prvi, drugi);

		if ( dobar_broj_mostova( stanje_polja[b][prvi], b, prvi) === 0 ){ boja_polja[b][prvi] = "rgb(255,255,255)";}
		else {boja_polja[b][prvi] = "rgb(144,238,144)";}

		if ( dobar_broj_mostova( stanje_polja[b][drugi], b, drugi) === 0){boja_polja[b][drugi] = "rgb(255,255,255)";}
		else{boja_polja[b][drugi] = "rgb(144,238,144)";}
		return 1;
	}
	else if( stanje_polja[koordx][koordy] === "|" || stanje_polja[koordx][koordy] === "||")
	{
		obrisi_put(b, prvi, drugi);

		if ( dobar_broj_mostova( stanje_polja[prvi][b], prvi, b) === 0){ boja_polja[prvi][b] = "rgb(255,255,255)";}
		else{boja_polja[prvi][b] = "rgb(144,238,144)";}	
		if ( dobar_broj_mostova( stanje_polja[drugi][b], drugi, b) === 0){boja_polja[drugi][b] = "rgb(255,255,255)";}
		else {boja_polja[drugi][b] = "rgb(144,238,144)";}	
		return 1;	
	}
	return 0;
}

function dobar_broj_mostova( broj_mostova, a, b )
{
	for (let i=0; i< mostovi.length; i++)
	{
		if ( mostovi[i].x === a && mostovi[i].y === b  && mostovi[i].broj === broj_mostova){return 1;}
	}
	return 0;
}

function detektiraj_pobjedu()
{
	for (let m = 0; m < mostovi.length; m++)
	{
		let i = mostovi[m].x ;
		let j= mostovi[m].y ;
		if ( mostovi[m].broj !== stanje_polja[i][j]){ return 0;}
	}
	return 1;
}
function ispisi_cestitku()
{
	$("#cestitka").html("");
	sessionStorage.clear();
	var p = $("<p>");
	p.append("Sagradili ste sve mostove, čestitamo!");
	$("#cestitka").append(p);
}

function refresh_polje()
{
	var n = velicinaPuzzle;
	$("#poljeIgre").html("");
	sessionStorage.clear();

	var table = $("<table>");
	for( var r=0; r<n; r++ ){
		table.append("<tr>");
		for( var c=0; c<n; c++ )
		{
			table.append("<td id=" + (r).toString() + (c).toString() + ">" + stanje_polja[r][c]  + "</td>");
		}
		table.append("</tr>");
	}

	table.append("</table>");
    $("#poljeIgre").append(table);
	$("table").css("width","500px");
	var d = Math.floor( 500/ n);
	$("td").css("height",d + "px").css("width", d + "px");

	for( var r=0; r<n; r++ ){
		for( var c=0; c<n; c++ )
		{
			$("#"+(r).toString() + (c).toString()).css("background-color", boja_polja[r][c]);
		}
	}
	$("#cestitka").html("");
	sessionStorage.clear();
}

function znak(a)
{
	if ( a === "|" || a === "||" || a === "=" || a === "-" ){return 1;}
	return 0;
}
