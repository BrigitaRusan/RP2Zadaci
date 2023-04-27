<?php
session_start();
procesiraj_login();

//------------------------------------------

function procesiraj_login( )
{
  if( isset( $_POST['ime'] ))
  {
    if( preg_match( '/^[a-zA-Z]{3,20}$/', $_POST['ime'] ) )
    {
      $_SESSION['ime'] = $_POST['ime'];
      odabir_levela();
      igraj_igru();
    }
  }
  if ( !isset( $_SESSION['ime'] ) )
  {
    header( 'Location:sokoban.php');
    exit;
  }
}

//----------------odabir razine igre----------------------------------------------
function odabir_levela()
{
  if ( isset( $_POST['razina_igre']) )
  {
    $razina = (int)$_POST['razina_igre'];
    $_SESSION['razina']= $razina;
  }
  if( isset( $_SESSION['razina'] ) )
  {
    $razina = $_SESSION['razina'];
    if( $razina == 1 ){ init_level1();}
    else if( $razina == 2 ){  init_level2(); }
  }
}


//---------reakcije u formi-------obrada klika----------------------------------------

if ( isset( $_POST['gumb'] ) )
{
    if( $_POST['gumb']=="izvrsi" ) //-------------radio-------------------------
    {
        if( !empty($_POST['akcija']) )
        {
            $opcija=(int)$_POST['akcija'];
        }
        if ( $opcija==1 )
        {
          odabir_levela();
          igraj_igru();
        }
        if ( $opcija==2 )
        {
            $koord = explode(",", $_POST['koordinate']);
            $x = (int)$koord[0];
            $y = (int)$koord[1];
            obrisi_dijamant( $x, $y );

            if( pobjeda() )
            {
              ispisi_cestitku();
              session_unset();
              session_destroy();
              if ( isset( $_POST['button'] ) )
              {
                header( 'Location:sokoban.php');
              }
            }
            else
            {
              igraj_igru();
            }
        }
    }
    else if( !empty( $_POST['gumb'] ) ) //-----gumbici--------------------------
    {
        $smjer=$_POST['gumb'];
        if ( moguc_pomak( $smjer ) )
        {
          $pom = pomakni_na_gumb( $smjer );
          $_SESSION['pomak'] = $pom;
        }
        //--------provjerava pobjedu-------------
        if( pobjeda() )
        {
          ispisi_cestitku();
          session_unset();
          session_destroy();
          if ( isset( $_POST['button'] ) )
          {
            header( 'Location:sokoban.php');
          }
        }
        else
        {
          igraj_igru();
        }
    }
}

//------------inicijalizacija levela----------------------------

function init_level1()
{
    $_SESSION['redaka']=9;  $_SESSION['stupaca']=8;

    $ploca=[];
    $ploca[]=array('white', 'white', 'blue', 'blue', 'blue', 'blue', 'blue', 'white');
    $ploca[]=array('blue', 'blue', 'blue', 'white', 'white', 'white', 'blue', 'white');
    $ploca[]=array('blue', 'yellow', 'white', 'white', 'white', 'white', 'blue', 'white');
    $ploca[]=array('blue', 'blue', 'blue', 'white', 'white', 'yellow', 'blue', 'white');
    $ploca[]=array('blue', 'yellow', 'blue', 'blue', 'white', 'white', 'blue', 'white');
    $ploca[]=array('blue', 'white', 'blue', 'white', 'yellow', 'white', 'blue', 'blue');
    $ploca[]=array('blue', 'white', 'white', 'yellow', 'white', 'white', 'yellow', 'blue');
    $ploca[]=array('blue', 'white', 'white', 'white', 'yellow', 'white', 'white', 'blue');
    $ploca[]=array('blue', 'blue', 'blue', 'blue', 'blue', 'blue', 'blue', 'blue', 'blue');

    $_SESSION['ploca']=$ploca;

    $stanje=[];;
    $stanje[]=array('N', 'N' , 'N', 'N', 'N', 'N', 'N', 'N');
    $stanje[]=array('N', 'N' , 'N', 'N', 'N', 'N', 'N', 'N');
    $stanje[]=array('N', 'N' , 'X', 'D', 'N', 'N', 'N', 'N');
    $stanje[]=array('N', 'N' , 'N', 'N', 'D', 'N', 'N', 'N');
    $stanje[]=array('N', 'N' , 'N', 'N', 'D', 'N', 'N', 'N');
    $stanje[]=array('N', 'N' , 'N', 'N', 'N', 'N', 'N', 'N');
    $stanje[]=array('N', 'D' , 'N', 'D', 'D', 'D', 'N', 'N');
    $stanje[]=array('N', 'N' , 'N', 'N', 'N', 'N', 'N', 'N');
    $stanje[]=array('N', 'N' , 'N', 'N', 'N', 'N', 'N', 'N');

    $_SESSION['stanje']=$stanje;

    $_SESSION['igrac']=array(2,2);
    $_SESSION['dijamanti']= array("3,4", "4,5", "5,5", "7,2", "7,4", "7,5", "7,6" );
    $_SESSION['pomak']=0;

}

function init_level2()
{
  $_SESSION['redaka']=11;  $_SESSION['stupaca']=22;

  $ploca=[];
  $ploca[]=array('white', 'white', 'white', 'white', 'blue', 'blue', 'blue', 'blue', 'blue', 'white', 'white',
                'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white');
  $ploca[]=array('white', 'white', 'white', 'white', 'blue', 'white', 'white', 'white', 'blue', 'white', 'white',
                'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white');
  $ploca[]=array('white', 'white', 'white', 'white', 'blue', 'white', 'white', 'white', 'blue', 'white', 'white',
                'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white');
  $ploca[]=array('white', 'white', 'blue', 'blue', 'blue', 'white', 'white', 'white', 'blue', 'blue', 'blue',
                'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white');
  $ploca[]=array('white', 'white', 'blue', 'white', 'white', 'white', 'white', 'white', 'white', 'white', 'blue',
                'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white');
  $ploca[]=array('blue', 'blue', 'blue', 'white', 'blue', 'white', 'blue', 'blue', 'blue', 'white', 'blue',
                'white', 'white', 'white', 'white', 'white', 'blue', 'blue', 'blue', 'blue', 'blue', 'blue');
  $ploca[]=array('blue', 'white', 'white', 'white', 'blue', 'white', 'blue', 'blue', 'blue', 'white', 'blue',
                'blue', 'blue', 'blue', 'blue', 'blue', 'blue', 'white', 'white', 'yellow', 'yellow', 'blue');
  $ploca[]=array('blue', 'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white',
                'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white', 'yellow', 'yellow', 'blue');
  $ploca[]=array('blue', 'blue', 'blue', 'blue', 'blue', 'white', 'blue', 'blue', 'blue', 'blue', 'white',
                'blue', 'white', 'blue', 'blue', 'blue', 'blue', 'white', 'white', 'yellow', 'yellow', 'blue');
  $ploca[]=array('white', 'white', 'white', 'white', 'blue', 'white', 'white', 'white', 'while', 'white', 'white',
                'blue', 'blue', 'blue', 'white', 'white', 'blue', 'blue', 'blue', 'blue', 'blue', 'blue');
  $ploca[]=array('white', 'white', 'white', 'white', 'blue', 'blue', 'blue', 'blue', 'blue', 'blue', 'blue',
                'blue', 'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white', 'white');

  $_SESSION['ploca']=$ploca;

  $stanje=[];;
  $stanje[]=array('N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N');
  $stanje[]=array('N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N');
  $stanje[]=array('N', 'N', 'N', 'N', 'N', 'D', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N');
  $stanje[]=array('N', 'N', 'N', 'N', 'N', 'N', 'N', 'D', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N');
  $stanje[]=array('N', 'N', 'N', 'N', 'N', 'D', 'N', 'N', 'D', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N');
  $stanje[]=array('N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N');
  $stanje[]=array('N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N');
  $stanje[]=array('N', 'N', 'D', 'N', 'N', 'D', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N');
  $stanje[]=array('N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'X', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N');
  $stanje[]=array('N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N');
  $stanje[]=array('N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N');

  $_SESSION['stanje']=$stanje;

  $_SESSION['igrac']=array(8,12);
  $_SESSION['dijamanti']= array("3,6", "4,8", "5,6", "5,9", "8,3", "8,6" );
  $_SESSION['pomak']=0;


}
//--------------ispis igre------------------------------------------

function igraj_igru()
{
  ?>
  <!DOCTYPE html>
  <html>
    <head>
      <meta charset="utf-8">
      <title>Sokoban</title>
      <style media="screen">
        table,tr,td{ border: 1px solid black; border-collapse: collapse;}
        td{background-color: white; width: 40px; height: 40px;}

        #oba{border: 1px solid blue; width:1000px; height: 600px;}
        #lijevi{width:50%;float:left;}
        #desni{width:50%; float:left;}
        #desni1{height: 150px;}
        #desni2{height: 150px;}

      </style>
    </head>
    <body>
<div id="oba">
      <h1>Sokoban</h1>
      <?php echo '<p>'. "Igrač ". $_SESSION['ime']. " je dosad napravio/la ". $_SESSION['pomak']." pomaka.". '</p>' ?>


<div id="lijevi">
      <table>
          <?php
          $redovi=$_SESSION['redaka'];
          $stupci=$_SESSION['stupaca'];

          for( $red=0; $red < $redovi; $red++ )
          {
            echo '<tr>';
            for( $stup=0; $stup < $stupci; $stup++ )
            {

              if( $_SESSION['stanje'][$red][$stup]=='X')
              {
                echo '<td style="background-color:'.$_SESSION['ploca'][$red][$stup].';"><img src="smajlic.jpeg" width=100%></td>';
              }
              else if( $_SESSION['stanje'][$red][$stup]=='D')
              {
                echo '<td style="background-color:'.$_SESSION['ploca'][$red][$stup].';"><img src="dijamantic.jpeg" width=100%></td>';
              }
              else if( $_SESSION['stanje'][$red][$stup]=='N' )
              {
                echo '<td style="background-color:'.$_SESSION['ploca'][$red][$stup].';"></td>';
              }
            }
            echo '</tr>';
          }
           ?>
      </table>
  </div>
<div id="desni">
  <form action="<?php echo htmlentities($_SERVER['PHP_SELF'])?>" method="post">
    <div id="desni1">
        Pomakni igrača za jedno mjesto u smjeru:<br>
      <button type="submit" name="gumb" value="gore" style="position:relative; left:80px; top:20px">Gore</button>
      <button type="submit" name="gumb" value="lijevo" style="position:relative; right:30px; top:60px">Lijevo</button>
      <button type="submit" name="gumb" value="desno" style="position:relative; left:30px; top:60px">Desno</button>
      <button type="submit" name="gumb" value="dolje" style="position:relative; right:80px; top:100px">Dolje</button>
    </div>
    <br>
  <div id="desni2">
    Ili odaberi željenu akciju: <br><br>
    <input type="radio" name="akcija" value="1" checked> Pokreni sve ispočetka</input><br>
    <input type="radio" name="akcija" value="2"> Obriši dijamant s pozicije (red, stupac) =
    <select class="koord" name="koordinate">
      <?php
      foreach ($_SESSION['dijamanti'] as $k)
      {
        echo'<option value="'.$k.'">'."(".$k.")".'</option>';
      }
       ?>
    </select></input><br><br>
    <button type="submit" name="gumb" value="izvrsi">Izvrši akciju!</button>
    </div>
  </form>
</div>
</div>
    </body>
  </html>
  <?php
}
//----------provjera pomaka--------------------------------------------

function moguc_pomak($smjer)
{
    $igracx = (int)$_SESSION['igrac'][0];
    $igracy = (int)$_SESSION['igrac'][1];

    if( $smjer == 'gore' )
    {
        if( $_SESSION['ploca'][$igracx-1][$igracy]=='blue' ){ return 0; }
        if( $_SESSION['stanje'][$igracx-1][$igracy]=="D" )
        {
            if( $_SESSION['ploca'][$igracx-2][$igracy]=='blue' || $_SESSION['stanje'][$igracx-2][$igracy]=='D' )
            {
                return 0;
            }
        }
    }
    else if ( $smjer == 'dolje' )
    {
        if( $_SESSION['ploca'][$igracx+1][$igracy]=='blue' ){ return 0; }
        if( $_SESSION['stanje'][$igracx+1][$igracy]=="D" )
        {
            if( $_SESSION['ploca'][$igracx+2][$igracy]=='blue' || $_SESSION['stanje'][$igracx+2][$igracy]=='D' )
            {
                return 0;
            }
        }
    }
    else if ( $smjer == 'lijevo' )
    {
        if( $_SESSION['ploca'][$igracx][$igracy-1]=='blue' ){ return 0; }
        if( $_SESSION['stanje'][$igracx][$igracy-1]=="D" )
        {
            if ($_SESSION['ploca'][$igracx][$igracy-2]=='blue' || $_SESSION['stanje'][$igracx][$igracy-2]=='D' )
            {
                return 0;
            }
        }
    }
    else if( $smjer == 'desno' )
    {
        if( $_SESSION['ploca'][$igracx][$igracy+1]=='blue' ){ return 0; }
        if( $_SESSION['stanje'][$igracx][$igracy+1]=="D" )
        {
            if( $_SESSION['ploca'][$igracx][$igracy+2]=='blue' || $_SESSION['stanje'][$igracx][$igracy+2]=='D' )
            {
                return 0;
            }
        }
    }
    return 1;
}
//---------pomaci----------------------------------------------------------

function pomakni_na_gumb($smjer)
{
    $igracx = $_SESSION['igrac'][0];
    $igracy = $_SESSION['igrac'][1];

    if( $smjer == 'gore' && $_SESSION['stanje'][$igracx-1][$igracy]=='D' )
    {
        $_SESSION['stanje'][$igracx-2][$igracy]='D';
        $_SESSION['stanje'][$igracx-1][$igracy]='X';
        $_SESSION['stanje'][$igracx][$igracy]='N';

        $_SESSION['igrac'][0]=$igracx-1;
        $_SESSION['igrac'][1]=$igracy;
        $dijam = $_SESSION['dijamanti'];

        for( $i=0; $i< count($dijam); $i++ )
        {
            $jedan = explode( ",", $dijam[$i] );
            $a = (int)$jedan[0]-1;
            $b = (int)$jedan[1]-1;
            if( $a == $igracx-1 && $b == $igracy )
            {

                $jedan[0] = strval( $a );
                $jedan[1] = strval( $b+1 );
            }
            $dijam[$i] = implode (",", $jedan );
        }
        $_SESSION['dijamanti'] = $dijam;
    }
    if( $smjer == 'dolje' && $_SESSION['stanje'][$igracx+1][$igracy]=='D' )
    {
        $_SESSION['stanje'][$igracx+2][$igracy]='D';
        $_SESSION['stanje'][$igracx+1][$igracy]='X';
        $_SESSION['stanje'][$igracx][$igracy]='N';

        $_SESSION['igrac'][0]=$igracx+1;
        $_SESSION['igrac'][1]=$igracy;
        $dijam = $_SESSION['dijamanti'];

        for( $i=0; $i< count($dijam); $i++ )
        {
            $jedan = explode(",", $dijam[$i]);
            $a = (int)$jedan[0]-1;
            $b = (int)$jedan[1]-1;
            if( $a == $igracx+1 && $b == $igracy )
            {
                $jedan[0] = strval( $a+2 );
                $jedan[1] = strval( $b+1 );
            }
            $dijam[$i] = implode(",", $jedan);
        }
        $_SESSION['dijamanti'] = $dijam;
    }
    if( $smjer == 'lijevo' && $_SESSION['stanje'][$igracx][$igracy-1]=='D' )
    {
        $_SESSION['stanje'][$igracx][$igracy-2]='D';
        $_SESSION['stanje'][$igracx][$igracy-1]='X';
        $_SESSION['stanje'][$igracx][$igracy]='N';

        $_SESSION['igrac'][0]=$igracx;
        $_SESSION['igrac'][1]=$igracy-1;
        $dijam = $_SESSION['dijamanti'];

        for( $i=0; $i< count($dijam); $i++ )
        {
            $jedan = explode(",", $dijam[$i]);
            $a = (int)$jedan[0]-1;
            $b = (int)$jedan[1]-1;
            if( $a == $igracx && $b == $igracy-1 )
            {
                $jedan[0] = strval( $a+1 );
                $jedan[1] = strval( $b );
            }
            $dijam[$i] = implode(",", $jedan);
        }
        $_SESSION['dijamanti'] = $dijam;
    }
    if( $smjer == 'desno' && $_SESSION['stanje'][$igracx][$igracy+1]=='D' )
    {
        $_SESSION['stanje'][$igracx][$igracy+2]='D';
        $_SESSION['stanje'][$igracx][$igracy+1]='X';
        $_SESSION['stanje'][$igracx][$igracy]='N';

        $_SESSION['igrac'][0]=$igracx;
        $_SESSION['igrac'][1]=$igracy+1;
        $dijam = $_SESSION['dijamanti'];

        for( $i=0; $i< count($dijam); $i++ )
        {
            $jedan = explode(",", $dijam[$i]);
            $a = (int)$jedan[0] - 1;
            $b = (int)$jedan[1] - 1;
            if( $a == $igracx && $b == $igracy+1 )
            {
                $jedan[0] = strval( $a+1 );
                $jedan[1] = strval( $b+2 );
            }

            $dijam[$i] = implode(",", $jedan);
        }
        $_SESSION['dijamanti'] = $dijam;
    }
    if( $smjer == 'gore' && $_SESSION['stanje'][$igracx-1][$igracy]!='D')
    {
        $_SESSION['stanje'][$igracx-1][$igracy]='X';
        $_SESSION['stanje'][$igracx][$igracy]='N';

        $_SESSION['igrac'][0]=$igracx-1;
        $_SESSION['igrac'][1]=$igracy;
    }
    if( $smjer == 'dolje' && $_SESSION['stanje'][$igracx+1][$igracy]!='D' )
    {
        $_SESSION['stanje'][$igracx+1][$igracy]='X';
        $_SESSION['stanje'][$igracx][$igracy]='N';

        $_SESSION['igrac'][0]=$igracx+1;
        $_SESSION['igrac'][1]=$igracy;
    }
    if( $smjer == 'lijevo' && $_SESSION['stanje'][$igracx][$igracy-1]!='D' )
    {
        $_SESSION['stanje'][$igracx][$igracy-1]='X';
        $_SESSION['stanje'][$igracx][$igracy]='N';

        $_SESSION['igrac'][0]=$igracx;
        $_SESSION['igrac'][1]=$igracy-1;
    }
    if( $smjer == 'desno' && $_SESSION['stanje'][$igracx][$igracy+1]!='D' )
    {
        $_SESSION['stanje'][$igracx][$igracy+1]='X';
        $_SESSION['stanje'][$igracx][$igracy]='N';

        $_SESSION['igrac'][0]=$igracx;
        $_SESSION['igrac'][1]=$igracy+1;
    }

    $pomak = (int) $_SESSION['pomak'];
    ++$pomak;
    $_SESSION['pomak'] = $pomak;
    return $pomak;
}

function obrisi_dijamant( $x, $y)
{
  $stanje = $_SESSION['stanje'];
  $ploca = $_SESSION['ploca'];

  $dijam = $_SESSION['dijamanti'];
  for( $i=0; $i< count($dijam); $i++ )
  {
      $jedan = explode(",", $dijam[$i]);
      $a = (int)$jedan[0];
      $b = (int)$jedan[1];
      if( $a == $x && $b == $y )
      {
        unset( $dijam[$i] );
        $dijam = array_values($dijam);
      }
    }
  $_SESSION['dijamanti'] = $dijam;

  $_SESSION['stanje'][$x-1][$y-1]='N';
}

//---------------- pobjeda i čestitka---------------------------------------
function pobjeda()
{
  $stanje = $_SESSION['stanje'];
  $ploca = $_SESSION['ploca'];
  $red = $_SESSION['redaka'];
  $stup = $_SESSION['stupaca'];
  $flag = 1;

  for ( $i = 0; $i < $red; $i++ )
  {
    for( $j = 0; $j < $stup; $j++)
    {
      if ($ploca[$i][$j] != 'yellow' &&  $stanje[$i][$j] == 'D')
      {
        $flag = 0;
      }
    }
  }
  return $flag;
}

function ispisi_cestitku()
{
  ?>
  <!DOCTYPE html>
  <html>
    <head>
      <meta charset="utf-8">
      <title>Pobjeda</title>
    </head>
    <body>
      <h1> <?php echo htmlentities($_SESSION['ime'])?>, čestitke na pobjedi!</h1>
      <form action="igra.php" method="post">
      <button type="submit" name="button">Počni igru iz početka!</button>
      </form>
    </body>
  </html>
  <?php
}

?>
